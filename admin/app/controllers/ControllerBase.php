<?php

/**
 * @abstract  控制器业务层父类
 */
class ControllerBase extends ControllerCore
{

    protected $_json = array(
        'status' => 0,
        'info' => null,
        'data' => null
    );
    protected $_userInfo = null;
    protected $_cityId = 0;
    protected $_page = 1; // 当前页码
    protected $_pagesize = 15;
    protected $_offset = 0;
    protected $_cityCheck = true; // 是否需要根据城市获取数据
    private $_urlPowerArr = array();
    private $_menuArr = array();

    protected function initialize()
    {
        $this->_page = $this->request->get('page') ? : 1;
        $this->_offset = ($this->_page - 1) * $this->_pagesize;

        // 检查是否登录,登陆后初始化用户信息
        if(!defined('NO_NEED_LOGIN') || NO_NEED_LOGIN !== true)
        {
            $this->_checkLogin();
            $this->_userInfo = Cookie::get(LOGIN_KEY);
            $this->_id = $this->_userInfo['id'];
            $this->_cityId = $this->_userInfo['cityId'];
            $this->_roleId = $this->_userInfo['roleId'];
            // 检查是否需要根据城市取数据
            if($this->_cityId == 0)
            {
                return $this->response->redirect("/error/noaccess", true);
            } elseif($this->_cityId == HEAD_CITY)
            {
                $this->_cityCheck = false;
            }
        }
        // 检查是否有权限操作
        if((!defined('NO_NEED_LOGIN') || NO_NEED_LOGIN !== true) && (!defined('NO_NEED_POWER') || NO_NEED_POWER !== true))
        {
            $this->_menuArr = $this->_getMenus();
            $this->_checkPower();
        }
    }

    /**
     * 检查是否登录,没有登录直接跳到登录页面
     */
    private function _checkLogin()
    {
        if(Cookie::get(LOGIN_KEY))
        {
            Cookie::set(LOGIN_KEY, Cookie::get(LOGIN_KEY), LOGIN_LIFETIME);
        } else
        {
            $this->response->redirect("/login", true)->sendHeaders();
            exit();
        }
        return false;
    }

    /**
     * 获取所有有权限的菜单
     */
    private function _getMenus()
    {
        $_powers = Roles::findFirst($this->_userInfo['roleId'])->power;
        if($_powers)
        {
            $_powers = json_decode($_powers, true);
            foreach($_powers as $v)
            {
                //一级菜单 menu
                $menuKey = "admin-db-menu-id-" . $v['menuId'];
                $_menu = Mem::Instance()->Get($menuKey);
                if(empty($_menu))
                {
                    $_menu = Menu::findFirst("id={$v['menuId']}", 0)->toArray();
                    if(empty($_menu))
                    {
                        continue;
                    }
                    $res = Mem::Instance()->Set($menuKey, $_menu, 3600);
                }
                
                //二级菜单 moudel
                $moudelKey = "admin-db-moudel-id-" . $v['moudelId'];
                $_moudel = Mem::Instance()->Get($moudelKey);
                if(empty($_moudel))
                {
                    $_moudel = Moudel::findFirst("id={$v['moudelId']}", 0)->toArray();
                    if(empty($_moudel))
                    {
                        continue;
                    }
                    $res = Mem::Instance()->Set($moudelKey, $_moudel, 3600);
                }
                
                if($_moudel['isShow'] != 1)
                {
                    $this->_urlPowerArr[] = $_moudel->path;
                    continue;
                }
                $_arr[$v['menuId']]['menu'] = $_menu;
                $_arr[$v['menuId']]['moudel'][] = $_moudel;
                $this->_urlPowerArr[] = $_moudel['path'];
            }
        }

        $this->_urlPowerArr = array_map(function ($v) {
            return trim(str_replace('\\', '/', $v), "/");
        }, array_flip(array_flip(array_filter($this->_urlPowerArr))));
        //echo '<pre>';var_dump($this->_urlPowerArr,$_arr);exit;
        return $_arr;
    }

    /**
     * url权限验证
     */
    private function _checkPower()
    {
        $_currentUrl = $this->dispatcher->getControllerName();
        if(!in_array($_currentUrl, $this->_urlPowerArr))
        {
            return $this->response->redirect('/error/noaccess', true);
        }
    }

    /**
     * @param type $file
     * @param type $data
     * @param type $layout
     * @param type $print
     * @return type
     */
    public function show($file = null, $data = null, $layout = null)
    {
        if(!is_null($file))
        {
            // 自定义提示
            if($file === 'JSON')
            {
                $data = is_null($data) ? $this->_json : $data;
                echo json_encode($data);
                $this->setRender(0);
                die();
            }
            // 一般错误提示
            if($file === 'ERROR')
            {
                $this->_json['status'] = 1;
                $this->_json['info'] = $data ? $data : ($this->_json['info'] ? $this->_json['info'] : "操作失败");
                echo json_encode($this->_json);
                $this->setRender(0);
                die();
            }
            // 权限错误提示
            if($file === 'QX')
            {
                $this->_json['status'] = 1;
                $this->_json['info'] = $data['info'] ? $data['info'] : ($this->_json['info'] ? $this->_json['info'] : "对不起,权限错误!");
                echo json_encode($this->_json);
                $this->setRender(0);
                die();
            }
            // 自定义视图body文件
            $this->view->pick($this->dispatcher->getControllerName() . "/" . $file);
        }
        $this->default_assign();
        if(!is_null($data) && is_array($data))
        {
            $this->view->setVars($data);
        }

        // 自定义视图layout文件
        if($layout === false)
        {
            return;
        } else if(!is_null($layout))
        {
            $this->view->setTemplateAfter($layout);
        } else
        {
            $this->view->setTemplateAfter('layout');
        }

        return;
    }

    public function showSingle($file = null, $data = null)
    {
        if(!is_null($data) && is_array($data))
        {
            $this->view->setVars($data);
        }
        if(!is_null($file))
        {
            $this->view->pick($this->dispatcher->getControllerName() . "/" . $file);
        }
        return;
    }

    /**
     * 无阻塞响应客户端
     * @param type $isAjax
     */
    public function over($isAjax = false)
    {
        if(function_exists("fastcgi_finish_request"))
        {
            if($isAjax)
            {
                echo json_encode($this->_json);
            }
            fastcgi_finish_request();
        }
    }

    /**
     * 全局默认赋值
     */
    private function default_assign()
    {
        $this->view->setVars(
            [
                "navs" => $this->_menuArr,
                "userinfo" => $this->_userInfo,
                "base_url" => BASE_URL,
                "src_url" => SRC_URL,
                "map_key" => MAPABC_KEY,
        ]);
    }

    /**
     * 获取加密后的密码
     * @param type $userName
     * @return type
     */
    protected function _getPasswordStr($userName)
    {
        $password = md5(md5('$asdf@@#js234_' . $userName . '_asd##(23^'));

        return substr($password, 3, 28);
    }

}
