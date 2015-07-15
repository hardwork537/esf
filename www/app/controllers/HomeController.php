<?php

class HomeController extends ControllerBase
{

    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/index.css');
        //热门搜索
        global $HOT_SEARCH;
        $data['hot'] = $HOT_SEARCH[2];
        
        $this->show(null, $data);
    }

}
