<?php

class LoginController extends ControllerBase
{
    public function indexAction()
    {
        $data = array();
        $data['cssList'] = array('css/login.css');
        
        $this->show(null, $data);
    }
}