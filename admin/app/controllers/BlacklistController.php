<?php

class BlacklistController extends ControllerBase
{

    public function listAction()
    {
        $data = array();
        $where = "";
        $phone = $this->request->get('phone', 'string', '');
        $phone && $where .= "phone='{$phone}'";
        $data['startDate'] = $startDate = $this->request->get('startDate', 'string', '');
        if($startDate)
        {
            $where .= $where ? " and addTime>='{$startDate} 00:00:00'" : "addTime>='{$startDate} 00:00:00'";
        }
        $data['endDate'] = $endDate = $this->request->get('endDate', 'string', '');
        if($endDate)
        {
            $where .= $where ? " and addTime<='{$endDate} 23:59:59'" : "addTime<='{$endDate} 23:59:59'";
        }
        
        $totalNum = Blacklist::count($where);
        if($totalNum == 0)
        {
            $this->show(null, $data);
            return;
        }
        $condition = array(
            'conditions' => $where,
            'order' => 'id desc',
            'limit' => array(
                'offset' => $this->_offset,
                'number' => $this->_pagesize
            )
        );
        $result = Blacklist::find($condition, 0)->toArray();
        
        $list = $userIds = array();
        foreach($result as $v)
        {
            $list[$v['id']] = $v;
            $userIds[] = $v['operator'];
        }
        //操作人姓名
        $userIds = array_flip(array_flip($userIds));
        if(!empty($userIds))
        {
            $data['users'] = AdminUser::instance()->getUserByIds($userIds, 'id,name');
        }
        $data['list'] = $list;
        $data['page']  = Page::create($totalNum, $this->_pagesize);
        
        $this->show(null, $data);
    }

    public function addAction()
    {
        $phone = $this->request->getPost('phone', 'string', '');
        if(!preg_match('/^1[0-9]{10}$/', $phone))
        {
            $this->show('JSON', array('status'=>1, 'info'=>'手机格式不正确'));
        }
        $phoneNum = Blacklist::count("phone='{$phone}'");
        if($phoneNum > 0)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'该手机号已经存在'));
        }
        $remark = $this->request->getPost('remark', 'string', '');
        
        $insertData = array(
            'phone' => $phone,
            'remark' => $remark,
            'operator' => $this->_userInfo['id'],
            'addTime' => date('Y-m-d H:i:s')
        );
        $phoneObj = Blacklist::instance();
        $addRes = $phoneObj->create($insertData);
        
        if($addRes)
        {
            $this->show('JSON', array('status'=>0, 'info'=>'手机号添加成功'));
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'手机号添加失败'));
        }
    }
}
