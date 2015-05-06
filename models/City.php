<?php

class City extends BaseModel
{

    protected $id;

    protected $name;

    protected $abbr;

    protected $pinyin;

    protected $pinyinAbbr;

    protected $status;

    protected $update;

    protected $cityWeight;

    protected $avgPrice = 0;

    protected $increase = 0;

    protected $weight = 0;

    protected $code;

    const STATUS_ENABLED = 1; // 启用中
    const STATUS_DISABLED = 0; // 未启用
    const STATUS_WASTED = - 1; // 废弃


    public function getCityId ()
    {
        return $this->cityId;
    }

    public function setCityId ($cityId)
    {
        if (preg_match('/^\d{1,10}$/', $cityId == 0) || $cityId > 4294967295) {
            return false;
        }
        $this->cityId = $cityId;
    }

    public function getCityName ()
    {
        return $this->cityName;
    }

    public function setCityName ($cityName)
    {
        if ($cityName == '' || mb_strlen($cityName, 'utf8') > 50) {
            return false;
        }
        $this->cityName = $cityName;
    }

    public function getCityAbbr ()
    {
        return $this->cityAbbr;
    }

    public function setCityAbbr ($cityAbbr)
    {
        if ($cityAbbr == '' || mb_strlen($cityAbbr, 'utf8') > 10) {
            return false;
        }
        $this->cityAbbr = $cityAbbr;
    }

    public function getCityPinyin ()
    {
        return $this->cityPinyin;
    }

    public function setCityPinyin ($cityPinyin)
    {
        if ($cityPinyin == '' || mb_strlen($cityPinyin, 'utf8') > 50) {
            return false;
        }
        $this->cityPinyin = $cityPinyin;
    }

    public function getCityPinyinAbbr ()
    {
        return $this->cityPinyinAbbr;
    }

    public function setCityPinyinAbbr ($cityPinyinAbbr)
    {
        if ($cityPinyinAbbr == '' || mb_strlen($cityPinyinAbbr, 'utf8') > 50) {
            return false;
        }
        $this->cityPinyinAbbr = $cityPinyinAbbr;
    }

    public function getCityStatus ()
    {
        return $this->cityStatus;
    }

    public function setCityStatus ($cityStatus)
    {
        if (preg_match('/^-?\d{1,3}$/', $cityStatus) == 0 || $cityStatus > 127 || $cityStatus < - 128) {
            return false;
        }
        $this->cityStatus = $cityStatus;
    }

    public function getCityUpdate ()
    {
        return $this->cityUpdate;
    }

    public function setCityUpdate ($cityUpdate)
    {
        if (preg_match('/^\d{4}-|\/\d{1,2}-|\/\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/', $cityUpdate) == 0 || strtotime($cityUpdate) == false) {
            return false;
        }
        $this->cityUpdate = $cityUpdate;
    }

    public function getAvgPrice ()
    {
    	return $this->avgPrice;
    }

    public function setAvgPrice ($avgPrice)
    {
    	$this->avgPrice = $avgPrice;
    }

    public function getIncrease ()
    {
    	return $this->increase;
    }

    public function setIncrease ($increase)
    {
    	$this->increase = $increase;
    }

    public function getSource ()
    {
        return 'city';
    }

    public static function getOptions ($cityId=HEAD_CITY)
    {        
        if($cityId==HEAD_CITY){
            $where[] = "status=" . self::STATUS_ENABLED ;
        }else{
            $where[] = "status=" . self::STATUS_ENABLED ." AND id=$cityId" ;
        }
        
        $rs = self::getAll($where);
        foreach ($rs as $v) {
            $data[$v['id']] = $v['name'];
        }
        return $data;
    }

    public static function getOptionsByCityId ($cityId)
    {
        if($cityId==HEAD_CITY){
            $where[] = "status=" . self::STATUS_ENABLED ;
        }else{
            $where[] = "status=" . self::STATUS_ENABLED ." AND id=$cityId" ;
        }

        $rs = self::getAll($where);

        foreach ($rs as $v) {
            $data[$v['id']] = $v['name'];
        }
        return $data;
    }

    public function columnMap ()
    {
        return array(
                'cityId' => 'id',
                'cityName' => 'name',
                'cityAbbr' => 'abbr',
                'cityPinyin' => 'pinyin',
                'cityPinyinAbbr' => 'pinyinAbbr',
                'cityStatus' => 'status',
                'cityUpdate' => 'update',
                'cityWeight'    =>  'weight',
        		'cityAvgPrice' => 'avgPrice',
        		'cityIncrease' => 'increase',
                'cityCode'=>'code'
        );
    }

    public function initialize ()
    {
        $this->setConn('esf');
    }

    /**
     * 实例化对象
     *
     * @param type $cache
     * @return \Users_Model
     */
    public static function instance ($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self();
    }

    /**
     * 添加城市
     *
     * @param array $arr
     * @return boolean
     */
    public function add ($arr)
    {
        $rs = self::instance();
        $rs->name = $arr["name"];
        $rs->abbr = $arr["abbr"];
        $rs->pinyin = $arr["pinyin"];
		$rs->weight = $arr["weight"];
        $rs->pinyinAbbr = $arr["pinyinAbbr"];
        $rs->status = intval($arr["status"]);
        $rs->update = date("Y-m-d H:i:s");

        if ($rs->create()) {
            return true;
        }
        return false;
    }

    /**
     * 编辑城市信息
     *
     * @param unknown $cityId
     * @param unknown $arr
     * @return boolean
     */
    public function edit ($cityId, $arr)
    {
        $cityId = intval($cityId);
        $rs = self::findfirst($cityId);
        $rs->name = $arr["name"];
        $rs->abbr = $arr["abbr"];
        $rs->pinyin = $arr["pinyin"];
        $rs->weight = $arr["weight"];
        $rs->pinyinAbbr = $arr["pinyinAbbr"];
        $rs->status = intval($arr["status"]);
        $rs->update = date("Y-m-d H:i:s");

        if ($rs->update()) {
            return true;
        }
        return false;
    }

    /**
     * 删除单条记录
     *
     * @param unknown $where
     */
    public function del ($where)
    {
        $rs = self::findFirst($where);
        if ($rs->delete()) {
            return true;
        }
        return false;
    }

    /**
     * @abstract 根据城市ID获取城市信息
     * @author Eric xuminwan@sohu-inc.com
     * @param int $cityId
     * @return array|bool
     *
     */
    public function getCityById($cityId)
    {
    	if(!$cityId) return false;
    	$arrCond  = "id = ?1 and status = ".self::STATUS_ENABLED;
    	$arrParam = array(1=>$cityId);
        $arrCondition['conditions'] = $arrCond;
        $arrCondition['bind'] = $arrParam;
    	$arrCity = self::findFirst($arrCondition, 0)->toArray();
    	return $arrCity;
    }
    
    /**
     * 获取城市信息
     * @param int    $cityId
     * @param string $column
     * @return array
     */
    public function getAllCity($cityId = 0, $column = '')
    {
        $condition = array(
            "conditions" => "status=".self::STATUS_ENABLED,
            "order"      => "weight asc",
        );
        if($column)
        {
            $condition['columns'] = $column;
        }
        $cities = self::find($condition, 0)->toArray();
        $res = array();
        foreach($cities as $value)
        {
            $res[$value['id']] = $value;
        }
        
        return $cityId ? (array)$res[$cityId] : $res;
    }
}