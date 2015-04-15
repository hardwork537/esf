<?php

class RoomController extends ControllerBase
{
    public function indexAction($name='')
    {
        var_dump($this->request->get());
        exit('RoomController');
        $this->show(null, $data);
    }

}
