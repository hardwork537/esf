<?php
/**
 * @abstract  统一入口
 */
header("Content-type:text/html;charset=utf-8");
version_compare(PHP_VERSION, '5.4', '<') and exit('Vaguer requires PHP 5.4 or newer.');
$_display_errors = (isset($_GET['DEBUG']) && intval($_GET['DEBUG'])) ? 'on' : 'off';
error_reporting(0);
ini_set('display_errors', $_display_errors);
ini_set('session.gc_maxlifetime', 3600);
define("DOCROOT", str_replace("public", "", str_replace('\\', '/', realpath(__DIR__))));
define("ROOT", dirname(DOCROOT) . "/");
define("APPROOT", DOCROOT . "app/");
//define("NO_NEED_LOGIN", true);
//define("NO_NEED_POWER", true);

require APPROOT."config/global.config.php";
require DOCROOT . '../config/config.inc.php';

include(APPROOT."bootstrap.php");

