<?php

class Sms
{
    const BASE_URL = SMS_BASE_URL;
    
    public static function sendmessage($phone)
    {
        $timeNow = date('YmdHis');
        $sig = strtoupper(md5(SMS_ACCOUNT_SID .SMS_AUTH_TOKEN . $timeNow));
        $url = self::BASE_URL.'/'.SMS_VERSION . '/Accounts/'.SMS_ACCOUNT_SID.'/SMS/TemplateSMS?sig='.$sig;
        
        $data = array(
            'to' => $phone,
            'appId' => SMS_APPID,
            'templateId' => SMS_TEMPLATEID,
            'datas' => 'tonytest'
        );
        $authorization = base64_encode(SMS_ACCOUNT_SID . ':' . $timeNow);
        $header = array(
            'Authorization' => $authorization,
            'Accept' => 'application/json',
            'Content-Type' => 'Content-Type',
            'Content-Length' => 123
        );
        //var_dump($data,$sig,$authorization);
        $res = Curl::GetResult($url, 'post', $data, $header);
        var_dump($res);
    }
}
