<?php
$router = new Phalcon\Mvc\Router();

$router->add('/', array(
    "controller"    => 'upload',
    'action'        => 'index' 
));

$router->notFound(array(
    "controller" => "error",
    "action"     => "nofound"
));
