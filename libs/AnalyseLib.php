<?php
/**
 * 搜索原始数据分析与存储。
 * 通过分析搜索数据，将每条记录分析结果记入数据库。
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/11/10
 */

class AnalyseLib
{
	const FUNC_BREAK = 'break';
	const FUNC_CONTINUE = 'continue';
	const PREFIX_PARSE = '_parse';
	const PREFIX_SAVE = '_save';

	/**
	 * 需要被处理的站点。
	 *
	 * @var array
	 */
	public $sites = array();

	/**
	 * 需要被处理的城区信息。
	 *
	 * @var array
	 */
	public $district = array();

	/**
	 * 需要被处理的板块信息。
	 *
	 * @var array
	 */
	public $region = array();

	/**
	 * 调试。
	 *
	 * @var bool
	 */
	public $debug = false;

	/**
	 * Apache 日志文件正则表达式。
	 *
	 * @var string
	 */
	//public $pattern = '/^(?P<ip>[0-9.]+)[^\[]+\[(?P<time>[^\]]+)\] [0-9.]+ "GET (?P<url>[^ ]+) HTTP\/1.[1|0|2]" [0-9.]+ [0-9.-]+ "(?P<refer>[^"]+)" "(?P<agent>[^"]+)"/i';
	public $pattern = '/^"([0-9., -]+)" - (?P<ip>[0-9.]+)[^\[]+\[(?P<time>[^\]]+)\] [0-9.]+ "GET (?P<url>[^ ]+) HTTP\/1.[1|0|2]" [0-9.]+ [0-9.-]+ "(?P<refer>[^"]+)" "(?P<agent>[^"]+)"/i';

	/**
	 * 日志文件根目录。
	 *
	 * @var string
	 */
	private $_rootPath;

	/**
	 * 处理某天数据，默认为time()的前一天。
	 *
	 * @var string
	 */
	private $_date;

	/**
	 * 某天所有 Apache 日志路径数组，并按城市分组。
	 *
	 * @var array
	 */
	private $_logFiles;

	/**
	 * 保存处理后的数据。
	 *
	 * @var array
	 */
	private $_data = array();

	/**
	 * 解析函数名。
	 *
	 * @var array
	 */
	private $_parsers = array();

	/**
	 * 存储函数名。
	 *
	 * @var array
	 */
	private $_savers = array();

	/**
	 * 是否收集解析器。
	 *
	 * @var bool
	 */
	private $_collected = false;

	public function __construct()
	{
	}

	public function trace($str, $replace = array())
	{
		if (!empty($replace)) {
			$str = strtr($str, $replace);
		}

		if ($this->debug) {
			echo trim($str), "\n";
		}
	}

	/**
	 * 入口函数。
	 *
	 * @return object
	 */
	public function run()
	{
		foreach ($this->sites as $site => $id) {
			$this->trace('--- START site {site} ---', array('{site}' => $site));
			$this->doSite($site);
			$this->trace('--- END site {site} ---', array('{site}' => $site));
		}

		return $this;
	}

	/**
	 * 清理过期数据。
	 *
	 * @param int $day 相对于 $date 天数
	 * @param string $date 如: 2011-11-08
	 * @return object
	 */
	public function complete($day = 7, $date = null)
	{
		if ($date === null) {
			$date = $this->getDate();
		}

		$time = date('Y-m-d H:i:s', strtotime($date) - $day * 86400);
		$cond = "logTime < '{$time}'";
		$clsAnalyse = new Analyse();
		$clsAnalyse->deleteAll($cond);
		$this->delLogFiles();
		return $this;
	}

	/**
	 * 按站点处理。
	 *
	 * @param string $site
	 * @return object
	 */
	public function doSite($site)
	{
		$files = $this->getLogFiles($site);
		foreach ($files as $file) {
			$this->trace('--- PARSE file {file} ---', array('{file}' => $file));
			$this->parseFile($file, $site);
		}
		$this->save($site);
		return $this;
	}
	
	/**
	 * 获取可用的站点
	 * 
	 * @param array $ignoreSites
	 * @author libo<neroli@sohu-inc.com>
	 */
	public function setSite($ignoreSites = array())
	{
		if(empty($ignoreSites)){
			$cond = '';
		}else{
			foreach($ignoreSites as &$ignore){
				$ignore = intval($ignore);
			}
			if(count($ignoreSites) == 1){
				$cond[] = "id <> {$ignoreSites[0]}";
			}else{
				$cond[] = "id NOT IN (".implode(',', $ignoreSites).")";
			}
		}
		$cond[] = "status = 1";
		$city = City::Instance()->getAll(implode(' AND ', $cond));
		foreach($city as $row){
			$this->sites[$row['pinyinAbbr']] = $row['id'];
		}
	}

	/**
	 * 数据存储入口。
	 *
	 * @param string $site
	 * @return object
	 */
	public function save($site)
	{
		$savers = $this->getSavers();
		foreach ($savers as $saver) {
			// 执行所有存储函数。
			// 此类中以 _save 开头的方法。
			$flag = call_user_func_array(array($this, $saver), array($site));
		}
		$this->_data = array();
		return $this;
	}

	/**
	 * 解析文件内容，并将结果存入数组。
	 *
	 * @param string $file 文件路径
	 * @return object
	 */
	public function parseFile($file, $site)
	{
		$fp = fopen($file, 'r');
		$parsers = $this->getParsers();
		$i = 0;
		while (($line = fgets($fp)) !== false) {
			//重新连接防止gone away
			global $di;
			$di->getShared('esfMaster')->connect();
			$di->getShared('esfSlave')->connect();

			$i++;
			$row = array();
			if (preg_match($this->pattern, $line, $row)) {
				$row['id'] = $i; // for debug

				if ($row['refer'] == '-') {
					continue;
				}

				// 对每行执行所有解析函数。
				// 此类中以 _parse 开头的方法。
				foreach ($parsers as $parser) {
					$flag = call_user_func_array(array($this, $parser), array($row, $site));
					if ($flag === self::FUNC_BREAK) {
						break;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * 获取解析器。
	 *
	 * @return array
	 */
	public function getParsers()
	{
		$this->_collect();
		return $this->_parsers;
	}

	/**
	 * 获取存储器。
	 *
	 * @return array
	 */
	public function getSavers()
	{
		$this->_collect();
		return $this->_savers;
	}

	/**
	 * 默认返回 time()前一天。格式 2011-11-04
	 *
	 * @return string
	 */
	public function getDate()
	{
		if ($this->_date === null) {
			$this->_date = date('Y-m-d', time() - 86400);
		}

		return $this->_date;
	}

	/**
	 * 设置解析哪天数据日期。
	 *
	 * @param string $date
	 * @return object
	 */
	public function setDate($date)
	{
		$this->_date = $date;
		return $this;
	}

	/**
	 * 设置日志文件根目录。
	 *
	 * @param string $path
	 * @return object
	 * @throws Exception
	 */
	public function setRootPath($path)
	{
		if (!($this->_rootPath = realpath($path))) {
			throw new Exception(strtr('Root path {path} is not exist.', array('{path}' => $this->_rootPath)));
		}

		return $this;
	}

	/**
	 * 获取日志文件根目录。
	 *
	 * @return string
	 */
	public function getRootPath()
	{
		return $this->_rootPath;
	}

	/**
	 * 按站点分组日志文件。
	 *
	 * @param string $site
	 * @return array
	 */
	public function getLogFiles($site = null)
	{
		if ($this->_logFiles === null) {
			$path = $this->getRootPath() . '/' . $this->getDate();
			if (!realpath($path)) {
				throw new Exception(strtr('Path {path} is not exist.', array('{path}' => $path)));
			}
			$files = scandir($path);
			$sites = array_keys($this->sites);
			foreach ($files as $file) {
				if ($file == '.' || $file == '..') {
					continue;
				}

				$prefix = substr($file, 0, strpos($file, '_'));
				if (in_array($prefix, $sites)) {
					$this->_logFiles[$prefix][] = $path . '/' .$file;
				}
			}
		}

		if ($site === null) {
			return $this->_logFiles;
		}

		return (array) @$this->_logFiles[$site];
	}

	/**
	 * 清理14天前的日志文件
	 *
	 * @return bool
	 */
	private function delLogFiles()
	{
		$path = $this->getRootPath();
		if (!realpath($path)) {
			throw new Exception(strtr('Path {path} is not exist.', array('{path}' => $path)));
		}
		$files = scandir($path);
		foreach ($files as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			if (is_dir($path . '/' .$file) && strtotime($file) > 0 && strtotime($file) < time() - 86400*7 ) {
				$this->delDir($path . '/' .$file);
			}
		}
		return true;
	}

	/**
	 * 删除文件夹及其文件夹下所有文件
	 *
	 * @param string $dir
	 * @return bool
	 */
	private function delDir($dir) {
		//先删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					$this->deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 收集解析与存储函数。
	 *
	 * @return object
	 */
	private function _collect()
	{
		if (!$this->_collected) {
			$ref = new ReflectionClass($this);
			$methods = $ref->getMethods(ReflectionMethod::IS_PRIVATE);
			$plen = strlen(self::PREFIX_PARSE);
			$slen = strlen(self::PREFIX_SAVE);
			foreach ($methods as $method) {
				if (strncmp(self::PREFIX_PARSE, $method->name, $plen) === 0) {
					$this->_parsers[] = $method->name;
				} elseif (strncmp(self::PREFIX_SAVE, $method->name, $slen) === 0) {
					$this->_savers[] = $method->name;
				}
			}
			$this->_collected = true;
		}

		return $this;
	}

	/**
	 * 批量写入分析数据
	 *
	 * @return object
	 */
	private function _insert($data)
	{
		if (empty($data) || empty($data[0])) {
			return $this;
		}
		foreach ($data as $param) {
			$clsAnalyse = new Analyse();
			$clsAnalyse->create($param);
		}

		return $this;
	}

	/* 当URL地址变更时，修改下面解析函数 */
	private function _parseSale($row, $site)
	{
		$pattern1 = '@^/sale/(?P<query> |[^/]+)@i';
		$match = array();

		if (!preg_match($pattern1, $row['url'], $match)) {
			return self::FUNC_CONTINUE;
		}

		$insert = $this->parseUrl($row, $site, 'sale');
		$this->_data[House::TYPE_ERSHOU][] = $insert;
		return self::FUNC_BREAK;
	}

	private function _parseRent($row, $site)
	{
		$pattern1 = '@^/rent/(?P<query> |[^/]+)@i';
		$match = array();
		if (!preg_match($pattern1, $row['url'], $match)) {
			return self::FUNC_CONTINUE;
		}        
		$insert = $this->parseUrl($row, $site, 'rent');
		$this->_data[House::TYPE_ZHENGZU][] = $insert;
        return self::FUNC_BREAK;
	}

	private function _parsePark($row, $site)
	{
		$pattern1 = '@^/xiaoqu/(?P<query> |[^/]+)@i';
		$match = array();
		if (!preg_match($pattern1, $row['url'], $match)) {
			return self::FUNC_CONTINUE;
		}        
		$insert = $this->parseUrl($row, $site, 'park');
		$this->_data[0][] = $insert;
        return self::FUNC_BREAK;
	}

	private function _saveSale($site)
	{
		$start = strtotime($this->getDate());
		$end = $start + 86400;
		$start = date('Y-m-d H:i:s', $start);
		$end = date('Y-m-d H:i:s', $end);
		$type = House::TYPE_ERSHOU;
		if (empty($this->_data[$type])) {
			return false;
		}
		$cond = "houseType = {$type} "
			 . "AND cityId = {$this->sites[$site]} "
			 . "AND logTime >= '{$start}' AND logTime < '{$end}'";
		$clsAnalyse = new Analyse();
		$clsAnalyse->begin();
		if (!$clsAnalyse->deleteAll($cond)) {
			$clsAnalyse->rollback();
			return false;
		}
		if (!$this->_insert($this->_data[$type])) {
			$clsAnalyse->rollback();
			return false;
		}
		$clsAnalyse->commit();
		return $this;
	}

	private function _saveRent($site)
	{
		$start = strtotime($this->getDate());
		$end = $start + 86400;
		$start = date('Y-m-d H:i:s', $start);
		$end = date('Y-m-d H:i:s', $end);
		$type = House::TYPE_ZHENGZU;
		if (empty($this->_data[$type])) {
			return false;
		}
		$cond = "houseType = {$type} "
			 . "AND cityId = {$this->sites[$site]} "
			 . "AND logTime >= '{$start}' AND logTime < '{$end}'";
		$clsAnalyse = new Analyse();
		$clsAnalyse->begin();
		if (!$clsAnalyse->deleteAll($cond)) {
			$clsAnalyse->rollback();
			return false;
		}
		if (!$this->_insert($this->_data[$type])) {
			$clsAnalyse->rollback();
			return false;
		}
		$clsAnalyse->commit();
		return $this;
	}

	private function _savePark($site)
	{
		$start = strtotime($this->getDate());
		$end = $start + 86400;
		$start = date('Y-m-d H:i:s', $start);
		$end = date('Y-m-d H:i:s', $end);
		$type = 0;
		if (empty($this->_data[$type])) {
			return false;
		}
		$cond = "houseType = {$type} "
			 . "AND cityId = {$this->sites[$site]} "
			 . "AND logTime >= '{$start}' AND logTime < '{$end}'";
		$clsAnalyse = new Analyse();
		$clsAnalyse->begin();
		if (!$clsAnalyse->deleteAll($cond)) {
			$clsAnalyse->rollback();
			return false;
		}
		if (!$this->_insert($this->_data[$type])) {
			$clsAnalyse->rollback();
			return false;
		}
		$clsAnalyse->commit();
		return $this;
	}

	/**
	 * 为分析url中的城区和板块做初始数据
	 */
	public function parsePinyin(){
		if( empty($this->sites) )  throw new Exception("Function's '$this->sites' is empty!");
		$arrCity = array();
		foreach ($this->sites as &$value) { $arrCity[$value] = array(); }

		$arrDistrict = $arrCity;
		$district = CityDistrict::Instance()->getAll();
		foreach($district as $row){
			if( isset($arrDistrict[$row['cityId']]) )
				$arrDistrict[$row['cityId']] = array_merge($arrDistrict[$row['cityId']], array($row['pinyin']=>$row['id']));
    	}
    	$this->district = $arrDistrict;

    	$arrRegion = $arrCity;
		$region = CityRegion::Instance()->getAll();
		foreach($region as $row){
			if( isset($arrHotarea[$row['cityId']]) )
				$arrRegion[$row['cityId']] = array_merge($arrRegion[$row['cityId']], array( $row['cityId']=>array('distId'=>$row['distId'], 'regId'=>$row['id']) ) );
    	}    	
    	$this->region = $arrRegion;
    	return $this;
	}

	/**
	 * 分析url并得出存储数据
	 * @param  array $row  单条数据
	 * @param  string $site 当前城市简称
	 * @param  string $type 租售类型
	 * @return array 需要存储的数据
	 */
	private function parseUrl($row, $site, $type){
		$arrType = array(
			'sale' => array('pinyin'=>'sale', 'type'=>House::TYPE_ERSHOU),
			'rent' => array('pinyin'=>'rent', 'type'=>House::TYPE_ZHENGZU),
			'park' => array('pinyin'=>'xiaoqu', 'type'=>0),
		);
		$strUrl = $row['url'];
		$intCityId = @$this->sites[$site];
		$arrInsert = array(				
			'houseType'=> $arrType[$type]['type'],
			'cityId'=> $intCityId,
			'distId'=> 0,
			'regId'=> 0,
			'parkId'=> 0,
			'housePrice'=> 0,
			'houseBA'=> 0,
			'houseBedRoom'=> 0,
			'houseBuildType'=> 0,
			'logTime'=> date('Y-m-d H:i:s', strtotime($row['time']))
		);

		$strUrl = preg_replace('/\?k=(.*)/i', '', $strUrl); //过滤k关键字
		$strUrl = preg_replace('/\?s=(.*)/i', '', $strUrl); //过滤s关键字
		$strUrl = str_ireplace('/'.$arrType[$type]['pinyin'], '', $strUrl); //过滤第一层sale|rent|xiaoqu关键字
		preg_match('/\/(?P<S>[A-Za-z0-9_]+)\//i', $strUrl, $tmp); //匹配第二级数据
		if( !empty($tmp) ){ //处理第二级数据，处理完后清理掉
			if( strpos($strUrl, 'sub')>-1 ){ //第二级目录是地铁的
				$strUrl = str_ireplace('/'.$tmp['S'].'/', '', $strUrl);
			}elseif( strpos($strUrl, 'sch')>-1 ){ //第二级目录是学校的
				$strUrl = str_ireplace('/'.$tmp['S'].'/', '', $strUrl);
			}else{ //第二级目录是城区和板块的
				if( isset($this->district[$intCityId][$tmp['S']]) ){
					$arrInsert['distId'] = $this->district[$intCityId][$tmp['S']];
					$strUrl = str_ireplace('/'.$tmp['S'].'/', '', $strUrl);
				}elseif( isset($this->region[$intCityId][$tmp['S']]) ){
					$arrInsert['regId'] = $this->region[$intCityId][$tmp['S']]['id'];
					$arrInsert['distId'] = $this->region[$intCityId][$tmp['S']]['distId'];
					$strUrl = str_ireplace('/'.$tmp['S'].'/', '', $strUrl);
				}elseif( intval($tmp['S'])>0 ){//小区
					$arrInsert['parkId'] = intval($tmp['S']);
					$strUrl = str_ireplace('/'.$tmp['S'].'/', '', $strUrl);
				}
			}
		}
		//处理最后的参数
		$strUrl = ltrim($strUrl, '/');
		$intFlag = preg_match_all("/([a-z]{1,4})(\d+\.?\d*-?\d*\.?\d*)/i", $strUrl, $match);
        $arrKey = $match[1] = array_flip($match[1]);
        $arrValue = $match[2];
		if( isset($arrKey['j']) && isset($arrValue[$arrKey['j']]) ) $arrInsert['housePrice'] = $arrValue[$arrKey['j']];
		if( isset($arrKey['m']) && isset($arrValue[$arrKey['m']]) ) $arrInsert['houseBA'] = $arrValue[$arrKey['m']];
		if( isset($arrKey['h']) && isset($arrValue[$arrKey['h']]) ) $arrInsert['houseBedRoom'] = $arrValue[$arrKey['h']];
		if( isset($arrKey['w']) && isset($arrValue[$arrKey['w']]) ) $arrInsert['houseBuildType'] = $arrValue[$arrKey['w']];
		// print_r($arrInsert);
		return $arrInsert;
	}
}
