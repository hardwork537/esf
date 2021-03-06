<?php

/**
 * @abstract  买房基础类
 */
class houseBuy extends ControllerBase
{
    protected $cityId;
    protected $cityName;
    protected $district = array();
    protected $region = array();
    protected $otherParam = array();
    protected $distPinyin = '';
    protected $regPinyin = '';
    protected $houseTag;
    protected $types = array(
        'distId' => 'distId',
        'regId' => 'regId',
        'p' => 'price',
        'a' => 'area',
        'h' => 'houseType',
        'f' => 'feature',
        'o' => 'order'
    );
    protected $title;

    protected function initialize()
    {      
        parent::initialize();
        $tmp = explode('.', $_SERVER['HTTP_HOST']);
        //if('www' == $tmp[0])
        //{
            $cityDefault = $GLOBALS['CITY_DEFAULT'];
            $this->cityId = $cityDefault['cityId'];
            $this->cityName = $cityDefault['cityName'];
            $this->title = $this->cityName;
        //}
    }
    
    /**
     * 获取筛选项
     */
    protected function getFilters($type = '')
    {
        if($type)
        {
            if(method_exists($this, "_get" . ucfirst($type)))
            {
                return $this->{"_get" . ucfirst($type)}($value);
            } else {
                return array();
            }
        }
        $filters = array();       
        
        //获取区域
        $filters['dist'] = $this->district = CityDistrict::instance()->getDistrict($this->cityId, 'id,pinyin,name');
        
        //获取售价
        $price = $this->_getPrice();
        foreach($price as $k=>$v)
        {
            $filters['price'][$k] = $v['name'];
        }
        
        //获取面积
        $area = $this->_getArea();
        foreach($area as $k=>$v)
        {
            $filters['area'][$k] = $v['name'];
        }
        
        //获取户型
        $houseType = $this->_getHouseType();
        foreach($houseType as $k=>$v)
        {
            $filters['houseType'][$k] = $v['name'];
        }
        //获取特色
        $filters['feature'] = $this->_getFeature();
        
        //处理参数
        $resolveRes = $this->resolveParam();
        if(!empty($this->region))
        {
            $filters['reg'] = $this->region;
        }
        //获取url
        $url = $this->_getFilterUrl($filters);
        
        return array('filter' => $filters, 'url'=>$url, 'params' => (array)$resolveRes['filter']);
    }
    
    protected function _getPrice()
    {
        return $GLOBALS['TAB_PRICE'];
    }
    
    protected function _getArea()
    {
        return $GLOBALS['TAB_AREA'];
    }
    
    protected function _getHouseType()
    {
        return $GLOBALS['TAB_HOUSE_TYPE'];
    }
    
    protected function _getFeature()
    {        
        $this->houseTag = $houseTag = HouseTag::instance()->getTagsForOption($this->cityId);
        
        return $houseTag;
    }


    private function _getFilterUrl($filters)
    {
        $data = array();
        
        $otherfilter = '';
        if(!empty($this->otherParam))
        {
            foreach($this->otherParam as $k=>$v)
            {
                $otherfilter .= $k.$v;
            }
        }
        //区域
        $data['dist'] = array();
        $data['dist'][0] = $otherfilter ? "/{$otherfilter}/" : "/";
        foreach($filters['dist'] as $k=>$v)
        {
            $data['dist'][$v['id']] = "/{$v['pinyin']}/";
            $otherfilter && $data['dist'][$v['id']] .= "{$otherfilter}/";
        }
        //板块
        if(!empty($filters['reg']))
        {
            $data['reg'][0] = $otherfilter ? "/{$otherfilter}/" : "/";
            foreach($filters['reg'] as $k=>$v)
            {
                $data['reg'][$v['id']] = "/{$v['pinyin']}/";
                $otherfilter && $data['reg'][$v['id']] .= "{$otherfilter}/";
            }    
        }
        //拼接参数
        $combine = function($allParams, $type) {
            if($this->distPinyin)
            {
                $str = "/{$this->distPinyin}/";
            } elseif($this->regPinyin) {
                $str = "/{$this->regPinyin}/";
            } else {
                $str = '/';
            }
            
            if(isset($allParams[$type]))
            {
                unset($allParams[$type]);
            }
            foreach($allParams as $k=>$v)
            {
                $str .= $k.$v;
            }
            
            return $str;
        };
        //售价
        $otherfilter = $combine($this->otherParam, 'p');
        $data['price'][0] = $otherfilter!='/' ? $otherfilter . '/' : '/';
        foreach($filters['price'] as $k=>$v)
        {
            $data['price'][$k] = $otherfilter . "p{$k}/" ;
        }
        //面积
        $otherfilter = $combine($this->otherParam, 'a');
        $data['area'][0] = $otherfilter!='/' ? $otherfilter . '/' : '/';
        foreach($filters['area'] as $k=>$v)
        {
            $data['area'][$k] = $otherfilter . "a{$k}/" ;
        }
        //户型
        $otherfilter = $combine($this->otherParam, 'h');
        $data['houseType'][0] = $otherfilter!='/' ? $otherfilter . '/' : '/';
        foreach($filters['houseType'] as $k=>$v)
        {
            $data['houseType'][$k] = $otherfilter . "h{$k}/" ;
        }
        //特色
        $otherfilter = $combine($this->otherParam, 'f');
        $data['feature'][0] = $otherfilter!='/' ? $otherfilter . '/' : '/';
        foreach($filters['feature'] as $k=>$v)
        {
            $data['feature'][$k] = $otherfilter . "f{$k}/" ;
        }
        //排序
        $otherfilter = $combine($this->otherParam, 'o');
        $data['order']['default'] = $otherfilter!='/' ? $otherfilter . '/' : '/';
        if(2 == $this->otherParam['o'])
        {
            $data['order']['price'] = $otherfilter . "o1/" ;
        } else {
            $data['order']['price'] = $otherfilter . "o2/" ;
        }
        if(4 == $this->otherParam['o'])
        {
            $data['order']['area'] = $otherfilter . "o3/" ;
        } else {
            $data['order']['area'] = $otherfilter . "o4/" ;
        }
        
        return $data;
    }
    /**
     * 解析参数
     * @param type $param
     */
    protected function resolveParam()
    {
        $param1 = $this->dispatcher->getParam('param1');
        $param2 = $this->dispatcher->getParam('param2');
        
        $filter = array();

        if($param1)
        {
            //第一个参数
            if(preg_match('/^[a-z]*$/', $param1))
            {
                //纯字母(区域或板块)
                $param1Res = $this->_resolveDistOrRegParam($param1);
                
                if($param1Res['regId'])
                {
                    $filter['filter']['regId'] = $param1Res['regId'];
                    $this->title .= $this->region[$param1Res['regId']]['name'];
                }
                if($param1Res['distId'])
                {
                    $filter['filter']['distId'] = $param1Res['distId'];
                    $this->title .= $this->district[$param1Res['distId']]['name'];
                }
            } else {
                //其他筛选项
                $otherParam = $this->_resolveOtherParam($param1);
            }
        }
        
        if($param2 && empty($otherParam))
        {
            //其他筛选项
            $otherParam = $this->_resolveOtherParam($param2);
        }
        empty($otherParam) || $filter['filter']['otherParam'] = $otherParam;
        
        return $filter;
    }
    
    //解析区域或板块
    private function _resolveDistOrRegParam($param)
    {
        foreach($this->district as $distId=>$value)
        {
            if($param == $value['pinyin'])
            {
                //匹配到区域
                $this->distPinyin = $param;
                $distId = $value['id'];
                $regRes = CityRegion::instance()->getRegionByDistrict($distId, 'id,name,pinyin');
                empty($regRes) || $this->region = $regRes;
                
                return array('distId'=>$distId);
            }
        }
        
        $columns = 'CityRegion.id,CityRegion.name,CityRegion.pinyin,CityRegion.distId';
        $regionGroup = CityRegion::instance()->getDI()->get('modelsManager')->createBuilder()->from('CityRegion')
                ->columns($columns)
                ->leftJoin('CityRegion', 'reg.distId = CityRegion.distId', 'reg')
                ->where("reg.pinyin='{$param}' and reg.cityId={$this->cityId}")
                ->getQuery()
                ->execute()
                ->toArray();
        
        if(empty($regionGroup))
        {
            return array();
        }
        //匹配到板块
        $region = array();
        
        foreach($regionGroup as $v)
        {
            $region[$v['id']] = $v;
            if($param == $v['pinyin'])
            {
                $this->regPinyin = $param;
                $regId = $v['id'];
                $distId = $v['distId'];
            }
        }
        $this->region = $region;
        
        return array('distId'=>$distId, 'regId'=>$regId);
    }
    
    //解析其他筛选项
    private function _resolveOtherParam($param)
    {
        $params = array();
        preg_match_all('/([a-z]+)([0-9]+)/', $param, $result);
        
        if(!empty($result[1]))
        {
            foreach($result[1] as $k=>$v)
            {
                if(isset($this->types[$v]))
                {
                    $params[$v] = $result[2][$k];
                }
            }
        }
        empty($params) || $this->otherParam = $params;
        
        return $params;
    }
}
