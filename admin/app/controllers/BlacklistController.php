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
            'offset' => $this->_offset,
            'limit' => $this->_pagesize
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
    
    public function delAction($id = 0)
    {
        $id = intval($id);
        $black = Blacklist::findFirst($id);
        if(!$black)
        {
            $this->show('JSON', array('status'=>1, 'info'=>'不存在'));
        }
        
        $delRes = $black->delete();
        if($delRes)
        {
            $this->show('JSON', array('status'=>0, 'info'=>'删除成功'));
        } else {
            $this->show('JSON', array('status'=>1, 'info'=>'删除失败'));
        }
    }
    public function importAction()
    {
        $rs = Utility::importCsv();
        if($rs == 1)
        {
            $str = "文件类型不正确";
            echo "<script>parent.doframe('".$str."')</script>";
            die();
        }
        elseif($rs == 2)
        {
            $str = "请选择上传的文件";
            echo "<script>parent.doframe('".$str."')</script>";
            die();
        }
        elseif(is_array($rs))
        {
            set_time_limit(0);

            $str  = "";
            $data = $label_names = array();
            foreach($rs as $k => $v)
            {
                $n = $k + 2;

                $phone  = trim($v[0]);
                $remark  = trim($v[1]);

                if(!preg_match('/^1[0-9]{10}$/', $phone))
                {
                    $str = "第 {$n} 行手机格式错误！";
                    break;
                }
                
                $data[$phone] = array(
                    'phone' => $phone,
                    'remark'   => $remark ? $remark : 'excel导入'
                );
            }
            
            if($str == "")
            {
                //添加数据
                $ret = Blacklist::instance()->addBlackList($this->_userInfo['id'], $data);
                $str = 0 !== $ret['status'] ? "导入失败，请稍后重试！" : "导入成功！";
            }
        }
        else
        {
            $str = "未知错误,请重试";
        }

        echo "<script>parent.doframe('".$str."')</script>";
        exit();
    }
    
    public function downloadAction()
    {
        $filename = ROOT.'src/admin/download/blacklist.csv';
        if(false == file_exists($filename))
        {
            exit($filename);
            return false;
        }

        // http headers
        header('Content-Type: application-x/force-download');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-length: '.filesize($filename));

        // for IE6
        if(false === strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6'))
        {
            header('Cache-Control: no-cache, must-revalidate');
        }
        header('Pragma: no-cache');

        // read file content and output
        return readfile($filename);;
    }
}
