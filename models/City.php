<?php

class City extends BaseModel
{

    protected $id;
    protected $name;
    protected $abbr = '';
    protected $pinyin;
    protected $pinyinAbbr;
    protected $status;
    protected $updateTime;
    protected $avgPrice = 0;
    protected $increase = 0;
    protected $weight = 0;
    protected $code;

    const STATUS_ENABLED = 1; // 启用中
    const STATUS_DISABLED = 0; // 未启用
    const STATUS_WASTED = - 1; // 废弃

    public function getSource()
    {
        return 'city';
    }

    public function columnMap()
    {
        return array(
            'cityId' => 'id',
            'cityName' => 'name',
            'cityAbbr' => 'abbr',
            'cityPinyin' => 'pinyin',
            'cityPinyinAbbr' => 'pinyinAbbr',
            'cityStatus' => 'status',
            'cityUpdate' => 'updateTime',
            'cityWeight' => 'weight',
            'cityAvgPrice' => 'avgPrice',
            'cityIncrease' => 'increase',
            'cityCode' => 'code'
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
     * 获取城市
     * @param type $cityId
     * @return type
     */
    public static function getOptions($cityId = HEAD_CITY)
    {
        if($cityId == HEAD_CITY)
        {
            $where[] = "status=" . self::STATUS_ENABLED;
        } else
        {
            $where[] = "status=" . self::STATUS_ENABLED . " AND id=$cityId";
        }

        $rs = self::getAll($where);
        foreach($rs as $v)
        {
            $data[$v['id']] = $v['name'];
        }
        
        return $data;
    }

    /**
     * 返回状态
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
     * 添加城市
     *
     * @param array $arr
     * @return boolean
     */
    public function add($arr)
    {
        $rs = self::instance();
        $rs->name = $arr["name"];
        $rs->pinyin = $arr["pinyin"];
        $rs->weight = $arr["weight"];
        $rs->pinyinAbbr = $arr["pinyinAbbr"];
        $rs->status = intval($arr["status"]);
        $rs->updateTime = date("Y-m-d H:i:s");

        if($rs->create())
        {
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
    public function edit($cityId, $arr)
    {
        $cityId = intval($cityId);
        $rs = self::findfirst($cityId);
        $rs->name = $arr["name"];
        $rs->pinyin = $arr["pinyin"];
        $rs->weight = $arr["weight"];
        $rs->pinyinAbbr = $arr["pinyinAbbr"];
        $rs->status = intval($arr["status"]);
        $rs->updateTime = date("Y-m-d H:i:s");

        if($rs->update())
        {
            return true;
        }
        return false;
    }

    /**
     * 删除单条记录
     *
     * @param unknown $where
     */
    public function del($where)
    {
        $rs = self::findFirst($where);
        if($rs->delete())
        {
            return true;
        }
        return false;
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
            "conditions" => "status=" . self::STATUS_ENABLED,
            "order" => "weight asc",
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

        return $cityId ? (array) $res[$cityId] : $res;
    }

}
