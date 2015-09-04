<?php

class PhoneController extends ControllerBase
{

    public function listAction()
    {
        $data = array();
        $where = "1=1";
        $mobile = trim($this->request->get('mobile', 'string', ''));
        $mobile && $where .= " and mobile='{$mobile}'";
        $data['mobile'] = $mobile;

        $condition = array(
            "conditions" => $where,
            "order" => "id desc",
            "limit" => $this->_pagesize,
            "offset" => $this->_offset
        );
        $pageCount = Phone400::count($where);
        $data['page'] = Page::create($pageCount, $this->_pagesize);
        $data['lists'] = Phone400::find($condition, 0)->toArray();
        
        $this->show(null, $data);
    }

    public function addAction()
    {
        if($this->request->isPost())
        {
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            $data = $checkRes['params'];
            $addRes = Phone400::instance()->add($data['mobile'], $data['phoneHost'], $data['phoneExt']);
            $this->show("JSON", $addRes);
        }
        $this->show("JSON");
    }

    public function editAction($id = 0)
    {
        if($this->request->isPost())
        {
            $id = intval($this->request->getPost("id", "int"));
            $checkRes = $this->_filterParams();
            if(0 != $checkRes['status'])
            {
                $this->show("JSON", $checkRes);
            }
            $rs = Phone400::instance()->edit($id, $checkRes['params']);

            $this->show("JSON", $rs);
        }
        $where[] = "id=$id";
        $this->_json["data"] = Phone400::findFirst($where, 0)->toArray();
        
        $this->show("JSON");
    }

    /**
     * 添加、修改时 参数验证
     */
    private function _filterParams()
    {
        //验证手机号
        $mobile = trim($this->request->getPost('mobile', 'string', ''));
        if(!$mobile)
        {
            return array('status' => 1, 'info' => '手机号不能为空!');
        }
        if(!preg_match('/1[0-9]{10}/', $mobile))
        {
            return array('status' => 1, 'info' => '手机号格式错误!');
        }

        //验证400电话主号
        $phoneHost = trim($this->request->getPost('phoneHost', 'string', ''));
        if(!$phoneHost)
        {
            return array('status' => 1, 'info' => '400电话主号不能为空!');
        }
        if(!preg_match('/^[0-9\-]{1,15}$/', $phoneHost))
        {
            return array('status' => 1, 'info' => '400电话主号格式错误!');
        }
        
        //验证400电话小号
        $phoneExt = trim($this->request->getPost('phoneExt', 'string', ''));
        if(!$phoneExt)
        {
            return array('status' => 1, 'info' => '400电话小号不能为空!');
        }
        if(!preg_match('/^[0-9]{1,5}$/', $phoneExt))
        {
            return array('status' => 1, 'info' => '400电话小号格式错误!');
        }
        $params = array(
            'mobile' => $mobile,
            'phoneHost' => $phoneHost,
            'phoneExt' => $phoneExt
        );

        return array('status' => 0, 'params' => $params);
    }
    
    public function delAction($id = 0)
    {
        $id      = intval($id);
        $delRes = Phone400::instance()->del($id);

        $this->show("JSON", $del_ret);
    }

}
