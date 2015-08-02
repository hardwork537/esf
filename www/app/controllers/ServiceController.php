<?php

class ServiceController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/service.css');
        
        $this->_setTitle('房易买-我要服务');
        
        $this->show(null, $data);
    }
}
