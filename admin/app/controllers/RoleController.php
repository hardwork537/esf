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
        $data['menuList'] = Menu::getMenu();        
        //角色信息
        $roleList = Roles::find(null, 0)->toArray();
        
        foreach($roleList as $value)
        {
            $powers = json_decode($value['power'], true);
            $menuId = array();
            foreach($powers as $v)
            {
                $menuId[] = $v['menuId'];
            }
            $roles[$value['id']] = array(
                'name' => $value['name'],
                'menuId' => $menuId
            );
        }
        
        $data['roles'] = $roles;
        
        $this->show(null, $data);
    }

    public function editAction()
    {
        $roleId = $this->request->getPost('roleId', 'int', 0);
        $menuId = trim($this->request->getPost('menuId', 'string', ''));

        if(!$menuId)
        {
            $menuIds = array();
        } else
        {
            $menuIds = explode('_', $menuId);
        }

        $roleInfo = Roles::instance()->getRoleForOption();
        if(!array_key_exists($roleId, $roleInfo))
        {
            $this->show("ERROR", '非法操作！');
        }

        $menuList = Menu::getMenu();

        if(!empty($menuIds))
        {
            foreach($menuIds as $v)
            {
                if(!array_key_exists($v, $menuList))
                {
                    $this->show("ERROR", '非法操作！');
                }
            }
        }

        $updateRes = Roles::instance()->updateRolePower($roleId, $menuIds);
        if($updateRes)
        {
            $this->show("JSON", array("status" => 0, "info" => "{$roleInfo[$roleId]}权限修改成功！"));
        } else
        {
            $this->show("ERROR", "{$roleInfo[$roleId]}权限修改失败！");
        }
    }

}
