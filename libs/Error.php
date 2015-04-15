<?php
class Error{
    /**
     * 错误信息捕获
     * @param type $website 日志路径
     */
    public static function catchFatalError($website)
    {
        $arrError =  error_get_last();
        if(isset($arrError['type']) && ($arrError['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR |E_USER_ERROR)))
        {
           if(OPEN_DEBUG)
           {
              echo "错误信息: Message: ".$arrError['message']."\t File: ".$arrError['file']."\t Line: ".$arrError['line'];
           }
           isset($arrError['message']) && Log::ErrorWrite($website, '', $_SERVER['REQUEST_URI']."\t Message: ".$arrError['message']."\t File: ".$arrError['file']."\t Line: ".$arrError['line'], 'debug.txt');
           header("http/1.1 502 Bad Gateway"); die;
        }

    }
}

