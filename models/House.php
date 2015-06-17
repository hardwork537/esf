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
    
    public $id;
    public $parkId;
    public $regId;
    public $distId;
    public $cityId;
    public $type;
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
    public $delTime;
    public $auditingTime;
    public $xiajiaTime;
    public $createTime;
    public $updateTime;
    public $deliverDate;

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
            'houseType' => 'type',
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
        $this->setReadConnectionService('esfSlave');
        $this->setWriteConnectionService('esfMaster');
        $this->hasManyToMany(
            'houseId', 'Sale', 'houseId', 'HouseExt', 'houseId', 'HouseMore', 'houseId'
        );
    }

    public function onConstruct()
    {
        \Phalcon\Mvc\Model::setup(array(
            'notNullValidations' => false
        ));
    }

    /**
     * 租约状态
     * @return type
     */
    public static function getAllRentStatus()
    {
        return array(
            self::IS_RENT => '是',
            self::NO_RENT => '否'
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
            self::IS_MORTGAGE => '是',
            self::NO_MORTGAGE => '否'
        );
    }
    
    /**
     * 户口状态 
     * @return type
     */
    public static function getAllHukouStatus()
    {
        return array(
            self::HAS_HUKOU => '是',
            self::NO_HUKOU => '否'
        );
    }
    
    /**
     * 境外人士状态
     * @return type
     */
    public static function getAllForeignStatus()
    {
        return array(
            self::IS_FOREIGN => '是',
            self::NO_FOREIGN => '否'
        );
    }
    
    /**
     * 是否满五年
     * @return type
     */
    public static function getAllFiveYearStatus()
    {
        return array(
            self::IS_FIVEYEAR => '是',
            self::NO_FIVEYEAR => '否'
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
}
