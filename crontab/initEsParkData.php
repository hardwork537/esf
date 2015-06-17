<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Shanghai');
define("OPEN_DEBUG", false);

require_once __DIR__."/../vendor/autoload.php";
//Register an autoload
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    __DIR__.'/./controllers/',
    __DIR__.'/../libs/',
    __DIR__.'/./libs/',
    __DIR__.'/../models/'
))->register();

$config = require __DIR__ . '/../config/system.db.config.php';
//Create a DI
$di = new Phalcon\DI\FactoryDefault();
/**
 * 注入监听服务
 */
$di->set('events', function () {
    return new Phalcon\Events\Manager ();
});
$di->set('profiler', function() {
    return new \Phalcon\Db\Profiler();
}, true);
/**
 * 遍历注入数据库组件
 */
foreach($sysDB as $k => $v)
{
    foreach($v as $p => $q)
    {
        $di->set($k . ucfirst($p), function () use($q, $di) {
            $eventsManager = $di->getEvents();
            $profiler = $di->getProfiler();
            $eventsManager->attach('db', function ($event, $connection) use($profiler) {
                if($event->getType() == 'beforeQuery')
                {
                    if(OPEN_DEBUG == true)
                    {
                        //if (stripos($connection->getRealSQLStatement(),'prop') !== false){
                        // var_dump($connection);
                        echo "SQL:" . $connection->getRealSQLStatement() . "<br/>";
                        //}
                    }
                } else
                {
                    
                }
            });
            $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
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

$mPark = new Park();
$cityWhere = '';
$cityName = isset($argv[1]) ? $argv[1] : '';
if($cityName)
{
    $mCity = new City();
    $allCity = $mCity->getAllCity();
    $cityInfo = array();
    foreach($allCity as $city)
    {
        if($city['pinyin'] == $cityName)
        {
            $cityInfo = $city;
            break;
        }
    }
    unset($allCity);
    if($cityInfo)
    {
        //  $cityWhere = "cityId=".$cityInfo['id'];
        echo $cityInfo['name'] . "小区开始写入小区--->" . time() . "\n";
    } else
    {
        echo "全部小区开始写入小区--->" . time() . "\n";
    }
} else
{
    echo "全部小区开始写入小区--->" . time() . "\n";
}
$count = $mPark->count($cityWhere);
$pageSize = 1;
$totalPage = ceil($count / $pageSize);
global $sysES;
$params = $sysES['default'];
$params['index'] = 'esf';
$params['type'] = 'park';

$client = new Es($params);

$createMapping = array(
    'index' => "esf",
    "type" => "park",
);
//var_dump($client->existsType($createMapping));exit;
//$client->createIndex('esf');
if(!$client->existsType($createMapping))
{
    $mapType = array(
        '_source' => array(
            'enabled' => true,
        ),
        'properties' => array(
            "cityId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "distId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "regId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkType" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "parkBuildType" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkBuildYear" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkSalePrice" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkX" => array(
                "type" => "string",
                "index" => "not_analyzed",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkY" => array(
                "type" => "string",
                "index" => "not_analyzed",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkSaleValid" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkRentValid" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkName" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkAlias" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkPinYin" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkPinYinAbbr" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkAddress" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkSubwaySite" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkSubwayLine" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkSubwaySiteLine" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkStatus" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "parkId" => array(
                "type" => "integer",
                "store" => "yes",
            ),
            "parkTags" => array(
                "type" => "string",
                "index" => "not_analyzed",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkFacility" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkOldPercent" => array(
                "type" => "integer",
                "store" => 'yes',
            ),
            "parkRentPercent" => array(
                "type" => 'integer',
                "store" => "yes",
            ),
            "parkGR" => array(
                "type" => "integer",
                "store" => "yes",
            ),
            "parkFAR" => array(
                "type" => "integer",
                "store" => "yes",
            ),
        )
    );
    $res = $client->createMapping($mapType);
    var_dump($res,'aaa');exit;
}

$totalPage = 5;

for($currentPage = 1; $currentPage < $totalPage; $currentPage++)
{
    $mPark = new Park();
    $mParkExt = new ParkExt();
    $blukData = array("index" => "esf", "type" => "park");
    $arrCondition = array();
    $arrCondition['conditions'] = $cityWhere;
    $arrCondition['order'] = "id asc";
    $arrCondition['limit'] = array("number" => $pageSize, "offset" => ($currentPage - 1) * $pageSize);
    $arrCondition['columns'] = "id as parkId,name as parkName,alias as parkAlias,pinyin as parkPinyin,pinyinAbbr as parkPinyinAbbr,
    cityId,distId,regId,status as parkStatus,address as parkAddress,type as parkType,buildType as parkBuildType,buildYear as parkBuildYear,
    salePrice as parkSalePrice,X as parkX,Y as parkY,saleValid as parkSaleValid,rentValid as parkRentValid,tags as parkTags,GR as parkGR,
    FAR as parkFAR";
    $arrCondition['columns'] = 'id';
    echo "park Memory befor:" . memory_get_usage() . "\n";
    $parkInfo = $mPark::find($arrCondition)->toArray();
//    foreach ($parkInfo as &$park){
//        unset($park);
//    }
//    unset($park);
    unset($mPark);
    unset($parkInfo);
    echo "park Memory after:" . memory_get_usage() . "\n";
    die();
    if(!$parkInfo)
        continue;
    $arParkId = array();
    foreach($parkInfo as $park)
    {
        $arParkId[] = $park['parkId'];
    }
    echo "park ext Memory befor:" . memory_get_usage() . "\n";
//    $extParkInfo = $mParkExt->getAll("parkId in (".join(',', $arParkId).")");
//    $mParkExt->free();
//    unset($mParkExt);
//    echo "park ext Memory after:".memory_get_usage()."\n";
//    $rhExtPark = array();
//    if ($extParkInfo){
//        foreach ($extParkInfo as $extPark)
//        {
//            $rhExtPark[$extPark['parkId']][$extPark['name']] = $extPark['value'];
//        }
//    }
    echo "park ext Memory after1:" . memory_get_usage() . "\n";

//    foreach ($parkInfo as &$park){
//        $park['parkId'] = (int)$park['parkId'];
//        $park['cityId'] = (int)$park['cityId'];
//        $park['distId'] = (int)$park['distId'];
//        $park['regId'] = (int)$park['regId'];
//        $park['parkStatus'] = (int)$park['parkStatus'];
//        $park['parkType'] = (int)$park['parkType'];
//        $park['parkBuildType'] = (int)$park['parkBuildType'];
//        $park['parkBuildYear'] = (int)$park['parkBuildYear'];
//        $park['parkSalePrice'] = (int)$park['parkSalePrice'];
//        $park['parkSaleValid'] = (int)$park['parkSaleValid'];
//        $park['parkRentValid'] = (int)$park['parkRentValid'];
//        $park['parkGR'] = (int)$park['parkGR'];
//        $park['parkFAR'] = (int)$park['parkFAR'];
//        $park['parkSubywaySite'] = isset($rhExtPark[$park['parkId']]['周边地铁站点']) ? $rhExtPark[$park['parkId']]['周边地铁站点'] : '';
//        $park['parkSubwayLine'] = isset($rhExtPark[$park['parkId']]['周边地铁线路']) ? $rhExtPark[$park['parkId']]['周边地铁线路'] : '';
//        $park['parkSubwaySiteLine'] = isset($rhExtPark[$park['parkId']]['周边地铁站点串']) ? $rhExtPark[$park['parkId']]['周边地铁站点串'] : '';
//        $park['parkAroundSchool'] = isset($rhExtPark[$park['parkId']]['周边学校']) ? $rhExtPark[$park['parkId']]['周边学校'] : '';
//        $park['parkFacility'] = isset($rhExtPark[$park['parkId']]['小区设施']) ? $rhExtPark[$park['parkId']]['小区设施'] : '';
//        $park['parkOldPercent'] = isset($rhExtPark[$park['parkId']]['老人占比']) ? (int)$rhExtPark[$park['parkId']]['老人占比'] : 0;
//        $park['parkRentPercent'] = isset($rhExtPark[$park['parkId']]['出租占比']) ? (int)$rhExtPark[$park['parkId']]['出租占比'] : 0;
//        $blukData[] = array(
//            'index' =>  array('_id'=>$park['parkId'])
//        );
//        $blukData[] = $park;
//    }
    // $client->bulk($blukData);
    unset($park);
    unset($parkInfo);
    unset($rhExtPark);
    unset($extParkInfo);
    unset($blukData);
    unset($arParkId);
    unset($arrCondition);
    echo "park ext Memory after2:" . memory_get_usage() . "\n";
}

echo "数据写入结束\n--->" . time();

