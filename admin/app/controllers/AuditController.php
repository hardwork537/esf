<?php

/**
 * @abstract  房源图片审核
 */
class AuditController extends ControllerBase
{
    
    private static $pageSize = 30;

    public function housepicAction()
    {
        $this->_pagesize = self::$pageSize;
        $data = array();
        $data['startDate'] = $startDate = trim($this->request->get('startDate', 'string', ''));
        $data['endDate'] = $endDate = trim($this->request->get('endDate', 'string', ''));
        
        $where = "status=".  HousePicture::STATUS_TOPASS;
        $startDate && $where .= " and updateTime>='{$startDate} 00:00:00'";
        $endDate && $where .= " and updateTime<='{$endDate} 23:59:59'";
        
        $totalNum = HousePicture::count($where);
        if($totalNum < 1)
        {
            $this->show(null, $data);
            return ;
        }
        $condition = array(
            'conditions' => $where,
            'order' => 'id desc',
            'offset' => $this->_offset,
            'limit' => $this->_pagesize
        );
        $result = HousePicture::find($condition, 0)->toArray();
        $pictures = array();
        foreach($result as $v)
        {
            $v['url'] = ImageUtility::getImgUrl(PICTURE_PRODUCT_NAME, $v['imgId'], $v['imgExt']);
            $pictures[$v['id']] = $v;
        }
        $data['pictures'] = $pictures;
        $data['page'] = Page::create($totalNum, $this->_pagesize);
        
        $this->show(null, $data);
    }

    public function delAction()
    {
        if(!$this->request->isPost())
        {
            $this->show('JSON', array('status'=>1, 'info'=>'错误访问'));
        }
        
        $checkRes = $this->_checkParams();
        if(0 !== $checkRes['status'])
        {
            $this->show('JSON', $checkRes);
        }
        
        $delRes = HousePicture::instance()->delImageById($checkRes['id']);
        
        $this->show('JSON', $delRes);
    }
    
    public function passAction()
    {
        if(!$this->request->isPost())
        {
            $this->show('JSON', array('status'=>1, 'info'=>'错误访问'));
        }
        
        $checkRes = $this->_checkParams();
        if(0 !== $checkRes['status'])
        {
            $this->show('JSON', $checkRes);
        }
        
        $auditRes = HousePicture::instance()->passAudit($checkRes['id']);
        
        $this->show('JSON', $auditRes);
    }
    
    private function _checkParams()
    {
        $id = trim($this->request->getPost('id', 'string', ''));
        if(!$id)
        {
            return array('status'=>1, 'info'=>'缺少参数');
        }
        $ids = explode('-', $id);
        $idNum = count($ids);
        $where = 1 == $idNum ? "id={$ids[0]}" : "id in(".  implode(',', $ids).")";
        $condition = array(
            'conditions' => $where,
            'columns' => 'id,status,imgId'
        );
        $res = HousePicture::find($condition, 0)->toArray();
        if(count($res) != $idNum)
        {
            return array('status'=>1, 'info'=>'部分图片不存在');
        }
        $picIds = array();
        foreach($res as $v)
        {
            $picIds[] = $v['id'];
        }
        
        return array('status' => 0, 'id' => $picIds);
    }
}
