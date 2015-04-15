<?php
/**
 * @abstract 全站静态方法调用
 * @author Yuntaohu <yuntaohu@51.com>
 * @date 2011-03-04
 * @lastModify By Yuntaohu 2011-03-04
 *
 */
class Utility{
	const EARTH_RADIUS = 6378.137;
	const DEFAULT_COOKIE_KEY = 'esf_session_id';
	const DEFAULT_COOKIE_DOMAIIN = 'esf.focus.cn';
	const MAIN_COOKIE_DOMAIIN = 'focus.cn';
	const ZU_COOKIE_DOMAIIN = 'zu.focus.cn';
	const COOKIE_KEY = 'Esf!2#51f.com$5^';
	const COOKIE_NAME_ALL_RECENTLY_VIEWED = 'esf_all_recently_viewed';
	const ESF_HISTORY_HOUSE = 'esf_history_house';

//=========== =========== =========== =========== =========== url jumper =========== =========== =========== ===========
	/**
     * 模仿JS的alert功能，尽量不要使用php的header进行跳转     原alert
     *
     * @param string $msg 抛出信息
     * @param string $location 跳转地址
     */
	public static function MsgBox( $msg = '',$location = '' ){
		$location = trim($location);
		if( empty($location) ){
			echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
			echo "<script type=\"text/javascript\">alert(\"{$msg}\");</script>";
			exit();
		}else{
			if( !empty($msg) ){
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo"<script type=\"text/javascript\">alert(\"{$msg}\");parent.location.href=\"{$location}\"</script>";
				exit();
			}else{
				echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
				echo"<script type=\"text/javascript\">parent.location.href=\"{$location}\"</script>";
				exit();
			}
		}
	}

	/**
     * 以json的方式返回ajax提交的任务执行信息
     *
     * @param string $msg 抛出信息
     * @param string $location 跳转地址
     * @param string $showtype 消息展示方式
     */
	public static function AjaxMsgBox( $msg = '',$location = '',$showtype = 'alert' ){
		$location = trim($location);
		header("Content-type:text/html;charset=utf-8");
		echo json_encode(array('msg' => $msg, 'location' => $location, 'showtype' => $showtype));
		exit();
	}
	
	/**
     * 模仿JS的alert功能，尽量不要使用php的header进行跳转     div显示alert信息
     *
     * @param string $msg 抛出信息
     * @param string $location 跳转地址
     */
	public static function MessageBox( $msg = '',$location = '' ){
		$location = trim($location);
		if( empty($location) ){
			echo "<script type=\"text/javascript\">".
					"if(typeof parent.myalert!='function')parent.alert('{$msg}');else parent.myalert('{$msg}');".
				"</script>";
		}else{
			if( !empty($msg) ){
				echo "<script type=\"text/javascript\">".
					"if(typeof parent.myalert!='function')parent.alert('{$msg}');else parent.myalert('{$msg}', '{$location}');".
				"</script>";
			}else{
				echo "<script type=\"text/javascript\">".
					"parent.location.href=\"{$location}\"".
					"</script>";
			}
		}
		exit();
	}
		
	/**
	 * 模仿JS的alert功能，尽量不要使用php的header进行跳转     原alert
	 *
	 * @param string $msg 抛出信息
	 * @param string $location 跳转地址
	 */
	public static function ShowMessage( $msg = '',$location = '' )
	{
		$location = trim($location);
		if( empty($location) )
		{	
			echo"<script type=\"text/javascript\">alert(\"{$msg}\");parent.history.go(-1);</script>";
		}
		else
		{
			if( !empty($msg) )
			{
			    echo"<script type=\"text/javascript\">alert(\"{$msg}\");parent.location.href=\"{$location}\"</script>";
			}else
			{
			    echo"<script type=\"text/javascript\">parent.location.href=\"{$location}\"</script>";
			}
		}
		exit;
	}
	
	/**
     * 提示信息不跳转页面
     * @param string $msg 抛出信息
     * @param string $location 跳转地址
     */
	public static function ShowMessageNOLocation( $msg = '',$location = '' ) {
		$location = trim($location);
		if( empty($location) ){
			echo"<script type=\"text/javascript\">alert(\"{$msg}\");</script>";
		}
		exit;
	}
	
	/**
	 * 二个密码对比
	 * @param 原密码 $strPasswd
	 * @param 二次确认密码 $strRePasswd
	 * @return boolean
	 */
	public static function IsPasswdCmp($strPasswd,$strRePasswd)
	{
		if(!strcmp($strPasswd,$strRePasswd))
			return true;
		return false;
	}
	/**
     * 提示信息并跳转到历史界面
     * @param string $msg 抛出信息
     */
	public static function HistoryBack( $msg = '') {
		echo"<script type=\"text/javascript\">alert(\"{$msg}\");parent.history.go(-1);</script>";
		exit;
	}
	
	/**
	 * 直接跳转，不需弹出框
	 *
	 * @param string $location 跳转地址
	 */
	public static function Location($location = ''){
		$location = trim($location);
		if( empty($location)){
			echo"<script type=\"text/javascript\">parent.history.go(-1);</script>";
		}else{
			echo"<script type=\"text/javascript\">parent.location.href=\"{$location}\"</script>";
		}
		exit;
	}

	/**
     * 不跳转，在页面上显示错误信息，模仿表单异步提交
     * @param string $id 要显示错误信息的<span>的 id
     * @param string $errorMessage 错误信息
     */
	public static function NoAlert($id = '',$errorMessage='',$isCheckCode=false)
	{
		$errorMessage = trim($errorMessage);
		if( !empty($id)){
			echo "<script type=\"text/javascript\">parent.document.getElementById('".$id."').innerHTML='".$errorMessage."';</script>";
			if($isCheckCode){
				self::refreshCheckCode();
			}
			exit;
		}
		exit;
	}
	/**
		* 执行回调函数 
		* @param string $strFunction 要执行的js
	*/
	public static function execJs( $strFunction='' ){
		if( !empty($strFunction)){
			echo "<script type=\"text/javascript\">".$strFunction."</script>";exit;
		}
		exit;
	}
	/**
	 * 显示404错误页面
	 *
	 * @return void
	 */
	public static function Show404()
	{
		echo '<meta http-equiv="refresh" content="0;url=/error.html">';
		exit;
	}	
//=========== =========== =========== =========== =========== /url jumper =========== =========== =========== ===========
	/*
	* 对变量进行 JSON 编码(支持中文)
	*
	* @param mix $value                   待编码的 value ，除了resource 类型之外，可以为任何数据类型
	* return string                        编码成功则返回一个以 JSON 形式表示的 string
	*/
	public static function jsonEncodeEx($value){
		return json_encode($value);
	}

	/*
	* 对 JSON 格式的字符串进行解码(支持中文)
	*
	* @param string $value                 json格式的字符串
	* @param bool $assoc(When TRUE, returned objects will be converted into associative arrays. )
	* return mix
	*/
	public static function jsonDecodeEx($value, $assoc = true){
		$value = json_decode($value,$assoc);
		return $value;
	}

	//内部调用方法
	public static function jsonConvertEncodingG2U(&$value, &$key) {
		$value = iconv("GBK", "UTF-8", $value);
	}

	//内部调用方法
	public static function jsonConvertEncodingU2G(&$value, &$key) {
		$value = iconv("UTF-8", "GBK", $value);
	}

	/**
     * 过滤字符
     *
     * @param string $subject
     * @param int $enter  $enter=0 为默认允许回车换行符,1为不允许
     * @param string $allowable_tags 允许保留的标签,如'<p><div>'
     * @return string $subject
     */
	public static function filterSubject($subject, $allowable_tags = false){

		//去除空格
		$subject = trim($subject);
		//过滤script
		$pattern = array('/<script.*\/script>/ism','/<script[^>]*>/ism','/<\/script>/ism');
		$subject = preg_replace($pattern, '', $subject);
		//过滤onclick
		$subject = preg_replace('/onclick\s?=[\'"]?[^\s>]*[>\s]?/ism' , ' ' , $subject);
		//过滤超连接
		$subject = preg_replace('/(?<=href=)([^\>]*)(?=\>)/ism' , '"#"' , $subject);
		//过滤HTML
		if ( $allowable_tags ) {
			$subject = strip_tags($subject);
		}
		return $subject;
	}
	/**
     * 将POST,GET过来的数据转义
     */
	public static function autoStripSlashes($str){
		return get_magic_quotes_gpc()? $str: addslashes($str);
	}

	/**
     * 获得用户IP地址
     *
     * @return string $user_ip
     */
	public static function GetUserIP(){
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$user_ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$user_ip=$_SERVER["REMOTE_ADDR"];
		}
		return $user_ip;
	}

	/**
     * 获取用户的浏览器信息
     * @return 浏览器类型
     */
     public static function GetUserExplorer(){
        $os=$_SERVER['HTTP_USER_AGENT'];// 浏览者操作系统及浏览器
        if(strpos($os,'NetCaptor')) $explorer='NetCaptor';
        elseif(strpos($os,'Opera')) $explorer='Opera';
        elseif(strpos($os,'Firefox'))   $explorer='Firefox';
        elseif(strpos($os,'MSIE 9'))    $explorer='MSIE 9.x';
        elseif(strpos($os,'MSIE 8'))    $explorer='MSIE 8.x';
        elseif(strpos($os,'MSIE 7'))    $explorer='MSIE 7.x';
        elseif(strpos($os,'MSIE 6'))    $explorer='MSIE 6.x';
        elseif(strpos($os,'MSIE 5'))    $explorer='MSIE 5.x';
        elseif(strpos($os,'MSIE 4'))    $explorer='MSIE 4.x';
        elseif(strpos($os,'Netscape'))  $explorer='Netscape';
        else    $explorer='Other';
        return $explorer;
     }

    /**
     * 获取用户的操作系统
     * @return 操作系统类型
     */
	public static function GetUserOs(){
		$os=$_SERVER['HTTP_USER_AGENT'];// 浏览者操作系统及浏览器
		// 分析操作系统
		if(strpos($os,'Windows NT 5.0'))$os='Windows 2000';
		elseif(strpos($os,'Windows NT 5.1'))$os='Windows XP';
		elseif(strpos($os,'Windows NT 5.2'))$os='Windows 2003';
		elseif(strpos($os,'Windows NT'))$os='Windows NT';
		elseif(strpos($os,'Windows 9'))$os='Windows 98';
		elseif(strpos($os,'unix'))$os='Unix';
		elseif(strpos($os,'linux'))$os='Linux';
		elseif(strpos($os,'SunOS'))$os='SunOS';
		elseif(strpos($os,'BSD'))$os='FreeBSD';
		elseif(strpos($os,'Mac'))$os='Mac';
		else $os='Other';
		return $os;
	}

	//========== ========== ========== ========== 验证 ========== ========== ========== ==========
	/**
     * 是否是有效邮箱
     * @param string $str
     * @return bool
     */
	public static function IsEmail($str) {
		$flag = false;
		$str = trim($str);
		$pattern = '/^(\d|[a-zA-Z])+(-|\.|\w)*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/';
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}

	/**
     * 是否是有效QQ
     * @param string $str
     * @return bool
     */
	public static function IsQQ($str) {
		$flag = false;
		$str = trim($str);
		$pattern = '/^[1-9][0-9]{4,12}$/';
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}

	/**
     * 是否是有效密码
     * @param string $str
     * @return bool
     */
	public static function IsPassword($str) {
		$flag = false;
		$str = trim($str);
		$pattern = '/^[\w-]{6,16}$/';
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}
	
	/**
     * 是否是有效密码 个人中心 因规则不同独立方法
     * @param string $str
     * @return bool
     */
	public static function IsPersonPassword($str) {
		$flag = false;
		$str = trim($str);
		$pattern = "/^[0-9A-Za-z~!@#\$%\^&\*\(\)_\+`\-\\=\[\];,\.\/\{\}\|:\'\"<>\?]{6,16}$/";
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}	

	/**
     * 是否是有效用户名 个人中心 因规则不同独立方法
     * @param string $str
     * @return bool
     */
	public static function IsPersonUsername($str) {
		$flag = false;
		$str = trim($str);
		$pattern = "/^[a-zA-Z]\w{3,29}$/";
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}
	
	/**
     * 是否是有效邮箱 个人中心 因规则不同独立方法
     * @param string $str
     * @return bool
     */
	public static function IsPersonEmail($str) {
		$flag = false;
		$str = trim($str);
		$pattern = "/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/";
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}	
		
	/**
     * 是否是有效邮编
     *
     * @param string $str
     * @return bool
     */
	public static function IsPostalCode($str) {
		$flag = false;
		$str = trim($str);
		$pattern = '/(^[0-9]{6}$)/';
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}

	/**
     * 是否是有效电话
     *
     * @param string $str
     * @return bool
     */
	public static function IsPhone($str) {
		$flag = false;
		$str = trim($str);
		$pattern = '/(^[0-9]{3,4}\-[0-9]{3,8}$)|(^[0-9]{3,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)/';
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}

	/**
     * 是否是有效手机号
     *
     * @param string $str
     * @return bool
     */
	public static function IsMobile($str) {
		$flag = false;
		$str = trim($str);
		$pattern = "/^[1][3|4|5|7|8]\d{9}$/";
		if (preg_match($pattern,$str)) {
			$flag = true;
		}
		return $flag;
	}
	/**
     * 验证是否是中文
     * @param $str 验证的字符串(这里验证是GBK编码的) 这里没有做trim操作(防止与入库的字符串重复trim)
     * @param $pregAdd 所加所允许的字符串
     * @param $minNum 最小个数
     * @param $maxNum 最大个数
     * @return boollen
     */
	public static function IsChinese($str, $minNum, $maxNum, $pregAdd=''){
	    $num = mb_strlen($str, 'UTF-8');
	    if ( $num < $minNum || $num >$maxNum ) { return false; }
	    $str = strtolower($str);

	    //去除正则字符
	    $arrSearch = array();
	    $arrPinYin = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	    $arrNum = array('0','1','2','3','4','5','6','7','8','9');
	    if( strpos($pregAdd, 'a-z')>-1||strpos($pregAdd, 'A-Z')>-1 ) $arrSearch = array_merge($arrSearch, $arrPinYin);
	    if( strpos($pregAdd, '0-9')>-1 ) $arrSearch = array_merge($arrSearch, $arrNum);
	    $pregAdd = str_ireplace(array('a-z','A-Z','0-9','|','[',']','(',')', '\\'), '', $pregAdd);
	    //添加所添加的字符
	    $n = mb_strlen($pregAdd, 'UTF-8');
	    for($i=0;$i<$n;$i++){ array_push($arrSearch, mb_substr($pregAdd, $i, 1, 'UTF-8')); }
	    //去除字符
	    $str = str_ireplace($arrSearch, '', $str);
	    //汉字验证并验证个数正确于否
	    $pattern='/^([\x7f-\xff])*$/';
	    return preg_match($pattern, $str)? true: false;
	}

	
	//========== ========== ========== ========== /验证 ========== ========== ========== ==========

	/**
     * 返回指定时间和当前时间的差值，并处理成用户体验良好的中文表现形式
     *
     * @param int|string $time 支持时间戳和文本格式的时间
     * @param 格式化类型 $stress 1:精确到秒的中文表现形式 2：精确到日 3：精确到分
     * @return string
     */
	public static function GetHumanTime($time=null, $stress=1){
		$now = time();		
		$time = is_numeric($time) ? $time : ($time == '0000-00-00 00:00:00' ? 0 : strtotime($time));
		$interval = $now - $time;
		switch( $stress ){
			case 1:
				if( empty($time) ) return '未曾更新过';
				if ( $interval > 31536000){//365*86400
					return floor($interval/(31536000)).'年前';
				}
				else if ( $interval > 2592000){//30*86400
					return floor($interval/(2592000)).'月前';
				}
				else if ( $interval > 604800){////7*86400
					return floor($interval/(604800)).'周前';
				}
				else if ( $interval > 86400 ){
					return floor($interval/(86400)).'天前';
				}
				else if ( $interval > 3600 ){
					return floor($interval/(3600)).'小时前';
				}
				else if ( $interval <= 3600 ){
					return ceil($interval/(60)).'分钟前'; //这里统一和房源的更新时间
				}
				// else if ( $interval > 0 ) {
				// 	return $interval.'秒前';
				// }
				// else
				// return '刚刚';
			case 2:
				return date('Y-m-d', $time);
			case 3:
				return date('Y-m-d H:i', $time);
			case 4:
				if( empty($time) ) return 0;
				if ( $interval < 60 ) {
				 	return '刚刚';
				}
				else if ( $interval < 3600 ){
					return floor($interval/(60)).'分钟前';
				}
				else if ( $interval < 86400 ){
					return floor($interval/(3600)).'小时前';
				}
				else if ( $interval < 2592000 ){//30*86400
					//return floor($interval/(86400)).'天前';
					return floor((strtotime(date('Y-m-d', $now))-strtotime(date('Y-m-d', $time)))/(86400)).'天前';//24小时 <= 发布时间 < 30天，应取当前日期 – 发布日期
				}
				else {
					return date('m-d H:i', $time);
				}
		}
	}

	/**
     * 获取过去一段时间列表
     *  @by wuzhangshu 20110330
     * @param int $timestamp1 开始时间
     * @param int $timestamp2 结束时间
     * @return array
     */
	public static function getMonthList ($timestamp1, $timestamp2)
	{
		$yearsyn = date('Y', $timestamp1);
		$monthsyn = date('m', $timestamp1);
		$daysyn = date('d', $timestamp1);

		$yearnow = date('Y', $timestamp2);
		$monthnow = date('m', $timestamp2);
		$daynow = date('d', $timestamp2);

		if ($yearsyn == $yearnow){
			$monthinterval = $monthnow - $monthsyn;
		}
		else if ($yearsyn < $yearnow){
			$yearinterval = $yearnow - $yearsyn -1;
			$monthinterval = (12 - $monthsyn + $monthnow) + 12 * $yearinterval;
		}

		$timedata = array();
		for ($i = 0; $i <= $monthinterval; $i++){
			$tmptime = mktime(0, 0, 0, $monthsyn + $i, 1, $yearsyn);
			$timedata[$i]['year'] = date('Y', $tmptime);
			$timedata[$i]['month'] = date('m', $tmptime);
			$timedata[$i]['beginday'] = '01';
			$timedata[$i]['endday'] = date('t', $tmptime);
		}

		$timedata[0]['beginday'] = $daysyn;
		$timedata[$monthinterval]['endday'] = $daynow;
		unset($tmptime);
		return $timedata;
	}

	/*
	* 定义Css,Js,Image地址
	* @param string $absUrlPath 地址
	* @param bool
	* @return string 格式化后的Url
	*/
	public static function GetAssetUrl($absUrlPath = ""){
		if ( empty($absUrlPath) ) return '';
		if ( preg_match( '#^https?://#', $absUrlPath ) ) return $absUrlPath;
		if ( $absUrlPath{0}=='/' ){
			$absUrlPath = substr($absUrlPath, 1, strlen($absUrlPath)-1);
		}
		return _RESOURCE_URL_ . $absUrlPath;
	}
//=========== =========== =========== =========== =========== cookie =========== =========== =========== ===========
	/**
     * 设置Cookie
     * @param array $cookie 需要保存的信息
     * @return bool
     */
    public static function setCookie($cookieName=self::DEFAULT_COOKIE_KEY, $cookie=array(), $expire=0, $path='/',  $domain=self::DEFAULT_COOKIE_DOMAIIN, $secure=0){
        if( empty($cookie) )
            return false;
        $cookie = serialize($cookie);
        $key = self::COOKIE_KEY;
        return setcookie($cookieName, md5($key).base64_encode($cookie), $expire, $path, $domain, $secure);
    }
	/**
     * Cookie解析
     * @param string $session_id
     * @return array $output
     */
	public static function parseCookie($cookie) {
		$arrBackData = array();
		if(isset($_COOKIE[$cookie])){
			$strCookie = $_COOKIE[$cookie];
			$strCookie = base64_decode(substr($strCookie, 32));
			$arrBackData = unserialize($strCookie);
		}
		return $arrBackData;
	}

	/**
     * Cookie解析
     * @param string $cookieName cookie名称
     * @return array
     */
	public static function getCookieInfo($cookieName=self::DEFAULT_COOKIE_KEY) {
		if( !isset($_COOKIE[$cookieName]) )
            return false;
        return unserialize( base64_decode(substr($_COOKIE[$cookieName], 32)) );
	}
	/**
     * 清除Cookie
     * @return bool
     */
	public static function unsetCookie($cookieName=self::DEFAULT_COOKIE_KEY, $domain=self::DEFAULT_COOKIE_DOMAIIN, $defaultTime=3600, $path='/'){
		return setcookie($cookieName, null, time()-intval($defaultTime), $path, $domain, 0);
	}
	/**
	 * @abstract 发布过的房源的小区记录
	 * @param string $strType 选择操作类型 eg:get,set
	 * @param int $intHouseId 小区id
	 */
	public static function historyHouseCookie($strType, $intCityId=1, $intHouseId=0){
		$strCookieName = self::ESF_HISTORY_HOUSE;
		$intCookieMaxNum = 5;
		$arrAllCookie = isset($_COOKIE[$strCookieName])? json_decode($_COOKIE[$strCookieName], TRUE): array();
		$arrCityCookie = isset($arrAllCookie[$intCityId])? $arrAllCookie[$intCityId]: array();
		switch($strType){
			case 'get':
				return $arrCityCookie;
				break;
			case 'set':
				if( !empty($intHouseId)&&!in_array($intHouseId, $arrCityCookie) ){
					$arrAllCookie[$intCityId] = array_slice( array_merge(array($intHouseId), $arrCityCookie), 0, $intCookieMaxNum );
					return setcookie($strCookieName, json_encode($arrAllCookie), time()+31536000, '/', self::DEFAULT_COOKIE_DOMAIIN, 0);
				}
				break;
		}
	}

	/**
     * Cookie解析
     * @param string $cookieName cookie名称
     * @param int $intSubLen cookie字符串截取长度
     * @return array $output
     */
	public static function parseCookieInfo($cookieName, $intSubLen = 32) {
		$arrBackData = array();
		if(isset($_COOKIE[$cookieName])){
			$strCookie = $_COOKIE[$cookieName];
			$strCookie = base64_decode(substr($strCookie, $intSubLen));
			$arrBackData = unserialize($strCookie);
			return $arrBackData;
		}else{
			return '';
		}
	}
//=========== =========== =========== =========== =========== /cookie =========== =========== =========== ===========
	//设置计时开始函数
	public static function TimeStart(){
		global $StartTime;
		$StartTime = microtime(true);
		echo "开始计时\n";
	}

	//设置计时结束函数
	public static function TimeEnd(){
		global $StartTime;
		$EndTime = microtime(true);
		$UseTime = $EndTime - $StartTime;
		echo "计时终止，共耗时 $UseTime 秒 [".intval($UseTime/3600)."时".intval($UseTime%3600/60)."分".intval($UseTime%3600%60%60)."秒]\n";
		unset($StartTime, $EndTime, $UseTime);
	}

	/**
     * 转义字符串
     * @param str $str 需要转义的字符串
     * @return str 转义后的字符串
     */
	public static function escStr($str){
		return htmlspecialchars($str);
	}
	/**
     * 转义数组中的每个字符串
     * @param str $str 需要转义的字符串
     * @return str 转义后的字符串
     */
	public static function escArr($data = array()){
        if ( empty($data) || !is_array($data) ) {
            return $data;
        }
        $arrBack = array();
        if ( !empty($data) ) {
            foreach ( $data as $k => $v ) {
            	if(is_array($v)) {
            		$arrBack[$k][] = self::escArr($v);
            	} else {
                	$arrBack[$k] = self::escStr($v);
            	}
            }
        }
        return $arrBack;
    }

	/**
     * 获取经纬度算法
     * @param int $lat1  经度开始坐标
     * @param int $lng1  纬度开绐坐标
     * @param int $lat2  经度结束坐标
     * @param int $lng2  纬度结束坐标
     */
	public static function GetDistance($lat1,$lng1,$lat2,$lng2){
		$radlat1=self::rad($lat1);
		$radlat2=self::rad($lat2);
		$a = $radlat1-$radlat2;//经度差值
		$b = self::rad($lng1)-self::rad($lng2);//纬度差值
		$temp=sqrt(pow(sin($a/2),2)+ cos($radlat1) * cos($radlat2)*pow(sin($b/2),2));
		$s=2*atan($temp/sqrt(-$temp*$temp+1));
		$s=$s*self::EARTH_RADIUS;
		return $s;
	}

	public static function rad($double){
		return $double * pi()/180;
	}

	/**
	 * 截取字符串GBK ...
	 */
	public static function substr_cn($string, $length = 80, $etc = '...', $charset = 'GBK'){
		if(mb_strwidth($string,$charset)<=$length)return $string;
			return mb_strimwidth($string,0,$length,'',$charset) . $etc;
	}

	//截取utf8字符串
	public static function utf8Substr($str, $from, $len){
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
		'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
		'$1',$str);
	}

	/**
     * 取得sohu的passport信息
     *
     * @return array $passport
     */
	public static function getSohuPassportInfo() {
		$passport = array();
		$headers = apache_request_headers();
		$passport_userid = isset($headers['X-SohuPassport-UserId']) ? $headers['X-SohuPassport-UserId'] : '';
		if (!preg_match("/(.+)@(.+)$/", $passport_userid, $passport_info)) {
			return null;
		}

		//转换编码为gbk
		$passport["userid"] = iconv("UTF-8", "GBK", $passport_userid);
		$passport["username"] = iconv("UTF-8", "GBK", $passport_info[1]);
		$passport["domain"] = trim($passport_info[2]);

		return $passport;
	}

	/**
	 * 积分等级
	 *
	 * @param int $intIntegral
	 * @return int
	 */
	public static function realtorRank($intIntegral) {
		if ( $intIntegral >= 0 && $intIntegral < 500 ) {
			return array('rank' => 1, 'icon' => 'intger1.gif', 'medal' => '银牌经纪人', 'next_level' => 500-$intIntegral);
		} elseif ( $intIntegral >= 500 && $intIntegral < 1000 ) {
			return array('rank' => 2, 'icon' => 'intger1.gif', 'medal' => '银牌经纪人', 'next_level' => 1000-$intIntegral);
		} elseif ( $intIntegral >= 1000 && $intIntegral < 2000 ) {
			return array('rank' => 3, 'icon' => 'intger1.gif', 'medal' => '银牌经纪人', 'next_level' => 2000-$intIntegral);
		} elseif ( $intIntegral >= 2000 && $intIntegral < 4000 ) {
			return array('rank' => 4, 'icon' => 'intger1.gif', 'medal' => '银牌经纪人', 'next_level' => 4000-$intIntegral);
		} elseif ( $intIntegral >= 4000 && $intIntegral < 8000 ) {
			return array('rank' => 5, 'icon' => 'intger1.gif', 'medal' => '银牌经纪人', 'next_level' => 8000-$intIntegral);
		} elseif ( $intIntegral >= 8000 && $intIntegral < 15000 ) {
			return array('rank' => 6, 'icon' => 'intger2.gif', 'medal' => '金牌经纪人', 'next_level' => 15000-$intIntegral);
		} elseif ( $intIntegral >= 15000 && $intIntegral < 25000 ) {
			return array('rank' => 7, 'icon' => 'intger2.gif', 'medal' => '金牌经纪人', 'next_level' => 25000-$intIntegral);
		} elseif ( $intIntegral >= 25000 && $intIntegral < 35000 ) {
			return array('rank' => 8, 'icon' => 'intger2.gif', 'medal' => '金牌经纪人', 'next_level' => 35000-$intIntegral);
		} elseif ( $intIntegral >= 35000 && $intIntegral < 50000 ) {
			return array('rank' => 9, 'icon' => 'intger2.gif', 'medal' => '金牌经纪人', 'next_level' => 50000-$intIntegral);
		} elseif ( $intIntegral >= 50000 && $intIntegral < 65000 ) {
			return array('rank' => 10, 'icon' => 'intger2.gif', 'medal' => '金牌经纪人', 'next_level' => 65000-$intIntegral);
		} elseif ( $intIntegral >= 65000 && $intIntegral < 80000 ) {
			return array('rank' => 11, 'icon' => 'intger3.gif', 'medal' => '钻石经纪人', 'next_level' => 80000-$intIntegral);
		} elseif ( $intIntegral >= 80000 && $intIntegral < 100000 ) {
			return array('rank' => 12, 'icon' => 'intger3.gif', 'medal' => '钻石经纪人', 'next_level' => 100000-$intIntegral);
		} elseif ( $intIntegral >= 100000 && $intIntegral < 150000 ) {
			return array('rank' => 13, 'icon' => 'intger3.gif', 'medal' => '钻石经纪人', 'next_level' => 150000-$intIntegral);
		} elseif ( $intIntegral >= 150000 && $intIntegral < 250000 ) {
			return array('rank' => 14, 'icon' => 'intger3.gif', 'medal' => '钻石经纪人', 'next_level' => 250000-$intIntegral);
		} elseif ( $intIntegral >= 250000 && $intIntegral < 350000 ) {
			return array('rank' => 15, 'icon' => 'intger3.gif', 'medal' => '钻石经纪人', 'next_level' => 350000-$intIntegral);
		} elseif ( $intIntegral >= 350000 && $intIntegral < 500000 ) {
			return array('rank' => 16, 'icon' => 'intger4.gif', 'medal' => '皇冠经纪人', 'next_level' => 500000-$intIntegral);
		} elseif ( $intIntegral >= 500000 && $intIntegral < 650000 ) {
			return array('rank' => 17, 'icon' => 'intger4.gif', 'medal' => '皇冠经纪人', 'next_level' => 650000-$intIntegral);
		} elseif ( $intIntegral >= 650000 && $intIntegral < 800000 ) {
			return array('rank' => 18, 'icon' => 'intger4.gif', 'medal' => '皇冠经纪人', 'next_level' => 800000-$intIntegral);
		} elseif ( $intIntegral >= 800000 && $intIntegral < 1000000 ) {
			return array('rank' => 19, 'icon' => 'intger4.gif', 'medal' => '皇冠经纪人', 'next_level' => 1000000-$intIntegral);
		} elseif ( $intIntegral >= 1000000 ) {
			return array('rank' => 20, 'icon' => 'intger4.gif', 'medal' => '皇冠经纪人', 'next_level' => 0);
		}
	}

	/**
	 * @abstract 检测字符串中是否存在违规词组
	 *
	 * @param string $strWords
	 * @return bool
	 */
	public static function Illegal_word_stock( $strWords )
	{
		//获取敏感词
		$strKey = MCDefine::ILLEGAL_WORD_STOCK ;
    	$arrWords = Mem::Instance()->Get( $strKey );
		if(empty( $arrWords ))
		{
			Mem::Instance()->Set($strKey, $GLOBALS['_Illegal_word_stock'], 3600);
		}
		Mem::Instance()->close();
		
		//是否存在敏感词
		$bRetrun = false;
		$strNewWords = '';
		if(!empty( $arrWords ))
		{
			foreach ( $arrWords as $strVal ) 
			{
				if ( $strVal == $strWords )
				{
					$bRetrun = true;
					break;
				}
			}
		}
		return $bRetrun;
	}


	/**
	 * 加密解密-针对字符串内容都为数字的
	 * 用于 注册发邮箱
	 * 地址栏不能带 # 字符
	 * @author yanfang
	 * @param string $str 明文 或 密文
	 * @param string $operation：DECODE表示解密,其它表示加密
	 * @return string 加密解密后的值
	 * @since 2011-11-18
	 */
	function authcode($str,$operation='DECODE'){
		$len = 1;
		if($operation == 'DECODE'){
			$arrLetter = array(
			'Z' => 0,
			'A' => 1,
			'M' => 2,
			'G' => 3,
			'H' => 4,
			'Q' => 5,
			'L' => 6,
			'C' => 7,
			'X' => 8,
			'S' => 9,
			);
			$strTmp = substr($str,$len);
			$strTmp = substr($strTmp,0,strlen($strTmp)-$len);
			$strTmp = strrev($strTmp);
			$intStrTmp = strlen($strTmp);
			for($i = 0;$i < $intStrTmp;$i++){
				$strTmp{$i} = $arrLetter[$strTmp{$i}];
			}
		}else{
			$arrLetter = array(
			'0' => 'Z',
			'1' => 'A',
			'2' => 'M',
			'3' => 'G',
			'4' => 'H',
			'5' => 'Q',
			'6' => 'L',
			'7' => 'C',
			'8' => 'X',
			'9' => 'S',
			);
			$letter_all = "0ab34G6789Esf!251f.com$5^=";//地址栏不能带 #
		    $number_all = 'Esf!251f.com$5^=903847';
			$strTmp = substr(str_shuffle($letter_all),0,$len);
			$strRev = strrev($str);
			$intStrRev = strlen($strRev);
			for($i = 0;$i < $intStrRev;$i++){
				$strTmp .= $arrLetter[$strRev{$i}];
			}
			$strTmp .= substr(str_shuffle($number_all),0,$len);
		}
		return $strTmp;
	}


	/**
	 * excel导出
	 *
	 * @param string $filename 要生成的excel文件名
	 */

	static public function ExcelHeader($filename){
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=".$filename.".xls");
	}

	/**
	 * 解析小区学校串，并返回多个小区附近学校信息 按照距离房源大小排序
	 * 使用于房源详情页
	 * @author fangfangyan@sohu-inc.com
	 * @param str $around_school 学校串
	 * @param int $city_id 城市ID
	 * @param str $strSchool 学校名称
	 * @param str $arrSchoolInNowCity 当前城市下的学校，为了防止多次查询同样的数据
	 */
	public static function getHouseSchoolsDetail($around_school,$city_id,$strSchool,$school_orderby=SORT_ASC, $arrSchoolInNowCity=array()) {
		if (empty ( $around_school )) {
			return false;
		}
		$arrMin = array();
		// $strFormat = "距离%s%d米";
		$arrSchool = self::getWebHouseSchoolArr2($around_school);
		if( empty( $arrSchool ) ){
			return false;
		}
		$arrSpace = array();
		foreach( $arrSchool as $itemSub ){
			$arrSpace[] = $itemSub['space'];
		}
		array_multisort($arrSpace,SORT_NUMERIC ,$school_orderby,$arrSchool ); //按照'assort_id'字段排序;
		//获取学校信息，以名称为KEY
		$arrSchoolInfo = empty($arrSchoolInNowCity)? MDSchool::Instance()->getSchoolInfoByCityId($city_id): $arrSchoolInNowCity;
		if(!empty($strSchool)){
			foreach( $arrSchool as $itemSub ){
				if( $strSchool == $itemSub['school_name'] ){
					$arrMin[0] = array(
						'school_name' => $itemSub['school_name'],
						'space' => intval($itemSub['space'])
					);//sprintf( $strFormat, $itemSub['school_name'],intval( $itemSub['space'] ) );
					break;
				}
			}
		}else{
			$i = 1;
			foreach( $arrSchool as $k => $itemSub ){
				if($i>4)break;
				if($itemSub['school_type_name'] == '大学'){
					$stype = MDSchool::SCHOOL_TYPE_UNIVERSITY;
				}elseif($itemSub['school_type_name'] == '中学'){
					$stype = MDSchool::SCHOOL_TYPE_MIDDLESCHOOL;
				}elseif($itemSub['school_type_name'] == '小学'){
					$stype = MDSchool::SCHOOL_TYPE_PRIMARYSCHOOL;
				}
				$arrMin[$k] = array(
						'id' => $arrSchoolInfo[$itemSub['school_name']]['school_id'],
						'school_name' => $itemSub['school_name'],
						'school_type' => $stype,
						'space' => intval($itemSub['space'])
					);
				$i++;
			}
		}
		return $arrMin;
	}

	/**
	 * 解析小区学校串，并返回多个小区附近中小学信息
	 * @author 翟健雯<jianwenzhai@51f.com>(周海龙修改原方法getWebHouseSchool)
	 * @param str $around_school 学校串
	 * @param int $city_id 城市ID
	 * @param str $strSchool 学校名称
	 * @param str $arrSchoolInNowCity 当前城市下的学校，为了防止多次查询同样的数据
	 * @param str $intShoolType 学校类型
	 */
	public static function getHouseSchools($around_school,$city_id,$strSchool,$school_orderby=SORT_DESC, $arrSchoolInNowCity=array(), $intShoolType='') {
		if (empty ( $around_school )) {
			return false;
		}
		$arrMin = array();
		// $strFormat = "距离%s%d米";
		$arrSchool = self::getWebHouseSchoolArr2($around_school, $intShoolType);
		if( empty( $arrSchool ) ){
			return false;
		}
		$arrSpace = array();
		foreach( $arrSchool as $itemSub ){
			$arrType[] = $itemSub['school_type'];
		}
		array_multisort($arrType,SORT_NUMERIC ,$school_orderby,$arrSchool ); //按照'assort_id'字段排序;
		//获取学校信息，以名称为KEY
		$arrSchoolInfo = empty($arrSchoolInNowCity)? MDSchool::Instance()->getSchoolInfoByCityId($city_id): $arrSchoolInNowCity;
		if(!empty($strSchool)){
			foreach( $arrSchool as $itemSub ){
				if( $strSchool == $itemSub['school_name'] ){
					$arrMin[0] = array(
						'school_name' => $itemSub['school_name'],
						'space' => intval($itemSub['space'])
					);//sprintf( $strFormat, $itemSub['school_name'],intval( $itemSub['space'] ) );
					break;
				}
			}
		}else{
			$i = 1;
			foreach( $arrSchool as $k => $itemSub ){
				if($i>5)break;
				if($itemSub['school_type_name'] == '大学'){
					$stype = MDSchool::SCHOOL_TYPE_UNIVERSITY;
				}elseif($itemSub['school_type_name'] == '中学'){
					$stype = MDSchool::SCHOOL_TYPE_MIDDLESCHOOL;
				}elseif($itemSub['school_type_name'] == '小学'){
					$stype = MDSchool::SCHOOL_TYPE_PRIMARYSCHOOL;
				}
				$arrMin[$k] = array(
						'id' => $arrSchoolInfo[$itemSub['school_name']]['school_id'],
						'school_name' => $itemSub['school_name'],
						'school_type' => $stype,
						'space' => intval($itemSub['space'])
					);
				$i++;
			}
		}
		return $arrMin;
	}

	/**
	 *
	 * 重构小区学校串
	 * @author 翟健雯<jianwenzhai@51f.com>
	 * @param string $arrSub 小区学校串
	 * @param int $intShoolType 学校类型
	 * $return array array(
	 * 						array(
	 * 							'school_name' => 学校名称
	 * 							'space' => 距离
	 * 							)
	 * 						...
	 * 						)
	 */
	private static function getWebHouseSchoolArr2($strSub, $intShoolType=''){
		if( empty( $strSub ) ){
			return false;
		}
		$arrSchoolAll = explode(',', $strSub);
		$arrSub = array();
		if( !empty( $arrSchoolAll ) ){
			$strSeperate = $GLOBALS['AROUND_INFO_LINK_ID'];
			if( empty($intShoolType) ){
				foreach( $arrSchoolAll as $itemSchool){
					$arrTempSchool = explode( $strSeperate, $itemSchool);
					if($arrTempSchool[0] == '大学'){
						$stype = MDSchool::SCHOOL_TYPE_UNIVERSITY;
					}elseif($arrTempSchool[0] == '中学'){
						$stype = MDSchool::SCHOOL_TYPE_MIDDLESCHOOL;
					}elseif($arrTempSchool[0] == '小学'){
						$stype = MDSchool::SCHOOL_TYPE_PRIMARYSCHOOL;
					}
					$arrSub[] = array(
						'school_type_name'  => $arrTempSchool[0], //学校类型名称
						'school_name'       => $arrTempSchool[1], //学校名称
						'time'              => $arrTempSchool[2],  //时间
						'space'             => $arrTempSchool[3],  //距离
						'school_type'		=> $stype//类型ID
					);
				}
			}else{
				$strSchoolType = isset($GLOBALS['SCHOOL_TYPE'][$intShoolType])? $GLOBALS['SCHOOL_TYPE'][$intShoolType]: 3;
				foreach( $arrSchoolAll as $itemSchool){
					$arrTempSchool = explode( $strSeperate, $itemSchool);
					if( $strSchoolType!=$arrTempSchool[0] ) continue;
					$stype = $intShoolType;
					$arrSub[] = array(
						'school_type_name'  => $arrTempSchool[0], //学校类型名称
						'school_name'       => $arrTempSchool[1], //学校名称
						'time'              => $arrTempSchool[2],  //时间
						'space'             => $arrTempSchool[3],  //距离
						'school_type'		=> $stype//类型ID
					);
				}
			}
		}
		return $arrSub;
	}

	/**
	 * 解析小区地铁线路串
	 * @author 翟健雯<jianwenzhai@51f.com>
	 * @param str $subway_site 站点串
	 * @param str $strLine 线路名称
	 * @param str $strSite 站点名称
	 * @return array(
	 *                'desc' => 线路地址,
	 *                'site_name' => 线路或站点名,
	 *              )
	 */
	public static function getWebHouseSubWaySite($subway_site, $strLine = '', $strSite = '' ) {
		if (empty ( $subway_site )) {
			return false;
		}
		$intMin = null;
		$strMin = '';
		$strFormat = "距离%s%s站%d米";
		$strSiteName = '';
		$arrSubWaySite = self::getWebHouseSubArr($subway_site);
		if( empty( $arrSubWaySite ) ){
			return false;
		}
		$arrSpace = array();
		foreach( $arrSubWaySite as $itemSub ){
			$arrSpace[] = $itemSub['space'];
		}
		array_multisort($arrSpace,SORT_NUMERIC ,SORT_ASC,$arrSubWaySite ); //按照'space'字段排序;
		if ( empty( $strLine ) && empty( $strSite ) ) {
			$strMin = sprintf( $strFormat, $arrSubWaySite[0]['line'], $arrSubWaySite[0]['site'] ,intval( $arrSubWaySite[0]['space'] ) );
			$strSiteName = $arrSubWaySite[0]['site'];
		}else{
			foreach( $arrSubWaySite as $itemSub ){
				if( empty($strSite) ){
					if( $strLine == $itemSub['line'] ){
						$strMin = sprintf( $strFormat, $itemSub['line'], $itemSub['site'] ,intval( $itemSub['space'] ) );
						$strSiteName = $itemSub['line'];
						break;
					}
				}else{
					if( $strLine == $itemSub['line'] && $strSite == $itemSub['site'] ){
						$strMin = sprintf( $strFormat, $itemSub['line'], $itemSub['site'] ,intval( $itemSub['space'] ) );
						$strSiteName = $itemSub['site'];
						break;
					}
				}
			}
		}
		return array('desc'=>$strMin, 'site_name'=>$strSiteName);
	}

	/**
	 *
	 * 重构小区站点串
	 * @author 翟健雯<jianwenzhai@51f.com>
	 * @param string $arrSub 小区站点串
	 * $return array array(
	 * 						array(
	 * 							'line' => 线路名称
	 * 							'site' => 站点名称
	 * 							'space' => 距离
	 * 							)
	 * 						...
	 * 						)
	 */
	private static function getWebHouseSubArr($strSub){
		if( empty( $strSub ) ){
			return false;
		}
		$arrLineSiteAll = explode(',', $strSub);
		$arrSub = array();
		if( !empty( $arrLineSiteAll ) ){
			foreach($arrLineSiteAll as $itemLineSite){
				$arrTempLineSite = explode($GLOBALS['AROUND_INFO_LINK_ID'], $itemLineSite);
				$arrSub[] = array(
				'line'  => $arrTempLineSite[0], //线路名称
				'site'  => $arrTempLineSite[1], //站点名称
				'time'  => $arrTempLineSite[2],  //时间
				'space' => $arrTempLineSite[3]  //距离

				);
			}
		}
		return $arrSub;
	}

	/**
	 * 动态获取搜狗地图静态图
	 *
	 * @param string $x
	 * @param string $y
	 * @return string
	 */
	static public function getSogouStaticMap($x = '', $y = '', $height = '179', $width = '207', $labels = '') {
		if ( empty($x) || empty($y) ) {
			return '';
		}
        $cacheKey = MCDefine::SOGOU_MAP."x:".$x."y:$y"."height:".$height."width".$width."labels:".$labels;
        $memCache = Mem::Instance();
        $url = $memCache->get($cacheKey);//不需要清楚cache，因为x，y变了，自然key也变了
        $url = false;
        if (!$url){
            $arrBackData = Curl::GetResult("http://api.go2map.com/engine/api/translate/json?points={$x},{$y}&type=2", array(), "", 3);
            if ( empty($arrBackData) ) {
                return '';
            }
            $arrInfo = json_decode($arrBackData);
            $arrXY = $arrInfo->response->points;
            if ( !is_array($arrXY) ) {
                return '';
            }
            if(!empty($labels)){
                $url =  "http://api.go2map.com/engine/api/static/image+{'points':'{$arrXY[0]->x},{$arrXY[0]->y}',height:$height,'width':$width,'zoom':14,'labels':$labels,'center':'{$arrXY[0]->x},{$arrXY[0]->y}'}.png";
            }
            else{
                $url = "http://api.go2map.com/engine/api/static/image+{'points':'{$arrXY[0]->x},{$arrXY[0]->y}',height:$height,'width':$width,'zoom':14,'center':'{$arrXY[0]->x},{$arrXY[0]->y}'}.png";
            }
            $memCache->set($cacheKey, $url,3600*24*30);
        }
        return $url;
	}

	/**
	 * 校验以checkcode.php生成的验证码
	 * @param string $checkcode 校验值
	 * @author sunhailong<sunhailong@51f.com>
	 * @return boolean false 不正确 | true 正确
	 */
	public static function verifyCheckcode($checkcode=''){
		if($checkcode){
			if($checkcode == $_COOKIE['authnum_session']){
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取用户登录信息
	 * @param string $cookietype 用户类型串
	 * @return $arrCookie 返回用户信息 | false 未登录
	 */
	public static function get_user_login_info($cookietype='vip_session_id'){
		if($cookietype=='vip_session_id'){
			$arrCookie = false;
			$arrCookie = Utility::parseCookie('esf_brokerallinfo');
			if($arrCookie) return $arrCookie;
			return false;
		}
		if($cookietype=='my_session_id'){
			$arrCookie = false;
			$arrCookie = Utility::parseCookie('esf_personalinfo');
			if($arrCookie) return $arrCookie;
			return false;
		}
		return false;
	}

	public static function Distance($strMore, $strType = 'school', $intBackArray = false) {
		$arrBackData = array();
		preg_match_all('/(?P<F>[^,].+)兲(?P<S>.+)兲(?P<T>.+)分钟兲(?P<time>[0-9]+?)/U', $strMore, $arrMore);
		//按照距离进行排序处理
		if(!empty($arrMore['F'])){
			$arrInfo = array();
			$arrTime = array();
		    foreach($arrMore['F'] as $k => $data){
			     $arrInfo[$k] = array('F'=>$data, 'S'=>$arrMore['S'][$k], 'time'=>$arrMore['time'][$k]);
				 $arrTime[] = $arrMore['time'][$k];
			}
			array_multisort($arrTime,SORT_NUMERIC ,SORT_ASC,$arrInfo ); //按照'space'字段排序;
			foreach($arrInfo as $k => $data){
			    $arrMore['F'][$k] = $data['F'];
				$arrMore['S'][$k] = $data['S'];
				$arrMore['time'][$k] = $data['time'];
			}
			$arrTime = null;
			$arrInfo = null;
		}

		if ( $intBackArray === true ) {
			return array('F' => $arrMore['F'], 'S' => $arrMore['S'], 'M' => $arrMore['time']);
		}
		if ( !empty($arrMore['time']) ) {
			foreach ( $arrMore['time'] as $k => $v ) {
				if ( $strType == 'school' ) {
					$arrBackData[$v] = '距离'.$arrMore['S'][$k].$v.'米';
				} else {
					$arrBackData[$v] = '距离'.$arrMore['F'][$k].$arrMore['S'][$k].'站'.$v.'米';
				}
			}
		}
		if ( empty($arrBackData) ) {
			return '';
		}
		ksort($arrBackData);
		return reset($arrBackData);
	}
	//DDos拦截 
	public static function DdosFireWall($broker_id,$city=0){
		//去除焦点通列表页5秒的访问限制---Start
		return true; 
		//去除焦点通列表页5秒的访问限制---End
		if($city != 1) return true;
		$Skey = 'search_'.intval($broker_id);		
		$isSearch = FSMC::Instance($GLOBALS['CONFIG_MEMCACHE_SPECIAL'])->Get($Skey);
		if($isSearch){
			return false;
		}else{
			FSMC::Instance($GLOBALS['CONFIG_MEMCACHE_SPECIAL'])->Set($Skey,'1',3);
			return true;
		}				
	}
	/**
	 * 
	 * @param unknown $strMore
	 * @param string $strType
	 * @param string $intBackArray
	 * @param unknown $strLine,从line分离出线路
	 * @return multitype:unknown |string|mixed
	 */
	
	public static function DistanceSub($strMore, $strType = 'subway', $intBackArray = false ,$strLine ) {
		$arrBackData = array();
		$strLine = str_replace(array('@subway_site','"'), '', $strLine);
		$arrLine = explode('兲', $strLine);
		preg_match_all('/(?P<F>[^,].+)兲(?P<S>.+)兲(?P<T>.+)分钟兲(?P<time>[0-9]+?)/U', $strMore, $arrMore);
		if ( $intBackArray === true ) {
			return array('F' => $arrMore['F'], 'S' => $arrMore['S'], 'M' => $arrMore['time']);
		}
// 		var_dump($arrMore['F']);
		if ( !empty($arrMore['time']) ) {
			foreach ( $arrMore['time'] as $k => $v ) {
				if ( $arrMore['F'][$k] == trim($arrLine['0']) && $arrMore['S'][$k] == trim($arrLine['1']) ) {
					$arrBackData[$v] = '距离'.$arrMore['F'][$k].$arrMore['S'][$k].'站'.$v.'米';
				}
			}
		}
// 		var_dump($arrBackData);
		if ( empty($arrBackData) ) {
			return '';
		}
		ksort($arrBackData);
		return reset($arrBackData);
	}
	
	/**
	 * 
	 * @param unknown $strMore
	 * @param string $strType
	 * @param string $intBackArray
	 * @param unknown $strSch 从strSch中分离出学校
	 * @return multitype:unknown |string|mixed
	 */
	public static function DistanceSch($strMore, $strType = 'school', $intBackArray = false ,$strSch ) {
		$arrBackData = array();
		$strSch = str_replace(array('@around_school','"'), '', $strSch);//"@around_school "小学兲北京市海淀区中关村第一小学"" 
		$strSch = explode('兲', $strSch);
		preg_match_all('/(?P<F>[^,].+)兲(?P<S>.+)兲(?P<T>.+)分钟兲(?P<time>[0-9]+?)/U', $strMore, $arrMore);
		if ( $intBackArray === true ) {
			return array('F' => $arrMore['F'], 'S' => $arrMore['S'], 'M' => $arrMore['time']);
		}
		if ( !empty($arrMore['time']) ) {
			foreach ( $arrMore['time'] as $k => $v ) {
				if ( $arrMore['S'][$k] == trim($strSch['1']) ) {
					$arrBackData[$v] = '距离'.$arrMore['S'][$k].$v.'米';
				}
			}
		}
		// 		var_dump($arrBackData);
		if ( empty($arrBackData) ) {
			return '';
		}
		ksort($arrBackData);
		return reset($arrBackData);
	}
	
	
	
	/**
     * 遍历去除数组中元素的前后空格
     *
     * @param array $data 原始数据
     * @return array 处理后数据
     */
	public static function Trim( $data = array() )
	{
		if ( empty($data) || !is_array($data) ) {
			return $data;
		}
		if ( !empty($data) ) {
			$arrBack = array();
			foreach ( $data as $k => $v ) {
				$arrBack[$k] = trim($v);
			}
		}
		return $arrBack;
	}
	
	/**
     * 强制字符型转为整型
     * 
     * @param int|array $data
     * @return bool
     * @author libo <neroli@sohu-inc.com>
	 * @date 2013-01-15
     */
	public static function TransInt($data = array())
	{
		if ( empty($data) || !is_array($data) ) {
			return intval($data);
		}
		if ( is_array($data) ) {
			foreach($data as &$value)
			{
				$value = Utility::TransInt($value);
			}
			return $data;
		}
	}

	/**
	 * 获取城市id 如果参数城市id为空时取cookie里面记录的城市id
	 *
	 * @param int $city_id
	 * @return int $city_id
	 */
	public static function getPersonCityIdForConfig($city_id = 0){
		$city_id = (int)$city_id;
		if($city_id <= 0){
			//========== ========== 判断当前城市 ========== ==========
			$strCookieDomain = isset($_COOKIE['esf_city_name'])? trim($_COOKIE['esf_city_name']): ''; //这个地方没有调用公共的方法获取cookie
			if( empty($strCookieDomain) ){
				$strCityAbbr = 'bj';
			}else{
				$tmp = explode( '.', $strCookieDomain); //此变量只是临时用，用前请重新赋值
				$strCityAbbr = $tmp[0];
				//如果cookie中记录的是南京 则转变为北京
				if(isset($GLOBALS['IGNORE_CITY_IDS'][$strCityAbbr])){
					$strCityAbbr = 'bj';
					$city_id = 1;
				}
			}
			$arrCity = CCity::getAllCity();
			//========== ========== /判断当前城市 ========== ==========	
			foreach( $arrCity as $value){
			    if( $value['pinyinAbbr']==$strCityAbbr ){
			    	$city_id = $value['id'];
			    }
			}					
		}
		return $city_id;		
	}
		
	/**
	 * 加载头部所需数据信息 根据城市id或cookie中记录的城市信息
	 *
	 * @param int $city_id
	 * @return NULL
	 */
	public static function getPersonHeaderInfo($city_id = 0){
		$city_id = empty($city_id)? 1: intval($city_id);

		//这里初始化一些公用性强的数据
		$objFSInit = new Initialize();
		$objFSInit->Instance(array('city', 'district', 'subwayline', 'region'));
		$arrDistrict = CDist::getSearchConfig($city_id);
		$arrNowCity = $GLOBALS['city_all'][$city_id];
		//加载当前城市的配置文件
		require(dirname(__FILE__) . '/../config/'.$GLOBALS['city_all'][$GLOBALS['CITY_ID']]['pinyinAbbr'].'.config.inc.php');
		$strSaleDomain = _DEFAULT_DOMAIN_;
		$strRentDomain = _ZU_DOMAIN_;
		
		global $di;
		$personController=$di->getShared('dispatcher')->getActiveController();
		$personController->assign(array(
			'strSaleDomain' => $strSaleDomain,
			'strRentDomain' => $strRentDomain,
			'arrCity'		=> $GLOBALS['city_all'],
			'arrNowCity'	=> $arrNowCity,
			'arrGlobalsCity'=> $arrNowCity, //这个参数是为了兼容公共头部文件的参数统一
			'local_city_'	=> 'http://'.$arrNowCity['pinyinAbbr'].$strSaleDomain,
			'rent_local_city_'	=> 'http://'.$arrNowCity['pinyinAbbr'].$strRentDomain,
			'CITY_TEL'      => $GLOBALS['CITY_TEL'],  //城市销售电话，在config文件中配置
			'arrDistrict'	=> $arrDistrict,
			'arrSubwayLine'	=> $GLOBALS['subwayline'],
			'arrSalePriceRange'	=> $GLOBALS['UNIT_SALE_PRICE_RANGE'],
			'arrRoomType'	=>	$GLOBALS['SEARCH_UNIT_BEDROOM'],//房源居室
			'_ent_type'		=> Utility::parseCookie('_ent_type'),//记录登陆的用户类型
		));	
	}
	
	/**
	 * 设置我们为你推荐的房源cookie信息
	 * 存的房源5分钟失效
	 * 条件存60天
	 * 
	 * @param array $arrCondition 搜索条件
	 * @param int $unit_type 房源类型
	 * @return booleans
	 */
	static public function setRecommendUnitCondition(array $arrCondition = array(), $unit_type = UNIT_TYPE_SALE){
	  	$strCookieName = 'esf_recommend_unit_search';
		$arrCookie = Utility::parseCookie($strCookieName);
		/*
		 * $arrCookie = {
		 *                 'city_id' => 城市id,//当切换城市后这个cookie信息都变成当前城市的，也就是一切换以前存的都清除
		 *                 'search_con' => array(
		 *                                          UNIT_TYPE_SALE => $arrCondition,//出售条件
		 *                                          UNIT_TYPE_RENT => $arrCondition,//出租条件
		 *                                          'i'.UNIT_TYPE_SALE => $arrCondition,//个人出售条件
		 *                                          'i'.UNIT_TYPE_RENT => $arrCondition,//个人出租条件
		 *                                      ),
		 *                 UNIT_TYPE_SALE      => array(//出售房源
		 *                                          'ctime' => $GLOBALS['_NOW_TIME'],//存放cookie时的当前时间
		 *                                          'units' => $arrUnitIds,//出售房源ids
		 *                                      ),
		 *                 UNIT_TYPE_RENT      => array(//出租房源
		 *                                          'ctime' => $GLOBALS['_NOW_TIME'],//存放cookie时的当前时间
		 *                                          'units' => $arrUnitIds,//出租房源ids
		 *                                      ),
		 *                 'i'.UNIT_TYPE_SALE      => array(//个人出售房源
		 *                                          'ctime' => $GLOBALS['_NOW_TIME'],//存放cookie时的当前时间
		 *                                          'units' => $arrUnitIds,//个人出售房源ids
		 *                                      ),
		 *                 'i'.UNIT_TYPE_RENT      => array(//个人出租房源
		 *                                          'ctime' => $GLOBALS['_NOW_TIME'],//存放cookie时的当前时间
		 *                                          'units' => $arrUnitIds,//个人出租房源ids
		 *                                      ),              
		 *              }		
		*/
	  	if(!empty($arrCondition)){
	  		if($arrCookie['city_id'] != $GLOBALS['CITY_ID']){
	  			$arrCookie = array();
	  		}
	  		$arrCookie['city_id'] = $GLOBALS['CITY_ID'];
	  		$arrCookie['search_con'][$unit_type] = $arrCondition;
	  		//缓存60天
	  		Utility::setCookie($strCookieName, $arrCookie, $GLOBALS['_NOW_TIME']+5184000, '/', Utility::MAIN_COOKIE_DOMAIIN);
	  	}
	  	return true;
	 }	
	 
	/**
     * 刷新验证码
     */
	public static function refreshCheckCode()
	{
		echo "<script src=\"http://gitsrc.esf.focus.cn/vip/js/jquery-1.7.2.min.js\"></script>";
		echo "<script type=\"text/javascript\">$('input[name=\"yanzheng\"]', parent.document).val('');</script>";
		echo "<script type=\"text/javascript\">$('#checkcodelogin', parent.document).attr('src', '/checkcode/code?'+Date.parse(new Date()));</script>";exit;
		exit;
	}
	/**
	 * 去掉小数后面的零
	 * @param unknown_type $num
	 * @return float $returnNum
	 */
	public static function delzero($num)
	{
		$num = floatval($num);
		$numArr = explode(".",$num);
		$returnNum = $numArr[0];
		if(!isset($numArr[1])) return $returnNum;
		$dotNum = strlen($numArr[1])-1;
		for($i=$dotNum;$i>=0;$i--){
			if($numArr[1][$i]>0){
				$returnNum .='.'.substr($numArr[1], 0, $i+1);
				break;
			}
	
		}
		return floatval($returnNum);
	}
	/**
	 * Excel 导出
	 * @author Eric
 	 * @date 2014年4月1日15:09:53
	 * @param string $filename
	 * @return array $returnArr
	 * 
	 */
	public static function ExportExcel($filename){
		require_once dirname(__FILE__) . "/../class/PHPExcel/IOFactory.php";
		require_once dirname(__FILE__) . "/../class/PHPExcel.php";
		 
		// Check prerequisites
		if (!file_exists($filename)) {
			return false;
		}

		$objExcel = new PHPExcel();
		//判断文件类型03or07
		$fileArr=explode('.', $filename);
		$ext=$fileArr[count($fileArr)-1];
		if(strtolower($ext)=='xlsx'){
			$excelType='Excel2007';
		}else{
			$excelType='Excel5';
		}
		$reader = PHPExcel_IOFactory::createReader($excelType);
		$PHPExcel = $reader->load($filename); // 载入excel文件
		
		$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumm = $sheet->getHighestColumn(); // 取得总列数
		 
		/** 循环读取每个单元格的数据 */
		$i=0;
		for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
		    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
		        $dataset[$i][] = $sheet->getCell($column.$row)->getValue();
		        //echo $column.$row.":".$sheet->getCell($column.$row)->getValue()."<br />";
		    }
			$i++;
		}
		/*
		echo '<pre>';
		print_r($dataset);
		*/
		return $dataset;
	}

    /*
     * 转码
     */
    static public function iconvArray($data, $in_charset, $out_charset){
        if (!$data) return false;

        if(is_array($data)){
            foreach ($data as $key=>$value){
                $key = iconv($in_charset,$out_charset, $key);
                if (is_array($value)){
                    $rtn[$key] = self::iconvArray($value, $in_charset, $out_charset);
                }
                else{
                    $rtn[$key] = iconv($in_charset,$out_charset,$value);
                }
            }
        }elseif(is_string($data)){
            $rtn=iconv($in_charset,$out_charset,$data);
        }
        else{
            $rtn=$data;
        }
        return $rtn;
    }

    //新版轨道交通规则
    public static function NewSubDistance($strMore) {
        $arrBackData = array();
        preg_match_all('/(?P<F>[^,].+)兲(?P<S>.+)兲(?P<T>.+)分钟兲(?P<time>[0-9]+?)/U', $strMore, $arrMore);
        //按照距离进行排序处理

        if(!empty($arrMore['F'])){
            $arrInfo = array();
            $arrTime = array();
            $rhSub = array();
            foreach ($arrMore['F'] as $k => $data){
                if(isset($rhSub[$data]) && $rhSub[$data]['time']<=$arrMore['time'][$k]) continue;
                $rhSub[$data] = array('k'=>$k,'time'=>$arrMore['time'][$k]);

            }
            foreach($rhSub as $subwayName => $data){
              //  $arrInfo[$data['k']] = array('F'=>$subwayName, 'S'=>$arrMore['S'][$data['k']], 'time'=>$arrMore['time'][$data['k']]);
                $arrTime['F'][] = $arrMore['F'][$data['k']];
                $arrTime['S'][] = $arrMore['S'][$data['k']];
                $arrTime['M'][] = $arrMore['time'][$data['k']];
            }

        }
        return array('F' => $arrTime['F'], 'S' => $arrTime['S'], 'M' => $arrTime['time']);
    }
    
   	/**
   	 * @abstract 获取相关页面的热门搜索的专题静态页面
     * @author xuminwan@sohu-inc.com
     * @param str 专题静态列表的文件名
   	 */
    static public function getTopicHotSearch($strTopicName)
    {
    	if( !file_exists($strTopicName) ) return false;
		$arrTopic = array();
	    $arrTopicKey = array('name','url');
	    $resTopic = @fopen($strTopicName,'r');
	    while($strLine = fgets($resTopic))
	    {
	    	if( $strLine )
	    	{
	    		$arrTopicLine = explode('	', rtrim($strLine));
	    		$arrTopic[] = array_combine($arrTopicKey, $arrTopicLine);
	    	}
	    }
	    fclose($resTopic);
    	return $arrTopic;
    }

    static public function getPriceTrend($array=array()){
        $districtInfo = MDistrict::Instance()->getDistrict();
        $blockInfo = MHotArea::Instance()->getHotArea();
        $rhDistrict = array();
        $rhBlock = array();
        foreach ($districtInfo as $value){

            $rhDistrict[$value['district_id']] = $value['district_name'];
        }
        if ($blockInfo){
            foreach ($blockInfo as $value){
                $rhBlock[$value['hot_area_id']] = $value['hot_area_name'];
            }
        }

        //城市id
        $city_id = isset($array['city_id']) ? intval($array['city_id']) : $GLOBALS['CITY_ID'];
        //城区id
        $district_id  = intval($array['district_id']);
        //版块id
        $hot_area_id  = intval($array['hot_area_id']);
        //小区id
        $house_id   = intval($array['house_id']);
//        //对比的小区id
//        $_house_id 	=  intval($array['_house_id']);
        //筛选时间点
        $length = !empty($array['length']) ? intval($array['length']) : 6;
        //增加是否现象 曲线名称的表示，1表示显示，0 表示不显示
        $flag = intval($array['flag']);
        $objEsfCache = CEsfCache::Instance();
        //构造对应的数据名称
        if($city_id > 0) {//城市
            $cityName = $GLOBALS['arrGlobalsCity']['city_name'];
            $arrInfo["{$cityName}"] = $objEsfCache->getMonthCityAvgPriceById($city_id,$length);
        }
        if($district_id > 0) { //城区
            $distName = !empty($rhDistrict[$district_id]) ? $rhDistrict[$district_id] : '小区';
            $arrInfo["{$distName}城区"] = $objEsfCache->getMonthDistrictAvgPriceById($district_id,$length);
        }
        if($hot_area_id > 0) {//版块
            $hotAreaName = !empty($rhBlock[$hot_area_id]) ? $rhBlock[$hot_area_id] : '板块';
            $arrInfo["{$hotAreaName}版块"] = $objEsfCache->getMonthHotAreaAvgPriceById($hot_area_id,$length);
        }
        if($house_id > 0){//小区
            $arrHouse = MHouse::Instance()->getHouseById($house_id);
            $houseName = !empty($arrHouse['house_name']) ? trim($arrHouse['house_name']) : '小区';
            unset($arrHouse);
            $arrInfo["{$houseName}小区"] = $objEsfCache->getMonthHouseAvgPriceById($house_id,$length);
        }
//        if($_house_id > 0){//对比的小区id
//            $arrHouse = MHouse::Instance()->getHouseById($_house_id);
//            $houseName = !empty($arrHouse['house_name']) ? trim($arrHouse['house_name']) : '小区';
//            unset($arrHouse);
//            $arrInfo["{$houseName}小区"] = $objEsfCache->getMonthHouseAvgPriceById($_house_id,$length);
//        }


        //构造获取数据对象
        if ( !empty($arrInfo) ) {
            foreach ( $arrInfo as $name => $avg ) {
                if (!$name) continue;
                foreach($avg as $valuse) {
                    if(intval($valuse) > 0) {
                        $arrAvgPrice[] = $valuse;
                    }
                }
                if($flag > 0) {
                    $line["{$name}"] = array_values($avg);
                }else {
                    $line[''] = array_values($avg);
                }
                $left_min = min($arrAvgPrice);
                $left_max = max($arrAvgPrice);
            }
        }
//print_r($arrAvgPrice);
//构造横坐标的月份日期
//        $time = strtotime(date("Y-m-01"));
//        for ($i = $length -1 ;$i >= 0;$i--) {
//            $label[] = date("n月",strtotime("-{$i} month",$time));
//        }
        unset($arrInfo,$arrAvgPrice);
        //构造显示的数据
        $left_max = $left_max+2000;
        if($left_min>4000){
            $left_min = $left_min-2000;
            $left_min = intval($left_min/1000)*1000;
        }else{
            $left_min = 0;
        }

        $steps = 1000;
//判断显示的刻度区间
        if(($left_max-$left_min)>40000){
            $steps = 10000;
        }elseif (($left_max-$left_min)>30000){
            $steps = 5000;
        }elseif (($left_max-$left_min)>15000){
            $steps = 3000;
        }elseif (($left_max-$left_min)>8000){
            $steps = 2000;
        }
        $time = strtotime(date("Y-m-01"));
        for ($i = $length -1 ;$i >= 0;$i--) {
            $label[] = date("n月",strtotime("-{$i} month",$time));
        }
        return array('line'=>$line,'step'=>$steps,'label'=>$label,'maxValue'=>$left_max,'minValue'=>$left_min);
    }
    
    public static function unsetCookieInfo($cookiename,$domain="esf.focus.cn")
    {
    	$flag = setcookie($cookiename, null, time()-3600, "/", $domain, 0);
    	if ($flag) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * CSV文件导入,第一行默认不处理,成功返回数组.
     * @return 
     */
    public static function importCsv() {
        if (!empty($_FILES)) {
            $tempFile = $_FILES['filecsv']['tmp_name'];
            $fileTypes = array('csv'); // File extensions
            $fileParts = pathinfo($_FILES['filecsv']['name']);

            if (in_array($fileParts['extension'], $fileTypes)) {
                $handle = fopen($tempFile, "r");
                $row = 0;
                $rs = array();
                while ($data = fgetcsv($handle, 1000, ',')) {                  
                    $row++;
                    if ($row == 1) {
                        continue;
                    }
                    if (is_array($data)) {
                        $rs[] = $data;
                    }
                }
                fclose($handle);
                return self::gbkToutf8($rs);
            } else {
                return 1;  //文件类型不正确
            }
        }
        return 2; //没有上传文件
    }
    
    /**
     * 编码转换 GBK 转 UTF8 
     * @param mix(string array) $str
     * @return mix
     */
    public static function gbkToutf8($str) {
        if (is_array($str) && $str) {
            foreach ($str as $_k => $_v) {
                if (is_array($_v)) {
                    $str[$_k] = self::gbkToutf8($_v);
                } else {
                    $str[$_k] = @iconv('GB18030', 'UTF-8', $_v);
                }
            }
        } else {
            $str = @iconv('GB18030', 'UTF-8', $str);
        }
        return $str;
    }

    /**
     * 编码转换 UTF8 转  GBK
     * @param mix(string array) $str
     * @return mix
     */
    public static function utf8Togbk($str) {
        if (is_array($str) && $str) {
            foreach ($str as $_k => $_v) {
                if (is_array($_v)) {
                    $str[$_k] = self::utf8Togbk($_v);
                } else {
                    $str[$_k] = @iconv('UTF-8', 'GB18030', $_v);
                }
            }
        } else {
            $str = @iconv('UTF-8', 'GB18030', $str);
        }
        return $str;
    }
    
    /**
     * 是否是有效帐户 true 表示合法
     * 帐号输入不合法，长度为5-16位，请以字母、数字、下划线来命名!
     */
    public static function IsAccount($str)
    {
    	$str=trim($str);
    	$pattern='/^\w{5,16}$/';
    	if(preg_match($pattern, $str))
    		return true;
    	return false;
    }	
    
    /**
     * @abstract 创建CSV文件
     *
     * @param 文件名称 $file
     * @param 文件内容 $msg
     */
    public function creatCsv($file,$msg){
    	if(empty($file)) {
    		return false;
    	}
    	self::createFolder(dirname($file));
    	if (!$handle = fopen($file, 'w')) {
    		return false;
    	}
    	if (fwrite($handle, $msg) === FALSE) {
    		return false;
    	}
    	fclose($handle);
    	unset($file,$msg);  
    }
    
    /**
     * @abstract 创建目录
     *
     * @param string $path
     *
     */
    public static function createFolder($path)
    {
    	if (!file_exists($path)) {
    		self::createFolder(dirname($path));
    		mkdir($path, 0777);
    	}
    }

    //url base64编码
    static function urlSafeB64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
    //url base64解码
    static function urlSafeB64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * 获取经纪人的房销宝邀请码
     * @param int $comId 公司ID
     * @param int $realId 经纪人ID
     * @param int $shopId 门店ID
     * @return string
     */
    static public function getRealtorCode($comId, $realId, $shopId) {
        if ( empty($comId) || empty($realId) ) {
            return "";
        }
        $strRealtorCode = md5($comId.$realId.$shopId);
        return substr($strRealtorCode, 17, 6);
    }

    static public function num_format($num){
        if(!is_numeric($num)){
            return false;
        }
        $rvalue='';
        $num = explode('.',$num);//把整数和小数分开
        $rl = !isset($num['1']) ? '' : $num['1'];//小数部分的值
        $j = strlen($num[0]) % 3;//整数有多少位
        $sl = substr($num[0], 0, $j);//前面不满三位的数取出来
        $sr = substr($num[0], $j);//后面的满三位的数取出来
        $i = 0;
        while($i <= strlen($sr)){
            $rvalue = $rvalue.','.substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开
            $i = $i + 3;
        }
        $rvalue = $sl.$rvalue;
        $rvalue = substr($rvalue,0,strlen($rvalue)-1);//去掉最后一个逗号
        $rvalue = explode(',',$rvalue);//分解成数组
        if($rvalue[0]==0){
            array_shift($rvalue);//如果第一个元素为0，删除第一个元素
        }
        $rv = $rvalue[0];//前面不满三位的数
        for($i = 1; $i < count($rvalue); $i++){
            $rv = $rv.','.$rvalue[$i];
        }
        if(!empty($rl)){
            $rvalue = $rv.'.'.$rl;//小数不为空，整数和小数合并
        }else{
            $rvalue = $rv;//小数为空，只有整数
        }
        return $rvalue;
    }
}

