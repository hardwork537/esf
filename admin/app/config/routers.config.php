<?php
$router = new Phalcon\Mvc\Router();

$router->add('/', array(
    "controller"    => 'home',
    'action'        => 'index' 
));


$router->notFound(array(
    "controller" => "error",
    "action"     => "nofound"
));
