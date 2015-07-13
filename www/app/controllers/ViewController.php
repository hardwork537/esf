<?php

class ViewController extends ControllerBase
{   
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
        $park = Park::instance()->getParkByIds($house['parkId'], 'id,name,salePrice');
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
        
        $this->show(null, $data);
    }
}

