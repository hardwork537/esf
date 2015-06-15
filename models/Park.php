<?php

/**
 * @abstract 小区基础model
 *
 */
class Park extends BaseModel
{

    public $id;
    public $regId;
    public $distId;
    public $cityId;
    public $name;
    public $alias;
    public $pinyin;
    public $pinyinAbbr;
    public $address;
    public $postcode = '';
    public $X;
    public $Y;
    public $BdX = 0;
    public $BdY = 0;
    public $type;
    public $buildType = 0;
    public $buildYear;
    public $landArea;
    public $GFA;
    public $FAR;
    public $GR;
    public $RR = '';
    public $buildings = 0;
    public $houses;
    public $pCount;
    public $pFix = 0;
    public $fee;
    public $tags = '';
    public $picId = 0;
    public $status = self::STATUS_VALID;
    public $saleCount = 0;
    public $saleValid = 0;
    public $update;
    public $picExt = '';
    public $rentCount = 0;
    public $rentValid = 0;
    public $salePrice = 0;
    public $salePriceIncrease = 0;
    public $source = self::SOURCE_SECONDARY_PARK;
    public $allowWgPhoto = self::ALLOW_WAIGUAN_YES;
    public $allowHxPhoto = self::ALLOW_HUXING_YES;

    //小区来源
    const SOURCE_NEW_PARK = 1;       //新房小区
    const SOURCE_SECONDARY_PARK = 2; //二手房小区
    //数据状态  status
    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;
    const STATUS_DELETE = -1;
    //是否允许经纪人上传外观图
    const ALLOW_WAIGUAN_YES = 1;   //允许
    const ALLOW_WAIGUAN_NO = 2;   //不允许
    //是否允许经纪人上传户型图
    const ALLOW_HUXING_YES = 1; //允许
    const ALLOW_HUXING_NO = 2; //不允许

    public function columnMap()
    {
        return array(
            'parkId' => 'id',
            'regId' => 'regId',
            'distId' => 'distId',
            'cityId' => 'cityId',
            'parkName' => 'name',
            'parkAlias' => 'alias',
            'parkPinyin' => 'pinyin',
            'parkPinyinAbbr' => 'pinyinAbbr',
            'parkAddress' => 'address',
            'parkPostcode' => 'postcode',
            'parkX' => 'X',
            'parkY' => 'Y',
            'parkBX' => 'BdX', //请不要随便改动
            'parkBY' => 'BdY', //请不要随便改动
            'parkLonLat' => 'lonLat',
            'parkType' => 'type',
            'parkBuildType' => 'buildType',
            'parkBuildYear' => 'buildYear',
            'parkLandArea' => 'landArea',
            'parkGFA' => 'GFA',
            'parkFAR' => 'FAR',
            'parkGR' => 'GR',
            'parkRR' => 'RR',
            'parkBuildings' => 'buildings',
            'parkHouses' => 'houses',
            'parkPCount' => 'pCount',
            'parkPFix' => 'pFix',
            'parkFee' => 'fee',
            'parkTags' => 'tags',
            'parkPicId' => 'picId',
            'parkStatus' => 'status',
            'parkUpdate' => 'update',
            'parkSaleValid' => 'saleValid',
            'parkSaleCount' => 'saleCount',
            'parkPicExt' => 'picExt',
            'parkRentCount' => 'rentCount',
            'parkRentValid' => 'rentValid',
            'parkSalePrice' => 'salePrice',
            'parkSalePriceIncrease' => 'salePriceIncrease',
            'parkSource' => 'source',
            'parkAllowWgPhoto' => 'allowWgPhoto',
            'parkAllowHxPhoto' => 'allowHxPhoto'
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
        return new self;
    }

    /**
     * 获取所有小区来源
     * @return array
     */
    public static function getSourceTypes()
    {
        return array(
            self::SOURCE_NEW_PARK => '新房',
            self::SOURCE_SECONDARY_PARK => '二手房'
        );
    }

}
