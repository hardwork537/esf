<?php

class AdminUser extends CrmBaseModel
{
    //用户状态
    const STATUS_VALID = 1; // 有效
    const STATUS_INVALID = 2; // 无效

    protected $id;
    protected $accname = '';
    protected $name;
    protected $password = '';
    protected $tel = 0;
    protected $adminRoleId = 0;
    protected $cityId = 0;
    protected $status = 0;
    protected $email = '';
    protected $addTime = 0;

    public function columnMap()
    {
        return array(
            'userId' => 'id',
            'userAccname' => 'accname',
            'userName' => 'name',
            'userPassword' => 'password',
            'userTel' => 'tel',
            'adminRoleId' => 'adminRoleId',
            'cityId' => 'cityId',
            'userStatus' => 'status',
            'userEmail' => 'email',
            'userAddTime' => 'addTime'
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
    }

    public function getSource()
    {
        return "admin_user";
    }

    /**
     * 实例化对象
     *
     * @param type $cache
     * @return \Users_Model
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self();
    }
    
    /**
     * 添加用户
     * @param type $data
     * @return type
     */
    public function add($data)
    {
        $checkRes = $this->_checkParams($data);
        if(0 != $checkRes['status'])
        {
            return $checkRes;
        }
        
        $user = self::instance();
        $addData = $checkRes['data'];
        $addData['addTime'] = time();
        $addData['status'] = self::STATUS_VALID;
        
        if($user->create($addData))
        {
            return array('status' => 0);
        } else {
            return array('status' => 1);
        }
    }
    
    /**
     * 修改用户信息
     * @param type $userId
     * @param type $data
     * @return type
     */
    public function edit($userId, $data)
    {
        $user = self::findFirst("id={$userId}");
        if(!$user)
        {
            return array('status' => 1, 'info' => '用户不存在');
        }
        $checkRes = $this->_checkParams($data, $userId);
        if(0 != $checkRes['status'])
        {
            return $checkRes;
        }
        
        $editData = $checkRes['data'];
        if(!$data['password'])
        {
            unset($editData['password']);
        }
        if($user->update($editData))
        {
            return array('status' => 0);
        } else {
            return array('status' => 1);
        }
    }
    
    private function _checkParams($data, $id = 0)
    {        
        $accname = $data['accname'];
        $where = "accname='{$accname}' and status=".self::STATUS_VALID;
        $id && $where .= " and id<>{$id}";
        $accnameNum = self::count($where);
        if($accnameNum > 0)
        {
            return array('status' => 1, 'info' => '账号已经存在');
        }
        
        $tel = $data['tel'];
        $where = "tel='{$tel}' and status=".self::STATUS_VALID;
        $id && $where .= " and id<>{$id}";
        $telNum = self::count($where);
        if($telNum > 0)
        {
            return array('status' => 1, 'info' => '手机号已经存在');
        }
        
        $email = $data['email'];
        $where = "email='{$email}' and status=".self::STATUS_VALID;
        $id && $where .= " and id<>{$id}";    
        $emailNum = self::count($where);
        if($emailNum > 0)
        {
            return array('status' => 1, 'info' => '邮箱已经存在');
        }
        
        $params = array(
            'name' => $data['name'],
            'accname' => $data['accname'],
            'password' => $data['password'],
            'tel' => $data['tel'],
            'adminRoleId' => $data['roleId'],
            'cityId' => $data['cityId'],
            'email' => $data['email']
        );
        
        return array('status' => 0, 'data' => $params);
    }
    
    /**
     * 删除账号
     * @param type $userId
     * @return type
     */
    public function del($userId)
    {
        $user = self::findFirst($userId);
        if(!$user)
        {
            return array('status' => 1, 'info' => '账号不存在');
        }
        
        $status = $user->delete() ? 0 : 1;
        
        return array('status' => $status);
    }
}
