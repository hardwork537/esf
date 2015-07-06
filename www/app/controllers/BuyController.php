<?php

class BuyController extends houseBuy
{
    public function listAction()
    {
        $data = array();
        $data['cssList'] = array('css/list.css');
        //筛选项
        $res = $this->getFilters();
        $data['filter'] = $res['filter'];
        $data['params'] = $res['params'];
        $data['url'] = $res['url'];
        
        $this->show(null, $data);
    }
}
