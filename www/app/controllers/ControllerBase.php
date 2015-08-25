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
    private $_controllerPowerArr = array();
    private $_menuArr = array();
    private $_title = '房易买';
    private $_description = '房易买';

    protected function initialize()
    {
        $this->_page = $this->request->get('page') ? $this->request->get('page') : 1;
        $this->_offset = ($this->_page - 1) * $this->_pagesize;
        
        $userInfo = Cookie::get(LOGIN_KEY);
        if(!empty($userInfo))
        {
            $this->_userInfo = $userInfo;
        }
    }

    /**
     * 检查是否登录,没有登录直接跳到登录页面
     */
    protected function _checkLogin()
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

    protected function _setTitle($title)
    {
        $this->_title = $title;
    }
    
    protected function _setDescription($desc)
    {
        $this->_description = $desc;
    }
    /**
     * @param type $file
     * @param type $data
     * @param type $layout
     * @param type $print
     * @return type
     */
    public function show($file = null, $data = null, $layout = null, $controller = '')
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
            $controllerName = $controller ? $controller : $this->dispatcher->getControllerName();
            $this->view->pick($controllerName . "/" . $file);
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
        $currController = $this->dispatcher->getControllerName();
        $defaultAssign = array(
           "src_url" => SRC_URL,
            "currController" => $currController,
            'headTitle' => $this->_title,
            'headDesc' => $this->_description
        );
        empty($this->_userInfo) || $defaultAssign['userInfo'] = $this->_userInfo;
        
        $this->view->setVars($defaultAssign);
    }

    /**
     * 获取加密后的密码
     * @param type $userName
     * @return type
     */
    protected function _getPasswordStr($userName)
    {
        $password = md5(md5('$asdfs234_' . $userName . '_asd##(23^'));

        return substr($password, 3, 28);
    }

}
