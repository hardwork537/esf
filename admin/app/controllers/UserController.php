<?php

class UserController extends ControllerBase
{
    public function listAction()
    {
        $user = AdminUser::find(null, 0)->toArray();
        var_dump($user);exit;
        
        $this->show(null, $data);
    }
    
    public function showAction()
    {
        echo 'aaa';
    }

}
