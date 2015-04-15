<?php

/**
 * @abstract  控制器系统核心类
 */
class ControllerCore extends Phalcon\Mvc\Controller
{
    protected function initialize()
    {

    }

    /**
     * 页面跳转
     * @param string $uri
     * @return type
     */
    protected function forward($uri)
    {
        if(is_string($uri))
        {
            $uriParts = explode('/', str_replace("\\", "/", $uri));
        }
        return $this->dispatcher->forward([ 'controller' => $uriParts[0], 'action' => $uriParts[1] ]);
    }


    /**
     * 设置渲染级别
     * @param type $level
     *              0:关闭渲染
     *              1:只渲染对应的action文件
     */
    public function setRender($level = 0)
    {
        $this->view->setRenderLevel(intval($level));
    }


    protected function _debug()
    {
        xdebug_print_function_stack("<br/>stop here!");
        die();
    }

    /**
     * 获取当前控制器名称
     * @return type

    public function getControllerName(){
     * return $this->dispatcher->getControllerName();
     * }
     */


    public function beforeExecuteRoute($dispatcher)
    {
        // This is executed before every found action
        //echo "beforeExecuteRoute";
        //echo "<br>";
        if($dispatcher->getActionName() == 'save')
        {
            $this->flash->error("You don't have permission to save posts");
            return false;
        }
    }

    public function afterExecuteRoute($dispatcher)
    {
        // echo "afterExecuteRoute";
        //echo "<br>";

        //$a = get_class_methods('Phalcon\Mvc\Dispatcher');
        //print_r($a);die();
        /*
        foreach($a as $v){
            if(strpos($v,"get")!==false){
               echo "<br>".$v.":". $this->dispatcher->{$v}();
            }
        }

         */
        ob_flush();
        if(OPEN_DEBUG)
        {
            ob_flush();
        }
    }

}
