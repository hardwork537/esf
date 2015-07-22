<?php
define('NO_NEED_POWER', true);
class TestController extends ControllerBase
{
    
    
    public function listAction()
    {
        $condition = array(
            'conditions' => null,
            'offset' => 2,
            'limit' => 2
        );
        $city = City::find($condition, 0)->toArray();
        
        echo '<pre>';var_dump($city, $_REQUEST, $_GET, $_POST, $_COOKIE);
        
    }

}
