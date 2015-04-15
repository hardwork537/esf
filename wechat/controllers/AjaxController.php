<?php

	class AjaxController extends  BaseController{

		public function indexAction(){


            $objRealtor = new Realtor();         //经纪人
            $objRealtorLog = RealtorLog::getInstance();   //微信日志

            $aryBrokerInfo = array();
            $aryWechat = Utility::parseCookie('wechatbrokerinfo');

            if(empty($aryWechat))
            {
                $aryReturn = array('status'=>'-1','url'=>_API_DOMAIN_.'/login');
                echo json_encode($aryReturn);
                exit;
            }

            //$aryWechat['broker_id'] = 139682;

            /*获取经纪人信息*/
            $aryBrokerInfo = $objRealtor->getRealtorById($aryWechat['real_id'])->toArray();

            if(empty($aryBrokerInfo))
            {
                $aryReturn = array('status'=>'-1','url'=>_API_DOMAIN_.'/login');
                echo json_encode($aryReturn);
                exit;
            }
            else
            {
                /*加载城市配置*/
                $arrCity =CCity::getAllCity($aryBrokerInfo['cityId']);

                //require_once WEIXIN_COMMON_CONFIG_DIR . '/' . $arrCity['city_pinyin_abbr'].".db.config.inc.php";

                $GLOBALS['CITY_PY_ABBR'] 		= $arrCity['pinyinAbbr'];
                $GLOBALS['client']['city_id']   = $aryBrokerInfo['cityId'];
                $GLOBALS['client']['broker_id'] = $aryWechat['real_id'];
                $GLOBALS['client']['city_pinyin'] = $arrCity['pinyin'];

                /*判断经纪人端口是否开启*/
                if(empty($aryBrokerInfo) || !in_array($aryBrokerInfo['status'], array(Realtor::STATUS_OPEN, Realtor::STATUS_FREE)))
                {
                    $aryReturn = array('status'=>'-100','content'=>'没有开启端口');
                    echo json_encode($aryReturn);
                    exit;
                }

                $act = $_POST['do'];

                switch($act)
                {
                    case 'flush':
                        $aryUnitId = explode(',', $_POST['unitid']);
                        $mixResult = CWechat::getInstance()->flush($aryUnitId, $aryWechat['real_id'], $_POST['unitype']);
                        if($mixResult === false)
                        {

                        }
                        else
                        {
                            /*记录日志*/
                            $arrLog = array();
                            $arrLog['realId'] = $aryWechat['real_id'];
                            $arrLog['logType'] = RealtorLog::LOGTYPE_FLUSH_SUCCESS;
                            $objRealtorLog->InsertLog($arrLog);

                            $mVipQue = new VipRefreshQueue();
                            $objref = new RefreshLog();
                            //刷新数量
                            $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
                            if($arrorderport != false)
                            {
                                $arrorderport =  $arrorderport->toArray();
                            }
                            if($_POST['unitype'] == 'sale')
                            {
                                $completeRef = $objref->getUsedFlush($aryBrokerInfo['id'],House::TYPE_SALE,date('Y-m-d'),false);
                                $timeRef = $mVipQue->getRefreshCount('realId='.$aryBrokerInfo['id'].' and houseType =2');
                                $mixResult = $arrorderport['saleRefresh'] - ($timeRef + $completeRef);
                            }
                            else
                            {
                                $completeRef = $objref->getUsedFlush($aryBrokerInfo['id'],House::TYPE_RENT,date('Y-m-d'),false);
                                $timeRef = $mVipQue->getRefreshCount('realId='.$aryBrokerInfo['id'].' and houseType =1');
                                $mixResult = $arrorderport['rentRefresh'] - ($timeRef +$completeRef);
                            }
                            $aryReturn = array('status'=>'1','leftNum'=>$mixResult,'freshTime'=>'刷新：' . date('m-d H:i'));
                            echo json_encode($aryReturn);
                            exit;
                        }
                        break;
                    /*获取出租房刷新列表*/
                    case 'getFlushRentList':

                        //端口数据
                        $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
                        if($arrorderport != false)
                        {
                            $arrorderport =  $arrorderport->toArray();
                        }

                        //获取房源列表
                        $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,House.timing,House.verification,rent.title,rent.refreshTime,rent.parkName,rent.price as price,rent.currency,House.type as houseType';
                        $where ='House.type IN (10,11) and House.realId = '.$aryBrokerInfo['id'];   //出租

                        $objhouse = new House();
                        $objhousepic = new HousePicture();
                        $mZebHouse = new ZebHouse();
                        $mVipQue = new VipRefreshQueue();
                        $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                        $intUnitTotal = count($arrhouse);
                        if($intUnitTotal > 0)
                        {
                            foreach($arrhouse as $key=>$val)
                            {
                                $imgcount = $objhousepic->getHousePicCountById($val['id'],HousePicture::STATUS_OK,HousePicture::IMG_SHINEI);
                                $arrhouse[$key]['imgcount'] = $imgcount;
                                $arrhouse[$key]['bA'] = round($val['bA']);
                                $arrhouse[$key]['create'] = date('m-d',strtotime($val['create']));
                                $arrhouse[$key]['refreshTime'] = isset($val['refreshTime'])?date('m-d H:i',strtotime($val['refreshTime'])):'';
                                $xjTime = date('d',strtotime($val['create']) - strtotime($val['refreshTime']));
                                if($xjTime == '06')
                                {
                                    $arrhouse[$key]['refreshTime'] ='';
                                }
                                $arrhouse[$key]['lastflush'] = date('Y-m-d',strtotime($val['refreshTime']));
                                $arrClick = $mZebHouse->getUnitClick(array(0=>$val['id']));
                                $arrhouse[$key]['day'] = $arrClick[$val['id']]['yesterday'];
                                $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                                $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                                $timg = $mVipQue->isTimingRefresh(array(0=>$val['id']),$aryBrokerInfo['id']);
                                if(count($timg) > 0)
                                {
                                    $arrhouse[$key]['timing'] =  $timg[$val['id']];
                                }
                                else
                                {
                                    $arrhouse[$key]['timing'] = 0;
                                }
                                $arrhouse[$key]['currency'] = isset($GLOBALS['RENT_PRICE_UNIT'][$val['currency']])? $GLOBALS['RENT_PRICE_UNIT'][$val['currency']]: '';
                            }
                        }


                        $objref = new RefreshLog();
                        $completeRef = $objref->getUsedFlush($aryBrokerInfo['id'],House::TYPE_RENT,date('Y-m-d'),false);
                        $timeRef = $mVipQue->getRefreshCount('realId='.$aryBrokerInfo['id'].' and houseType =1');
                        $maxRef = $arrorderport['rentRefresh'] - ($timeRef + $completeRef);
                        $maxRef = $maxRef < 1 ? 0 : $maxRef;

                        $nowtime = time();
                        $this->assign(array(
                            'page' => 1,
                            'pagesize'=>30,
                            'intTotal' => $intUnitTotal,
                            //'isAllowSelect' => $isAllowSelect,
                            'completeRef'=>$completeRef,
                            'timeRef'=>$timeRef,
                            'maxRef'=>$maxRef,
                            'intBrokerId' => $aryWechat['real_id'],
                            'arrUnitList' => $arrhouse,
                            'UNIT_BEDROOM'    => $GLOBALS['UNIT_BEDROOM'],
                            'UNIT_LIVING_ROOM' => $GLOBALS['UNIT_LIVING_ROOM'],
                            //'RENT_PRICE_UNIT' => $RENT_PRICE_UNIT,
                            'intTodayZero' => date("Y-m-d",$nowtime),
                            'unittype' =>'rent',
                            'intNow' => $nowtime,
                            'showClick'=>1
                        ));

                        $this->pick('refresh','main');
                        break;
                    case 'getupdownlist':
                        /*获取上架（下架）房源列表*/
                        $strTopTab = $_POST['toptab'];
                        $strSubTab = $_POST['subtab'];

                        $intOnLineCount = 0;
                        $intOffLineCount =0;
                        //端口数据
                        $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
                        if($arrorderport != false)
                        {
                            $arrorderport =  $arrorderport->toArray();
                        }

                        $objhouse = new House();
                        $objhousepic = new HousePicture();
                        $mZebHouse = new ZebHouse();
                        $mVipQue = new VipRefreshQueue();

                        $arrhouse = array();
                        if($strTopTab == 'sale')
                        {
                            //$unitType = 0;
                            if($strSubTab == 'online')
                            {
                                //已发布出售
                                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.price,House.create,House.quality,House.timing,House.verification,House.xiajia,sale.title,sale.refreshTime,sale.parkName,House.type as houseType';
                                $where ='House.type IN (20,21,22)  and House.realId = '.$aryBrokerInfo['id'];
                                $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                                $intOnLineCount = count($arrhouse);
                                if($intOnLineCount > 0)
                                {
                                    foreach($arrhouse as $key=>$val)
                                    {
                                        $imgcount = $objhousepic->getHousePicCountById($val['id'],HousePicture::STATUS_OK,HousePicture::IMG_SHINEI);
                                        $arrhouse[$key]['imgcount'] = $imgcount;
                                        $arrhouse[$key]['bA'] = round($val['bA']);
                                        $arrhouse[$key]['create'] = date('m-d',strtotime($val['create']));
                                        $arrhouse[$key]['refreshTime'] = date('m-d H:i',strtotime($val['refreshTime']));
                                        $xjTime = date('d',strtotime($val['create']) - strtotime($val['refreshTime']));
                                        if($xjTime == '06')
                                        {
                                            $arrhouse[$key]['refreshTime'] ='';
                                        }
                                        $arrhouse[$key]['lastflush'] = date('Y-m-d',strtotime($val['refreshTime']));
                                        $arrhouse[$key]['xiajiatime'] = date('d',(time()+28*24*3600)-strtotime($val['xiajia']));
                                        $arrClick = $mZebHouse->getUnitClick(array(0=>$val['id']));
                                        $arrhouse[$key]['day'] = $arrClick[$val['id']]['yesterday'];
                                        $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                                        $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                                        $arrhouse[$key]['xiajia'] = date('m-d H:i',strtotime($val['xiajia']));
                                        $timg = $mVipQue->isTimingRefresh(array(0=>$val['id']),$aryBrokerInfo['id']);
                                        if(count($timg) > 0)
                                        {
                                            $arrhouse[$key]['timing'] =  $timg[$val['id']];
                                        }
                                        else
                                        {
                                            $arrhouse[$key]['timing'] = 0;
                                        }
                                    }
                                }
                                //待发布出售
                                $columnsOff ='House.id,House.bedRoom,House.livingRoom,House.bA,House.price,House.create,House.quality,House.timing,House.verification,House.xiajia,sale.title,sale.refreshTime,sale.parkName,House.type as houseType';
                                $whereOff ='House.type IN (20,21,22)  and House.status = '.House::STATUS_OFFLINE.' and House.realId = '.$aryBrokerInfo['id'];
                                $arrHouseOff = $objhouse->getHouseByRealtorCondition($whereOff,$columnsOff,'House.id desc',0,500);
                                $intOffLineCount = count($arrHouseOff);
                            }
                            else
                            {
                                //待发布出售
                                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.price,House.create,House.quality,House.timing,House.verification,House.xiajia,sale.title,sale.refreshTime,sale.parkName,House.type as houseType';
                                $where ='House.type IN (20,21,22)  and House.status = '.House::STATUS_OFFLINE.' and House.realId = '.$aryBrokerInfo['id'];
                                $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                                $intOffLineCount = count($arrhouse);
                                if($intOffLineCount > 0)
                                {
                                    foreach($arrhouse as $key=>$val)
                                    {
                                        $imgcount = $objhousepic->getHousePicCountById($val['id'],HousePicture::STATUS_OK,HousePicture::IMG_SHINEI);
                                        $arrhouse[$key]['imgcount'] = $imgcount;
                                        $arrhouse[$key]['bA'] = round($val['bA']);
                                        $arrhouse[$key]['create'] = date('m-d',strtotime($val['create']));
                                        $arrhouse[$key]['refreshTime'] = date('m-d H:i',strtotime($val['refreshTime']));
                                        $xjTime = date('d',strtotime($val['create']) - strtotime($val['refreshTime']));
                                        if($xjTime == '06')
                                        {
                                            $arrhouse[$key]['refreshTime'] ='';
                                        }
                                        $arrhouse[$key]['lastflush'] = date('Y-m-d',strtotime($val['refreshTime']));
                                        $arrhouse[$key]['xiajiatime'] = date('d',(time()+28*24*3600)-strtotime($val['xiajia']));
                                        $arrClick = $mZebHouse->getUnitClick(array(0=>$val['id']));
                                        $arrhouse[$key]['day'] = $arrClick[$val['id']]['yesterday'];
                                        $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                                        $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                                        $arrhouse[$key]['xiajia'] = date('m-d H:i',strtotime($val['xiajia']));
                                        $timg = $mVipQue->isTimingRefresh(array(0=>$val['id']),$aryBrokerInfo['id']);
                                        if(count($timg) > 0)
                                        {
                                            $arrhouse[$key]['timing'] =  $timg[$val['id']];
                                        }
                                        else
                                        {
                                            $arrhouse[$key]['timing'] = 0;
                                        }
                                    }
                                }
                                //已发布出售
                                $columnsOnline ='House.id,House.bedRoom,House.livingRoom,House.bA,House.price,House.create,House.quality,House.timing,House.verification,House.xiajia,sale.title,sale.refreshTime,sale.parkName,House.type as houseType';
                                $whereOnline ='House.type IN (20,21,22) and House.verification > -1  and House.realId = '.$aryBrokerInfo['id'];     //出售
                                $arrHouseOnline = $objhouse->getHouseByRealtorCondition($whereOnline,$columnsOnline,'House.id desc',0,500);
                                $intOnLineCount = count($arrHouseOnline);
                            }
                        }
                        else if($strTopTab == 'rent')
                        {
                            //$unitType = 1;
                            if($strSubTab == 'online')
                            {
                                //已发布出租
                                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,House.timing,House.verification,House.xiajia,rent.title,rent.refreshTime,rent.parkName,House.type as houseType,rent.price as price';
                                $where ='House.type IN (10,11)  and House.realId = '.$aryBrokerInfo['id'];
                                $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                                $intOnLineCount = count($arrhouse);
                                if($intOnLineCount > 0)
                                {
                                    foreach($arrhouse as $key=>$val)
                                    {
                                        $imgcount = $objhousepic->getHousePicCountById($val['id'],HousePicture::STATUS_OK,HousePicture::IMG_SHINEI);
                                        $arrhouse[$key]['imgcount'] = $imgcount;
                                        $arrhouse[$key]['bA'] = round($val['bA']);
                                        $arrhouse[$key]['create'] = date('m-d',strtotime($val['create']));
                                        $arrhouse[$key]['refreshTime'] = date('m-d H:i',strtotime($val['refreshTime']));
                                        $xjTime = date('d',strtotime($val['create']) - strtotime($val['refreshTime']));
                                        if($xjTime == '06')
                                        {
                                            $arrhouse[$key]['refreshTime'] ='';
                                        }
                                        $arrhouse[$key]['lastflush'] = date('Y-m-d',strtotime($val['refreshTime']));
                                        $arrhouse[$key]['xiajiatime'] = date('d',(time()+28*24*3600)-strtotime($val['xiajia']));
                                        $arrClick = $mZebHouse->getUnitClick(array(0=>$val['id']));
                                        $arrhouse[$key]['day'] = $arrClick[$val['id']]['yesterday'];
                                        $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                                        $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                                        $arrhouse[$key]['xiajia'] = date('m-d H:i',strtotime($val['xiajia']));
                                        $timg = $mVipQue->isTimingRefresh(array(0=>$val['id']),$aryBrokerInfo['id']);
                                        if(count($timg) > 0)
                                        {
                                            $arrhouse[$key]['timing'] =  $timg[$val['id']];
                                        }
                                        else
                                        {
                                            $arrhouse[$key]['timing'] = 0;
                                        }
                                    }
                                }
                                //待发布出租
                                $columnsOff ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,House.timing,House.verification,House.xiajia,rent.title,rent.refreshTime,rent.parkName,House.type as houseType,rent.price as price';
                                $whereOff ='House.type IN (10,11)  and House.status = '.House::STATUS_OFFLINE.' and House.realId = '.$aryBrokerInfo['id'];
                                $arrHouseOff = $objhouse->getHouseByRealtorCondition($whereOff,$columnsOff,'House.id desc',0,500);
                                $intOffLineCount = count($arrHouseOff);
                            }
                            else
                            {
                                //待发布出租
                                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,House.timing,House.verification,House.xiajia,rent.title,rent.refreshTime,rent.parkName,House.type as houseType,rent.price as price';
                                $where ='House.type IN (10,11)  and House.status = '.House::STATUS_OFFLINE.' and House.realId = '.$aryBrokerInfo['id'];
                                $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                                $intOffLineCount = count($arrhouse);
                                if($intOffLineCount > 0)
                                {
                                    foreach($arrhouse as $key=>$val)
                                    {
                                        $imgcount = $objhousepic->getHousePicCountById($val['id'],HousePicture::STATUS_OK,HousePicture::IMG_SHINEI);
                                        $arrhouse[$key]['imgcount'] = $imgcount;
                                        $arrhouse[$key]['bA'] = round($val['bA']);
                                        $arrhouse[$key]['create'] = date('m-d',strtotime($val['create']));
                                        $arrhouse[$key]['refreshTime'] = date('m-d H:i',strtotime($val['refreshTime']));
                                        $xjTime = date('d',strtotime($val['create']) - strtotime($val['refreshTime']));
                                        if($xjTime == '06')
                                        {
                                            $arrhouse[$key]['refreshTime'] ='';
                                        }
                                        $arrhouse[$key]['lastflush'] = date('Y-m-d',strtotime($val['refreshTime']));
                                        $arrhouse[$key]['xiajiatime'] = date('d',(time()+28*24*3600)-strtotime($val['xiajia']));
                                        $arrClick = $mZebHouse->getUnitClick(array(0=>$val['id']));
                                        $arrhouse[$key]['day'] = $arrClick[$val['id']]['yesterday'];
                                        $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                                        $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                                        $arrhouse[$key]['xiajia'] = date('m-d H:i',strtotime($val['xiajia']));
                                        $timg = $mVipQue->isTimingRefresh(array(0=>$val['id']),$aryBrokerInfo['id']);
                                        if(count($timg) > 0)
                                        {
                                            $arrhouse[$key]['timing'] =  $timg[$val['id']];
                                        }
                                        else
                                        {
                                            $arrhouse[$key]['timing'] = 0;
                                        }
                                    }
                                }
                                //已发布出租
                                $columnsOnline ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,House.timing,House.verification,House.xiajia,rent.title,rent.refreshTime,rent.parkName,House.type as houseType,rent.price as price';
                                $whereOnline ='House.type IN (10,11)   and House.realId = '.$aryBrokerInfo['id'];     //出售
                                $arrHouseOnline = $objhouse->getHouseByRealtorCondition($whereOnline,$columnsOnline,'House.id desc',0,500);
                                $intOnLineCount = count($arrHouseOnline);
                            }
                        }

                        //获取剩余上架数量
                        if($strTopTab == 'sale')
                        {
                            $intLeftOnLineNum = $arrorderport['saleRelease']-$intOnLineCount;
                        }
                        else
                        {
                            $intLeftOnLineNum = $arrorderport['rentRelease']-$intOnLineCount;
                        }
                        $intLeftOnLineNum = $intLeftOnLineNum < 1 ? 0 : $intLeftOnLineNum;

                        $reStr = '!#'.$intOnLineCount.'|'.$intOffLineCount.'|'.$intLeftOnLineNum.'#!';
                        $nowtime = time();

                         $arrAssign = array(
                            'page' => 1,
                            'pagesize'=>30,
                            'intOnLineCount' => $intOnLineCount,
                            'intOffLineCount' => $intOffLineCount,
                            'intBrokerId' => $aryWechat['real_id'],
                            'arrUnitList' => $arrhouse,
                            'UNIT_BEDROOM'    => $GLOBALS['UNIT_BEDROOM'],
                            'UNIT_LIVING_ROOM' => $GLOBALS['UNIT_LIVING_ROOM'],
                            //'RENT_PRICE_UNIT' => $RENT_PRICE_UNIT,
                            'intTodayZero' => date("Y-m-d",$nowtime),
                            'intNow' => $nowtime,
                            'unittype'=>$strTopTab,
                            'reStr'=>$reStr,
                        );

                        if($strSubTab == 'offline')
                        {
                            $this->assign($arrAssign);
                            $this->pick('manage','offlinelist');
                        }
                        else
                        {
                            $arrAssign['showClick'] = 1;
                            $this->assign($arrAssign);
                            $this->pick('manage','onlinelist');
                        }

                        //echo '|'.chr(16).'|' . $intOnLineCount . '|' . chr(16) . '|' . $intOffLineCount . '|' . chr(16) . '|' . $intLeftOnLineNum;
                        break;

                    /*获取分享房源列表*/
                    case 'sharelist':
                        $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,rent.title,rent.refreshTime,rent.parkName,House.type as houseType,rent.price as price';
                        $where ='House.type IN (10,11) and House.verification > -1 and House.realId = '.$aryWechat['real_id'];     //高清 -3
                        $objhouse = new House();
                        $objhousepic = new HousePicture();
                        $mZebHouse = new ZebHouse();
                        $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                        $intUnitTotal = count($arrhouse);
                        if($intUnitTotal > 0)
                        {
                            foreach($arrhouse as $key=>$val)
                            {
                                $imgcount = $objhousepic->getHousePicCountById($val['id'],HousePicture::STATUS_OK,HousePicture::IMG_SHINEI);
                                $arrhouse[$key]['imgcount'] = $imgcount;
                                $arrhouse[$key]['bA'] = round($val['bA']);
                                $arrhouse[$key]['create'] = date('m-d',strtotime($val['create']));
                                $arrhouse[$key]['refreshTime'] = date('m-d H:i',strtotime($val['refreshTime']));
                                $arrClick = $mZebHouse->getUnitClick(array(0=>$val['id']));
                                $arrhouse[$key]['day'] = $arrClick[$val['id']]['yesterday'];
                                $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                                $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                            }
                        }


                        $this->assign(array(
                            'page' => 1,
                            'pagesize'=>30,
                            'intTotal' => $intUnitTotal,
                            //'intBrokerId' => $aryWechat['real_id'],
                            'arrUnitList' => $arrhouse,
                            'UNIT_BEDROOM'    => $GLOBALS['UNIT_BEDROOM'],
                            'UNIT_LIVING_ROOM' => $GLOBALS['UNIT_LIVING_ROOM'],
                            //'RENT_PRICE_UNIT' => $GLOBALS['RENT_PRICE_UNIT'],
                            //'intTodayZero' => strtotime(date("Y-m-d",$GLOBALS['_NOW_TIME'])),
                            'unittype' => 'Rent',
                            'city_pinyin_abbr' => $GLOBALS['CITY_PY_ABBR']
                        ));

                        $this->pick('share','list');
                        break;

                    /*上下架房源信息*/
                    case 'unitupdown':
                        $arrUnitIds = explode(',', $_POST['unitids']);
                        if(empty($arrUnitIds) || !isset($_POST['unittype']) || !isset($_POST['acttype']))
                        {
                            echo json_encode(array('status'=>'-2', 'content'=>'非法请求'));
                            exit;
                        }
                        $mixedResult = CWechat::getInstance()->setUnitStatus($arrUnitIds, $aryWechat['real_id'], $_POST['acttype'], $_POST['unittype']);

                        if($mixedResult === false)
                        {
                            echo json_encode(array('status'=>'-2', 'content'=>CWechat::getInstance()->getErrorMsg()));
                        }
                        else
                        {
                            /*记录日志*/
                            $arrLog = array();
                            $arrLog['realId'] = $aryWechat['real_id'];
                            $arrLog['logType'] = RealtorLog::LOGTYPE_UPDOWN_SUCCESS;
                            $objRealtorLog->InsertLog($arrLog);

                            /*获取剩余上架数量*/
                            $arrorderport = RealtorPort::instance()->getAccountByRealId($aryWechat['real_id']);
                            if($arrorderport != false)
                            {
                                $arrorderport =  $arrorderport->toArray();
                            }

                            $intOnLineCount = 0;
                            $objhouse = new House();
                            if($_POST['unittype'] == 'sale')
                            {
                                //已发布出售
                                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.price,House.create,House.quality,House.timing,House.verification,House.xiajia,sale.title,sale.refreshTime,sale.parkName,House.type as houseType';
                                $where ='House.type IN (20,21,22)  and House.realId = '.$aryWechat['real_id'];
                                $arrhouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,500);
                                $intOnLineCount = count($arrhouse);
                                $intLeftOnLineNum = $arrorderport['saleRelease']- $intOnLineCount;
                            }
                            else
                            {
                                //已发布出租
                                $columnsOnline ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,House.timing,House.verification,House.xiajia,rent.title,rent.refreshTime,rent.parkName,House.type as houseType,rent.price as price';
                                $whereOnline ='House.type IN (10,11)   and House.realId = '.$aryWechat['real_id'];     //出售
                                $arrHouseOnline = $objhouse->getHouseByRealtorCondition($whereOnline,$columnsOnline,'House.id desc',0,500);
                                $intOnLineCount = count($arrHouseOnline);
                                $intLeftOnLineNum = $arrorderport['rentRelease'] - $intOnLineCount;
                            }
                            $intLeftOnLineNum = $intLeftOnLineNum < 1 ? 0 : $intLeftOnLineNum;

                            echo json_encode(array('status'=>'1', 'successNum'=>$mixedResult['successNum'], 'leftOnlineNum'=>$intLeftOnLineNum,'successIds'=>$mixedResult['successIds']));
                        }
                        break;

                    /*分享房源时记录日志信息*/
                    case 'share':
                        $arrLog = array();
                        $arrLog['realId'] = $aryWechat['real_id'];
                        $arrLog['logType'] = RealtorLog::LOGTYPE_SHARE_CLICK;
                        $objRealtorLog->InsertLog($arrLog);
                        echo json_encode(array('status'=>'1', 'content'=>''));
                        break;
                }
            }

		}


		public function notfoundAction(){
            echo "notFound";die();
		}

	}



