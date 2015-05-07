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
            'moudelId' => 'id',
            'moudelName' => 'name',
            'moudelUrl' => 'url',
            'moudelWeight' => 'weight',
            'moudelPath' => 'path',
            'menuId' => 'menuId',
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
     * 获取二级菜单信息
     * @param type $menuId
     * @return type
     */
    public static function getMoudel($moudelId = 0)
    {
        $memKey = MemKey::ADMIN_MOUDEL_LIST;
        $moudelList = Mem::Instance()->Get($memKey);
        if(empty($moudelList))
        {
            $moudelList = array();
            $condition = array(
                'conditions' => null,
                'order' => 'weight asc'
            );
            $moudels = self::find($condition, 0)->toArray();
            foreach($moudels as $value)
            {
                $moudelList[$value['id']] = $value;
            }
        }
        
        return $moudelId ? $moudelList[$moudelId] : $moudelList;
    }
}
