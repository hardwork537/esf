<?php

class Moudel extends BaseModel
{

    public $id;

    public $name;

    public $url;

    public $path;


    public $weight;


    public $menuId;

    public $isShow;


    public function columnMap()
    {
        return array(
            'moudelId'     => 'id',
            'moudelName'   => 'name',
            'moudelUrl'    => 'url',
            'moudelWeight' => 'weight',
            'moudelPath'   => 'path',
            'menuId'       => 'menuId',
            'moudelIsShow' => 'isShow',
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
        return 'admin_moudel';
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
        $rs->url    = $arr["url"];
        $rs->path   = $arr["path"];
        $rs->menuId = intval($arr["menuId"]);
        $rs->isShow = intval($arr["isShow"]);

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
        $rs->url    = $arr["url"];
        $rs->path   = $arr["path"];
        $rs->menuId = intval($arr["menuId"]);
        $rs->isShow = intval($arr["isShow"]);

        if($rs->update())
        {
            return true;
        }
        return false;
    }

}
