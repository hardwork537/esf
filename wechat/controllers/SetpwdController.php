<?php

	class SetpwdController extends BaseController{

		public function indexAction(){

            if($_POST)
            {
                $mem 	   = new Mem();
                $objrealtor = new Realtor();
                $objvip = new VipAccount();

                $strToken = md5(WEIXIN_TOKEN . $_POST['broker_accname'] . $_POST['broker_id'] . $_POST['openid'] . $_POST['t']);
                if(strcmp($_POST['token'], $strToken) == 0)
                {
                    /*验证密码合法性*/
                    if( empty($_POST['password']))
                    {
                        echo json_encode(array('status'=>'-2', 'content'=>'请输入新密码'));
                        exit;
                    }
                    if( !preg_match("/^[A-Za-z0-9]{6,18}$/",$_POST['password']))
                    {
                        echo json_encode(array('status'=>'-3', 'content'=>'密码仅限6-18位英文、数字'));
                        exit;
                    }
                    if( empty($_POST['passwordSecond']))
                    {
                        echo json_encode(array('status'=>'-4', 'content'=>'请输入确认密码'));
                        exit;
                    }
                    if( $_POST['password'] != $_POST['passwordSecond'])
                    {
                        echo json_encode(array('status'=>'-5', 'content'=>'两次密码不一致 请再次确认'));
                        exit;
                    }

                    /*修改密码*/
                    $data['passwd'] = sha1(md5($_POST['password']));
                    $data =  Utility::escArr($data);
                    $flag = $objvip->modifyRealtorPwd($_POST['broker_id'],$data['passwd']);

                    if($flag)
                    {
                        $key = MCDefine::MOBILE_CHECK_ID_KEY .$_POST['tel'];
                        $mem->Del($key);

                        /*设置Cookie信息*/
                        $arrRealtor = $objrealtor->getRealtorById($_POST['broker_id']);
                        if($arrRealtor != false){
                            $arrRealtor = $arrRealtor->toArray();
                        }

                        //扩展城市基本信息
                        $arrCity = CCity::getAllCity($arrRealtor['cityId']);

                        //存Cookie信息
                        $arrSaveInfo = array(
                            "real_id"         =>      $arrRealtor['id'],
                            "cityId"           =>      isset($arrRealtor['cityId'])?$arrRealtor['cityId']:0,
                            "cityName"         =>      isset($arrCity['name'])? $arrCity['name']:null ,
                            "cityCode"       =>      isset($arrCity['code'])?$arrCity['code']:null,
                            "cityPinyin"       =>      isset($arrCity['pinyin'])?$arrCity['pinyin']:null,
                            "cityPinyinAbbr"  =>      isset($arrCity['pinyinAbbr'])?$arrCity['pinyinAbbr']:null
                        );

                        /*将所有登陆者登录信息 存wechatbrokerinfo，有效期一年*/
                        Utility::setCookie('wechatbrokerinfo', $arrSaveInfo, time() + (365 * 24 * 3600), '/', _API_COOKIE_DOMAIN_);

                        //$city_config = $arrCity['city_pinyin'];
                        //include_once WEIXIN_COMMON_CONFIG_DIR . "/".$city_config.".config.inc.php";

                        /* 登陆日志 */
                        $arrAccount = $objvip->getAccountByStatus($_POST['broker_accname'],VipAccount::STATUS_VALID)->toArray();
                        RealtorWeixin::getInstance()->addLog($arrAccount['id'],$arrRealtor['id'],$arrRealtor['shopId'],$arrRealtor['comId']);

                        $nowtime = time();
                        $strUrl = _API_DOMAIN_ . '/login/success?realtor_id='. $_POST['broker_id'] . '&tm=' . $nowtime . '&tk=' . md5(WEIXIN_TOKEN . $_POST['broker_id'] . $nowtime);

                        echo json_encode(array('status'=>'1', 'content'=>$strUrl));
                        exit;
                    }
                    else
                    {
                        $mem->close();
                        echo json_encode(array('status'=>'-6', 'content'=>'重置密码失败'));
                        exit;
                    }
                }
                else
                {
                    echo json_encode(array('status'=>'-1', 'content'=>'非法请求'));
                    exit;
                }
            }
            else
            {
                $strToken = md5(WEIXIN_TOKEN . $_GET['broker_accname'] . $_GET['broker_id'] . $_GET['openid'] . $_GET['t']);
                if(strcmp($_GET['token'], $strToken) == 0)
                {
                    foreach($_GET as $key=>$value)
                    {
                        $_GET[$key] = htmlspecialchars($value);
                    }

                    $this->assign(array(
                        'broker_accname' => $_GET['broker_accname'],
                        'broker_id'=>$_GET['broker_id'],
                        'openid'=>$_GET['openid'],
                        't'=>$_GET['t'],
                        'token'=>$_GET['token'],
                        'tel'=>$_GET['tel']
                    ));
                    $this->display('setpwd','index');

                }
            }

		}

		public function notfoundAction(){
            echo "notFound";die();
		}

	}
