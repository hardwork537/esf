<?php

class User extends BaseModel
{   
    public $id;
    public $telphone;
    public $openid;
    public $unionid;
    public $px;
    public $py;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id'       => 'id',
            'telphone' => 'telphone',
            'openid'   => 'openid',
            'unionid'  => 'unionid', 
            'px'       => 'px', 
            'py'       => 'py'
        );
    }

    public function initialize ()
    {
        $this->setConn('wx');
    }
    
    public function getSource ()
    {
        return 'user';
    }
    
    /**
     * 单例对象
     * @return type
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__,$cache);
    }
}
