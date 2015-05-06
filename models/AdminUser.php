<?php

class AdminUser extends CrmBaseModel
{
    //用户状态
    const STATUS_VALID = 1; // 有效
    const STATUS_INVALID = 2; // 无效

    public $error;
    public $id;
    protected $accname = '';
    protected $name;
    protected $password = '';
    protected $tel = 0;
    protected $adminRoleId = 0;
    protected $cityId = 0;
    protected $status = 0;
    protected $email = '';
    //protected $crmTeamId = 0;
    protected $crmRoleId = 0;
    protected $addTime = '0000-00-00 00:00:00';

    public function columnMap()
    {
        return array(
            'userId' => 'id',
            'userAccname' => 'accname',
            'userName' => 'name',
            'userPassword' => 'password',
            'userTel' => 'tel',
            'adminRoleId' => 'adminRoleId',
            'cityId' => 'cityId',
            'userStatus' => 'status',
            'userEmail' => 'email',
            //'crmTeamId' => 'crmTeamId',
            'crmRoleId' => 'crmRoleId',
            'userAddTime' => 'addTime'
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
    }

    public function getSource()
    {
        return "admin_user";
    }

    /**
     * 实例化对象
     *
     * @param type $cache
     * @return \Users_Model
     */
    public static function instance($cache = true)
    {
        return parent::_instance(__CLASS__, $cache);
        return new self();
    }

    public function add($arr)
    {
        $count = self::count("email='{$arr["email"]}' or accname='{$arr["accname"]}'");
        if($count > 0)
        {
            $this->error = "用户名或邮箱已经存在";
            return false;
        }
        $data = array(
            'accname' => $arr["accname"],
            'name' => $arr["name"],
            'email' => $arr["email"],
            'cityId' => intval($arr["cityId"]),
            'adminRoleId' => intval($arr["adminRoleId"]),
            'crmRoleId' => intval($arr["crmRoleId"]),
            //'crmTeamId' => intval($arr["crmTeamId"]),
            'tel' => $arr["tel"],
            'status' => 1,
            'addTime' => time(),
            'password' => md5(md5('esf.focus.cn' . $arr["password"]))
        );

        $rs = $this->create($data);
        if($rs == false)
        {
            $this->error = "添加用户失败";
            return false;
        }
        return true;
    }

    public function edit($id, $arr)
    {
        $id = intval($id);

        $count = self::count("email='{$arr["email"]}' and id<>$id ");
        if($count > 0)
        {
            $this->error = "邮箱已经存在";
            return false;
        }

        $data = array(
            //'accname' => $arr["accname"],
            //'name' => $arr["name"],
            'email' => $arr["email"],
            'cityId' => intval($arr["cityId"]),
            'adminRoleId' => intval($arr["adminRoleId"]),
            'tel' => $arr["tel"]
        );
        if($arr["password"])
        {
            $data['password'] = md5(md5('esf.focus.cn' . $arr["password"]));
        }

        $rs = self::findFirst($id);
        if($rs->update($data))
        {
            return true;
        }
        $this->error = "更新用户失败";
        return false;
    }

    /**
     * 删除用户
     *
     * @param type $id
     * @return type
     */
    public function del($id)
    {
        $rs = self::findFirst($id);
        $rs->status = 0;
        return $rs->update();
    }

    /**
     * @abstract 批量获取用户信息
     * @param array  $ids
     * @param string $columns
     * @return array|bool
     *
     */
    public function getUserByIds($ids, $columns = '', $status = self::STATUS_VALID)
    {
        if(empty($ids))
            return array();
        if(is_array($ids))
        {
            $arrBind = $this->bindManyParams($ids);
            $arrCond = "id in({$arrBind['cond']}) and status={$status}";
            $arrParam = $arrBind['param'];
            $condition = array(
                $arrCond,
                "bind" => $arrParam,
            );
        } else
        {
            $condition = array(
                'conditions' => "id={$ids} and status={$status}"
            );
        }
        $columns && $condition['columns'] = $columns;
        $arrUser = self::find($condition, 0)->toArray();
        $arrRuser = array();
        foreach($arrUser as $value)
        {
            $arrRuser[$value['id']] = $value;
        }
        return $arrRuser;
    }

    /**
     * @abstract 批量获取用户信息
     * @param array  $accounts
     * @param string $columns
     * @return array
     *
     */
    public function getUserByAccnames($accounts, $columns = '')
    {
        if(empty($accounts))
            return array();
        if(is_array($accounts))
        {
            $arrBind = $this->bindManyParams($accounts);
            $arrCond = "accname in({$arrBind['cond']})";
            $arrParam = $arrBind['param'];
            $condition = array(
                $arrCond,
                "bind" => $arrParam,
            );
            $columns && $condition['columns'] = $columns;
            $arrUser = self::find($condition, 0)->toArray();
        } else
        {
            $arrUser = self::findFirst("accname={$accounts}", 0)->toArray();
            empty($arrUser) || $arrUser = array($arrUser);
        }
        $arrRuser = array();
        foreach($arrUser as $value)
        {
            $arrRuser[$value['accname']] = $value;
        }
        return $arrRuser;
    }

    public function getOptions($type, $ids = array(), $cityId = 0)
    {
        if(!empty($ids))
        {
            $idstr = implode(',', $ids);
            $where = "adminRoleId=$type AND id in({$idstr}) AND status=" . self::STATUS_VALID;
        } else
        {
            $where = "adminRoleId=$type AND status=" . self::STATUS_VALID;
        }
        if($cityId)
        {
            $where .= " AND cityId=$cityId";
        }

        $rs = self::find([
                "conditions" => $where,
                "order" => "id asc",
            ])->toArray();
        foreach($rs as $v)
        {
            $data[$v['id']] = $v['name'];
        }
        return $data;
    }

    /**
     * 根据城市id,团队id,区域id,板块id,取销售用户信息
     * @param int $cityId   城市id
     * @param int $teamId   团队
     * @param int $distId   区域
     * @param int $regId    板块
     * @return array
     */
    public function getUserForSearch($cityId = 0, $teamId = 0, $distId = 0, $regId = 0)
    {
//        if($distId > 0)
//        {
//            $rs = Usersarea_Model::instance()->Get(array("hot_area_id"=>$block_id), "", "", "", " user_id");
//            if(is_array($rs) && !empty($rs)){
//                foreach($rs as $k=>$v){
//                    $userids[] = $v['user_id'];
//                }
//            }else{
//                return array();
//            }
//        }elseif($area_id>0){
//            $rs = Usersarea_Model::instance()->Get(array("district_id"=>$area_id), "", "", "", " user_id");
//            if(is_array($rs) && !empty($rs)){
//                foreach($rs as $k=>$v){
//                    $userids[] = $v['user_id'];
//                }
//            }else{
//                return array();
//            }
//        }
//        elseif($city_id>0){
//            $condition['city_id'] = $city_id;
//        }
        $where = "cityId={$cityId} and adminRoleId=" . AdminUser::ROLE_SALE . " and status=" . AdminUser::STATUS_VALID;
        $teamId > 0 && $where .= " and crmTeamId={$teamId}";


//        if(is_array($userids) && !empty($userids)){
//            $condition['crm_user_id'] = $userids;
//        }

        $userInfo = self::find($where, 0)->toArray();
        if(empty($userInfo))
        {
            return array();
        }
        $data = array();
        foreach($userInfo as $value)
        {
            $data[$value['id']] = $value['name'];
        }

        return $data;
    }

    //根据用户名，城市id，roleId获取用户信息
    public function getUserByAccname($cityId, $accname, $roleId = 2)
    {
        $con = "adminRoleId =$roleId";
        if($cityId)
        {
            $con .= " AND cityId in(0, $cityId)";
        }
        if($accname)
        {
            $con .= " AND accname = $accname";
        }
        $rs = self::find($con)->toArray();
        if(empty($rs))
            return array();

        foreach($rs as $k => $v)
        {
            $result[$v['id']] = $v['id'];
        }
        return $result;
    }

    /**
     * 根据用户id获取对应的团队id
     * @param array|int $userIds
     * @return type
     */
    public function getTeamIdByUserId($userIds)
    {
        if(empty($userIds))
        {
            return array();
        }
        if(is_array($userIds))
        {
            $arrBind = $this->bindManyParams($userIds);
            $arrCond = "id in({$arrBind['cond']}) and status=" . self::STATUS_VALID;
            $arrParam = $arrBind['param'];
            $condition = array(
                $arrCond,
                "bind" => $arrParam,
            );
        } else
        {
            $condition = array(
                'conditions' => "id={$userIds} and status=" . self::STATUS_VALID
            );
        }
        $condition['columns'] = 'id,crmTeamId';
        $arrUser = self::find($condition, 0)->toArray();
        $arrRuser = array();
        foreach($arrUser as $value)
        {
            $arrRuser[$value['id']] = $value['crmTeamId'];
        }
        return $arrRuser;
    }

}
