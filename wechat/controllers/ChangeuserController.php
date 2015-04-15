<?php

	class ChangeuserController extends BaseController{

		public function indexAction(){

            if(isset($_GET['code']) && !empty($_GET['code']))
            {
                $strOpenId = Api::getInstance()->getOpenIdByCode($_GET['code']);
                $strToken =  md5($strOpenId . WEIXIN_TOKEN);
            }
            else
            {
                $strRequestUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WEIXIN_APPID;
                $strRequestUrl .= '&redirect_uri=' . urlencode(_API_DOMAIN_.'/changeuser');
                $strRequestUrl .= '&response_type=code&scope=snsapi_base&state=azh5934f#wechat_redirect';
                echo '<script type="text/javascript">window.location.href = \'' . $strRequestUrl . '\';</script>';
                exit;
            }

            //$strOpenId='oxlkntyn6Vd67nrjskwakyJMMbCs';
            $arrWechatBroker = RealtorWeixin::getInstance()->getBrokerIdByOpenId($strOpenId)->toArray();
            if(count($arrWechatBroker) == 0 || $arrWechatBroker['status'] == RealtorWeixin::STATUS_UNBIND)
            {
                /*没有绑定跳转到登录页面*/
                $strLoginUrl = _API_DOMAIN_ . '/login?openid=' . $strOpenId . '&token=' . md5($strOpenId . WEIXIN_TOKEN);
                $showMsg = '嗷嗷嗷，亲，小焦发现你还没有登录焦点通哦，<a href="' . $strLoginUrl . '">点击这里登录</a>，贴心服务马上就来';

                $this->view->setVar("showMsg", $showMsg);
                $this->renderurl('login','nologin');
                exit;
            }
            else
            {
                /*获取经纪人信息*/
                $objRealtor = new Realtor();   //经纪人
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

            $objaccount = new VipAccount();
            $arraccount = $objaccount->getAccountByToId(VipAccount::ROLE_REALTOR,$aryBrokerInfo['id']);
            if($arraccount != false)
            {
                $arraccount = $arraccount->toArray();
            }

            $weixinInfo = Api::getInstance()->getUserBaseInfo($strOpenId);

            $strNickname = '';
            if($weixinInfo != false)
            {
                $strNickname = $weixinInfo['nickname'];
            }

            //公司信息
            $aryCompany = Company::instance()->getCompanyById($aryBrokerInfo['comId']);
            if($aryCompany != false)
            {
                $aryCompany =  $aryCompany->toArray();
            }

            //门店信息
            $aryShop = Shop::instance()->getShopById($aryBrokerInfo['shopId']);
            if($aryShop != false)
            {
                $aryShop =  $aryShop->toArray();
            }

            //端口信息
            $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
            if($arrorderport != false)
            {
                $arrorderport =  $arrorderport->toArray();
                $arrportcity = PortCity::instance()->getPortById($arrorderport['portId']);
                if($arrportcity != false)
                {
                    $arrportcity =  $arrportcity->toArray();
                }
                $portType = $arrportcity['type'];
                $is_expired = (strtotime($arrorderport['expiryDate'])-strtotime(date("Y-m-d",time())))/86400;
            }

            $this->assign(array(
                'accountName'  => $arraccount['name'],
                'realName'=>$aryBrokerInfo['name'],
                'realTel'=>$aryBrokerInfo['mobile'],
                'realType' =>$aryBrokerInfo['type'] ,
                'agentName'=>$aryShop['name'],
                'agentInfo'=>$aryShop['name'],
                'maxPort'=>$arrorderport['num'],
                'portType'=>$portType,
                'is_expired'=>$is_expired,
                'stoptime'=>$arrorderport['expiryDate'],
                'weixinName' => $strNickname,
                'companyName' =>$aryCompany['abbr'],
                'loginUrl' => _API_DOMAIN_ . '/login?rqf=1&openid=' . $strOpenId . '&token=' . md5($strOpenId . WEIXIN_TOKEN)
            ));

            $this->display('changeuser','index');

		}

		public function notfoundAction(){
            echo "notFound";die();
		}

	}
