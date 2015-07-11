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
            $this->show('JSON', $regRes);
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
        } elseif(!preg_match("/^[0-9a-zA-Z\-\.]{6,}$/", $phone)) {
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
        $num = '3838';
        if($code != $num)
        {
            return array('status' => 1, 'info' => '验证码错误' );
        }
        
        $params = array(
            'phone' => $phone,
            'password' => $this->_getPasswordStr($phone)
        );
        
        return array('status'=>0, 'params'=>$params);
    }
}
