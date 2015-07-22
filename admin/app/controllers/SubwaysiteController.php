<?php

class SubwaysiteController extends ControllerBase
{
    public function listAction()
    {
        $cityId         = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys']  = City::getOptions();

        $data['metroId'] = $this->request->get("metroId", 'int', 0);
        $data['lines']   = Metro::instance()->getLinesForOption($cityId);
        $where           = "cityId=$cityId and status=".Metro::STATUS_ENABLED;
        $data['metroId'] > 0 && $where .= " and metroId={$data['metroId']}";
        $condition      = array(
            "conditions" => $where,
            "order"      => "weight asc, id asc",
            "limit" => $this->_pagesize,
            "offset" => $this->_offset
        );
        $pageCount      = MetroStation::count($where);
        $data['page']   = Page::create($pageCount, $this->_pagesize);
        $data['lists']  = MetroStation::find($condition)->toArray();

        $this->show(null, $data);
    }

    public function addAction()
    {
        $cityId         = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys']  = City::getOptions();

        if($this->request->isPost())
        {
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            $addRes = MetroStation::instance()->add($checkRes['params']);
            
            $this->show("JSON", $addRes);
        }
        $data['metroId'] = $this->request->get("metroId", int, 0);
        $data['lines']   = Metro::instance()->getLinesForOption($cityId);

        $this->show(null, $data);
    }

    public function editAction($id = 0)
    {
        $cityId         = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys']  = City::getOptions();

        if($this->request->isPost())
        {
            //提交修改
            $id        = intval($this->request->getPost("msId", "int"));
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }

            $rs = MetroStation::instance()->edit($id, $checkRes['params']);
            
            $this->show("JSON", $rs);
        }
        //进入修改页面
        $id                = intval($id);
        $where[]           = "id=$id";
        $data['site_info'] = MetroStation::findFirst($where);
        if($data['site_info'])
        {
            $data['site_info'] = $data['site_info']->toArray();
        }
        else
        {
            return $this->response->redirect('subwaysite/list');
        }
        $data['msId']    = $id;
        $data['metroId'] = $data['site_info']['metroId'];
        $data['lines']   = Metro::instance()->getLinesForOption($data['cityId']);

        $this->show("add", $data);
    }

    /**
     * 添加、修改时 参数验证
     */
    private function _filterParams()
    {
        $params = array();
        //验证 轨道站点
        $msName = trim($this->request->getPost('msName', 'string', ''));
        if(!$msName)
        {
            return array( 'status' => 1, 'info' => '轨道站点不能为空!' );
        }
        $params['msName'] = $msName;

        //验证 X坐标
        $msX = trim($this->request->getPost('x', 'string', ''));

        if(!$msX)
        {
            return array( 'status' => 1, 'info' => 'X精度坐标不能为空!' );
        }
        $params['msX'] = $msX;

        //验证 Y坐标
        $msY = trim($this->request->getPost('y', 'string', ''));
        if(!$msY)
        {
            return array( 'status' => 1, 'info' => 'Y精度坐标不能为空!' );
        }
        $params['msY'] = $msY;

        //验证城市
        $citys  = City::getOptions();
        $cityId = intval($this->request->getPost("cityId", 'int'));
        if(!array_key_exists($cityId, $citys))
        {
            return array( 'status' => 1, 'info' => '城市无效!' );
        }
        $params['cityId'] = $cityId;

        //验证轨道线路
        $metroId = intval($this->request->getPost("metroId", 'int'));
        if($metroId < 1)
        {
            return array( 'status' => 1, 'info' => '轨道线路无效!' );
        }
        $params['metroId'] = $metroId;
        $params['weight']  = abs($this->request->getPost("weight", 'int'));

        return array( 'status' => 0, 'params' => $params );
    }

}
