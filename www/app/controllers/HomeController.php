<?php

class HomeController extends ControllerBase
{

    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/index.css');
        
        $this->show(null, $data);
    }

}
