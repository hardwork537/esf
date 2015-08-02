<?php

class HotsearchController extends ControllerBase
{

    public function listAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys'] = City::getOptions();

        $where = "cityId=$cityId";

        $condition = array(
            "conditions" => $where,
            "order" => "weight asc, id desc",
            "limit" => $this->_pagesize,
            "offset" => $this->_offset
        );
        $pageCount = HotSearch::count($where);
        $data['page'] = Page::create($pageCount, $this->_pagesize);
        $data['lists'] = HotSearch::find($condition, 0)->toArray();
        
        $data['baseUrl'] = substr(WWW_BASE_URL, 0, -1);
        $data['statuses'] = HotSearch::getStatus(null);

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
            $addRes = HotSearch::instance()->add($checkRes['params']);
            $this->show("JSON", $addRes);
        }
        $this->show("JSON");
    }

    public function editAction($id = 0)
    {
        if($this->request->isPost())
        {
            $id = intval($this->request->getPost("id", "int"));
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            $rs = HotSearch::instance()->edit($id, $checkRes['params']);

            $this->show("JSON", $rs);
        }
        $where[] = "id=$id";
        $this->_json["data"] = HotSearch::findFirst($where, 0)->toArray();
        
        $this->show("JSON");
    }

    /**
     * 添加、修改时 参数验证
     */
    private function _filterParams()
    {
        $params = array();
        $name = trim($this->request->getPost('name', 'string', ''));
        if(!$name)
        {
            return array('status' => 1, 'info' => '热门搜索名称不能为空!');
        }
        $params['name'] = $name;

        $citys = City::getOptions();
        $cityId = intval($this->request->getPost("cityId", 'int'));
        if(!array_key_exists($cityId, $citys))
        {
            return array('status' => 1, 'info' => '城市无效!');
        }
        $params['cityId'] = $cityId;
        $params['weight'] = abs($this->request->getPost("weight", 'int'));
        
        $url = trim($this->request->getPost('url', 'string', ''));
        if(!$url)
        {
            return array('status' => 1, 'info' => 'url不能为空!');
        }
        $params['url'] = $url;
        
        $status = $this->request->getPost('status', 'int', '0');
        $allStatus = HotSearch::getStatus(null);
        if(!array_key_exists($status, $allStatus))
        {
            return array('status' => 1, 'info' => '状态无效!');
        }        
        $params['status'] = $status;

        return array('status' => 0, 'params' => $params);
    }
    
    public function delAction($id = 0)
    {
        $id      = intval($id);
        $delRes = HotSearch::instance()->del($id);

        $this->show("JSON", $del_ret);
    }

}
