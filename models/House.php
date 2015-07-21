<?php

use Aws\S3\Model\MultipartUpload\ParallelTransfer;

class House extends BaseModel
{

    //房源状态常量status
    const STATUS_ONLINE = 1; //发布
    const STATUS_OFFLINE = 2; //下架   
    //审核状态status 0:待审核 1:审核过 -1:违规
    const HOUSE_VERING = 0;
    const HOUSE_VERED = 1;
    const HOUSE_VERNO = -1;
    //是否满五年
    const IS_FIVEYEAR = 1;
    const NO_FIVEYEAR = 0;
    //是否唯一一套
    const IS_ONLYONE = 1;
    const NO_ONLYONE = 0;
    //是否境外人士
    const IS_FOREIGN = 1;
    const NO_FOREIGN = 0;
    //租约
    const IS_RENT = 1;
    const NO_RENT = 0;
    //是否有车位
    const HAS_PARK = 1;
    const NO_PARK = 0;
    //是否抵押
    const IS_MORTGAGE = 1;
    const NO_MORTGAGE = 0;
    //是否有户口
    const HAS_HUKOU = 1;
    const NO_HUKOU = 0;
    //房源等级
    const LEVEL_A = 1;
    const LEVEL_B = 2;
    const LEVEL_C = 3;
    //下架原因
    const OFFLINE_SOLD = 1; //已售
    const OFFLINE_WAITING = 2; //暂缓出售
    const OFFLINE_INVALID = 3; //无效号码
    const OFFLINE_AGENT = 4; //中介
    //类型
    const TYPE_WEITUO = 2; //委托
    
    public $id;
    public $parkId;
    public $regId = 0;
    public $distId = 0;
    public $cityId;
    public $userId = 0;
    public $type = 0;
    public $level = '';
    public $price = 0.00;
    public $handPrice = 0.00;
    public $buyPrice = 0.00;
    public $saleTax = 0.00;
    public $tax = 0.00;
    public $isFiveYear = self::NO_FIVEYEAR;
    public $isOnlyOne = self::IS_ONLYONE;
    public $propertyOwner = '';
    public $propertyPhone = '';
    public $agent = '';
    public $agentPhone = '';
    public $isRent = self::NO_RENT;
    public $rentPrice = 0;
    public $rentEndTime = '0000-00-00';
    public $hasPark = self::NO_PARK;
    public $isMortgage = self::NO_MORTGAGE;
    public $isForeign = self::NO_FOREIGN;
    public $hasHukou = self::NO_HUKOU;
    public $give = 0;
    public $remark = '';
    public $bedRoom;
    public $livingRoom;
    public $bathRoom;
    public $bA = 0;
    public $uA = 0;
    public $decoration = 0;
    public $orientation = 0;
    public $liftCount = 0;
    public $unitNo = 0;
    public $roomNo = 0;
    public $floor = 0;
    public $floorMax = 0;
    public $picNum = 0;
    public $buildType = 0;
    public $floorPosition = 0;
    public $propertyType = 0;
    public $verification = 0;
    public $status = self::STATUS_ONLINE;
    public $delTime = '0000-00-00 00:00:00';
    public $auditingTime = '0000-00-00 00:00:00';
    public $xiajiaTime = '0000-00-00 00:00:00';
    public $xiajiaReason = 0;
    public $createTime = '0000-00-00 00:00:00';
    public $updateTime = '0000-00-00 00:00:00';
    public $deliverDate = '0000-00-00';

    public function getSource()
    {
        return 'house';
    }

    public function columnMap()
    {
        return array(
            'houseId' => 'id',
            'parkId' => 'parkId',
            'regId' => 'regId',
            'distId' => 'distId',
            'cityId' => 'cityId',
            'userId' => 'userId',
            'houseType' => 'type',
            'houseLevel' => 'level',
            'housePrice' => 'price',
            'houseHandPrice' => 'handPrice',
            'houseBuyPrice' => 'buyPrice',
            'houseSaleTax' => 'saleTax',
            'houseTax' => 'tax',
            'houseIsFiveYear' => 'isFiveYear',
            'houseIsOnlyOne' => 'isOnlyOne',
            'housePropertyOwner' => 'propertyOwner',
            'housePropertyPhone' => 'propertyPhone',
            'houseAgent' => 'agent',
            'houseAgentPhone' => 'agentPhone',
            'houseIsRent' => 'isRent',
            'houseRentPrice' => 'rentPrice',
            'houseRentEndTime' => 'rentEndTime',
            'houseHasPark' => 'hasPark',
            'houseIsMortgage' => 'isMortgage',
            'houseIsForeign' => 'isForeign',
            'houseHasHukou' => 'hasHukou',
            'houseGive' => 'give',
            'houseRemark' => 'remark',
            'houseBedRoom' => 'bedRoom',
            'houseLivingRoom' => 'livingRoom',
            'houseBathRoom' => 'bathRoom',
            'houseBA' => 'bA',
            'houseUA' => 'uA',
            'houseDecoration' => 'decoration',
            'houseOrientation' => 'orientation',
            'houseLiftCount' => 'liftCount',
            'houseUnitNo' => 'unitNo',
            'houseRoomNo' => 'roomNo',
            'houseFloor' => 'floor',
            'houseFloorMax' => 'floorMax',
            'housePicNum' => 'picNum',
            'houseBuildType' => 'buildType',
            'houseFloorPosition' => 'floorPosition',
            'housePropertyType' => 'propertyType',
            'houseVerification' => 'verification',
            'houseStatus' => 'status',
            'houseDelTime' => 'delTime',
            'houseAuditing' => 'auditingTime',
            'houseXiajia' => 'xiajiaTime',
            'houseXiajiaReason' => 'xiajiaReason',
            'houseCreate' => 'createTime',
            'houseUpdate' => 'updateTime',
            'houseDeliverDate' => 'deliverDate'
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

    /**
     * 租约状态
     * @return type
     */
    public static function getAllRentStatus()
    {
        return array(
            self::NO_RENT => '否',
            self::IS_RENT => '是'
        );
    }
    
    /**
     * 停车位状态
     * @return type
     */
    public static function getAllParkStatus()
    {
        return array(
            self::HAS_PARK => '是',
            self::NO_PARK => '否'
        );
    }
    
    /**
     * 抵押状态
     * @return type
     */
    public static function getAllMortgageStatus()
    {
        return array(
            self::NO_MORTGAGE => '否',
            self::IS_MORTGAGE => '是'           
        );
    }
    
    /**
     * 户口状态 
     * @return type
     */
    public static function getAllHukouStatus()
    {
        return array(
            self::NO_HUKOU => '否',
            self::HAS_HUKOU => '是'            
        );
    }
    
    /**
     * 境外人士状态
     * @return type
     */
    public static function getAllForeignStatus()
    {
        return array(
            self::NO_FOREIGN => '否',
            self::IS_FOREIGN => '是'
        );
    }
    
    /**
     * 是否满五年
     * @return type
     */
    public static function getAllFiveYearStatus()
    {
        return array(
            self::NO_FIVEYEAR => '否',
            self::IS_FIVEYEAR => '是'            
        );
    }
    
    /**
     * 唯一一套
     * @return type
     */
    public static function getAllOnlyOneStatus()
    {
        return array(
            self::IS_ONLYONE => '是',
            self::NO_ONLYONE => '否'
        );
    }
    
    /**
     * 房源等级
     * @return type
     */
    public static function getAllLevelStatus()
    {
        return array(
            self::LEVEL_A => 'A',
            self::LEVEL_B => 'B',
            self::LEVEL_C => 'C'
        );
    }

    /**
     * 下架原因
     * @return type
     */
    public static function getAllOfflineReason()
    {
        return array(
            self::OFFLINE_SOLD => '已售',
            self::OFFLINE_WAITING => '暂缓出售',
            self::OFFLINE_INVALID => '无效号码',
            self::OFFLINE_AGENT => '中介'
        );
    }

    /**
     * 图片数量跟等级关系
     * @return type
     */
    public static function getAllLevel($level = 0)
    {
        $levels = array(
            self::LEVEL_A => array(
                '>=' => 5,
            ),
            self::LEVEL_B => array(
                '>=' => 1,
                '<' => 5
            ),
            self::LEVEL_C => array(
                '=' => 0
            )
        );
        
        return $level ? $levels[$level] : $levels;
    }
    /**
     * 获取指定小区下所有的有效发布房源数量
     *
     */
    public function getTotalByParkId($parkId, $houseType = 'all')
    {
        if(empty($parkId))
        {
            return false;
        }
        if(empty($houseType))
        {
            return false;
        }

        $where = " status=" . self::STATUS_ONLINE;
        $where .=" and parkId=" . $parkId;

        return $this->getCount($where);
    }
    
    /**
     * 新增房源
     * @param type $data
     * @return type
     */
    public function addHouse($data)
    {
        $insertData = $this->_getInsertData($data);
        if(empty($data) || empty($insertData))
        {
            return array('status' => 1, 'info' => '数据为空');
        }
        if(0 != $insertData['status'])
        {
            return $insertData;
        }
        
        $this->begin();
        $houseObj = self::instance();
        $insertData['data']['createTime'] = date('Y-m-d H:i:s'); //创建时间
        $insertData['data']['status'] = $data['isPublish'] ? self::STATUS_ONLINE : self::STATUS_OFFLINE; //房源状态，是否发布
        
        if(!$houseObj->save($insertData['data']))
        {
            $this->rollback();
            return array('status' => 1, 'info' => '房源添加失败');
        }
        
        if(isset($data['houseDesc']))
        {
            $hmData = array(
                'houseId' => $houseObj->id,
                HouseMore::$descColumnName => $data['houseDesc']
            );
            $addRes = HouseMore::instance()->addHouseDesc($hmData);
            if(!$addRes)
            {
                $this->rollback();
                return array('status' => 1, 'info' => '房源添加失败');
            }
        }
        $v = $insertData['data'];
        $value = array();
        $value['id'] = (int) $houseObj->id;
        $value['houseId'] = (int) $houseObj->id;
        $value['parkId'] = (int) $v['parkId'];
        $value['distId'] = (int) $v['distId'];
        $value['regId'] = (int) $v['regId'];
        $value['housePrice'] = (float) $v['price'];
        $value['houseBuildType'] = (int) $v['buildType'];
        $value['houseBA'] = (float) $v['bA'];
        $value['houseBedRoom'] = (int) $v['bedRoom'];
        $value['houseLivingRoom'] = (int) $v['livingRoom'];
        $value['houseBathRoom'] = (int) $v['bathRoom'];
        $value['houseOrientation'] = (int) $v['orientation'];
        $value['houseDecoration'] = (int) $v['decoration'];
        $value['status'] = (int) $v['status'];
        $value['houseCreate'] = strtotime($v['createTime']) ? strtotime($v['createTime']) : 0;
        $value['houseUpdate'] = 0;
        $value['houseUnit'] = (float) (number_format($v['price']/$v['bA'], 2, '.', ''));
        $value['subwayLine'] = '';
        $value['subwaySite'] = '';
        $value['subwaySiteLine'] = '';
        $value['housePropertyType'] = (int) $v['propertyType'];
        $value['houseFeatures'] = '';
        $value['houseFloor'] = (int) $v['floor'];
        $value['houseFloorMax'] = (int) $v['floorMax'];
        $value['housePicId'] = 0;
        $value['housePicExt'] = '';
        $value['houseType'] = (int) $v['type'];
        $value['houseTags'] = (int) $v['parkId'];
        $value['cityId'] = (int) $v['cityId'];
        $value['houseRemark'] = $v['remark'];
        
        $parkInfo = Park::findFirst($v['parkId'], 0)->toArray();
        $value['parkName'] = $parkInfo['name'];
        //$value['parkAlias'] = $parkInfo['alias'];
        $value['houseAddress'] = $parkInfo['address'];
        
        $esRes = $this->addEs($value, 'house');
        if(!$esRes)
        {
            $this->rollback();
            return array('status' => 1, 'info' => '房源添加失败~');
        }
        
        $this->commit();
        return array('status' => 0, 'info' => '添加房源成功');
    }
    
    private function _getInsertData($data, $houseId = 0)
    {   
        isset($data['parkId']) && $insertData['parkId'] = $data['parkId']; //小区id
        isset($data['cityId']) && $insertData['cityId'] = $data['cityId']; //城市
        isset($data['distId']) && $insertData['distId'] = $data['distId']; //区域
        isset($data['regId']) && $insertData['regId'] = $data['regId']; //板块
        isset($data['userId']) && $insertData['userId'] = $data['userId'];
        isset($data['propertyType']) && $insertData['propertyType'] = $data['propertyType']; //物业类型
        isset($data['buildType']) && $insertData['buildType'] = $data['buildType']; //建筑类型
        isset($data['orientation']) && $insertData['orientation'] = $data['orientation']; //朝向
        isset($data['decoration']) && $insertData['decoration'] = $data['decoration']; //装修状况
        isset($data['floorPosition']) && $insertData['floorPosition'] = $data['floorPosition']; //楼层位置
        isset($data['floorMax']) && $insertData['floorMax'] = $data['floorMax']; //总楼层
        isset($data['listCount']) && $insertData['listCount'] = $data['listCount']; //电梯数量
        isset($data['unitNo']) && $insertData['unitNo'] = $data['unitNo']; //单元号
        isset($data['roomNo']) && $insertData['roomNo'] = $data['roomNo']; //房号
        isset($data['bedRoom']) && $insertData['bedRoom'] = $data['bedRoom']; //室
        isset($data['livingRoom']) && $insertData['livingRoom'] = $data['livingRoom']; //厅
        isset($data['bathRoom']) && $insertData['bathRoom'] = $data['bathRoom']; //卫
        isset($data['bA']) && $insertData['bA'] = $data['bA']; //建筑面积
        isset($data['uA']) && $insertData['uA'] = $data['uA']; //使用面积
        isset($data['handPrice']) && $insertData['handPrice'] = $data['handPrice']; //到手价
        isset($data['buyPrice']) && $insertData['buyPrice'] = $data['buyPrice']; //买入价
        isset($data['saleTax']) && $insertData['saleTax'] = $data['saleTax']; //营业税
        isset($data['tax']) && $insertData['tax'] = $data['tax']; //个税
        isset($data['isFiveYear']) && $insertData['isFiveYear'] = $data['isFiveYear']; //是否满五年
        isset($data['isOnlyOne']) && $insertData['isOnlyOne'] = $data['isOnlyOne']; //是否唯一一套
        isset($data['propertyOwner']) && $insertData['propertyOwner'] = $data['propertyOwner']; //产权人
        isset($data['propertyPhone']) && $insertData['propertyPhone'] = $data['propertyPhone']; //产权人联系方式
        isset($data['agent']) && $insertData['agent'] = $data['agent']; //代理人
        isset($data['agentPhone']) && $insertData['agentPhone'] = $data['agentPhone']; //代理人联系方式
        isset($data['isRent']) && $insertData['isRent'] = $data['isRent']; //租约
        isset($data['rentPrice']) && $insertData['rentPrice'] = $data['rentPrice']; //月租金
        isset($data['rentEndTime']) && $insertData['rentEndTime'] = $data['rentEndTime']; //租约到期时间
        isset($data['hasPark']) && $insertData['hasPark'] = $data['hasPark']; //车位
        isset($data['hasHukou']) && $insertData['hasHukou'] = $data['hasHukou']; //户口
        isset($data['isForeign']) && $insertData['isForeign'] = $data['isForeign']; //境外人士
        isset($data['price']) && $insertData['price'] = $data['price']; //价格
        isset($data['isMortgage']) && $insertData['isMortgage'] = $data['isMortgage']; //抵押
        isset($data['giveDetail']) && $insertData['give'] = $data['giveDetail']; //赠送细节
        isset($data['remark']) && $insertData['remark'] = $data['remark']; //备注        
        
        if($insertData['parkId'] && $insertData['unitNo'] && $insertData['roomNo'])
        {
            $isExist = $this->_isRoomExist($insertData['parkId'], $insertData['unitNo'], $insertData['roomNo'], $houseId);
            if($isExist)
            {
                return array('status' => 1, 'info' => '该套房源已存在');
            }
        } 
        $insertData['level'] = 'C';
        
        return array('status' => 0, 'data' => $insertData);
    }
    
    /**
     * 编辑房源
     * @param int  $id
     * @param type $data
     * @return type
     */
    public function editHouse($id, $data)
    {
        $updateData = $this->_getUpdateData($data);
        if(empty($data) || empty($updateData))
        {
            return array('status' => 1, 'info' => '数据为空');
        }
        if(0 != $updateData['status'])
        {
            return $updateData;
        }
        $house = self::findFirst($id);
        if(!$house)
        {
            return array('status' => 1, 'info' => '房源不存在');
        }
             
        $esData = array();
        $editData = $updateData['data'];
        isset($editData['floorMax']) && $esData['houseFloorMax'] = $editData['floorMax']; //总楼层
        isset($editData['price']) && $esData['housePrice'] = (float)$editData['price']; //价格
        isset($editData['status']) && $esData['status'] = $editData['status']; //状态
        isset($editData['remark']) && $esData['houseRemark'] = $editData['remark']; //状态
        isset($editData['price']) && $esData['houseUnit'] = (float)number_format($editData['price']/$house->bA, 2, '.', '');
        $esData['houseUpdate'] = time();
        
        $arrEsData = array(
            'id' => (int)$id,
            'data' => $esData
        );
        $esRes = $this->editEs($arrEsData, 'house');
        $this->begin();
        if(!$esRes)
        {
            return array('status' => 1, 'info' => '编辑房源失败~');
        }
        if(!$house->update($updateData['data']))
        {
            $this->rollback();
            return array('status' => 1, 'info' => '编辑房源失败');
        }
        
        //房源描述
        $descData = array(
            'description' => $editData['houseDesc']
        );
        $descRes = HouseMore::instance()->modifyHouseDesc($id, $descData);
        if(!$descRes)
        {
            $this->rollback();
            return array('status' => 1, 'info' => '编辑房源失败');
        }
        $this->commit();
        return array('status' => 0, 'info' => '编辑房源成功');
    }
    
    private function _getUpdateData($data, $houseId = 0)
    {   
        isset($data['floorMax']) && $insertData['floorMax'] = $data['floorMax']; //总楼层
        isset($data['listCount']) && $insertData['liftCount'] = $data['listCount']; //电梯数量
        isset($data['uA']) && $insertData['uA'] = $data['uA']; //使用面积
        isset($data['buyPrice']) && $insertData['buyPrice'] = $data['buyPrice']; //买入价
        isset($data['saleTax']) && $insertData['saleTax'] = $data['saleTax']; //营业税
        isset($data['tax']) && $insertData['tax'] = $data['tax']; //个税
        isset($data['isFiveYear']) && $insertData['isFiveYear'] = $data['isFiveYear']; //是否满五年
        isset($data['isOnlyOne']) && $insertData['isOnlyOne'] = $data['isOnlyOne']; //是否唯一一套
        isset($data['propertyOwner']) && $insertData['propertyOwner'] = $data['propertyOwner']; //产权人
        isset($data['propertyPhone']) && $insertData['propertyPhone'] = $data['propertyPhone']; //产权人联系方式
        isset($data['agent']) && $insertData['agent'] = $data['agent']; //代理人
        isset($data['agentPhone']) && $insertData['agentPhone'] = $data['agentPhone']; //代理人联系方式
        isset($data['isRent']) && $insertData['isRent'] = $data['isRent']; //租约
        isset($data['rentPrice']) && $insertData['rentPrice'] = $data['rentPrice']; //月租金
        isset($data['rentEndTime']) && $insertData['rentEndTime'] = $data['rentEndTime']; //租约到期时间
        isset($data['hasPark']) && $insertData['hasPark'] = $data['hasPark']; //车位
        isset($data['hasHukou']) && $insertData['hasHukou'] = $data['hasHukou']; //户口
        isset($data['isForeign']) && $insertData['isForeign'] = $data['isForeign']; //境外人士
        isset($data['price']) && $insertData['price'] = $data['price']; //价格
        isset($data['isMortgage']) && $insertData['isMortgage'] = $data['isMortgage']; //抵押
        isset($data['giveDetail']) && $insertData['give'] = $data['giveDetail']; //赠送细节
        isset($data['remark']) && $insertData['remark'] = $data['remark']; //备注 
        $insertData['houseDesc'] = $data['houseDesc'];
        
        if($data['isPublish'])
        {
            $insertData['status'] = self::STATUS_ONLINE;
        }
        $insertData['updateTime'] = date('Y-m-d H:i:s');
        
        return array('status' => 0, 'data' => $insertData);
    }
    
    private function _isRoomExist($parkId, $unitNo, $roomNo, $houseId)
    {
        $where = "parkId={$parkId} and unitNo='{$unitNo}' and roomNo='{$roomNo}'";
        $houseId > 0 && $where .= " and id<>{$houseId}";
        $houseNum = self::count($where);
        
        return $houseNum > 0 ? true : false;
    }
    
    /**
     * 新增委托房源
     * @param type $data
     * @return type
     */
    public function addWeituoHouse($data)
    {
        $insertData = $this->_getInsertWeituoData($data);
        if(empty($data) || empty($insertData))
        {
            return array('status' => 1, 'info' => '数据为空');
        }
        if(0 != $insertData['status'])
        {
            return $insertData;
        }
        
        $this->begin();
        $houseObj = self::instance();
        $insertData['data']['createTime'] = date('Y-m-d H:i:s'); //创建时间
        
        if(!$houseObj->save($insertData['data']))
        {
            $this->rollback();
            return array('status' => 1, 'info' => '房源添加失败');
        }
        
        if(!empty($data['images']))
        {
            $delSql = "DELETE FROM house_picture WHERE houseId={$houseObj->id}";
            $delRes = HousePicture::instance()->execute($delSql);
            if(!$delRes)
            {
                $this->rollback();
                return array('status' => 1, 'info' => '房源添加失败');  
            }
            
            $addData = '';
            $timeNow = date('Y-m-d H:i:s');

            foreach($data['images'] as $v)
            {
                $id = $v['id'];
                $ext = $v['ext'];
                $status = HousePicture::STATUS_TOPASS;
                $addData .= "({$houseObj->id},{$id},'{$ext}',{$houseObj->parkId},{$status},'{$timeNow}'),";
            }
            $addData = rtrim($addData, ',');
            $addSql = "INSERT INTO house_picture(houseId,imgId,imgExt,parkId,picStatus,picUpdate) VALUES".$addData;
            
            $addRes = HousePicture::instance()->execute($addSql);
            if(!$addRes)
            {
                $this->rollback();
                return array('status' => 1, 'info' => '房源添加失败');  
            }
        }
        $v = $insertData['data'];
        $value = array();
        $value['id'] = (int) $houseObj->id;
        $value['houseId'] = (int) $houseObj->id;
        $value['parkId'] = (int) $v['parkId'];
        $value['housePrice'] = (float) $v['price'];
        $value['houseBA'] = (float) $v['bA'];
        $value['houseBedRoom'] = (int) $v['bedRoom'];
        $value['houseLivingRoom'] = (int) $v['livingRoom'];
        $value['houseBathRoom'] = (int) $v['bathRoom'];
        $value['status'] = (int) $v['status'];
        $value['houseCreate'] = strtotime($v['createTime']) ? strtotime($v['createTime']) : 0;
        $value['houseUpdate'] = 0;
        $value['houseUnit'] = (float) (number_format($v['price']/$v['bA'], 2, '.', ''));        
        $value['housePicId'] = 0;
        $value['housePicExt'] = '';
        $value['cityId'] = (int) $v['cityId'];
        
        $parkInfo = Park::findFirst($v['parkId'], 0)->toArray();
        $value['parkName'] = $parkInfo['name'];
        $value['houseAddress'] = $parkInfo['address'];
        
        $esRes = $this->addEs($value, 'house');
        if(!$esRes)
        {
            $this->rollback();
            return array('status' => 1, 'info' => '房源添加失败~');
        }
        
        $this->commit();
        return array('status' => 0, 'info' => '添加房源成功');
    }
    
    private function _getInsertWeituoData($data, $houseId = 0)
    {   
        isset($data['parkId']) && $insertData['parkId'] = $data['parkId']; //小区id
        isset($data['cityId']) && $insertData['cityId'] = $data['cityId']; //城市
        isset($data['userId']) && $insertData['userId'] = $data['userId'];
        isset($data['bedRoom']) && $insertData['bedRoom'] = $data['bedRoom']; //室
        isset($data['livingRoom']) && $insertData['livingRoom'] = $data['livingRoom']; //厅
        isset($data['bathRoom']) && $insertData['bathRoom'] = $data['bathRoom']; //卫
        isset($data['bA']) && $insertData['bA'] = $data['bA']; //建筑面积
        isset($data['agent']) && $insertData['agent'] = $data['agent']; //代理人
        isset($data['agentPhone']) && $insertData['agentPhone'] = $data['agentPhone']; //代理人联系方式
        isset($data['price']) && $insertData['price'] = $data['price']; //价格     
    
        $insertData['type'] = self::TYPE_WEITUO;
        $insertData['status'] = self::STATUS_OFFLINE;
        $insertData['level'] = 'C';
        
        return array('status' => 0, 'data' => $insertData);
    }
}
