<?php

class AboutController extends ControllerBase
{

    public function usAction()
    {
        $data = array();
        $data['cssList'] = array('css/about.css');
        
        $this->show(null, $data);
    }

    public function serviceAction()
    {
        $data = array();
        $data['cssList'] = array('css/about.css');
        
        $this->show(null, $data);
    }
    
    public function siteAction()
    {
        $data = array();
        $data['cssList'] = array('css/about.css');
        
        $this->show(null, $data);
    }
}
