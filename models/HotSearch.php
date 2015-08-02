<?php

class  HotSearch extends BaseModel
{

    protected $id;
    protected $cityId;
    protected $name = '';
    protected $url = '';
    protected $weight = 1;
    protected $status = self::STATUS_DISABLED;
    protected $addTime = '0000-00-00 00:00:00';

    const STATUS_ENABLED = 1; // 启用中
    const STATUS_DISABLED = 0; // 未启用

    public function getSource()
    {
        return 'hot_search';
    }

    public function columnMap()
    {
        return array(
            'id' => 'id',
            'cityId' => 'cityId',
            'name' => 'name',
            'url' => 'url',
            'weight' => 'weight',
            'status' => 'status',
            'addTime' => 'addTime'
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
     * 返回状态
     * @param type $status
     * @return type
     */
    public static function getStatus($status = 0)
    {
        $allStatus = array(
            self::STATUS_ENABLED => '启用',
            self::STATUS_DISABLED => '未启用'
        );
        
        return isset($status) ? $allStatus[$status] : $allStatus;
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
        $rs->cityId = $arr['cityId'];
        $rs->name = $arr["name"];
        $rs->url = $arr["url"];
        $rs->weight = $arr["weight"];
        $rs->status = intval($arr["status"]);
        $rs->addTime = date("Y-m-d H:i:s");

        if($rs->create())
        {
            return array('status'=>0, 'info'=>'添加成功');
        }
        return array('status'=>1, 'info'=>'添加失败');
    }

    /**
     * 编辑城市信息
     *
     * @param unknown $cityId
     * @param unknown $arr
     * @return boolean
     */
    public function edit($id, $arr)
    {
        $id = intval($id);
        $rs = self::findfirst($id);
        
        $rs->name = $arr["name"];
        $rs->url = $arr["url"];
        $rs->weight = $arr["weight"];
        $rs->status = intval($arr["status"]);
        $rs->cityId = $arr['cityId'];

        if($rs->update())
        {
            return array('status'=>0, 'info'=>'修改成功');
        }
        return array('status'=>1, 'info'=>'修改失败');
    }

    /**
     * 删除单条记录
     *
     * @param unknown $where
     */
    public function del($id)
    {
        $rs = self::findFirst($id);
        if($rs->delete())
        {
            return array('status'=>0, 'info'=>'删除成功');
        }
        return array('status'=>1, 'info'=>'删除成功');
    }
    
    /**
     * 获取热门搜索
     * @param type $cityId
     * @return type
     */
    public function getHotSearchByCityId($cityId)
    {
        $memKey = "fym_hot_search_cityId_" . $cityId;
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            return $memValue;
        }
        $data = array();
        $where = "cityId={$cityId} and status=".self::STATUS_ENABLED;
        $condition = array(
            'conditions' => $where,
            'order' => 'weight asc',
            'columns' => 'name,url'
        );
        $res = self::find($condition, 0)->toArray();
        if(!empty($res))
        {
            $data = $res;
            Mem::Instance()->Set($memKey, $data, 300);
        }
        
        return $data;
    }
}
