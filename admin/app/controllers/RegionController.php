<?php

/**
 * @abstract  板块字典
 */
class RegionController extends ControllerBase
{

    public $distStatus = [
        "1" => "启用",
        "0" => "未启用",
        "-1" => "废弃"
    ];

    public function listAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['citys'] = City::getOptions();
        $data['cityId'] = $cityId;

        $distId = $this->request->get("distId", int, 0);
        $con = "cityId=$cityId";
        $regIds = array();
        if($distId)
        {
            $con .= " and distId=$distId";
        }

        $pageCount = CityRegion::count($con);

        $data['lists'] = CityRegion::find(
                [
                    "conditions" => $con,
                    "order" => " weight asc,id asc",
                    "limit" => [
                        "number" => $this->_pagesize,
                        "offset" => $this->_offset
                    ]
            ])->toArray();
        foreach($data['lists'] as &$v)
        {
            if($rs = CityDistrict::findFirst($v['distId']))
            {
                $v['distName'] = $rs->name;
            }
        }

        $data['params'] = $this->request->get();
        $data['page'] = Page::create($pageCount, $this->_pagesize);

        $this->show(null, $data);
    }

    public function addAction()
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys'] = City::getOptions();

        if($this->request->isPost())
        {

            $rs = CityRegion::instance()->add(
                array(
                    'cityId' => $cityId,
                    'distId' => $this->request->getPost("distId", 'int'),
                    'name' => $this->request->getPost("name", 'string'),
                    'abbr' => $this->request->getPost("abbr", 'string'),
                    'pinyin' => $this->request->getPost("pinyin", 'string'),
                    'pinyinAbbr' => $this->request->getPost("pinyinAbbr", 'string'),
                    'pinyinFirst' => $this->request->getPost("pinyinFirst", 'string'),
                    'X' => $this->request->getPost("x", 'string'),
                    'Y' => $this->request->getPost("y", 'string'),
                    'status' => $this->request->getPost("status", 'int'),
                    'weight' => $this->request->getPost("weight", 'int')
                )
            );
            if($rs)
            {
                $this->show("JSON");
            }
            $this->show("ERROR");
        }
        $data['distStatus'] = CityRegion::getStatus();
        
        $this->show("add", $data);
    }

    public function editAction($regId = 0)
    {
        $cityId = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys'] = City::getOptions();

        if($this->request->isPost())
        {
            $regId = $this->request->getPost("id", 'int');
            $rs = CityRegion::instance()->edit($regId, array(
                'cityId' => $cityId,
                'distId' => $this->request->getPost("distId", 'int'),
                'name' => $this->request->getPost("name", 'string'),
                'abbr' => $this->request->getPost("abbr", 'string'),
                'pinyin' => $this->request->getPost("pinyin", 'string'),
                'pinyinAbbr' => $this->request->getPost("pinyinAbbr", 'string'),
                'pinyinFirst' => $this->request->getPost("pinyinFirst", 'string'),
                'X' => $this->request->getPost("x", 'string'),
                'Y' => $this->request->getPost("y", 'string'),
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
        $regId = intval($regId);
        $data['info'] = CityRegion::findFirst($regId);
        $data['distStatus'] = CityRegion::getStatus();
        
        $this->show("add", $data);
    }  
}
