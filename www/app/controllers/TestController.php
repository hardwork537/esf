<?php

class TestController extends ControllerBase
{  
    
    public function esselectAction()
    {
        global $sysES;
        
        $clsEs = new Es($sysES['default']);
        $limit = array(0, 100);
        
        $houseId = trim($this->request->get('houseId', 'string', ''));
        $houseId && $houseIds = explode(',', $houseId);
        if(!empty($houseIds))
        {
            $where['houseId'] = array(
                'in' => $houseIds
            );
        }
        
        $arrSelect = $clsEs->search(array('where' => $where, 'limit' => $limit, 'order'=>$order));
       
        echo '<pre>'; var_dump($arrSelect);
    }
}