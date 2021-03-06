<?php

class WwwUser extends CrmBaseModel
{
    //用户状态
    const STATUS_VALID = 1; // 有效
    const STATUS_INVALID = 2; // 无效
    //性别
    const SEX_MALE = 1; //男性
    const SEX_FEMALE = 2; //女性

    protected $id;
    protected $name = '';
    protected $sex = 0;
    protected $password = '';
    protected $phone = '';
    protected $cityId = 0;
    protected $status = self::STATUS_VALID;
    protected $addTime = 0;

    public function columnMap()
    {
        return array(
            'userId' => 'id',
            'userName' => 'name',
            'userSex' => 'sex',
            'userPassword' => 'password',
            'userPhone' => 'phone',
            'cityId' => 'cityId',
            'userStatus' => 'status',
            'userAddTime' => 'addTime'
        );
    }

    public function initialize()
    {
        $this->setConn('esf');
    }

    public function getSource()
    {
        return "www_user";
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
    
    /**
     * 添加用户
     * @param type $data
     * @return type
     */
    public function add($data)
    {
        $checkRes = $this->_checkParams($data);
        if(0 != $checkRes['status'])
        {
            return $checkRes;
        }
        if($checkRes['userObj'])
        {
            //之前已经存在，修改状态
            $user = $checkRes['userObj'];
            $user->status = self::STATUS_VALID;
            if(!$user->update())
            {
                return array('status'=>1, 'info'=>'添加失败');
            }
        } else {
            $user = self::instance();
            
            $insertData = array(
                'phone' => $checkRes['data']['phone'],
                'password' => $checkRes['data']['password'],
                'addTime' => time()
            );
            if(!$user->create($insertData))
            {
                return array('status'=>1, 'info'=>'添加失败');
            }
        }
        
        return array('status'=>0, 'info'=>'注册成功', 'userInfo'=>$user->toArray());
    }
    
    private function _checkParams($data, $id = 0)
    {        
        $phone = $data['phone'];
        $where = "phone='{$phone}'";
        $user = self::findFirst($where);
        if($user)
        {
            if($user->status == self::STATUS_VALID)
            {
                return array('status' => 1, 'info' => '账号已经存在');
            }          
        }
        
        $params = array(
            'phone' => $data['phone'],
            'password' => $data['password']
        );
        
        return array('status' => 0, 'data' => $params, 'userObj' => $user);
    }
    
    /**
     * 编辑用户信息
     * @param type $data
     * @return type
     */
    public function edit($userId, $data)
    {
        $user = self::findFirst("id={$userId}");
        if(!$user)
        {
            return array('status'=>1, 'info'=>'用户不存在');
        }
        
        $editData = array();
        $data['name'] && $editData['name'] = $data['name'];
        $data['sex'] && $editData['sex'] = $data['sex'];
        $data['password'] && $editData['password'] = $data['password'];
        if($data['phone'])
        {
            $phone = $data['phone'];
            $phoneNum = self::count("phone='{$phone}' and id<>{$userId}");
            if($phoneNum > 0)
            {
                return array('status'=>1, 'info'=>'手机号已经存储在');
            }
            $editData['phone'] = $phone;
        }
        if(!$user->update($editData))
        {
            return array('status'=>1, 'info'=>'修改失败');
        }
        
        return array('status'=>0, 'info'=>'修改成功', 'userInfo'=>$user->toArray());
    }
    
    /**
     * 根据userid获取信息
     * @param int|array $userIds
     * @param string    $columns
     * @param int       $status
     * @return array
     */
    public function getUserByIds($userIds, $columns = '', $status = self::STATUS_VALID)
    {
        if(empty($userIds))
        {
            return array();
        }
        if(is_array($userIds))
        {
            $arrBind = $this->bindManyParams($userIds);
            $arrCond = "id in({$arrBind['cond']}) and status={$status}";
            $arrParam = $arrBind['param'];
            $condition = array(
                $arrCond,
                "bind" => $arrParam,
            );
        }
        else
        {
            $condition = array('conditions'=>"id={$userIds} and status={$status}");
        }
        $columns && $condition['columns'] = $columns;
        $arrUser  = self::find($condition,0)->toArray();
        $arrRUser = array();
        foreach($arrUser as $value)
        {
        	$arrRUser[$value['id']] = $value;
        }
        
        return $arrRUser;
    }
}
