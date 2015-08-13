<?php

class HomeController extends ControllerBase
{
    private $_defaultCityId = 2;
    private $_limitNum = 4;
    
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/index.css?v=20150801001');
        //热门搜索
        $data['baseUrl'] = substr(WWW_BASE_URL, 0, -1);
        $data['hot'] = HotSearch::instance()->getHotSearchByCityId(2);
        //推荐最新房源
        $res = CHouse::getHomeNewHouse($this->_defaultCityId, 'id,parkId,price,handPrice', $this->_limitNum);
        
        if(empty($res))
        {
            $this->show(null, $data);
            return;
        }
        $data['houseList'] = $res;
        $parkIds = $houseIds = array();
        foreach($res as $v)
        {
            $parkIds[] = $v['parkId'];
            $houseIds[] = $v['id'];
        }
        $parkIds = array_flip(array_flip($parkIds));
        //小区信息
        $data['parkList'] = Park::instance()->getParkByIds($parkIds, 'id,name');
        //房源图片
        $data['housePic'] = HousePicture::instance()->getHousePicsByIds($houseIds);
        
        $this->_setTitle('房易买-首页');
        
        $this->show(null, $data);
    }

}
