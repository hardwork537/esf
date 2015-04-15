<?php

use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;

class ModelBase extends \Phalcon\Mvc\Model
{

    private static $_instanceCache;
    protected $_rtn = [ "status" => 0, "info" => "success" ];
    public $mdb = null;
    public $sdb = null;
    public $tbname = null;
    public $transaction1 = null;

    /**
     * 设置数据库连接
     * @param type $type
     */
    protected function setConn($type = 'www')
    {
        $this->setWriteConnectionService($type.'Master');
        $this->setReadConnectionService($type.'Slave');
    }

    protected function setDB()
    {
        $this->mdb    = $this->getWriteConnection();
        $this->sdb    = $this->getReadConnection();
        $this->tbname = $this->getSource();
    }

    public static function unsetVar(&$obj)
    {
        unset($obj->mdb, $obj->sdb, $obj->tbname);
    }

    public function onConstruct()
    {
        $this->setDB();
    }

    public function begin()
    {
        $this->mdb->begin();
    }

    public function rollback()
    {
        $this->mdb->rollback();
    }

    public function commit()
    {
        $this->mdb->commit();
    }

    public function updateAll($where, $data)
    {
        if(empty($data))
        {
            return false;
        }

        $con = self::packageCon($where);

        $columns = array_keys($data);
        $values  = array_values($data);

        return $this->mdb->update(
            $this->tbname, $columns, $values, $con['conditions']
        );
    }

    public function deleteAll($where)
    {
        $con = self::packageCon($where);
        $rs  = self::find($con);

        return $rs->delete();
    }

    public static function getAll($where = null, $order = "", $offset = 0, $limit = 0, $select = "", $cachetime = 0, $cachekey = "")
    {
        $con = self::packageCon($where, $order, $offset, $limit, $select, $cachetime, $cachekey);
        $rs  = self::find($con)->toArray();
        return $rs;
    }

    public static function getOne($where = null, $toArray = true)
    {
        $con = self::packageCon($where);
        $rs  = self::findfirst($con);
        if($toArray === true)
        {
            return $rs->toArray();
        }
        return $rs;
    }

    public static function getCount($where = null)
    {
        $con = self::packageCon($where);
        return self::count($con);
    }

    /**
     * phql方式执行
     * @param type $sql
     * @return type

    public  function execute($sql){
     * $rs = $this->getDI()->getPhql()->executeQuery($sql);
     * return $rs;
     * }
     */

    /**
     * 执行不需要返回的SQL(增删改)
     * @param type $sql
     * @return type
     */
    public function execute($sql)
    {
        return $this->mdb->execute($sql);
    }

    public function insertId()
    {
        return $this->mdb->lastInsertId();
    }

    public function fetchOne($sql)
    {
        return $this->sdb->fetchOne();
    }

    public function fetchAll($sql)
    {
        return $this->sdb->fetchAll();
    }

    public static function packageCon($where = null, $order = "", $offset = 0, $limit = 0, $select = "", $cachetime = 0, $cachekey = "")
    {
        $con = [ ];
        if(!is_null($where))
        {
            if(is_array($where))
            {
                $con['conditions'] = implode(" and ", $where);
            }
            elseif(is_object($where))
            {
                $con['conditions'] = implode(" and ", (array)$where);
            }
            else
            {
                $con['conditions'] = $where;
            }
        }
        $limit  = intval($limit);
        $offset = intval($offset);
        if($limit > 0)
        {
            $con['limit'] = [ 'number' => $limit, 'offset' => $offset ];
        }
        if(!empty($order))
        {
            $con['order'] = $order;
        }
        if(!empty($select))
        {
            $con['columns'] = $select;
        }
        if($cachetime > 0 && !empty($cachekey))
        {
            $con['cache'] = [ "lifetime" => intval($cachetime), 'key' => $cachekey ];
        }
        return $con;
    }

    /**
     * 实例化对象
     * @param class $className 实例化的类名
     * @param type $cache 是否缓存
     * @return object
     */
    protected static function _instance($className, $cache = false)
    {
        if(true == $cache)
        {
            if(isset(self::$_instanceCache[$className]) &&
                is_subclass_of(self::$_instanceCache[$className], __CLASS__)
            )
            { //已经实例化过了
                return self::$_instanceCache[$className];
            }
            elseif(class_exists($className))
            { //没有实例化而且存在这个类
                self::$_instanceCache[$className] = new $className();
                return self::$_instanceCache[$className];
            }
        }
        else
        {
            return new $className();
        }

        throw new Exception($className.'_model类不存在.');
    }

    /**
     * 返回特定自定义的数组
     * @return type
     */
    public function success($str = "")
    {
        if($str)
        {
            $this->_rtn['info'] = $str;
        }
        return $this->_rtn;
    }

    /**
     * 返回错误信息数组
     * @return type
     */
    public function error($str = "")
    {
        $this->_rtn['status'] = 1;
        if($str)
        {
            $this->_rtn['info'] = $str;
        }
        return $this->_rtn;
    }

}
