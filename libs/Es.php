<?php

require dirname(__FILE__) . "/../vendor/autoload.php";

class Es {

    private $_index = 'esf';
    private $_type = 'house';
    private $_connection;
    private $_debug = false;
    private static $_instanceCache;
    private $_rhNameMap = array(
        'id' => 'id',
        'houseId' => 'houseId',
        'houseType' => 'houseType',
        'customId' => 'customId',
        'parkName' => 'parkName',
        'parkAlias' => 'parkAlias',
        'houseAddress' => 'houseAddress',
        'title' => 'houseTitle',
        'houseTitle' => 'houseTitle',
        'houseRoleType' => 'houseRoleType',
        'status' => 'status',
        'houseStatus' => 'status',
        'realId' => 'realId',
        'personId' => 'personId',
        'shopId' => 'shopId',
        'areaId' => 'areaId',
        'comId' => 'comId',
        'parkId' => 'parkId',
        'distId' => 'distId',
        'regId' => 'regId',
        'bedRoom' => 'houseBedRoom',
        'houseBedRoom' => 'houseBedRoom',
        'livingRoom' => 'houseLivingRoom',
        'orientation' => 'houseOrientation',
        'houseOrientation' => 'houseOrientation',
        'bathRoom' => 'houseBathRoom',
        'houseBathRoom' => 'houseBathRoom',
        'propertyType' => 'housePropertyType',
        'housePropertyType' => 'housePropertyType',
        'decoration' => 'houseDecoration',
        'houseDecoration' => 'houseDecoration',
        'bA' => 'houseBA',
        'houseBA' => 'houseBA',
        'price' => 'housePrice',
        'housePrice' => 'housePrice',
        'unit' => 'houseUnit',
        'houseUnit' => 'houseUnit',
        'floor' => 'houseFloor',
        'houseFloor' => 'houseFloor',
        'floorMax' => 'houseFloorMax',
        'houseFloorMax' => 'houseFloorMax',
        'buildYear' => 'houseBuildYear',
        'houseBuildYear' => 'houseBuildYear',
        'buildType' => 'houseBuildType',
        'houseBuildType' => 'houseBuildType',
        'picId' => 'housePicId',
        'housePicId' => 'housePicId',
        'picExt' => 'housePicExt',
        'housePicExt' => 'housePicExt',
        'fine' => 'houseFine',
        'houseFine' => 'houseFine',
        'tags' => 'houseTags',
        'houseTags' => 'houseTags',
        'create' => 'houseCreate',
        'houseCreate' => 'houseCreate',
        'houseUpdate' => 'houseUpdate',
        'refreshTime' => 'houseRefreshTime',
        'houseRefreshTime' => 'houseRefreshTime',
        'lookTime' => 'houseLookTime',
        'houseLookTime' => 'houseLookTime',
        'xiajia' => 'houseXiajia',
        'houseXiajia' => 'houseXiajia',
        'verification' => 'houseVerification',
        'houseVerification' => 'houseVerification',
        "houseRoleType" => 'houseRoleType',
        "subwayLine" => 'subwayLine',
        "subwaySite" => 'subwaySite',
        "subwaySiteLine" => 'subwaySiteLine',
        "parkAroundSchool" => 'parkAroundSchool',
        "sign" => 'houseFeatures',
        "features" => 'houseFeatures',
        "houseFeatures" => 'houseFeatures',
        "quality" => 'houseQuality',
        "houseQuality" => 'houseQuality',
        "parkAvgPrice" => 'parkAvgPrice',
        's_keyword' => 's_keyword',
        'rentPrice' => 'housePrice',
        'cityId' => 'cityId',
        "failure" => 'failure',
        "roleType" => 'houseRoleType',
        'personId' => 'personId',
        "multiFields" => 'multiFields',
        "currency" => "houseCurrency",
        "rentCurrency" => "houseCurrency",
        "hoId"          =>  "hoId",
        "rentType"  =>  "rentType",
        "rentTypeTo"    =>  "rentTypeTo",
    );
    private $_parkNameMap = array(
        'id' => 'id',
        'parkId' => 'parkId',
        'type' => 'parkType',
        'name' => 'parkName',
        'parkName' => 'parkName',
        'alias' => 'parkAlias',
        'parkAlias' => 'parkAlias',
        'address' => 'parkAddress',
        'parkAddress' => 'parkAddress',
        'status' => 'parkStatus',
        'parkStatus' => 'parkStatus',
        'cityId' => 'cityId',
        'distId' => 'distId',
        'regId' => 'regId',
        'buildYear' => 'parkBuildYear',
        'houseBuildYear' => 'parkBuildYear',
        'buildType' => 'parkBuildType',
        'parkBuildType' => 'parkBuildType',
        "subwayLine" => 'parkSubwayLine',
        "parkSubwayLine" => 'parkSubwayLine',
        "subwaySite" => 'parkSubwaySite',
        "parkSubwaySite" => 'parkSubwaySite',
        "subwaySiteLine" => 'parkSubwaySiteLine',
        "parkSubwaySiteLine" => 'parkSubwaySiteLine',
        "aroundSchool" => 'parkAroundSchool',
        "parkAroundSchool" => 'parkAroundSchool',
        "salePrice" => "parkSalePrice",
        "parkSalePrice" => "parkSalePrice",
        "saleValid" => "parkSaleValid",
        "parkSaleValid" => "parkSaleValid",
        "rentValid" => "parkRentValid",
        "parkRentValid" => "parkRentValid",
        's_keyword' => 's_keyword',
        "multiFields" => 'multiFields',
    );

    function __construct($param = array()) {
        if (isset($param['index']) && $param['index']) {
            $this->_index = $param['index'];
            unset($param['index']);
        }
        if (isset($param['type']) && $param['type']) {
            $this->_type = $param['type'];
            unset($param['type']);
        }
        $this->_connection = new Elasticsearch\Client($param);
    }

    public static function instance($param = array(), $cache = true) {
        if ($cache) {
            if (isset(self::$_instanceCache)) {
                return self::$_instanceCache;
            }
            self::$_instanceCache = new self($param);
            return self::$_instanceCache;
        } else {
            return new self($param);
        }
    }

    function setIndex($indexName) {
        if (!$indexName)
            return;
        $this->_index = $indexName;
    }

    function getIndex() {
        return $this->_index;
    }

    function setType($typeName) {
        if (!$typeName)
            return;
        $this->_type = $typeName;
    }

    function getType() {
        return $this->_type;
    }

    function get($pararms) {
        
    }

    //
    function createIndex($indexName) {
        $this->_connection->indices()->create(array('index' => $indexName));
    }

    function deleteIndex($indexName) {
        $this->_connection->indices()->delete(array('index' => $indexName));
    }

    function createMapping($arMapping) {
        $param['index'] = $this->_index;
        $param['type'] = $this->_type;
        if ($this->existsType($param))
            return false;
        $param['body'][$this->_type] = $arMapping;
        try {
            $this->_connection->indices()->putMapping($param);
        } catch (Exception $e) {
            return false;
        }
    }

    function existsType($param) {
        try {
            return $this->_connection->indices()->existsType($param);
        } catch (Exception $e) {
            return false;
        }
    }

    function update($param) {
        if (!$param || !is_array($param) || !isset($param['id']) || !isset($param['data']) || !$param['data'] || !is_array($param['data']))
            return false;
        $updateParam = $this->getTypeIndex($param);

        $updateParam['id'] = $param['id'];
        unset($param['id']);
        $updateParam['body']['doc'] = $param['data'];
        try {
            return $this->_connection->update($updateParam);
        } catch (Exception $e) {
            //print_r($e->getMessage());
            return false;
        }
    }

    public function insert($param) {
        if (!$param || !is_array($param) || empty($param['id']))
            return false;
        $insertData = $this->getTypeIndex($param);
        if (isset($param['id'])) {
            if ($param['id']) {
                $insertData['id'] = $param['id'];
            }
            unset($param['id']);
        }
        $insertData['body'] = $param;
        try {
            return $this->_connection->index($insertData);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //有问题
    public function bulk($param) {
        if (!$param || !is_array($param))
            return false;
        $insertData = $this->getTypeIndex($param);
        $insertData['body'] = $param;
        try {
            return $this->_connection->bulk($insertData);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function delete($param = array()) {
        if (!$param || !is_array($param) || !isset($param['id']) || !$param['id'])
            return false;
        $deleteParam = $this->getTypeIndex($param);

        $deleteParam['id'] = $param['id'];
        try {
            return $this->_connection->delete($deleteParam);
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteByQuery($param) {
        if (!$param || !is_array($param))
            return false;
        $deleteParam = $this->getTypeIndex($param);
        if (isset($param['where']) && $param['where'] && is_array($param['where'])) {
            $arQuery = $this->buildCondition($param['where']);
            if ($arQuery) {
                $deleteParam['body'] = $arQuery;
            }
        } else {
            return false;
        }
        try {
            return $this->_connection->deleteByQuery($deleteParam);
        } catch (Exception $e) {
            return false;
        }
    }

    function search($param) {
        $searchParam = $this->getTypeIndex($param);
        if (isset($param['select']) && $param['select']) {
            $searchParam['fields'] = is_array($param['select']) ? join(',', $param['select']) : $param['select'];
        }

        if (isset($param['order']) && $param['order']) {
            $searchParam['sort'] = $param['order'];
        }
        if (isset($param['limit']) && $param['limit']) {
            if (is_array($param['limit'])) {
                $searchParam['from'] = count($param['limit']) == 2 ? $param['limit'][0] : 0;
                $searchParam['size'] = array_pop($param['limit']);
            } else if (is_numeric($param['limit'])) {
                $searchParam['from'] = 0;
                $searchParam['size'] = $param['limit'];
            }
        }
        if (isset($param['where']) && $param['where'] && is_array($param['where'])) {
            $arQuery = $this->buildCondition($param['where']);
            if ($arQuery) {
                $searchParam['body'] = $arQuery;
            }
        } else {
            $searchParam['body']['query'] = array("match_all" => array());
        }
        try {
            //  var_dump($searchParam);die();
            $info = $this->_connection->search($searchParam);
            if (!$info)
                return array('status' => '404');
            $info = $info['hits'];
            $data = array();
            foreach ($info['hits'] as $value) {
                $data[] = $value['_source'];
            }
            return array('total' => $info['total'], 'data' => $data, 'status' => '200');
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->_debug) {
                echo $e->getMessage();
            }
            return false;
        }
    }

    //
    function buildCondition($where) {
        if (!$where || !is_array($where))
            return false;
        //  $return['query']['bool'] = array();
        $arOperateor = array(">", ">=", "<", "<=");
        $rhOperateor = array(
            ">" => "gt",
            ">=" => "gte",
            "<" => "lt",
            "<=" => "lte"
        );
        $return = array();
        foreach ($where as $fieldName => $v) {
            if (is_numeric($v) || is_string($v)) {
                $return['query']['bool']['must'][] = array(
                    'term' => array(
                        $fieldName => trim($v)
                    )
                );
            } elseif (is_array($v)) {
                foreach ($v as $operator => $values) {
                    if (in_array(trim($operator), $arOperateor) && is_numeric($values)) {
                        $return['query']['bool']['must'][] = array(
                            "range" => array(
                                $fieldName => array(
                                    $rhOperateor[trim($operator)] => $values
                                )
                            )
                        );
                    } elseif (trim($operator) == 'in') {
                        $arValue = $values;
                        if (!is_array($values)) {
                            $arValue = array($values);
                        }
                        $return['query']['bool']['must'][] = array(
                            'terms' => array(
                                $fieldName => $arValue,
                                "minimum_should_match" => 1, //ֻҪ��1���Ϳ���
                            )
                        );
                    } elseif (trim($operator) == 'not in') {
                        $arValue = $values;
                        if (!is_array($values)) {
                            $arValue = array($values);
                        }
                        $return['query']['bool']['must_not'][] = array(
                            'terms' => array(
                                $fieldName => $arValue,
                            //  "minimum_should_match"  =>  count($arValue),//ֻҪ��1���Ϳ���
                            )
                        );
                    } elseif (trim($operator) == '!=' && is_numeric($values)) {
                        $return['query']['bool']['must_not'][] = array(
                            'term' => array(
                                $fieldName => trim($values)
                            )
                        );
                    } elseif (trim($operator) == 'like') {
                        if (is_string($values)) {
                            $return['query']['bool']['must'][] = array(
                                'match' => array(
                                    $fieldName => array(
                                        "query" => trim($values),
                                        "type" => "phrase",
                                    )
                                ),
                            );
                        } elseif (is_array($values)) {
                            $return['query']['bool']['must'][] = array(
                                'multi_match' => array(
                                    "query" => $values['query'],
                                    "fields" => $values['fields'],
                                    "type" => "phrase",
                                ),
                            );
                        }
                    } elseif (trim($operator) == 'minlike') {
                        if (is_string($values)) {
                            $return['query']['bool']['must'][] = array(
                                'match' => array(
                                    $fieldName => array(
                                        "query" => trim($values),
                                        "minimum_should_match" => 1,
                                    )
                                ),
                            );
                        }
                    } elseif (trim($operator) == 'likeand') {
                        if (is_array($values)) {
                            foreach ($values as $v) {
                                $return['query']['bool']['must'][] = array(
                                    'match' => array(
                                        $fieldName => array(
                                            "query" => $v,
                                            "type" => "phrase",
                                        )
                                    ),
                                );
                            }
                        }
                    } elseif (trim($operator) == 'likeor') {
                        //请不要改成类似likeand的形式
                        if (is_array($values)) {
                            $return['query']['bool']['must'][] = array(
                                "query_string" => array(
                                    "fields" => array($fieldName),
                                    "query" => join(' OR ', $values),
                                    "auto_generate_phrase_queries" => true
                                )
                            );
                        }
                    } elseif (trim($operator) == 'prefix' && is_string($values)) {
                        $return['query']['bool']['must'][] = array(
                            'prefix' => array(
                                $fieldName => trim($values)
                            )
                        );
                    } else {
                        $arValue = $values;
                        if (!is_array($values)) {
                            $arValue = array($values);
                        }
                        $return['query']['bool']['must'][] = array(
                            'terms' => array(
                                $fieldName => $arValue,
                                "minimum_should_match" => 1, //ֻҪ��1���Ϳ���
                            )
                        );
                    }
                }
            }
        };
        // var_dump($return);exit;
        return $return;
    }

    public function index($param) {
        try {
            $this->_connection->index($param);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getTypeIndex(&$param) {
        if (!isset($param['index']) || !$param['index']) {
            $return['index'] = $this->_index;
        } else {
            $return['index'] = $param['index'];
            unset($param['index']);
        }

        if (!isset($param['type']) || !$param['type']) {
            $return['type'] = $this->_type;
        } else {
            $return['type'] = $param['type'];
            unset($param['type']);
        }
        return $return;
    }

    public function close() {
        
    }

    //格式化房源数据
    public function houseFormat($arrData, $flag = 1) {
        //房源ID

        if (!$arrData)
            return false;

        $arrHouse = array();
        foreach ($arrData as $key => $value) {
            if (isset($this->_rhNameMap[$key])) {
                $arrHouse[$this->_rhNameMap[$key]] = $value;
            }
        }
        if ($flag == 1) {
            //索引编号
            if (empty($arrData['id']) && !empty($arrData['houseId'])) {
                $arrHouse['id'] = intval($arrData['houseId']);
            }
            //房屋总价
            if (isset($arrData['price'])) {
                $arrHouse['housePrice'] = intval($arrData['price']);
            }
            if (isset($arrHouse['housePrice'])) {
                $arrHouse['housePrice'] = intval($arrHouse['housePrice']);
            }
            //建筑面积
            if (isset($arrData['bA'])) {
                $arrHouse['houseBA'] = intval($arrData['bA']);
            }
            //房屋单价
            if (isset($arrData['unit'])) {
                $arrHouse['houseUnit'] = intval($arrData['unit']);
            }
            //创建时间
            if (isset($arrData['create'])) {
                $arrHouse['houseCreate'] = $arrData['create'] == '0000-00-00 00:00:00' ? 0 : strtotime($arrData['create']);
            }
            //更新时间
            if (isset($arrData['houseUpdate'])) {
                $arrHouse['houseUpdate'] = $arrData['houseUpdate'] == '0000-00-00 00:00:00' ? 0 : strtotime($arrData['houseUpdate']);
            }
            //刷新时间
            if (!empty($arrData['refreshTime'])) {
                $arrHouse['houseRefreshTime'] = $arrData['refreshTime'] == '0000-00-00 00:00:00' ? 0 : strtotime($arrData['refreshTime']);
            } elseif (!empty($arrHouse['houseCreate'])) {
                $arrHouse['houseRefreshTime'] = $arrHouse['houseCreate']; //新发布房源没有刷新时间时默认以发布时间为值
            }
            //下架时间
            if (isset($arrData['xiajia'])) {
                $arrHouse['houseXiajia'] = $arrData['xiajia'] == '0000-00-00 00:00:00' ? 0 : strtotime($arrData['xiajia']);
            }
            //下架时间
            if (isset($arrData['houseXiajia'])) {
                $arrHouse['houseXiajia'] = $arrData['houseXiajia'] == '0000-00-00 00:00:00' ? 0 : strtotime($arrData['houseXiajia']);
            }
            //标签时间
            if (isset($arrData['tagTime'])) {
                $arrHouse['houseTagTime'] = $arrData['tagTime'] == '0000-00-00 00:00:00' ? 0 : strtotime($arrData['tagTime']);
            }
//                //违规原因
//                if ( isset($arrData['failure']) ) {
//                    $arrHouse['failure'] = intval($arrData['failure']);
//                }
            //小区周边的地铁线路及地铁站点
            if (isset($arrData['parkId'])) {
                //小区地址
                $arrPark = Park::instance()->getParkById($arrData['parkId']);
                $arrHouse['houseAddress'] = isset($arrPark['address']) ? $arrPark['address'] : '';
                $arrParkExt = ParkExt::Instance()->getParkExtByIdName($arrData['parkId'], '周边地铁线路');
                $arrHouse['subwayLine'] = isset($arrParkExt['value']) ? $arrParkExt['value'] : '';
                $arrParkExt = ParkExt::Instance()->getParkExtByIdName($arrData['parkId'], '周边地铁站点');
                $arrHouse['subwaySite'] = isset($arrParkExt['value']) ? $arrParkExt['value'] : '';
                $arrParkExt = ParkExt::Instance()->getParkExtByIdName($arrData['parkId'], '周边地铁站点串');
                $arrHouse['subwaySiteLine'] = isset($arrParkExt['value']) ? $arrParkExt['value'] : '';
                $arrParkExt = ParkExt::Instance()->getParkExtByIdName($arrData['parkId'], '周边学校');
                $arrHouse['aroundSchool'] = isset($arrParkExt['value']) ? $arrParkExt['value'] : '';
            }
            $arrHouse['houseRoleType'] = isset($arrData['roleType']) ? intval($arrData['roleType']) : 1;
        }

        return $arrHouse;
    }

    //格式化小区数据
    public function parkFormat($arrData, $flag = 1) {
        if (!$arrData)
            return false;

        $arrPark = array();
        foreach ($arrData as $key => $value) {
            if (isset($this->_parkNameMap[$key])) {
                $arrPark[$this->_parkNameMap[$key]] = $value;
            }
        }

        return $arrPark;
    }

}
