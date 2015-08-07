<?php

require_once 'base.php';

global $sysES;
$params = $sysES['default'];
$params['index'] = 'esf';
$params['type'] = 'house';

$client = new Es($params);

$createMapping = array(
    'index' => "esf",
    "type" => "house",
);
$inParams = array();
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
        $inParams[$result[0]] = $result[1];
    }
}

if($inParams['id'])
{
    $houseIds = explode(',', $inParams['id']);
}
if('delete' == $inParams['type'])
{
    if(!empty($houseIds))
    {
        $where = array('where'=>array('houseId'=>array('in'=>$houseIds)));
    } else {
        $where = array('where'=>array('houseId'=>array('>'=>0)));
    }
    $client->deleteByQuery($where);
    exit;
}

if(!$client->existsType($createMapping))
{
    $mapType = array(
        '_source' => array(
            'enabled' => true,
        ),
        'properties' => array(
            "houseId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "parkId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "houseRemark" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "parkName" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "houseAddress" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "houseTitle" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "distId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "regId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "housePrice" => array(
                'type' => 'float',
                "null_value" => '0.00',
                "store" => "yes",
            ),
            "houseBuildType" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseBA" => array(
                "type" => "float",
                "null_value" => '0.00',
                "store" => "yes",
            ),
            "houseBedRoom" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseLivingRoom" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseBathRoom" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseOrientation" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseDecoration" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "status" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseCreate" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "houseUpdate" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "houseUnit" => array(
                'type' => 'float',
                'store' => 'yes',
                "null_value" => '0.00',
            ),
            "subwayLine" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "subwaySite" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "subwaySiteLine" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "housePropertyType" => array(
                'type' => 'short',
                'store' => 'yes',
            ),
            "houseFeatures" => array(
                "type" => "string",
                "index" => "analyzed",
                "analyzer" => "mmseg",
                "store" => "yes",
                "null_value" => '',
            ),
            "houseFloor" => array(
                'type' => 'short',
                'store' => 'yes',
            ),
            "houseFloorMax" => array(
                'type' => 'short',
                'store' => 'yes',
            ),
            "housePicId" => array(
                'type' => 'integer',
                'store' => 'yes',
            ),
            "housePicExt" => array(
                'type' => 'string',
                'store' => 'yes',
                "index" => "not_analyzed",
                'null_value' => '',
            ),
            "houseLevel" => array(
                'type' => 'string',
                'store' => 'yes',
                "index" => "not_analyzed",
                'null_value' => '',
            ),
            "houseType" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "houseTags" => array(
                "type" => "short",
                "store" => "yes",
            ),
            "cityId" => array(
                "type" => "integer",
                "store" => "yes",
            )
        )
    );
    $client->createMapping($mapType);
}

$conWhere = empty($houseIds) ? null : "id in(".  implode(',', $houseIds).")";
$house = House::find($conWhere, 0)->toArray();
if(empty($house))
{
    exit('no house');
}
$parkIds = $houseList = array();
foreach($house as $v)
{
    $parkIds[] = $v['parkId'];
    $value = array();

    $value['houseId'] = (int) $v['id'];
    $value['parkId'] = (int) $v['parkId'];
    $value['distId'] = (int) $v['distId'];
    $value['regId'] = (int) $v['regId'];
    $value['houseTitle'] = $v['title'];
    $value['housePrice'] = (float) $v['handPrice'];
    $value['houseBuildType'] = (int) $v['buildType'];
    $value['houseBA'] = (float) $v['bA'];
    $value['houseBedRoom'] = (int) $v['bedRoom'];
    $value['houseLivingRoom'] = (int) $v['livingRoom'];
    $value['houseBathRoom'] = (int) $v['bathRoom'];
    $value['houseOrientation'] = (int) $v['orientation'];
    $value['houseDecoration'] = (int) $v['decoration'];
    $value['status'] = (int) $v['status'];
    $value['houseCreate'] = strtotime($v['createTime']) ? strtotime($v['createTime']) : 0;
    $value['houseUpdate'] = strtotime($v['updateTime']) ? strtotime($v['updateTime']) : 0;
    $value['houseUnit'] = (float)number_format($v['handPrice']/$v['bA'], 2, '.', '');
    $value['subwayLine'] = '';
    $value['subwaySite'] = '';
    $value['subwaySiteLine'] = '';
    $value['housePropertyType'] = (int) $v['propertyType'];
    $value['houseFeatures'] = '';
    $value['houseFloor'] = (int) $v['floor'];
    $value['houseFloorMax'] = (int) $v['floorMax'];
    $value['housePicId'] = 0;
    $value['housePicExt'] = '';
    $value['houseType'] = (int) $v['type'];
    $value['houseTags'] = (int) $v['parkId'];
    $value['cityId'] = (int) $v['cityId'];
    $value['houseRemark'] = $v['remark'];
    $value['houseLevel'] = $v['level'];

    $houseList[$v['id']] = $value;
}
$parkIds = array_flip(array_flip($parkIds));
$parks = Park::instance()->getParkByIds($parkIds, 'id,name,alias,address');
//var_dump($parks);
//获取标签
$tagList = HouseTag::instance()->getTagsForOption();

//获取房源标签
$houseTagList = array();
$houseIds = array_keys($houseList);
$where = "houseId in(".  implode(',', $houseIds).")";
$houseTag = HouseExtTag::find($where, 0)->toArray();
foreach($houseTag as $v)
{
    $tagList[$v['tag']] && $houseTagList[$v['houseId']][] = $tagList[$v['tag']];
}

foreach($houseList as $id => $v)
{
    $data = $v;
    $data['parkName'] = $parks[$v['parkId']]['name'];
    //$data['parkAlias'] = $parks[$v['parkId']]['alias'];
    $data['houseAddress'] = $parks[$v['parkId']]['address'];
    if(!empty($houseTagList[$data['houseId']]))
    {
        $data['houseFeatures'] = implode(',', $houseTagList[$data['houseId']]);
    }

    $bulkData[] = array(
        'index' => array('_id' => $data['houseId'])
    );
    $bulkData[] = $data;
    
    $info = $client->bulk($bulkData);

    if($info === false || $info['errors'] == true)
    {
        $tmp = '';
        foreach($bulkData as $value)
        {
            if(isset($value['houseId']))
            {
                $tmp .= $value['houseId'] . ",";
            }
        }
        $tmp = rtrim($tmp, ',');
        fwrite($errorFh, "error:" . $tmp . "\n");
    } else
    {
        echo $v['houseId'] .' done'.PHP_EOL;
    }
}