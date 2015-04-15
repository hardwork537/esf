<?php

class Roles extends BaseModel
{
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

    public static function getOptions()
    {
        $rs = self::find()->toArray();
        foreach($rs as $v)
        {
            $data[$v['id']] = $v['name'];
        }
        return $data;
    }

    /**
     * 添加菜单
     * @param array $arr
     * @return boolean
     */
    public function add($arr)
    {
        $rs        = self::instance();
        $rs->name  = $arr["name"];
        $rs->power = $arr["power"];

        if($rs->create())
        {
            return true;
        }
        return false;
    }

    /**
     * 编辑菜单
     * @param unknown $cityId
     * @param unknown $arr
     * @return boolean
     */
    public function edit($id, $arr)
    {
        $id = intval($id);
        $rs = self::findFirst($id);

        $rs->name  = $arr["name"];
        $rs->power = $arr["power"];

        if($rs->update())
        {
            return true;
        }
        return false;
    }

}
