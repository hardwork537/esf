<?php

/**
 * @abstract 伪造Header进行数据抓取，具体参数开启看PHP文档
 */
class Curl
{
    
    public static function GetResult($url, $method = 'post', $postfield = array(), $header = array(), $timeout = 30, $format = 0, $host = '', $proxy = '')
    {
        $proxy = trim($proxy);
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        $ch = curl_init(); // 初始化CURL句柄
        if(!empty($proxy))
        {
            curl_setopt($ch, CURLOPT_PROXY, $proxy); //设置代理服务器
        }
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    //https时，忽略验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    //https时，忽略验证
        if('post' === $method)
        {
            curl_setopt($ch, CURLOPT_POST, 1); //启用POST提交
            $postfield = http_build_query($postfield);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield); //设置POST提交的字符串
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 超时时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent); //HTTP请求User-Agent:头

        $http_header = array(
            'Accept-Language: zh-cn',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache'
        );
        if($host != false)
        {
            $http_header[] = 'Host:' . $host;
        }
        if(!empty($header) && is_array($header))
        {
            foreach($header as $key=>$value)
            {
                $http_header[] = $key. ':' . $value;
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header); //设置HTTP头信息
        $data = curl_exec($ch); //执行预定义的CURL
        $info = curl_getinfo($ch); //得到返回信息的特性
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);
        
        curl_close($ch);
        if(0 == $format)
        {
            return $data;
        } elseif(1 == $format)
        {
            return array(
                'data' => $data,
                'info' => $info,
                'errorNo' => $errorNo,
                'errorMsg' => '',
                'httpCode' => $httpCode
            );
        }

        if($httpCode != '200')
        {
            return $data;
        } else
        {
            return self::ErrMsg($httpCode);
        }
    }

    public static function GetResultJson($url, $method = 'post', $postfield = '', $proxy = '', $timeout = 30, $format = 0, $host = '')
    {
        $proxy = trim($proxy);
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        $ch = curl_init(); // 初始化CURL句柄
        if(!empty($proxy))
        {
            curl_setopt($ch, CURLOPT_PROXY, $proxy); //设置代理服务器
        }
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    //https时，忽略验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    //https时，忽略验证
        if('post' === $method)
        {
            curl_setopt($ch, CURLOPT_POST, 1); //启用POST提交
            $postfield = is_array($postfield) ? http_build_query($postfield) : $postfield;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield); //设置POST提交的字符串
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 超时时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent); //HTTP请求User-Agent:头

        $http_header = array(
            'Accept-Language: zh-cn',
            'Connection: Keep-Alive',
            'Cache-Control: no-cache',
            //'Content-Type: application/json;charset=UTF-8'
        );
        if($host != false)
        {
            $http_header[] = 'Host:' . $host;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header); //设置HTTP头信息
        $data = curl_exec($ch); //执行预定义的CURL
        $info = curl_getinfo($ch); //得到返回信息的特性
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);

        curl_close($ch);
        if(0 == $format)
        {
            return $data;
        } elseif(1 == $format)
        {
            return array(
                'data' => $data,
                'info' => $info,
                'errorNo' => $errorNo,
                'errorMsg' => '',
                'httpCode' => $httpCode
            );
        }


        if($httpCode != '200')
        {
            return $data;
        } else
        {
            return self::ErrMsg($httpCode);
        }
    }


    public static function ErrMsg($msgcode)
    {
        //[Informational 1xx]
        $httpCode['0'] = 'Unable to access';
        $httpCode['100'] = 'Continue';
        $httpCode['101'] = 'Switching Protocols';

        //[Successful 2xx]
        $httpCode['200'] = 'OK';
        $httpCode['201'] = 'Created';
        $httpCode['202'] = 'Accepted';
        $httpCode['203'] = 'Non-Authoritative Information';
        $httpCode['204'] = 'No Content';
        $httpCode['205'] = 'Reset Content';
        $httpCode['206'] = 'Partial Content';

        //[Redirection 3xx]
        $httpCode['300'] = 'Multiple Choices';
        $httpCode['301'] = 'Moved Permanently';
        $httpCode['302'] = 'Found';
        $httpCode['303'] = 'See Other';
        $httpCode['304'] = 'Not Modified';
        $httpCode['305'] = 'Use Proxy';
        $httpCode['306'] = '(Unused)';
        $httpCode['307'] = 'Temporary Redirect';

        //[Client Error 4xx]
        $httpCode['400'] = 'Bad Request';
        $httpCode['401'] = 'Unauthorized';
        $httpCode['402'] = 'Payment Required';
        $httpCode['403'] = 'Forbidden';
        $httpCode['404'] = 'Not Found';
        $httpCode['405'] = 'Method Not Allowed';
        $httpCode['406'] = 'Not Acceptable';
        $httpCode['407'] = 'Proxy Authentication Required';
        $httpCode['408'] = 'Request Timeout';
        $httpCode['409'] = 'Conflict';
        $httpCode['410'] = 'Gone';
        $httpCode['411'] = 'Length Required';
        $httpCode['412'] = 'Precondition Failed';
        $httpCode['413'] = 'Request Entity Too Large';
        $httpCode['414'] = 'Request-URI Too Long';
        $httpCode['415'] = 'Unsupported Media Type';
        $httpCode['416'] = 'Requested Range Not Satisfiable';
        $httpCode['417'] = 'Expectation Failed';

        //[Server Error 5xx]
        $httpCode['500'] = 'Internal Server Error';
        $httpCode['501'] = 'Not Implemented';
        $httpCode['502'] = 'Bad Gateway';
        $httpCode['503'] = 'Service Unavailable';
        $httpCode['504'] = 'Gateway Timeout';
        $httpCode['505'] = 'HTTP Version Not Supported';

        return $httpCode[$msgcode];
    }

    public static function sendJson($url,$post_data, $timeout=5){
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        if($post_data != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}
