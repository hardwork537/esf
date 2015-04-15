<?php

	class LoginController extends BaseController{

		public function indexAction(){

            if(isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 'azh5934f')
            {
                $strOpenId = Api::getInstance()->getOpenIdByCode($_GET['code']);
                $strToken =  md5($strOpenId . WEIXIN_TOKEN);
            }
            else
            {
                if(isset($_GET['openid']) && !empty($_GET['openid']) &&
                    $_GET['token'] && !empty($_GET['token']) &&
                    $_GET['token'] == md5($_GET['openid'] . WEIXIN_TOKEN))
                {
                    $strOpenId = htmlspecialchars($_GET['openid']);
                    $strToken =  htmlspecialchars($_GET['token']);
                }
                else
                {
                    $strRequestUrl  = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WEIXIN_APPID;
                    $strRequestUrl .= '&redirect_uri=' . urlencode(_API_DOMAIN_.'/login');
                    $strRequestUrl .= '&response_type=code&scope=snsapi_base&state=azh5934f#wechat_redirect';
                    header("Location: $strRequestUrl");
                    exit;
                }
            }
            $requestFrom = isset($_GET['rqf']) ? $_GET['rqf'] : '0';

            //根据用户ID判断用户是否已经绑定，如果绑定则提示用户绑定成功
            $GLOBALS['CITY_PY_ABBR'] = 'sh';
            $arrBrokerInfo = RealtorWeixin::getInstance()->getBrokerIdByOpenId($strOpenId)->toArray();

            if(!$requestFrom && count($arrBrokerInfo) >0 && $arrBrokerInfo['status'] == RealtorWeixin::STATUS_BIND)
            {
                $this->assign(array(
                    'fromlogin'          => 1,
                    'success'			=> 1
                ));
                $this->pick('login','success');
            }
            else
            {
                $this->assign(array(
                    'strOpenId'          => $strOpenId,
                    'strToken'          => $strToken,
                    'strForgetPwd'		=> _API_DOMAIN_ . '/getpwd?'
                ));
                $this->pick('login','index');
            }
		}

        /*
         * login request
         * */
        public function reqloginAction(){

           $actType = isset($_POST['do']) ? $_POST['do'] : '';

           switch($actType)
           {
               case "login":

                  //验证相关参数是否合法
                  $strOpenId = isset($_POST['openid']) ? $_POST['openid'] : '';
                  $strToken  = isset($_POST['token']) ? $_POST['token'] : '';
                  if(strcmp($strToken, md5($strOpenId . WEIXIN_TOKEN)) != 0)
                  {
                      self::Response(array('status'=>'-1', 'content'=>'非法请求'));
                  }
                  $strEntName = isset($_POST['username']) ? $_POST['username'] : '';
                  $strPassword = isset($_POST['password']) ? $_POST['password'] : '';
                  if($strEntName == '')
                  {
                      self::Response(array('status'=>'-2', 'content'=>'账号不能为空'));
                  }

                  if($strPassword == '')
                  {
                      self::Response(array('status'=>'-3', 'content'=>'密码不能为空'));
                  }

                  $objvipaccount = new VipAccount();
                  $arrAccount = $objvipaccount->getAccountByStatus($strEntName,VipAccount::STATUS_VALID)->toArray();

                  if(count($arrAccount) == 0)
                  {
                      self::Response(array('status'=>'-4', 'content'=>'账号或者密码不正确'));
                  }
                  else if($arrAccount['password'] != sha1(md5($strPassword)))
                  {
                      self::Response(array('status'=>'-4', 'content'=>'账号或者密码不正确'));
                  }

                  $objrealtor = new Realtor();
                  $arrRealtor = $objrealtor->getRealtorById($arrAccount['toId']);
                  if($arrRealtor != false){
                      $arrRealtor = $arrRealtor->toArray();
                  }

                  //判断是否存在该经纪人
                  if($arrRealtor == false)
                  {
                      self::Response(array('status'=>'-4', 'content'=>'账号或者密码不正确'));
                  }
                  else if($arrRealtor['status']== Realtor::STATUS_DELETE)
                  {
                      self::Response(array('status'=>'-4', 'content'=>'账号或者密码不正确'));
                  }

                  //判断数据是否出现异常
                  if(empty($arrRealtor['cityId']) || empty($arrRealtor['comId']))
                  {
                      self::Response(array('status'=>'-4', 'content'=>'账号或者密码不正确'));
                  }

                  //绑定焦点通账号和微信账号
                  $arrBrokerInfo = RealtorWeixin::getInstance()->getBrokerIdByOpenId($strOpenId)->toArray();
                  if(count($arrBrokerInfo) >0)
                  {
                      $bolResult = RealtorWeixin::getInstance()->RelationBind($strOpenId,$arrRealtor['id']);
                  }
                  else
                  {
                      $bolResult = RealtorWeixin::getInstance()->Insert($strOpenId, $arrRealtor['id']);
                  }


                   if(!$bolResult)
                   {
                       self::Response(array('status'=>'-5', 'content'=>'系统错误，稍后再试'));
                   }

                  //扩展城市基本信息
                  $arrCity = CCity::getAllCity($arrRealtor['cityId']);
                  //加载城市配置
                  require_once WEIXIN_COMMON_CONFIG_DIR ."/".$arrCity['pinyinAbbr'].".config.inc.php";

                  //存Cookie信息
                  $arrSaveInfo = array(
                      "real_id"         =>      $arrRealtor['id'],
                      "cityId"           =>      isset($arrRealtor['cityId'])?$arrRealtor['cityId']:0,
                      "cityName"         =>      isset($arrCity['name'])? $arrCity['name']:null ,
                      "cityCode"       =>      isset($arrCity['code'])?$arrCity['code']:null,
                      "cityPinyin"       =>      isset($arrCity['pinyin'])?$arrCity['pinyin']:null,
                      "cityPinyinAbbr"  =>      isset($arrCity['pinyinAbbr'])?$arrCity['pinyinAbbr']:null
                  );

                  //将所有登陆者登录信息 存wechatbrokerinfo，有效期一年
                  Utility::setCookie('wechatbrokerinfo', $arrSaveInfo, time() + 365 * 24 * 3600, '/', _API_COOKIE_DOMAIN_);

                  $intNowTime = time();
                   /* 登陆日志 */
                  RealtorWeixin::getInstance()->addLog($arrAccount['id'],$arrRealtor['id'],$arrRealtor['shopId'],$arrRealtor['comId']);

                  $strUrl = _API_DOMAIN_ . '/login/success?realtor_id='. $arrRealtor['id'] . '&tm=' . $intNowTime . '&tk=' . md5(WEIXIN_TOKEN . $arrRealtor['id'] . $intNowTime);
                  self::Response(array('status'=>'1', 'content'=>$strUrl));

                  break;

               default:
                   $arrJson = array('status'=>'-1', 'content'=>'非法请求');
                   echo json_encode($arrJson);
           }

        }

        /*
         * login fail
         * */
        public function nologinAction(){
            //$this->display('login','nologin');
        }

        /*
         * login success
         * */
        public function successAction(){
            $intRealtorId = isset($_GET['realtor_id']) ? $_GET['realtor_id'] : '';
            $intTime     = isset($_GET['tm']) ? $_GET['tm'] : '';
            $strToken    = isset($_GET['tk']) ? $_GET['tk'] : '';


            $strTmpTk = md5(WEIXIN_TOKEN . $intRealtorId . $intTime);
            if(strcmp($strToken, $strTmpTk) == 0)
            {
                $this->view->setVar("success", 1);
                $this->view->setVar("fromlogin", 0);
            }
            else
            {
                $this->view->setVar("success",0);
            }
            $this->display('login','success');
        }

        public function Response($arrData)
        {
            echo json_encode($arrData);
            exit;
        }

		public function notfoundAction(){
            echo "notFound";die();
		}

	}
