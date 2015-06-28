<?php

class HousePicture extends BaseModel
{

    //房源的图片类型
    const IMG_HUXING = 1; //户型图
    //房源的图片状态
    const STATUS_TOPASS = 1; //待审核
    const STATUS_OK = 2; //审核通过
    const STATUS_DEL = -1; //删除
    const STATUS_NOPASS = 0; //审核失败

    protected $id; //为了兼容接口旧参数
    protected $houseId;
    protected $imgId;
    protected $imgExt;
    protected $parkId = 0;
    protected $type = 0;
    protected $desc = '';
    protected $meta = '';
    protected $seq = 0;
    protected $status;
    protected $updateTime;

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
    
    /**
     * 保存房源图片
     * @param type $houseId
     * @param type $picId
     * @param type $picExt
     * @return type
     */
    public function saveHousePicture($houseId, $picId, $picExt)
    {
        $house = House::findFirst($houseId, 0)->toArray();
        if(empty($house))
        {
            return array('status' => 1, 'info' => '房源不存在');
        }
        
        $housePicture = self::findFirst("houseId={$houseId} and imgId={$picId} and imgExt='{$picExt}'");
        $data = array(
            'houseId' => $houseId,
            'imgId' => $picId,
            'imgExt' => $picExt,
            'parkId' => $house['parkId'],
            'status' => HousePicture::STATUS_TOPASS,
            'updateTime' => date('Y-m-d H:i:s')
        );
        
        if($housePicture)
        {
            $res = $housePicture->update($data);
        } else {
            $housePicture = self::instance();
            $res = $housePicture->create($data);
        }
        
        if($res)
        {
            return array('status' => 0, 'info' => '上传成功');
        } else {
            return array('status' => 1, 'info' => '上传失败');
        }
    }

    /**
     * 删除图片
     * @param type $houseId
     * @param type $picIds
     * @return type
     */
    public function delHousePicture($houseId, $picIds)
    {
        if(!$houseId || empty($picIds))
        {
            return array('status'=>1, 'info'=>'缺少参数');
        }
        $picId = implode(',', $picIds);
        $where = is_array($picIds) ? "houseId={$houseId} and imgId in({$picId})" : "houseId={$houseId} and imgId={$picId}";
        $pictures = self::find($where);
        if(!$pictures)
        {
            return array('status'=>1, 'info'=>'图片不存在');
        }
        
        $this->begin();
        $housePics = array();
        foreach($pictures as $picture)
        {
            if(isset($housePics[$picture->houseId]))
            {
                $housePics[$picture->houseId]++;
            } else {
                $housePics[$picture->houseId] = 1;
            }
            $picture->status = self::STATUS_DEL;
            $picture->updateTime = date('Y-m-d H:i:s');
            if(!$picture->update())
            {
                $this->rollback();
                return array('status'=>1, 'info'=>'删除失败');
            }
        }
        
        //更新房源图片
        $houseIds = array_keys($housePics);
        $where = 1==count($houseIds) ? "id={$houseIds[0]}" : "id in(".  implode(',', $houseIds).")";
        $houses = House::find($where);
        foreach($houses as $house)
        {
            $house->picNum = $house->picNum > $housePics[$house->id] ? $house->picNum-$housePics[$house->id] : 0;
            if(!$house->update())
            {
                $this->rollback();
                return array('status'=>1, 'info'=>'更新图片失败');
            }
        }
        /*
        //删除 image 表中的数据
        $where = is_array($picIds) ? "imgId in($picId)" : "imgId=$picId";
        $images = Image::find($where);
        foreach($images as $image)
        {
            $image->status = Image::STATUS_DEL;
            $image->updateTime = date('Y-m-d H:i:s');
            if(!$image->update())
            {
                return array('status'=>1, 'info'=>'删除失败');
            }
        }
        */
        $this->commit();
        return array('status'=>0, 'info'=>'删除成功');
    }
    
    /**
     * 根据自增id删除图片
     * @param array $ids
     * @return type
     */
    public function delImageById($ids)
    {
        if(empty($ids) || !is_array($ids))
        {
            return array('status'=>1, 'info'=>'缺少参数');
        }
        $where = 1==count($ids) ? "id={$ids[0]}" : "id in(".  implode(',', $ids).")";
        $pictures = self::find($where);
        if(!$pictures)
        {
            return array('status'=>1, 'info'=>'图片不存在');
        }
        
        $this->begin();
        foreach($pictures as $picture)
        {
            $picture->status = self::STATUS_DEL;
            $picture->updateTime = date('Y-m-d H:i:s');
            if(!$picture->update())
            {
                $this->rollback();
                return array('status'=>1, 'info'=>'删除失败');
            }
        }        
        /*
        //删除 image 表中的数据
        $where = is_array($picIds) ? "imgId in($picId)" : "imgId=$picId";
        $images = Image::find($where);
        foreach($images as $image)
        {
            $image->status = Image::STATUS_DEL;
            $image->updateTime = date('Y-m-d H:i:s');
            if(!$image->update())
            {
                return array('status'=>1, 'info'=>'删除失败');
            }
        }
        */
        $this->commit();
        return array('status'=>0, 'info'=>'删除成功');
    }
    
    /**
     * 图片通过审核
     * @param type $id
     * @return type
     */
    public function passAudit($ids)
    {
        if(empty($ids) || !is_array($ids))
        {
            return array('status'=>1, 'info'=>'缺少参数');
        }
        $where = 1==count($ids) ? "id={$ids[0]}" : "id in(".  implode(',', $ids).")";
        $pictures = self::find($where);
        if(!$pictures)
        {
            return array('status'=>1, 'info'=>'图片不存在');
        }
        
        $this->begin();
        $housePics = array();
        foreach($pictures as $picture)
        {
            $picture->status = self::STATUS_OK;
            $picture->updateTime = date('Y-m-d H:i:s');
            if(isset($housePics[$picture->houseId]))
            {
                $housePics[$picture->houseId]++;
            } else {
                $housePics[$picture->houseId] = 1;
            }
            if(!$picture->update())
            {
                $this->rollback();
                return array('status'=>1, 'info'=>'审核失败');
            }
        }
        //更新房源图片
        $houseIds = array_keys($housePics);
        $where = 1==count($houseIds) ? "id={$houseIds[0]}" : "id in(".  implode(',', $houseIds).")";
        $houses = House::find($where);
        foreach($houses as $house)
        {
            $house->picNum += $housePics[$house->id];
            if(!$house->update())
            {
                $this->rollback();
                return array('status'=>1, 'info'=>'更新图片失败');
            }
        }
        
        $this->commit();
        return array('status'=>0, 'info'=>'审核成功');
    }
}
