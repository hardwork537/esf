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
