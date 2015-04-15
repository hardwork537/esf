<?php 
/**
 * CURL HTTP响应封装
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/8/11
 */

class HttpResponse {
    public $version = null;
    public $statusCode = null;
    public $statusMessage = null;
    public $headers = array();
    public $body = null;
    public $file = null;

    public $error_no;
    public $error_mess;
    public $request;


    /*
       检查是否发生错误
    */
    public function error($ctype = '/^image/i'){

        if(($this->statusCode == 200) && empty($this->error_no) && $this->checkHeader($ctype)){
            return false;
        }

        return true;
    }

    public function errorNoType(){
        return $this->error(null);
    }

    public function errorMsg(){
        if(empty($this->error_no))
            return $this->statusMessage;
        else
            return $this->error_mess;
    }
    public function errorCode(){
        $this->error_no;
    }
    public function checkheader($ctype){
        if(empty($ctype))
            return true;

        if(! preg_match($ctype,$this->headers['Content-Type'])){
            $this->error_no = 1001;
            $this->error_mess = "错误的文件类型";
            return false;
        }
        return true;
    }
    /*
    将获取的数据保存到文件
    执行成功返回文件路径否则返回false
    */
    public function save($file = null){
        if($this->error()){
            return false;
        }
        if(empty($file))
            $file = $this->getTmpPath();

        $fp = fopen($file, "w");

        if(! empty($fp) )
            fwrite($fp, $this->body);
        else{
            $this->error_no = 1000;
            $this->error_mess = "file:$file is not writable!";
            return false;
        }
        fclose($fp);
        $this->file = $file;
        return $file;
    }
    /*
    直接显示所获取的的数据
    */
    public function display(){
        if($this->error())
            return false;

        header("content-type: " .$this->headers['Content-Type']);
        echo $this->body;
        return true;
    }

    private function getTmpPath(){
        $dstr = "";
        $m = "Za0YbXc1WdVe2UfTg3ShRiQ4jPk5Ol6NmM7nL8oKpJqIr9HsGtFuEvDwCxByAz";
        for( $i = 1;$i <= 8;$i++ ) {
            mt_srand( ( double )microtime() * 1000000 );
            $ta = mt_rand( 0, 61 );
            $dstr = $dstr . substr( $m, $ta, 1 );
        } ;
        $ext = (substr($this->headers['Content-Type'],strrpos($this->headers['Content-Type'],"/")+1));
        return "/tmp/".$dstr.".".$ext;
    }
}
?>