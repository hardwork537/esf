<?php

class ViewController extends ControllerBase
{   
    
    private $_typeMapping = array(
        2 => 3, //银行
        6 => 2, //餐饮
        4 => 4, //医院
        8 => 1, //超市
        7 => 5, //学校
        9 => 6 //公园
    );
    
    public function indexAction()
    {
        $houseId = intval($this->dispatcher->getParam('houseid'));
        $data = array();
        $data['cssList'] = array('css/property_view.css');
        //房源基础信息
        $house = House::findFirst($houseId, 0)->toArray();
        if(empty($house))
        {
            $this->show('nofound', null, 'layoutsingle', 'error');
            return;
        }
        $houseInfo = array();
        $houseInfo['id'] = $houseId;
        $houseInfo['bA'] = $house['bA']; //建筑面积
        $houseInfo['title'] = $house['remark']; //标题
        $houseInfo['bedRoom'] = $house['bedRoom']; //室
        $houseInfo['livingRoom'] = $house['livingRoom']; //厅
        $houseInfo['bathRoom'] = $house['bathRoom']; //卫
        $houseInfo['distId'] = $house['distId'];
        $houseInfo['regId'] = $house['regId'];
        $houseInfo['parkId'] = $house['parkId'];
        $houseInfo['handPrice'] = $house['handPrice'];
        $houseInfo['tax'] = $house['tax'];
        $houseInfo['saleTax'] = $house['saleTax'];
        $houseInfo['price'] = $house['price'];
        
        global $FLOOR_POSITION, $LIVE_TYPE, $BUILD_TYPE, $UNIT_EXPOSURE, $UNIT_FITMENT;
        $houseInfo['orientation'] = $UNIT_EXPOSURE[$house['orientation']]; //朝向
        $houseInfo['floorPosition'] = $FLOOR_POSITION[$house['floorPosition']]; //楼层
        $houseInfo['decoration'] = $UNIT_FITMENT[$house['decoration']]; //装修
        $houseInfo['propertyType'] = $LIVE_TYPE[$house['propertyType']]; //物业类型
        $houseInfo['buildType'] = $BUILD_TYPE[$house['buildType']]; //建筑类型
        
        $data['house'] = $houseInfo;
        
        //区域信息
        $district = CityDistrict::instance()->getDistByIds($house['distId'], 'id,pinyin,name');
        if(!empty($district))
        {
            $data['district'] = $district[$house['distId']];
        }
        //板块信息
        $region = CityRegion::instance()->getRegionByIds($house['regId'], 'id,pinyin,name');
        if(!empty($region))
        {
            $data['region'] = $region[$house['regId']];
        }
        //小区信息
        $park = Park::instance()->getParkByIds($house['parkId'], 'id,name,salePrice,BdX,BdY');
        if(!empty($park))
        {
            $data['park'] = $park[$house['parkId']];
        }
        //房源标签
        $houseTag = HouseTag::instance()->getTagsForOption($this->_cityId, 'id,name');
        
        $houseExtTag = HouseExtTag::find("houseId={$houseId}", 0)->toArray();
        foreach($houseExtTag as $v)
        {
            $data['houseTag'][$v['tag']] = $houseTag[$v['tag']];
        }
        //房源描述
        $houseMore = HouseMore::instance()->getUnitExtById($houseId);
        $data['description'] = $houseMore['houseDescription'];
        //房源图片
        $housePic = HousePicture::instance()->getHousePicById($houseId);
        foreach($housePic as $v)
        {
            $data['housePic'][] = ImageUtility::getImgUrl(PICTURE_PRODUCT_NAME, $v['imgId'], $v['imgExt']);
        }
        //判断是否收藏
        if(!empty($this->_userInfo))
        {
            $where = "userId={$this->_userInfo['id']} and houseId={$houseId}";
            $num = HouseFavorite::count($where);
            if($num > 0)
            {
                $data['isFav'] = true;
            }
        }
        //获取 小区扩展信息
        $arrAssort = CPark::getParkAssort($house['parkId'], 2);
        $newMapArr = array();
        if(!empty($arrAssort))
        {
            foreach($arrAssort as $k=>$v)
            {
                if(array_key_exists($k, $this->_typeMapping))
                {
                    foreach($v as $key=>$value)
                    {
                        $vv = array(
                            'type' => $this->_typeMapping[$k],
                            'list' => array(
                                'assort_id' => $value['assort_id'],
                                'assort_name' => $value['assort_name'],
                                'x' => $value['x'],
                                'y' => $value['y'],
                            )
                        );
                        $newMapArr[] = $vv;
                    }
                }
            }
        }
        $parkId = $house['parkId'];
        $data['bX'] = empty($park) ? '' : $park[$parkId]['BdX'];
        $data['bY'] = empty($park) ? '' : $park[$parkId]['BdY'];
        $data['mapJson'] = json_encode($newMapArr);
        //echo '<pre>';var_dump($data['mapJson']);exit;
        //var_dump($data['mapJson']);exit;
        
        $this->show(null, $data);
    }
    
    //可能感兴趣的房源
    public function favhouseAction()
    {
        if($this->request->isPost())
        {
            $parkId = $this->request->getPost('parkId', 'int', 0);
            $houseId = $this->request->getPost('houseId', 'int', 0);
            $num = $this->request->getPost('num', 'int', 0);
            $num == 0 && $num = 7;
            
            $where = "id<>{$houseId} and parkId={$parkId} and status=".House::STATUS_ONLINE;
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,price',
                'offset' => 0,
                'limit' => $num,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(empty($res))
            {
                $this->show('JSON', array('num'=>0));
            }
            $house = $houseIds = array();
            foreach($res as $v)
            {
                $house[$v['id']] = $v;
                $house[$v['id']]['imgUrl'] = '';
                $houseIds[] = $v['id'];
            }
            //获取房源图片
            $condition = array(
                'conditions' => "houseId in(".implode(',', $houseIds).") and status=".HousePicture::STATUS_OK,
                'columns' => 'houseId,imgId,imgExt',
                'group' => 'houseId',
                'order' => 'imgId asc'
            );
            $imgRes = HousePicture::find($condition, 0)->toArray();
            foreach($imgRes as $v)
            {
                $house[$v['houseId']]['imgUrl'] = ImageUtility::getImgUrl('esf', $v['imgId'], $v['imgExt']);
            }
            shuffle($house);
            $this->show('JSON', array('num'=>count($res), 'data'=>$house));
        } else {
            $this->show('JSON', array('num'=>0));
        }
    }
    
    //同板块房源
    public function reghouseAction()
    {
        if($this->request->isPost())
        {
            $regId = $this->request->getPost('regId', 'int', 0);
            $houseId = $this->request->getPost('houseId', 'int', 0);
            $num = $this->request->getPost('num', 'int', 0);
            $num == 0 && $num = 4;
            
            $where = "id<>{$houseId} and regId={$regId} and status=".House::STATUS_ONLINE;
            $condition = array(
                'conditions' => $where,
                'columns' => 'id,bA,bedRoom,livingRoom,bathRoom,price',
                'offset' => 0,
                'limit' => $num,
                'order' => 'createTime desc'
            );
            $res = House::find($condition, 0)->toArray();
            if(empty($res))
            {
                $this->show('JSON', array('num'=>0));
            }
            $house = $houseIds = array();
            foreach($res as $v)
            {
                $house[$v['id']] = $v;
                $house[$v['id']]['imgUrl'] = '';
                $houseIds[] = $v['id'];
            }
            //获取房源图片
            $condition = array(
                'conditions' => "houseId in(".implode(',', $houseIds).") and status=".HousePicture::STATUS_OK,
                'columns' => 'houseId,imgId,imgExt',
                'group' => 'houseId',
                'order' => 'imgId asc'
            );
            $imgRes = HousePicture::find($condition, 0)->toArray();
            foreach($imgRes as $v)
            {
                $house[$v['houseId']]['imgUrl'] = ImageUtility::getImgUrl('esf', $v['imgId'], $v['imgExt']);
            }
            shuffle($house);
            $this->show('JSON', array('num'=>count($res), 'data'=>$house));
        } else {
            $this->show('JSON', array('num'=>0));
        }
    }
}

