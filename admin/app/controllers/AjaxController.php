<?php

use SohuCS\Common\Enum\Region;

/**
 * @abstract  提供ajax调用
 * @copyright Sohu-inc Team.
 * @author    Rady (yifengcao@sohu-inc.com)
 * @date      2014-08-30 16:11:26
 * @version   admin 1.0
 */
define("NO_NEED_LOGIN", true);
define("NO_NEED_POWER", true);
header("Content-type: text/html; charset=utf-8");

/**
 * 根据$cityId获取对应的区域
 * @author Rady
 */
class AjaxController extends ControllerBase
{

    public function getDistByCityIdAction($cityId = 1)
    {
        $list   = [ ];
        $cityId = intval($cityId);
        $rs     = CityDistrict::find("cityId='$cityId' and status=".CityDistrict::STATUS_ENABLED)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据$distId获取对应的板块
     * @param number $distId
     */
    public function getRegByDistIdAction($distId = 1)
    {
        $list   = [ ];
        $distId = intval($distId);
        $rs     = CityRegion::find("distId='$distId' and status=".CityRegion::STATUS_ON)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据 comId 获取对应的区域
     * @param number comId
     */
    public function getAreaByComIdAction($comId = 1)
    {
        $list      = [ ];
        $comId     = intval($comId);
        $condition = array(
            'conditions' => "comId={$comId} and status=".Area::STATUS_OK,
            'columns'    => 'id,name'
        );
        $rs        = Area::find($condition)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据 areaId 获取对应的门店
     * @param number $areaId
     */
    public function getShopByAreaIdAction($areaId = 1)
    {
        $list      = [ ];
        $areaId    = intval($areaId);
        $condition = array(
            'conditions' => "areaId={$areaId} and status=".Shop::STATUS_VALID,
            'columns'    => 'id,name'
        );
        $rs        = Shop::find($condition)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据 shopId 获取对应的经纪人
     * @param number $shopId
     */
    public function getRealByShopIdAction($shopId = 1)
    {
        $list      = [ ];
        $shopId    = intval($shopId);
        $condition = array(
            'conditions' => "shopId={$shopId} and status<>".Realtor::STATUS_DELETE,
            'columns'    => 'id,name'
        );
        $rs        = Realtor::find($condition)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据$cityId获取对应的销售
     * @param number $distId
     */
    public function getXiaoshouByCityIdAction($cityId = 1)
    {
        $list   = [ ];
        $cityId = intval($cityId);
        $con    = "adminRoleId=4 and cityId='$cityId' and status=".AdminUser::STATUS_VALID;
        $rs     = AdminUser::find($con)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 根据$cityId获取对应的客服
     * @param number $distId
     */
    public function getKefuByCityIdAction($cityId = 1)
    {
        $list   = [ ];
        $cityId = intval($cityId);
        $con    = "adminRoleId=3  and cityId='$cityId' and status=".AdminUser::STATUS_VALID;
        $rs     = AdminUser::find($con)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /*
     * @desc 根据$cityId获取对应的轨道线路
     * @param int $cityID
     * */

    public function getMetroByCityIdAction($cityId = 1)
    {
        $list   = [ ];
        $cityId = intval($cityId);
        $rs     = Metro::find("cityId=$cityId and status=".Metro::STATUS_ENABLED)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /*
     * @desc 根据$cityId获取对应的经纪公司
     * @param int $cityID
     * */

    public function getCompanyByCityIDAction($cityId = 1)
    {
        $list   = [ ];
        $cityId = intval($cityId);
        $rs     = Company::find("cityId=$cityId and status=".Company::STATUS_ENABLED)->toArray();
        foreach($rs as $k => $v)
        {
            $list[$v['id']] = $v['name'];
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /*
     * @desc 根据 $shopId 获取门店信息
     * @param int $shopId
     * */

    public function getShopInfoAction($shopId = 0)
    {
        $rs = Shop::findFirst("id='$shopId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $realtorId 获取经纪人信息
     * @param int $realtorId
     * */

    public function getRealtorInfoAction($realtorId = 0)
    {
        $rs = Realtor::findFirst("id='$realtorId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $districtId 获取区域信息
     * @param int $districtId
     * */

    public function getDistrictInfoAction($districtId = 0)
    {
        $rs = CityDistrict::findFirst("id='$districtId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $regionId 获取板块信息
     * @param int $regionId
     * */

    public function getRegionInfoAction($regionId = 0)
    {
        $rs = CityRegion::findFirst("id='$regionId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $metroId 获取轨道线路
     * @param int $metroId
     * */

    public function getMetroInfoAction($metroId = 0)
    {
        $rs = Metro::findFirst("id='$metroId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $metroStationId 轨道站点
     * @param int $metroStationId
     * */

    public function getmetroStationInfoAction($metroStationId = 0)
    {
        $rs = MetroStation::findFirst("id='$metroStationId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $parkId 获取小区信息
     * @param int $parkId
     * */

    public function getParkInfoAction($parkId = 0)
    {
        $rs = Park::findFirst("id='$parkId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /*
     * @desc 根据 $schoolId 获取学校信息
     * @param int $schoolId
     * */

    public function getSchoolInfoAction($schoolId = 0)
    {
        $rs = School::findFirst("id='$schoolId'");
        if($rs)
        {
            $this->_json['data'] = $rs->toArray();
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /**
     * 根据城市、端口类型 获取端口信息
     * @param int $cityId
     * @param string $pcType
     */
    public function getPortByCityIdAction($cityId = 0, $pcType = '')
    {
        $cityId   = intval($cityId);
        $pcType   = trim($pcType);
        $portType = $this->request->get('type', 'int', 0);

        $ports = PortCity::instance()->getPortForOptionByCityId($cityId, $pcType, $portType);
        if(!empty($ports))
        {
            foreach($ports as $pcId => $name)
            {
                if('QS1' == $name)
                {
                    $this->_json['data'] = array( $pcId => $name );
                }
            }

            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    /**
     * 公司联想
     * @param int $cityId
     * @param string $portType
     */
    public function getCompanyInfoAction($isAllName = 0)
    {
        $inputInfo = $this->request->getPost('keyword', string);
        $cityId    = $this->request->getPost('cityId', int);
        $list      = [ ];
        $inputInfo = trim($inputInfo);
        $con       = "(abbr like '{$inputInfo}%' or pinyin like '{$inputInfo}%' or pinyinAbbr like '{$inputInfo}%' or name like '{$inputInfo}%')";
        if($cityId > 0)
        {
            $con .= " and cityId={$cityId}";
        }

        $rs = Company::find(array(
            $con,
            "limit" => 10
        ))->toArray();
        foreach($rs as $k => $v)
        {
            if(!$isAllName)
            {
                $list[] = array( "id" => $v['id'], "name" => $v['abbr'] );
            }
            else
            {
                $list[] = array( "id" => $v['id'], "name" => $v['name'] );
            }
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 客户账号联想
     * @param int $cityId
     * @param string $portType
     */
    public function getClientInfoAction()
    {
        $inputInfo = $this->request->getPost('keyword', string);
        $cityId    = $this->request->getPost('cityId', int);
        $list      = [ ];
        $inputInfo = trim($inputInfo);
        $con       = " name like '{$inputInfo}%'";
        if($cityId > 0)
        {
            // $con .= " and cityId={$cityId}";
        }

        $rs = VipAccount::find(array(
            $con,
            "limit"   => 10,
            "columns" => "toId,name"
        ))->toArray();
        foreach($rs as $k => $v)
        {
            $list[] = array( "id" => $v['toId'], "name" => $v['name'] );
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /*
     * @desc 小区联想
     * */

    public function getParkNameAction()
    {
        $inputInfo = $this->request->getPost('keyword', string);
        $cityId    = $this->request->getPost('cityId', int);
        $list      = [ ];
        $inputInfo = trim($inputInfo);
        $con       = "(name like '{$inputInfo}%' or pinyin like '{$inputInfo}%' or pinyinAbbr like '{$inputInfo}%')";
        if($cityId > 0)
        {
            $con .= " and cityId={$cityId}";
        }

        $rs = Park::find(array(
            $con,
            "limit" => 10
        ))->toArray();
        foreach($rs as $k => $v)
        {
            $list[] = array( "id" => $v['id'], "name" => $v['name'] );
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /*
     * @desc 区域联想
     * */

    public function getAreaInfoAction()
    {
        $inputInfo = $this->request->getPost('keyword', string);
        $cityId    = $this->request->getPost('cityId', int);
        $comId     = $this->request->getPost('comId', int);
        var_dump($comId);
        $list      = [ ];
        $inputInfo = trim($inputInfo);
        $con       = "(name like '{$inputInfo}%' or abbr like '{$inputInfo}%' or address like '{$inputInfo}%')";
        if($cityId > 0)
        {
            $con .= " and cityId={$cityId}";
        }

        $rs = Area::find(array(
            $con,
            "limit" => 10
        ))->toArray();
        foreach($rs as $k => $v)
        {
            $list[] = array( "id" => $v['id'], "name" => $v['name'] );
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /*
     * @desc 门店联想
     * */

    public function getShopAction()
    {
        $inputInfo = $this->request->getPost('keyword', string);
        $cityId    = $this->request->getPost('cityId', int);
        $list      = [ ];
        $inputInfo = trim($inputInfo);
        $con       = "(name like '{$inputInfo}%' or abbr like '{$inputInfo}%' or address like '{$inputInfo}%')";
        if($cityId > 0)
        {
            $con .= " and cityId={$cityId}";
        }

        $rs = Shop::find(array(
            $con,
            "limit" => 10
        ))->toArray();
        foreach($rs as $k => $v)
        {
            $list[] = array( "id" => $v['id'], "name" => $v['name'] );
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    /**
     * 检查账户是否存在
     */
    public function checkAccnameAction()
    {
        $type    = $this->request->getPost('type', int); //账号类型
        $accname = $this->request->getPost('accname', string);
        $rs      = VipAccount::findFirst("to={$type} and name='{$accname}'");
        if($rs)
        {
            $this->show("JSON");
        }
        $this->show("ERROR");
    }

    //根据公司id获取区域
    public function getAreaByComAction($comId)
    {
        $rs = Area::find("comId=$comId AND status=".Area::STATUS_OK)->toArray();
        foreach($rs as $k => $v)
        {
            $list[] = array( 'id' => $v['id'], 'name' => $v['name'] );
        }
        $this->_json['data'] = $list;
        $this->show("JSON");
    }

    public function uploadimageAction($userId = 0)
    {
        $data = array();

        $imageRes = Scs::Instance()->uploadImage('image_upload', Image::FROM_ADMIN, $userId);

        if(isset($imageRes['error']))
        {
            $data   = $imageRes['error'];
            $status = 1;
        }
        else
        {
            $data   = $imageRes;
            $status = 0;
        }

        $this->show("JSON", array( 'msg' => $data, 'status' => $status ));
    }

    public function getSaleByCityIdAction($cityId)
    {
        $areaSource = Crmallocation::find("type=".Crmallocation::AREA)->toArray(); //获取区域的资源分配
        foreach($areaSource as $source)
        {
            $salesIds[$source['toId1']] = $source['toId1'];
            $csIds[$source['toId2']]    = $source['toId2'];
        }
        $data["sales"]       = AdminUser::instance()->getOptions(AdminUser::ROLE_SALE, $salesIds, $cityId); //获取销售
        $data["CSId"]        = AdminUser::instance()->getOptions(AdminUser::ROLE_CS, $csIds, $cityId); //获取客服
        $this->_json['data'] = $data;
        $this->show("JSON");
    }

    public function getImageSizeAction()
    {
        $url        = str_replace("180x120_", "", trim($this->request->getPost('url', 'string', '')));
        $arrRequest = getimagesize($url);

        $this->show('JSON', array( 'width' => $arrRequest[0], 'height' => $arrRequest[1] ));
    }

    public function ueditUploadImageAction()
    {

        $action = $_GET['action'];
        if($action == 'config')
        {
            $info = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(APPROOT."config/uedit.config.json")), true);
        }
        else if($action == 'uploadimage')
        {
            $this->_userInfo = Cookie::get(LOGIN_KEY);
            $imageRes        = Scs::Instance()->uploadImage('upfile', Image::FROM_ADMIN, $this->_userInfo["id"]);

            if(isset($imageRes['error']))
            {
                $info = array(
                    "state"    => $imageRes['error'],
                    "url"      => "",
                    "title"    => "",
                    "original" => "",
                    "type"     => "",
                    "size"     => "0"
                );
            }
            else
            {
                $info = array(
                    "state"    => "SUCCESS",
                    "url"      => $imageRes['upload_url'],
                    "title"    => "",
                    "original" => "",
                    "type"     => $imageRes['ext'],
                    "size"     => "1"
                );
            }

        }

        echo json_encode($info);
    }

}
