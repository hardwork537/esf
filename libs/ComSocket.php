<?php
 /**
 * @abstract Socket接口基类
 * @author By jackchen
 * @date 2014-09-01
 */
class ComSocket {

    /*
     * 请求主机，也有用passport.sohu.com
     */
    protected $_host = "passport.sohu.com";

    /*
     * 默认主机端口
     */
    protected $_port = 80;

    /*
     * 默认的超时时间
     */
    protected $_timeout = 30;

    /*
     * 连接错误的错误号
     */
    protected $_errno;

    /*
     * 连接错误的错误串
     */
    protected $_errstr;

    /**
     * @var resource $_connection
     * @desc Socket连接对象
     */
    protected $_connection;

    /**
     * 连接状态，未连接DISCONNECTED，已连接CONNECTED
     */
    protected $_connectionState = 0;

    /**
     * 是否使持久连接
     */
    protected $_persistentConnection = false;

    /**
     * 为true时，会打印出请求字符串
     */
    protected $_debug = false;

    /**
     * 构造函数
     */
    public function __construct() {
        
    }

    /**
     * 连接Socket
     *
     * @param string $host 连接主机
     * @param int $port 端口号
     * @param int $timeout 超时时间
     * @return 
     */
    public function connect($host = false, $port = false, $timeout = false) {
        if ($this->validateConnection()) return; // 已经处于连接状态
        
        $host = $host ? $host : $this->_host;
        $port = $port ? $port : $this->_port;
        $timeout = $timeout ? $timeout : $this->_timeout;
        $this->_connection = fsockopen($host, $port, $this->_errno, $this->_errstr, $timeout);
        if (!$this->_connection) {
            $str = "Connecting to {$this->_host}:{$this->_port} failed.<br>Reason: {$this->_errstr}<br />\n";
            $this->_throwError($str);
        }

        stream_set_blocking($this->_connection, 0);
        $this->_connectionState = 1;
    }

    /**
     * 设置请求的主机，有的请求为passport.sohu.com，有的为internal.passport.sohu.com
     *
     * @param string $host 设置连接主机
     */
    public function setHost($host) {
        $this->_host = $host;
        return $this;
    }

    /**
     * 设置请求的主机，有的请求为passport.sohu.com，有的为internal.passport.sohu.com
     *
     * @return string 返回请求的主机
     */
    public function getHost() {
        return $this->_host;
    }
    
    /**
     * 设置连接端口
     * @param int $port
     */
    public function setPort($port) {
        $port = (int)$port;
        $this->_port = $port;
        return $this;
    }

    /**
     * 设置是否处于调试阶段，debug打开时，会打印出请求字符串
     *
     * @param bool $debug
     */
    public function setDebug($debug) {
        $this->_debug = $debug;
        return $this;
    }

    /*
     * 连接服务器，向服务器发送Socket请求并获取响应
     *
     * @param string $requestStr 请求字符串
     * @return object SimpleXMLElememt对象
     */
    public function request($requestStr) {
        $this->connect(); // 连接
        $this->sendRequest($requestStr); // 请求
        $response = $this->getResponse(); // 获取响应
        return $response;
    }

    /*
     * 向服务器发送Socket请求
     *
     * @param string $requestStr 请求字符串
     * @return int fwrite的返回值，表示number of bytes written, or FALSE on error.
     */
    public function sendRequest($requestStr) {
        if ($this->_debug) {
            echo "<b>请求字符串</b>：<br>";
            echo "<pre>" . htmlspecialchars($requestStr) . "</pre>";
            echo "------------------------<br>";
        }
        
        if(!$this->validateConnection()) return -1;
        //$result = fwrite($this->_connection, $requestStr, strlen($requestStr));
        $result = $this->_fullwrite($this->_connection, $requestStr);
        return $result;
    }

    /**
     * 获取响应，如果一定读取字节数不够，接着读取
     *
     * @param resource $sd Socket连接对象
     * @param int $len 读取的长度
     * @return 读取的字符串
     */
    private function _fullread ($sd, $len) {
        $ret = '';
        $read = 0;

        while ($read < $len && ($buf = fread($sd, $len - $read))) {
            $read += strlen($buf);
            $ret .= $buf;
        }

        return $ret;
    }

    /**
     * 向Socket写数据，如果一次没写完，尝试多次写
     *
     * @param resource $sd Socket连接对象
     * @param string $buf 要写入的字符串
     * @return 返回实际写入的数据长度
     */
    private function _fullwrite ($sd, $buf) {
        $total = 0;
        $len = strlen($buf);

        while ($total < $len && ($written = fwrite($sd, $buf))) {
            $total += $written;
            $buf = substr($buf, $written);
        }

        return $total;
    }
    
    /*
     * 从服务器获取响应
     *
     * @return string 响应字符串
     */
    public function getResponse() {
        if(!$this->validateConnection()) return -1;

        $responseStr = "";
        while (!feof($this->_connection)) {
            $responseStr .= fgets($this->_connection, 1024);
        }
        
        if ($this->_debug) {
            echo "<b>响应字符串</b>：<br>";
            echo "<pre>" . htmlspecialchars($responseStr) . "</pre>";
            echo "------------------------<br>";
        }
        
        return $responseStr;
    }

    /**
     * 断开连接
     * 
     * @return True on succes, false if the connection was already closed
     */
    public function disconnect() {
        if($this->validateConnection())
        {
            fclose($this->_connection);
            $this->_connectionState = 0;
            return true;
        }
        return false;
    }

    /**
     * 验证连接状态
     * 
     * @return bool
     */
    public function validateConnection() {
        return (is_resource($this->_connection) && ($this->_connectionState != 0));
    }
    
    /**
     * 抛出异常
     *
     * @return void
     */
    private function _throwError($errorMessage) {
        throw new Exception("Socket error: " . $errorMessage);
    }

    /**
     * 析构函数，断开连接
     *
     */
    public function __destruct() {
        $this->disconnect();
    }
    
}
?>