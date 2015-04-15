<?php
class Health{
	/**
	 * 检测房源基础属性是否健康
	 * 判断标准1：标题不能重复
	 * 判断标准2：小区，面积，楼层数，室，厅，卫不能完全一致
	 *
	 * @param string $strHouseType
	 * @param int $intRealId
	 * @param array $arrData
	 * @param int $intHouseId
	 * @return bool
	 */
	public static function CheckBody($strHouseType = 'Sale', $intRealId = 0, $arrData = array(), $intHouseId = 0)
	{
		//构建查询条件
		$strCondition = "h.realId = ".$intRealId;
		$strCondition .= " AND h.status = ".House::STATUS_ONLINE;
		if ( $intHouseId > 0 ) $strCondition = "h.id != ".$intHouseId;
		//过滤提交标题中的非中文数据
		preg_match_all("/[\x{4e00}-\x{9fa5}A-Za-z0-9]+/u", $arrData['title'], $arrWords);
		if ( count($arrWords[0]) > 0 )
		{
			$strNewWords = "";
			foreach ( $arrWords[0] as $words )
			{
				$strNewWords .= $words;
			}
		}
		$arrData['title'] = $strNewWords;
		//加载当前经纪人的所有有效房源(上架，下架)
		if ( $strHouseType == 'Sale' )
		{
			$arrHouse = Sale::query()
				->columns('h.id,title,h.parkId,h.bA,h.floor,h.bedRoom,h.livingRoom,h.bathRoom')
				->where($strCondition)
				->leftJoin('House', 'h.id = Sale.houseId', 'h')
				->limit(1000)
				->execute()
				->toArray();
		} 
		else 
		{
			$arrHouse = Rent::query()
				->columns('h.id,title,h.parkId,h.bA,h.floor,h.bedRoom,h.livingRoom,h.bathRoom')
				->where($strCondition)
				->leftJoin('House', 'h.id = Rent.houseId', 'h')
				->limit(1000)
				->execute()
				->toArray();
		}
		//构造审核标准
		if ( !empty($arrUnit) ) 
		{
			foreach ( $arrUnit as $k => $v ) {
				$strNewWords = '';
				preg_match_all("/[\x{4e00}-\x{9fa5}A-Za-z0-9]+/u", $v['title'], $arrWords);
				if ( count($arrWords[0]) > 0 ) {
					foreach ( $arrWords[0] as $words ) 
					{
						$strNewWords .= $words;
					}
				}
				$arrUnitId[] = $v['id'];
				$arrTitle[md5($strNewWords)] = 1;
				$arrAttribute[$v['parkId'].'_'.intval($v['bA']).'_'.$v['floor'].'_'.$v['bedRoom'].'_'.$v['livingRoom'].'_'.$v['bathRoom']] = 1;
			}
		}
		//释放中间变量
		unset($arrUnit);
		//检测是否触发标题审核标准
		if ( isset($arrTitle[md5($arrData['title'])]) ) 
		{
			unset($arrTitle);
			return false;
		}
		//释放中间变量
		unset($arrTitle);
		//检测是否触发属性审核标准
		if ( isset($arrData['livingRoom']) && isset($arrData['bathRoom']) && isset($arrAttribute[$arrData['parkId'].'_'.intval($arrData['bA']).'_'.$arrData['floor'].'_'.$arrData['bedRoom'].'_'.$arrData['livingRoom'].'_'.$arrData['bathRoom']]) )
		{
			unset($arrAttribute);
			return false;
		}
		//释放中间变量
		unset($arrAttribute);
		return true;
	}
	
	/**
	 * 检测房源图片是否健康
	 *
	 * @param string $strHouseType
	 * @param int $intRealId
	 * @param array $arrData
	 * @param int $intHouseId
	 * @return bool
	 */
	public static function CheckImage($strHouseType = 'Sale', $intRealId = 0, $arrData = array(), $intHouseId = 0)
	{
		$arrImageData = array();
		//格式化请求数据
		if ( !empty($arrData['shinei']['img']) ) 
		{
			foreach ( $arrData['shinei']['img'] as $k => $v ) 
			{
				$arrP = explode('.', $v);
				$arrImageData[] = $arrP[0];
			}
		}
		//构建查询条件
		$strCondition = "realId = ".$intRealId;
		$strCondition .= " AND status = ".House::STATUS_ONLINE;
		if ( $intHouseId > 0 ) $strCondition = "id != ".$intHouseId;
		//加载当前经纪人的所有有效房源(上架，下架)
		$arrHouse = House::find(array($strCondition, 'columns' => 'id', 'limit' => 10000))->toArray();
		
		//获取房源ID
		if ( !empty($arrHouse) ) 
		{
			$strHouseId = '';

			foreach ( $arrHouse as $k => $v ) 
			{
				$strHouseId .= $v['id'].',';
			}
			$strHouseId = trim($strHouseId, ',');
		
			//释放中间变量
			unset($arrHouse);
			$strCondition = "houseId IN(".$strHouseId.")";
			$strCondition .= " AND type = ".HousePicture::IMG_SHINEI;
			//获取所有房源的室内图
			$arrImage = HousePicture::find(array($strCondition, 'columns' => 'imgId', 'limit' => 10000));
			if ( !empty($arrImage) ) 
			{
				foreach ( $arrImage as $k => $v ) 
				{
					$arrUseImage[] = $v->imgId;
				}
			}
			//检测新传图片是否存在已使用
			if ( !empty($arrUseImage) && array_intersect($arrImageData, $arrUseImage) ) 
			{
				return false;
			}
		}
		return true;
	}
}
