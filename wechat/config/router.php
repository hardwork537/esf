<?php
	$router = new \Phalcon\Mvc\Router();
    $router->add('/',
        array(
            'controller'	=>	'index',
            'action'	=>	'index',
        )
    );
	$router->add('/:controller/:action[/]',
			array(
				'controller'	=>	1,
				'action'	=>	2,
			));

	$router->notFound(array(
			'controller'	=>	'index',
			'action'	=>	'notfound',
			));
	return $router;
