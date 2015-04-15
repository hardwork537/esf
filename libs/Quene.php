<?php
/**
 * Beanstalkd队列处理类
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/10/5
 */

class Quene {
	private $mQuene = null;
	private $mTube = array('test', 'house', 'refresh', 'realtor', 'monitor');//队列组名
	private $mAction = array(
		'test' => array('test'),//仅测试使用
		'house' => array('create', 'update', 'delete', 'online', 'offline', 'fine', 'tags', 'image'),
		'refresh' => array('refresh'),
		'realtor' => array('stop', 'score'),
		'monitor' => array('action'),
	);//队列任务名
	static private $mInstance = array();

	/**
	 * 构造方法
	 *
	 * @param array $client 队列名称
	 */
	public function __construct($client=array()) {
	    if (empty($cluster)) {
			global $sysQuene;
	    	$client = $sysQuene['default'];
	    }
		$this->mQuene = new Phalcon\Queue\Beanstalk($client);
	}

	/**
	 * quene实例
	 *
	 * @param array $cluster 集群名称
	 * @return object Instance quene实例
	 */
	public static function Instance($cluster=array()) {
	    $cluster_key = md5(serialize($cluster));
		if ( isset( self::$mInstance[ $cluster_key ] ) ) return self::$mInstance[ $cluster_key ];
		return self::$mInstance[ $cluster_key ] = new Quene($cluster);
	}
	
	/**
	 * quene关闭连接
	 */
	public function Close() {
		$this->mQuene->disconnect();
		$keys = array_keys(self::$mInstance);
        if ($keys){
        	foreach ($keys as $key){
            	unset(self::$mInstance[$key]);
            }
       	}
	}

	/**
	 * 读取队列
	 *
	 * @param string $tube 唯一tube
	 * @param bool $del 是否读取即删除
	 * @return mixed 多类型值
	 */
	public function Get($tube, $del = true) {
		if (empty($tube) || !in_array($tube, $this->mTube)) {
			return false;
		}
		$this->mQuene->choose($tube);
		$this->mQuene->watch($tube);//设置要监听的tube
		while (true) {
			$job = $this->mQuene->reserve();//获取任务，此为阻塞获取，直到获取有用的任务为止
			$result = $job->getBody();
			if ($result && $del) $job->delete($job->getId());//删除任务
			call_user_func('__callback', $result);//回调函数处理任务
		}
		$this->mQuene->disconnect();
	}

    /**
     * 写入队列
     *
     * @param string $tube 唯一tube
     * @param mixed $var 多类型值
     * @param array $opt 可选项，如array('priority' => 250, 'delay' => 10, 'ttr' => 3600)
     * @return bool
     */
	public function Put($tube, $var, $opt = array()) {
		if (empty($tube) || !in_array($tube, $this->mTube)) {
			return false;
		}
		if (empty($var['action']) || !in_array($var['action'], $this->mAction[$tube])) {
			return false;
		}
		$this->mQuene->choose($tube);
		return $this->mQuene->put($var, $opt);
	}
}
?>