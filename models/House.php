<?php

use Aws\S3\Model\MultipartUpload\ParallelTransfer;

class House extends BaseModel
{

    //房源状态常量status
    const STATUS_ONLINE = 1; //发布
    const STATUS_OFFLINE = 2; //下架
    const STATUS_DELETE = 4; //删除
    const STATUS_RECYCLE = 5; //回收站
    const STATUS_VIOLATE = 6; //违规-- 已废弃， 请用下面 审核状态里面的违规
    const STATUS_SLEEP = 7; //休眠
    //房源类别houseType
    const TYPE_ZHENGZU = 10;
    const TYPE_HEZU = 11;
    const TYPE_XINFANG = 20;
    const TYPE_CIXIN = 21;
    const TYPE_ERSHOU = 22;
    //审核状态status 0:待审核 1:审核过 -1:违规
    const HOUSE_VERING = 0;
    const HOUSE_VERED = 1;
    const HOUSE_VERNO = -1;
    //是否推荐房源(精品房源)
    const FINE_YES = 1;
    const FINE_NO = 2;
    //房源类型常量type
    const TYPE_SALE = 2; //出售房源
    const TYPE_RENT = 1; //出租房源
    //房源类型1、中介 2、个人
    const ROLE_REALTOR = 1;
    const ROLE_SELF = 2;
    const ROLE_INDIX = 3; //独立经纪人
    //是否标签 tags 字段：0: 否 ，1:急 2:免 3:急 免
    const HOUSE_NOTAG = 0;
    const HOUSE_JITAG = 1;
    const HOUSE_FREETAG = 2;
    const HOUSE_ALLTAG = 3;
    //房源品质 quality 字段：0未知 1普通 2优质 3高清
    const QUALITY_NULL = 0;
    const QUALITY_COMMON = 1;
    const QUALITY_GOOD = 2;
    const QUALITY_HIGH = 3;
    //是否现房type_type
    const TYPE_TYPE_DEFAULT = 0; //二手房默认值
    const TYPE_TYPE_BEING = 1; //新房期房
    const TYPE_TYPE_DELIVER = 2; //新房现房
    const MIN_HOUSE_ID = 50000000;

    public $id;
    public $parkId = 0;
    public $regId = 0;
    public $distId = 0;
    public $cityId;
    public $hoId = 0;
    public $realId = 0;
    public $shopId = 0;
    public $areaId = 0;
    public $comId = 0;
    public $type;
    public $price = 0;
    public $unit = 0;
    public $bedRoom = 0;
    public $livingRoom = 0;
    public $bathRoom = 0;
    public $bA = 0;
    public $uA = 0;
    public $decoration = '';
    public $orientation = 0;
    public $floor = 0;
    public $floorMax = 0;
    public $buildType = 0;
    public $buildYear = 0;
    public $propertyType = 0;
    public $lookTime = 0;
    public $propertyNature = 0;
    public $picId = 0;
    public $picExt = 0;
    public $quality = 1;
    public $tags = 0;
    public $fine = 2;
    public $timing = 0;
    public $verification = 0;
    public $status = 1;
    public $tagTime = '0000-00-00 00:00:00';
    public $fineTime = '0000-00-00 00:00:00';
    public $delTime = '0000-00-00 00:00:00';
    public $create;
    public $houseUpdate; //请不要修改否则会影响columns中设置该字段
    public $xiajia = '0000-00-00 00:00:00';
    public $roleType = 1;
    public $typeType = 0;
    public $openDate = '0000-00-00';
    public $deliverDate = '0000-00-00';
    public $auditing = "0000-00-00";
    public $code = 0;

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
            'hoId' => 'hoId',
            'realId' => 'realId',
            'shopId' => 'shopId',
            'areaId' => 'areaId',
            'comId' => 'comId',
            'houseType' => 'type',
            'housePrice' => 'price',
            'houseUnit' => 'unit',
            'houseBedRoom' => 'bedRoom',
            'houseLivingRoom' => 'livingRoom',
            'houseBathRoom' => 'bathRoom',
            'houseBA' => 'bA',
            'houseUA' => 'uA',
            'houseDecoration' => 'decoration',
            'houseOrientation' => 'orientation',
            'houseFloor' => 'floor',
            'houseFloorMax' => 'floorMax',
            'houseBuildType' => 'buildType',
            'houseBuildYear' => 'buildYear',
            'housePropertyType' => 'propertyType',
            'houseLookTime' => 'lookTime',
            'housePropertyNature' => 'propertyNature',
            'housePicId' => 'picId',
            'housePicExt' => 'picExt',
            'houseQuality' => 'quality',
            'houseTags' => 'tags',
            'houseFine' => 'fine',
            'houseTiming' => 'timing',
            'houseVerification' => 'verification',
            'houseStatus' => 'status',
            'houseDelTime' => 'delTime',
            'houseTagTime' => 'tagTime',
            'houseFineTime' => 'fineTime',
            'houseUpdate' => 'houseUpdate',
            'houseCreate' => 'create',
            'houseXiajia' => 'xiajia',
            'houseRoleType' => 'roleType',
            "houseTypeType" => 'typeType',
            'houseOpenDate' => 'openDate',
            'houseDeliverDate' => 'deliverDate',
            'houseCode' => 'code',
            'houseAuditing' => 'auditing',
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
        if($houseType == 'Sale')
        {
            $where.=" and (type=" . self::TYPE_ERSHOU . " or type=" . self::TYPE_CIXIN . " or type=" . self::TYPE_XINFANG . ")";
        } elseif($houseType == 'Rent')
        {
            $where.=" and (type=" . self::TYPE_ZHENGZU . " or type=" . self::TYPE_HEZU . ")";
        } else
        {
            $where.=" and (type=" . self::TYPE_ERSHOU . " or type=" . self::TYPE_CIXIN . " or type=" . self::TYPE_XINFANG . " or type=" . self::TYPE_ZHENGZU . " or type=" . self::TYPE_HEZU . ")";
        }

        return $this->getCount($where);
    }
}
