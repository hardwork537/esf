<?php

class CPark
{

    /**
     * @ 周边配套子页的数据接口
     * @param int $intParkId 
     * @param int $dataType 1:全部的配套数据,json格式 2:第一次加载页面的配套数据,数组
     * @return array|string
     * 
     */
    static function getParkAssort($intParkId, $dataType = 1)
    {
        if(!($intParkId && $dataType))
            return array();
        //先处理合并数据
        $mParkExt = new ParkExt();
        $name = '周边配套';
        $arrAssortInfo = $mParkExt->getParkExtByParkId($intParkId, $name);
        if(!$arrAssortInfo)
            return array();;
        $strAssortInfo = $arrAssortInfo[0]['value'];
        unset($arrAssortInfo);
        
        $arrParkAssort = json_decode($strAssortInfo, true);

        if(!$arrParkAssort)
            return array();
        unset($strAssortInfo);
        $arrAssort = array();
        $arrAssortNew = array();
        foreach($arrParkAssort as $key => $value)
        {
            foreach($value as $k => $v)
            {
                foreach($v as $ak => $av)
                {
                    $arrAssort[] = array('type' => $k, 'list' => $av);
                    $arrAssortNew[$k][] = $av;
                }
            }
        }
        unset($arrParkAssort);
        ksort($arrAssortNew);
        if($dataType == 1)
        {
            $strAssort = json_encode($arrAssort);
            unset($arrAssort);
            return $strAssort;
        } elseif($dataType == 2)
        {
            return $arrAssortNew;
        }
    }

}
