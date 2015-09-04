<?php

class Phone400 extends BaseModel
{

    protected $id;
    protected $mobile = '';
    protected $phoneHost = '';
    protected $phoneExt = '';
    protected $pinyinAbbr;
    protected $addTime = '0000-00-00 00:00:00';
    protected $updateTime = '0000-00-00 00:00:00';

    public function getSource()
    {
        return 'phone400';
    }

    public function columnMap()
    {
        return array(
            'id' => 'id',
            'mobile' => 'mobile',
            'phoneHost' => 'phoneHost',
            'phoneExt' => 'phoneExt',
            'addTime' => 'addTime',
            'updateTime' => 'updateTime'
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
     * 添加400电话绑定
     * @param type $mobile
     * @param type $phoneHost
     * @param type $phoneExt
     * @return type
     */
    public function add($mobile, $phoneHost, $phoneExt)
    {
        $checkRes = $this->_isMobileOrPhoneExist($mobile, $phoneHost, $phoneExt);
        if(0 !== $checkRes['status'])
        {
            return $checkRes;
        }
        $rs = self::instance();
        $rs->mobile = $mobile;
        $rs->phoneHost = $phoneHost;
        $rs->phoneExt = $phoneExt;
        $rs->addTime = date("Y-m-d H:i:s");

        if($rs->create())
        {
            return array('status' => 0, 'inf' => '添加成功');
        }
        return array('status' => 1, 'inf' => '添加失败');
    }

    /**
     * 编辑400电话绑定
     * @param type $id
     * @param type $data
     * @return boolean
     */
    public function edit($id, $data)
    {
        $id = intval($id);
        $rs = self::findfirst($id);
        if(!($id && $data['mobile'] && $data['phoneHost'] && $data['phoneExt']))
        {
            return array('status' => 1, 'info' => '参数不完整');
        }
        if(!$rs)
        {
            return array('status' => 1, 'info' => '数据不存在');
        }
        $checkRes = $this->_isMobileOrPhoneExist($data['mobile'], $data['phoneHost'], $data['phoneExt'], $id);
        if(0 !== $checkRes['status'])
        {
            return $checkRes;
        }
        $rs->mobile = $data['mobile'];
        $rs->phoneHost = $data['phoneHost'];
        $rs->phoneExt = $data['phoneExt'];
        $rs->updateTime = date("Y-m-d H:i:s");
        
        if(!$rs->update())
        {
            return array('status' => 1, 'inf' => '修改失败');
        }
        $memKey = $this->_getMobileMemkey($data['mobile']);
        Mem::Instance()->Del($memKey);
        
        return array('status' => 0, 'inf' => '修改成功');
    }

    private function _isMobileOrPhoneExist($mobile, $phoneHost, $phoneExt, $id = 0)
    {
        $where = "(mobile='{$mobile}' or (phoneHost='{$phoneHost}' and phoneExt='{$phoneExt}'))";
        $id > 0 && $where .= " and id<>{$id}";
        
        $res = self::findFirst($where, 0)->toArray();
        if(empty($res))
        {
            return array('status' => 0);
        }
        
        if($res['mobile'] == $mobile)
        {
            return array('status' => 1, 'info' => '手机号已存在');
        } else {
            return array('status' => 1, 'info' => '400电话已存在');
        }
    }
    /**
     * 删除单条记录
     *
     * @param unknown $where
     */
    public function del($where)
    {
        $rs = self::findFirst($where);
        $mobile = $rs->mobile;
        if($rs->delete())
        {
            $memKey = $this->_getMobileMemkey($mobile);
            Mem::Instance()->Del($memKey);
            return true;
        }
        return false;
    }
    
    /**
     * 根据手机号获取400电话
     * @param type $mobile
     * @return type
     */
    public function getPhoneByMobile($mobile)
    {
        $memKey = $this->_getMobileMemkey($mobile);
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            return $memValue;
        }
        
        $res = self::findFirst("mobile='{$mobile}'", 0)->toArray();
        if(empty($res))
        {
            return array();
        }       
        Mem::Instance()->Set($memKey, $res, 3600);
        
        return $res;
    }
    
    private function _getMobileMemkey($mobile)
    {
        return 'fym_esf_phone400_mobile_' . $mobile;
    }
}
