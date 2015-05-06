<?php

class Roles extends BaseModel
{
    //角色
    const ROLE_SUPER_MANAGER = 1;  //管理员
    const ROLE_CUSTOM_MANAGER = 2; //客户经理
    const ROLE_CONTRACT_SERVICE = 3; //签约经理
    const ROLE_OPERATE_SPECIALIST = 4; //运营专员
    
    public $id;
    public $name;
    public $power;


    public function columnMap()
    {
        return array(
            'roleId'    => 'id',
            'roleName'  => 'name',
            'rolePower' => 'power'
        );
    }

    /**
     * 实例化对象
     * @param type $cache
     * @return \Users_Model
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self();
    }

    public function initialize()
    {
        $this->setConn("esf");
    }

    public function getSource()
    {
        return 'admin_roles';
    }
}
