<?php

class HouseExtTag extends BaseModel
{

    protected $id;
    protected $cityId;
    protected $houseId;
    protected $tag;
    protected $addTime;

    public function getSource()
    {
        return 'house_ext_tag';
    }

    public function columnMap()
    {
        return array(
            'heId' => 'id',
            'cityId' => 'cityId',
            'houseId' => 'houseId',
            'heTag' => 'tag',
            'heAddTime' => 'addTime'
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
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
