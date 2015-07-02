<?php

class ServiceController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/service.css');
        
        $this->show(null, $data);
    }
}
