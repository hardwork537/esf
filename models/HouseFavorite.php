<?php

class HouseFavorite extends BaseModel
{
    protected $id;
    protected $houseId;
    protected $userId;
    protected $addTime;

    public function columnMap()
    {
        return array(
            'id' => 'id',
            'userId' => 'userId',
            'houseId' => 'houseId',
            'addTime' => 'addTime'
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
    }

    public function getSource()
    {
        return "house_favorite";
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
     * 收藏房源
     * @param type $data
     * @return type
     */
    public function addHouse($data)
    {
        if(!$data['houseId'] || !$data['userId'])
        {
            return array('status'=>1, 'info'=>'缺少参数');
        }
        
        $favorite = self::findFirst("userId={$data['userId']} and houseId={$data['houseId']}");
        if($favorite)
        {
            return array('status'=>0);
        }
        
        $favorite = self::instance();
        $insertData = array(
            'userId' => $data['userId'],
            'houseId' => $data['houseId'],
            'addTime' => time()
        );
        if(!$favorite->create($insertData))
        {
            return array('status'=>1, 'info'=>'收藏失败');
        }
        
        return array('status'=>0);
    }
    
}
