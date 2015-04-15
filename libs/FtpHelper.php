<?php
/**
 * @abstract ftp 上传类
 * @author zhangzhiyu <zhiyuzhang@sohu-inc.com>
 * @date 2014-1-6
 * @LastModified 2014-1-6
 */
 
 class FtpHelperException extends Exception{};
 
 final class FtpHelper {
 	
 	private $hostname	= '';
	private $username	= '';
	private $password	= '';
	private $port 		= 21;
	private $passive 	= TRUE;
	private $debug		= TRUE;
	private $conn_id 	= FALSE;
	
	
	public function __construct($hostname,$username,$password) {
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->_connect();
	}
	
	/**
	 * FTP连接	 
	 * @access 	public
	 * @param 	array 	配置数组
	 * @return	boolean
	 */
	private function _connect() {				
		if(($this->conn_id = @ftp_connect($this->hostname,$this->port))=== FALSE) {
			if($this->debug === TRUE) {
				$this->_error("ftp_unable_to_connect");
			}
			return FALSE;
		}		
		if( ! $this->_login()) {
			if($this->debug === TRUE) {
				$this->_error("ftp_unable_to_login");
			}
			return FALSE;
		}
		
		if($this->passive === TRUE) {
			ftp_pasv($this->conn_id, TRUE);
		}		
		return TRUE;
	}
	
	/**
	 * FTP登陆
	 * @access 	private
	 * @return	boolean
	 */
	private function _login() {
		return @ftp_login($this->conn_id, $this->username, $this->password);
	}
	
	/**
	 * 改变目录
	 * @access 	public
	 * @param 	string 	目录标识(ftp)
	 * @param	boolean	
	 * @return	boolean
	*/
	 
	public function changedir($path = '', $supress_debug = FALSE) {
		if($path == '' || ! $this->_isconn()) {
			return FALSE;
		}
		$result = @ftp_chdir($this->conn_id, $path);		
		if($result === FALSE) {
			if($this->debug === TRUE && $supress_debug == FALSE) {
				$this->_error("ftp_unable_to_chgdir:dir[".$path."]");
			}
			return FALSE;
		}		
		return TRUE;
	}
	
	/**
	 * 上传
	 * @access 	public
	 * @param 	string 	远程目录标识(ftp)
	 * @param	string	本地目录标识
	 * @param	string	下载模式 auto || ascii	
	 * @return	boolean
	 */
	public function upload($localpath, $remotepath, $mode = 'auto', $permissions = NULL) {
		if( ! $this->_isconn()) {
			return FALSE;
		}		
		if( ! file_exists($localpath)) {
			if($this->debug === TRUE) {
				$this->_error("ftp_no_source_file:".$localpath);				
			}
			return FALSE;
		}			
		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;		
		$result = @ftp_put($this->conn_id, $remotepath, $localpath, $mode);		
		if($result === FALSE) {
			if($this->debug === TRUE) {
				$this->_error("ftp_unable_to_upload:localpath[".$localpath."]/RemotePath[".$remotepath."]");
			}
			return FALSE;
		}		
		if(!is_null($permissions)) {
			$this->chmod($remotepath,(int)$permissions);
		}		
		return TRUE;
	}
	
	/**
	 * 下载
	 * @access 	public
	 * @param 	string 	远程目录标识(ftp)
	 * @param	string	本地目录标识
	 * @param	string	下载模式 auto || ascii	
	 * @return	boolean
	 */
	public function download($remotepath, $localpath, $mode = 'auto') {
		if( ! $this->_isconn()) {
			return FALSE;
		}		
		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;		
		$result = @ftp_get($this->conn_id, $localpath, $remotepath, $mode);		
		if($result === FALSE) {
			if($this->debug === TRUE) {
				$this->_error("ftp_unable_to_download:localpath[".$localpath."]-Remotepath[".$remotepath."]");
			}
			return FALSE;
		}		
		return TRUE;
	}
	
	/**
	 * 修改文件权限
	 *
	 * @access 	public
	 * @param 	string 	目录标识(ftp)
	 * @return	boolean
	 */
	public function chmod($path, $perm) {
		if( ! $this->_isconn()) {
			return FALSE;
		}		
		//只有在PHP5中才定义了修改权限的函数(ftp)
		if( ! function_exists('ftp_chmod')) {
			if($this->debug === TRUE) {
				$this->_error("ftp_unable_to_chmod(function)");
			}
			return FALSE;
		}		
		$result = @ftp_chmod($this->conn_id, $perm, $path);		
		if($result === FALSE) {
			if($this->debug === TRUE) {
				$this->_error("ftp_unable_to_chmod:path[".$path."]-chmod[".$perm."]");
			}
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 判断con_id
	 * @access 	private
	 * @return	boolean
	 */
	private function _isconn() {
		if( ! is_resource($this->conn_id)) {
			if($this->debug === TRUE) {
				$this->_error("ftp_no_connection");
			}
			return FALSE;
		}
		return TRUE;
	}	
	
	private function _error($msg) {
		echo "Error:".$msg."\n";		
	}
	
	/**
	 * 关闭FTP
	 * @access 	public
	 * @return	boolean
	 */
	public function close() {
		if( ! $this->_isconn()) {
			return FALSE;
		}		
		return @ftp_close($this->conn_id);
	}
	
 }
?>
