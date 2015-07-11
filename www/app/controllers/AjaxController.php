<?php

class AjaxController extends ControllerBase
{
    /**
     * 发送短信或语音消息
     */
    public function sendmessageAction()
    {   
        $phone = $this->request->getPost('phone', 'string', '');
        if(!preg_match("/^1\d{10}$/", $phone))
        {
            $this->show("JSON", array('status' => 1, 'info' => '手机格式错误'));
        }

        $number = mt_rand(1000, 9999);
        $memkey = Data::getSendMessageMemkey($phone);

        $res = Mem::Instance()->Set($memkey, $number, Data::$codeLisftime);
        if(!$res)
        {
            $this->show("JSON", array('status' => 1, 'info' => '网络错误，请重试'));
        }

        $codeNum = join(",", str_split($number)) . ",";
        $message = "房易买验证码: {$codeNum}";
        //$sendRes = OTAApi::sendMessge($mobile, $message, OTAApi::MESSAGE_VOICE);
        
        $status = $sendRes['success'] ? 0 : 1;
        $status = 0;
        $this->show('JSON', array('status' => $status));
    }

}
