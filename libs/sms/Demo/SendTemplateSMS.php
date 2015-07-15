<?php

include_once (dirname(__FILE__) . "/../SDK/CCPRestSDK.php");

class sendSms
{
    /*
     *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
     *
     *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
     *  that can be found in the LICENSE file in the root of the web site.
     *
     *   http://www.yuntongxun.com
     *
     *  An additional intellectual property rights grant can be found
     *  in the file PATENTS.  All contributing project authors may
     *  be found in the AUTHORS file in the root of the source tree.
     */

    //主帐号
    static public $accountSid = SMS_ACCOUNT_SID;
    //主帐号Token
    static public $accountToken = SMS_ACCOUNT_TOKEN;
    //应用Id
    static public $appId = SMS_APPID;
    //请求地址，格式如下，不需要写https://
    static public $serverIP = SMS_BASE_URL;
    //请求端口 
    static public $serverPort = SMS_BASE_PORT;
    //REST版本号
    static public $softVersion = SMS_VERSION;

    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
    public static function sendTemplateSMS($to, $datas, $tempId)
    {
        // 初始化REST SDK
        $rest = new REST(self::$serverIP, self::$serverPort, self::$softVersion);
        $rest->setAccount(self::$accountSid, self::$accountToken);
        $rest->setAppId(self::$appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);

        if($result == NULL)
        {
            return false;
        }
        if($result->statusCode != 0)
        {
            return false;
        } else
        {
            return true;
        }
    }

}

?>
