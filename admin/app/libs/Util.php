<?php

/**
 * @abstract  全局工具类
 * @copyright Sohu-inc Team.
 * @author    Rady (yifengcao@sohu-inc.com)
 * @date      2014-09-25 14:17:58
 * @version   admin 1.0
 */
class Util
{
    /**
     * 创建目录
     */
    public static function mkpath($path, $mode = 0777)
    {
        umask(0);
        $path  = str_replace("\\", "|", $path);
        $path  = str_replace("/", "|", $path);
        $path  = str_replace("||", "|", $path);
        $dirs  = explode("|", $path);
        $path  = $dirs[0];
        $is_ok = true;
        for($i = 1; $i < count($dirs); $i++)
        {
            $path .= "/".$dirs[$i];
            if(@!is_dir($path))
            {
                if(@!mkdir($path, $mode))
                {
                    $is_ok = false;
                }
            }
        }
        if($is_ok)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 格式化JSON字符串
     * @param Mixed $value
     * @return String
     */
    public static function prettyJsonEncode($value)
    {
        $tab          = "  ";
        $new_json     = "";
        $indent_level = 0;
        $in_string    = false;

        $json = json_encode($value);
        $len  = strlen($json);

        for($c = 0; $c < $len; $c++)
        {
            $char = $json[$c];
            switch($char)
            {
                case '{' :
                case '[' :
                    if(!$in_string)
                    {
                        $new_json .= $char."\n".str_repeat($tab, $indent_level + 1);
                        $indent_level++;
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case '}' :
                case ']' :
                    if(!$in_string)
                    {
                        $indent_level--;
                        $new_json .= "\n".str_repeat($tab, $indent_level).$char;
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case ',' :
                    if(!$in_string)
                    {
                        $new_json .= ",\n".str_repeat($tab, $indent_level);
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case ':' :
                    if(!$in_string)
                    {
                        $new_json .= ": ";
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case '"' :
                    if($c > 0 && $json[$c - 1] != '\\')
                    {
                        $in_string = !$in_string;
                    }
                default :
                    $new_json .= $char;
                    break;
            }
        }

        return $new_json;
    }

    /**
     * 编码转换 GBK 转 UTF8
     * @param mix(string array) $str
     * @return mix
     */
    public static function gbkToutf8($str)
    {
        if(is_array($str) && $str)
        {
            foreach($str as $_k => $_v)
            {
                if(is_array($_v))
                {
                    $str[$_k] = self::gbkToutf8($_v);
                }
                else
                {
                    $str[$_k] = @iconv('GB18030', 'UTF-8', $_v);
                }
            }
        }
        else
        {
            $str = @iconv('GB18030', 'UTF-8', $str);
        }
        return $str;
    }

    /**
     * 编码转换 UTF8 转  GBK
     * @param mix(string array) $str
     * @return mix
     */
    public static function utf8Togbk($str)
    {
        if(is_array($str) && $str)
        {
            foreach($str as $_k => $_v)
            {
                if(is_array($_v))
                {
                    $str[$_k] = self::utf8Togbk($_v);
                }
                else
                {
                    $str[$_k] = @iconv('UTF-8', 'GB18030', $_v);
                }
            }
        }
        else
        {
            $str = @iconv('UTF-8', 'GB18030', $str);
        }
        return $str;
    }


    /**
     * 模仿JS的alert功能，尽量不要使用php的header进行跳转     原alert
     * @param string $msg 抛出信息
     * @param string $location 跳转地址
     */
    public static function ShowMessage($msg = '', $location = '')
    {
        @header('Content-Type: text/html; charset=utf-8');
        $location = trim($location);
        if(!empty($msg) && !empty($location))
        {
            if($location == "back")
            {
                echo "<script type=\"text/javascript\">alert(\"{$msg}\");parent.history.go(-1);</script>";
            }
            else
            {
                echo "<script type=\"text/javascript\">alert(\"{$msg}\");parent.location.href=\"{$location}\"</script>";
            }
            exit;
        }

        if(empty($msg) && !empty($location))
        {
            if($location == "back")
            {
                echo "<script type=\"text/javascript\">parent.history.go(-1);</script>";
            }
            else
            {
                echo "<script type=\"text/javascript\">parent.location.href=\"{$location}\"</script>";
            }
            exit;
        }

        if(!empty($msg) && empty($location))
        {
            echo "<script type=\"text/javascript\">alert(\"{$msg}\")</script>";
            exit;
        }
        exit;
    }


    /**
     * 执行JS函数
     * @param string $strFunction 要执行的js
     */
    public static function execJs($strFunction = '')
    {
        @header('Content-Type: text/html; charset=gbk');
        if(!empty($strFunction))
        {
            echo "<script type=\"text/javascript\">".$strFunction."</script>";
            exit;
        }
        exit;
    }


    /**
     * 遍历去除数组中元素的前后空格
     * @param array $data 原始数据
     * @return array 处理后数据
     */
    public static function trim($data = array())
    {
        if(is_array($data) && !empty($data))
        {
            foreach($data as $k => $v)
            {
                $data[$k] = trim($v);
            }
        }
        return $data;
    }

    /**
     * 过滤字符
     * @param string $subject
     * @param int $enter $enter=0 为默认允许回车换行符,1为不允许
     * @param string $allowable_tags 允许保留的标签,如'<p><div>'
     * @return string $subject
     */
    public static function filterSubject($subject, $allowable_tags = false)
    {
        if(is_array($subject))
        {
            foreach($subject as $k => $v)
            {
                $subject[$k] = Util::filterSubject($subject[$k], $allowable_tags = false);
            }
        }
        //去除空格
        $subject = trim($subject);
        //过滤script
        $pattern = array( '/<script.*\/script>/ism', '/<script[^>]*>/ism', '/<\/script>/ism' );
        $subject = preg_replace($pattern, '', $subject);
        //过滤onclick
        $subject = preg_replace('/onclick\s?=[\'"]?[^\s>]*[>\s]?/ism', ' ', $subject);
        //过滤HTML
        if($allowable_tags)
        {
            $subject = strip_tags($subject);
        }
        return $subject;
    }

    /**
     * 获得用户IP地址
     * @return string $user_ip
     */
    public static function GetUserIP()
    {
        if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else
        {
            $user_ip = $_SERVER["REMOTE_ADDR"];
        }
        return $user_ip;
    }

    /**
     * 获取用户的浏览器信息
     * @return 浏览器类型
     */
    public static function GetUserExplorer()
    {
        $os = $_SERVER['HTTP_USER_AGENT']; // 浏览者操作系统及浏览器
        if(strpos($os, 'NetCaptor'))
        {
            $explorer = 'NetCaptor';
        }
        elseif(strpos($os, 'Opera'))
        {
            $explorer = 'Opera';
        }
        elseif(strpos($os, 'Firefox'))
        {
            $explorer = 'Firefox';
        }
        elseif(strpos($os, 'MSIE 9'))
        {
            $explorer = 'MSIE 9.x';
        }
        elseif(strpos($os, 'MSIE 8'))
        {
            $explorer = 'MSIE 8.x';
        }
        elseif(strpos($os, 'MSIE 7'))
        {
            $explorer = 'MSIE 7.x';
        }
        elseif(strpos($os, 'MSIE 6'))
        {
            $explorer = 'MSIE 6.x';
        }
        elseif(strpos($os, 'MSIE 5'))
        {
            $explorer = 'MSIE 5.x';
        }
        elseif(strpos($os, 'MSIE 4'))
        {
            $explorer = 'MSIE 4.x';
        }
        elseif(strpos($os, 'Netscape'))
        {
            $explorer = 'Netscape';
        }
        else
        {
            $explorer = 'Other';
        }
        return $explorer;
    }

    /**
     * 获取用户的操作系统
     * @return 操作系统类型
     */
    public static function GetUserOs()
    {
        $os = $_SERVER['HTTP_USER_AGENT']; // 浏览者操作系统及浏览器
        // 分析操作系统
        if(strpos($os, 'Windows NT 5.0'))
        {
            $os = 'Windows 2000';
        }
        elseif(strpos($os, 'Windows NT 5.1'))
        {
            $os = 'Windows XP';
        }
        elseif(strpos($os, 'Windows NT 5.2'))
        {
            $os = 'Windows 2003';
        }
        elseif(strpos($os, 'Windows NT'))
        {
            $os = 'Windows NT';
        }
        elseif(strpos($os, 'Windows 9'))
        {
            $os = 'Windows 98';
        }
        elseif(strpos($os, 'unix'))
        {
            $os = 'Unix';
        }
        elseif(strpos($os, 'linux'))
        {
            $os = 'Linux';
        }
        elseif(strpos($os, 'SunOS'))
        {
            $os = 'SunOS';
        }
        elseif(strpos($os, 'BSD'))
        {
            $os = 'FreeBSD';
        }
        elseif(strpos($os, 'Mac'))
        {
            $os = 'Mac';
        }
        else
        {
            $os = 'Other';
        }
        return $os;
    }

    /**
     * 检测公司名称是否为2~20个字母或数字
     * @param unknown_type $str
     */
    public static function IsName($str)
    {
        $str     = trim($str);
        $pattern = '/^\w{2,20}$/';
        if(preg_match($pattern, $str))
        {
            return true;
        }
        return false;
    }

    /**
     * 是否是有效邮箱
     * @param string $str
     * @return bool
     */
    public static function IsEmail($str)
    {
        $flag    = false;
        $str     = trim($str);
        $pattern = '/^(\d|[a-zA-Z])+(-|\.|\w)*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/';
        if(preg_match($pattern, $str))
        {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 是否是有效QQ
     * @param string $str
     * @return bool
     */
    public static function IsQQ($str)
    {
        $flag    = false;
        $str     = trim($str);
        $pattern = '/^[1-9][0-9]{4,12}$/';
        if(preg_match($pattern, $str))
        {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 是否是有效密码
     * @param string $str
     * @return bool
     */
    public static function IsPassword($str)
    {
        $flag    = false;
        $str     = trim($str);
        $pattern = '/^[\w]{6,16}$/';
        if(preg_match($pattern, $str))
        {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 是否是有效邮编
     * @param string $str
     * @return bool
     */
    public static function IsPostalCode($str)
    {
        $flag    = false;
        $str     = trim($str);
        $pattern = '/(^[0-9]{6}$)/';
        if(preg_match($pattern, $str))
        {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 是否是有效电话
     * @param string $str
     * @return bool
     */
    public static function IsPhone($str)
    {
        $flag    = false;
        $str     = trim($str);
        $pattern = '/(^[0-9]{3,4}\-[0-9]{3,8}$)|(^[0-9]{3,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)/';
        if(preg_match($pattern, $str))
        {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 是否是有效手机号
     * @param string $str
     * @return bool
     */
    public static function IsMobile($str)
    {
        $flag    = false;
        $str     = trim($str);
        $pattern = '/(^0{0,1}1[0-9]{10}$)/';
        if(preg_match($pattern, $str))
        {
            $flag = true;
        }
        return $flag;
    }

    /**
     * 是否是有效帐户 true 表示合法
     * 帐号输入不合法，长度为5-16位，请以字母、数字、下划线来命名!
     */
    public static function IsAccount($str)
    {
        $str     = trim($str);
        $pattern = '/^\w{5,16}$/';
        if(preg_match($pattern, $str))
        {
            return true;
        }
        return false;
    }

    /**
     * 二个密码对比
     * @param 原密码 $strPasswd
     * @param 二次确认密码 $strRePasswd
     * @return boolean
     */
    public static function IsPasswdCmp($strPasswd, $strRePasswd)
    {
        if(!strcmp($strPasswd, $strRePasswd))
        {
            return true;
        }
        return false;
    }

    //设置计时开始函数
    public static function TimeStart()
    {
        global $StartTime;
        $StartTime = microtime(true);
        echo "开始计时\n";
    }

    //设置计时结束函数
    public static function TimeEnd()
    {
        global $StartTime;
        $EndTime = microtime(true);
        $UseTime = $EndTime - $StartTime;
        echo "计时终止，共耗时 $UseTime 秒 [".intval($UseTime/3600)."时".intval($UseTime%3600/60)."分".intval($UseTime%3600%60%60)."秒]\n";
        unset($StartTime, $EndTime, $UseTime);
    }

    /**
     * 转义字符串
     * @param str $str 需要转义的字符串
     * @return str 转义后的字符串
     */
    public static function escStr($str)
    {
        return htmlspecialchars($str);
    }

    /**
     * 转义数组中的每个字符串
     * @param str $str 需要转义的字符串
     * @return str 转义后的字符串
     */
    public static function escArr($data = array())
    {
        if(empty($data) || !is_array($data))
        {
            return $data;
        }
        $arrBack = array();
        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                if(is_array($v))
                {
                    $arrBack[$k][] = self::escArr($v);
                }
                else
                {
                    $arrBack[$k] = self::escStr($v);
                }
            }
        }
        return $arrBack;
    }

    /**
     * 获取经纬度算法
     * @param int $lat1 经度开始坐标
     * @param int $lng1 纬度开绐坐标
     * @param int $lat2 经度结束坐标
     * @param int $lng2 纬度结束坐标
     */
    public static function GetDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radlat1 = self::rad($lat1);
        $radlat2 = self::rad($lat2);
        $a       = $radlat1 - $radlat2; //经度差值
        $b       = self::rad($lng1) - self::rad($lng2); //纬度差值
        $temp    = sqrt(pow(sin($a/2), 2) + cos($radlat1)*cos($radlat2)*pow(sin($b/2), 2));
        $s       = 2*atan($temp/sqrt(-$temp*$temp + 1));
        $s       = $s*self::EARTH_RADIUS;
        return $s;
    }

    public static function rad($double)
    {
        return $double*pi()/180;
    }

    //截取utf8字符串
    public static function utf8Substr($str, $from, $len)
    {
        return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
            '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s', '$1', $str);
    }

    /**
     * 取得sohu的passport信息
     * @return array $passport
     */
    public static function getSohuPassportInfo()
    {
        $passport        = array();
        $headers         = apache_request_headers();
        $passport_userid = isset($headers['X-SohuPassport-UserId']) ? $headers['X-SohuPassport-UserId'] : '';
        if(!preg_match("/(.+)@(.+)$/", $passport_userid, $passport_info))
        {
            return null;
        }

        //转换编码为gbk
        $passport["userid"]   = iconv("UTF-8", "GBK", $passport_userid);
        $passport["username"] = iconv("UTF-8", "GBK", $passport_info[1]);
        $passport["domain"]   = trim($passport_info[2]);

        return $passport;
    }

    /**
     * 显示404错误页面
     * @return void
     */
    public static function die404()
    {
        echo '<meta http-equiv="refresh" content="0;url=/404.html">';
        exit;
    }


    /**
     * @abstract 创建CSV文件
     * @param 文件名称 $file
     * @param 文件内容 $msg
     */
    public function creatCsv($file, $msg)
    {
        if(empty($file))
        {
            return false;
        }
        self::createFolder(dirname($file));
        if(!$handle = fopen($file, 'w'))
        {
            return false;
        }
        if(fwrite($handle, $msg) === FALSE)
        {
            return false;
        }
        fclose($handle);
        unset($file, $msg);
    }

    /**
     * 判断并转换字符编码，需 mb_string 模块支持。
     * @param mixed $str 数据
     * @param string $encoding 要转换成的编码类型
     * @return mixed 转换过的数据
     */
    public static function encodingConvert($str, $encoding = 'UTF-8')
    {
        if(is_array($str))
        {
            $arr = array();
            foreach($str as $key => $val)
            {
                $arr[$key] = self::encodingConvert($val, $encoding);
            }

            return $arr;
        }

        $_encoding = mb_detect_encoding($str, array( 'ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5' ));
        if($_encoding == $encoding)
        {
            return $str;
        }

        return mb_convert_encoding($str, $encoding, $_encoding);
    }

    /**
     * Excel 导出头部输出
     * @param type $filename
     */
    public static function excelHead($filename)
    {
        if(!$filename)
        {
            $filename = date("YmdHis");
        }
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=".$filename.".xls");
    }

}
