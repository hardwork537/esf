<?php

	class CreatemenuController extends BaseController{

		public function indexAction(){
            global $arrWeixinMenu;

            $result = Api::getInstance()->createMenu($arrWeixinMenu);
            //var_dump($arrWeixinMenu);
            echo Api::getInstance()->getGlobalAccessToken();
            exit;
		}

		public function notfoundAction(){
            echo "notFound";die();
		}

	}
