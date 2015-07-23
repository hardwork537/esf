<?php

class RegisterController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/register.css');
        
        $this->show(null, $data);
    }
    
    public function doAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_checkParams();
            if(0 !== $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            
            $regRes = WwwUser::instance()->add($checkRes['params']);
            
            if(0 != $regRes['status'])
            {
                $this->show('JSON', $regRes);
            }
            $user = $regRes['userInfo'];
            $userData = array(
                'name' => $user['name'],
                'sex' => $user['sex'],
                'phone' => $user['phone'],
                'id' => $user['id']
            );
            Cookie::set(LOGIN_KEY, $userData, LOGIN_LIFETIME);
            
            $this->show('JSON', array('status'=>99));
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'非法请求'));
        }
    }
    
    private function _checkParams()
    {      
        //验证手机号
        $phone = $this->request->getPost('phone', 'string', '');
        if(!$phone || !preg_match("/^1\d{10}$/", $phone))
        {
            return array( 'status' => 1, 'info' => '手机号格式错误！' );
        }
        
        //验证密码
        $pwd = trim($this->request->getPost('password', 'string', ''));
        $rePwd = trim($this->request->getPost('repassword', 'string', ''));
        if(!$pwd)
        {
            return array( 'status' => 1, 'info' => '请输入密码' );
        } elseif(!preg_match("/^[0-9a-zA-Z\-\.]{6,}$/", $pwd)) {
            return array( 'status' => 1, 'info' => '密码格式错误' );
        }
       
        if($rePwd != $pwd)
        {           
            return array( 'status' => 1, 'info' => '两次密码输入不一致' );
        }
        
        //验证验证码
        $code = trim($this->request->getPost('code', 'string', ''));
        $memkey = Data::getSendMessageMemkey($phone);

        $num = Mem::Instance()->Get($memkey);
        //$num = '3838';
        if($code != $num)
        {
            return array('status' => 1, 'info' => '短信验证码错误' );
        }
        //图形验证码
        $imgCode = $this->session->get('www_reg_authnum_session');
        $image_code = trim($this->request->getPost('imgCode', 'string', ''));
        if($image_code != $imgCode)
        {
            return array('status' => 1, 'info' => '验证码错误' );
        }
        
        $params = array(
            'phone' => $phone,
            'password' => $this->_getPasswordStr($pwd)
        );
        
        return array('status'=>0, 'params'=>$params);
    }
}
