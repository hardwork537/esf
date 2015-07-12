<?php

class LoginController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/login.css');
        
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
            Cookie::set(LOGIN_KEY, $checkRes['userInfo'], LOGIN_LIFETIME);
            
            $this->show('JSON', array('status'=>0));
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
        if(!$pwd)
        {
            return array( 'status' => 1, 'info' => '请输入密码' );
        }
        
        //验证密码
        $user = WwwUser::findFirst("phone='{$phone}'", 0)->toArray();
        if(empty($user))
        {
            return array( 'status' => 1, 'info' => '用户名或密码错误' );
        }
        $pwd = $this->_getPasswordStr($pwd);
        if($pwd != $user['password'])
        {
            return array( 'status' => 1, 'info' => '用户名或密码错误' );
        }
        $userInfo = array(
            'name' => $user['name'],
            'sex' => $user['sex'],
            'phone' => $user['phone'],
            'id' => $user['id']
        );
        
        return array('status'=>0, 'userInfo'=>$userInfo);
    }
    
    /**
     * 退出
     */
    public function outAction()
    {
        Cookie::delete(LOGIN_KEY);
        $this->show("JSON");
    }
}
