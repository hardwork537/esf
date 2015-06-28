<?php

class HouseController extends ControllerBase
{

    public function listAction()
    {
        $data = array();
        $data['cityId'] = $this->_cityId;
        
        //房源等级
        $data['levels'] = House::getAllLevelStatus();
        //房源状态
        $data['statuses'] = array(
            House::STATUS_ONLINE => '在线',
            House::STATUS_OFFLINE => '下架'
        );
        //归属人
        $where = "cityId={$this->_cityId} and status=".  AdminUser::STATUS_VALID;
        $condition = array(
            'conditions' => $where,
            'columns' => 'id,name',
            'limit' => array(
                'offset' => $this->_offset,
                'number' => $this->_pagesize
            )
        );
        $result = AdminUser::find($condition, 0)->toArray();
        foreach($result as $v)
        {
            $data['users'][$v['id']] = $v['name'];
        }

        $filterRes = $this->_filterParams();
        $data['params'] = $filterRes['params'];
        if(0 != $filterRes['status'])
        {
            $this->show(null, $data);
            return;
        }
        $where = $filterRes['where'];
        $result = House::find($where, 0)->toArray();
        if(empty($result))
        {
            $this->show(null, $data);
            return;
        }
        $list = $distIds = $regIds = $parkIds = $userIds = array();
        foreach($result as $v)
        {
            $value = array();
            $distIds[] = $value['distId'] = $v['distId'];
            $regIds[] = $value['regId'] = $v['regId'];
            $parkIds[] = $value['parkId'] = $v['parkId'];
            $value['bedRoom'] = $v['bedRoom'];
            $value['livingRoom'] = $v['livingRoom'];
            $value['bathRoom'] = $v['bathRoom'];
            $value['bA'] = $v['bA'];
            $value['userId'] = $v['userId'];
            $value['price'] = $v['price'];
            $value['createTime'] = date('Y.m.d', $v['createTime']);
            $value['picNum'] = $v['picNum'];
            
            $list[$v['id']] = $value;
        }
        $distIds = array_flip(array_flip($distIds));
        $regIds = array_flip(array_flip($regIds));
        $parkIds = array_flip(array_flip($parkIds));
        //区域
        $data['dists'] = CityDistrict::instance()->getDistByIds($distIds, 'id,name');
        //板块
        $data['regs'] = CityRegion::instance()->getRegionByIds($regIds, 'id,name');
        //小区
        $data['parks'] = Park::instance()->getParkByIds($parkIds, 'id,name');
        
        $totalNum = House::count($where);
        $data['page'] = Page::create($totalNum, $this->_pagesize);
        
        $data['lists'] = $list;
        
        $this->show(null, $data);
    }
    
    private function _filterParams()
    {
        $params = array();
        $params['parkName'] = $parkName = trim($this->request->get('parkName', 'string', ''));
        $params['distId'] = $distId = $this->request->get('distId', 'int', 0);
        $params['regId'] = $regId = $this->request->get('regId', 'int', 0);
        $params['level'] = $level = $this->request->get('level', 'int', 0);
        $params['userId'] = $userId = $this->request->get('userId', 'int', 0);
        $params['status'] = $status = $this->request->get('status', 'int', 0);
        
        $where = "cityId={$this->_cityId}";
        if($parkName)
        {
            $parkWhere = "cityId={$this->_cityId} and name='{$parkName}' and status=".Park::STATUS_VALID;
            $park = Park::findFirst($parkWhere, 0)->toArray();
            if(empty($park))
            {
                return array('status'=>1, 'params' => $params);
            }
            $where .= " and parkId={$park['id']}";
        }
        $distId && $where .= " and distId={$distId}";
        $regId && $where .= " and regId={$regId}";
        if($level)
        {
            $levelCon = House::getAllLevel($level);
            foreach($levelCon as $k=>$v)
            {
                $where .= " and picNum{$k}{$v}";
            }
        }
        $userId && $where .= " and userId={$userId}";
        $status && $where .= " and status={$status}";
        
        return array('status'=>0, 'where'=>$where, 'params'=>$params);
    }

    public function addAction()
    {
        $data = array();
        if($this->request->isPost())
        {
            $checkRes = $this->_checkParams();
            
            if($checkRes['status'] != 0)
            {
                $this->show('JSON', $checkRes);
            }
            
            $addRes = House::instance()->addHouse($checkRes['params']);
            $this->show('JSON', $addRes);
        }
        
        $data['action'] = 'add';
        $data['cityId'] = $this->_cityId;
        $data['options'] = $this->_getOption();
        
        $this->show('edit', $data);
    }

    public function editAction($id = 0)
    {
        $data = array();
        if($this->request->isPost())
        {
            $id = $this->request->getPost('houseId', 'int', 0);
            $checkRes = $this->_checkParams('edit');
            
            if($checkRes['status'] != 0)
            {
                $this->show('JSON', $checkRes);
            }
            
            $updateRes = House::instance()->editHouse($id, $checkRes['params']);
            $this->show('JSON', $updateRes);
        }
        $id = intval($id);
        //房源基本信息
        $house = House::findFirst($id, 0)->toArray();
        if(empty($house))
        {
            echo "<script>alert('房源不存在');location.href='/house/list/'</script>";
            exit;
        }
        //小区信息
        $park = Park::findFirst($house['parkId'], 0)->toArray();
        $house['parkName'] = $park['name'];
        //房源描述信息
        $houseMore = HouseMore::findFirst("houseId={$house['id']} and name='".HouseMore::$descColumnName."'", 0)->toArray();
        $house['desc'] = $houseMore['text'];
        
        $data['house'] = $house;
        
        $data['action'] = 'edit';
        $data['cityId'] = $this->_cityId;
        $data['options'] = $this->_getOption();
        
        $this->show(null, $data);
    }
    
    private function _checkParams($type = 'add')
    {
        $options = $this->_getOption();
        
        if('edit' != $type)
        {
            /** 编辑时，这些字段不需要判断 **/
            //验证城市
            $cityId = $this->request->getPost('cityId', 'int', 0);
            $where = "id={$cityId} and status=".City::STATUS_ENABLED;
            $cityNum = City::count($where);
            if($cityNum == 0)
                return array('status' => 1, 'info' => '城市无效');
            //验证区域
            $distId = $this->request->getPost('distId', 'int', 0);
            $where = "id={$distId} and cityId={$cityId} and status=".CityDistrict::STATUS_ENABLED;
            $distNum = CityDistrict::count($where);
            if($distNum == 0)
                return array('status' => 1, 'info' => '区域无效');
            //验证板块
            $regId = $this->request->getPost('regId', 'int', 0);
            $where = "id={$regId} and cityId={$cityId} and distId={$distId} and status=".CityRegion::STATUS_ON;
            $regNum = CityRegion::count($where);
            if($regNum == 0)
                return array('status' => 1, 'info' => '板块无效');
            //验证小区
            $parkName = trim($this->request->getPost('parkName', 'string', ''));
            $where = "name='{$parkName}' and cityId={$cityId}";
            $park = Park::findFirst($where, 0)->toArray();
            if(empty($park))
                return array('status' => 1, 'info' => '该城市不存在该小区');
            //验证物业类型
            $propertyType = $this->request->getPost('propertyType', 'int', 0);
            if(!array_key_exists($propertyType, $options['propertyType']))
                return array('status' => 1, 'info' => '无效的物业类型');
            //验证建筑类型
            $buildType = $this->request->getPost('buildType', 'int', 0);
            if(!array_key_exists($buildType, $options['buildType']))
                return array('status' => 1, 'info' => '无效的建筑类型');
            //验证朝向
            $orientation = $this->request->getPost('orientation', 'int', 0);
            if(!array_key_exists($orientation, $options['orientation']))
                return array('status' => 1, 'info' => '无效的朝向');
            //验证装修状况
            $decoration = $this->request->getPost('decoration', 'int', 0);
            if(!array_key_exists($decoration, $options['decoration']))
                return array('status' => 1, 'info' => '装修状况');
            //验证楼层位置
            $floorPosition = $this->request->getPost('floorPosition', 'int', 0);
            if(!array_key_exists($floorPosition, $options['floorPosition']))
                return array('status' => 1, 'info' => '无效的楼层位置');
            //单元号
            $unitNo = $this->request->getPost('unitNo', 'int', 0);
            if($unitNo < 1)
                return array('status' => 1, 'info' => '单元号不能为空');
            //室号
            $roomNo = $this->request->getPost('roomNo', 'int', 0);
            if($roomNo < 1)
                return array('status' => 1, 'info' => '室号不能为空');
            //室
            $bedRoom = $this->request->getPost('bedRoom', 'int', 0);
            //厅
            $livingRoom = $this->request->getPost('livingRoom', 'int', 0);
            //卫
            $bathRoom = $this->request->getPost('bathRoom', 'int', 0);
            //建筑面积
            $bA = $this->request->getPost('bA', 'int', 0);
            if($bA < 1)
                return array('status' => 1, 'info' => '建筑面积不能为空');
            //到手价
            $handPrice = $_REQUEST['handPrice'];
            if($handPrice < 1)
                return array('status' => 1, 'info' => '到手价不能为空');
            //房源描述
            $houseDesc = $_POST['houseDesc'];
            if(!$houseDesc)
                return array('status' => 1, 'info' => '房源描述不能为空');
        }                             
        //总楼层
        $floorMax = intval($this->request->getPost('floorMax', 'int', 0));
        //电梯数量
        $listCount = $this->request->getPost('listCount', 'int', 0);      
        //使用面积
        $uA = $this->request->getPost('uA', 'int', 0);       
        //买入价
        $buyPrice = $_REQUEST['buyPrice'];
        //营业税
        $saleTax = $_REQUEST['saleTax'];
        //个税
        $tax = $_REQUEST['tax'];
        //验证满五年
        $isFiveYear = $this->request->getPost('isFiveYear', 'int', 0);
        if(!array_key_exists($isFiveYear, $options['isFiveYear']))
            return array('status' => 1, 'info' => '无效的满五年状态');
        //验证唯一一套
        $isOnlyOne = $this->request->getPost('isOnlyOne', 'int', 0);
        if(!array_key_exists($isOnlyOne, $options['isOnlyOne']))
            return array('status' => 1, 'info' => '无效的唯一一套状态');
        //验证境外人士
        $isForeign = $this->request->getPost('isForeign', 'int', 0);
        if(!array_key_exists($isForeign, $options['isForeign']))
            return array('status' => 1, 'info' => '无效的境外人士状态');
        //验证租约
        $isRent = $this->request->getPost('isRent', 'int', 0);
        if(!array_key_exists($isRent, $options['isRent']))
            return array('status' => 1, 'info' => '无效的租约状态');
        //验证车位
        $hasPark = $this->request->getPost('hasPark', 'int', 0);
        if(!array_key_exists($hasPark, $options['hasPark']))
            return array('status' => 1, 'info' => '无效的车位状态');
        //验证抵押
        $isMortgage = $this->request->getPost('isMortgage', 'int', 0);
        if(!array_key_exists($isMortgage, $options['isMortgage']))
            return array('status' => 1, 'info' => '无效抵押状态');
        //验证户口
        $hasHukou = $this->request->getPost('hasHukou', 'int', 0);
        if(!array_key_exists($hasHukou, $options['hasHukou']))
            return array('status' => 1, 'info' => '无效的户口状态');
        //验证赠送明细
        $giveDetail = $this->request->getPost('giveDetail', 'int', 0);
        if(!array_key_exists($giveDetail, $options['giveDetail']))
            return array('status' => 1, 'info' => '无效的赠送明细');
        //产权人
        $propertyOwner = trim($this->request->getPost('propertyOwner', 'string', ''));
        //产权人联系方式
        $propertyPhone = trim($this->request->getPost('propertyPhone', 'string', ''));
        //代理人
        $agent = trim($this->request->getPost('agent', 'string', ''));
        //代理人联系方式
        $agentPhone = trim($this->request->getPost('agentPhone', 'string', ''));
        //月租金
        $rentPrice = trim($this->request->getPost('rentPrice', 'string', ''));
        //到期时间
        $rentEndTime = trim($this->request->getPost('rentEndTime', 'string', ''));
        $rentEndTime = date('Y-m-d', strtotime($rentEndTime));
        //价格
        $price = trim($this->request->getPost('price', 'string', ''));
        //备注
        $remark = trim($this->request->getPost('remark', 'string', ''));      
        //是否发布
        $publish = $this->request->getPost('publish', 'string', '');
        $isPublish = '1' == $publish ? true : false;
        
        $params = array(
            'cityId' => $cityId,
            'distId' => $distId,
            'regId' => $regId,
            'userId' => $this->_userInfo['id'],
            'parkId' => $park['id'],
            'propertyType' => $propertyType,
            'buildType' => $buildType,
            'orientation' => $orientation,
            'decoration' => $decoration,
            'floorPosition' => $floorPosition,
            'floorMax' => $floorMax,
            'listCount' => $listCount,
            'unitNo' => $unitNo,
            'roomNo' => $roomNo,
            'bedRoom' => $bedRoom,
            'livingRoom' => $livingRoom,
            'bathRoom' => $bathRoom,
            'bA' => $bA,
            'uA' => $uA,
            'handPrice' => $handPrice,
            'buyPrice' => $buyPrice,
            'saleTax' => $saleTax,
            'tax' => $tax,
            'isFiveYear' => $isFiveYear,
            'isOnlyOne' => $isOnlyOne,
            'propertyOwner' => $propertyOwner,
            'propertyPhone' => $propertyPhone,
            'agent' => $agent,
            'agentPhone' => $agentPhone,
            'isRent' => $isRent,
            'rentPrice' => $rentPrice,
            'rentEndTime' => $rentEndTime,
            'hasPark' => $hasPark,
            'hasHukou' => $hasHukou,
            'isForeign' => $isForeign,
            'price' => $price,
            'isMortgage' => $isMortgage,
            'giveDetail' => $giveDetail,
            'remark' => $remark,
            'houseDesc' => $houseDesc,
            'isPublish' => $isPublish
        );
        
        return array('status' => 0, 'params' => $params);
    }
    
    private function _getOption($type = '')
    {
        $options = array();
        
        //物业类型
        $options['propertyType'] = $GLOBALS['LIVE_TYPE'];
        //建筑类型
        $options['buildType'] = $GLOBALS['BUILD_TYPE'];
        //朝向
        $options['orientation'] = $GLOBALS['UNIT_EXPOSURE'];
        //装修状况
        $options['decoration'] = $GLOBALS['UNIT_FITMENT'];
        //楼层位置
        $options['floorPosition'] = $GLOBALS['FLOOR_POSITION'];
        //是否境外人士
        $options['isForeign'] = House::getAllForeignStatus();
        //有无车位
        $options['hasPark'] = House::getAllParkStatus();
        //租约
        $options['isRent'] = House::getAllRentStatus();
        //是否抵押
        $options['isMortgage'] = House::getAllMortgageStatus();
        //是否有户口
        $options['hasHukou'] = House::getAllHukouStatus();
        //赠送明细
        $options['giveDetail'] = $GLOBALS['GIVE_DETAIL'];
        //是否满五年
        $options['isFiveYear'] = House::getAllFiveYearStatus();
        //是否唯一一套
        $options['isOnlyOne'] = House::getAllOnlyOneStatus();
        
        return $type ? $options[$type] : $options;
    }
    
    public function pictureAction($houseId)
    {
        $data = array();
        $data['id'] = $houseId = intval($houseId);
        $data['userId'] = $this->_userInfo['id'];
        $data['from'] = Image::FROM_CRM_HOUSE;
        //房源图片
        $result = HousePicture::find("houseId={$houseId} and status=".HousePicture::STATUS_OK, 0)->toArray();
        if(empty($result))
        {
            $this->show(null, $data);
        }
        
        $pictures = array();
        foreach($result as $v)
        {
            $value = array(
                'id' => $v['imgId'],
                'ext' => $v['imgExt'],
                'url' => ImageUtility::getImgUrl(PICTURE_PRODUCT_NAME, $v['imgId'], $v['imgExt'])
            );
            //var_dump($value);exit;
            $pictures[] = $value;
        }
        $data['pictures'] = $pictures;
        
        $this->show(null, $data);
    }
    
    public function moreAction($houseId)
    {
        $data = array();
        $data['id'] = intval($houseId);
        //房源基本信息
        $house = House::findFirst($id, 0)->toArray();
        if(empty($house))
        {
            echo "<script>alert('房源不存在');location.href='/house/list/'</script>";
            exit;
        }
        $data['house'] = $house;
        $data['statuses'] = array(House::STATUS_ONLINE => '在线', House::STATUS_OFFLINE=>'下架');
        $data['online'] = House::STATUS_ONLINE;
        $data['offline'] = House::STATUS_OFFLINE;
        $data['xiajiaReason'] = array(
            0 => '下架原因'
        );
        $data['xiajiaReason'] += House::getAllOfflineReason();
        //房源标签
        $tags = HouseTag::find("cityId={$house['cityId']}", 0)->toArray();
        $houseTag = array();
        foreach($tags as $v)
        {
            $houseTag[$v['id']] = $v['name'];
        }
        $data['houseTag'] = $houseTag;
        //该套房源标签
        $result = HouseExtTag::find("houseId={$house['id']}", 0)->toArray();
        $tagList = array();
        foreach($result as $v)
        {
            $tagList[] = $v['tag'];
        }
        $data['tagList'] = $tagList;
        
        $this->show(null, $data);
    }
    
    /**
     * 上下架房源
     * @param type $type
     * @param type $reason
     */
    public function operateAction($houseId, $type, $reason = 0)
    {
        $id = intval($houseId);
        $house = House::findFirst($id);
        if(!$house)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'房源不存在'));
        }
        if(House::STATUS_ONLINE == $type)
        {
            //上线房源
            $data = array(
                'status' => House::STATUS_ONLINE
            );
            $res = $house->update($data);
            if($res)
            {
                $this->show('JSON', array('status'=>0, 'info'=>'上线成功'));
            } else {
                $this->show('JSON', array('status'=>1, 'info'=>'上线失败'));
            }
            
        } elseif(House::STATUS_OFFLINE == $type) {
            //下架房源
            $reason = intval($reason);
            $reasons = House::getAllOfflineReason();
            if(!array_key_exists($reason, $reasons))
            {
                $this->show('JSON', array('status'=>1, 'info'=>'无效的下架原因'));
            }
            $data = array(
                'status' => House::STATUS_OFFLINE,
                'xiajiaReason' => $reason,
                'xiajiaTime' => date('Y-m-d H:i:s')
            );
            $res = $house->update($data);
            if($res)
            {
                $this->show('JSON', array('status'=>0, 'info'=>'下架成功'));
            } else {
                $this->show('JSON', array('status'=>1, 'info'=>'下架失败'));
            }
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'无效操作'));
        }
    }
    
    public function tagAction($type, $houseId, $tagId)
    {
        if(!in_array($type, array('add', 'del')))
        {
            $this->show('JSON', array('status'=>1, 'info'=>'无效操作'));
        }
        $houseId = intval($houseId);
        $house = House::findFirst($houseId, 0)->toArray();
        if(empty($house))
        {
            $this->show('JSON', array('status'=>1, 'info'=>'房源不存在'));
        }
        
        $tagId = intval($tagId);
        if('add' == $type)
        {
            //添加标签
            $tag = HouseTag::findFirst($tagId);
        
            if(!$tag)
            {
                $this->show('JSON', array('status'=>1, 'info'=>'标签不存在'));
            }
            $tagNum = HouseExtTag::count("houseId={$houseId}");
            if($tagNum >= 3)
            {
                $this->show('JSON', array('status'=>1, 'info'=>'最多只能选择 3 个标签'));
            }
            $tag = HouseExtTag::instance();
            $data = array(
                'cityId' => $house['cityId'],
                'houseId' => $houseId,
                'tag' => $tagId,
                'addTime' => date('Y-m-d H:i:s')
            );
            $addRes = $tag->create($data);
            if($addRes)
            {
                $this->show('JSON', array('status'=>0, 'info'=>'标签添加成功'));
            } else {
                $this->show('JSON', array('status'=>1, 'info'=>'标签添加失败'));
            }
            
        } elseif('del' == $type) {
            //删除标签
            $houseTag = HouseExtTag::findFirst("houseId={$houseId} and tag={$tagId}");
            if(!$houseTag)
            {
                $this->show('JSON', array('status'=>1, 'info'=>'该房源标签不存在'));
            }
            $delRes = $houseTag->delete();
            if($delRes)
            {
                $this->show('JSON', array('status'=>0, 'info'=>'标签取消成功'));
            } else {
                $this->show('JSON', array('status'=>1, 'info'=>'标签取消失败'));
            }
        }      
    }
    
    public function delpicAction()
    {
        $houseId = $this->request->getPost('houseId', 'int', 0);
        $ids = trim($this->request->getPost('ids', 'string', ''));
        
        $house = House::count($houseId);
        if($house < 1)
        {
            $this->show("JSON", array('status'=>1, 'info'=>'房源不存在'));
        }
        $id = substr($ids, 1);
        $picIds = explode('_', $id);
        
        $delRes = HousePicture::instance()->delHousePicture($houseId, $picIds);
            
        $this->show("JSON", $delRes);        
    }
}
