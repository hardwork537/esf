<?php

class CHouse
{

    //相邻价格，上下浮动 10W
    const PRICE_RANGE = 100000;

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
            return $memValue;
        }

        $numLess = $num;
        $house = $houseIds = array();
        //同小区内同户型房源
        $where = "cityId={$cityId} and id<>{$houseId} and bedRoom={$bedRoom} and livingRoom={$livingRoom} and bathRoom={$bathRoom} and parkId={$parkId} and status=" . House::STATUS_ONLINE;
        $condition = array(
            'conditions' => $where,
            'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
            'offset' => 0,
            'limit' => $num,
            'order' => 'createTime desc'
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
                $houseIds[] = $v['id'];
            }
        }
        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            $existHouseIds = array($houseId) + $houseIds;
            //同板块内同户型房源
            $where = "cityId={$cityId} and bedRoom={$bedRoom} and livingRoom={$livingRoom} and bathRoom={$bathRoom} and regId={$regId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $v['id'];
                }
            }
        }
        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            $existHouseIds = array($houseId) + $houseIds;
            //同板块内相邻价格房源
            $minPrice = $price - self::PRICE_RANGE;
            $maxPrice = $price + self::PRICE_RANGE;
            $where = "cityId={$cityId} and regId={$regId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ") and handPrice>={$minPrice} and handPrice<={$maxPrice}";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $v['id'];
                }
            }
        }
        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            $existHouseIds = array($houseId) + $houseIds;
            //最新发布房源
            $where = "cityId={$cityId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $v['id'];
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
            Mem::Instance()->Set($memKey, $house, 3600);
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
            return $memValue;
        }
        $house = $houseIds = array();

        //同板块内相邻价格房源
        $minPrice = $price - self::PRICE_RANGE;
        $maxPrice = $price + self::PRICE_RANGE;

        $where = "cityId={$cityId} and handPrice>={$minPrice} and handPrice<={$maxPrice} and id<>{$houseId} and regId={$regId} and status=" . House::STATUS_ONLINE;
        $condition = array(
            'conditions' => $where,
            'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
            'offset' => 0,
            'limit' => $num,
            'order' => 'createTime desc'
        );
        $res = House::find($condition, 0)->toArray();
        if(!empty($res))
        {
            foreach($res as $v)
            {
                $house[$v['id']] = $v;
                $house[$v['id']]['imgUrl'] = '';
                $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                $houseIds[] = $v['id'];
            }
        }

        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            $existHouseIds = array($houseId) + $houseIds;
            //同区域内相邻价格房源
            $where = "cityId={$cityId} and handPrice>={$minPrice} and handPrice<={$maxPrice} and distId={$distId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(!empty($res))
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $v['id'];
                }
            }
        }

        $numLess = $num - count($houseIds);
        if($numLess > 0)
        {
            $existHouseIds = array($houseId) + $houseIds;
            //最新发布房源
            $where = "cityId={$cityId} and status=" . House::STATUS_ONLINE . " and id not in(" . implode(',', $existHouseIds) . ")";
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,handPrice',
                'offset' => 0,
                'limit' => $numLess,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(count($res) > 0)
            {
                foreach($res as $v)
                {
                    $house[$v['id']] = $v;
                    $house[$v['id']]['imgUrl'] = '';
                    $house[$v['id']]['price'] = number_format($v['handPrice'] / 10000, 2);
                    $houseIds[] = $v['id'];
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

        return $house;
    }

}
