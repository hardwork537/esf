<?php

class BuyController extends houseBuy
{    
    public function listAction()
    {
        $data = array();
        $data['cssList'] = array('css/list.css');
        //筛选项
        $res = $this->getFilters();
        $data['filter'] = $res['filter'];
        $data['params'] = $res['params'];
        $data['url'] = $res['url'];
        
        $kw = trim($this->request->get('kw', 'string', ''));
        
        if($kw)
        {
            $esData = $this->_getSearchData($kw);
        } else {
            $esData = $this->_getFilterData($data['params']);
        }
        $data['totalNum'] = $totalNum = $esData['total'];
        $data['currNum'] = $currNum = count($esData['data']);
        $data['page'] = Page::create($data['totalNum'], $this->_pagesize);
        if($currNum == 0)
        {
            $this->show(null, $data);
            return;
        }
        $parkIds = $distIds = array();
        foreach($esData['data'] as $v)
        {
            $parkIds[] = $v['parkId'];
            $distIds[] = $v['distId'];
        }
        //排重
        $parkIds = array_flip(array_flip($parkIds));
        $distIds = array_flip(array_flip($distIds));
        
        $data['distList'] = CityDistrict::instance()->getDistByIds($distIds, 'id,name');
        $data['parkList'] = Park::instance()->getParkByIds($parkIds, 'id,address,name,salePrice');
        
        $data['list'] = $esData['data'];
        
        $this->show(null, $data);
    }
    
    private function _getFilterData($params)
    {
        $arrWhere = array();
        $arrWhere["cityId"] = $this->cityId;
        $arrWhere['status'] = House::STATUS_ONLINE;
        //区域
        $params['distId'] && $arrWhere['distId'] = $params['distId'];
        //板块
        $params['regId'] && $arrWhere['regId'] = $params['regId'];
        
        $otherParam = $params['otherParam'];
        if(!empty($otherParam))
        {
            //售价
            if($otherParam['p'])
            {
                $priceConfig = $this->_getPrice();
                if(!isset($priceConfig[$otherParam['p']]))
                {
                    return array('total'=>0);
                }
                $price = $priceConfig[$otherParam['p']];
                if(isset($price['max']))
                {
                    $arrWhere['housePrice'] = array('<=' => $price['max']*10000);
                }
                if(isset($price['min']))
                {
                    $arrWhere['housePrice'] = array('>=' => $price['min']*10000);
                }
            }
            //面积
            if($otherParam['a'])
            {
                $areaConfig = $this->_getArea();
                if(!isset($areaConfig[$otherParam['a']]))
                {
                    return array('total'=>0);
                }
                $area = $areaConfig[$otherParam['a']];
                if(isset($area['max']))
                {
                    $arrWhere['houseBA'] = array('<=' => $area['max']);
                }
                if(isset($area['min']))
                {
                    $arrWhere['houseBA'] = array('>=' => $area['min']);
                }
            }
            //户型
            if($otherParam['h'])
            {
                $htConfig = $this->_getHouseType();
                if(!isset($htConfig[$otherParam['h']]))
                {
                    return array('total'=>0);
                }
                $houseType = $htConfig[$otherParam['h']];
                if(isset($houseType['eq']))
                {
                    $arrWhere['houseBedRoom'] = $houseType['eq'];
                }
                if(isset($houseType['max']))
                {
                    $arrWhere['houseBedRoom'] = array('<=' => $houseType['max']);
                }
                if(isset($houseType['min']))
                {
                    $arrWhere['houseBedRoom'] = array('>=' => $houseType['min']);
                }
            }
        }
        
        $limit = array($this->_offset, $this->_pagesize);
        //$order = array( "houseUpdate:desc", "_id:desc" );
        $order = array();
        switch($otherParam['o'])
        {
            case 1:
                $order[] = "housePrice:asc";
                break;
            case 2:
                $order[] = "housePrice:desc";
                break;
            case 3:
                $order[] = "houseBA:asc";
                break;
            case 4:
                $order[] = "houseBA:desc";
                break;
            default:
                $order[] = "houseUpdate:desc";
                break;
        }
        //$order[] = "_id:desc";

        $esData = $this->_getEsData($arrWhere, $limit, $order);
        
        return $esData;
    }
    
    private function _getSearchData($keyword, $params)
    {
        $arrWhere = array();
        $arrWhere['cityId'] = $this->_cityId;
        $arrWhere['status'] = House::STATUS_ONLINE;
        $arrWhere['parkName'] = array('like' => $keyword);
        $arrWhere['houseAddress'] = array('likeor' => $keyword);
        
        $limit = array($this->_offset, $this->_pagesize);
        //$order = array( "houseUpdate:desc", "_id:desc" );
        $order = array();
        switch($otherParam['o'])
        {
            case 1:
                $order[] = "housePrice:asc";
                break;
            case 2:
                $order[] = "housePrice:desc";
                break;
            case 3:
                $order[] = "houseBA:asc";
                break;
            case 4:
                $order[] = "houseBA:desc";
                break;
            default:
                $order[] = "houseUpdate:desc";
                break;
        }
        $esData = $this->_getEsData($arrWhere, $limit, $order);
        
        return $esData;
    }
    
    private function _getEsData($where,  $limit, $order)
    {
        global $sysES;
        $clsEs = new Es($sysES['default']);
        $arrSelect = $clsEs->search(array('where' => $where, 'limit' => $limit, 'order'=>$order));
       
        return $arrSelect;
    }
}
