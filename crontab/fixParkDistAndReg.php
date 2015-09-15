<?php

require_once 'base.php';

$dbConfig = $sysDB['esf']['master'];

$con = mysql_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password']);
mysql_select_db($dbConfig['dbname']);

//解析参数
foreach($argv as $v)
{
    if(false === strpos($v, '='))
    {
        continue;
    }
    $result = explode('=', $v);
    if($result[0] && $result[1])
    {
        $params[$result[0]] = $result[1];
    }
}
if(empty($params))
{
    exit('no params');
}

//查询个数
$sql = "select count(*) as num from house where parkId={$params['parkId']}";
$res = mysql_query($sql);
$row = mysql_fetch_assoc($res);
$houseNum = intval($row['num']);

//查询小区区域、板块
$sql = "select distId,regId from park where parkId={$params['parkId']}";
$res = mysql_query($sql);
$row = mysql_fetch_assoc($res);
if(empty($row))
{
    exit('no park info');
}
$distId = intval($row['distId']);
$regId = intval($row['regId']);

//更新房源区域、板块数据
$updateSql = "update house set distId={$distId},regId={$regId} where parkId={$params['parkId']} limit {$houseNum}";
$res = mysql_query($updateSql);
var_dump($res);