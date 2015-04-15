<?php

class Menu extends BaseModel
{

    public $id;

    public $name;

    public $url = "";

    public $weight;

    public function columnMap()
    {
        return array(
            'menuId'     => 'id',
            'menuName'   => 'name',
            'menuUrl'    => 'url',
            'menuWeight' => 'weight'
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
        return 'admin_menu';
    }


    /**
     * 添加菜单
     * @param array $arr
     * @return boolean
     */
    public function add($arr)
    {
        $rs         = self::instance();
        $rs->name   = $arr["name"];
        $rs->weight = intval($arr["weight"]);

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
        $id         = intval($id);
        $rs         = self::findfirst($id);
        $rs->name   = $arr["name"];
        $rs->weight = intval($arr["weight"]);

        if($rs->update())
        {
            return true;
        }
        return false;
    }
}
