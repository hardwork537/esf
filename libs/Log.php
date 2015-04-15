<?php

/**
 * @全站错误日志记录方法调用
 */
//日志根目录
define('LOG_PATH', __DIR__ . "/../log/");

class Log {

    /**
     * 日志写入函数，根据项目名称写入相应日志文件
     *
     * @param $project 项目名称
     * @param $page 文件名  
     * @param $message 日志信息
     * @param $file_name 指定的文件名
     */
    public static function LogWrite($page, $message, $file_name) {
        //$WRITE_LOG为日志记录开关，config.inc.php中定义，默认为true
        if (!$GLOBALS['_WRITE_LOG']) {
            exit();
        }
        if (!file_exists($file_name)) {
            $handle = fopen($file_name, 'w');
            if (!$handle) {
                // exit ( "create file fail" );
                return;
            } else {
                chmod($file_name, 0777);
            }
            fclose($handle);
        }

        $LogMess = date("Y-m-d H:i:s") . "\t";

        $LogMess .= $page . "\t";   //日志错误页面		
        $LogMess .= $message . "\r\n";
        //$LogMess .= FSUtility::GetUserIP()."\r\n";	 //定义错误服务器	
        file_put_contents($file_name, $LogMess, FILE_APPEND);
    }

    /**
     * 日志写入函数，根据项目名称写入相应日志文件
     *
     * @param $project 项目名称
     * @param $page 文件名  
     * @param $message 日志信息
     * @param $file_name 指定的文件名
     */
    public static function ErrorWrite($project, $page, $message, $filename = '') {
        //$WRITE_LOG为日志记录开关，config.inc.php中定义，默认为true
        if (!$GLOBALS['_WRITE_ERROR_LOG']) {
            return;
        }

        $filename = self::LogPath($project) . self::LogFile($filename, 'error');

        self::LogWrite($page, $message, $filename);
    }

    public static function SuccessWrite($project, $page, $message, $filename = '') {
        //$WRITE_LOG为日志记录开关，config.inc.php中定义，默认为true
        if (!$GLOBALS['_WRITE_SUCCESS_LOG']) {
            return;
        }

        $filename = self::LogPath($project) . self::LogFile($filename, 'success');
        
        self::LogWrite($page, $message, $filename);
    }

    /**
     * 日志保存路径，按项目名分
     *
     * @param string $project 项目名称vip/www/admin
     * @return string $file_path 文件夹路径
     */
    private static function LogPath($project) {
        
        $file_path = LOG_PATH;
        switch ($project) {
            case 'admin':
                $file_path .= 'admin/';
                break;
            case 'weixin':
                $file_path .= 'weixin/';
                break;
            case 'crondtab':
                $file_path .= 'crondtab/';
                break;
            default:
                $file_path .= 'unknow/';
                break;
        }

        return $file_path;
    }

    /**
     * 保存日志的文件名，按错误类型/时间添加前缀
     *
     * @param string $file_name 自定义文件名
     * @param string  $type 日志类型 error/success
     * @return string $file_name 文件名
     */
    private static function LogFile($file_name, $type) {
        if ($type == 'error') {
            $pre = 'err_';
        }

        if ($type == 'success') {
            $pre = 'suc_';
        }

        if ($file_name == '') {
            $file_name = $pre . date("YmdH") . "_log.txt";
        } else {
            $file_name = $pre . date("YmdH") . "_" . $file_name;
        }

        return $file_name;
    }

}

?>