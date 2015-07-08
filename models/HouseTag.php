<?php

class HouseTag extends BaseModel
{

    protected $id;
    protected $name;
    protected $type = 0;
    protected $addTime = '0000-00-00 00:00:00';
    protected $cityId;

    public function getSource()
    {
        return 'house_tag';
    }

    public function columnMap()
    {
        return array(
            'id' => 'id',
            'cityId' => 'cityId',
            'type' => 'type',
            'name' => 'name',
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
     * 添加标签
     * @param type $data
     * @return type
     */
    public function add($data)
    {
        if(empty($data))
        {
            return array('status' => 1, 'info' => '参数为空！');
        }
        if($this->isExistTagName($data["tagName"], $data["cityId"]))
        {
            return array('status' => 1, 'info' => '标签名称已经存在！');
        }

        $rs = self::instance();
        $rs->cityId = $data["cityId"];
        $rs->name = $data["tagName"];
        $rs->addTime = date("Y-m-d H:i:s");

        if($rs->create())
        {
            return array('status' => 0, 'info' => '添加标签成功！');
        }
        return array('status' => 1, 'info' => '添加标签失败！');
    }
    
    private function isExistTagName($tagName, $cityId, $tagId = 0)
    {
        $tagName = trim($tagName);
        if(empty($tagName))
        {
            return true;
        }
        $con['conditions'] = "name='{$tagName}' and cityId={$cityId}";
        $tagId > 0 && $con['conditions'] .= " and id<>{$tagId}";

        $intCount = self::count($con);
        if($intCount > 0)
        {
            return true;
        }
        return false;
    }
    
    /**
     * 删除标签
     * @param type $tagId
     * @return type
     */
    public function del($tagId)
    {
        $tag = self::findFirst($tagId);
        if(!$tag)
        {
            return array('status' => 1, 'info' => '标签不存在');
        }
        
        $status = $tag->delete() ? 0 : 1;
        
        return array('status' => $status);
    }
    
    /**
     * 获取城市标签
     * @param type $cityId
     * @param type $columns
     * @return type
     */
    public function getTagsForOption($cityId = 0, $columns = '')
    {
        $where = '';
        $cityId && $where .= "cityId={$cityId}";
        
        $condition = array(
            'conditions' => $where
        );
        $columns && $condition['columns'] = $columns;
        
        $res = self::find($condition, 0)->toArray();
        
        $returnData = array();
        foreach($res as $v)
        {
            $returnData[$v['id']] = $v['name'];
        }
        
        return $returnData;
    }
}
