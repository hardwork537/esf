<?php

class RegisterController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/register.css');
        
        $this->show(null, $data);
    }
}
