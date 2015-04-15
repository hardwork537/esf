<?php

/**
 * @abstract  Cookie
 * @copyright Sohu-inc Team.
 * @author    Rady (yifengcao@sohu-inc.com)
 * @date      2014-10-22 16:10:06
 * @version   admin 1.0
 */
class Cookie
{
    /**
     * 获取Cookie
     * @param  string $key
     * @return mix
     */
    public static function get($key)
    {
        $arrBackData = array();
        if(isset($_COOKIE[$key]))
        {
            $strCookie   = $_COOKIE[$key];
            $strCookie   = base64_decode(substr($strCookie, 27));
            $arrBackData = unserialize($strCookie);
            return $arrBackData;
        }
        else
        {
            return '';
        }
    }

    /**
     * 设置Cookie
     * @param integer $expiration 过期时间 86400为一天
     * @param string $path Cookie路径
     * @param string $domain Cookie域
     * @return  bool
     */
    public static function set($name, $value, $expiration = 0, $path = "/", $domain = ".focus.cn")
    {
        $value = substr(md5(COOKIE_KEY), 5).base64_encode(serialize($value));
        $expiration !== 0 && $expiration += time();

        return setcookie($name, $value, $expiration, $path, $domain, 0);
    }

    /**
     * 删除Cookie
     * @param  string $name
     * @return bool
     */
    public static function delete($name)
    {
        self::set($name, null, -86400);
        unset($_COOKIE[$name]);
    }

    final private function __construct()
    {

    }

} // End cookie
