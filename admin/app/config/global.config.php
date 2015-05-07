<?php
define("SRC_URL", "http://src.taoyun.com/admin/");
define("BASE_URL", "/");
define("SUBJECT_URL", "http://upload.zhuanti.esf.focus.cn");
    

if($_GET['debug'])
{
    define("OPEN_DEBUG", true);
}
else
{
    define("OPEN_DEBUG", false);
}

//超时自动登出
define('LOGIN_LIFETIME',3600);

define("HEAD_CITY_NAME", "全国");

define("HEAD_CITY", 999);

//登陆 cookie KEY
define("LOGIN_KEY", 'admin_esf_loginInfo');

//域名信息
define("_COOKIE_BASE_DOMAIN", '.taoyun.com');

