<?php

/**
 * @abstract  城区字典
 */
class DistrictController extends ControllerBase
{

    public function listAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys'] = City::getOptions();

        $con = "cityId=$cityId";
        $pageCount = CityDistrict::count($con);

        $data['lists'] = CityDistrict::find(
                [
                    "conditions" => "cityId=$cityId",
                    "order" => "weight asc,id asc",
                    "limit" => [
                        "number" => $this->_pagesize,
                        "offset" => $this->_offset
                    ]
            ])->toArray();

        $data['params'] = $this->request->get();
        $data['page'] = Page::create($pageCount, $this->_pagesize);

        $this->show(null, $data);
    }

    public function addAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['citys'] = City::getOptions();

        if($this->request->isPost())
        {
            $rs = CityDistrict::instance()->add(
                array(
                    'cityId' => $cityId,
                    'name' => $this->request->getPost("name", 'string'),
                    'abbr' => $this->request->getPost("abbr", 'string'),
                    'pinyin' => $this->request->getPost("pinyin", 'string'),
                    'X' => $this->request->getPost("x", 'float'),
                    'Y' => $this->request->getPost("y", 'float'),
                    'status' => $this->request->getPost("status", 'int'),
                    'weight' => $this->request->getPost("weight", 'int')
            ));

            if($rs)
            {
                $this->show("JSON");
            } else
            {
                $this->show('ERROR');
            }
        }
        $data['cityId'] = $cityId;
        $data['distStatus'] = CityDistrict::getStatus();
        
        $this->show("add", $data);
    }

    public function editAction($distId = 0)
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['citys'] = City::getOptions();

        if($this->request->isPost())
        {
            $distId = $this->request->getPost("distId", 'int');
            $rs = CityDistrict::instance()->edit($distId, array(
                'cityId' => $cityId,
                'name' => $this->request->getPost("name", 'string'),
                'abbr' => $this->request->getPost("abbr", 'string'),
                'pinyin' => $this->request->getPost("pinyin", 'string'),
                'X' => $this->request->getPost("x", 'float'),
                'Y' => $this->request->getPost("y", 'float'),
                'status' => $this->request->getPost("status", 'int'),
                'weight' => $this->request->getPost("weight", 'int')
            ));

            if($rs)
            {
                $this->show("JSON");
            } else
            {
                $this->show('ERROR');
            }
        }
        $distId = intval($distId);
        $data['info'] = CityDistrict::findFirst($distId);
        $data['distStatus'] = CityDistrict::getStatus();
        $data['cityId'] = $cityId;
        
        $this->show("add", $data);
    }

}
