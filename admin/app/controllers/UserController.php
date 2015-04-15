<?php

class UserController extends ControllerBase
{
    public function indexAction()
    {
        $user = User::instance()->find(null, 0)->toArray();
        var_dump($user);exit;
        
        $this->show(null, $data);
    }

}
