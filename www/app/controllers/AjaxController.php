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
    
    public function addfavAction()
    {
        if($this->request->isPost())
        {
            if(empty($this->_userInfo))
            {
                $this->show('JSON', array('status'=>1, 'info'=>'请登陆账号后 再进行收藏'));
            }
            $houseId = $this->request->getPost('houseId', 'int', 0);
            $house = House::findFirst($houseId);
            if(!$house)
            {
                $this->show('JSON', array('status'=>1, 'info'=>'房源不存在'));
            }
            
            $data = array(
                'userId' => $this->_userInfo['id'],
                'houseId' => $houseId
            );
            $addRes = HouseFavorite::instance()->addHouse($data);
            
            $this->show('JSON', $addRes);
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'非法请求'));
        }
    }
    
    public function delfavAction()
    {
        if($this->request->isPost())
        {
            if(empty($this->_userInfo))
            {
                $this->show('JSON', array('status'=>1, 'info'=>'请登陆账号后 再取消收藏'));
            }
            $houseId = $this->request->getPost('houseId', 'int', 0);
            
            $data = array(
                'userId' => $this->_userInfo['id'],
                'houseId' => $houseId
            );
            $delRes = HouseFavorite::instance()->delHouse($data);
            
            $this->show('JSON', $delRes);
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'非法请求'));
        }
    }
    
    public function togglefavAction()
    {
        if(empty($this->_userInfo))
        {
            $this->show('JSON', array('status'=>1, 'info'=>'请先登陆账号'));
        }
        $houseId = $this->request->getPost('houseId', 'int', 0);
        $num = HouseFavorite::count("userId={$this->_userInfo['id']} and houseId={$houseId}");
        if($num > 0)
        {
            $this->delfavAction();
        } else {
            $this->addfavAction();
        }
    }
    
    public function getparkAction()
    {
        $this->show('JSON', array(array('tony')));
    }

}
