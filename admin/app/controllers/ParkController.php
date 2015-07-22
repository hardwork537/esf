<?php

class ParkController extends ControllerBase
{
    public function listAction()
    {
        $cityId         = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys']  = City::getOptions();

        //区域信息
        $data['districts'] = CityDistrict::instance()->getDistrict($cityId);
        $data['distId']    = intval($this->request->get("distId", int, 0));
        //板块信息
        $data['regId'] = intval($this->request->get("regId", int, 0));
        $data['distId'] > 0 && $data['regions'] = CityRegion::instance()->getRegionForOptionByDistId($data['distId']);
        //类型
        $cityInfo = City::findFirst($data['cityId'], 0)->toArray();
        require_once DOCROOT."../config/{$cityInfo['pinyinAbbr']}.config.inc.php";
        $data['types'] = $GLOBALS['LIVE_TYPE'];
        $data['type']  = $this->request->get('type', 'int', 0);

        $where = "cityId=$cityId and status=".Park::STATUS_VALID;
        $data['distId'] > 0 && $where .= " and distId={$data['distId']}";
        $data['regId'] > 0 && $where .= " and regId={$data['regId']}";
        $data['name'] = $name = $this->request->get("name", string, '');
        $name && $where .= " and name like '%{$name}%'";
        $data['type'] > 0 && $where .= " and type={$data['type']}";

        $condition     = array(
            "conditions" => $where,
            "order"      => "id desc",
            "columns"    => "id,name",
            "limit" => $this->_pagesize,
            "offset" => $this->_offset
        );
        $pageCount     = Park::count($where);
        $data['page']  = Page::create($pageCount, $this->_pagesize);
        $data['lists'] = Park::find($condition, 0)->toArray();
        foreach($data['lists'] as $k => $v)
        {
            $data['lists'][$k]['parkUrl'] = Url::getParkUrl($v['id'], $data['cityId']);
        }

        $this->show(null, $data);
    }

    public function addAction()
    {
        $cityId         = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        $data['cityId'] = $cityId;
        $data['citys']  = City::getOptions();

        if($this->request->isPost())
        {
            //保存小区
            $check_ret = $this->_filter_params();

            if(0 != $check_ret['status'])
            {
                $this->show("JSON", $check_ret);
            }
            $addRes = Park::instance()->add($check_ret['params']);
            if(0 === $addRes['status'])
            {
                //成功，添加log日志
                $avgPrice = $check_ret['params']['avgPrice'] ? $check_ret['params']['avgPrice'] : 0;
                //$logRes   = $this->addAdminLog('park', "新增小区 {$check_ret['params']['name']}({$addRes['id']})，新增小区均价 {$avgPrice}");
            }
            $this->show("JSON", $addRes);
        }

        //区域信息
        $data['districts'] = CityDistrict::instance()->getDistrict($cityId);
        list($data['distId'], $tmp) = each($data['districts']);
        //板块信息
        $data['regions'] = CityRegion::instance()->getRegionForOptionByDistId($data['distId']);
        list($data['regId'], $tmp) = each($data['regions']);

        $cityInfo = City::findFirst($cityId)->toArray();

        require_once DOCROOT."../config/{$cityInfo['pinyinAbbr']}.config.inc.php";
        $data['types'] = $GLOBALS['LIVE_TYPE'];
        list($data['typeId'], $tmp) = each($data['types']);
        $data['sourceTypes']   = Park::getSourceTypes();
        $data['defaultSource'] = Park::SOURCE_SECONDARY_PARK;

        $this->show(null, $data);
    }

    public function editAction($id = 0)
    {
        $cityId        = $_REQUEST['cityId'] ? intval($_REQUEST['cityId']) : $this->_userInfo['cityId'];
        //$cityId        = $this->checkAdminCity(intval($cityId));
        $data['citys'] = City::getOptions();

        if($this->request->isPost())
        {
            //提交修改
            $id        = intval($this->request->getPost("parkId", "int", 0));
            $check_ret = $this->_filter_params();
            if(0 != $check_ret['status'])
            {
                $this->show("JSON", $check_ret);
            }
            $rs = Park::instance()->edit($id, $check_ret['params']);
            if(0 === $rs['status'])
            {
                //成功，添加log日志
                $avgPrice = $check_ret['params']['avgPrice'] ? $check_ret['params']['avgPrice'] : 0;
                //$logRes   = $this->addAdminLog('park', "修改小区 {$check_ret['params']['name']}({$id})，修改小区均价 {$avgPrice}");
            }          
            $this->show("JSON", $rs);
        }
        //进入修改页面
        $id                = intval($id);
        $where[]           = "id=$id";
        $data['park_info'] = Park::findFirst($where);

        if($data['park_info'])
        {
            $data['park_info'] = $data['park_info']->toArray();
        }
        else
        {
            return $this->response->redirect('park/list');
        }
        $data['parkId'] = $id;
        $data['cityId'] = intval($data['park_info']['cityId']);
        $data['source'] = $data['park_info']['source'];
        $cityInfo       = City::findFirst($data['cityId'])->toArray();

        require_once DOCROOT."../config/{$cityInfo['pinyinAbbr']}.config.inc.php";
        $data['types']  = $GLOBALS['LIVE_TYPE'];
        $data['typeId'] = $data['park_info']['type'];

        //区域信息
        $data['districts'] = CityDistrict::instance()->getDistrict($data['cityId']);
        $data['distId']    = intval($data['park_info']['distId']);
        //板块信息
        $data['regions'] = CityRegion::instance()->getRegionForOptionByDistId($data['distId']);
        $data['regId']   = intval($data['park_info']['regId']);

        //小区扩展信息
        $parkExt = ParkExt::instance()->find(array( "conditions" => "parkId={$id} and status=".ParkExt::STATUS_VALID ));
        if($parkExt)
        {
            foreach($parkExt->toArray() as $value)
            {
                $data['parkExt'][$value['name']] = $value['value'];
            }
        }
        //小区描述信息
        $parkMore = ParkMore::instance()->find(array( "conditions" => "parkId={$id} and status=".ParkMore::STATUS_VALID ));
        if($parkMore)
        {
            foreach($parkMore->toArray() as $value)
            {
                $data['parkMore'][$value['name']] = $value['text'];
            }
        }
        //小区论坛信息
        //$data['parkBbs'] = ParkBbs::findFirst("parkId={$id} and status=".ParkBbs::STATUS_VALID, 0)->toArray();

        $this->show("add", $data);
    }

    public function delAction($id = 0)
    {
        $id      = intval($id);
        $del_ret = Park::instance()->del($id);
//        if(0 === $del_ret['status'])
//        {
//            //成功，添加log日志
//            $logRes = $this->addAdminLog('park', "删除小区 {$del_ret['name']}({$id})");
//        }

        $this->show("JSON", $del_ret);
    }

    /**
     * 添加、修改时 参数验证
     */
    private function _filter_params()
    {
        $params = array();
        //验证小区名称
        $name = trim($this->request->getPost('name', 'string', ''));
        if(!$name)
        {
            return array( 'status' => 1, 'info' => '小区名称不能为空!' );
        }
        $params['name'] = $name;
        //验证城市
        $citys  = City::getOptions();
        $cityId = intval($this->request->getPost("cityId", 'int', 0));
        if(!array_key_exists($cityId, $citys))
        {
            return array( 'status' => 1, 'info' => '城市无效!' );
        }
        $params['cityId'] = $cityId;
        //验证区域
        $distId            = intval($this->request->getPost("distId", 'int', 0));
        $con['conditions'] = "cityId={$cityId} and id={$distId} and status=".CityDistrict::STATUS_ENABLED;
        $distNum           = CityDistrict::count($con);
        if($distNum < 1)
        {
            return array( 'status' => 1, 'info' => '区域无效!' );
        }
        $params['distId'] = $distId;
        //验证板块
        $regId             = intval($this->request->getPost("regId", 'int', 0));
        $con['conditions'] = "cityId={$cityId} and distId={$distId} and id={$regId} and status=".CityRegion::STATUS_ON;
        $regNum            = CityRegion::count($con);
        if($regNum < 1)
        {
            return array( 'status' => 1, 'info' => '板块无效!' );
        }
        $params['regId'] = $regId;
        //验证均价
        $avgPrice = trim($this->request->getPost('avgPrice', 'string', ''));
        if(!empty($avgPrice) && !preg_match('/^\d{4,6}$/', $avgPrice))
        {
            return array( 'status' => 1, 'info' => '本月均价必须为4~6位数字!' );
        }
        $params['avgPrice'] = intval($avgPrice);
        //验证小区地址
        $address = trim($this->request->getPost('address', 'string', ''));
        if(!$address)
        {
            return array( 'status' => 1, 'info' => '小区地址不能为空!' );
        }
        $params['address'] = $address;
        //验证物业类型
        $type = intval($this->request->getPost("type", 'int', 0));
        if($type < 1)
        {
            return array( 'status' => 1, 'info' => '物业类型不能为空!' );
        }
        $params['type'] = $type;
        //验证X坐标
        $x = trim($this->request->getPost('x', 'string', ''));
        if(!preg_match("/\d{1,3}\.\d{1,15}/", $x))
        {
            return array( 'status' => 1, 'info' => '请正确输入X坐标!' );
        }
        $params['X'] = $x;
        //验证Y坐标
        $y = trim($this->request->getPost('y', 'string', ''));
        if(!preg_match("/\d{1,3}\.\d{1,15}/", $y))
        {
            return array( 'status' => 1, 'info' => '请正确输入Y坐标!' );
        }
        $params['Y'] = $y;
        //焦点小区ID
        $params['projId'] = $this->request->getPost('projId', 'int', 0);
        //焦点论坛ID
        $params['groupId'] = $this->request->getPost('groupId', 'int', 0);
        //小区别名
        $params['alias'] = trim($this->request->getPost('alias', 'string', ''));
        //建筑年代
        $params['buildYear'] = intval($this->request->getPost('buildYear', 'int', 0));
        if(!preg_match("/^[1-9][0-9]{3}$/", $params['buildYear']))
        {
            return array( 'status' => 1, 'info' => '建筑年代无效!' );
        }
        //绿化率
        $params['greenRate'] = trim($this->request->getPost('greenRate', 'string', ''));
        //容积率
        $params['floorAreaRate'] = trim($this->request->getPost('floorAreaRate', 'string', ''));
        //总建筑面积
        $params['grossFloorArea'] = trim($this->request->getPost('grossFloorArea', 'string', ''));
        //占地面积
        $params['landArea'] = trim($this->request->getPost('landArea', 'string', ''));
        //车位数
        $params['pCount'] = intval($this->request->getPost('pCount', 'int', 0));
        //总户数
        $params['houses'] = intval($this->request->getPost('houses', 'int', 0));
        //物业公司
        $params['物业公司'] = trim($this->request->getPost('propertyCompany', 'string', ''));
        //物业电话
        $params['物业电话'] = trim($this->request->getPost('propertyTel', 'string', ''));
        //物业费
        $params['fee'] = floatval(trim($this->request->getPost('fee', 'string', '')));
        //开发商
        $params['开发商'] = trim($this->request->getPost('developer', 'string', ''));
        //周边公交
        $params['周边公交'] = trim($this->request->getPost('busLine', 'string', ''));
        //周边设施
        $params['周边设施'] = trim($this->request->getPost('aroundFacility', 'string', ''));
        //内部设施
        $params['内部设施'] = trim($this->request->getPost('insideFacility', 'string', ''));
        //小区设施
        $params['小区设施'] = trim($this->request->getPost('parkFacility', 'string', ''));
        //小区来源
        $params['source'] = $this->request->getPost('source', 'int', 0);
        if(!array_key_exists($params['source'], Park::getSourceTypes()))
        {
            return array( 'status' => 1, 'info' => '楼盘来源无效!' );
        }
        //开盘时间
        $params['开盘时间'] = trim($this->request->getPost('openDate', 'string', ''));
        //入住时间
        $params['入住时间'] = trim($this->request->getPost('checkinDate', 'string', ''));
        //预售许可证
        $params['预售许可证'] = trim($this->request->getPost('presalePermit', 'string', ''));
        //产权年限
        $params['产权年限'] = trim($this->request->getPost('propertyAge', 'string', ''));
        //400电话
        $params['400电话'] = trim($this->request->getPost('tel400', 'string', ''));
        if(Park::SOURCE_NEW_PARK == $params['source'])
        {
            if(!$params['开盘时间'])
            {
                return array( 'status' => 1, 'info' => '开盘时间不能为空!' );
            }
            if(!$params['入住时间'])
            {
                return array( 'status' => 1, 'info' => '入住时间不能为空!' );
            }
            if(!$params['预售许可证'])
            {
                return array( 'status' => 1, 'info' => '预售许可证不能为空!' );
            }
            if(!$params['产权年限'])
            {
                return array( 'status' => 1, 'info' => '产权年限不能为空!' );
            }
            if(!$params['400电话'])
            {
                return array( 'status' => 1, 'info' => '400电话不能为空!' );
            }
        }

        return array( 'status' => 0, 'params' => $params );
    }

    public function pictureAction($parkId = 0)
    {
        $parkId = intval($parkId);
        $type   = $this->request->get('type', 'string', '');

        $condition = array(
            'conditions' => "id={$parkId} and status=".Park::STATUS_VALID,
            'columns'    => 'id,name,cityId,picId,picExt'
        );
        $parkInfo  = Park::findFirst($condition, 0)->toArray();
        if(empty($parkInfo))
        {
            $this->response->redirect('/park/', true);
        }
        $parkPictures      = ParkPicture::instance()->getParkPictureById($parkId);
        $defaultCoverImage = $parkInfo['picId'].'.'.$parkInfo['picExt'];

        $data             = array();
        $data['parkName'] = $parkInfo['name'];
        switch($type)
        {
            case 'waiguan_biaozhun':
                $arrWaiGuan = !empty($parkPictures[ParkPicture::IMAGE_TYPE_WAIGUAN]) ? $parkPictures[ParkPicture::IMAGE_TYPE_WAIGUAN] : array(); //外观图
                if($this->request->isPost())
                {
                    $waiguan                  = $this->request->getPost('waiguan');
                    $coverImage               = $this->request->getPost('cover_image', 'string', '');
                    $closeWgPhoto             = $this->request->getPost('closeWgPhoto', 'int', 0);
                    $parkInfo['allowWgPhoto'] = $closeWgPhoto ? Park::ALLOW_WAIGUAN_NO : Park::ALLOW_WAIGUAN_YES;

                    $saveRes = ParkPicture::instance()->saveParkPictures($parkInfo, $waiguan, $arrWaiGuan, $coverImage, $defaultCoverImage, ParkPicture::IMAGE_TYPE_WAIGUAN);
                    $msg     = "保存".($saveRes ? '成功' : '失败');
                    echo "<script>alert('{$msg}');location.href='/park/picture/{$parkId}/?type=waiguan_biaozhun';</script>";
                    exit();
                    //$this->response->redirect("/park/picture/{$parkId}/?type=waiguan_biaozhun", true);
                }
                else
                {
                    $arrTagImage = $this->_getTag($arrWaiGuan);

                    $data['arrWaiGuanTag']      = ParkPicture::getWaiGuanTag();
                    $data['arrWaiGuan']         = $arrWaiGuan;
                    $data['arrTagImage']        = $arrTagImage['type'];
                    $data['intTotal']           = $arrTagImage['intTotal'];
                    $data['defaultCoverImage']  = $defaultCoverImage;
                    $data['arrWaiGuanTagForJs'] = json_encode($data['arrWaiGuanTag']);
                    $data['userId']             = $this->_id;
                    $data['parkInfo']           = $parkInfo;

                    $this->show('picture/waiguan1', $data);
                }
                break;

            case 'huxing_biaozhun':
                $arrHuXing       = !empty($parkPictures[ParkPicture::IMAGE_TYPE_HUXING]) ? $parkPictures[ParkPicture::IMAGE_TYPE_HUXING] : array(); //户型图
                $arrBedroom      = ParkPicture::getParkPictureBedroom();
                $arrBedroomImage = $this->_getBedroom($arrHuXing, $arrBedroom);

                if($this->request->isPost())
                {
                    $huxing                   = $this->request->getPost('huxing');
                    $coverImage               = '';
                    $closeHxPhoto             = $this->request->getPost('closeHxPhoto', 'int', 0);
                    $parkInfo['allowHxPhoto'] = $closeWgPhoto ? Park::ALLOW_WAIGUAN_NO : Park::ALLOW_WAIGUAN_YES;

                    $saveRes = ParkPicture::instance()->saveParkPictures($parkInfo, $huxing, $arrHuXing, $coverImage, $defaultCoverImage, ParkPicture::IMAGE_TYPE_HUXING);
                    $msg     = "保存".($saveRes ? '成功' : '失败');
                    echo "<script>alert('{$msg}');location.href='/park/picture/{$parkId}/?type=huxing_biaozhun';</script>";
                    exit();
                    //$this->response->redirect('/park/', true);
                }
                else
                {
                    //用到城市信息的是头部的小区相册链接
                    if(empty($_REQUEST['bedroom']))
                    {
                        $arrHuXingImage = $arrBedroomImage['all'];
                    }
                    else
                    {
                        $tmp            = intval($_REQUEST['bedroom']);
                        $arrHuXingImage = $arrBedroomImage['type'][$tmp]['data'];
                    }
                    $arrLiving = $GLOBALS['UNIT_LIVING_ROOM'];
                    unset($arrLiving[0]);

                    $arrBathroom = $GLOBALS['UNIT_BATHROOM'];
                    unset($arrBathroom[0]);

                    $data                     = array();
                    $data['parkName']         = $parkInfo['name'];
                    $data['arrCity']          = City::findFirst($parkInfo['cityId']);
                    $data['arrHuXingImage']   = $arrHuXingImage;
                    $data['arrHuXingType']    = $arrBedroomImage['type'];
                    $data['intTotal']         = $arrBedroomImage['intTotal'];
                    $data['arrBedroom']       = array( '居室' ) + $arrBedroom;
                    $data['arrLiving']        = array( '无厅' ) + $arrLiving;
                    $data['arrBathroom']      = array( '无卫' ) + $arrBathroom;
                    $data['arrExposure']      = array( '朝向' ) + $GLOBALS['UNIT_EXPOSURE'];
                    $data['userId']           = $this->_id;
                    $data['arrBedroomForJs']  = json_encode($data['arrBedroom']);
                    $data['arrLivingForJs']   = json_encode($data['arrLiving']);
                    $data['arrBathroomForJs'] = json_encode($data['arrBathroom']);
                    $data['arrExposureForJs'] = json_encode($data['arrExposure']);
                    $data['parkInfo']         = $parkInfo;

                    $this->show('picture/huxing1', $data);
                }
                break;

            case 'view_photo':
                $arrMenu = $this->_getMenu($parkId);

                $imageType = HousePicture::IMG_HUXING;
                $where     = "parkId={$parkId} and cityId={$parkInfo['cityId']} and (type=".House::TYPE_CIXIN." or type=".House::TYPE_ERSHOU.")";
                if(isset($_REQUEST['bedroom'])) //有此条件，则户型图下的对应居室选中
                {
                    if($_REQUEST['bedroom'] == 'over5')
                    {
                        $where .= " and bedRoom>=6 and bedRoom<99";
                    }
                    else
                    {
                        $where .= " and bedRoom=".intval($_REQUEST['bedroom']);
                    }
                    $arrMenu[$_REQUEST['bedroom']]['check'] = true;
                }
                else
                {
                    //如果没有设置bedroom变量，则外观图选中
                    $arrMenu[0]['check'] = true;
                    $imageType           = HousePicture::IMG_WAIGUAN;
                }
                $arrSale   = array();
                $condition = array(
                    'conditions' => $where,
                    'columns'    => 'id,bedRoom,livingRoom,bathRoom,bA,orientation',
                    'limit'      => array(
                        'offset' => 0,
                        'number' => 100
                    )
                );

                $arrSaleTmp = House::find($condition, 0)->toArray();
                foreach($arrSaleTmp as $data)
                {
                    if($imageType == ParkPicture::IMAGE_TYPE_HUXING)
                    {
                        $arrSale[$data['id']]['des'] = (isset($GLOBALS['UNIT_BEDROOM'][$data['bedRoom']]) ? $GLOBALS['UNIT_BEDROOM'][$data['bedRoom']] : '').($data['livingRoom'] > 0 ? $data['livingRoom'].'厅' : '').($data['bathRoom'] > 0 ? $data['bathRoom'].'卫' : '');
                        $arrSale[$data['id']]['des'] .= $data['bA'] > 0 ? ' '.$data['bA'].'㎡' : '';
                        $arrSale[$data['id']]['des'] .= isset($GLOBALS['UNIT_EXPOSURE'][$data['orientation']]) ? ' '.$GLOBALS['UNIT_EXPOSURE'][$data['orientation']] : '';
                    }
                    $arrSaleIds[] = $data['id'];
                }
                unset($arrSaleTmp);
                if(!empty($arrSaleIds))
                {
                    $saleId       = implode(',', $arrSaleIds);
                    $where        = "houseId in ($saleId) and type=".$imageType;
                    $condition    = array(
                        'conditions' => $where,
                        'limit'      => array(
                            'offset' => 0,
                            'number' => 50
                        ),
                    );
                    $arrUnitImage = HousePicture::find($condition, 0)->toArray();
                }
                $arrBackData = array();
                if(!empty($arrUnitImage))
                {
                    foreach($arrUnitImage as $photo)
                    {
                        if(isset($arrBackData[$photo['imgId']]))
                        {
                            continue;
                        }
                        $arrBackData[$photo['imgId']] = array(
                            'smallUrl' => ImageUtility::getImgUrl(PRODUCT_NAME, $photo['imgId'], $photo['imgExt'], 180, 120),
                            'bigUrl'   => ImageUtility::getImgUrl(PRODUCT_NAME, $photo['imgId'], $photo['imgExt']),
                        );
                        if(isset($arrSale[$photo['houseId']]['des']))
                        {
                            $arrBackData[$photo['imgId']]['des'] = $arrSale[$photo['houseId']]['des'];
                        }
                    }
                }
                $cityInfo = City::findFirst($parkInfo['cityId'], 0)->toArray();
                $data     = array(
                    'arrUnitImage' => $arrBackData,
                    'arrMenu'      => $arrMenu,
                    'parkName'     => $parkInfo['name'],
                    'xiangce_url'  => 'http://'.$cityInfo['pinyinAbbr']._DEFAULT_DOMAIN_.'/xiaoqu/'.$parkId.'/xiangce/', //前端小区相册url
                );

                $this->show('picture/viewphoto', $data);
//                FSTemplate::Assign(array(
//                    'arrUnitImage' => $arrBackData,
//                    'arrMenu'      => $arrMenu,
//                    'house_name'   => $arrHouse['house_name'],
//                    'xiangce_url'  => 'http://'.$arrCity[$arrHouse['city_id']]['city_pinyin_abbr']._DEFAULT_DOMAIN_.'/xiaoqu/'.$intHouseId.'/xiangce/',//前端小区相册url
//                 ));
//                 FSTemplate::Display('admin/housePhoto/viewphoto_brokerunit.tpl'); 
        }
    }

    private function _getTag($arrImage)
    {
        $arrTagImage = array( 'intTotal' => 0, 'all' => array(), 'type' => array() );

        if(is_array($arrImage))
        {
            foreach($arrImage as $value)
            {
                if(is_array($value))
                {
                    if(isset($value['tag']))
                    {
                        if(array_key_exists($value['tag'], $arrTagImage['type']))
                        {
                            $arrTagImage['type'][$value['tag']]['num']++;
                            $arrTagImage['type'][$value['tag']][$arrTagImage['type'][$value['tag']]['num']] = $value;
                        }
                        else
                        {
                            $arrTagImage['type'][$value['tag']]['num'] = 1;
                            $arrTagImage['type'][$value['tag']][1]     = $value;
                        }
                        $arrTagImage['intTotal']++;
                    }
                    $this->_getTag($value);
                }
            }
        }

        return $arrTagImage;
    }

    private function _getBedroom($arrHuXing, $arrBedroom)
    {
        $arrBedroomImage = array( 'intTotal' => 0, 'type' => array(), 'all' => array() );
        if(is_array($arrHuXing))
        {
            foreach($arrHuXing as $value)
            {
                if(is_array($value))
                {
                    //递归机制
                    //if(isset($value['bed_room'])  ){ //寻找需要处理的数据
                    $value['name']    = @$arrBedroom[$value['bedRoom']] ? $arrBedroom[$value['bedRoom']] : '';
                    $value['areaMin'] = floatval($value['areaMin']);
                    $value['areaMax'] = floatval($value['areaMax']);
                    $value['tag']     = stripcslashes($value['tag']);

                    if(array_key_exists($value['bedRoom'], $arrBedroomImage['type']))
                    {
                        //对数据分类处理
                        $tmp = array(
                            'num'  => ++$arrBedroomImage['type'][$value['bedRoom']]['num'], //类型统计个数
                            'name' => $value['name'], //添加名字
                            'data' => $arrBedroomImage['type'][$value['bedRoom']]['data'], //分类的数据
                        );
                        array_push($tmp['data'], $value);
                    }
                    else
                    {
                        $tmp = array(
                            'num'  => 1, //类型统计个数
                            'name' => $value['name'], //添加名字
                            'data' => array( $value ), //分类的数据
                        );
                    }
                    $arrBedroomImage['intTotal']++;
                    $arrBedroomImage['type'][$value['bedRoom']] = $tmp; //处理分类数据
                    array_push($arrBedroomImage['all'], $value); //处理全部数据
                    //}
                    $this->_getBedroom($value, $arrBedroom);
                }
            }
        }

        return $arrBedroomImage;
    }

    private function _getMenu($parkId)
    {
        return array(
            '0'     => array(
                'name' => '外景图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo",
            ),
            '1'     => array(
                'name' => '1室户型图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=1",
            ),
            '2'     => array(
                'name' => '2室户型图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=2",
            ),
            '3'     => array(
                'name' => '3室户型图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=3",
            ),
            '4'     => array(
                'name' => '4室户型图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=4",
            ),
            '5'     => array(
                'name' => '5室户型图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=5",
            ),
            'over5' => array(
                'name' => '5室以上户型图',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=over5",
            ),
            '99'    => array(
                'name' => '复室',
                'url'  => "/park/picture/{$parkId}/?type=view_photo&bedroom=99",
            ),
            '100'   => array(
                'name'    => '开间',
                'url'     => "/park/picture/{$parkId}/?type=view_photo&bedroom=100",
                'li_last' => true,
            )
        );
    }

}
