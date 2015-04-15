<?php

	class GetpwdController extends BaseController{

		public function indexAction(){
            if($_POST)
            {
                $openid = $_POST['openid'];
                $token  = $_POST['token'];
                if(strcmp($token, md5($openid . WEIXIN_TOKEN)) != 0)
                {
                    echo json_encode(array('status'=>'-1', 'content'=>'非法请求'));
                    exit;
                }

                $action = $_POST['do'];
                $mem  = new Mem();
                $objrealtor = new Realtor();

                switch($action)
                {
                    case 'sendcode':
                        $broker_accname = isset($_POST['broker_accname']) ? $_POST['broker_accname'] : "";
                        $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : "";

                        if(is_array(self::checkUserInfo()))
                        {
                            echo json_encode(self::checkUserInfo());
                            exit;
                        }
                        else
                        {
                            $_SESSION['retrieve_pw_tel'] = $telephone;
                            $key = MCDefine::MOBILE_CHECK_ID_KEY.$telephone;
                            //$mem->Del($key);
                            $arrTel0 = $mem->Get($key);

                            if(!empty($arrTel0))
                            {
                                if($arrTel0['number'] >= 3)
                                {
                                    echo json_encode(array('status'=>'-6', 'content'=>'您今天短信超过3次，不能再发送！'));
                                    exit;
                                }
                            }
                            /*生成验证码*/
                            $verifyNum = rand(1000, 9999);
                            $clsComSms = new ComSmsMTBase();

                            /*将utf-8编码转换成gbk编码，否则短信内容出现乱码*/
                            $strMsgContent = "您好，您的手机验证码是" . $verifyNum . ",感谢您一直对搜狐焦点的支持！";
                            $strMsgContent = iconv('UTF-8', 'GBK', $strMsgContent);
                            $clsComSms->focusSubmit($telephone, $strMsgContent);

                            /*存入memcache*/
                            if(!empty($arrTel0))
                            {
                                $arrTel['number'] = $arrTel0['number']+1;
                            }else
                            {
                                $arrTel['number']  =  1;
                            }
                            $arrTel['verify'] = $verifyNum;
                            $mem->Set($key, $arrTel,864000,0);/*有效期一天*/
                            $mem->close();
                            echo json_encode(array('status'=>'1', 'content'=>''));
                            exit;
                        }
                        break;
                    case 'checkcode':

                        if(is_array(self::checkUserInfo()))
                        {
                            echo json_encode(self::checkUserInfo());
                            exit;
                        }
                        $telephone = $_POST['telephone'];
                        $key = MCDefine::MOBILE_CHECK_ID_KEY.$telephone;
                        $arrTel = $mem->Get($key);
                        $verifyNum = isset($_POST['verifyNum']) ? $_POST['verifyNum'] : "";
                        $mem->close();

                        if(!empty($verifyNum))
                        {
                            if($verifyNum != $arrTel['verify'])
                            {
                                echo json_encode(array('status'=>'-7', 'content'=>'验证码不正确或者已过期'));
                                exit;
                            }
                            else
                            {
                                $_SESSION['retrieve_pw_tel'] = $telephone;

                                //获取账号信息
                                $objvip = new VipAccount();
                                $arraccount  = $objvip->getAccountByStatus($_POST['broker_accname'],VipAccount::STATUS_VALID)->toArray();
                                $broker_accname = $_POST['broker_accname'];
                                $broker_id = $arraccount['toId'];

                                $intNowTime = time();
                                $strUrl = _API_DOMAIN_ . '/setpwd?broker_accname='.$broker_accname.'&broker_id='.$broker_id . '&openid=' . $openid . '&t=' . $intNowTime . '&token=' . md5(WEIXIN_TOKEN . $broker_accname . $broker_id . $openid . $intNowTime).'&tel='.$telephone;
                                echo json_encode(array('status'=>'1', 'content'=>$strUrl));
                                exit;
                            }
                        }
                        else
                        {
                            echo json_encode(array('status'=>'-7', 'content'=>'请填写验证码'));
                            exit;
                        }
                        break;
                }
            }
            else
            {
                $openid = htmlspecialchars($_GET['openid']);
                $token  = htmlspecialchars($_GET['token']);

                $this->assign(array(
                    'openid' => $openid,
                    'token'=>$token
                ));

                $this->display('getpwd','index');
            }

		}

        public function checkUserInfo()
        {
            $strErrorMsg ='';
            $objrealtor = new Realtor();

            $flag0 = $objrealtor->checkTelRet($_POST);
            if($flag0 == false)
            {
                //$strErrorMsg = iconv('GBK','UTF-8', $objrealtor->getError());
                $strErrorMsg = $objrealtor->getError();

                if($strErrorMsg == '账号不能为空')
                {
                    $intErrorNo = -2;
                    $strErrorMsg = '请填写账号';
                }
                else if($strErrorMsg == '手机号不能为空')
                {
                    $intErrorNo = -3;
                    $strErrorMsg = '请填写手机号';
                }
                else if($strErrorMsg == '您输入的手机号码格式有误！')
                {
                    $intErrorNo = -4;
                    $strErrorMsg = '手机号格式不正确，检查下吧';
                }
                else if($strErrorMsg == '您输入的账号有误！')
                {
                    $intErrorNo = -5;
                    $strErrorMsg = '账号错误，请检查';
                }
                else if($strErrorMsg == '账号和手机号不匹配！')
                {
                    $intErrorNo = -6;
                    $strErrorMsg = '账号和手机号不匹配，请检查';
                }
            }
            if(!empty($strErrorMsg)){
                $arrcheck = array('status'=>$intErrorNo,'content'=>$strErrorMsg);
                return $arrcheck;
            }
            else{
                return true;
            }
        }

        public function notfoundAction(){
            echo "notFound";die();
		}

	}
