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
        $data['cssList'] = array('css/property_view.css?v=2015081011');
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
        $houseInfo['title'] = $house['title']; //标题
        $houseInfo['bedRoom'] = $house['bedRoom']; //室
        $houseInfo['livingRoom'] = $house['livingRoom']; //厅
        $houseInfo['bathRoom'] = $house['bathRoom']; //卫
        $houseInfo['distId'] = $house['distId'];
        $houseInfo['regId'] = $house['regId'];
        $houseInfo['parkId'] = $house['parkId'];
        $houseInfo['handPrice'] = $house['handPrice'];
        $houseInfo['contractTax'] = $house['contractTax'];
        $houseInfo['tax'] = $house['tax'];
        $houseInfo['saleTax'] = $house['saleTax'];
        $houseInfo['price'] = $house['handPrice'];
        $houseInfo['cityId'] = $house['cityId'];

        global $FLOOR_POSITION, $LIVE_TYPE, $BUILD_TYPE, $UNIT_EXPOSURE, $UNIT_FITMENT;
        $houseInfo['orientation'] = $UNIT_EXPOSURE[$house['orientation']]; //朝向
        $houseInfo['floorPosition'] = $FLOOR_POSITION[$house['floorPosition']]; //楼层
        $houseInfo['decoration'] = $UNIT_FITMENT[$house['decoration']]; //装修
        $houseInfo['propertyType'] = $LIVE_TYPE[$house['propertyType']]; //物业类型
        $houseInfo['buildType'] = $BUILD_TYPE[$house['buildType']]; //建筑类型

        $data['house'] = $houseInfo;

        //获取400电话
        
        
        //区域信息
        $data['district'] = CHouse::getDistById($house['distId'], 'id,pinyin,name');
        
        //板块信息
        $data['region'] = CHouse::getRegById($house['regId'], 'id,pinyin,name');

        //小区信息
        $data['park'] = CHouse::getParkById($house['parkId'], 'id,name,salePrice,BdX,BdY');
        $park[$house['parkId']] = $data['park'];
        
        //获取房源发布者信息
        $userInfo = CHouse::getUserById($house['userId']);
        if(!empty($userInfo))
        {
            $phone400 = CHouse::getPhoneByMobile($userInfo['tel']);
            if(!empty($phone400))
            {
                $data['phone400'] = array(
                    'host' => $phone400['phoneHost'],
                    'ext' => $phone400['phoneExt']
                );
            }
        }

        //均价
        $data['house']['avgPrice'] = $house['bA'] ? number_format($house['handPrice'] / $house['bA'], 0) : intval($data['park']['salePrice']);
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
            foreach($arrAssort as $k => $v)
            {
                if(array_key_exists($k, $this->_typeMapping))
                {
                    foreach($v as $key => $value)
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
        //设置title
        $title = $house['title'];
        $title .= ($title ? '-' : '').$region[$house['regId']]['name'].'二手房';
        $title .= '-房易买二手房';

        $this->_setTitle($title);

        $this->show(null, $data);
    }

    //可能感兴趣的房源
    public function favhouseAction()
    {
        if($this->request->isPost())
        {
            $cityId = $this->request->getPost('cityId', 'int', 0);
            $parkId = $this->request->getPost('parkId', 'int', 0);
            $houseId = $this->request->getPost('houseId', 'int', 0);
            $regId = $this->request->getPost('regId', 'int', 0);
            $bedRoom = $this->request->getPost('bedRoom', 'int', 0);
            $livingRoom = $this->request->getPost('livingRoom', 'int', 0);
            $bathRoom = $this->request->getPost('bathRoom', 'int', 0);
            $price = $this->request->getPost('price');
            $num = $this->request->getPost('num', 'int', 0);
            $num == 0 && $num = 7;

            $house = CHouse::getFavHouse($num, $houseId, $cityId, $regId, $parkId, $bedRoom, $livingRoom, $bathRoom, $price);
            $this->show('JSON', array('num' => count($house), 'data' => $house));
        } else
        {
            $this->show('JSON', array('num' => 0));
        }
    }

    //同板块房源
    public function reghouseAction()
    {
        if($this->request->isPost())
        {
            $distId = $this->request->getPost('distId', 'int', 0);
            $regId = $this->request->getPost('regId', 'int', 0);
            $houseId = $this->request->getPost('houseId', 'int', 0);
            $cityId = $this->request->getPost('cityId', 'int', 0);
            $price = $this->request->getPost('price');
            $num = $this->request->getPost('num', 'int', 0);
            $num == 0 && $num = 4;

            $house = CHouse::getRegHouse($num, $houseId, $cityId, $distId, $regId, $price);
            $this->show('JSON', array('num' => count($house), 'data' => $house));
        } else
        {
            $this->show('JSON', array('num' => 0));
        }
    }

}
