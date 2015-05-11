<?php

class SubwaylineController extends ControllerBase
{

    public function listAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys'] = City::getOptions();

        $where = "cityId=$cityId and status=" . Metro::STATUS_ENABLED;

        $condition = array(
            "conditions" => $where,
            "order" => "weight asc, id asc",
            "limit" => array(
                "number" => $this->_pagesize,
                "offset" => $this->_offset
            )
        );
        $pageCount = Metro::count($where);
        $data['page'] = Page::create($pageCount, $this->_pagesize);
        $data['lists'] = Metro::find($condition)->toArray();

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
            $addRes = Metro::instance()->add($checkRes['params']);
            $this->show("JSON", $addRes);
        }
        $this->show("JSON");
    }

    public function editAction($id = 0)
    {
        if($this->request->isPost())
        {
            $id = intval($this->request->getPost("metroId", "int"));
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            $rs = Metro::instance()->edit($id, $checkRes['params']);

            $this->show("JSON", $rs);
        }
        $where[] = "id='$id'";
        $this->_json["data"] = Metro::findFirst($where)->toArray();
        
        $this->show("JSON");
    }

    /**
     * 添加、修改时 参数验证
     */
    private function _filterParams()
    {
        $params = array();
        $metroName = trim($this->request->getPost('metroName', 'string', ''));
        if(!$metroName)
        {
            return array('status' => 1, 'info' => '轨道线路不能为空!');
        }
        $params['metroName'] = $metroName;

        $citys = City::getOptions();
        $cityId = intval($this->request->getPost("cityId", 'int'));
        if(!array_key_exists($cityId, $citys))
        {
            return array('status' => 1, 'info' => '城市无效!');
        }
        $params['cityId'] = $cityId;
        $params['weight'] = abs($this->request->getPost("weight", 'int'));

        return array('status' => 0, 'params' => $params);
    }

}
