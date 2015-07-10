<?php

/**
 * @abstract 小区基础model
 *
 */
class Park extends BaseModel
{

    public $id;
    public $regId;
    public $distId;
    public $cityId;
    public $name;
    public $alias;
    public $pinyin;
    public $pinyinAbbr;
    public $address;
    public $postcode = '';
    public $X;
    public $Y;
    public $BdX = 0;
    public $BdY = 0;
    public $type;
    public $buildType = 0;
    public $buildYear;
    public $landArea;
    public $GFA;
    public $FAR;
    public $GR;
    public $RR = '';
    public $buildings = 0;
    public $houses;
    public $pCount;
    public $pFix = 0;
    public $fee;
    public $tags = '';
    public $picId = 0;
    public $status = self::STATUS_VALID;
    public $saleCount = 0;
    public $saleValid = 0;
    public $update;
    public $picExt = '';
    public $rentCount = 0;
    public $rentValid = 0;
    public $salePrice = 0;
    public $salePriceIncrease = 0;
    public $source = self::SOURCE_SECONDARY_PARK;
    public $allowWgPhoto = self::ALLOW_WAIGUAN_YES;
    public $allowHxPhoto = self::ALLOW_HUXING_YES;

    //小区来源
    const SOURCE_NEW_PARK = 1;       //新房小区
    const SOURCE_SECONDARY_PARK = 2; //二手房小区
    //数据状态  status
    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;
    const STATUS_DELETE = -1;
    //是否允许经纪人上传外观图
    const ALLOW_WAIGUAN_YES = 1;   //允许
    const ALLOW_WAIGUAN_NO = 2;   //不允许
    //是否允许经纪人上传户型图
    const ALLOW_HUXING_YES = 1; //允许
    const ALLOW_HUXING_NO = 2; //不允许

    public function columnMap()
    {
        return array(
            'parkId' => 'id',
            'regId' => 'regId',
            'distId' => 'distId',
            'cityId' => 'cityId',
            'parkName' => 'name',
            'parkAlias' => 'alias',
            'parkPinyin' => 'pinyin',
            'parkPinyinAbbr' => 'pinyinAbbr',
            'parkAddress' => 'address',
            'parkPostcode' => 'postcode',
            'parkX' => 'X',
            'parkY' => 'Y',
            'parkBX' => 'BdX', //请不要随便改动
            'parkBY' => 'BdY', //请不要随便改动
            'parkLonLat' => 'lonLat',
            'parkType' => 'type',
            'parkBuildType' => 'buildType',
            'parkBuildYear' => 'buildYear',
            'parkLandArea' => 'landArea',
            'parkGFA' => 'GFA',
            'parkFAR' => 'FAR',
            'parkGR' => 'GR',
            'parkRR' => 'RR',
            'parkBuildings' => 'buildings',
            'parkHouses' => 'houses',
            'parkPCount' => 'pCount',
            'parkPFix' => 'pFix',
            'parkFee' => 'fee',
            'parkTags' => 'tags',
            'parkPicId' => 'picId',
            'parkStatus' => 'status',
            'parkUpdate' => 'update',
            'parkSaleValid' => 'saleValid',
            'parkSaleCount' => 'saleCount',
            'parkPicExt' => 'picExt',
            'parkRentCount' => 'rentCount',
            'parkRentValid' => 'rentValid',
            'parkSalePrice' => 'salePrice',
            'parkSalePriceIncrease' => 'salePriceIncrease',
            'parkSource' => 'source',
            'parkAllowWgPhoto' => 'allowWgPhoto',
            'parkAllowHxPhoto' => 'allowHxPhoto'
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
        return new self;
    }

    /**
     * 获取所有小区来源
     * @return array
     */
    public static function getSourceTypes()
    {
        return array(
            self::SOURCE_NEW_PARK => '新房',
            self::SOURCE_SECONDARY_PARK => '二手房'
        );
    }

    /**
     * 新增小区
     * @param array $data
     * @return array
     */
    public function add($data)
    {
        if(empty($data))
        {
            return array('status' => 1, 'info' => '参数为空！');
        }
        if($this->isExistParkName($data["name"], $data["cityId"]))
        {
            return array('status' => 1, 'info' => '小区名称已经存在！');
        }

        $clsPinYin = new HanZiToPinYin();
        $pinyinShort = $clsPinYin->getPinYin(trim($data["name"]));
        $bxy = BaiduMap::instance()->getLonLat($data['X'], $data['Y']);

        $this->cityId = $data["cityId"];
        $this->distId = $data["distId"];
        $this->regId = $data["regId"];
        $this->name = $data["name"];
        $this->pinyin = $pinyinShort['full'];
        $this->pinyinAbbr = $pinyinShort['short'];
        $this->alias = $data['alias'];
        $this->fee = $data['fee'];
        $this->address = $data['address'];
        $this->type = $data['type'];
        $this->salePrice = $data['avgPrice'];
        $this->buildYear = $data['buildYear'];
        $this->GR = $data['greenRate'];
        $this->X = $data['X'];
        $this->Y = $data['Y'];
        $this->BdX = $bxy['x'];
        $this->BdY = $bxy['y'];
        $this->landArea = $data['landArea'];
        $this->GFA = $data['grossFloorArea'];
        $this->FAR = $data['floorAreaRate'];
        $this->houses = $data['houses'];
        $this->pCount = $data['pCount'];
        $this->update = date("Y-m-d H:i:s");
        $this->source = $data['source'];

        $this->begin();
        if($this->create())
        {
            $extData = $moreData = array();
            $data['周边公交'] && $moreData['周边公交'] = $data['周边公交'];
            $data['周边设施'] && $moreData['周边设施'] = $data['周边设施'];
            $data['内部设施'] && $moreData['内部设施'] = $data['内部设施'];
            $data['小区设施'] && $moreData['小区设施'] = $data['小区设施'];
            if(!empty($moreData))
            {
                //添加扩展信息
                $moreInsertRet = $this->saveParkMore($this->id, $moreData);
                if(!$moreInsertRet)
                {
                    $this->rollback();
                    return array('status' => 1, 'info' => '添加小区失败！');
                }
            }
            /*
              if($data['projId'] > 0 || $data['groupId'] > 0)
              {
              $bbsRes = $this->saveParkBbs($this->id, $data['projId'], $data['groupId']);
              if(!$bbsRes)
              {
              $this->rollback();
              return array('status' => 1, 'info' => '添加小区失败！');
              }
              } */
            $arr = array(
                "cityId" => $this->cityId,
                "distId" => $this->distId,
                "regId" => $this->regId,
                "parkBuildType" => 0,
                "parkBuildYear" => $this->buildYear,
                "parkSalePrice" => $this->salePrice,
                "parkX" => $this->X,
                "parkY" => $this->Y,
                "parkHouseValidSum" => 0,
                "parkRentHouseValidSum" => 0,
                "parkName" => $this->name,
                "parkAlias" => $this->alias,
                "parkPinyin" => $this->pinyin,
                "parkPinyinAbbr" => $this->pinyinAbbr,
                "parkAddress" => $this->address,
                "parkSubwaySite" => '',
                "parkSubwayLine" => '',
                "parkSubwaySiteLine" => '',
                "parkAroundSchool" => '',
                "parkStatus" => $this->status,
                'parkId' => $this->id,
                'id' => $this->id,
            );
            $metroInfo = $this->getParkMetrInfo($this->cityId, $this->id, $this->X, $this->Y);
            if(!empty($metroInfo))
            {
                $arr['parkSubywaySite'] = $metroInfo['linestation'];
                $arr['parkSubwayLine'] = $metroInfo['line'];
                $arr['parkSubwaySiteLine'] = $metroInfo['station'];
            }
            
            //$esRes = $this->addEs($arr);

            $data['物业公司'] && $extData['物业公司'] = $data['物业公司'];
            $data['物业电话'] && $extData['物业电话'] = $data['物业电话'];
            $data['开发商'] && $extData['开发商'] = $data['开发商'];
            $data['开盘时间'] && $extData['开盘时间'] = $data['开盘时间'];
            $data['入住时间'] && $extData['入住时间'] = $data['入住时间'];
            $data['预售许可证'] && $extData['预售许可证'] = $data['预售许可证'];
            $data['产权年限'] && $extData['产权年限'] = $data['产权年限'];
            $data['400电话'] && $extData['400电话'] = $data['400电话'];

            if(!empty($extData))
            {
                //添加扩展信息
                $extInsertRet = $this->saveParkExt($this->id, $extData);
                if(!$extInsertRet)
                {
                    $this->rollback();
                    return array('status' => 1, 'info' => '添加小区失败！');
                }
            }
            $this->commit();
            //$zebRes = $this->_addZebParkNum($this->id);
            return array('status' => 0, 'info' => '添加小区成功！', 'id' => $this->id);
        }
        $this->rollback();
        return array('status' => 1, 'info' => '添加小区失败！');
    }
   
    /**
     * 保存小区描述信息
     * @param type $parkId
     * @param type $data
     */
    private function saveParkMore($parkId, $data)
    {
        $parkId = intval($parkId);
        if(!$parkId || empty($data))
        {
            return false;
        }

        foreach($data as $k => $v)
        {
            $isExist = ParkMore::instance()->isExistMore($parkId, $k);

            if(false === $isExist)
            {
                //如果不存在，则新增
                $insertRet = ParkMore::instance()->add($parkId, array("name" => $k, "value" => $v));
                if(!$insertRet)
                    return false;
            }
            else
            {
                //如果存在，则修改
                if(true === $isExist)
                    return false;
                $updateRet = ParkMore::instance()->edit($isExist, array("name" => $k, "value" => $v));
                if(!$updateRet)
                    return false;
            }
        }

        return true;
    }

    /**
     * 保存小区扩展信息
     * @param type $parkId
     * @param type $data
     */
    private function saveParkExt($parkId, $data)
    {
        $parkId = intval($parkId);
        if(!$parkId || empty($data))
        {
            return false;
        }

        foreach($data as $k => $v)
        {
            $isExist = ParkExt::instance()->isExistExt($parkId, $k);

            if(false === $isExist)
            {
                //如果不存在，则新增
                $insertRet = ParkExt::instance()->add($parkId, array("name" => $k, "value" => $v));
                if(!$insertRet)
                    return false;
            }
            else
            {
                //如果存在，则修改
                if(true === $isExist)
                    return false;
                $updateRet = ParkExt::instance()->edit($isExist, array("name" => $k, "value" => $v));
                if(!$updateRet)
                    return false;
            }
        }

        return true;
    }

    /**
     * 获取小区的地铁信息
     * @param int   $cityId
     * @param float $parkX
     * @param float $parkY
     * @return array
     */
    public function getParkMetrInfo($cityId, $parkId, $parkX, $parkY)
    {
        if(!$cityId || !$parkX || !$parkY)
        {
            return array();
        }
        $arrSubwayLine = array();

        //获取城市的地铁线路
        $metro = Metro::find("cityId={$cityId} and status=" . Metro::STATUS_ENABLED, 0)->toArray();
        foreach($metro as $v)
        {
            $arrSubwayLine[$v['id']] = $v['name'];
        }

        //获取城市的地铁站点
        $msCondition = array(
            'conditions' => "cityId={$cityId} and x<>'' and y<>'' and status=" . MetroStation::STATUS_ENABLED,
            'columns' => 'id,metroId,name,x,y'
        );
        $metroStation = MetroStation::find($msCondition, 0)->toArray();

        //获取城市的有效小区
        $parkCondition = array(
            'conditions' => "cityId={$cityId} and X<>'' and Y<>'' and status=" . Park::STATUS_VALID,
            'columns' => 'id,name,X,Y'
        );

        $str = '';
        $strLine = '';
        $arrNearestLine = array();
        $strNearestLine = "";

        foreach($metroStation as $eRow)
        {
            $distance = Utility::GetDistance($parkY, $parkX, $eRow['y'], $eRow['x']); //算法查出经纬度距离(公里)
            if($distance <= 1.5)
            {
                if(!strstr($str, $eRow['name']))
                {
                    $str .= "{$eRow['name']},";
                }

                if(!strstr($strLine, $arrSubwayLine[$eRow['metroId']]))
                {
                    $strLine .= "{$arrSubwayLine[$eRow['metroId']]},";
                }

                if($distance < 0.5)
                {
                    $arrNearestLine[] = "{$arrSubwayLine[$eRow['metroId']]}兲{$eRow['name']}兲10分钟兲" . intval($distance * 1000);
                } elseif($distance >= 0.5 && $distance < 1)
                {
                    $arrNearestLine[] = "{$arrSubwayLine[$eRow['metroId']]}兲{$eRow['name']}兲20分钟兲" . intval($distance * 1000);
                } else
                {
                    $arrNearestLine[] = "{$arrSubwayLine[$eRow['metroId']]}兲{$eRow['name']}兲30分钟兲" . intval($distance * 1000);
                }
            }
        }

        $str = rtrim($str, ",");
        $strLine = rtrim($strLine, ",");
        $strNearestLine = implode(",", $arrNearestLine);

        if(!empty($str))
        {
            //更新 周边地铁线路
            $lineRes = $this->updateParkMetro($parkId, '周边地铁线路', $strLine);
            //更新 周边地铁站点串
            $stationRes = $this->updateParkMetro($parkId, '周边地铁站点串', $str);
            //更新 周边地铁站点
            $lsRes = $this->updateParkMetro($parkId, '周边地铁站点', $strNearestLine);

            if($lineRes && $stationRes && $lsRes)
            {
                return array('line' => $strLine, 'station' => $str, 'linestation' => $strNearestLine);
            } else
            {
                return array();
            }
        }

        return array();
    }

    /**
     * 更新小区的地铁信息
     * @param int    $parkId
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function updateParkMetro($parkId, $name, $value)
    {
        $rs = ParkExt::findFirst("parkId={$parkId} and name='{$name}'");

        if($rs)
        {
            $rs->value = $value;
            $rs->length = mb_strlen($value, 'utf-8');
            $rs->status = ParkExt::STATUS_VALID;
            $rs->update = date('Y-m-d H:i:s');

            return $rs->update();
        } else
        {
            $rs = ParkExt::instance(false);

            $rs->parkId = $parkId;
            $rs->name = $name;
            $rs->value = $value;
            $rs->length = mb_strlen($value, 'utf-8');
            $rs->status = ParkExt::STATUS_VALID;
            $rs->update = date('Y-m-d H:i:s');

            return $rs->create();
        }
    }

    /**
     * 修改小区
     * @param array $data
     * @return array
     */
    public function edit($parkId, $data)
    {
        $parkId = intval($parkId);

        if(empty($data) || !$parkId)
        {
            return array('status' => 1, 'info' => '参数为空！');
        }

        if($this->isExistParkName($data["name"], $data["cityId"], $parkId))
        {

            return array('status' => 1, 'info' => '小区名称已经存在！');
        }

        $clsPinYin = new HanZiToPinYin();
        $pinyinShort = $clsPinYin->getPinYin(trim($data["name"]));
        $bxy = BaiduMap::instance()->getLonLat($data['X'], $data['Y']);

        $rs = self::findFirst($parkId);
        $rs->cityId = $data["cityId"];
        $rs->distId = $data["distId"];
        $rs->regId = $data["regId"];
        $rs->name = $data["name"];
        $rs->pinyin = $pinyinShort['full'];
        $rs->pinyinAbbr = $pinyinShort['short'];
        $rs->alias = $data['alias'];
        $rs->fee = $data['fee'];
        $rs->address = $data['address'];
        $rs->type = $data['type'];
        $rs->salePrice = empty($data['avgPrice']) ? 0 : $data['avgPrice'];
        $rs->buildYear = $data['buildYear'];
        $rs->GR = $data['greenRate'];
        $rs->X = $data['X'];
        $rs->Y = $data['Y'];
        $rs->BdX = empty($bxy['x']) ? 0 : $bxy['x'];
        $rs->BdY = empty($bxy['y']) ? 0 : $bxy['y'];
        $rs->landArea = $data['landArea'];
        $rs->GFA = $data['grossFloorArea'];
        $rs->FAR = $data['floorAreaRate'];
        $rs->houses = $data['houses'];
        $rs->pCount = $data['pCount'];
        $rs->update = date("Y-m-d H:i:s");

        $this->begin();
        if($rs->update())
        {
            $arr = array(
                "id" => $rs->id,
                "data" => array(
                    "cityId" => $rs->cityId,
                    "distId" => $rs->distId,
                    "regId" => $rs->regId,
                    "parkX" => $rs->X,
                    "parkY" => $rs->Y,
                    "parkName" => $rs->name,
                    "parkAlias" => $rs->alias,
                    "parkPinyin" => $rs->pinyin,
                    "parkPinyinAbbr" => $rs->pinyinAbbr,
                    "parkAddress" => $rs->address,
                    "parkStatus" => $rs->status,
                    'parkId' => $rs->id,
                    'parkBuildType' => $rs->buildType,
                    'parkBuildYear' => $rs->buildYear,
                    'parkGR' => $rs->GR,
                    'parkFAR' => $rs->FAR,
                    'parkSalePrice' => $rs->salePrice,
                )
            );
            //小区地铁站点信息
            $metroInfo = $this->getParkMetrInfo($rs->cityId, $rs->id, $rs->X, $rs->Y);
            if(!empty($metroInfo))
            {
                $arr['data']['parkSubwaySite'] = $metroInfo['linestation'];
                $arr['data']['parkSubwayLine'] = $metroInfo['line'];
                $arr['data']['parkSubwaySiteLine'] = $metroInfo['station'];
            }
            //$res = $this->editEs($arr);

            $extData = $moreData = array();
            $data['周边公交'] && $moreData['周边公交'] = $data['周边公交'];
            $data['周边设施'] && $moreData['周边设施'] = $data['周边设施'];
            $data['内部设施'] && $moreData['内部设施'] = $data['内部设施'];
            $data['小区设施'] && $moreData['小区设施'] = $data['小区设施'];

            if(!empty($moreData))
            {
                //添加扩展信息
                $moreInsertRet = $this->saveParkMore($rs->id, $moreData);
                if(!$moreInsertRet)
                {
                    $this->rollback();
                    return array('status' => 1, 'info' => '修改小区失败！');
                }
            }

//            if($data['projId'] > 0 || $data['groupId'] > 0)
//            {
//                $bbsRes = $this->saveParkBbs($rs->id, $data['projId'], $data['groupId']);
//                if(!$bbsRes)
//                {
//                    $this->rollback();
//                    return array('status' => 1, 'info' => '修改小区失败！');
//                }
//            }

            $data['物业电话'] && $extData['物业电话'] = $data['物业电话'];
            $data['物业公司'] && $extData['物业公司'] = $data['物业公司'];
            $data['开发商'] && $extData['开发商'] = $data['开发商'];
            $data['物业费'] && $extData['物业费'] = $data['物业费'];
            $data['车位信息'] && $extData['车位信息'] = $data['车位信息'];
            $data['小区设施'] && $extData['小区设施'] = $data['小区设施'];
            $data['老人占比'] && $extData['老人占比'] = $data['老人占比'];
            $data['出租占比'] && $extData['出租占比'] = $data['出租占比'];
            $data['开盘时间'] && $extData['开盘时间'] = $data['开盘时间'];
            $data['入住时间'] && $extData['入住时间'] = $data['入住时间'];
            $data['预售许可证'] && $extData['预售许可证'] = $data['预售许可证'];
            $data['产权年限'] && $extData['产权年限'] = $data['产权年限'];
            $data['400电话'] && $extData['400电话'] = $data['400电话'];

            if(!empty($extData))
            {
                //添加扩展信息
                $extInsertRet = $this->saveParkExt($rs->id, $extData);

                if(!$extInsertRet)
                {
                    $this->rollback();
                    return array('status' => 1, 'info' => '修改小区失败！');
                }
            }

            $this->commit();

            return array('status' => 0, 'info' => '修改小区成功！');
        }
        $this->rollback();
        return array('status' => 1, 'info' => '修改小区失败！');
    }   
    
    private function isExistParkName($parkName, $cityId, $parkId = 0)
    {
        $parkName = trim($parkName);
        if(empty($parkName))
        {
            return true;
        }
        $con['conditions'] = "name='{$parkName}' and cityId={$cityId} and status=" . self::STATUS_VALID;
        $parkId > 0 && $con['conditions'] .= " and id<>{$parkId}";

        $intCount = self::count($con);
        if($intCount > 0)
        {
            return true;
        }
        return false;
    }
    
    
    /**
     * 删除单条记录
     *
     * @param int $parkId
     * @return boolean
     */
    public function del($parkId)
    {
        $rs = self::findFirst("id=" . $parkId);
        $rs->status = self::STATUS_DELETE;

        $houseObj = new House();
        $houseNum = $houseObj->getTotalByParkId($parkId);

        if(intval($houseNum) > 0)
        {
            return array("status" => 1, "info" => "该小区下有房源，不能删除");
        }

        $this->begin();
        if($rs->update())
        {
            $delMore = ParkMore::instance()->del($parkId);
            if(!$delMore)
            {
                $this->rollback();
                return array("status" => 1, "info" => "删除失败!");
            }
            $delExt = ParkExt::instance()->del($parkId);
            if(!$delExt)
            {
                $this->rollback();
                return array("status" => 1, "info" => "删除失败!");
            }
            $arr = array(
                "id" => $rs->id,
                "data" => array(
                    "parkStatus" => self::STATUS_DELETE,
                )
            );
            //$delEs = $this->editEs($arr);
//            if(!$delEs)
//            {
//                $this->rollback();
//                return array("status" => 1, "info" => "删除失败!");
//            }
//            $delBbs = ParkBbs::instance()->del($parkId);
//            if(!$delBbs)
//            {
//                $this->rollback();
//                return array("status" => 1, "info" => "删除失败!");
//            }
            $this->commit();
            return array("status" => 0, "info" => "删除成功!", 'name' => $rs->name);
        }
        $this->rollback();
        return array("status" => 1, "info" => "删除失败!");
    }

    /**
     * 根据小区id获取信息
     * @param int|array $parkIds
     * @param string    $columns
     * @param int       $status
     * @return array
     */
    public function getParkByIds($parkIds, $columns = '', $status = self::STATUS_VALID)
    {
        if(empty($parkIds))
        {
            return array();
        }
        if(is_array($parkIds))
        {
            $arrBind = $this->bindManyParams($parkIds);
            $arrCond = "id in({$arrBind['cond']}) and status={$status}";
            $arrParam = $arrBind['param'];
            $condition = array(
                $arrCond,
                "bind" => $arrParam,
            );
        }
        else
        {
            $condition = array('conditions'=>"id={$parkIds} and status={$status}");
        }
        $columns && $condition['columns'] = $columns;
        $arrPark  = self::find($condition,0)->toArray();
        $arrRPark = array();
        foreach($arrPark as $value)
        {
        	$arrRPark[$value['id']] = $value;
        }
        
        return $arrRPark;
    }
}
