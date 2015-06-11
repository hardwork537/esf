<?php

class HousetagController extends ControllerBase
{

    public function listAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys'] = City::getOptions();

        $where = "cityId=$cityId";

        $condition = array(
            "conditions" => $where,
            "order" => "id desc",
            "limit" => array(
                "number" => $this->_pagesize,
                "offset" => $this->_offset
            )
        );
        $pageCount = HouseTag::count($where);
        $data['page'] = Page::create($pageCount, $this->_pagesize);
        $data['lists'] = HouseTag::find($condition)->toArray();

        $this->show(null, $data);
    }

    public function addAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            $addRes = HouseTag::instance()->add($checkRes['params']);
            $this->show("JSON", $addRes);
        }
        $this->show("JSON");
    }

    /**
     * 添加、修改时 参数验证
     */
    private function _filterParams()
    {
        $params = array();
        $tagName = trim($this->request->getPost('tagName', 'string', ''));
        if(!$tagName)
        {
            return array('status' => 1, 'info' => '标签名称不能为空!');
        }
        $params['tagName'] = $tagName;

        $citys = City::getOptions();
        $cityId = intval($this->request->getPost("cityId", 'int'));
        if(!array_key_exists($cityId, $citys))
        {
            return array('status' => 1, 'info' => '城市无效!');
        }
        $params['cityId'] = $cityId;

        return array('status' => 0, 'params' => $params);
    }
    
    public function delAction($tagId)
    {
        $tagId = intval($tagId);
        $delRes = HouseTag::instance()->del($tagId);
        
        $this->show('JSON', $delRes);
    }

}
