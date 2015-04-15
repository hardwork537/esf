<?php
/**
 * Memcache处理类
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014-09-11
 */

class Mem {
	private $mCache = null;
	static private $mInstance = array();

	/**
	 * 构造方法
	 *
	 * @param array $cluster 集群名称
	 */
	public function __construct($cluster=array()) {
	    if (empty($cluster)) {
			global $sysMemcache;
	    	$cluster = $sysMemcache;
	    }
		$this->mCache = new MemCache();
		foreach ($cluster AS $one) {
			$this->mCache->addserver($one['host'], $one['port'], FALSE, $one['weight'], 1, 15, true, array('Mem','FailureCallback'));
		}
		$this->mCache->setCompressThreshold(5000); // > 5k then zlib
	}

	/**
	 * memcache实例
	 *
	 * @param array $cluster 集群名称
	 * @return object Instance memcache实例
	 */
	public static function Instance($cluster=array()) {
	    $cluster_key = md5(serialize($cluster));
		if ( isset( self::$mInstance[ $cluster_key ] ) ) return self::$mInstance[ $cluster_key ];
		return self::$mInstance[ $cluster_key ] = new Mem($cluster);//注意请不要把这里Mem改成MemCache，这里是用类名
	}

	/**
	 * memcache关闭连接
	 */
	public function close() {
		$this->mCache->close();
		$keys = array_keys(self::$mInstance);
        if ($keys){
        	foreach ($keys as $key){
            	unset(self::$mInstance[$key]);
            }
       	}
	}

	/**
	 * 错误处理
	 *
	 * @param string $ip ip地址
	 * @param string $port 端口
	 */
	public static function FailureCallback($ip, $port) {
		error_log( 'connect memcache fail: '.$ip.':'.$port );
	}

	/**
	 * 取值
	 *
	 * @param string $key 唯一key
	 * @return mixed 多类型值
	 */
	public function Get($key) {
		if(empty($key)){
			return false;
		}
		return $this->mCache->get($key);
	}

    /**
     * 添加值
     *
     * @param string $key 唯一key
     * @param mixed $var 多类型值
     * @param bool $flag 是否压缩
     * @param int $expire 过期时间
     * @return mixed 多类型值
     */
	public function Add($key, $var, $expire=0, $flag=0) {
		return $this->mCache->add(trim($key),$var,$flag,$expire);
	}

    /**
     * 自增id号递减
     *
     * @param string $key 唯一key
     * @param int $value id号递减值
     * @return bool
     */
	public function Dec($key, $value=1) {
		return $this->mCache->decrement(trim($key), $value);
	}

    /**
     * 自增id号递增
     *
     * @param string $key 唯一key
     * @param int $value id号递增值
     * @return bool
     */
	public function Inc($key, $value=1) {
		return $this->mCache->increment(trim($key), $value);
	}

	/**
	 * 更新值
	 *
	 * @param string $key 唯一key
	 * @param mixed $var 多类型值
	 * @param bool $flag 是否压缩
	 * @param int $expire 过期时间
	 * @return bool
	 */
	public function Replace($key, $var, $expire=0, $flag=0) {
		return $this->mCache->replace(trim($key), $var, $flag, $expire);
	}

    /**
     * 设置值
     *
     * @param string $key 唯一key
     * @param mixed $var 多类型值
     * @param bool $flag 是否压缩
     * @param int $expire 过期时间
     * @return bool
     */
	public function Set($key, $var, $expire=0, $flag=0) {
		return $this->mCache->set(trim($key), $var,$flag,$expire);
	}

	/**
     * 删除值
     *
     * @param mixed $key key值(字符串或数组)
     * @param int $timeout 过期时间
     * 
     * ps:删除时不带timeout,在使用代理时，带着timeout参数会报“Invalid  argument”
     */
	public function Del($key, $timeout=0) {
		if (is_array($key)) {
			foreach ($key as $k) $this->mCache->delete($k);
		} else {
			$this->mCache->delete($key);
		}
	}

	/**
	 * 清理所有cache
	 *
	 * @return bool
	 */

	public static function Flush() {
		self::Instance();
		return self::Instance()->mCache->flush();
	}
}
?>