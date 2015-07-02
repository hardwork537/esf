<?php
define("SRC_URL", "http://src.taoyun.com/www/");
define("BASE_URL", "/");
define("MAPABC_KEY", "4706805b4dac4acdeddafabe13079a4d8099793ef5ce0f49b2f9153d82a4594af912c23f25f0cfee"); 

if($_GET['debug'])
{
    define("OPEN_DEBUG", true);
}
else
{
    define("OPEN_DEBUG", false);
}

//超时自动登出
define('LOGIN_LIFETIME',86400);

define("HEAD_CITY_NAME", "全国");

define("HEAD_CITY", 999);
define("PICTURE_PRODUCT_NAME", 'esf');

//登陆 cookie KEY
define("LOGIN_KEY", 'www_esf_loginInfo');

//域名信息
define("_COOKIE_BASE_DOMAIN", '.taoyun.com');
//默认密码
$GLOBALS['defaultPwd'] = '123456';

