<?php

define("NO_NEED_LOGIN", true);
define("NO_NEED_POWER", true);

class LoginController extends ControllerBase
{

    public function indexAction()
    {
        $this->show(null, null, 'layoutsingle');
    }

    /**
     * 登陆
     */
    public function inAction()
    {
        $name = $this->request->getPost('name');
        $action = $this->request->getPost('action');
        $passwd = $this->request->getPost('password');
        $yz = $this->request->getPost('yanzheng');

        if($action == "1")
        {
            if(strtolower($yz) != strtolower($this->session->get('authnum_session')))
            {
                $this->show("ERROR", '验证码输入错误');
            }

            if(!preg_match("/[0-9a-zA-Z\_]+/", $name))
            {
                $this->show("ERROR", '用户名不符合要求');
            }
            
            $rs = AdminUser::findFirst("accname='$name' and status=" . AdminUser::STATUS_VALID);
            
            if($rs->password != $this->_getPasswordStr($passwd))
            {
                $this->show("ERROR", "用户名或密码错误");
            }
            
            $roles = Roles::findFirst("id='" . $rs->adminRoleId . "'");

            if($rs->cityId == HEAD_CITY)
            {
                $cityName = HEAD_CITY_NAME;
            } else
            {
                $citys = City::findFirst("id='" . $rs->cityId . "'");
                $cityName = $citys->name;
            }

            $_userInfo = array(
                "id" => $rs->id,
                "accname" => $rs->accname,
                "name" => $rs->name,
                "tel" => $rs->tel,
                "cityId" => $rs->cityId,
                "roleId" => $rs->adminRoleId,
                "cityName" => $cityName,
                "roleName" => $roles->name
            );
            
            Cookie::set(LOGIN_KEY, $_userInfo, LOGIN_LIFETIME);
 
            $this->show("JSON");
        }
        $this->show("ERROR", "参数错误");
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
