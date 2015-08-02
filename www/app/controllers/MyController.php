<?php

class MyController extends ControllerBase
{

    private $_sexList = array(
        WwwUser::SEX_MALE => '男',
        WwwUser::SEX_FEMALE => '女'
    );
    private $_userObj = null;

    protected function initialize()
    {
        parent::initialize();
        //验证
        $checkRes = $this->_checkLogin();
    }

    public function favoriteAction($type = 0)
    {
        $data = array();
        $data['cssList'] = array('css/counter.css');

        $totalNum = HouseFavorite::count("userId={$this->_userInfo['id']}");
        if($totalNum == 0)
        {
            $this->show(null, $data);
            return;
        }
        $data['page'] = Page::create($totalNum, $this->_pagesize, '收藏');
        $type = intval($type);
        $condition = array(
            'conditions' => "userId={$this->_userInfo['id']}",
            'columns' => 'houseId'
        );
        $res = HouseFavorite::find($condition, 0)->toArray();
        $houseIds = array();
        foreach($res as $v)
        {
            $houseIds[] = $v['houseId'];
        }
        //获取房源信息
        $houseCondition = array(
            'conditions' => "id in (" . implode(',', $houseIds) . ")",
            'columns' => 'id,distId,parkId,bA,remark,price,livingRoom,bathRoom,bedRoom,floor,floorMax',
            'offset' => $this->_offset,
            'limit' => $this->_pagesize
        );
        $link = array(
            'default' => '/my/favorite/',
            'price' => '/my/favorite/1/',
            'area' => '/my/favorite/3/'
        );
        $data['curr'] = 'default';
        if(1 == $type)
        {
            $houseCondition['order'] = 'price asc';
            $link['price'] = '/my/favorite/2/';
            $data['curr'] = 'price';
        } elseif(2 == $type)
        {
            $houseCondition['order'] = 'price desc';
            $data['curr'] = 'price';
        } elseif(3 == $type)
        {
            $houseCondition['order'] = 'bA asc';
            $link['area'] = '/my/favorite/4/';
            $data['curr'] = 'area';
        } elseif(4 == $type)
        {
            $houseCondition['order'] = 'bA desc';
            $data['curr'] = 'area';
        }
        $data['link'] = $link;
        $data['type'] = $type;

        $houses = House::find($houseCondition, 0)->toArray();
        $data['houseList'] = $houses;

        $parkIds = $distIds = $houseId = array();
        foreach($houses as $v)
        {
            $parkIds[] = $v['parkId'];
            $distIds[] = $v['distId'];
        }
        $parkIds = array_flip(array_flip($parkIds));
        $distIds = array_flip(array_flip($distIds));
        //获取小区信息
        if(!empty($parkIds))
        {
            $parks = Park::instance()->getParkByIds($parkIds, 'id,name,address,salePrice');
            $data['parkList'] = $parks;
        }
        //获取小区信息
        if(!empty($distIds))
        {
            $dists = CityDistrict::instance()->getDistByIds($distIds, 'id,name');
            $data['distList'] = $dists;
        }
        //获取房源图片
        $houseImgs = HousePicture::instance()->getHousePicsByIds($houseIds);
        $data['imgs'] = $houseImgs;
        
        $this->_setTitle('房易买-我的收藏');

        $this->show(null, $data);
    }

    public function accountAction()
    {
        $data = array();
        $data['cssList'] = array('css/counter.css');
        $data['user'] = $user = $this->_userInfo;
        $data['sexList'] = $this->_sexList;
        
        $this->_setTitle('房易买-管理账号');

        $this->show(null, $data);
    }

    public function modifyuserAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_checkParams();
            if(0 !== $checkRes['status'])
            {
                $this->show('JSON', $checkRes);
            }

            $data = $checkRes['params'];
            $editRes = WwwUser::instance()->edit($this->_userInfo['id'], $data);

            if(0 !== $editRes['status'])
            {
                $this->show('JSON', $editRes);
            }
            $userInfo = $editRes['userInfo'];
            $user = array(
                'id' => $userInfo['id'],
                'name' => $userInfo['name'],
                'phone' => $userInfo['phone'],
                'sex' => $userInfo['sex']
            );
            Cookie::set(LOGIN_KEY, $user, LOGIN_LIFETIME);

            $this->show('JSON', array('status' => 0));
        } else
        {
            $this->show('JSON', array('status' => 1, 'info' => '非法请求'));
        }
    }

    private function _checkParams()
    {
        //验证手机号
        $phone = $this->request->getPost('phone', 'string', '');
        if(!$phone || !preg_match("/^1\d{10}$/", $phone))
        {
            return array('status' => 1, 'info' => '手机号格式错误！');
        }
        //验证姓名
        $name = trim($this->request->getPost('name', 'string', ''));
        if(!$name)
        {
            return array('status' => 1, 'info' => '姓名不能为空！');
        } elseif(mb_strlen($name, 'utf-8') > 50)
        {
            return array('status' => 1, 'info' => '姓名太长！');
        }
        //验证性别
        $sex = $this->request->getPost('sex', 'int', 0);
        if(0 == $sex)
        {
            return array('status' => 1, 'info' => '性别不能为空！');
        } elseif(!isset($this->_sexList[$sex]))
        {
            return array('status' => 1, 'info' => '无效的性别！');
        }

        $user = WwwUser::findFirst($this->_userInfo['id']);
        if(!$user)
        {
            return array('status' => 1, 'info' => '用户不存在');
        }
        //$this->_userObj = $user;
        //验证密码
        $oldPwd = trim($this->request->getPost('oldPwd', 'string', ''));
        $pwd = trim($this->request->getPost('pwd', 'string', ''));
        $rePwd = trim($this->request->getPost('rePwd', 'string', ''));
        if($oldPwd || $pwd || $rePwd)
        {
            if(!$oldPwd)
            {
                return array('status' => 1, 'info' => '请输入旧密码');
            }
            if(!$pwd)
            {
                return array('status' => 1, 'info' => '请输入密码');
            } elseif(!preg_match("/^[0-9a-zA-Z\-\.]{6,}$/", $pwd))
            {
                return array('status' => 1, 'info' => '密码格式错误');
            }
            if($pwd != $rePwd)
            {
                return array('status' => 1, 'info' => '两次密码输入不一致');
            }

            $oldPwdStr = $this->_getPasswordStr($oldPwd);
            if($oldPwdStr != $user->password)
            {
                return array('status' => 1, 'info' => '旧密码错误');
            }
            $pwdStr = $this->_getPasswordStr($pwd);
        }

        $params = array(
            'name' => $name,
            'sex' => $sex,
            'phone' => $phone,
        );
        $pwdStr && $params['password'] = $pwdStr;

        return array('status' => 0, 'params' => $params);
    }
}
