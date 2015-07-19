<?php

class SellController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/weituo.css');
        
        $this->show(null, $data);
    }
    
    public function addAction()
    {
        $data = array();
        $data['cssList'] = array('css/fabu.css');
        $data['baseUrl'] = WWW_BASE_URL;
        $data['userId'] = intval($this->_userInfo['id']);
        $data['citys'] = City::instance()->getOptions();;
        
        $this->show(null, $data);
    }
    
    public function addsaveAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_checkParams();
            if(0 !== $checkRes['status'])
            {
                $this->show('JSON', $checkRes);
            }
            $addRes = House::instance()->addWeituoHouse($checkRes['params']);
            
            $this->show('JSON', $addRes);
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'无效请求'));
        }
    }
    
    private function _checkParams()
    {
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
            'userId' => $this->_userInfo['id'],
            'parkId' => $park['id'],
            'bedRoom' => $bedRoom,
            'livingRoom' => $livingRoom,
            'bathRoom' => $bathRoom,
            'bA' => $bA*10000,
            'agent' => $agent,
            'agentPhone' => $agentPhone,
            'price' => $price,
            'images' => $images
        );
        
        return array('status' => 0, 'params' => $params);
    }
}
