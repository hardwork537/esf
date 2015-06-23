<?php

class HouseMore extends BaseModel
{

    public $id;
    public $houseId;
    public $name = '';
    public $text = '';
    public $length = 0;
    public $status;
    public $updateTime;

    const HOUSE_MORE_STAUTS_FAILURE = 0;  //失效
    const HOUSE_MORE_STAUTS_EFFECTIVITY = 1; //有效
    const HOUSE_MORE_STAUTS_DELETE = -1;  //删除

    //描述字段名字

    static public $descColumnName = 'description';

    public function getSource()
    {
        return 'house_more';
    }

    public function columnMap()
    {
        return array(
            'hmId' => 'id',
            'houseId' => 'houseId',
            'hmName' => 'name',
            'hmText' => 'text',
            'hmLength' => 'length',
            'hmStatus' => 'status',
            'hmUpdate' => 'updateTime'
        );
    }

    public function initialize()
    {
        $this->setReadConnectionService('esfSlave');
        $this->setWriteConnectionService('esfMaster');
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
     * 添加房源描述
     * @param unknown $arrDate
     */
    public function addHouseDesc($arrData)
    {
        if(empty($arrData))
            return false;

        $arrInsert = array();
        $arrInsert['houseId'] = $arrData['houseId'];
        $arrInsert['name'] = isset($arrData['hmName']) ? $arrData['hmName'] : self::$descColumnName;
        $arrInsert['text'] = isset($arrData[self::$descColumnName]) ? $arrData[self::$descColumnName] : new Phalcon\Db\RawValue("''");
        $arrInsert['length'] = isset($arrData[self::$descColumnName]) ? mb_strlen(Utility::filterSubject($arrData[self::$descColumnName], true), 'utf-8') : 0;
        $arrInsert['status'] = self::HOUSE_MORE_STAUTS_EFFECTIVITY;
        $arrInsert['updateTime'] = date('Y-m-d H:i:s');

        try
        {
            return self::create($arrInsert);
        } catch(Exception $ex)
        {
            return false;
        }
    }

    /**
     * 修改房源描述
     * @param unknown $arrDate
     */
    public function modifyHouseDesc($intHouseId, $arrData)
    {

        if(empty($arrData))
            return false;

        $arrUpdate = array();
        $arrUpdate['name'] = empty($arrData['hmName']) ? self::$descColumnName : $arrData['hmName'];
        $arrUpdate['text'] = empty($arrData['description']) ? new Phalcon\Db\RawValue("''") : $arrData['description'];
        $arrUpdate['length'] = empty($arrData['description']) ? 0 : mb_strlen(Utility::filterSubject($arrData['description'], true), 'utf-8');
        $arrUpdate['status'] = self::HOUSE_MORE_STAUTS_EFFECTIVITY;
        $arrUpdate['updateTime'] = date('Y-m-d H:i:s');

        $objHouseMore = self::findFirst("houseId = " . $intHouseId . " AND name = '". $arrUpdate['name'] ."'");
        if($objHouseMore)
        {
            $intFlag = $objHouseMore->update($arrUpdate);
        } else
        {
            $arrData['houseId'] = $intHouseId;
            $intFlag = $this->addHouseDesc($arrData);
        }
        return $intFlag;
    }

    /**
     * 获取指定房源的扩展信息
     *
     * @param int|array $ids 数组为获取多条信息
     * @return array
     * by Moon
     */
    public function getUnitExtById($ids)
    {
        if(!$ids)
            return array();
        $arrCondition['conditions'] = "name='". self::$descColumnName ."'";
        if(is_numeric($ids))
        {
            $arrCondition['conditions'] .= " and houseId=" . $ids;
        } else if(!is_array($ids))
        {
            return array();
        } else
        {
            $arrCondition['conditions'] .= ' and houseId in (' . join(',', $ids) . ")";
        }
        $return = self::find($arrCondition, 0)->toArray();
        if(empty($return))
            return array();
        $arrBackData = array();
        foreach($return as $value)
        {
            $value['houseDescription'] = stripcslashes($value['text']);
            $arrBackData[$value['houseId']] = $value;
        }

        if(is_numeric($ids))
            return reset($arrBackData);
        
        return $arrBackData;
    }

}
