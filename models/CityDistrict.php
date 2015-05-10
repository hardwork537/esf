<?php

class CityDistrict extends BaseModel
{

    protected $id;
    protected $cityId;
    protected $name;
    protected $abbr;
    protected $pinyin;
    protected $maxPrice = 0;
    protected $X;
    protected $Y;
    protected $BX;
    protected $BY;
    protected $status;
    protected $updateTime;
    protected $distWeight;
    protected $avgPrice = 0;
    protected $increase = 0;
    protected $weight = 0;

    const STATUS_ENABLED = 1; // 启用中
    const STATUS_DISABLED = 0; // 未启用
    const STATUS_WASTED = - 1; // 废弃


    public function getSource()
    {
        return 'city_district';
    }

    public function columnMap()
    {
        return array(
            'distId' => 'id',
            'cityId' => 'cityId',
            'distName' => 'name',
            'distAbbr' => 'abbr',
            'distPinyin' => 'pinyin',
            'distMaxPrice' => 'maxPrice',
            'distX' => 'X',
            'distBX' => 'BX',
            'distY' => 'Y',
            'distBY' => 'BY',
            'distLonLat' => 'lonLat',
            'distStatus' => 'status',
            'distUpdate' => 'updateTime',
            'distWeight' => 'weight',
            'distAvgPrice' => 'avgPrice',
            'distIncrease' => 'increase',
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
     * 获取状态
     * @param type $status
     * @return type
     */
    public static function getStatus($status = 0)
    {
        $allStatus = array(
            self::STATUS_ENABLED => '启用',
            self::STATUS_DISABLED => '未启用',
            self::STATUS_WASTED => '废弃'
        );
        
        return $status ? $allStatus[$status] : $allStatus;
    }
    
    /**
     * 添加城区
     *
     * @param array $arr
     * @return boolean
     */
    public function add($arr)
    {
		$bxy = BaiduMap::instance()->getLonLat($arr["X"],$arr["Y"]);
		$this->BX = $bxy['x'];
        $this->BY = $bxy['y'];
        $this->cityId = $arr["cityId"];
        $this->name = $arr["name"];
        $this->abbr = $arr["abbr"];
        $this->pinyin = $arr["pinyin"];
		$this->weight = $arr["weight"];
        $this->X= intval($arr["X"]);
        $this->Y= intval($arr["Y"]);
        $this->status = intval($arr["status"]);
        $this->weight = intval($arr["weight"]);
        $this->updateTime = date("Y-m-d H:i:s");

        if ($this->create()) {
            return true;
        }
        return false;
    }
    
    /**
     * 编辑城区信息
     *
     * @param unknown $cityId
     * @param unknown $arr
     * @return boolean
     */
    public function edit ($distId, $arr)
    {
        $distId = intval($distId);
		$bxy = BaiduMap::instance()->getLonLat($arr["X"],$arr["Y"]);
        $rs = self::findfirst($distId);
        $rs->cityId = $arr["cityId"];
        $rs->name = $arr["name"];
        $rs->abbr = $arr["abbr"];
        $rs->pinyin = $arr["pinyin"];
		$rs->weight = $arr["weight"];
        $rs->X = floatval($arr["X"]);
        $rs->Y = floatval($arr["Y"]);
		$rs->BX = $bxy['x'];
        $rs->BY = $bxy['y'];
        $rs->status = intval($arr["status"]);
        $rs->weight = intval($arr["weight"]);
        $rs->updateTime = date("Y-m-d H:i:s");

        if ($rs->save()) {
            return true;
        }

        return false;
    }
}
