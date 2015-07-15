<?php

class Sms
{

    private static $_timeout = 5; //5分钟
    private static $_templateId = SMS_TEMPLATE_ID;

    public static function sendmessage($phone, $message)
    {
        include_once dirname(__FILE__) . '/sms/Demo/SendTemplateSMS.php';
        
        if(!preg_match('/^1[0-9]{10}$/', $phone))
        {
            return array('success'=>false, 'errmsg'=>'手机格式错误');
        }
        $message = trim($message);
        if(!$message)
        {
            return array('success'=>false, 'errmsg'=>'内容不能为空');
        }
        
        try
        {
            $datas = array(
                $message,
                self::$_timeout
            );
            $res = sendSms::sendTemplateSMS($phone, $datas, self::$_templateId);

            return $res ? array('success'=>true) : array('success'=>false, 'errmsg'=>'发送失败');
        } catch(Exception $exc)
        {
            return array('success'=>false, 'errmsg'=>'系统异常');
        }
    }

}
