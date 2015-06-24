<?php

class HomeController extends ControllerBase
{

    public function indexAction()
    {
        $data['user'] = $this->_userInfo;
        
        $this->show(null, $data);
    }

}
