<?php

class HousePicture extends BaseModel
{

    //房源的图片类型
    const IMG_HUXING = 1; //户型图
    //房源的图片状态
    const STATUS_OK = 1; //有效
    const STATUS_DEL = -1; //删除
    const STATUS_NOPASS = 0; //审核失败

    protected $id; //为了兼容接口旧参数
    protected $houseId;
    protected $imgId;
    protected $imgExt;
    protected $parkId = 0;
    protected $type;
    protected $desc;
    protected $meta;
    protected $seq;
    protected $status;
    protected $picUpdate;
    private $mNotDealImage = array();

    public function getSource()
    {
        return 'house_picture';
    }

    public function columnMap()
    {
        return array(
            'hpId' => 'id',
            'houseId' => 'houseId',
            'imgId' => 'imgId',
            'imgExt' => 'imgExt',
            'parkId' => 'parkId',
            'picType' => 'type',
            'picDesc' => 'desc',
            'picMeta' => 'meta',
            'picSeq' => 'seq',
            'picStatus' => 'status',
            'picUpdate' => 'updateTime'
        );
    }

    public function initialize()
    {
        $this->setReadConnectionService('esfSlave');
        $this->setWriteConnectionService('esfMaster');
    }

    /**
     * 实例化
     * @param type $cache
     * @return Park_Model
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
    }

    /**
     * 获取图片数量 - 根据房源ID
     * @param int  $houseId
     * @param int  $status
     * @param int  $type
     * @return boolean
     */
    public function getHousePicCountById($houseId, $status = self::STATUS_OK, $type = 0)
    {
        if(empty($houseId))
            return 0;
        $where = "status={$status}";
        
        if(is_array($houseId))
        {
            $where .= " and houseId in(".  implode(',', $houseId).")";
        } else {
            $where .= " and houseId={$houseId}";
        }
        $condition = array(
            'columns' => 'houseId,count(*) as count',
            'conditions' => $where,
            'group' => 'houseId'
        );
        $res = self::find($condition, 0)->toArray();
        $list = array();
        foreach($res as $v)
        {
            $list[$v['houseId']] = intval($v['count']);
        }
        
        return $list;
    }

}
