<?php

class CityRegion extends BaseModel
{

    /**
     * 板块状态，未启用0、启用1、废弃-1
     */
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const STATUS_DEL = -1;

    public $id;
    public $distId;
    public $cityId;
    public $name;
    public $abbr;
    public $pinyin;
    public $pinyinAbbr;
    public $pinyinFirst;
    public $X;
    public $Y;
    public $BX;
    public $BY;
    public $status;
    public $updateTime;
    public $weight;
    public $avgPrice = 0;
    public $increase = 0;
    public $price = 0;
    public $bX = 0;
    public $bY = 0;

    public function getSource()
    {
        return 'city_region';
    }

    public function columnMap()
    {
        return array(
            'regId' => 'id',
            'distId' => 'distId',
            'cityId' => 'cityId',
            'regName' => 'name',
            'regAbbr' => 'abbr',
            'regPinyin' => 'pinyin',
            'regPinyinAbbr' => 'pinyinAbbr',
            'regPinyinFirst' => 'pinyinFirst',
            'regPrice' => 'price',
            'regX' => 'X',
            'regBX' => 'BX',
            'regY' => 'Y',
            'regBY' => 'BY',
            'regLonLat' => 'lonLat',
            'RegPrice' => 'price',
            'regStatus' => 'status',
            'regUpdate' => 'updateTime',
            'regWeight' => 'weight',
            'regAvgPrice' => 'avgPrice',
            'regIncrease' => 'increase',
            'regBX' => 'bX',
            'regBY' => 'bY',
        );
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

    public function initialize()
    {
        $this->setConn('esf');
    }

    /**
     * 获取状态
     * @param type $status
     * @return type
     */
    public static function getStatus($status = 0)
    {
        $allStatus = array(
            self::STATUS_ON => '启用',
            self::STATUS_OFF => '未启用',
            self::STATUS_DEL => '废弃'
        );

        return $status ? $allStatus[$status] : $allStatus;
    }

    /**
     * 添加板块
     *
     * @param array $arr
     * @return boolean
     */
    public function add($arr)
    {
        $bxy = BaiduMap::instance()->getLonLat($arr["X"], $arr["Y"]);

        $this->cityId = $arr["cityId"];
        $this->distId = $arr["distId"];
        $this->name = $arr["name"];
        $this->abbr = $arr["abbr"];
        $this->pinyin = $arr["pinyin"];
        $this->pinyinAbbr = $arr["pinyinAbbr"];
        $this->pinyinFirst = $arr["pinyinFirst"];
        $this->X = $arr["X"];
        $this->Y = $arr["Y"];
        $this->BX = $bxy['x'];
        $this->BY = $bxy['y'];
        $this->status = intval($arr["status"]);
        $this->weight = intval($arr["weight"]);
        $this->updateTime = date("Y-m-d H:i:s");

        if($this->create())
        {
            return $this->id;
        }
        return false;
    }

    /**
     * 编辑板块信息
     *
     * @param unknown $cityId
     * @param unknown $arr
     * @return boolean
     */
    public function edit($regId, $arr)
    {
        $regId = intval($regId);
        $bxy = BaiduMap::instance()->getLonLat($arr["X"], $arr["Y"]);
        $rs = self::findfirst($regId);
        $rs->cityId = $arr["cityId"];
        $rs->distId = $arr["distId"];
        $rs->name = $arr["name"];
        $rs->abbr = $arr["abbr"];
        $rs->pinyin = $arr["pinyin"];
        $rs->pinyinAbbr = $arr["pinyinAbbr"];
        $rs->pinyinFirst = $arr["pinyinFirst"];
        $rs->X = $arr["X"];
        $rs->Y = $arr["Y"];
        $rs->BX = $bxy['x'];
        $rs->BY = $bxy['y'];
        $rs->status = intval($arr["status"]);
        $rs->weight = intval($arr["weight"]);
        $rs->updateTime = date("Y-m-d H:i:s");

        if($rs->update())
        {
            return true;
        }
        foreach($rs->getMessages() as $message)
        {
            echo $message;
        }

        return false;
    }

}
