<?php

$sysQuene['default'] = array(
    'host' => '10.3.34.55',
    'port' => '11300',
);
include("../config/system.db.config");
include("Quene.php");
$time1 = time();
        
$queneObj = Quene::Instance();echo "\r\n";
for($i = 1; $i <= 50; $i++)
{
    echo $i."\r\n";
    //fastcgi_finish_request();
    $res = $queneObj->Put('house', array('action' => 'offline', 'houseId' => $i, 'realId' => $i, 'cityId' => 1, 'status' => 2, 'time' => date('Y-m-d H:i:s', time())));
    var_dump($res);
}
echo "\r\n".(time()-$time1)."\r\n";
exit;