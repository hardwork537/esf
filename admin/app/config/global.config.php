<?php
define("SRC_URL", "http://src.esf.itc.cn/admin/");
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


