<?php

class Blacklist extends BaseModel
{

    protected $id; 
    protected $operator;
    protected $phone;
    protected $remark = '';
    protected $addTime;

    public function getSource()
    {
        return 'blacklist';
    }

    public function columnMap()
    {
        return array(
            'blId' => 'id',
            'blOperator' => 'operator',
            'blPhone' => 'phone',
            'remark' => 'remark',
            'blAddTime' => 'addTime'
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
    }

    /**
     * 批量添加
     * @param type $data
     * @return type
     */
    public function addBlackList($operatorId, $data = array())
    {
        if(!is_array($data) || count($data) == 0)
        {
            return array('status'=>1, 'info'=>'数据为空');
        }
        $phones = array_keys($data);
        
        $conditions = array(
            'conditions' => "phone in(".  implode(',', $phones).")",
            'columns' => 'phone'
        );
        $res = self::find($conditions, 0)->toArray();
        foreach($res as $v)
        {
            unset($data[$v['phone']]);
        }
        if(empty($data))
        {
            return array('status'=>0);
        }
        $operatorId = intval($operatorId);
        $addSql = "INSERT INTO ".$this->getSource()."(blOperator,blPhone,remark,blAddTime) VALUES";
        $i = 0;
        foreach($data as $v)
        {
            if($i > 0)
            {
                $addSql .= ',';
            }
            $phone = $v['phone'];
            $remark = $v['remark'];
            $addTime = date('Y-m-d H:i:s');
            
            $addSql .= "({$operatorId},'{$phone}','{$remark}','{$addTime}'),";
        }
        $addSql = rtrim($addSql, ',');
        $addRes = self::instance()->execute($addSql);
        if($addRes)
        {
            return array('status'=>0);
        } else {
            return array('status'=>1, 'info'=>'导入失败');
        }
    }
}
