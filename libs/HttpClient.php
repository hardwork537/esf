<?php
/**
 * CURL HTTP封装
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/8/11
 */

class HttpClient {
     public static function get($httpRequest){
        $url = HttpClient::getURL($httpRequest);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($httpRequest->cookie != null)
            curl_setopt($ch, CURLOPT_COOKIE,  HttpClient::getCookies($httpRequest));

        curl_setopt($ch, CURLOPT_TIMEOUT, $httpRequest->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $httpRequest->timeout);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        if($httpRequest->headers != null)
            curl_setopt($ch, CURLOPT_HTTPHEADER,  HttpClient::getHeaders($httpRequest));

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
        HttpClient::setExtraCurlOptions($httpRequest, $ch);

        $response = curl_exec($ch);
        $error_no =  curl_errno($ch);
        $error_mess = curl_error($ch);
        curl_close($ch);

        if(!empty($error_no)){
            $httpResponse = new HttpResponse();
            $httpResponse->error_no = $error_no;
            $httpResponse->error_mess = $error_mess;
        }else{
            $httpResponse = HttpClient::parseResponse($response);
        }
        if ( $httpRequest->followRedirect === true && $httpRequest->numRedirect < $httpRequest->maxRedirect) {
        	//判断页面是否进行重定向
            if ( array_key_exists('location',$httpResponse->headers) ) {
                $httpRequest->url = $httpResponse->headers['location'];
                $httpRequest->parameters = null;
                $httpRequest->numRedirect++;
                $httpResponse = HttpClient::get($httpRequest);
            } elseif ( array_key_exists('Location',$httpResponse->headers) ) {
                $httpRequest->url = $httpResponse->headers['Location'];
                $httpRequest->parameters = null;
                $httpRequest->numRedirect++;
                $httpResponse = HttpClient::get($httpRequest);
            }
        }

        $httpResponse->request = $httpRequest;
        return $httpResponse;
    }

    public static function post($httpRequest){
        $url = $httpRequest->url;

        if ( $httpRequest->body != null )
            $body =& $httpRequest->body;
        else
            $body = HttpClient::buildQuery($httpRequest->parameters);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($httpRequest->cookies != null)
            curl_setopt($ch, CURLOPT_COOKIE,  HttpClient::getCookies($httpRequest));
        if ( $body !== null )
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_TIMEOUT, $httpRequest->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $httpRequest->timeout);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        if($httpRequest->headers != null)
            curl_setopt($ch, CURLOPT_HTTPHEADER,  HttpClient::getHeaders($httpRequest));

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
        HttpClient::setExtraCurlOptions($httpRequest, $ch);
        $response = curl_exec($ch);

        $error_no =  curl_errno($ch);
        $error_mess = curl_error($ch);
        curl_close($ch);

        if(!empty($error_no)){
            $httpResponse = new HttpResponse();
            $httpResponse->error_no = $error_no;
            $httpResponse->error_mess = $error_mess;
        }else{
            $httpResponse = HttpClient::parseResponse($response);
        }

        $httpResponse->request = &$httpRequest;
        return $httpResponse;
    }

    /**
     * 构造查询串
     */

    public static function buildQuery($arr){
        if ( $arr == null )
            return false;

        $url = '';
        $init = false;
        foreach($arr as $key=>$value)
        {
            if ( is_array($value) )
            {
                foreach($value as $val)
                    $url .= urlencode($key).'='.urlencode($val).'&';
            }
            else
            {
                $url .= urlencode($key).'='.urlencode($value).'&';
            }
        }
        return $url;
    }
    /**
     * 拼接url
     */
    public static function getURL(&$httpRequest){
        $query = HttpClient::buildQuery($httpRequest->parameters);
        if ( $query == false )
            return $httpRequest->url;
        if ( strpos($httpRequest->url,'?')==false )
            return $httpRequest->url.'?'.$query;
        else
            return $httpRequest->url.'&'.$query;
    }

    function & getHeaders(&$httpRequest)
    {
        $headers = array();
        foreach($httpRequest->headers as $key=>$val)
        {

            if ( is_string($key) )
                $headers[] = $key.': '.$val;
            else
                $headers[] = $val;
        }
        return $headers;
    }
    function  & getCookies(&$httpRequest)
    {
        if(is_string($httpRequest->cookies ))
            return $httpRequest->cookies;
        $cookies = "";
        foreach($httpRequest->cookies as $key=>$val)
        {
            if (strpos($val,'=') === false)
                $cookies .= ';'.$key.'='.$val;
            else
                $cookies .= ";$val";

        }
        return $cookies;
    }

    public static function setExtraCurlOptions(&$httpRequest, &$ch){
        if (! is_array($httpRequest->curlOpts) )
            return;

        foreach ($httpRequest->curlOpts as $key=>$value)
        {
            curl_setopt($ch, $key, $value);
        }
    }

     public static function parseResponse(&$response){
        $httpResponse = new HttpResponse();

        $parts = preg_split('/\r\n\r\n/',$response,2);
        $nparts = count($parts);
        $headerLines = $nparts>0 ? $parts[0] : null;
        $contentLines = $nparts>1 ? $parts[1] : null;
        while ( preg_match('/^HTTP/',$contentLines) )
        {
            $parts = preg_split('/\r\n\r\n/',$contentLines,2);
            $nparts = count($parts);
            $headerLines = $nparts>0 ? $parts[0] : null;
            $contentLines = $nparts>1 ? $parts[1] : null;
        }
        $httpResponse->body =& $contentLines;
        $httpResponse->headers = array();

        $lines = explode("\r\n",$headerLines);
        if($lines)
        {
            foreach($lines as $line)
            {
                $parts = array();
                if( preg_match('/^([a-zA-Z -]+): +(.*)$/',$line,$parts) )
                {
                    if(isset($httpResponse->headers[$parts[1]]))
                    {
                        if(is_array($httpResponse->headers[$parts[1]]))
                        {
                            $httpResponse->headers[$parts[1]][] = $parts[2];
                        } else
                        {
                            $preExisting = $httpResponse->headers[$parts[1]];
                            $httpResponse->headers[$parts[1]]= array($preExisting,$parts[2]);
                        }
                    } else
                    {
                        $httpResponse->headers[$parts[1]]=$parts[2];
                    }

                }
                else if ( preg_match('/^HTTP/',$line) )
                {
                    $parts = preg_split('/\s+/',$line,3);
                    $nparts = count($parts);
                    if ( $nparts > 0 )
                        $httpResponse->version = $parts[0];
                    if ( $nparts > 1 )
                        $httpResponse->statusCode = $parts[1];
                    if ( $nparts > 2 )
                        $httpResponse->statusMessage = $parts[2];
                }
            }
        }
        return $httpResponse;
    }
}