<?php

class SellController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/weituo.css');
        
        $this->show(null, $data);
    }
}
