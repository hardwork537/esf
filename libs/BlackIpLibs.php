<?php
/**
 * 该类目前只有焦点通房源管理中的手动刷新才会用到
 * Raul
 * @date 2014/10/14
 */
class BlackIpLibs
{
	/**
	 * 手动刷新校验，是否延时处理
	 * @param string $strIp
	 * @return number
	 */
	public static function AutoMonitorFlush($strIp = '')
	{
		$intNowTime = time();
		//获取今日的凌晨时间
		$time = strtotime(date('Y-m-d', $intNowTime));
		//开启监控时间段目前为早8点-晚8点
		if ( $intNowTime < $time+3600*8 || $intNowTime > $time+3600*20 ) return 0;
		//因监控未后台操作，没有获取到用户信息的将忽略
		if ( empty($GLOBALS['client']) ) return 0;
		//因主要监控企业经纪人，独立经纪人忽略
		if ( empty($GLOBALS['client']['shopId']) ) return 0;
		//授权IP白名单
		$arrWhiteIps = array(
				'61.144.221.177',
		);
		//IP白名单的访问用户忽略
		if ( in_array($strIp, $arrWhiteIps) ) return 0;
		//获取监控IP
		if ( empty($strIp) ) $strIp = Utility::GetUserIP();
		/*
		 * 数据存储结构为如下结构
		* array(
			 *     '127.0.0.1' => 5,
			 *     '192.168.0.1' => 3
			 * )
		*/
		//从MC中获取存储数据
		$arrAgentIps =  Mem::Instance()->Get("blacklist_agent_{$GLOBALS['client']['shopId']}");
		//向指定访问IP增加访问次数记录
		$arrAgentIps[$strIp] += 1;
		//将新增的访问写入记录
		$arrAgentIps =  Mem::Instance()->Set('blacklist_agent_'.$GLOBALS['client']['shopId'], $arrAgentIps, 600);
		if ( count($arrAgentIps) <= 1 ) {
			return 0;
		}
		//对数组进行逆向排序
		arsort($arrAgentIps);
		//理想认为，使用最多的IP为门店真是IP，此类IP不进行延时处理，否则其他的视为非法来源IP，进行自动延时
		foreach ( $arrAgentIps as $key => $item ) {
			if ( $strIp == $key ) return 0;
			break;
		}
		$intRandTime = rand(1, 30);
		Log::ErrorWrite('vip',"flushAction", '【'.date("Y-m-d H:i:s").'】	公司ID：' . $GLOBALS['client']['company_id'] . '	门店ID：' . $GLOBALS['client']['agent_id'] . "	经纪人ID：" . $GLOBALS['client']['broker_id'] . "	IP：".$strIp."	MESSAGE：门店多个IP访问，被认为非正常请求，操作被延时{$intRandTime}秒\n", FILE_APPEND);
		//根据访问情况返回延时描述
		return $intRandTime;
	}
}
