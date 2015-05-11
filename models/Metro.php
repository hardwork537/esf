<?php

class Metro extends BaseModel
{

    protected $id;
    protected $cityId;
    protected $name;
    protected $alias = '';
    protected $status = self::STATUS_ENABLED;
    protected $updateTime;
    protected $weight = 0;

    const STATUS_ENABLED = 1;   //启用
    const STATUS_DISABLED = 0;   //未启
    const STATUS_WASTED = - 1; //废弃

    public function getSource()
    {
        return 'metro';
    }

    public function columnMap()
    {
        return array(
            'metroId' => 'id',
            'cityId' => 'cityId',
            'metroName' => 'name',
            'metroAlias' => 'alias',
            'metroStatus' => 'status',
            'metroUpdate' => 'updateTime',
            'metroWeight' => 'weight',
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
    }

    /**
     * 实例化
     * @param type $cache
     * @return Metro_Model
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self;
    }

    /**
     * 新增轨交字典
     * @param array $data
     * @return array
     */
    public function add($data)
    {
        if(empty($data))
        {
            return array('status' => 1, 'info' => '参数为空！');
        }
        if($this->isExistMetroName($data["metroName"], $data["cityId"]))
        {
            return array('status' => 1, 'info' => '轨道线路已经存在！');
        }

        $rs = self::instance();
        $rs->cityId = $data["cityId"];
        $rs->name = $data["metroName"];
        $rs->weight = $data["weight"];
        $rs->updateTime = date("Y-m-d H:i:s");

        if($rs->create())
        {
            return array('status' => 0, 'info' => '添加轨交成功！');
        }
        return array('status' => 1, 'info' => '添加轨交失败！');
    }

    /**
     * 编辑轨交信息
     *
     * @param int   $id
     * @param array $data
     * @return array
     */
    public function edit($id, $data)
    {
        if(empty($data))
        {
            return array('status' => 1, 'info' => '参数为空！');
        }
        if($this->isExistMetroName($data["metroName"], $data["cityId"], $id))
        {
            return array('status' => 1, 'info' => '轨交线路已经存在！');
        }

        $rs = self::findfirst($id);
        $rs->cityId = $data["cityId"];
        $rs->name = $data["metroName"];
        $rs->weight = $data["weight"];
        $rs->updateTime = date("Y-m-d H:i:s");

        if($rs->update())
        {
            return array('status' => 0, 'info' => '轨交修改成功！');
        }
        return array('status' => 1, 'info' => '轨交修改失败！');
    }

    private function isExistMetroName($metroName, $cityId, $metroId = 0)
    {
        $metroName = trim($metroName);
        if(empty($metroName))
        {
            return true;
        }
        $con['conditions'] = "name='{$metroName}' and cityId={$cityId} and status=" . self::STATUS_ENABLED;
        $metroId > 0 && $con['conditions'] .= " and id<>{$metroId}";

        $intCount = self::count($con);
        if($intCount > 0)
        {
            return true;
        }
        return false;
    }

    /**
     * 获取所有有效轨道线路信息，key为metroId，value为metroName
     * @param type $city_id
     * @return type
     */
    public function getLinesForOption($cityId)
    {
        $cityId = intval($cityId);
        $condition = array(
            'conditions' => 'cityId=' . $cityId . ' and status=' . self::STATUS_ENABLED,
            'order' => 'weight asc, id asc'
        );
        $lines = self::find($condition)->toArray();

        $tmp = array();
        foreach($lines as $line)
        {
            $tmp[$line['id']] = $line['name'];
        }

        return $tmp;
    }

}
