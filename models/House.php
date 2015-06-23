<?php

use Aws\S3\Model\MultipartUpload\ParallelTransfer;

class House extends BaseModel
{

    //房源状态常量status
    const STATUS_SAVE = 0; //保存待发布
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
    
    public $id;
    public $parkId;
    public $regId;
    public $distId;
    public $cityId;
    public $userId;
    public $type;
    public $level = '';
    public $price;
    public $handPrice;
    public $buyPrice;
    public $saleTax;
    public $tax;
    public $isFiveYear;
    public $isOnlyOne;
    public $propertyOwner;
    public $propertyPhone;
    public $agent;
    public $agentPhone;
    public $isRent;
    public $rentPrice;
    public $rentEndTime;
    public $hasPark;
    public $isMortgage;
    public $isForeign;
    public $hasHukou;
    public $give;
    public $remark;
    public $bedRoom;
    public $livingRoom;
    public $bathRoom;
    public $bA;
    public $uA;
    public $decoration;
    public $orientation;
    public $liftCount;
    public $unitNo;
    public $roomNo;
    public $floor;
    public $floorMax;
    public $buildType;
    public $floorPosition;
    public $propertyType;
    public $verification;
    public $status;
    public $delTime = '0000-00-00 00:00:00';
    public $auditingTime = '0000-00-00 00:00:00';
    public $xiajiaTime = '0000-00-00 00:00:00';
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
            'houseBuildType' => 'buildType',
            'houseFloorPosition' => 'floorPosition',
            'housePropertyType' => 'propertyType',
            'houseVerification' => 'verification',
            'houseStatus' => 'status',
            'houseDelTime' => 'delTime',
            'houseAuditing' => 'auditingTime',
            'houseXiajia' => 'xiajiaTime',
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
        
        $this->begin();
        $houseObj = self::instance();
        
        if(!$houseObj->save($insertData))
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
        
        $this->commit();
        return array('status' => 0);
    }
    
    private function _getInsertData($data, $houseId = 0)
    {   
        isset($data['parkId']) && $insertData['parkId'] = $data['parkId']; //小区id
        isset($data['cityId']) && $insertData['cityId'] = $data['cityId']; //城市
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
        isset($data['giveDetail']) && $insertData['giveDetail'] = $data['giveDetail']; //赠送细节
        isset($data['remark']) && $insertData['remark'] = $data['remark']; //备注
        
        if($insertData['parkId'] && $insertData['unitNo'] && $insertData['roomNo'])
        {
            $isExist = $this->_isRoomExist($insertData['parkId'], $insertData['unitNo'], $insertData['roomNo'], $houseId);
            if($isExist)
            {
                return array('status' => 1, 'info' => '该套房源已存在');
            }
        }
        
        return array('status' => 0, 'data' => $insertData);
    }
    
    private function _isRoomExist($parkId, $unitNo, $roomNo, $houseId)
    {
        $where = "parkId={$parkId} and unitNo='{$unitNo}' and roomNo='{$roomNo}'";
        $houseId > 0 && $where .= " and id<>{$houseId}";
        $houseNum = self::count($where);
        
        return $houseNum > 0 ? true : false;
    }
}
