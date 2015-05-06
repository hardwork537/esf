<?php

class RoleController extends ControllerBase
{

    public function listAction()
    {
        $data = array();
        $condition = array(
            "order" => "weight asc",
        );
        //一级菜单信息
        $data['menuList'] = Menu::find($condition, 0)->toArray();
        //角色信息
        $roleList = Roles::instance()->getRoleMoudel();
        $roles = array();
        foreach($roleList as $v)
        {
            $power = json_decode($v['power'], true);
            $powers = array();
            $roles[$v['id']] = array(
                'name' => $v['name']
            ); 
            foreach($power as $v)
            {
                $powers['menuId'] = $v['menuId'];
            }
        }

        foreach($powers as $v)
        {
            $powerList[$v['roleId']]['roleId'] = $v['roleId'];
            $powerList[$v['roleId']]['roleName'] = $roleList[$v['roleId']]['name'];
            $powerList[$v['roleId']]['moudelIds'][] = $v['moudelId'];
        }
        foreach($roleList as $v)
        {
            if(!isset($powerList[$v['id']]))
            {
                $powerList[$v['id']] = array(
                    'roleId' => $v['id'],
                    'roleName' => $v['name'],
                    'moudelIds' => array()
                );
            }
        }
        $data['powerList'] = $powerList;

        $this->show(null, $data);
    }

    public function editAction()
    {
        $roleId = $this->request->getPost('roleId', 'int', 0);
        $moudelId = trim($this->request->getPost('moudelId', 'string', ''));
        $moudels = array();
        if(!$moudelId)
        {
            $moudelId = array();
        } else
        {
            $moudelId = explode('_', $moudelId);
        }

        $roleInfo = CmsRole::instance()->getRoleForOption();
        if(!array_key_exists($roleId, $roleInfo))
        {
            $this->show("ERROR", '非法操作！');
        }

        $moudelInfo = CmsMoudel::find(array('columns' => 'id,menuId'), 0)->toArray();
        $moudelList = array();
        foreach($moudelInfo as $v)
        {
            $moudelList[$v['id']] = $v;
        }

        if(empty($moudelId))
        {
            $moudels = array();
        } else
        {
            foreach($moudelId as $v)
            {
                if(array_key_exists($v, $moudelList))
                {
                    $moudels[] = $moudelList[$v];
                } else
                {
                    $this->show("ERROR", '非法操作！');
                }
            }
        }

        $updateRes = CmsPower::instance()->updatePower($roleId, $moudels);
        if($updateRes)
        {
            $this->show("JSON", array("status" => 0, "info" => "{$roleInfo[$roleId]}权限修改成功！"));
        } else
        {
            $this->show("ERROR", "{$roleInfo[$roleId]}权限修改失败！");
        }
    }

}
