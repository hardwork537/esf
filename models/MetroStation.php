<?php
class MetroStation extends BaseModel
{
    protected $id;
    protected $metroId;
    protected $cityId;
    protected $name;
    protected $alias = '';
    protected $weight = 0;
    protected $x;
    protected $y;
	protected $bx;
    protected $by;
    protected $lonLat = '';
    protected $status = self::STATUS_ENABLED;
    protected $update;

    const STATUS_ENABLED  = 1;   //启用
    const STATUS_DISABLED = 0;   //未启
    const STATUS_WASTED   = - 1; //废弃

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if(preg_match('/^\d{1,10}$/', $id == 0) || $id > 4294967295)
        {
            return false;
        }
        $this->id = $id;
    }

    public function getMetroId()
    {
        return $this->metroId;
    }

    public function setMetroId($metroId)
    {
        if(preg_match('/^\d{1,10}$/', $metroId == 0) || $metroId > 4294967295)
        {
            return false;
        }
        $this->metroId = $metroId;
    }

    public function getCityId()
    {
        return $this->cityId;
    }

    public function setCityId($cityId)
    {
        if(preg_match('/^\d{1,10}$/', $cityId == 0) || $cityId > 4294967295)
        {
            return false;
        }
        $this->cityId = $cityId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if($name == '' || mb_strlen($name, 'utf8') > 50)
        {
            return false;
        }
        $this->name = $name;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        if($alias == '' || mb_strlen($alias, 'utf8') > 50)
        {
            return false;
        }
        $this->alias = $alias;
    }

    public function getSeq()
    {
        return $this->seq;
    }

    public function setSeq($seq)
    {
        if(preg_match('/^\d{1,3}$/', $seq == 0) || $seq > 255)
        {
            return false;
        }
        $this->seq = $seq;
    }

    public function getXY()
    {
        return $this->xY;
    }

    public function setXY($xY)
    {
        if($xY == '' || mb_strlen($xY, 'utf8') > 50)
        {
            return false;
        }
        $this->xY = $xY;
    }

    public function getLonLat()
    {
        return $this->lonLat;
    }

    public function setLonLat($lonLat)
    {
        if($lonLat == '' || mb_strlen($lonLat, 'utf8') > 30)
        {
            return false;
        }
        $this->lonLat = $lonLat;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if(preg_match('/^-?\d{1,3}$/', $status) == 0 || $status > 127 || $status < -128)
        {
            return false;
        }
        $this->status = $status;
    }

    public function getUpdate()
    {
        return $this->update;
    }

    public function setUpdate($update)
    {
        if(preg_match('/^\d{4}-|\/\d{1,2}-|\/\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/', $update) == 0 || strtotime($update) == false)
        {
            return false;
        }
        $this->update = $update;
    }

    public function getSource()
    {
        return 'metro_station';
    }

    /**
     * 新增轨交站点字典
     * @param array $data
     * @return array
     */
    public function add($data)
    {
        if(empty($data))
        {
            return array('status'=>1, 'info'=>'参数为空！');
        }
        if($this->isDisableMetro($data['metroId'], $data["cityId"]))
        {
            return array('status'=>1, 'info'=>'轨道线路不存在或无效！');
        }
        if($this->isExistMetroStationName($data["msName"], $data["cityId"]))
        {
            return array('status'=>1, 'info'=>'轨道站点已经存在！');
        }
		$bxy = BaiduMap::instance()->getLonLat($data['msX'],$data['msY']);
		$this->bx = $bxy['x'];
        $this->by = $bxy['y'];
        $this->cityId = $data["cityId"];
        $this->metroId = $data["metroId"];
        $this->x = $data['msX'];
        $this->y = $data['msY'];
        $this->name = $data['msName'];
        $this->weight = $data["weight"];
        $this->update = date("Y-m-d H:i:s");

        if($this->create())
        {
            return array('status'=>0, 'info'=>'添加轨道站点成功！');
        }
        return array('status'=>1, 'info'=>'添加轨道站点失败！');
    }

    /**
     * 修改轨交站点字典
     * @param int   $id
     * @param array $data
     * @return array
     */
    public function edit($id, $data)
    {
        if(empty($data) || $id < 1)
        {
            return array('status'=>1, 'info'=>'参数无效！');
        }
        if($this->isDisableMetro($data['metroId'], $data["cityId"]))
        {
            return array('status'=>1, 'info'=>'轨道线路不存在或无效！');
        }
        if($this->isExistMetroStationName($data["msName"], $data["cityId"], $id))
        {
            return array('status'=>1, 'info'=>'轨道站点已经存在！');
        }
		$bxy = BaiduMap::instance()->getLonLat($data['msX'],$data['msY']);

        $rs = self::findFirst($id);
        $rs->cityId = $data["cityId"];
        $rs->metroId = $data["metroId"];
        $rs->x = $data['msX'];
        $rs->y = $data['msY'];
		$rs->bx = $bxy['x'];
        $rs->by = $bxy['y'];
        $rs->name = $data['msName'];
        $rs->weight = $data["weight"];
        $rs->update = date("Y-m-d H:i:s");


        if($rs->update())
        {
            return array('status'=>0, 'info'=>'修改轨道站点成功！');
        }
        return array('status'=>1, 'info'=>'修改轨道站点失败！');
    }

    /**
     * 判断轨交线路是否无效
     * @param int $metroId
     * @param int $cityId
     * @return boolean
     */
    private function isDisableMetro($metroId, $cityId)
    {
        $metroId = intval($metroId);
        $cityId = intval($cityId);

        $con['conditions'] = "id={$metroId} and cityId={$cityId} and status=" . Metro::STATUS_ENABLED;

        $intCount = Metro::count($con);
        if($intCount > 0)
        {
            return false;
        }
        return true;
    }
    /**
     * 检测站点是否已存在
     * @param string $metroName
     * @param int    $cityId
     * @param int    $metroId
     * @return boolean
     */
    private function isExistMetroStationName($msName, $cityId, $msId=0)
    {
        $msName = trim($msName);
        if(empty($msName))
        {
            return true;
        }
        $con['conditions'] = "name='{$msName}' and cityId={$cityId} and status=" . self::STATUS_ENABLED;
        $msId > 0 && $con['conditions'] .= " and id<>{$msId}";

        $intCount = self::count($con);
        if($intCount > 0)
        {
            return true;
        }
        return false;
    }

    public function columnMap()
    {
        return array(
            'msId'     => 'id',
            'metroId'  => 'metroId',
            'cityId'   => 'cityId',
            'msName'   => 'name',
            'msAlias'  => 'alias',
            'msWeight' => 'weight',
            'msX'      => 'x',
            'msY'      => 'y',
			'msBX'      => 'bx',
            'msBY'      => 'by',
            'msStatus' => 'status',
            'msUpdate' => 'update'
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
    }

    /**
     * 实例化
     * @param type $cache
     * @return MetroStation_Model
     */

    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self;
    }

    /**
     * 获取当前城市下该线路下的所有站点信息
     * @return array
     */
    public function getDataByMetroStationId($id){
        $id = intval($id);
        $key = MCDefine::SITEDATA_ONE_LINE.$id;
        //从MC中加载常量配置
        $arrData = Mem::Instance()->Get($key);
        if ( !empty($arrData) ) return $arrData;

        //从数据库中拿数据
        $arrCondition['conditions'] = 'metroId='.$id;
        $arrCondition['order'] = 'weight asc';
        $arrData = self::find($arrCondition,0)->toArray();
        $tmp = array();
        foreach($arrData as &$val){
            $tmp[$val['id']] = $val;
        }
        Mem::Instance()->Set($key, $tmp, 3600);
        return $tmp;
    }


}