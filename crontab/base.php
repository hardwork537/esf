<?php
date_default_timezone_set('Asia/Shanghai');
setlocale(LC_ALL, 'En-Us');
set_time_limit(0); //不限制超时时间
ini_set('display_errors', "on");
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
ini_set('memory_limit','1024M');

define("ROOT", dirname(__DIR__) . '/');
define('CRONTAB_PATH', ROOT . "crontab/");

require ROOT . 'config/system.db.config.php';
require ROOT . 'config/config.inc.php';

$di = new \Phalcon\DI\FactoryDefault();
$di->set('esfMaster', function(){
    global $sysDB;
    return new \Phalcon\Db\Adapter\Pdo\Mysql($sysDB['esf']['master']);
});
$di->set('esfSlave', function(){
    global $sysDB;
    return new \Phalcon\Db\Adapter\Pdo\Mysql($sysDB['esf']['slave']);
});

$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        ROOT . 'models/',
        ROOT . 'libs/',
    )
)->register();