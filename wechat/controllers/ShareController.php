<?php

	class ShareController extends BaseController{

		public function indexAction(){

            $objRealtor = new Realtor();   //经纪人

            /*判断是否微信跳转到该页面*/
            if(isset($_GET['code']) && !empty($_GET['code']))
            {
                $mixedResult = Api::getInstance()->getOpenIdByCode($_GET['code']);

                if($mixedResult !== false)
                {
                    /*根据openid到数据库查询经纪人是否已经绑定了微信账号*/
                    $arrWechatBroker = RealtorWeixin::getInstance()->getBrokerIdByOpenId($mixedResult)->toArray();

                    if(count($arrWechatBroker) == 0 || $arrWechatBroker['status'] == RealtorWeixin::STATUS_UNBIND)
                    {
                        /*没有绑定跳转到登录页面*/
                        $strLoginUrl = _API_DOMAIN_ . '/login?openid=' . $mixedResult . '&token=' . md5($mixedResult . WEIXIN_TOKEN);
                        $showMsg = '嗷嗷嗷，亲，小焦发现你还没有登录焦点通哦，<a href="' . $strLoginUrl . '">点击这里登录</a>，贴心服务马上就来';

                        $this->view->setVar("showMsg", $showMsg);
                        $this->renderurl('login','nologin');
                        exit;
                    }
                    else
                    {
                        /*获取经纪人信息*/
                        $aryBrokerInfo = $objRealtor->getRealtorById($arrWechatBroker['realId']);
                        if($aryBrokerInfo != false)
                        {
                            $aryBrokerInfo = $aryBrokerInfo->toArray();
                        }

                        if(count($aryBrokerInfo) >0 && in_array($aryBrokerInfo['status'], array(Realtor::STATUS_OPEN, Realtor::STATUS_FREE)))
                        {
                            /*获取城市配置信息*/
                            $arrCity = CCity::getAllCity($aryBrokerInfo['cityId']);

                            //存Cookie信息
                            $arrSaveInfo = array(
                                "real_id"         =>      $aryBrokerInfo['id'],
                                "cityId"           =>      isset($arrRealtor['cityId'])?$arrRealtor['cityId']:0,
                                "cityName"         =>      isset($arrCity['name'])? $arrCity['name']:null ,
                                "cityCode"       =>      isset($arrCity['code'])?$arrCity['code']:null,
                                "cityPinyin"       =>      isset($arrCity['pinyin'])?$arrCity['pinyin']:null,
                                "cityPinyinAbbr"  =>      isset($arrCity['pinyinAbbr'])?$arrCity['pinyinAbbr']:null
                            );

                            /*将所有登录者登录信息 存wechatbrokerinfo，有效期一年*/
                            Utility::setCookie('wechatbrokerinfo', $arrSaveInfo, time() + (365 * 24 * 3600), '/', _API_COOKIE_DOMAIN_);
                        }
                    }
                }
                else
                {
                    $showMsg = '嗷嗷嗷，小焦发现什么出错鸟，再试试或者等会儿吧，小焦会努力做的更好的';
                    $this->view->setVar("showMsg", $showMsg);
                    $this->renderurl('login','nologin');
                    exit;
                }

            }
            else
            {
                $aryWechat = Utility::parseCookie('wechatbrokerinfo');
                if(empty($aryWechat) || empty($aryWechat['real_id']))
                {
                    /*跳转到微信接口获取openid*/
                    $strWechatApiUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WEIXIN_APPID.'&redirect_uri='. urlencode(_API_DOMAIN_ . $_SERVER['REQUEST_URI']) . '&response_type=code&scope=snsapi_base&state=qa34rfvhy#wechat_redirect';
                    header('Location:' . $strWechatApiUrl);
                    exit;
                }
                else
                {
                    /*获取经纪人信息*/
                    //$aryWechat['broker_id'] = '139682';
                    $aryBrokerInfo = array();
                    $aryBrokerInfo = $objRealtor->getRealtorById($aryWechat['real_id']);
                    if($aryBrokerInfo != false)
                    {
                        $aryBrokerInfo = $aryBrokerInfo->toArray();
                    }
                }
            }

            //经纪人无效
            if(count($aryBrokerInfo) == 0 || (!in_array($aryBrokerInfo['status'], array(Realtor::STATUS_OPEN, Realtor::STATUS_FREE)) && strpos($_SERVER['REQUEST_URI'], 'changeuser') === false))
            {
                $showMsg = '嗷嗷嗷，亲，小焦发现你还没有开通端口，功能暂时不可用哦';
                $this->view->setVar("showMsg", $showMsg);
                $this->renderurl('login','nologin');
                exit;
            }
            else
            {
                if(empty($aryWechat['cityPinyin']) && empty($aryWechat['cityPinyinAbbr']))
                {
                    //加载城市配置
                    $arrCity = CCity::getAllCity($aryBrokerInfo['cityId']);
                    $GLOBALS['CITY_PY_ABBR'] 		  = $arrCity['pinyinAbbr'];
                    $GLOBALS['client']['cityPinyin'] = $arrCity['pinyinAbbr'];
                }
                else
                {
                    $GLOBALS['CITY_PY_ABBR'] 		  = $aryWechat['cityPinyinAbbr'];
                    $GLOBALS['client']['cityPinyin'] = $aryWechat['cityPinyin'];
                }
                $GLOBALS['client']['cityId']   = $aryBrokerInfo['cityId'];
                $GLOBALS['client']['real_id'] = $aryBrokerInfo['id'];
            }

             //记录日志
             $arrLog = array();
             $arrLog['realId'] = $aryBrokerInfo['id'];
             $arrLog['logType'] = RealtorLog::LOGTYPE_SHARE;
             RealtorLog::getInstance()->InsertLog($arrLog);


            //端口数据
            $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
            if($arrorderport != false)
            {
                $arrorderport =  $arrorderport->toArray();
            }

            //判断经纪人出租或者出售可发布数量
            $bolShowTab = 0;
            $strUnitType = 'Sale';

            $max_unit_sale = $arrorderport['saleRelease'];   //出售
            $max_unit_rent = $arrorderport['rentRelease'];     //出租

            if($max_unit_sale > 0 && $max_unit_rent > 0)
            {
                $bolShowTab = 1;
            }

            if($max_unit_sale<= 0 && $max_unit_rent > 0)
            {
                $strUnitType = 'Rent';
            }

            //获取房源列表
            if($strUnitType == 'Sale')
            {
                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.price,House.create,House.quality,sale.title,sale.refreshTime,sale.parkName,House.type as houseType';
                $where ='House.type IN (20,21,22) and House.verification > -1 and House.realId = '.$aryBrokerInfo['id'];     //出售
            }
            else
            {
                $columns ='House.id,House.bedRoom,House.livingRoom,House.bA,House.create,House.quality,rent.title,rent.refreshTime,,rent.parkName,House.type as houseType,rent.price as price';
                $where ='House.type IN (10,11) and House.verification > -1 and House.realId = '.$aryBrokerInfo['id'];   //出租
            }

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
                    $arrhouse[$key]['day'] = $arrClick[$val['id']]['today'];
                    $arrhouse[$key]['week'] = $arrClick[$val['id']]['week'];
                    $arrhouse[$key]['month'] = $arrClick[$val['id']]['month'];
                }
            }

            $this->assign(array(
                'page' => 1,
                'pagesize'=>30,
                'intTotal' => $intUnitTotal,
                //'isAllowSelect' => $isAllowSelect,
                //'intBrokerId' => $aryWechat['real_id'],
                'arrUnitList' => $arrhouse,
                'UNIT_BEDROOM'    => $GLOBALS['UNIT_BEDROOM'],
                'UNIT_LIVING_ROOM' => $GLOBALS['UNIT_LIVING_ROOM'],
                //'RENT_PRICE_UNIT' => $GLOBALS['RENT_PRICE_UNIT'],
                //'intTodayZero' => strtotime(date("Y-m-d",$GLOBALS['_NOW_TIME'])),
                'bolShowTab'=> $bolShowTab,
                'unittype' => $strUnitType,
                'city_pinyin_abbr' => $GLOBALS['CITY_PY_ABBR']
            ));

            $this->display('share','index');
		}

        public function  listAction(){
            $this->display('share','list');
        }

		public function notfoundAction(){
            echo "notFound";die();
		}

	}

