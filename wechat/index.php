<?php
	error_reporting(E_ALL);
    ini_set('display_errors',1);
    date_default_timezone_set('Asia/Shanghai');

    try{
        //Register an autoload
        $loader = new \Phalcon\Loader();
        $loader->registerDirs(array(
                                './controllers/',
                                '../libs/',
                                './libs/',
                                '../models/',
                                '../www/libs/',
                                '../wechat/libs/'
                        ))->register();

        //加载公共db,memcache,es配置
        require_once __DIR__.'/../config/system.db.config.php';
        require_once __DIR__.'/../config/common.inc.php';
        require_once __DIR__.'/../config/MCDefine.php';
        require_once __DIR__.'/../config/wechat.config.inc.php';
        require_once __DIR__.'/../config/config.inc.php';

        //cityName
        //$cityName = array_shift(@explode('.',$_SERVER['HTTP_HOST']));
        //if (!file_exists(__DIR__.'/../config/'.$cityName.'.config.inc.php')){
        //exit;
        //}
        //require_once __DIR__.'/../config/'.$cityName.'.config.inc.php';

        //Create a DI
        $di = new Phalcon\DI\FactoryDefault();

        $di->set('dispatcher',function(){
            $eventsManager = new Phalcon\Events\Manager();
            $eventsManager->attach('dispatch:beforeNotFoundAction',function($event, $dispatcher){
                $dispatcher->forward(array('controller'=>'index','action'=>'notfound'));
            });
            $eventsManager->attach("dispatch:beforeException",function($event, $dispatcher, $exception){
                //404
                if ($exception instanceof DispatchException){
                    $dispatcher->forward(array(
                        'controller'	=>	'notfound',
                        'action'	=>	'index',
                    ));
                }
                //no controller or no action
                if ($event->getType() == 'beforeException'){
                    switch($exception->getCode()){
                        case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(array(
                                'controller'	=>	'notfound',
                                'action'	=>	'index',
                            ));
                    }
                }

            });
            $dispatcher = new Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        }, true);

        //setup the view component
        $di->set("view",function(){
            $view = new \Phalcon\Mvc\View();
            $voltSerivce = new Phalcon\Mvc\View\Engine\Volt($view);
            $voltSerivce->setOptions(array(
                'compiledPath' => __DIR__.'/compiled/',
                'compiledSeparator' => '_',
                "compiledExtension" => ".compiled"
            ));
            $compiler = $voltSerivce->getCompiler();
            $compiler->addFilter('staticUrl',function($resolvedArgs){
                return 'ViewHelper::staticUrl(' . $resolvedArgs . ')';
            });
            $compiler->addFilter('intValue',function($resolvedArgs){
                return 'ViewHelper::intValue(' . $resolvedArgs . ')';
            });
            $compiler->addFunction('substrChange',function($resolvedArgs){
                return 'ViewHelper::substrChange(' . $resolvedArgs .')';
            });
            $compiler->addFunction('advert',function($resolvedArgs){
                return 'ViewHelper::advert(' . $resolvedArgs .')';
            });
            $compiler->addFunction('advertSource',function($resolvedArgs){
                return 'ViewHelper::advertSource(' . $resolvedArgs .')';
            });

            $view->registerEngines(array(
                ".volt" => $voltSerivce
            ));
            $view->setViewsDir('./views/');
            //$view->setLayoutsDir('./layouts/');
            //$view->setMainView('index');

            $eventsManager = new Phalcon\Events\Manager();
            $eventsManager->attach('view:beforeRenderView',function($event, $view){

            });
            $view->setEventsManager($eventsManager);
            //Disable layout levels
            $view->disableLevel(array(
                \Phalcon\Mvc\View::LEVEL_LAYOUT => true,
            ));

            return $view;
        });


        $di->set('router',function(){
                $router = require_once __DIR__.'/config/router.php';
                return $router;
        });

        /**
         * 注入监听服务
         */
        $di->set ( 'events', function () {
            return new Phalcon\Events\Manager();
        } );

        /**
         * 注入DB Manage
         */
        $di->set('profiler', function(){
            return new \Phalcon\Db\Profiler();
        }, true);

        /**
         * 遍历注入数据库组件
         */
        foreach ( $sysDB as $k => $v ) {
            foreach($v as $p=>$q){
                $di->set ( $k . ucfirst ($p) , function () use($q, $di) {
                    $eventsManager = $di->getEvents ();
                    $profiler = $di->getProfiler ();
                    $eventsManager->attach ( 'db', function ($event, $connection) use($profiler) {
                        //一条语句查询之前事件，profiler开始记录sql语句
                        if ($event->getType () == 'beforeQuery') {
                            if (OPEN_DEBUG == true) {
                                echo  "SQL:" . $connection->getRealSQLStatement();
                            }
                        }
                    } );
                    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql ( array (
                        "host" => $q['host'],
                        "username" => $q['username'],
                        "password" => $q['password'],
                        "dbname" => $q['dbname'],
                        "options" => array (
                            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$q['charset']
                        )
                    ) );
                    $connection->setEventsManager ( $eventsManager );
                    return $connection;
                }, true );
            }
        }

        /*缓存
        $di->set('memcached',function(){

            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => 172800
            ));
            global $CONFIG_MEMCACHE_DEFAULT;
            $memcacheConfig = $CONFIG_MEMCACHE_DEFAULT;
            $cache = new Phalcon\Cache\Backend\Libmemcached($frontCache, array(
                'servers' => $memcacheConfig,
                'client' => array(
                    Memcached::OPT_HASH => Memcached::HASH_MD5,
                    Memcached::OPT_PREFIX_KEY => 'esf.',
                )
            ));
            return $cache;

        });
        */

        //handle the request
        $application = new \Phalcon\Mvc\Application($di);
        echo $application->handle()->getContent();
    }
    catch(\Phalcon\Exception $e){
        //var_dump($e);
        //echo "PhalconException:".$e->getMessage();
    }
	
