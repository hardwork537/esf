<?php

class SellController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/weituo.css?v=20150810001');
        $this->_setTitle('房易买-我要卖房');
        
        $this->show(null, $data);
    }
    
    public function addAction()
    {
        if(empty($this->_userInfo))
        {
            $this->response->redirect("/login/", true)->sendHeaders();
            exit();
        }
        $data = array();
        $data['cssList'] = array('css/fabu.css?v=20150730002');
        $data['baseUrl'] = WWW_BASE_URL;
        $data['userId'] = intval($this->_userInfo['id']);
        //$data['citys'] = City::instance()->getOptions();;
        $data['citys'] = array(2=>'上海');
        $data['default'] = array('cityId'=>2, 'name'=>'上海');
        
        $this->_setTitle('房易买-我要卖房');
        
        $this->show(null, $data);
    }
    
    public function addsaveAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_checkParams();
            if(0 !== $checkRes['status'])
            {
                echo "<script>alert('".$checkRes['info']."');location.href='/sell/add/';</script>";
                exit();
            }
            $addRes = House::instance()->addWeituoHouse($checkRes['params']);
            
            if($addRes['status'] !== 0)
            {
                echo "<script>alert('".($checkRes['info'] ? $checkRes['info'] : '发布失败') ."');location.href='/sell/add/';</script>";
                exit();
            } else {
                echo "<script>alert('发布成功');location.href='/buy/';</script>";
                exit();
            }
        } else {
            echo "<script>alert('无效请求');location.href='/sell/add/';</script>";
            exit();
        }
    }
    
    private function _checkParams()
    {
        if(empty($this->_userInfo))
        {
            return array('status' => 1, 'info' => '请先进行登陆');
        }
        //验证城市
        $cityId = $this->request->getPost('cityId', 'int', 0);
        $where = "id={$cityId} and status=".City::STATUS_ENABLED;
        $cityNum = City::count($where);
        if($cityNum == 0)
            return array('status' => 1, 'info' => '城市无效');
        
        //验证小区
        $parkName = trim($this->request->getPost('parkName', 'string', ''));
        $where = "name='{$parkName}' and cityId={$cityId}";
        $park = Park::findFirst($where, 0)->toArray();
        if(empty($park))
            return array('status' => 1, 'info' => '该城市不存在该小区');

        //室
        $bedRoom = $this->request->getPost('bedRoom', 'int', 0);
        //厅
        $livingRoom = $this->request->getPost('livingRoom', 'int', 0);
        //卫
        $bathRoom = $this->request->getPost('bathRoom', 'int', 0);
        //建筑面积
        $bA = $this->request->getPost('bA', 'int', 0);
        if($bA < 1)
            return array('status' => 1, 'info' => '建筑面积不能为空');
        //代理人
        $agent = trim($this->request->getPost('contactName', 'string', ''));
        //代理人联系方式
        $agentPhone = trim($this->request->getPost('contactPhone', 'string', ''));
        //价格
        $price = trim($this->request->getPost('price', 'string', ''));
        //图片
        $img = $_POST['shinei'];
        if(!empty($img))
        {
            $images = array();
            foreach($img as $v)
            {
                list($imgId, $imgExt) = explode('.', $v);
                $images[] = array('id'=>$imgId, 'ext'=>$imgExt);
            }
        }
             
        $params = array(
            'cityId' => $cityId,
            'distId' => $park['distId'],
            'regId' => $park['regId'],
            'userId' => $this->_userInfo['id'],
            'parkId' => $park['id'],
            'bedRoom' => $bedRoom,
            'livingRoom' => $livingRoom,
            'bathRoom' => $bathRoom,
            'bA' => $bA,
            'agent' => $agent,
            'agentPhone' => $agentPhone,
            'price' => $price*10000,
            'images' => $images
        );
        
        return array('status' => 0, 'params' => $params);
    }
}
