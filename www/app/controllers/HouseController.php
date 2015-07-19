<?php

class HouseController extends ControllerBase
{

    public function addAction()
    {
        $data = array();
        $data['cssList'] = array('css/fabu.css');
        
        $this->show(null, $data);
    }
}
