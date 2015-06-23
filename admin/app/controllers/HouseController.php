<?php

class HouseController extends ControllerBase
{

    public function listAction()
    {
        $this->show(null, $data);
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
        }
        
        $data['action'] = 'add';
        $data['cityId'] = $this->_cityId;
        $data['options'] = $this->_getOption();
        
        $this->show('edit', $data);
    }

    public function editAction()
    {
        $data = array();
        if($this->request->isPost())
        {
            
        }
        $data['action'] = 'edit';
        $data['options'] = $this->_getOption();
        
        $this->show(null, $data);
    }
    
    private function _checkParams()
    {
        //验证城市
        $cityId = $this->request->getPost('cityId', 'int', 0);
        $where = "id={$cityId} and status=".City::STATUS_ENABLED;
        $cityNum = City::count($where);
        if($cityNum == 0)
            return array('status' => 1, 'info' => '城市无效');
        //验证小区
        $parkName = trim($this->request->getPost('parkName', 'string', ''));
        $where = "name='{$parkName}' and cityId={$cityId}";
        $park = Park::findFirst($where, 0)->toArray();
        if(empty($park))
            return array('status' => 1, 'info' => '该城市不存在该小区');
        
        $options = $this->_getOption();
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
        //总楼层
        $floorMax = $this->request->getPost('floorMax', 'int', 0);
        //电梯数量
        $listCount = $this->request->getPost('listCount', 'int', 0);
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
        //使用面积
        $uA = $this->request->getPost('uA', 'int', 0);
        //到手价
        $handPrice = $_REQUEST['handPrice'];
        if($handPrice < 1)
            return array('status' => 1, 'info' => '到手价不能为空');
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
        //房源描述
        $houseDesc = trim($this->request->getPost('houseDesc', 'string', ''));
        if(!$houseDesc)
            return array('status' => 1, 'info' => '房源描述不能为空');
        
        $params = array(
            'cityId' => $cityId,
            'parkId' => $park['id'],
            'propertyType' => $propertyType,
            'buildType' > $buildType,
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
            'houseDesc' => $houseDesc
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
}
