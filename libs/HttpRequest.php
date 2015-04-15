<?php
/**
 * CURL HTTP请求封装
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/8/11
 */

class HttpRequest {
    public $url = null;
    public $parameters = array();
    public $headers = null;
    public $cookie = null;
    public $body = null;
    public $followRedirect = true;
    public $maxRedirect = 3;
    public $numRedirect = 0;
    public $timeout = 30;
    public $curlOpts = array();

    function post() {
        return HttpClient::post($this);
    }

    function get() {
        return HttpClient::get($this);
    }
}
?>