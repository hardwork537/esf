<?php

use SohuCS\Common\Enum\Region;

/**
 * @abstract  提供ajax调用
 */
define("NO_NEED_LOGIN", true);
define("NO_NEED_POWER", true);
header("Content-type: text/html; charset=utf-8");

class AjaxController extends ControllerBase
{
    /*
     * @desc 小区联想
     * */

    public function getParkNameAction()
    {
        $inputInfo = $this->request->getPost('keyword', string);
        $cityId = $this->request->getPost('cityId', int);
        $nums = $this->request->getPost('nums', int);
        $nums || $nums = 10;
        $list = [];
        $inputInfo = trim($inputInfo);
        $con = "(name like '{$inputInfo}%' or pinyin like '{$inputInfo}%' or pinyinAbbr like '{$inputInfo}%')";
        if($cityId > 0)
        {
            $con .= " and cityId={$cityId}";
        }

        $rs = Park::find(array(
                $con,
                "limit" => $nums
            ))->toArray();
        foreach($rs as $k => $v)
        {
            $list[] = array("id" => $v['id'], "name" => $v['name']);
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    public function uploadImageAction()
    {
        var_dump($_REQUEST);
        var_dump($_FILES);
    }
    public function ueditUploadImageAction()
    {

        $action = $_GET['action'];
        if($action == 'config')
        {
            $info = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(APPROOT . "config/uedit.config.json")), true);
        } else if($action == 'uploadimage')
        {
            $this->_userInfo = Cookie::get(LOGIN_KEY);
            $imageRes = Scs::Instance()->uploadImage('upfile', Image::FROM_ADMIN, $this->_userInfo["id"]);

            if(isset($imageRes['error']))
            {
                $info = array(
                    "state" => $imageRes['error'],
                    "url" => "",
                    "title" => "",
                    "original" => "",
                    "type" => "",
                    "size" => "0"
                );
            } else
            {
                $info = array(
                    "state" => "SUCCESS",
                    "url" => $imageRes['upload_url'],
                    "title" => "",
                    "original" => "",
                    "type" => $imageRes['ext'],
                    "size" => "1"
                );
            }
        }

        echo json_encode($info);
    }

    public function getDistByCityIdAction($cityId = 1)
    {
        $list = [];
        $cityId = intval($cityId);
        $rs = CityDistrict::find("cityId=$cityId and status=" . CityDistrict::STATUS_ENABLED, 0)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据$distId获取对应的板块
     * @param number $distId
     */
    public function getRegByDistIdAction($distId = 1)
    {
        $list = [];
        $distId = intval($distId);
        $rs = CityRegion::find("distId=$distId and status=" . CityRegion::STATUS_ON, 0)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }
    
    /**
     * 修改密码
     */
    public function modifypwdAction()
    {
        $userId = $this->request->getPost('userId', 'int', 0);
        $oldpassword = $this->request->getPost('oldpassword', 'string', '');
        $password = $this->request->getPost('password', 'string', '');
        $repassword = $this->request->getPost('repassword', 'string', '');
        
        if($password != $repassword)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'新密码两次输入不一致'));
        }
        if(!preg_match('/^\S{6,}$/', $password))
        {
            $this->show('JSON', array('status'=>1, 'info'=>'密码只能为不少于六位数的数字、字母组合'));
        }
        $user = AdminUser::findFirst($userId);
        if(!$user)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'用户不存在'));
        }
        $pwd = $this->_getPasswordStr($oldpassword);
        if($pwd != $user->password)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'旧密码错误'));
        }
        
        $newPwd = $this->_getPasswordStr($password);
        $updateRes = $user->update(array('password'=>$newPwd));
        
        if($updateRes)
        {
            $this->show('JSON', array('status'=>0, 'info'=>'修改密码成功'));
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'修改密码失败'));
        }
    }

}
