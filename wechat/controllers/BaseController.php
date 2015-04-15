<?php
	class BaseController extends Phalcon\Mvc\Controller{
		//protected $access;
		protected $_renderLevel;

		public function display($controllerName,$actionName='index', $params=array()){
            if ($params && is_array($params)){
                $this->view->setVars($params);
            }
            $this->view->setVar('HttpStaticUrl',_API_DOMAIN_);  //页面静态ajaxurl

            if (!$this->_renderLevel){
                $this->setRenderLevel();
            }
            $this->view->setRenderLevel($this->_renderLevel);
            $this->view->start();
            $this->view->render($controllerName, $actionName);
            $this->view->finish();
		}

		public function beforeExecuteRoute($dispatcher)
		{
		        // Executed before every found action
		}

	    public function afterExecuteRoute($dispatcher)
		{
		        // Executed after every found action
		}
		
		public function forward($array=array(),$params=array()){
			if (!$array || !$params) return false;
            $this->dispatcher->setParams($params);
			$this->dispatcher->forward($array);
		}

        public function assign($params){
            $this->view->setVars($params);
        }
	
		public function setRenderLevel($level=Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT){
			$this->_renderLevel = $level;
		}

        /*
         *重载页面模板
         */
        public  function pick($controllerName,$actionName){
            $this->view->setVar('HttpStaticUrl',_API_DOMAIN_);  //页面静态ajaxurl
            $this->view->pick($controllerName.'/'.$actionName);
        }

        /*
         *跳转页面
         */
        public  function renderurl($controllerName,$actionName){
            $this->view->render($controllerName,$actionName);
        }


	}
