<?php
$router = new Phalcon\Mvc\Router();

$router->add('/', array(
    "controller"    => 'home',
    'action'        => 'index' 
));

$router->add('/my[/]?', array(
    'controller' =>	'my',
    'action'     =>	'favorite'
));

$router->add('/buy/?([a-z1-9]*)/?([a-z1-9]*)/?', array(
    "controller"    => 'buy',
    'action'        => 'list',
    'param1' => 1,
    'param2' => 2
));


$router->notFound(array(
    "controller" => "error",
    "action"     => "nofound"
));
