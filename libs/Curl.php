<?php
/**
 * @abstract 伪造Header进行数据抓取，具体参数开启看PHP文档
 * @author jackchen
 * @date 2014-9-10
 * @LastModified by jackchen
 */
class Curl
{
    public static function SendReq($url, $postfield, $proxy='', $timeout=3, $format=1)
    {
        return self::GetResult($url, $postfield, $proxy, $timeout, $format);
    }

    public static function GetResult($url, $postfield, $proxy='', $timeout=3, $format=0, $host='',$method='post')
    {
        $proxy=trim($proxy);
        $user_agent ='Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        $ch = curl_init(); // 初始化CURL句柄
        if(!empty($proxy)){
            curl_setopt ($ch, CURLOPT_PROXY, $proxy);//设置代理服务器
        }
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        //curl_setopt($ch, CURLOPT_FAILONERROR, 1); // 启用时显示HTTP状态码，默认行为是忽略编号小于等于400的HTTP信息
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//启用时会将服务器服务器返回的“Location:”放在header中递归的返回给服务器
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);// 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        if('post' == $method) {
            curl_setopt($ch, CURLOPT_POST, 1);//启用POST提交
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield); //设置POST提交的字符串
        }       
        //curl_setopt($ch, CURLOPT_PORT, 80); //设置端口
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 超时时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);//HTTP请求User-Agent:头
        //curl_setopt($ch,CURLOPT_HEADER,1);//设为TRUE在输出中包含头信息
        //$fp = fopen('example_homepage.txt', 'w');//输出文件
        //curl_setopt($ch, CURLOPT_FILE, $fp);//设置输出文件的位置，值是一个资源类型，默认为STDOUT (浏览器)。
        $http_header = array(
			'Accept-Language: zh-cn',
			'Connection: Keep-Alive',
			'Cache-Control: no-cache'
		);
        if($host != false){
        	$http_header[] = 'Host:'.$host;
        }
        curl_setopt($ch,CURLOPT_HTTPHEADER,$http_header);//设置HTTP头信息
        $data = curl_exec($ch); //执行预定义的CURL
        $info=curl_getinfo($ch); //得到返回信息的特性
        $httpCode =curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $errorNo = curl_errno($ch);

        curl_close($ch);
        if(0==$format)
        {
            return $data;
        }
        elseif(1==$format)
        {
            return array(
                'data' => $data,
                'info' => $info,
                'errorNo' => $errorNo,
                'errorMsg' => '',
                'httpCode' => $httpCode
            );
        }


        if( $httpCode != '200' )
        {
            return $data;
        }
        else
        {
            return self::ErrMsg($httpCode);
        }

        //return self::ErrMsg($httpCode);
        //print_r($info);
        //if(isset($info['httpCode']) && $info['httpCode']=='405'){
        //echo 'bad proxy {$proxy}\n'; //代理出错
        //exit;
        //}
    }

    public static function ErrMsg($msgcode)
    {
        //[Informational 1xx]
        $httpCode['0']='Unable to access';
        $httpCode['100']='Continue';
        $httpCode['101']='Switching Protocols';

        //[Successful 2xx]
        $httpCode['200']='OK';
        $httpCode['201']='Created';
        $httpCode['202']='Accepted';
        $httpCode['203']='Non-Authoritative Information';
        $httpCode['204']='No Content';
        $httpCode['205']='Reset Content';
        $httpCode['206']='Partial Content';

        //[Redirection 3xx]
        $httpCode['300']='Multiple Choices';
        $httpCode['301']='Moved Permanently';
        $httpCode['302']='Found';
        $httpCode['303']='See Other';
        $httpCode['304']='Not Modified';
        $httpCode['305']='Use Proxy';
        $httpCode['306']='(Unused)';
        $httpCode['307']='Temporary Redirect';

        //[Client Error 4xx]
        $httpCode['400']='Bad Request';
        $httpCode['401']='Unauthorized';
        $httpCode['402']='Payment Required';
        $httpCode['403']='Forbidden';
        $httpCode['404']='Not Found';
        $httpCode['405']='Method Not Allowed';
        $httpCode['406']='Not Acceptable';
        $httpCode['407']='Proxy Authentication Required';
        $httpCode['408']='Request Timeout';
        $httpCode['409']='Conflict';
        $httpCode['410']='Gone';
        $httpCode['411']='Length Required';
        $httpCode['412']='Precondition Failed';
        $httpCode['413']='Request Entity Too Large';
        $httpCode['414']='Request-URI Too Long';
        $httpCode['415']='Unsupported Media Type';
        $httpCode['416']='Requested Range Not Satisfiable';
        $httpCode['417']='Expectation Failed';

        //[Server Error 5xx]
        $httpCode['500']='Internal Server Error';
        $httpCode['501']='Not Implemented';
        $httpCode['502']='Bad Gateway';
        $httpCode['503']='Service Unavailable';
        $httpCode['504']='Gateway Timeout';
        $httpCode['505']='HTTP Version Not Supported';

        return $httpCode[$msgcode];
    }

}
