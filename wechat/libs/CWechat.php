<?php
/**
 * 微信服务号交互操作类
 * @author jackchen
 * @since 2014-09-15
 */
class CWechat extends  BaseModel{
	
	private $mErrorNo;
	private $mErrorMsg;
	
	private static $mInstance;
	private function __clone() {}
	
	public static function getInstance()
	{
		if(!isset(self::$mInstance))
		{
			self::$mInstance = new CWechat();
		}
		return self::$mInstance;
	}
	
	public function getErrorNo()
	{
		return $this->mErrorNo;
	}
	
	public function setErrorNo($intErrorNo)
	{
		$this->mErrorNo = $intErrorNo;
	}
	
	public function getErrorMsg()
	{
		return $this->mErrorMsg;
	}
	
	public function setErrorMsg($strMsg)
	{
		$this->mErrorMsg = $strMsg;
	}
	
	/**
	 * 上架/下架房源
	 * @param array $aryUnitId 需要上架/下架的房源ID
	 * @param int $intBrokerId 经纪人ID
	 * @param string $strActtype 操作类型（上架-online,下架-offline）
	 * @param string $strUnitType 房源类型（出售-sale,出租-rent）
	 * @return boolean
	 */
	public function setUnitStatus($aryUnitId, $intBrokerId, $strActtype= 'online', $strUnitType = 'sale')
	{
	
		$intSuccessNum = 0;
		if(!in_array($strActtype, array('online','offline')) || !is_array($aryUnitId) || empty($aryUnitId)
		|| !in_array($strUnitType, array('sale','rent')) || preg_match('/^\d{1,20}$/', $intBrokerId) == 0)
		{
			$this->setErrorNo(-1);
			$this->setErrorMsg('参数错误');
			return false;
		}
		
		foreach($aryUnitId as $intUnitId)
		{
			if(preg_match('/^\d{1,20}$/', $intUnitId) == 0)
			{
				$this->setErrorNo(-1);
				$this->setErrorMsg('参数错误');
				return false;
			}
		}

        /*获取经纪人信息，判断经纪人是否有效*/
        $objRealtor = new Realtor();   //经纪人
        $aryBrokerInfo = $objRealtor->getRealtorById($intBrokerId)->toArray();

        if(!in_array($aryBrokerInfo['status'], array(Realtor::STATUS_OPEN, Realtor::STATUS_FREE)))
        {
            $this->setErrorNo(-2);
            $this->setErrorMsg('没有操作权限');
            return false;
        }

        /*获取经纪人所有上架/下架的房源ID,检查需要上架/下架的房源是否都是操作者自己的房源*/
		$aryUnitStatus = array(0=>House::STATUS_DELETE,1=>House::STATUS_RECYCLE);
        if($strUnitType == 'sale')
        {
            $houseType = 'Sale';
        }
        else
        {
            $houseType = 'Rent';
        }
        $objhouse = new House();
		$aryUnitInfo = $objhouse->getUnitByIds($aryUnitId,$houseType)->toArray();

		foreach ($aryUnitInfo as $unit)
		{
			if($unit['realId'] != $intBrokerId)
			{
				$this->setErrorNo(-3);
				$this->setErrorMsg('非法请求');
				return false;
			}

			if(in_array($unit['status'],$aryUnitStatus)) 
			{
				$this->setErrorNo(-4);
				$strErrMsg = count($aryUnitId) > 1 ? '部分房源已被删除，请重新操作' : '该房源不存在';
				$this->setErrorMsg($strErrMsg);
				return false;
			}

			if($strActtype == 'offline')
			{
				if($unit['status'] == House::STATUS_OFFLINE)
				{
					$this->setErrorNo(-5);
					$this->setErrorMsg('不能重复下架');
					return false;
				}
			}
			else 
			{
				if($unit['status'] == House::STATUS_ONLINE)
				{
					$this->setErrorNo(-5);
					$this->setErrorMsg('不能重复上架');
					return false;
				}

				if($unit['verification']  == House::HOUSE_VERNO)
				{
					$this->setErrorNo(-5);
					$this->setErrorMsg('违规房源不能上架');
					return false;
				}

			}
		}

		if($strActtype == 'online')
		{
			/*发布房源需要检测剩余发布数量*/
            $arrorderport = RealtorPort::instance()->getAccountByRealId($aryBrokerInfo['id']);
            if($arrorderport != false)
            {
                $arrorderport =  $arrorderport->toArray();
            }
            if($strUnitType == 'sale')
            {
                $intLeftNum = $arrorderport['saleRelease'];
            }
            else
            {
                $intLeftNum = $arrorderport['rentRelease'];
            }
            $intLeftNum = $intLeftNum < 1 ? 0 : $intLeftNum;

			/*如果剩余发布数量不足，则只上架其中一部分房源（前面的$intLeftNum个房源）*/
			if($intLeftNum - count($aryUnitId) < 0)
			{
				$aryUnitId = array_slice($aryUnitId, 0, $intLeftNum);
			}
		}
		
		$intSuccessNum = count($aryUnitId);
		$strSuccessIds = implode(',', $aryUnitId);

		/*更新房源状态*/
        $arrUpdate = array();
		if($strActtype == 'online')
		{
            $mixedResult = $objhouse->onLineWechatUnit($aryUnitId,time(),$aryBrokerInfo['id'],$aryBrokerInfo['cityId']);   //上架wechat
		}
		else 
		{
            $mixedResult = $objhouse->offLineWechatUnit($aryUnitId,time(),$aryBrokerInfo['id'],$aryBrokerInfo['cityId']);   //下架wechat
		}


		if($mixedResult === false)
		{
			$this->setErrorNo(-7);
			$this->setErrorMsg('系统错误');
			return false;
		}

        //更新房源可发布数量
        //RealtorPort::instance()->upRealtorPublicPortNum($aryBrokerInfo['id'],$intSuccessNum,$strActtype,$strUnitType);

		return array('successNum'=>$intSuccessNum, 'successIds'=>$strSuccessIds);
	}
	
	/**
	 * 刷新房源
	 * @param array $aryUnitId 需要刷新的房源ID
	 * @param int $intBrokerId 经纪人ID
	 * @param string $strUnitType 房源类型(sale-出售, rent-出租)
     * @return boolean
	 */
	public function flush($aryUnitId, $intBrokerId, $strUnitType = 'sale')
	{
		if(!is_array($aryUnitId) || empty($aryUnitId) || !in_array($strUnitType, array('sale','rent')) ||
		  preg_match('/^\d{1,20}$/', $intBrokerId) == 0)
		{
			$this->setErrorNo(-1);
			$this->setErrorMsg('参数错误');
			return false;
		}
		foreach($aryUnitId as $intUnitId)
		{
			if(preg_match('/^\d{1,20}$/', $intUnitId) == 0)
			{
				$this->setErrorNo(-1);
				$this->setErrorMsg('参数错误');
				return false;
			}
		}


		/*获取经纪人信息，判断经纪人是否有效*/
        $objRealtor = new Realtor();   //经纪人
        $aryBrokerInfo = $objRealtor->getRealtorById($intBrokerId)->toArray();

		if(!in_array($aryBrokerInfo['status'], array(Realtor::STATUS_OPEN, Realtor::STATUS_FREE)))
		{
			$this->setErrorNo(-2);
			$this->setErrorMsg('没有操作权限');
			return false;
		}

		
		/*批量刷新数量不限制，刷新频率不限制*/
		$intRefreshTime = time();
		$intHour = date('H',$intRefreshTime);
	
		if($intHour >= 0 && $intHour < 3)
		{
			$this->setErrorNo(-3);
			$this->setErrorMsg('请不要再0点--3点刷新');
			return false;
		}

        if($strUnitType == 'sale')
        {
            $houseType = 'Sale';
        }
        else
        {
            $houseType = 'Rent';
        }


		/*刷新房源时间*/
        $objhouse = new House();
		$arrUnitInfo = $objhouse->getUnitByIds($aryUnitId,$houseType)->toArray();

        if($strUnitType == 'sale')
        {
            $objsale = new Sale();
            $objsale->flushHouseByIds($arrUnitInfo);
        }
        else
        {
            $objrent = new Rent();
            $objrent->flushHouseByIds($arrUnitInfo);
        }


        //获取刷新数量
        $objref = new RefreshLog();
        $intOrderFlush = $objref->getUsedFlush($aryBrokerInfo['id'],$houseType,date('Y-m-d'),false);

        return $intOrderFlush;
	}

}


?>