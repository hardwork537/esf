<?php

/**
 * @abstract 搜狐企业数据同步接口类
 * @author tonyzhao <tonyzhao@sohu-inc.com>
 * @date 2011-11-10
 * @lastModify By Yuntaohu 2011-11-10
 *
 */
class SohuErp {

    //程序异常错误描述
    static $mErr = '';
    //路由接口地址，因考虑服务器IP授权的问题，统一由一台路由器来请求数据
    static $mInterface = 'http://10.11.162.34/sohuerp.php';
    static $mClsMail;
    static $mMailSender = array(
        'yuntaohu@sohu-inc.com',
        'billwang@sohu-inc.com',
        'tonyzhao@sohu-inc.com',
        'yifengcao@sohu-inc.com'
    );

    public function __construct() {
        
    }

    public function __destruct() {
        
    }

    /**
     * 实例Mail接口
     *
     * @return object
     */
    private function Mail() {
        if (is_object(self::$mClsMail)) {
            return self::$mClsMail;
        }
        return self::$mClsMail = new Email("mail.sohu.net", "services@51f.com", "abcd1234");
    }

    /**
     * 设置错误编码
     *
     * @param int $code
     * @return string
     */
    private function setError($code) {
        return $code;
    }

    /**
     * 同步公司数据
     *
     * @param array $arrRequest
     * @return object
     */
    public static function SendCompanyToErp(array $arrRequest) {
        $newArrRequest = array();
        $columns = self::companyColumnsMap();
        foreach($arrRequest as $column=>$value)
        {
            $newColumn = $columns[$column];
            if(empty($newColumn))
            {
                continue;
            }
            if(is_array($newColumn))
            {
                list($col, $values) = each($newColumn);
                $newArrRequest[$col] = $values[$value];
            }
            else
            {
                $newArrRequest[$newColumn] = $value;
            }
        }
        
        $arrBackData = Curl::SendReq(self::$mInterface . "?act=company", $newArrRequest);
        //路由不通错误
        if ($arrBackData['httpCode'] != 200) {
            foreach (self::$mMailSender as $mailer) {
                //self::Mail()->send($mailer, "搜狐焦点二手房<services@51f.com>", "SOHU_ERP路由故障", implode(",", $newArrRequest));
            }
            return self::setError(500);
        }
        //请求失败错误
        if ($arrBackData['data'] != '001') {
            foreach (self::$mMailSender as $mailer) {
                //self::Mail()->send($mailer, "搜狐焦点二手房<services@51f.com>", "SOHU_ERP公司同步失败", implode(",", $newArrRequest));
            }
            return self::setError(400);
        }
        //请求成功返回
        return self::setError(200);
    }
    
    private static function companyColumnsMap()
    {
        return array(
            'id'         => 'company_id',
            'name'       => 'company_name',
            'abbr'       => 'company_name_abbr',
            'cityId'     => 'city_id',
            'address'    => 'address',
            'tel'        => 'tel',
            'fax'        => 'fax',
            'logoId'     => 'logo_id',
            'logoExt'    => 'logo_ext',
            'account'    => 'company_accname',
            'saleId'     => 'xiaoshou_id',
            'CSId'       => 'kefu_id',
            'status'     => array('status' => array(Company::STATUS_ENABLED=>1, Company::STATUS_WASTED=>2, Company::STATUS_DISABLED=>3)),    // 1. 有效 2. 删除 3. 无效
            'isCheck'    => 'is_check', //公司及下属部门管理经纪人 1. 有 2. 无
            'pinyin'     => 'company_pinyin',
            'pinyinAbbr' => 'company_pinyin_abbr',
            'isDict'     => 'is_dict',          // 0. 字典 1. 公司
            'isAdmin'    => 'manager_broker', //
            'isCrmVerify'=> 'is_crm_verify',  //是否CRM对接表示，默认0
            'customTag'  => 'custom_title',
            'isShowTag'  => 'custom_title_check',
            'isOpenShop' => 'open_shop', //公司是否开通协议签署网店 1. 是 2. 否
        );
    }

    /**
     * 同步经纪人数据
     *
     * @param array $arrRequest
     * @return object
     */
    public static function SendBrokerToErp(array $arrRequest) {
        $newArrRequest = array();
        $columns = self::realtorColumnsMap();
        foreach($arrRequest as $column=>$value)
        {
            $newColumn = $columns[$column];
            if(empty($newColumn))
            {
                continue;
            }
            if(is_array($newColumn))
            {
                list($col, $values) = each($newColumn);
                $newArrRequest[$col] = $values[$value];
            }
            else
            {
                $newArrRequest[$newColumn] = $value;
            }
        }
        
        $arrBackData = Curl::SendReq(self::$mInterface . "?act=broker", $newArrRequest); 
        //路由不通错误
        if ($arrBackData['httpCode'] != 200) {
            foreach (self::$mMailSender as $mailer) {
                //self::Mail()->send($mailer, "搜狐焦点二手房<services@51f.com>", "SOHU_ERP路由故障", implode(",", $newArrRequest));
            }
            return self::setError(500);
        }
        //请求失败错误
        if ($arrBackData['data'] != '001') {
            foreach (self::$mMailSender as $mailer) {
                //self::Mail()->send($mailer, "搜狐焦点二手房<services@51f.com>", "SOHU_ERP经纪人同步失败", implode(",", $newArrRequest));
            }
            return self::setError(400);
        }
        //请求成功返回
        return self::setError(200);
    }
    
    private static function realtorColumnsMap()
    {
        return array(
            'id'         => 'broker_id',
            'areaId'     => 'sector_id',
            'shopId'     => 'agent_id',
            'comId'      => 'company_id',
            'regId'      => 'district_id',
            'distId'     => 'hot_area_id',
            'cityId'     => 'city_id',
            'name'       => 'broker_name',
            'mobile'     => 'broker_tel',
            'logoId'     => 'logo_id',
            'logoExt'    => 'logoExt',
            'cardId'     => 'card_id',
            'cardExt'    => 'card_ext',
            'desc'       => 'broker_describe',
            'status'     => 'broker_status',
            'type'       => 'broker_type',
            'create'     => 'createtime',
            'passport'   => 'passport',
            'mail'       => 'broker_mail',
            'saleId'     => 'xiaoshou_id',
            'CSId'       => 'kefu_id',
            'denyId'     => 'deny_id',
            'validation' => 'renzhengtime',
            'QQ'         => 'broker_qq',
            'tel'        => 'broker_phone',
            'telExt'     => 'broker_phone_ext',
            'rank'       => 'weight',
        );
    }

}

?>