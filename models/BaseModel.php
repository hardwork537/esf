<?php

/**
 * Model基类
 */
class BaseModel extends Phalcon\Mvc\Model {

    private static $_instanceCache;

    public function __set($name, $value) {
        if (method_exists($this, "set" . ucfirst($name))) {
            $this->{"set" . ucfirst($name)}($value);
        }
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }

    public function __get($name) {
        if (method_exists($this, "get" . ucfirst($name))) {
            $this->{"get" . ucfirst($name)}();
        }
        if (property_exists($this, $name)) {

            return $this->{$name};
        }
        return false;
    }

    public function __call($name, $arguments = null) {
        $propertyName = lcfirst(str_replace(array(
            'set',
            'get'
                        ), '', $name));
        if (property_exists($this, $propertyName)) {
            if (strpos($name, 'set') === 0) {
                $this->{$name} = $arguments[0];
            } else {
                return $this->{$name};
            }
        } else {
            return false;
        }
    }

    /**
     * 设置数据库连接
     *
     * @param string $type
     *            esf|crm|weixin|cms|vip|admin
     */
    protected function setConn($type = 'wx') {
        $this->setWriteConnectionService($type . 'Master');
        $this->setReadConnectionService($type . 'Slave');
    }

    static public function findFirst($param = null, $objectFlag = 1) {
        if ($objectFlag)
            return parent::findFirst($param);
        $info = parent::findFirst($param);
        return $info ? $info : new obj();
    }

    static public function find($param = null, $objectFlag = 1) {
        if ($objectFlag)
            return parent::find($param);
        $info = parent::find($param);
        return $info ? $info : new obj();
    }

    /**
     * 获取多条记录集合
     *
     * @param array $where
     * @param string $order
     * @param number $offset
     * @param number $limit
     * @param string $select
     * @param number $cachetime
     * @param string $cachekey
     * @return []
     */
    public static function getAll($where = null, $order = "", $offset = 0, $limit = 0, $select = "", $cachetime = 0, $cachekey = "") {
        $con = self::packageCon($where, $order, $offset, $limit, $select, $cachetime, $cachekey);
        $rs = self::find($con);
        return $rs->toArray();
    }

    public static function getOne($where = null, $toArray = true) {
        $con = self::packageCon($where);
        $rs = self::findfirst($con);
        if ($rs && $toArray === true) {
            return $rs->toArray();
        }

        return $rs;
    }

    /**
     * 获取记录集条数
     *
     * @param array $where
     * @return number
     */
    public static function getCount($where = null) {
        $con = self::packageCon($where);
        return self::count($con);
    }

    /**
     * 批量删除数据
     *
     * @param unknown $where
     * @return boolean
     */
    public function deleteAll($where) {
        $con = self::packageCon($where);
        return $this->getWriteConnection()->delete($this->getSource(), $con['conditions']);
    }

    /**
     * 批量更新数据
     *
     * @param unknown $where
     * @param unknown $data
     * @return boolean
     */
    public function updateAll($where, $data) {
        if (empty($data)) {
            return false;
        }
        $con = self::packageCon($where);
        $columns = array_keys($data);
        $values = array_values($data);
        return $this->getWriteConnection()->update($this->getSource(), $columns, $values, $con['conditions']);
    }

    /**
     * 批量插入数据
     * @auth jackchen zhichengchen@sohu-inc.com
     * @param array $values exp:array(1=>'rlTime',2=>'realId') //实际表中字段
     * @param array $fileds
     * @return boolean
     */
    public function insertAll($values, $fileds) {
        return $this->getWriteConnection()->insert($this->getSource(), $values, $fileds);
    }

    /**
     * 执行不需要返回的SQL(增删改)
     *
     * @param string $sql
     * @return boolean
     */
    public function execute($sql) {
        return $this->getWriteConnection()->execute($sql);
    }

    public function insertId() {
        return $this->getWriteConnection()->lastInsertId();
    }

    public function affectedRows() {
        return $this->getWriteConnection()->affectedRows();
    }

    public function fetchOne($sql, $mode = Phalcon\Db::FETCH_ASSOC) {
        return $this->getReadConnection()->fetchOne($sql, $mode);
    }

    public function fetchAll($sql, $mode = Phalcon\Db::FETCH_ASSOC) {
        return $this->getReadConnection()->fetchAll($sql, $mode);
    }

    public function begin() {
        $this->getWriteConnection()->begin();
    }

    public function rollback() {
        $this->getWriteConnection()->rollback();
    }

    public function commit() {
        $this->getWriteConnection()->commit();
    }

    /**
     * Sql拼装
     *
     * @param string $where
     * @param string $order
     * @param number $offset
     * @param number $limit
     * @param string $select
     * @param number $cachetime
     * @param string $cachekey
     * @return multitype:string multitype:string number multitype:number
     */
    public static function packageCon($where = null, $order = "", $offset = 0, $limit = 0, $select = "", $cachetime = 0, $cachekey = "") {
        $con = array();
        if (!is_null($where)) {
            if (is_array($where)) {
                $con['conditions'] = implode(" and ", $where);
            } elseif (is_object($where)) {
                $con['conditions'] = implode(" and ", (array) $where);
            } else {
                $con['conditions'] = $where;
            }
        }
        $limit = intval($limit);
        $offset = intval($offset);
        if ($limit > 0) {
            $con['limit'] = array(
                'number' => $limit,
                'offset' => $offset
            );
        }
        if (!empty($order)) {
            $con['order'] = $order;
        }
        if (!empty($select)) {
            $con['columns'] = $select;
        }
        if ($cachetime > 0 && !empty($cachekey)) {
            $con['cache'] = array(
                "lifetime" => intval($cachetime),
                'key' => $cachekey
            );
        }
        return $con;
    }

    /**
     * 单列化对象
     *
     * @param class $className
     *            实例化的类名
     * @param type $cache
     *            是否缓存
     * @return object
     */
    protected static function _instance($className, $cache = false) {
        if (true == $cache) {
            if (isset(self::$_instanceCache[$className]) && is_subclass_of(self::$_instanceCache[$className], __CLASS__)) { // 已经实例化过了
                return self::$_instanceCache[$className];
            } elseif (class_exists($className)) { // 没有实例化而且存在这个类
                self::$_instanceCache[$className] = new $className();
                return self::$_instanceCache[$className];
            }
        } else {
            return new $className();
        }

        throw new Exception($className . '类不存在.');
    }

    // 数据库字段为NOT NULL时框架可不对其进行校验
    public function onConstruct() {
        parent::setup(array(
            'notNullValidations' => false
        ));
    }

    /**
     * @abstract 构造绑定多个参数的方式
     * @author Eric xuminwan@sohu-inc.com
     * @param array $params
     * @return array|bool
     *
     */
    public function bindManyParams($params) {
        if (!is_array($params))
            return false;
        $strCond = '';
        $startNum = 1;
        $arrParam = array();
        foreach ($params as $value) {
            $strCond .= '?' . $startNum . ',';
            $arrParam[$startNum] = $value;
            $startNum++;
        }
        $strCond = rtrim($strCond, ',');
        return array('cond' => $strCond, 'param' => $arrParam);
    }

    /**
     * @abstract 获取查询数据，根据条件(单表)
     * @author jackchen  zhichengchen@sohu-inc.com
     * @param  string $modelname
     * @param  string|array  $columns  expamle: '*'|'id'|array('id', 'name')
     * @param string $whereStr  expamle:id = :id: and name = :name:
     * @param array  $WhereVal  expamle:  array('id' => $id,'name' =>$name)
     * @param array $inWhereArr expamle:$inWhereArr = array('id'=>array(1,2),'name'=>array('11','22'));
     * @param string $orderby  expamle:'id desc'|'id asc'
     * @param string|array  $groupby   expamle:'id'|array('id', 'name')
     * @param string $having expamle:'SUM(price) > 1000'
     * @param int $offset expamle:1
     * @param int $limit expamle:1
     * @param array $join  expamle:  array($model,$conditions,$alias,'inner'|'left'|'right')
     * @return array|bool 二维数组
     */
    public function getSelectData($modelname, $columns, $whereStr, $WhereVal, $inWhereArr = array(), $orderby = '', $groupby = '', $having = '', $offset = 0, $limit = 0, $join = array()) {
        $robots = self::getModelsManager()->createBuilder();
        $robots->from($modelname);
        if (!empty($join))
            $robots->join($join['model'], $join['conditions'], $join['alias'], $join['join']);
        $robots->columns($columns);
        $robots->where($whereStr, $WhereVal);

        if (is_array($inWhereArr) && count($inWhereArr) > 0) {
            foreach ($inWhereArr as $key => $value) {
                $robots->inWhere($key, $value);
            }
        }

        if (!empty($orderby)) {
            $robots->orderBy($orderby);
        }

        if (!is_array($groupby) && !empty($groupby)) {
            $robots->groupBy($groupby);
        } else {
            if (count($groupby) > 0 && !empty($groupby)) {
                $robots->groupBy($groupby);
            }
        }

        if (!empty($having)) {
            $robots->having($having);
        }

        if ($limit > 0) {
            if ($offset > 0) {
                $robots->limit($offset . ',' . $limit);
            } else {
                $robots->limit($limit);
            }
        }

        $data = $robots->getQuery()->execute()->toArray();
        //查询返回空
        if (count($data) == 0) {
            $data = false;
        }
        return $data;
    }

    //根据数组返回find的conditions和bind
    //param=>array(
    //  'xx'    =>  xxx,
    //  'xx'    =>  array('>'=>xx),
    //  'xx'    =>  not in |in 不支持,哈哈
    //)
    public function getConditionByParam($param) {
        if (!$param || !is_array($param))
            return array();

        $arrCondition = array();
        $conditions = '1 ';
        foreach ($param as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($k == 'in' || $k == 'not in')
                        continue;
                    if (is_string($v)) {
                        $conditions .= "and $key" . $k . "':" . $key . ":' ";
                    } else {
                        $conditions .= "and $key" . $k . ":$key:" . " ";
                    }
                    $arrCondition['bind'][$key] = $v;
                }
            } else {
                if (is_string($value)) {
                    $conditions .= "and " . $key . "='" . ":$key:" . "' ";
                } else {
                    $conditions .= "and " . $key . "=" . ":$key:" . " ";
                }
                $arrCondition['bind'][$key] = $value;
            }
        }
        $arrCondition['conditions'] = $conditions;
        return $arrCondition;
    }

    public function getBuilder($param) {
        if (!$param || !is_array($param))
            return false;

        $builder = $this->getModelsManager()->createBuilder();
        $modelName = ucfirst(get_class($this));
        $builder->from($modelName);
        $builder->where(1);


        foreach ($param as $key => $value) {
            if (is_int($value)) {
                $builder->andWhere($modelName . "." . $key . "=:$key:", array($key => $value));
            } elseif (is_string($value)) {
                $builder->andWhere($modelName . "." . $key . "=':$key:'", array($key => $value));
            } elseif (is_array($value)) {
                $overFlag = false;
                $types = array('<', '<=', '>', '>=');
                $keys = array_keys($value);
                $i = 0;
                foreach ($keys as $k) {
                    $i++;
                    if (in_array($k, $types, true)) {
                        $overFlag = true;
                        $builder->andWhere($modelName . "." . $key . "$k:$key" . $i . ":", array($key . $i => $value[$k]));
                    } elseif ($k === '!=') {
                        $overFlag = true;
                        if (is_array($value[$k])) {
                            $builder->notInWhere($modelName . "." . $key, $value[$k]);
                        } elseif (is_int($value[$k])) {
                            $builder->andWhere($modelName . "." . $key . "!=:$key:", array($key => $value[$k]));
                        } elseif (is_string($value[$k])) {
                            $builder->andWhere($modelName . "." . $key . "=':$key:'", array($key => $value[$k]));
                        }
                    }
                }
                if ($overFlag)
                    continue;
                $overFlag = true;
                ;
                $builder->inWhere($modelName . "." . $key, $value);
            }
        }
        return $builder;
    }

}
