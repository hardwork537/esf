<?php
$router = new Phalcon\Mvc\Router();

$router->add('/', array(
    "controller"    => 'house',
    'action'        => 'list' 
));


$router->notFound(array(
    "controller" => "error",
    "action"     => "nofound"
));
