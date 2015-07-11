<?php

class Data
{
    //短信验证码保留时间 5分钟
    public static $codeLisftime = 300;

    public static function getSendMessageMemkey($phone)
    {
        return 'esf_register_message_send_' . $phone;
    }
}
