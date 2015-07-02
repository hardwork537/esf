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
     * 获取一级菜单信息
     * @param type $menuId
     * @return type
     */
    public static function getMenu($menuId = 0)
    {
        $memKey = MemKey::ADMIN_MENU_LIST;
        $menuList = Mem::Instance()->Get($memKey);
        if(empty($menuList))
        {
            $menuList = array();
            $condition = array(
                'conditions' => null,
                'order' => 'weight asc'
            );
            $menus = self::find($condition, 0)->toArray();
            foreach($menus as $value)
            {
                $menuList[$value['id']] = $value;
            }
        }
        
        return $menuId ? $menuList[$menuId] : $menuList;
    }
}
