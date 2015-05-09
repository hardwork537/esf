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
}
