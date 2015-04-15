<?php

    error_reporting(0);
	class IndexController extends BaseController{

		public function indexAction(){

            $objWechat = Api::getInstance();  //微信API
            $objRealtor = new Realtor();   //经纪人
            $objhouse = new House();       //房源
            $objref = new RefreshLog();    //刷新日志

            /*检查请求是否来源于微信服务端，非法请求终止程序执行，不做任何响应*/
            if($objWechat->checkSignature())
            {
                $echoStr = $_GET["echostr"];
                echo $echoStr;
                //exit;
            }

            //error_reporting(E_ALL);

            /*解析微信服务器请求数据包（POST），解析失败终止程序执行*/
            $objPostData = $objWechat->parseRequestMsg();

            if($objPostData === false)
            {
                exit;
            }
            $strToUserName 	  = $objPostData->ToUserName;
            $strFromUserName  = $objPostData->FromUserName;
            $strCreateTime    = $objPostData->CreateTime;
            $strMsgType       = $objPostData->MsgType;

            /*初始化文本消息响应内容数组*/
            $aryResponseInfo = array();
            $aryResponseInfo['ToUserName'] = $strFromUserName;
            $aryResponseInfo['FromUserName'] = $strToUserName;
            $aryResponseInfo['Content'] = '';

            switch ($strMsgType)
            {
                /*处理事件推送消息（即菜单点击事件）*/
                case 'event':
                    $strEvent 		  = $objPostData->Event;

                    /*处理点击事件，即定义click类型的菜单被点击时的事件*/
                    if($strEvent == 'CLICK')
                    {
                        $strEventKey      = $objPostData->EventKey;

                        /*根据openid获取经纪人的broker_id*/
                        $objMwechatBroker = RealtorWeixin::getInstance();
                        $aryBrokerId = $objMwechatBroker->getBrokerIdByOpenId($strFromUserName)->toArray();

                        /*提醒用户绑定微信账号*/
                        if(empty($aryBrokerId) || $aryBrokerId['status'] != RealtorWeixin::STATUS_BIND)
                        {
                            $strLoginUrl = _API_DOMAIN_.'/login?openid=' . $strFromUserName . '&token=' . md5($strFromUserName . WEIXIN_TOKEN);
                            $aryResponseInfo['Content'] = '嗷嗷嗷，亲，小焦发现你还没有登录焦点通哦，<a href="' . $strLoginUrl . '">点击这里登录</a>，贴心服务马上就来。';
                            echo $objWechat->createTextResponseMsg($aryResponseInfo);
                            exit;
                        }
                        else
                        {
                            $strUnitType = '';
                            /*获取经纪人信息*/
                            $aryBrokerInfo = $objRealtor->getRealtorById($aryBrokerId['realId'])->toArray();

                            /*经纪人无效*/
                            if(empty($aryBrokerInfo))
                            {
                                $strLoginUrl =_API_DOMAIN_. '/login?openid=' . $strFromUserName . '&token=' . md5($strFromUserName . WEIXIN_TOKEN);
                                $aryResponseInfo['Content'] = '嗷嗷嗷，亲，小焦发现你还没有登录焦点通哦，<a href="' . $strLoginUrl . '">点击这里登录</a>，贴心服务马上就来。';
                                echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                exit;
                            }
                            else if(!in_array($aryBrokerInfo['status'], array(Realtor::STATUS_OPEN, Realtor::STATUS_FREE)))
                            {
                                $aryResponseInfo['Content'] = '嗷嗷嗷，亲，小焦发现你还没有开通端口，功能暂时不可用哦。';
                                echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                exit;
                            }
                            else
                            {
                                $strUnitType = 'sale';
                                $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
                                if($arrorderport != false)
                                {
                                    $arrorderport =  $arrorderport->toArray();
                                }

                                //判断经纪人出租或者出售可发布数量
                                $max_unit_sale = $arrorderport['saleRelease'];   //出售
                                $max_unit_rent = $arrorderport['rentRelease'];     //出租
                                if($max_unit_sale<= 0 && $max_unit_rent > 0)
                                {
                                    $strUnitType = 'rent';
                                }

                            }

                            switch ($strEventKey)
                            {
                                /*一键刷新高清房源*/
                                case 'Quickly_Refresh_High_Quality_House':

                                    /*记录日志*/
                                    $arrLog = array();
                                    $arrLog['realId'] = $aryBrokerId['realId'];
                                    $arrLog['logType'] = RealtorLog::LOGTYPE_FLUSH_GOOD;
                                    RealtorLog::getInstance()->InsertLog($arrLog);

                                    /*目前只有出售端口(免费也可以刷，刷出售房源)才能使用该功能（出售端口，刷新出售房源，出租端口，刷新出租房源。目前出租房源没有“高清”）*/
                                    if($strUnitType == 'rent')
                                    {
                                        $aryResponseInfo['Content'] = '嗷嗷嗷，此功能暂时只对出售端口类型的经纪人开放哦';
                                        echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                        exit;
                                    }

                                    /*获取剩余刷新数量*/
                                    $objref = new RefreshLog();
                                    if($strUnitType == 'sale')
                                    {
                                        $completeRef = $objref->getUsedFlush($aryBrokerId['realId'],'Sale',date('Y-m-d'),false);
                                        $timeRef = 0;
                                        $intLeftFlushNum = $arrorderport['saleRefresh'] - ($timeRef + $completeRef);
                                    }
                                    else
                                    {
                                        $completeRef = $objref->getUsedFlush($aryBrokerId['realId'],'Rent',date('Y-m-d'),false);
                                        $timeRef = 0;
                                        $intLeftFlushNum = $arrorderport['rentRefresh'] - ($timeRef + $completeRef);
                                    }
                                    $intLeftFlushNum = $intLeftFlushNum < 1 ? 0 : $intLeftFlushNum;

                                    /*获取高清房源*/
                                    $columns ='House.id';
                                    $where ='House.type IN (21,22) and House.verification =1 and House.quality =3 and House.realId = '.$aryBrokerId['realId'];
                                    $aryHouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,50);
                                    $aryUnitIds = array();
                                    foreach($aryHouse as $rows)
                                    {
                                        $aryUnitIds[] = $rows['id'];
                                    }
                                    $intGoodUnitNum = count($aryUnitIds);

                                    /*没有高清房源*/
                                    if($intGoodUnitNum == 0)
                                    {
                                        $aryResponseInfo['Content'] = '嗷嗷嗷 你还木有高清房源呢，高清房源的点击量更高，亲，赶紧去电脑上发布高清房源吧。';
                                        echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                        exit;
                                    }

                                    /*没有剩余刷新数*/
                                    if($intLeftFlushNum == 0)
                                    {
                                        $aryResponseInfo['Content'] = '嗷嗷嗷 你的可用手动刷新数已经木有了，去电脑上取消些定时刷新 或者 明天再来吧。';
                                        echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                        exit;
                                    }

                                    /*高清房源数 >= 剩余刷新数*/
                                    if($intGoodUnitNum >= $intLeftFlushNum)
                                    {
                                        $aryUnitIds = array_slice($aryUnitIds, 0, $intLeftFlushNum);
                                        $aryResponseInfo['Content'] = '小焦成功为你刷新' . $intLeftFlushNum . '套高清房源，今日手动刷新数已用完，坐等电话吧。';
                                    }
                                    else
                                    {
                                        /*计算下一个刷新高峰期*/
                                        $aryHour = array(9,11,14,18,20,22);
                                        $intCount = count($aryHour);
                                        $intNowHour = date('G');
                                        if($intNowHour < 9 )
                                        {
                                            $strTmp = '9-11';
                                        }
                                        else if($intNowHour < 14)
                                        {
                                            $strTmp = '14-18';
                                        }
                                        else if($intNowHour < 20)
                                        {
                                            $strTmp = '20-22';
                                        }
                                        else if($intNowHour >= 20)
                                        {
                                            $strTmp = '明天9-11';
                                        }
                                        /*剩余刷新数 > 优质房源数*/
                                        $aryResponseInfo['Content'] = '小焦成功为你刷新' . $intGoodUnitNum . '套高清房源，剩余' . ($intLeftFlushNum - $intGoodUnitNum) . '次可刷，小焦建议你合理使用刷新和刷新时段，下一个高峰时段是' .$strTmp . '点。';
                                    }

                                    /*刷新房源*/
                                    $bolResult = CWechat::getInstance()->flush($aryUnitIds, $aryBrokerId['realId'], $strUnitType);
                                    /*刷新成功*/
                                    if($bolResult === false)
                                    {
                                        $intErrorNo = CWechat::getInstance()->getErrorNo();
                                        switch ($intErrorNo)
                                        {
                                            case '-3':
                                                $aryResponseInfo['Content'] = '嗷嗷嗷 亲，请不要再0点--3点刷新。';
                                                break;
                                            case '-2':
                                                $aryResponseInfo['Content'] = '嗷嗷嗷 亲，您没有操作权限。';
                                                break;
                                            default:
                                                $aryResponseInfo['Content'] = '嗷嗷嗷，小焦发现什么出错鸟，再试试或者等会儿吧，小焦会努力做的更好的。';

                                        }
                                    }

                                    echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                    break;

                                /*一键下架违规房源*/
                                case 'Quickly_OffLine_Illegal_House':

                                    /*记录日志*/
                                    $arrLog = array();
                                    $arrLog['realId'] = $aryBrokerId['realId'];
                                    $arrLog['logType'] = RealtorLog::LOGTYPE_DOWN_WEIGUI;
                                    RealtorLog::getInstance()->InsertLog($arrLog);

                                    //获取违规房源
                                    $columns ='House.id';
                                    $where ='House.type IN (21,22,11,10) and House.verification =-1 and House.realId = '.$aryBrokerId['realId'];
                                    $aryHouse = $objhouse->getHouseByRealtorCondition($where,$columns,'House.id desc',0,50);
                                    $aryUnitIds = array();
                                    foreach($aryHouse as $rows)
                                    {
                                        $aryUnitIds[] = $rows['id'];
                                    }
                                    $intWeiGuiUnitNum = count($aryUnitIds);

                                    if($intWeiGuiUnitNum == 0)
                                    {
                                        $aryResponseInfo['Content'] = '亲，你当前没有违规房源哦，请继续保持~';
                                    }
                                    else
                                    {
                                        /*下架房源*/
                                        $mixedResult = CWechat::getInstance()->setUnitStatus($aryUnitIds, $aryBrokerId['realId'], 'offline', $strUnitType);
                                        if($mixedResult === false)
                                        {
                                            $aryResponseInfo['Content'] = '嗷嗷嗷，小焦发现什么出错鸟，再试试或者等会儿吧，小焦会努力做的更好的。';
                                        }
                                        else
                                        {
                                            $aryResponseInfo['Content'] = '亲，小焦成功为你下架' . $mixedResult['successNum'] . '套违规房源，有时间赶紧去电脑上修正这些房源哦，诚信经纪人从我做起~';
                                        }
                                    }

                                    echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                    break;

                                /*检查是否达标*/
                                case 'Check_Reach_Standard':

                                    /*记录日志*/
                                    $arrLog = array();
                                    $arrLog['realId'] = $aryBrokerId['realId'];
                                    $arrLog['logType'] = RealtorLog::LOGTYPE_CHECK_DABIAO;
                                    RealtorLog::getInstance()->InsertLog($arrLog);

                                    $aryObjectData = array();   //达标数据
                                    $aryFinishData = array();   //已完成数据

                                    /*判断经纪人是否设置了短信提醒*/
                                    $objvipsms = new VipSmsRemind();
                                    $arybrokerRemind = $objvipsms->getSmsByRealtorId($aryBrokerId['realId']);

                                    /*开启短信提示的需要将出售和出租的数量叠加*/
                                    if($strUnitType == 'sale')
                                    {
                                        $already_online =$objhouse->getRealtorHouseTotal($aryBrokerId['realId'],'Sale');  /*已经发布总数*/
                                        $already_flush =$objref->getUsedFlush($aryBrokerId['realId'],'Sale',date('Y-m-d'),false);  /*当天已经刷新的总数*/
                                        $already_flag = $objhouse->getRealtorHouseTagTotal($aryBrokerId['realId'],'Sale');   /*已经设置的标签数*/
                                        $already_recommend = $objhouse->getRealtorHouseFineTotal($aryBrokerId['realId'],'Sale');  /* （推荐/精品）房源数*/
                                        $already_new =$objhouse->getRealtorHouseNewTotal($aryBrokerId['realId'],'Sale');   /*当天新增房源数*/
                                    }
                                    else
                                    {
                                        $already_online =$objhouse->getRealtorHouseTotal($aryBrokerId['realId'],'Rent');  /*已经发布总数*/
                                        $already_flush =$objref->getUsedFlush($aryBrokerId['realId'],'Rent',date('Y-m-d'),false);  /*当天已经刷新的总数*/
                                        $already_flag = $objhouse->getRealtorHouseTagTotal($aryBrokerId['realId'],'Rent');       /*已经设置的标签数*/
                                        $already_recommend = $objhouse->getRealtorHouseFineTotal($aryBrokerId['realId'],'Rent');  /* （推荐/精品）房源数*/
                                        $already_new = $objhouse->getRealtorHouseNewTotal($aryBrokerId['realId'],'Rent');        /*当天新增房源数*/
                                    }

                                    $aryFinishData['rateCount'] = $already_online;
                                    $aryFinishData['rateFlush'] = $already_flush;
                                    $aryFinishData['rateFlag'] = $already_flag;
                                    $aryFinishData['rateRecommend'] = $already_recommend;
                                    $aryFinishData['rateNew'] = $already_new;

                                    /*没有设置短信提醒*/
                                    if(empty($arybrokerRemind['id']))
                                    {
                                        if($strUnitType == 'sale')
                                        {
                                            $aryObjectData['rateCount'] = $arrorderport['saleRelease'];
                                            $aryObjectData['rateFlush'] = $arrorderport['saleRefresh'];
                                            $aryObjectData['rateFlag'] = $arrorderport['saleTags'];
                                            $aryObjectData['rateRecommend'] = $arrorderport['saleBold'];
                                        }
                                        else
                                        {
                                            $aryObjectData['rateCount'] = $arrorderport['rentRelease'];
                                            $aryObjectData['rateFlush'] = $arrorderport['rentRefresh'];
                                            $aryObjectData['rateFlag'] = $arrorderport['rentTags'];
                                            $aryObjectData['rateRecommend'] = $arrorderport['rentBold'];
                                        }
                                        $aryObjectData['rateNew'] = '2';

                                    }
                                    else
                                    {
                                        $aryObjectData['rateCount'] = $arybrokerRemind['count'];
                                        $aryObjectData['rateFlush'] = $arybrokerRemind['flush'];
                                        $aryObjectData['rateFlag'] = $arybrokerRemind['flag'];
                                        $aryObjectData['rateRecommend'] = $arybrokerRemind['recommend'];
                                        $aryObjectData['rateNew'] = $arybrokerRemind['new'];

                                        if($arybrokerRemind['park'] > 0)
                                        {
                                            $aryObjectData['ratePark'] = $arybrokerRemind['park'];
                                            /*计算小区数量(出租和出售叠加)*/
                                            $columns ='House.id,House.parkId';
                                            $where ='House.type IN (10,11,21,22) and House.verification =1 and House.realId = '.$aryBrokerId['realId'];     //高清 -3
                                            $aryHouse = $objhouse->getHouseByRealtorSaleRent($where,$columns,'House.id desc',0,200);
                                            $aryHousePark = array();
                                            if(count($aryHouse) >0)
                                            {
                                                foreach ($aryHouse as $arValue)
                                                {
                                                    $aryHousePark[] = $arValue['parkId'];
                                                }
                                                $aryHousePark = array_unique($aryHousePark);

                                            }
                                            $aryFinishData['ratePark'] = count($aryHousePark);
                                        }
                                    }

                                    /*构造响应信息*/
                                    $aryView = array('rateCount' => '发布', 'rateFlush' => '刷新', 'rateFlag' => '标签', 'rateRecommend' => '精品', 'rateNew' => '新增', 'ratePark' => '小区');
                                    $strDaBiao = '';
                                    $strNoDaBiao = '';
                                    $strHref = '';
                                    $bolRepeat = false;
                                    foreach($aryObjectData as $key => $value)
                                    {
                                        if($value > $aryFinishData[$key])
                                        {
                                            $strNoDaBiao .= $strNoDaBiao == '' ? "【未达标】\n" : '';
                                            $strNoDaBiao .= $aryView[$key] . ' ' . $aryFinishData[$key] . '条/共'.$value."条\n";
                                            switch($key)
                                            {
                                                case 'rateCount':
                                                    $strHref .= "\n" . '<a href="'._API_DOMAIN_.'/manage">去上下架房源</a>'. "\n";
                                                    break;
                                                case 'rateFlush':
                                                    $strHref .= "\n" . '<a href="'._API_DOMAIN_.'/refresh">去刷新房源</a>'. "\n";
                                                    break;
                                                case 'rateFlag':
                                                case 'rateRecommend':
                                                    if(!$bolRepeat)
                                                    {
                                                        $strHref .= "\n" . '请去电脑上设置标签和精品'. "\n";
                                                        $bolRepeat = true;
                                                    }
                                                    break;
                                                case 'rateNew':
                                                    $strHref .= "\n" . '请去电脑上发布房源'. "\n";
                                                    break;
                                            }
                                        }
                                        else
                                        {
                                            $strDaBiao .= $strDaBiao == '' ? "【已达标】\n" : '';
                                            $strDaBiao .= $aryView[$key] . ' ' . $aryFinishData[$key] . '条/共'.$value."条\n";
                                        }
                                    }
                                    $aryResponseInfo['Content'] = $strNoDaBiao . $strHref;
                                    $strNewLine = $aryResponseInfo['Content'] == '' ? "" : "\n";
                                    $aryResponseInfo['Content'] .= $strNewLine . $strDaBiao;

                                    if($strNoDaBiao == '')
                                    {
                                        $aryResponseInfo['Content'] .= "\n\n干得好~ 全部达标，请继续保持~";
                                    }

                                    echo $objWechat->createTextResponseMsg($aryResponseInfo);
                                    break;
                            }
                        }
                    }
                    else if($strEvent == 'subscribe') /*关于微信服务号*/
                    {
                        $strNickname = '';
                        $strLoginUrl = _API_DOMAIN_.'/login?openid=' . $strFromUserName . '&token=' . md5($strFromUserName . WEIXIN_TOKEN);
                        $arrUserBaseInfo = $objWechat->getUserBaseInfo($strFromUserName);

                        if($arrUserBaseInfo != false)
                        {
                            $strNickname = $arrUserBaseInfo['nickname'];
                        }

                        $aryResponseInfo['Content'] = "Hi ". $strNickname . " 小焦总算把你给盼来了，赶紧登录你的焦点通账号吧，登录后你将能：\n\n";
                        $aryResponseInfo['Content'] .= "手动刷新房源\n";
                        $aryResponseInfo['Content'] .= "上下架房源\n";
                        $aryResponseInfo['Content'] .= "查看数据\n\n";
                        $aryResponseInfo['Content'] .= "还有很多便捷功能等你发现\n\n";
                        $aryResponseInfo['Content'] .= '<a href="'.$strLoginUrl.'">点击这里，立刻登录</a>';

                        echo $objWechat->createTextResponseMsg($aryResponseInfo);
                        exit;
                    }
                    else if($strEvent == 'unsubscribe')	/*取消关注微信服务号*/
                    {
                        echo '';
                    }
                    break;
                /*处理文本推送消息（用户主动发送的消息）*/
                default:
                    $aryResponseInfo['Content'] = '嗷嗷嗷，小焦还在努力成长，暂时听不懂你的意思，小焦的妈妈大焦会定期进行回复的，先等等哈。';
                    echo $objWechat->createTextResponseMsg($aryResponseInfo);
                    exit;
            }
		}

		public function notfoundAction(){
            echo "notFound";die();
        }

        public function checkcodeAction(){
            $echoStr = $_GET["echostr"];
            echo $echoStr;
        }

	}
