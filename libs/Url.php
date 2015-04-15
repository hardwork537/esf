<?php
/**
 * @abstract  url拼接
 * @copyright Sohu-inc Team.
 * @author    Tony (tonyzhao@sohu-inc.com)
 * @date      2014-09-12 09:40:06
 */

class Url
{
    private static $_vip_url = 'vip.esf.focus.cn';
    
    private static $_web_url = 'esf.focus.cn';

    private static $_zu_url = 'zu.focus.cn';
    
    /**
     * 获取城市拼音简写
     * @param int $city_id
     * @return string
     */
    private static function getCityPy($cityId) 
    {
        $cityId = intval($cityId);
        $cityInfo = City::findFirst($cityId);
        if($cityInfo) 
        {
            $cityInfo = $cityInfo->toArray();
            return $cityInfo['pinyinAbbr'];
        } 
        else 
        {
            return '';
        }      
    }
    
    /**
     * 获取经纪人店铺主页
     * @param int    $realtorId  经纪人id
     * @param int    $cityId     城市id
     * @param string $cityPy     城市简拼
     * @return string
     */
    public static function getRealtorShopUrl($realtorId, $cityId, $cityPy = '') 
    {
        $realtorId = intval($realtorId);
        if($realtorId < 1) 
        {
            return '';
        }
        $cityPy || $cityPy = self::getCityPy($cityId);
        if(!$cityPy) 
        {
            return '';
        }
        
        return 'http://' . $cityPy . '.' . self::$_web_url . '/agent/' . $realtorId . '/';
    }
    
    /**
     * 经纪人伪登录url
     * @param string   $realtorAccname  经纪人账号
     * @return string
     */
    public static function getRealtorFakeLoginUrl($realtorAccname) {
        if(!$realtorAccname) {
            return '';
        }
        
        return 'http://' . self::$_vip_url . '/user/PseudoLogin/?action=broker&ent_accname=' . $realtorAccname;
    }
    
    /**
     * 门店伪登录url
     * @param  string   $shopAccname  门店账号
     * @return string
     */
    public static function getShopFakeLoginUrl($shopAccname) 
    {
        if(!$shopAccname) 
        {
            return '';
        }
        
        return 'http://' . self::$_vip_url . '/user/PseudoLogin/?action=business&ent_accname=' . $shopAccname;
    }

    /*
     * 获取房源url
     * */
    public static function getHouseUrl($houseId,$type,$city){
        $city = self::getCityPy($city);
        if($type == "sale"){
            $url = "http://".$city.".".self::$_web_url."/view/".$houseId.".html";
        }else{
            $url = "http://".$city.".".self::$_zu_url."/view/".$houseId.".html";
        }
        return $url;

    }
    
    /**
     * 获取公司链接
     * @param int    $comId  公司id
     * @param int    $cityId     城市id
     * @param string $cityPy     城市简拼
     * @return string
     */
    public static function getCompanyUrl($comId, $cityId, $cityPy = '') 
    {
        $comId = intval($comId);
        if($comId < 1) 
        {
            return '';
        }
        $cityPy || $cityPy = self::getCityPy($cityId);
        if(!$cityPy) 
        {
            return '';
        }
        
        return 'http://' . $cityPy . '.' . self::$_web_url . '/mall/' . $comId . '/shou/';
    }
    
    /**
     * 获取门店链接
     * @param int    $shopId     门店id
     * @param int    $cityId     城市id
     * @param string $cityPy     城市简拼
     * @return string
     */
    public static function getShopUrl($shopId, $cityId, $cityPy = '') 
    {
        $shopId = intval($shopId);
        if($shopId < 1) 
        {
            return '';
        }
        $cityPy || $cityPy = self::getCityPy($cityId);
        if(!$cityPy) 
        {
            return '';
        }
        
        return 'http://' . $cityPy . '.' . self::$_web_url . '/store/' . $shopId . '/shou/';
    }
    
    /**
     * 获取门店链接
     * @param int    $realId     门店id
     * @param int    $cityId     城市id
     * @param string $cityPy     城市简拼
     * @return string
     */
    public static function getRealtorUrl($realId, $cityId, $cityPy = '') 
    {
        $realId = intval($realId);
        if($realId < 1) 
        {
            return '';
        }
        $cityPy || $cityPy = self::getCityPy($cityId);
        if(!$cityPy) 
        {
            return '';
        }
        
        return 'http://' . $cityPy . '.' . self::$_web_url . '/shop/' . $realId . '/';
    }
    
    /**
     * 获取门店链接
     * @param int    $parkId     小区id
     * @param int    $cityId     城市id
     * @param string $cityPy     城市简拼
     * @return string
     */
    public static function getParkUrl($parkId, $cityId, $cityPy = '') 
    {
        $parkId = intval($parkId);
        if($parkId < 1) 
        {
            return '';
        }
        $cityPy || $cityPy = self::getCityPy($cityId);
        if(!$cityPy) 
        {
            return '';
        }
        
        return 'http://' . $cityPy . '.' . self::$_web_url . '/xiaoqu/' . $parkId . '/';
    }
    
    /*
     * @desc 获取城区板块路径
     * */
    public static function getDistUrl($pingying, $cityId, $type="sale"){

        if(!$pingying)
        {
            return '';
        }
        $cityPy = self::getCityPy($cityId);
        if(!$cityPy)
        {
            return '';
        }

        return 'http://' . $cityPy . '.' . self::$_web_url . '/'.$type.'/' . $pingying . '/';
    }

    public static function getVipUrl($url){
        return 'http://' . self::$_vip_url .'/'. $url;
    }
}