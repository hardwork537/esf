<?php

class UserController extends ControllerBase
{
    public function listAction()
    {
        $condition = array(
            'conditions' => null,
            'order' => 'id desc',
            'offset' => $this->_offset,
            'limit' => $this->_pagesize
        );
        $user = AdminUser::find($condition, 0)->toArray();
        
        $data = array();
        $data['citys'] = $citys = City::instance()->getOptions();
        $data['roles'] = $roles = Roles::instance()->getRoleForOption();
        
        if(empty($user))
        {
            $this->show(null);
            return ;
        }
        $totalNum = AdminUser::count(null);
        $data['userList'] = $user;
        $data['page'] = Page::create($totalNum, $this->_pagesize);
        
        $this->show(null, $data);
    }
    
    public function addAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_checkAddParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }

            $this->show("JSON", AdminUser::instance()->add($checkRes['params']));
        }
        
        $this->show("JSON");
    }
    
    private function _checkAddParams()
    {
        //验证用户名
        $name   = trim($this->request->getPost('name', 'string', ''));
        if(!$name)
        {
            return array( 'status' => 1, 'info' => '用户名不能为空！' );
        }
        
        //验证账号
        $accname   = trim($this->request->getPost('accname', 'string', ''));
        if(!$accname)
        {
            return array( 'status' => 1, 'info' => '账号不能为空！' );
        }
        
        //验证邮箱
        $email = trim($this->request->getPost('email', 'string', ''));
        if(!preg_match("/^[0-9a-zA-Z\_]+@[a-z0-9]+\.[a-z]+$/", $email))
        {
            return array( 'status' => 1, 'info' => '邮箱格式错误！' );
        }
        
        //验证手机
        $tel = trim($this->request->getPost('tel', 'string', ''));
        if($tel && !preg_match("/^1\d{10}$/", $tel))
        {
            return array( 'status' => 1, 'info' => '手机号格式错误！' );
        }
        
        //验证城市
        $cityId   = $this->request->getPost('cityId', 'int', 0);
        $cityList = City::instance()->getOptions();
        if(!array_key_exists($cityId, $cityList) && $cityId != HEAD_CITY)
        {
            return array( 'status' => 1, 'info' => '城市不存在！' );
        }
        
        //验证角色 
        $roleId   = $this->request->getPost('adminRoleId', 'int', 0);
        $roleList = Roles::instance()->getRoleForOption();
        if(!array_key_exists($roleId, $roleList))
        {
            return array( 'status' => 1, 'info' => '角色不存在！' );
        }
        
        //验证密码
        $pwd = trim($this->request->getPost('password', 'string', ''));
        $rePwd = trim($this->request->getPost('repassword', 'string', ''));
        if($pwd || $rePwd)
        {
            if($pwd != $rePwd)
            {
                return array( 'status' => 1, 'info' => '密码不一致！' );
            }
            if(!preg_match("/^\S{6,}$/", $pwd))
            {
                return array( 'status' => 1, 'info' => '密码不能少于6位！' );
            }
        }
        $pwd || $pwd = $GLOBALS['defaultPwd'];
        
        $params = array();
        $params['name'] = $name;
        $params['accname'] = $accname;
        $params['email'] = $email;
        $params['tel'] = $tel;
        $params['cityId'] = $cityId;
        $params['roleId'] = $roleId;     
        $params['password'] = $this->_getPasswordStr($pwd);
        $params['editPwd'] = $rePwd ? $params['password'] : ''; //修改时用

        return array( 'status' => 0, 'params' => $params );
    }
    
    /**
     * 修改
     * @param type $id
     */
    public function editAction($id = 0)
    {
        if($this->request->isPost())
        {
            $id        = intval($this->request->getPost("id", "int"));
            $checkRes = $this->_checkAddParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }

            $rs = AdminUser::instance()->edit($id, $checkRes['params']);
            $this->show("JSON", $rs);
        }
        $where[]             = "id={$id}";
        $this->_json["data"] = AdminUser::findFirst($where, 0)->toArray();
        $this->show("JSON");
    }

    
    /**
     * 删除账号 
     * @param type $userId
     * @return type
     */
    public function delAction($userId)
    {
        $userId = intval($userId);
        $delRes = AdminUser::instance()->del($userId);
        
        $this->show('JSON', $delRes);
    }

    /**
     * 重置密码 
     * @param type $userId
     * @return type
     */
    public function resetpwdAction($userId)
    {
        $userId = intval($userId);
        $user = AdminUser::findFirst($userId);
        if(!$user)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'用户不存在'));
        }
        $newPwd = $this->_getPasswordStr($GLOBALS['defaultPwd']);
        $resetRes = $user->update(array('password'=>$newPwd));
        
        if($resetRes)
        {
            $this->show('JSON', array('status'=>0, 'info'=>'重置密码成功'));
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'重置密码失败'));
        }       
    }
}
