<?php
/**
 * @网安逻辑操作类
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/10/19
 *
 */
class NetsMonitor {
	private $mError;
	private $mCrmUserAccName = 'netmonitor';
	private $mCrmUserName = '网监';
	private $mCrmUserPermission = 1;
	private $mCityId = 0;

	/**
	 * 获取错误描述
	 *
	 * @return string
	 */
	public function getError() {
		return $this->mError;
	}

	/**
	 * 获取独立经纪人房源
	 *
	 * @param int $int_unit_id
	 * @param string $unit_type
	 * @return bool|array
	 */
	public function getIndixRealtorUnit($int_unit_id) {
		$clsUnit = new House();
		return $clsUnit->getOne('id='.$int_unit_id.' and roleType='.House::ROLE_REALTOR);
	}

	/**
	 * 网监删除房源
	 *
	 * @param array $arrData
	 * @return bool
	 */
	public function delIndixRealtorUnit($arrData) {
		if ( empty($arrData) ) {
			$this->mError = '数据不足';
			return false;
		}

		$arrUpdate['status'] = House::STATUS_DELETE;
		$arrUpdate['tags'] = 0;
		$arrUpdate['fine'] = 0;
		$arrUpdate['fineTime'] = '0000-00-00 00:00:00';
		$arrUpdate['delTime'] = date('Y-m-d H:i:s', time());

		//构建日志
        $LogInfo = "【{$arrData['passport']}】删除了{$arrData['unit_type']}_id:{$arrData['id']}";
        $arrLog=array(
	        'crm_user_accname'    =>  $this->mCrmUserAccName,
	        'crm_user_permission' =>  $this->mCrmUserPermission,
	        'city_id'             =>  $this->mCityId,
	        'message'             =>  $LogInfo,
	        'time'                =>  time(),
	        'ip'                  =>  Utility::GetUserIP()
        );
		
		 //事务开始
         $clsUnit = new House();
		 $clsUnit->begin();
         $arrUpdate['status'] = House::STATUS_DELETE;
		 $intFlag = $clsUnit->modifyHouseById($arrData['id'], $arrUpdate);
		 if ( $intFlag === false ) {
			 $clsUnit->rollback();
			 $this->mError = '房源删除失败';
			 return false;
		 }
		 //更新ES索引
		 global $sysES;
		 $clsEs = new Es($sysES['default']);
		 $intFlag = $clsEs->update(array('id' => $arrData['id'], 'data' => $clsEs->houseFormat($arrUpdate)));
		 if ( $intFlag == false ) {
			 $clsUnit->rollback();
			 $this->mError = '房源删除失败';
			 return false;
		 }
		 //删除图片
         $arrParkImages = $this->getUnitParkImageIds($arrData['parkId']);//查找小区图片
         $intFlag = $this->delRealtorUnitImage($arrData['id'], $arrParkImages);
		 if ( $intFlag == false ) {
			 $clsUnit->rollback();
			 $this->mError = '房源删除失败';
			 return false;
		 }
		 $clsUnit->commit();
		 ////TLogAction::Singleton()->LogInsert(new TLogAdmin(AbsLog::LogCrmPersonUnit),$arrLog);
		 $this->mError = '房源删除成功';
		 return true;
	}

    /**
	 * 获取该小区外观图id串
	 * @param int $intParkId 小区id
	 * @return array
	 */
    public function getUnitParkImageIds($intParkId) {
        $clsParkPhoto = new ParkPicture();
        $arrParkPhoto = $clsParkPhoto->getImageByParkId($intParkId);
        $arrParkImageIds = array();
        foreach($arrParkPhoto[ParkPicture::IMAGE_TYPE_WAIGUAN] as $value){
            $arrParkImageIds[] = $value['image_id'];
        }
        return $arrParkImageIds;
    }

	/**
	 * 恢复房源（恢复房源设置为下架状态）
	 *
	 * @param array $arrData
	 * @return bool
	 */
	public function recoverIndixRealtorUnit($arrData) {
		if ( empty($arrData) ) {
			$this->mError = '数据不足';
			return false;
		}

		$arrUpdate['status'] = House::STATUS_OFFLINE;
		$arrUpdate['delTime'] = '0000-00-00 00:00:00';

		//构建日志
        $LogInfo = "【{$arrData['passport']}】恢复了{$arrData['unit_type']}_id:{$arrData['id']}";
        $arrLog=array(
	        'crm_user_accname'    =>  $this->mCrmUserAccName,
	        'crm_user_permission' =>  $this->mCrmUserPermission,
	        'city_id'             =>  $this->mCityId,
	        'message'             =>  $LogInfo,
	        'time'                =>  time(),
	        'ip'                  =>  Utility::GetUserIP()
        );
		
		 //事务开始
         $clsUnit = new House();
		 $clsUnit->begin();
         $arrUpdate['status'] = House::STATUS_DELETE;
		 $intFlag = $clsUnit->modifyHouseById($arrData['id'], $arrUpdate);
		 if ( $intFlag === false ) {
			 $clsUnit->rollback();
			 $this->mError = '房源恢复失败';
			 return false;
		 }
		 //更新ES索引
		 global $sysES;
		 $clsEs = new Es($sysES['default']);
		 $intFlag = $clsEs->update(array('id' => $arrData['id'], 'data' => $clsEs->houseFormat($arrUpdate)));
		 if ( $intFlag == false ) {
			 $clsUnit->rollback();
			 $this->mError = '房源恢复失败';
			 return false;
		 }
		 //恢复图片
         $arrParkImages = $this->getUnitParkImageIds($arrData['parkId']);//查找小区图片
         $intFlag = $this->recoverRealtorUnitImage($arrData['id'], $arrParkImages);
		 if ( $intFlag == false ) {
			 $clsUnit->rollback();
			 $this->mError = '房源恢复失败';
			 return false;
		 }
		 $clsUnit->commit();
		 ////TLogAction::Singleton()->LogInsert(new TLogAdmin(AbsLog::LogCrmPersonUnit),$arrLog);
		 $this->mError = '房源恢复成功';
		 return true;
	}

	/**
	 * 获取个人房源ID
	 *
	 * @param int $int_unit_id
	 */
	public function getPersonUnit($int_unit_id) {
		$clsPersonUnit = new House();
		return $clsPersonUnit->getOne('id='.$int_unit_id.' and roleType='.House::ROLE_SELF);
	}

	/**
	 * 删除个人房源
	 *
	 * @param array $arrData
	 * @return bool
	 */
	public function delPersonUnit($arrData) {
		if ( empty($arrData) ) {
			$this->mError = '数据不足';
			return false;
		}
		//构建日志
		$arrLog=array(
	        'crm_user_accname'    =>  $this->mCrmUserAccName,
	        'crm_user_permission' =>  $this->mCrmUserPermission,
	        'city_id'             =>  $this->mCityId,
	        'unit_id'             =>  $arrData['id'],
	        'action'              =>  3,//操作类型
	        'time'                =>  time(),
	        'ip'                  =>  Utility::GetUserIP()
         );
		 
		 //事务开始
         $clsPersonUnit = new House();
		 $clsPersonUnit->begin();
         $arrUpdate['status'] = House::STATUS_DELETE;
		 $intFlag = $clsPersonUnit->modifyHouseById($arrData['id'], $arrUpdate);
		 if ( $intFlag === false ) {
			 $clsPersonUnit->rollback();
			 $this->mError = '房源删除失败';
			 return false;
		 }
		 //更新ES索引
		 global $sysES;
		 $clsEs = new Es($sysES['default']);
		 $intFlag = $clsEs->update(array('id' => $arrData['id'], 'data' => $clsEs->houseFormat($arrUpdate)));
		 if ( $intFlag == false ) {
			 $clsPersonUnit->rollback();
			 $this->mError = '房源删除失败';
			 return false;
		 }
		 //删除图片
         $intFlag = $this->delPersonUnitImage($arrData['id']);
		 if ( $intFlag == false ) {
			 $clsPersonUnit->rollback();
			 $this->mError = '房源删除失败';
			 return false;
		 }
		 $clsPersonUnit->commit();
		 ////TLogAction::Singleton()->LogInsert(new TLogAdmin(AbsLog::LogCrmPersonUnit),$arrLog);
		 $this->mError = '房源删除成功';
		 return true;
	}

	/**
	 * 恢复个人房源（因涉及超限的问题，不真正执行房源回复）
	 *
	 * @param array $arrData
	 * @return bool
	 */
	public function recoverPersonUnit($arrData) {
		if ( empty($arrData) ) {
			$this->mError = '数据不足';
			return false;
		}
		/**判断线上个人房源是否超限,如超限,恢复失败. */
		$clsPersonal = new House();
		$arrUnitInfo = $clsPersonal->getOne('id='.$arrData['id']);
		$condition['hoId'] = $arrUnitInfo['hoId'];
		if(false == $this->getPersonUnitCountByCondition($condition) ){
			$this->mError = '有效房源已超限';
			return false;
		}

		//构建日志
		$LogInfo = "【{$arrData['passport']}】恢复了{$arrData['unit_type']}_id:{$arrData['id']}";
		$arrLog=array(
	        'crm_user_accname'    =>  $this->mCrmUserAccName,
	        'crm_user_permission' =>  $this->mCrmUserPermission,
	        'city_id'             =>  $this->mCityId,
	        'unit_id'             =>  $arrData['id'],
	        'action'              =>  3,//操作类型
			'message'			  =>  $LogInfo,
	        'time'                =>  time(),
	        'ip'                  =>  Utility::GetUserIP()
         );
		 
		 //事务开始
         $clsPersonUnit = new House();
		 $clsPersonUnit->begin();
         $arrUpdate['status'] = House::STATUS_ONLINE;
		 $intFlag = $clsPersonUnit->modifyHouseById($arrData['id'], $arrUpdate);
		 if ( $intFlag === false ) {
			 $clsPersonUnit->rollback();
			 $this->mError = '房源恢复失败';
			 return false;
		 }
		 //更新ES索引
		 global $sysES;
		 $clsEs = new Es($sysES['default']);
		 $intFlag = $clsEs->update(array('id' => $arrData['id'], 'data' => $clsEs->houseFormat($arrUpdate)));
		 if ( $intFlag == false ) {
			 $clsPersonUnit->rollback();
			 $this->mError = '房源恢复失败';
			 return false;
		 }
		 //删除图片
         $intFlag = $this->recoverPersonUnitImage($arrData['id']);
		 if ( $intFlag == false ) {
			 $clsPersonUnit->rollback();
			 $this->mError = '房源恢复失败';
			 return false;
		 }
		 $clsPersonUnit->commit();
		 ////TLogAction::Singleton()->LogInsert(new TLogAdmin(AbsLog::LogCrmPersonUnit),$arrLog);
		 $this->mError = '房源恢复成功';
		 return true;
	}

	/**
	 * 获取指定个人当前有效的房源数量
	 *
	 * @param int $condition
	 * @return bool
	 */
	public function getPersonUnitCountByCondition($condition) {
		$clsPersonUnit = new House();
		$intUnitCount = $clsPersonUnit->getCount(array('hoId='.$condition['hoId'].' and status='.House::STATUS_ONLINE));
		if ( $intUnitCount >= 2 ) {
			return false;
		}
		return true;
	}

	/**
	 * 物理删除itc图片服务器图片
	 *
	 * @param int $int_unit_id 房源ID
	 * @param array $arrParkImages 该房源所对应小区的外观图
	 */
	public function delRealtorUnitImage($int_unit_id, $arrParkImages=array()) {
		$class_unit_image = new HousePicture();
		$string_unit_type = strtolower($string_unit_type);
		$array_unit_image = $class_unit_image->getAll(array('houseId='.$int_unit_id));
		if ( ! empty($array_unit_image) ) {
			foreach ( $array_unit_image as $k => $v ) {
                if( in_array($v['imgId'], $arrParkImages) ) continue;  //防止删除小区外观图
				$array_image[] =  $v['imgId'].".".$v['imgExt'];
			}
		}
		if ( ! empty($array_image) ) {
			$this->deleteImage($array_image);
		}
	}
	
	/**
	 * 物理恢复itc图片服务器图片
	 *
	 * @param int $int_unit_id 房源ID
	 * @param array $arrParkImages 该房源所对应小区的外观图
	 */
	public function recoverRealtorUnitImage($int_unit_id, $arrParkImages=array()) {
		$class_unit_image = new HousePicture();
		$string_unit_type = strtolower($string_unit_type);
		$array_unit_image = $class_unit_image->getAll(array('houseId='.$int_unit_id));
		if ( ! empty($array_unit_image) ) {
			foreach ( $array_unit_image as $k => $v ) {
				if( in_array($v['imgId'], $arrParkImages) ) continue;  //防止删除小区外观图
				$array_image[] =  $v['imgId'].".".$v['imgExt'];
			}
		}
		if ( ! empty($array_image) ) {
			$this->recoverImage($array_image);
		}
	}

	/**
	 * 物理删除itc图片服务器图片
	 *
	 * @param int $int_unit_id 房源ID
	 */
	public function delPersonUnitImage($int_unit_id) {
		$class_unit_image = new HousePicture();
		$array_unit_image = $class_unit_image->getAll(array('houseId='.$int_unit_id));
		if ( ! empty($array_unit_image) ) {
			foreach ( $array_unit_image as $k => $v ) {
				$array_image[] =  $v['imgId'].".".$v['imgExt'];
			}
		}
		if ( ! empty($array_image) ) {
			$this->deleteImage($array_image);
		}
	}
	
	/**
	 * 物理恢复itc图片服务器图片
	 *
	 * @param int $int_unit_id 房源ID
	 */
	public function recoverPersonUnitImage($int_unit_id) {
		$class_unit_image = new HousePicture();
		$array_unit_image = $class_unit_image->getAll(array('houseId='.$int_unit_id));
		if ( ! empty($array_unit_image) ) {
			foreach ( $array_unit_image as $k => $v ) {
				$array_image[] =  $v['imgId'].".".$v['imgExt'];
			}
		}
		if ( ! empty($array_image) ) {
			$this->recoverImage($array_image);
		}
	}

	/**
	 * 删除房源图片、经纪人头像、名片图片
	 * @param array $arrImage 房源图片
	 * @param string $LogInfo 日志记录信息
	 */
	function delPhoto($strImage, $LogInfo){
	    if( empty($strImage) ){
	    	$this->mError = '请传入图片！';
	    	return false;
	    }

        $preg = '/[^0-9a-z](\d{1,11}.[a-z]{3,4})/';
        $result = preg_match_all($preg, $strImage, $matches);
        if( !$result ){
	    	$this->mError = '图片格式错误！';
            return false;
        }
		$array_image = $matches[1];
		if(!empty($array_image)){
	        $this->deleteImage($array_image);
		}
		$arrLog=array(
	        'crm_user_accname'    =>  $this->mCrmUserAccName,
	        'crm_user_permission' =>  $this->mCrmUserPermission,
	        'city_id'             =>  $this->mCityId,
	        'unit_id'             =>  '0',
	        'action'              =>  3,//操作类型
			'message'			  =>  $LogInfo,
	        'time'                =>  mktime(),
	        'ip'                  =>  Utility::GetUserIP()
        );
        ////TLogAction::Singleton()->LogInsert(new TLogAdmin(AbsLog::LogCrmAdmin),$arrLog);
        return true;
    }


	/**
	 * 恢复房源图片、经纪人头像、名片图片
	 * @param array $arrImage 房源图片
	 * @param string $LogInfo 日志记录信息
	 */
	function recoverPhoto($strImage, $LogInfo){
	    if( empty($strImage) ){
	    	$this->mError = '请传入图片！';
	    	return false;
	    }
        $preg = '/[^0-9a-z](\d{1,11}.[a-z]{3,4})/';
        $result = preg_match_all($preg, $strImage, $matches);
        if( !$result ){
	    	$this->mError = '图片格式错误！';
            return false;
        }

        $array_image = $matches[1];
		if(!empty($array_image)){
	        $this->recoverImage($array_image);//分别删除
		}

		$arrLog=array(
	        'crm_user_accname'    =>  $this->mCrmUserAccName,
	        'crm_user_permission' =>  $this->mCrmUserPermission,
	        'city_id'             =>  $this->mCityId,
	        'unit_id'             =>  '0',
	        'action'              =>  3,//操作类型
			'message'			  =>  $LogInfo,
	        'time'                =>  mktime(),
	        'ip'                  =>  Utility::GetUserIP()
        );
        ////TLogAction::Singleton()->LogInsert(new TLogAdmin(AbsLog::LogCrmAdmin),$arrLog);
        return true;
    }
	
	/**
	 * 用于删除搜狐云上图片
	 * @param array $arrImage
	 */
	public function deleteImage($arrImage){
		foreach($arrImage as $kt=> $vt){
			$image_info = explode('.', $vt);
			$image_id = isset($image_info[0]) ? intval($image_info[0]) : 0;
			if ($image_id) $this->moveImageData($image_id, true);
		}
	}

	/**
	 * 搜狐云图片恢复
	 * @param array $arrImage
	 */
	public function recoverImage($arrImage){
		foreach($arrImage as $kt=> $vt){
			$image_info = explode('.', $vt);
			$image_id = isset($image_info[0]) ? intval($image_info[0]) : 0;
			if ($image_id) $this->moveImageData($image_id, false);
		}
	}
	
	/**
	 * 用于移动图片数据
	 * @param intval $image_id
	 * @param bool $is_del
	 */
	public function moveImageData($image_id, $is_del = true){
		$clsUnitImage = new HousePicture();
		$clsUnit = new House();

		if ($is_del) {
			$arrUnitImages = $clsUnitImage->getAll(array('imgId='.$image_id.' AND status='.HousePicture::STATUS_OK));
		} else {
			$arrUnitImages = $clsUnitImage->getAll(array('imgId='.$image_id.' AND status='.HousePicture::STATUS_DEL));
		}
		if (!empty($arrUnitImages)) {
			foreach ($arrUnitImages as $arrUnitImage) {
				$clsUnitImage->begin();
				if ($is_del) {
					$clsUnitImage->updateAll(array('id='.$arrUnitImage['id']), array('status' => HousePicture::STATUS_DEL));
				} else {
					$clsUnitImage->updateAll(array('id='.$arrUnitImage['id']), array('status' => HousePicture::STATUS_OK));
				}
				$intUnitImageAffect = $clsUnitImage->affectedRows();
				if ( $intUnitImageAffect === false ) {
					$this->mError = '操作图片数据失败';
					$clsUnitImage->rollback();
					return false;
				}
				if ($is_del) {
					$intFlag = Scs::Instance()->deleteImage($arrUnitImage['imgId'], $arrUnitImage['imgExt']);
				} else {
					$intFlag = Scs::Instance()->recoverImage($arrUnitImage['imgId'], $arrUnitImage['imgExt']);
				}
				if ( $intFlag === false ) {
					$this->mError = '操作图片文件失败';
					$clsUnitImage->rollback();
					return false;
				}
				$clsUnitImage->commit();
				$arrUpdate = array();
				$arrUnit = $clsUnit->getOne('id='.$arrUnitImage['houseId']);
				$strCond = 'houseId='.$arrUnitImage['houseId'].' AND type='.HousePicture::IMG_SHINEI;
				if ($is_del) $strCond .= 'imgId !='.$image_id;//删除的话排除原图
				$arrNewImage = $clsUnitImage->getAll($strCond);
				if (empty($arrNewImage)) {
					$arrUpdate = array('status' => House::STATUS_OFFLINE, 'tags' => House::HOUSE_NOTAG, 'fine' => House::FINE_NO, 'xiajia' => date('Y-m-d H:i:s', time()), 'houseUpdate' => date('Y-m-d H:i:s', time()), 'picId' => 0, 'picExt' => 0);//下架此房源
				} elseif ($arrUnit['picId'] == $image_id || empty($arrUnit['picId'])) {//删除或恢复了封面图
					$arrUpdate = array('picId' => $arrNewImage[0]['imgId'], 'picExt' => $arrNewImage[0]['imgExt']);//重设默认图
				}
				$clsUnit->begin();
				/*if (empty($arrNewImage)) {
					//房源下架取消定时
					$clsRefreshQueue = new TRefreshQueueMod();
					$intRefreshDel = $clsRefreshQueue->delFromRefreshListByIds($arrUnitImage['unit_id'], $arrUnitImage['unit_type']);
					if ($intRefreshDel == false) {
						$this->mError = '取消定时失败';
						$clsUnit->rollback();
						return false;
					}
				}*/
				$intUnitAffect = $clsUnit->modifyHouseById($arrUnitImage['houseId'], $arrUpdates);
				if ( $intUnitAffect === false ) {
					$this->mError = '更新房源基本信息失败';
					$clsUnit->rollback();
					return false;
				}
				//更新ES索引
				global $sysES;
				$clsEs = new Es($sysES['default']);
				$intFlag = $clsEs->update(array('id' => $arrUnitImage['houseId'], 'data' => $clsEs->houseFormat($arrUpdate)));
				if ( $intFlag == false ) {
					$this->mError = '更新房源索引失败';
					$clsUnit->rollback();
					return false;
				}
				$clsUnit->commit();
			}
			return true;
		}
		return false;
	}
	
}