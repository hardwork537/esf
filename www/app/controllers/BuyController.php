<?php

class BuyController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/list.css');
        
        $this->show(null, $data);
    }
}
