<?php

/**
 * @abstract  系统加载
 */
use Phalcon\Cache\Frontend\Output as OutputFrontend,
    Phalcon\Cache\Backend\Memcache as MemcacheBackend;

require DOCROOT . '../config/system.db.config.php';
require DOCROOT . '../config/www.config.php';

/**
 * 自动加载
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    [
        ROOT . "models",
        ROOT . "librarys",
        APPROOT . "controllers",
        APPROOT . "models",
        APPROOT . "libs",
        ROOT . "libs",
        ROOT . "models",
        APPROOT . "config",
        ROOT . "config",
])->register();

$di = new \Phalcon\DI\FactoryDefault();

/**
 * 设置路由
 */
$di->set('router', function () {
    require APPROOT . '/config/routers.config.php';
    $router->setDefaults(array(
        'controller' => 'house',
        'action' => 'index'
    ));
    return $router;
});

/**
 * 注入会话服务
 */
$di->setShared('session', function () {
    $session = new Phalcon\Session\Adapter\Files(array(
        'uniqueId' => 'esf_www_'
    ));
    $session->start();
    return $session;
});

/**
 * 注入日志服务
 */
$di->setShared('logger', function () {
    $logger = new \Phalcon\Logger\Adapter\File();
    $logger->setFormat("[%date%] - %message%");
    return $logger;
});

/**
 * 注入发送消息服务
 */
$di->set('flash', function () {
    return new \Phalcon\Flash\Direct();
});

/**
 * 注入数据库运行分析器
 */
$di->set('profiler', function () {
    return new \Phalcon\Db\Profiler();
}, true);

/**
 * 注入监听服务
 */
$di->set('events', function () {
    return new Phalcon\Events\Manager();
});

/**
 * 遍历注入数据库组件
 */
foreach($sysDB as $k => $v)
{
    foreach($v as $p => $q)
    {
        $di->set($k . ucfirst($p), function () use ($q, $di) {
            $eventsManager = $di->getEvents();
            $profiler = $di->getProfiler();
            $flash = $di->getFlash();
            $eventsManager->attach('db', function ($event, $connection) use ($profiler, $flash) {
                if($event->getType() == 'beforeQuery')
                {
                    if(OPEN_DEBUG == true)
                    {
                        // $flash->notice("getSQLStatement:".$connection->getSQLStatement());
                        $flash->notice("SQL:" . $connection->getRealSQLStatement());
                        // $flash->notice("getDescriptor:".json_encode($connection->getDescriptor()));
                    }
                }
                //else {
                // $flash->notice($event->getType());
                //}
            });
            $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(
                array(
                "host" => $q['host'],
                "username" => $q['username'],
                "password" => $q['password'],
                "dbname" => $q['dbname'],
                "options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $q['charset']
                )
            ));
            $connection->setEventsManager($eventsManager);
            return $connection;
        }, true);
    }
}

/**
 * 自定义标签服务
 */
$di->set("MyTags", function () {
    $mytags = new MyTags();
    return $mytags;
});

/**
 * 注入分发器服务
 */
$di->set('dispatcher', function () use ($di) {

    $eventsManager = $di->getEvents();
    $eventsManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {
        Log::ErrorWrite('www', '', $_SERVER['REQUEST_URI'] . "\t" . $exception->getMessage(), 'debug.txt');

        //Handle 404 exceptions
        if($exception instanceof Phalcon\Mvc\Dispatcher\Exception)
        {
            $dispatcher->forward(array(
                'controller' => 'error',
                'action' => 'nofound'
            ));
            return false;
        }

        //Alternative way, controller or action doesn't exist
        if($event->getType() == 'beforeException')
        {
            switch($exception->getCode())
            {
                case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward(array(
                        'controller' => 'error',
                        'action' => 'nofound'
                    ));
                    return false;
            }
        }
    });
    /*
      $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher)
      {
      $request = new Phalcon\Http\Request();
      $params  = $request->get();
      $getKeys = array();
      //        preg_match('/\.[a-z0-9]+$/', $params["_url"], $suffix);
      //        if($suffix)
      //        {
      //            $dispatcher->forward(array(
      //                'controller' => 'error',
      //                'action'     => 'nofound'
      //            ));
      //        }
      if($_GET)
      {
      foreach($_GET as $k => $v)
      {
      $getKeys[] = $k;
      }
      }
      //Use odd parameters as keys and even as values
      foreach($params as $number => $str)
      {
      if(in_array($number, $getKeys))
      {
      //$str = str_replace("and","",$str);
      $str = str_replace("execute", "", $str);
      $str = str_replace("update", "", $str);
      $str = str_replace("count", "", $str);
      $str = str_replace("chr", "", $str);
      $str = str_replace("mid", "", $str);
      $str = str_replace("master", "", $str);
      $str = str_replace("truncate", "", $str);
      $str = str_replace("char", "", $str);
      $str = str_replace("declare", "", $str);
      $str = str_replace("select", "", $str);
      $str = str_replace("create", "", $str);
      $str = str_replace("delete", "", $str);
      $str = str_replace("insert", "", $str);
      //$str = str_replace("or","",$str);
      $str               = str_replace("=", "", $str);
      $str               = str_replace("%20", "", $str);
      $_REQUEST[$number] = addslashes(strip_tags($str));
      }
      }
      });
     */

    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});

/**
 * 注入返回响应服务
 */
$di->set('response', function () use ($di) {
    $response = new Phalcon\Http\Response();
    return $response;
});

/**
 * 注入URl服务
 */
$di->set('url', function () {
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri("/"); // 可以定义一个基础变量
    return $url;
});

/**
 * 注入队列服务
 */
$di->set('queue', function () use ($sysQuene) {
    $queue = new Phalcon\Queue\Beanstalk([
        'host' => $sysQuene['default']['host'],
        'port' => $sysQuene['default']['port']
    ]);
    return $queue;
});

/**
 * 注入PHQL语言服务
 */
$di->set('phql', function () use ($di) {
    $phql = new Phalcon\Mvc\Model\Manager();
    return $phql;
});

/**
 * 缓存数据库结构
 */
$di->set('modelsMetadata', function () {
    //return new \Phalcon\Mvc\Model\Metadata\Memory();
    return new \Phalcon\Mvc\Model\MetaData\Files(array(
        "lifetime" => 86400,
        'metaDataDir' => realpath(__DIR__) . '/compiled/'
    ));
});

//设置模型缓存服务(模型中的cache默认会调用此缓存)
$di->set('modelsCache', function () use ($sysMemcache) {
    $frontCache = new \Phalcon\Cache\Frontend\Data(array(
        "lifetime" => 3600
    ));
    $cache = new \Phalcon\Cache\Backend\Memcache($frontCache, array(
        'host' => $sysMemcache['default']['host'],
        'port' => $sysMemcache['default']['port'],
        'persistent' => false
    ));

    return $cache;
});

/**
 * 定义需要的模板引擎
 */
$di->set('view', function () {
    $view = new \Phalcon\Mvc\View();
    $voltService = new Phalcon\Mvc\View\Engine\Volt($view);
    $voltService->setOptions(array(
        'compiledPath' => APPROOT . 'compiled/',
        'compiledSeparator' => '_',
        "compiledExtension" => ".compiled",
        "compileAlways" => true,
    ));
    $view->setViewsDir(APPROOT . "views");
    $view->registerEngines(array(
        ".html" => $voltService
    ));
    return $view;
});

try
{
    register_shutdown_function(array('Error', 'catchFatalError'), 'www');
    $StartTime = microtime(true);
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    echo $application->handle()->getContent();
    $EndTime = microtime(true);
    $UseTime = $EndTime - $StartTime;
    $UseTime >= 2 && Log::ErrorWrite('www', '', $_SERVER['REQUEST_URI'] . "\t共耗时 $UseTime 秒\n", 'timer.txt');
} catch(Exception $e)
{
    header("Content-type:text/html;charset=utf-8");
    if(OPEN_DEBUG === true)
    {
        echo "错误代码：" . $e->getCode() . "<br>错误信息：" . $e->getMessage() . '<br>文件：' . $e->getFile() . '<br>行号：' . $e->getLine() . '<br>路由信息：' . $e->getTraceAsString();
        exit();
    }
    Log::ErrorWrite('www', '', $_SERVER['REQUEST_URI'] . "\t" . "错误代码：" . $e->getCode() . "\r 错误信息：" . $e->getMessage() . '\r 文件：' . $e->getFile() . '\r 行号：' . $e->getLine() . '\r 路由信息：' . $e->getTraceAsString(), 'debug.txt');
    header("http/1.1 502 Bad Gateway");
    die;
    //Util::ShowMessage("", "/error/nofound");
}