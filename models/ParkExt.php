<?php

class ParkExt extends BaseModel
{

    protected $id;
    protected $parkId;
    protected $name;
    protected $value;
    protected $length;
    protected $status;
    protected $update;

    //数据状态  status
    const STATUS_VALID = 1;  //有效
    const STATUS_INVALID = 0;  //失效
    const STATUS_DELETE = -1; //删除
    const KEY_OF_PERMIT_FOR_PRESALE = '预售许可证';
    const KEY_OF_YEAR_LIMIT = "产权年限";
    const KEY_OF_ESTATE_DEVELOPER = '开发商';
    const KEY_OF_PROPERTY_COMPANY = '物业公司';

    public function columnMap()
    {
        return array(
            'peId' => 'id',
            'parkId' => 'parkId',
            'peName' => 'name',
            'peValue' => 'value',
            'peLength' => 'length',
            'peStatus' => 'status',
            'peUpdate' => 'update'
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
     * @return ParkExt_Model
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self;
    }

    /**
     * 判断某个小区的某个扩展字段是否存在
     * @param int    $parkId
     * @param string $peName
     * return int | boolean
     */
    public function isExistExt($parkId, $peName)
    {
        $parkId = intval($parkId);
        $peName = trim($peName);
        if(!$parkId || !$peName)
        {
            return true;
        }
        $con['conditions'] = "parkId={$parkId} and name='{$peName}' and status=" . self::STATUS_VALID;

        $extInfo = self::findFirst($con);
        if($extInfo)
        {
            $extInfo = $extInfo->toArray();

            return intval($extInfo['id']);
        } else
        {
            return false;
        }
    }

    /**
     * 添加扩展字段
     * @param int   $parkId
     * @param array $data
     * @return boolean
     */
    public function add($parkId, $data)
    {
        $parkId = intval($parkId);
        if(empty($data) || !$parkId)
        {
            return false;
        }
        $rs = self::instance();
        $rs->id = null;
        $rs->parkId = intval($parkId);
        $rs->name = $data["name"];
        $rs->value = $data["value"];
        $rs->length = mb_strlen($data["value"], 'utf-8');
        $rs->status = self::STATUS_VALID;
        $rs->update = date("Y-m-d H:i:s");

        if($rs->create())
        {
            return true;
        }

        return false;
    }

    /**
     * 更新某个扩展字段
     * @param int   $peId
     * @param array $data
     * @return boolean
     */
    public function edit($peId, $data)
    {
        $peId = intval($peId);
        if(empty($data) || !$peId)
        {
            return false;
        }
        $rs = self::findFirst($peId);
        $rs->name = $data["name"];
        $rs->value = $data["value"];
        $rs->length = mb_strlen($data["value"], 'utf-8');
        $rs->update = date("Y-m-d H:i:s");

        if($rs->update())
        {
            return true;
        }

        return false;
    }

    /**
     * 删除小区扩展信息
     * @param int $parkId
     * @return boolean
     */
    public function del($parkId)
    {
        $parkId = intval($parkId);
        if(!$parkId)
        {
            return false;
        }
        $exts = $this->find(array("conditions" => "parkId={$parkId}"));
        if($exts)
        {
            foreach($exts as $ext)
            {
                $ext->status = self::STATUS_DELETE;
                $delRet = $ext->update();
                if(!$delRet)
                    return false;
            }
        }
        else
        {
            return true;
        }

        return true;
    }

    /**
     * @abstract 获取小区扩展信息根据扩展字段值
     * @author Eric xuminwan@sohu-inc.com
     * @param int $intParkId
     * @param string $strName
     * @return array
     * 
     */
    public function getParkExtByParkId($intParkId, $strName = '')
    {
        if(!$intParkId)
            return array();
        if($strName != '')
        {
            $strCond = "status = 1 and parkId = ?1 and name =?2";
            $arrParam = array(1 => $intParkId, 2 => $strName);
        } else
        {
            $strCond = "status = 1 and parkId = ?1";
            $arrParam = array(1 => $intParkId);
        }
        $arrAssort = self::find(array(
                $strCond,
                'bind' => $arrParam,
                ), 0)->toArray();
        return $arrAssort;
    }

}
