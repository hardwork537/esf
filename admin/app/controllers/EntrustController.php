<?php

class EntrustController extends ControllerBase
{

    public function listAction()
    {
        $data = array();
        $data['cityId'] = $this->_cityId;
        
        $paramRes = $this->_filterParams();
        $data['params'] = $paramRes['params'];
        $data['statuses'] = array(
            House::STATUS_ONLINE => '已处理',
            House::STATUS_OFFLINE => '待处理'
        );
        $where = $paramRes['where'];
        $totalNum = House::count($where);
        
        if($totalNum < 1)
        {
            $this->show(null, $data);
            return ;
        }
        $column = "distId,regId,id,createTime,parkId,bedRoom,livingRoom,bathRoom,bA,price,agent,agentPhone,status";
        $condition = array(
            'conditions' => $where,
            'offset' => $this->_offset,
            'limit' => $this->_pagesize,
            'order' => 'createTime desc',
            'columns' => $column
        );
        $result = House::find($condition, 0)->toArray();
        $lists = $distIds = $regIds = $parkIds = array();
        foreach($result as $v)
        {
            $v['createTime'] = date('Y.n.d', strtotime($v['createTime']));
            $lists[$v['id']] = $v;
            $v['distId'] > 0 && $distIds[] = $v['distId'];
            $v['regId'] > 0 && $regIds[] = $v['regId'];
            $v['parkId'] > 0 && $parkIds[] = $v['parkId'];
        }
        
        $data['lists'] = $lists;
        //区域
        if(!empty($distIds))
        {
            $distIds = array_flip(array_flip($distIds));
            $data['district'] = CityDistrict::instance()->getDistByIds($distIds, 'id,name');
        }
        //板块
        if(!empty($regIds))
        {
            $regIds = array_flip(array_flip($regIds));
            $data['region'] = CityRegion::instance()->getRegionByIds($regIds, 'id,name');
        }
        //小区
        if(!empty($parkIds))
        {
            $parkIds = array_flip(array_flip($parkIds));
            $data['park'] = Park::instance()->getParkByIds($parkIds, 'id,name');
        }
        
        $this->show(null, $data);
    }
    
    private function _filterParams()
    {
        $params = array();
        $params['distId'] = $distId = $this->request->get('distId', 'int', 0);
        $params['regId'] = $regId = $this->request->get('regId', 'int', 0);
        $params['status'] = $status = $this->request->get('status', 'int', 0);
        $params['startDate'] = $startDate = $this->request->get('startDate', 'string', '');
        $params['endDate'] = $endDate = $this->request->get('endDate', 'string', '');
        $params['keyword'] = $keyword = trim($this->request->get('keyword', 'string', ''));
        
        $where = "type=".House::TYPE_WEITUO;
        HEAD_CITY == $this->_cityId || $where .= " and cityId={$this->_cityId}";
        if($keyword)
        {
            $keyword = "cityId={$this->_cityId} and name='{$keyword}' and status=".Park::STATUS_VALID;
            $park = Park::findFirst($parkWhere, 0)->toArray();
            if(!empty($park))
            {
                $where .= " and parkId={$park['id']}";
            } else {
                $where .= " and (agent='{$keyword}' or agentPhone='{$keyword}')";
            }          
        }
        $distId && $where .= " and distId={$distId}";
        $regId && $where .= " and regId={$regId}";
        if($startDate)
        {
            $startTime = date('Y-m-d H:i:s', strtotime($startDate));
            $where .= " and createTime>='{$startTime}'";
        }
        if($endDate)
        {
            $endTime = date('Y-m-d H:i:s', strtotime($endDate));
            $where .= " and createTime<='{$endTime}'";
        }
        $status && $where .= " and status={$status}";
        
        return array('status'=>0, 'where'=>$where, 'params'=>$params);
    }
}
