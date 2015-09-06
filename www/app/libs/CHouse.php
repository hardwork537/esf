<?php

class CHouse
{

    //相邻价格，上下浮动 10W
    const PRICE_RANGE = 100000;

    /**
     * 首页推荐最新房源
     * @param type $cityId
     * @param string $columns
     * @param type $limit
     * @return type
     */
    public static function getHomeNewHouse($cityId, $columns = '', $limit = 4)
    {
        $memKey = "fym_home_new_house_{$cityId}_{$limit}";
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            return $memValue;
        }
        
        //推荐最新房源
        $where = "cityId={$cityId} and status=".House::STATUS_ONLINE;
        $condition = array(
            'conditions' => $where,
            'columns' => $columns,
            'offset' => 0,
            'limit' => $limit,
            'order' => 'updateTime desc'
        );
        $res = House::find($condition, 0)->toArray();
        
        if(empty($res))
        {
            return array();
        } else {
            Mem::Instance()->Set($memKey, $res, 300);
            
            return $res;
        }      
    }

    /**
     * 获取你可能感兴趣的房源 (房源单页右侧栏)
     * @param type $num
     * @param type $houseId
     * @param type $cityId
     * @param type $regId
     * @param type $parkId
     * @param type $bedRoom
     * @param type $livingRoom
     * @param type $bathRoom
     * @param type $price
     * @return type
     */
    public static function getFavHouse($num = 7, $houseId = 0, $cityId = 0, $regId = 0, $parkId = 0, $bedRoom = 0, $livingRoom = 0, $bathRoom = 0, $price = 0)
    {
        //var_dump($num, $houseId, $cityId, $regId, $parkId, $bedRoom, $livingRoom, $bathRoom, $price);
        $memKey = "fym_houseView_right_houseId_" . $houseId;
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            //return $memValue;
        }
        $houseId = $houseId + 0;
        $numLess = $num;
        $house = $houseIds = $parkIds = array();
        $existHouseIds = array($houseId);
        //同小区内同户型房源
        $where = "cityId={$cityId} and id<>{$houseId} and bedRoom={$bedRoom} and livingRoom={$livingRoom} and bathRoom={$bathRoom} and parkId={$parkId} and status=" . House::STATUS_ONLINE;
        $condition = array(
            'conditions' => $where,
            'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
            'offset' => 0,
            'limit' => $num,
            'order' => 'updateTime desc'
        );
        $res = House::find($condition, 0)->toArray();
        $tmpNum = count($res);
        if($tmpNum > 0)
        {
            foreach($res as $v)
            {
                $house[$v['id']] = $v;
                $house[$v['id']]['imgUrl'] = '';
                $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                $houseIds[] = $existHouseIds[] = $v['id'] + 0;
                $parkIds[] = $v['parkId'];
            }
        }
        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            //同板块内同户型房源
            $where = "cityId={$cityId} and bedRoom={$bedRoom} and livingRoom={$livingRoom} and bathRoom={$bathRoom} and regId={$regId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'updateTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $existHouseIds[] = $v['id'] + 0;
                    $parkIds[] = $v['parkId'];
                }
            }
        }
        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            //同板块内相邻价格房源
            $minPrice = $price - self::PRICE_RANGE;
            $maxPrice = $price + self::PRICE_RANGE;
            $where = "cityId={$cityId} and regId={$regId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ") and handPrice>={$minPrice} and handPrice<={$maxPrice}";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'updateTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $existHouseIds[] = $v['id'] + 0;
                    $parkIds[] = $v['parkId'];
                }
            }
        }
        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            //最新发布房源
            $where = "cityId={$cityId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'updateTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $existHouseIds[] = $v['id'] + 0;
                    $parkIds[] = $v['parkId'];
                }
            }
        }

        if(!empty($houseIds))
        {                      
            //获取房源图片
            $condition = array(
                'conditions' => "houseId in(" . implode(',', $houseIds) . ") and status=" . HousePicture::STATUS_OK,
                'columns' => 'houseId,imgId,imgExt',
                'group' => 'houseId',
                'order' => 'imgId asc'
            );
            $imgRes = HousePicture::find($condition, 0)->toArray();
            foreach($imgRes as $v)
            {
                $house[$v['houseId']]['imgUrl'] = ImageUtility::getImgUrl('esf', $v['imgId'], $v['imgExt']);
            }
        }

        shuffle($house);
        if(!empty($house))
        {
            //获取小区信息
            $parkIds = array_flip(array_flip($parkIds));
            $parkInfo = Park::instance()->getParkByIds($parkIds, 'id,name');
            
            foreach($house as $key => $row) 
            {
                $house[$key]['parkName'] = $parkInfo[$row['parkId']]['name'];
                $volume[$key]  = $row['updateTime'];
            }
            array_multisort($volume, SORT_DESC, $house);
            Mem::Instance()->Set($memKey, $house, 300);
        }

        return $house;
    }

    /**
     * 获取板块内同价位房源 (房源单页下方推荐)
     * @param type $num
     * @param type $houseId
     * @param type $cityId
     * @param type $distId
     * @param type $regId
     * @param type $price
     */
    public static function getRegHouse($num = 4, $houseId = 0, $cityId = 0, $distId = 0, $regId = 0, $price = 0)
    {        
        $memKey = "fym_houseView_bottom_houseId_" . $houseId;
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            //return $memValue;
        }
        $house = $houseIds = $parkIds = array();
        $houseId = $houseId + 0;

        //同板块内相邻价格房源
        $minPrice = $price - self::PRICE_RANGE;
        $maxPrice = $price + self::PRICE_RANGE;

        $where = "cityId={$cityId} and handPrice>={$minPrice} and handPrice<={$maxPrice} and id<>{$houseId} and regId={$regId} and status=" . House::STATUS_ONLINE;
        $condition = array(
            'conditions' => $where,
            'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
            'offset' => 0,
            'limit' => $num,
            'order' => 'updateTime desc'
        );
        $existHouseIds = array($houseId);
        $res = House::find($condition, 0)->toArray();
        if(!empty($res))
        {
            foreach($res as $v)
            {
                $house[$v['id']] = $v;
                $house[$v['id']]['imgUrl'] = '';
                $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                $houseIds[] = $existHouseIds[] = $v['id'] + 0;
                $parkIds[] = $v['parkId'];
            }
        }

        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            //同区域内相邻价格房源
            $where = "cityId={$cityId} and handPrice>={$minPrice} and handPrice<={$maxPrice} and distId={$distId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'updateTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(!empty($res))
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $existHouseIds[] = $v['id'] + 0;
                    $parkIds[] = $v['parkId'];
                }
            }
        }
        
        $numLess = $num - count($houseIds);
        //Log::ErrorWrite('www', '', json_encode($houseIds).'-'.$numLess, 'debug.txt');
        if($numLess > 0)
        {
            //最新发布房源
            $where = "cityId={$cityId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice,updateTime,parkId',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'updateTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $existHouseIds[] = $v['id'];
                    $parkIds[] = $v['parkId'];
                }
            }
        }

        if(!empty($houseIds))
        {
            //获取房源图片
            $condition = array(
                'conditions' => "houseId in(" . implode(',', $houseIds) . ") and status=" . HousePicture::STATUS_OK,
                'columns' => 'houseId,imgId,imgExt',
                'group' => 'houseId',
                'order' => 'imgId asc'
            );
            $imgRes = HousePicture::find($condition, 0)->toArray();
            foreach($imgRes as $v)
            {
                $house[$v['houseId']]['imgUrl'] = ImageUtility::getImgUrl('esf', $v['imgId'], $v['imgExt']);
            }
        }
        shuffle($house);
        
        if(!empty($house))
        {
            //获取小区信息
            $parkIds = array_flip(array_flip($parkIds));
            $parkInfo = Park::instance()->getParkByIds($parkIds, 'id,name');
            
            foreach($house as $key => $row) 
            {
                $house[$key]['parkName'] = $parkInfo[$row['parkId']]['name'];
                $volume[$key]  = $row['updateTime'];
            }
            array_multisort($volume, SORT_DESC, $house);
            Mem::Instance()->Set($memKey, $house, 300);
        }
        
        return $house;
    }
    
    /**
     * 获取区域信息
     * @param type $distId
     * @return type
     */
    public static function getDistById($distId, $columns = '')
    {
        if(!$distId)
        {
            return array();
        }
        $memKey = "fym_web_getDist_byId_" . $distId;
        $memValue = Mem::Instance()->Get($memKey);
        
        if(!empty($memValue))
        {
            $res = $memValue;
        } else {
            $res = CityDistrict::instance()->getDistByIds($distId);
            if(empty($res))
            {
                return array();
            }

            $res = $res[$distId];
            Mem::Instance()->Set($memKey, $res, 3600);
        }
        
        if($columns)
        {
            $columnArr = explode(',', $columns);
            $result = array();
            foreach($columnArr as $column)
            {
                $result[$column] = $res[$column];
            }
            return $result;
        } else {
            return $res;
        }    
    }
    
    /**
     * 获取板块信息
     * @param type $regId
     * @return type
     */
    public static function getRegById($regId, $columns = '')
    {
        if(!$regId)
        {
            return array();
        }
        $memKey = "fym_web_getRegion_byId_" . $regId;
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            $res = $memValue;
        } else {
            $res = CityRegion::instance()->getRegionByIds($regId);
            if(empty($res))
            {
                return array();
            }

            $res = $res[$regId];
            Mem::Instance()->Set($memKey, $res, 3600);
        }
        
        if($columns)
        {
            $columnArr = explode(',', $columns);
            $result = array();
            foreach($columnArr as $column)
            {
                $result[$column] = $res[$column];
            }
            return $result;
        } else {
            return $res;
        }    
    }
    
    /**
     * 获取小区信息
     * @param type $parkId
     * @return type
     */
    public static function getParkById($parkId, $columns = '')
    {
        if(!$parkId)
        {
            return array();
        }
        $memKey = "fym_web_getpark_byId_" . $parkId;
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            $res = $memValue;
        } else {
            $res = Park::instance()->getParkByIds($parkId);
            if(empty($res))
            {
                return array();
            }

            $res = $res[$parkId];
            Mem::Instance()->Set($memKey, $res, 3600);
        }
        
        if($columns)
        {
            $columnArr = explode(',', $columns);
            $result = array();
            foreach($columnArr as $column)
            {
                $result[$column] = $res[$column];
            }
            return $result;
        } else {
            return $res;
        }    
    }
    
    /**
     * 获取房源发布人信息
     * @param type $parkId
     * @return type
     */
    public static function getUserById($userId, $columns = '')
    {
        if(!$userId)
        {
            return array();
        }
        $memKey = "fym_web_getuser_byId_" . $userId;
        $memValue = Mem::Instance()->Get($memKey);
        if(!empty($memValue))
        {
            $res = $memValue;
        } else {
            $res = AdminUser::instance()->getUserByIds($userId);
            if(empty($res))
            {
                return array();
            }

            $res = $res[$userId];
            Mem::Instance()->Set($memKey, $res, 3600);
        }
        
        if($columns)
        {
            $columnArr = explode(',', $columns);
            $result = array();
            foreach($columnArr as $column)
            {
                $result[$column] = $res[$column];
            }
            return $result;
        } else {
            return $res;
        }    
    }
    
    /**
     * 获取400电话
     * @param type $mobile
     * @return type
     */
    public static function getPhoneByMobile($mobile, $columns = '')
    {
        if(!$mobile)
        {
            return array();
        }
        $res = Phone400::instance()->getPhoneByMobile($mobile);
        if(empty($res))
        {
            return array();
        }
        
        if($columns)
        {
            $columnArr = explode(',', $columns);
            $result = array();
            foreach($columnArr as $column)
            {
                $result[$column] = $res[$column];
            }
            return $result;
        } else {
            return $res;
        }    
    }
}
