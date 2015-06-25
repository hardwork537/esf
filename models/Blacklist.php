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

}
