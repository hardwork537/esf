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
    
    /**
     * 获取角色
     * @param type $roleId
     * @return type
     */
    public static function getRoleForOption($roleId = 0)
    {
        $memKey = MemKey::ADMIN_ROLE_LIST;
        $roleList = Mem::Instance()->Get($memKey);
        if(empty($roleList))
        {
            $roleList = array();
            $condition = array(
                'conditions' => null,
                'columns' => 'id,name'
            );
            $roles = self::find($condition, 0)->toArray();
            foreach($roles as $value)
            {
                $roleList[$value['id']] = $value['name'];
            }
        }
        
        return $roleId ? $roleList[$roleId] : $roleList;
    }
    
    /**
     * 修改用户权限
     * @param type $roleId
     * @param type $menuIds
     * @return boolean
     */
    public function updateRolePower($roleId, $menuIds)
    {
        $role = self::findFirst("id={$roleId}");
        if(!$role)
        {
            return array('status' => 1, 'info' => '角色不存在');
        }
        if(empty($menuIds))
        {
            $power = '';
        } else {
            $powers = array();
            foreach($menuIds as $v)
            {
                $powers[] = array('menuId'=>$v);
            }
            $power = json_encode($powers);
        }
        
        $role->power = $power;
        
        return $role->update() ? array('status' => 0) : array('status' => 1);
    }
}
