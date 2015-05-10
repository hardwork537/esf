<?php

/**
 * @abstract  城市字典
 */
class CityController extends ControllerBase
{

    public function listAction()
    {
        $data['citylist'] = City::find(array(
                "order" => " weight asc"
            ))->toArray();
        $data['cityStatus'] = City::getStatus();

        $this->show(null, $data);
    }

    public function addAction()
    {
        if($this->request->isPost())
        {
            $arr['name'] = $this->request->getPost("name", "string");
            $arr['pinyin'] = $this->request->getPost("pinyin", "string");
            $arr['pinyinAbbr'] = $this->request->getPost("pinyinAbbr", "string");
            $arr['status'] = $this->request->getPost("status", "int", 1);
            $arr['weight'] = $this->request->getPost("weight", "int", 0);
            $rs = City::instance()->add($arr);
            if($rs)
            {
                $this->show("JSON");
            }
            $this->show("ERROR");
        }
        $this->show("JSON");
    }

    public function editAction($id = 0)
    {
        $id = intval($id);

        if($this->request->isPost())
        {
            $cityId = $this->request->getPost("id", 'int');
            $arr['name'] = $this->request->getPost("name", "string");
            $arr['pinyin'] = $this->request->getPost("pinyin", "string");
            $arr['pinyinAbbr'] = $this->request->getPost("pinyinAbbr", "string");
            $arr['status'] = $this->request->getPost("status", "int", 1);
            $arr['update'] = date("Y-m-d H:i:s");
            $arr['weight'] = $this->request->getPost("weight", "int", 0);
            $rs = City::instance()->edit($cityId, $arr);
            if($rs)
            {
                $this->show("JSON");
            }
            $this->show("ERROR");
        }
        $this->_json["data"] = City::findFirst("id='$id'")->toArray();
        $this->show("JSON");
    }

}
