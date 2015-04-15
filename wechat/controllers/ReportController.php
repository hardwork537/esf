<?php

	class ReportController extends BaseController{

		public function indexAction(){

            $objRealtor = new Realtor();         //经纪人
            $objarea = new Area();     //区域

            /*判断是否微信跳转到该页面*/
            if(isset($_GET['code']) && !empty($_GET['code']))
            {
                $mixedResult = WechatAPI::getInstance()->getOpenIdByCode($_GET['code']);

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

            /*记录日志*/
            $arrLog = array();
            $arrLog['realId'] = $aryBrokerInfo['id'];
            $arrLog['logType'] = RealtorLog::LOGTYPE_REPROT;
            RealtorLog::getInstance()->InsertLog($arrLog);

            $showTips = 0;
            $intNowTime = strtotime(date('Y-m-d'));
            if(isset($_COOKIE['showtime']))
            {
                $showLastTime = $_COOKIE['showtime'];
            }
            if(empty($showLastTime) || ($intNowTime - $showLastTime) < (14 * 86400))
            {
                $showTips = 1;
            }
            if(empty($showLastTime))
            {
                setcookie('showtime', $intNowTime, 10 * 365 * 24 * 3600, '/', _API_COOKIE_DOMAIN_);
            }

            //端口数据
            $arrorderport = OrderPort::instance()->getAccountByopToId(VipAccount::ROLE_REALTOR,$aryBrokerInfo['id']);
            if($arrorderport != false)
            {
                $arrorderport =  $arrorderport->toArray();
            }
            $arrportcity = PortCity::instance()->getPortById($arrorderport['portId']);
            if($arrportcity != false)
            {
                $arrportcity =  $arrportcity->toArray();
            }
            //出售-出租端口数量
            $portType = $arrportcity['type'];
            $max_unit_sale = 0;
            $max_unit_rent = 0;
            if($portType == PortCity::STATUS_Sale)
            {
                $max_unit_sale = $arrorderport['num'];   //出售
            }
            else if($portType == PortCity::STATUS_Rent)
            {
                $max_unit_rent = $arrorderport['num'];     //出租
            }

            $objhouse = new House();

            $arrUnitInfo = array();
            if($max_unit_sale <= 0)
            {
                $this->assign(array(
                    'nopower' => 1,
                    'showRefreshPie'=>0
                ));
                $this->display('report','index');
            }
            else
            {
                /*获取高清房源数*/
                $columns ='House.id,House.parkId';
                $where ='House.type IN (21,22) and House.verification =1 and House.quality =3 and House.realId = '.$aryBrokerInfo['id'];     //高清 -3
                $aryHouse = $objhouse->getHouseByRealtorSaleRent($where,$columns,'House.id desc',0,200);
                $intGoodUnitNum = count($aryHouse);

                /*获取总房源数*/
                $bolShowPie = 0;
                $intUnitNum = $objhouse->getRealtorHouseTotal($aryBrokerInfo['id'],'Sale');
                $strTipsMsg ='';

                if($intUnitNum == 0)
                {
                    $bolShowPie = 0;
                }
                else
                {
                    $bolShowPie = 1;
                    if($intGoodUnitNum < $intUnitNum)
                    {
                        $arrBrokerGoodUnitInfo = array();
                        $arrBrokerGoodUnitInfo[$aryWechat['real_id']] = $intGoodUnitNum;

                        /*获取同区域的其他经纪人*/
                        $intHotAreaId = $aryBrokerInfo['areaId'];
                        $arrBrokerIds = $objRealtor->getRealtorAreaIds($aryBrokerInfo['areaId']);

                        if(count($arrBrokerIds) > 0)
                        {
                            $intBrokerCount = count($arrBrokerIds);
                            for($i = 0; $i < $intBrokerCount; $i++)
                            {
                                $columns ='House.id,House.parkId';
                                $where ='House.type IN (21,22) and House.verification =1 and House.quality =3 and House.realId = '.$arrBrokerIds[$i]['id'];     //高清 -3
                                $aryHouseQuality = $objhouse->getHouseByRealtorSaleRent($where,$columns,'House.id desc',0,200);
                                $arrBrokerGoodUnitInfo[$arrBrokerIds[$i]['id']] =count($aryHouseQuality);
                            }
                            asort($arrBrokerGoodUnitInfo);
                            $intBrokerOrder = 0;
                            foreach($arrBrokerGoodUnitInfo as $key=>$value)
                            {
                                $intBrokerOrder++;
                                if($key == $aryWechat['real_id'])
                                {
                                    break;
                                }
                            }
                            /*获取板块名称*/
                            $arrArea = $objarea->getAreaById($intHotAreaId);
                            $strHotAreaName = $arrArea['name'];

                            if($intBrokerOrder == $intBrokerCount)
                            {
                                $strTipsMsg = '你的高清房源有<i>'.$arrBrokerGoodUnitInfo[$aryWechat['real_id']].'</i>套，哎呀，'.$strHotAreaName.'板块有<i>'.($intBrokerCount-1).'</i>个经纪人在追赶你，发布高清房源，继续保持领先~';
                            }
                            else
                            {
                                $strTipsMsg = '你的高清房源有<i>'.$arrBrokerGoodUnitInfo[$aryWechat['real_id']].'</i>套，哎呀，落后于' . $strHotAreaName . '板块的<i>'.($intBrokerCount - $intBrokerOrder).'</i>个经纪人呢，赶紧把房源高清上去，抢占客户吧~';
                            }
                        }
                    }
                    else
                    {
                        $strTipsMsg = '哇！全部是高清房源，赞啊！请继续保持~';
                    }

                    $arrUnitInfo = array();
                    $arrUnitInfo['good'] = sprintf('%0.2f', ($intGoodUnitNum / $intUnitNum) * 100);
                    $arrUnitInfo['nogood'] = 100 - $arrUnitInfo['good'];
                    $arrUnitInfo['nogood'] = sprintf('%0.2f', $arrUnitInfo['nogood']);
                    $arrUnitInfo['msg'] = $strTipsMsg;
                }


                /*获取最近7天的房源点击量数据*/
                $strClickMsg = '';
                $arrUnitClickTotal = array();
                $arrUnitClickTotal['good'] = $intGoodUnitNum;
                $arrUnitClickTotal['total'] = $intUnitNum;

                $arrUnitClickDetail = array();

                //$arrUnitClickTotal  = CUnitMould::getInstance()->getUnitTotalHigh($aryWechat['real_id']);    //待处理
                //$arrUnitClickDetail = CUnitMould::getInstance()->getUnitMouldInfoHigh($aryWechat['real_id'], 2);    //待处理

                if($arrUnitClickTotal['good'] < ($arrUnitClickTotal['total'] * 0.8))
                {
                    $intMaxNum = 0;
                    foreach($arrUnitClickDetail as $day => $clickInfo)
                    {
                        if($clickInfo['good']['average'] >  $clickInfo['nogood']['average'])
                        {
                            $intMaxNum++;
                        }
                    }
                    if($intMaxNum > 4)
                    {
                        $strClickMsg = '哇！高清房源点击量更高，赶紧去电脑上发布更多高清房源吧';
                    }
                    else
                    {
                        $strClickMsg = '你的高清房源平均点击居然比普通房源还要低，不应该呀，是你的房源还不够真实的吧，亲，赶紧去电脑上修改吧~';
                    }
                }
                else
                {
                    /*获取同一板块的所有经纪人的高清房源当天的点击量*/
                    $intOrader = 0;
                    $arrGoodUnitClickTotal = CUnitMould::getInstance()->getAreaUnitInfoHigh($aryBrokerInfo['hot_area_id']);   //待处理
                    foreach($arrGoodUnitClickTotal as $key => $value)
                    {
                        $intOrader++;
                        if($key == $aryWechat['real_id'])
                        {
                            break;
                        }
                    }

                    /*获取板块名称*/
                    $arrArea = $objarea->getAreaById($aryBrokerInfo['areaId']);
                    $strHotAreaName = $arrArea['name'];

                    $intNowHour = date('G');
                    $strTmp ='';
                    if($intNowHour < 9 )
                    {
                        $strTmp = '9到11';
                    }
                    else if($intNowHour < 14)
                    {
                        $strTmp = '14到18';
                    }
                    else if($intNowHour < 20)
                    {
                        $strTmp = '20到22';
                    }
                    else if($intNowHour >= 20)
                    {
                        $strTmp = '明天9到11';
                    }

                    $intTotal = count($arrGoodUnitClickTotal);
                    $intpercentage = 0;
                    if($intOrader > $intTotal * 0.5)
                    {
                        $intpercentage = sprintf("%0.2f", ($intOrader / $intTotal) * 100);
                        $strClickMsg = '你的高清房源点击量在' . $strHotAreaName . '打败了<i>' . $intpercentage . '%</i>的经纪人，下一波高峰期在'.$strTmp.'点，别被反超了';
                    }
                    else
                    {
                        $intpercentage = sprintf("%0.2f",(($intTotal - $intOrader) / $intTotal) * 100);
                        $strClickMsg = $strHotAreaName . '<i>' . $intpercentage . '%</i>的经纪人的高清点击量比你高，下一波高峰期在'.$strTmp.'点，请抢占时机刷新击败他们';
                    }
                }
                $arrUnitClickInfo = array();
                foreach($arrUnitClickDetail as $day => $value)
                {
                    $arrUnitClickInfo['day'] .= '"' .$day . '",';
                    $arrUnitClickInfo['good'] .= $value['good']['average'] . ',';
                    $arrUnitClickInfo['nogood'] .= $value['nogood']['average'] . ',';
                }

                $arrUnitClickInfo['day'] = substr($arrUnitClickInfo['day'], 0, -1);
                $arrUnitClickInfo['good'] = substr($arrUnitClickInfo['good'], 0, -1);
                $arrUnitClickInfo['nogood'] = substr($arrUnitClickInfo['nogood'], 0, -1);
                $arrUnitClickInfo['msg'] = $strClickMsg;
                unset($arrUnitClickDetail, $arrUnitClickTotal);


                die();
                /*获取最近7天房源刷新量*/
                $arrUnitRefreshInfo = array();
                $strRereshMsg = '';
                $arrUnitRefreshData = CUnitMould::getInstance()->getUnitMouldInfoHigh($aryWechat['real_id'], 1);     //待处理
                $intMaxNum = 0;
                foreach($arrUnitRefreshData as $day => $refreshInfo)
                {
                    $arrUnitRefreshInfo['day'] .= '"' .$day . '",';
                    $arrUnitRefreshInfo['good'] .= $refreshInfo['good']['total'] . ',';
                    $arrUnitRefreshInfo['nogood'] .= $refreshInfo['nogood']['total'] . ',';

                    if($refreshInfo['good']['total'] >  $refreshInfo['nogood']['total'])
                    {
                        $intMaxNum++;
                    }
                    if($intMaxNum >= 4)
                    {
                        /*获取当日房源刷新数量*/
                        $arrDayRefresh = CUnitMould::getInstance()->getTodayRefeshCountHigh($aryWechat['real_id']);      //待处理
                        if($arrDayRefresh['good'] < $arrDayRefresh['total'] * 0.8)
                        {
                            $strRereshMsg = '小焦建议把刷新量更多用在高清房源上，排名更靠前，获得更多点击和电话';
                        }
                        else
                        {
                            $strRereshMsg = '很好！刷新大部分都用在高清房源上了，请合理分配刷新数和刷新时段，让房源排在更前面~';
                        }
                    }else
                    {
                        $strRereshMsg = '小焦建议把刷新量更多用在高清房源上，排名更靠前，获得更多点击和电话';
                    }
                }

                /*检查经纪人是否还有剩余刷新量，如果有，显示刷新link*/
                $intSurplusFlush =  MSale::Instance()->getLeftFlushByBroker($GLOBALS['client']['broker_id'],false,true);    //待处理
                if($intSurplusFlush > 0)
                {
                    $strRereshMsg .= '<br><a href="' . _API_DOMAIN_ . '/refresh" style="text-decoration:none;">去刷新房源 >></a>';
                }
                $arrUnitRefreshInfo['day'] = substr($arrUnitRefreshInfo['day'], 0, -1);
                $arrUnitRefreshInfo['good'] = substr($arrUnitRefreshInfo['good'], 0 , -1);
                $arrUnitRefreshInfo['nogood'] = substr($arrUnitRefreshInfo['nogood'], 0 , -1);
                $arrUnitRefreshInfo['msg'] = $strRereshMsg;

                unset($arrUnitRefreshData);

                $this->assign(array(
                    'unitInfo' => $arrUnitInfo,
                    'unitClickInfo' => $arrUnitClickInfo,
                    'unitRefreshInfo' => $arrUnitRefreshInfo,
                    'showTips'=> $showTips,
                    'showRefreshPie'=>$bolShowPie
                ));
                $this->display('report','index');

            }

		}

		public function notfoundAction(){
            echo "notFound";die();
		}

	}
