<?php

	class AuthController extends BaseController{

		public function indexAction(){

            $strOpenId ='';
            $objRealtor = new Realtor();   //经纪人

            /*判断是否微信跳转到该页面*/
            if(isset($_GET['code']) && !empty($_GET['code']))
            {
                /*通过code 获取 openid,changeuser页面不需要再次获取opendid*/
                if(strpos($_SERVER['REQUEST_URI'], 'changeuser') === false)
                {
                    $mixedResult = Api::getInstance()->getOpenIdByCode($_GET['code']);
                }
                else
                {
                    $mixedResult = $strOpenId;
                }

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
                        $aryBrokerInfo = array();
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

                    $GLOBALS['CITY_PY_ABBR'] 		  = $arrCity['cityPinyinAbbr'];
                    $GLOBALS['client']['cityPinyin'] = $arrCity['cityPinyin'];
                }
                else
                {
                    $GLOBALS['CITY_PY_ABBR'] 		  = $aryWechat['cityPinyinAbbr'];
                    $GLOBALS['client']['cityPinyin'] = $aryWechat['cityPinyin'];
                }

                $GLOBALS['client']['cityId']   = $aryBrokerInfo['cityId'];
                $GLOBALS['client']['real_id'] = $aryBrokerInfo['id'];

                //require_once WEIXIN_COMMON_CONFIG_DIR . '/' . $GLOBALS['client']['city_pinyin'] . '.config.inc.php';
                //require_once WEIXIN_COMMON_CONFIG_DIR . '/' . $GLOBALS['CITY_PY_ABBR'] . '.db.config.inc.php';
            }

        }

		public function notfoundAction(){
            echo "notFound";die();
		}

	}
