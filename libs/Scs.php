<?php
/**
 * 上传图片到搜狐云
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/8/11
 */

define("UPLOAD_MAX_WIDTH",          5000);
define("UPLOAD_MAX_HEIGHT",         5000);
define("UPLOAD_MIN_WIDTH",          300);
define("UPLOAD_MIN_HEIGHT",         300);
define("UPLOAD_MAX_FILE_SIZE",      4 * 1024 * 1024);
define("EMPTY_MD5FILE",             '59cd2abb13fec97c5a38a7bb6d4a1df1');//暂无图片
define("MONITOR_MD5FILE",           'b04d90fc625afd952bdfeea10750c647');//网安图片

// require the SCS SDK for PHP library
require dirname(__FILE__) . '/scs/scs-autoloader.php';
use SohuCS\Scs\ScsClient;

class Scs {
	private static $mInstance;
	private $mScs = null;
	private $mProduct = 'esf';
    private $file;
    private $src_type;
    private $file_size = 0;
    private $size;
    private $error_msg = null;
    //图片格式
    private $img_type = array(
        'jpg','jpeg','gif','png'
    );

	/**
	 * 单键对象
	 *
	 * @return obj
	 */
	public static function Instance(){
		if ( isset( self::$mInstance ) ) return self::$mInstance;
		return self::$mInstance = new Scs();
	}

	/**
	 * 初始化建立搜狐云连接
	 *
	 * @return null
	 */
	public function __construct() {
		// Establish connection with SCS with an Scs client.
		$this->mScs = ScsClient::factory(array(
			'key' => SCS_ACCESS_KEY,
			'secret' => SCS_SECRET_KEY,
		));
	}

	/**
	 * 上传本地图片
	 *
	 * @param string $img_file FILE控件名
	 * @param int $img_from 图片来源类型
	 * @param int $img_fromid 图片来源ID
	 * @param array $meta_data 图片META信息
	 * @return array
	 */
	public function uploadImage($img_file, $img_from, $img_fromid, $meta_data = array()) {
		if(empty($img_file) || empty($img_from) || empty($_FILES[$img_file])){
			return array('error' => "参数错误");
		}

		//获取图片信息
		$this->src_type = 'upload';
		$this->file = $_FILES[$img_file];
		$this->file_size = filesize($this->file['tmp_name']);
		$this->size = getimagesize($this->file['tmp_name']);

		//检查图片
		if (!$image_ext = $this->checkImgExt()) {
			return array('error' => $this->error_msg);
		}
		if (!$this->checkFileSize()) {
			return array('error' => $this->error_msg);
		}
		if(empty($meta_data))
		{
			if (!$this->checkImgSize(null, null, false, true)) {
				return array('error' => $this->error_msg);
			}
		}
		else
		{
			if (!$this->checkImgSize(null, null, true, true)) {
				return array('error' => $this->error_msg);
			}
		}

		//检测重复图片
	    $md5file = md5_file($this->file['tmp_name']);
		$objImage = new Image();
	    $imgInfo = $objImage->getImageByMd5($md5file);
	    if(!empty($imgInfo) && intval($imgInfo['imgId'])>0 && (!empty($imgInfo['imgExt']))){
			$_IMG_CLUSTER_URL_PRE = array(
				1 => 'http://img1.f.itc.cn/',
				2 => 'http://img2.f.itc.cn/',
			);
			$imageArrPath = $this->mProduct."/".ImageUtility::hashFile($imgInfo['imgId']).$imgInfo['imgId'].".".$imgInfo['imgExt'];
			$img_info['id'] = $imgInfo['imgId'];
			$img_info['ext'] = $imgInfo['imgExt'];
			$img_info['upload_url'] = $_IMG_CLUSTER_URL_PRE[array_rand($_IMG_CLUSTER_URL_PRE, 1)].$imageArrPath;
			return $img_info;
	    }
		//生成图片ID
		$imgData = array(
			'imgType' => $objImage::TYPE_DEFAULT,
			'imgExt' => $image_ext,
			'imgWidth' => $this->size[0],
			'imgHeight' => $this->size[1],
			'imgSize' => $this->file_size,
			'imgMd5Data' => $md5file,
			'imgMd5Url' => '',
			'imgFrom' => $img_from,
			'imgFromId' => $img_fromid,
			'imgStatus' => $objImage::STATUS_OK,
			'imgUpdate' => date('Y-m-d h:i:s', time()),
		);
		$objImage->begin();
		$objImage->create($imgData);
		$image_id = $objImage->getImgId();
		if (!$image_id) {
			return array('error' => "图片ID为空");
		}

		//上传到搜狐云
		$client = $this->mScs;
		$image_fold = ImageUtility::hashFile($image_id);
		$image_key = $this->mProduct.'/'.$image_fold.$image_id.'.'.$image_ext;
		$result = $client->putObject(array(
			'Bucket' => SCS_BUCKET_SRC,
			'Key' => $image_key,
			'Body' => fopen($this->file['tmp_name'], 'r+'),
			'Metadata' => $meta_data,
		));
		if (!empty($result['ObjectURL'])) {
			$objImage->commit();
			$_IMG_CLUSTER_URL_PRE = array(
				1 => 'http://img1.f.itc.cn/',
				2 => 'http://img2.f.itc.cn/',
			);
			$imageArrPath = $this->mProduct."/".ImageUtility::hashFile($image_id).$image_id.".".$image_ext;
			$img_info['id'] = $image_id;
			$img_info['ext'] = $image_ext;
			$img_info['upload_url'] = $_IMG_CLUSTER_URL_PRE[array_rand($_IMG_CLUSTER_URL_PRE, 1)].$imageArrPath;
			return $img_info;
		} else {
			$objImage->rollback();
			return array('error' => "图片上传失败");
		}
	}

	/**
	 * 上传远程图片
	 *
	 * @param string $img_url 图片URL
	 * @param int $img_from 图片来源类型
	 * @param int $img_fromid 图片来源ID
	 * @param array $meta_data 图片META信息
	 * @return string
	 */
	public function uploadNetImage($img_url, $img_from, $img_fromid, $meta_data = array()) {
		if(empty($img_url) || empty($img_from) || empty($img_fromid)){
			return array('error' => "参数错误");
		}
		Log::ErrorWrite('soap', '', $img_url, 'upload.txt');//在日志中记录图片链接
		//检测重复URL图片
	    $md5url = md5($img_url);
		$objImage = new Image();
	    $imgInfo = $objImage->getImageByMd5('', $md5url);
	    if(!empty($imgInfo) && intval($imgInfo['imgId'])>0 && (!empty($imgInfo['imgExt']))){
			$_IMG_CLUSTER_URL_PRE = array(
				1 => 'http://img1.f.itc.cn/',
				2 => 'http://img2.f.itc.cn/',
			);
			$imageArrPath = $this->mProduct."/".ImageUtility::hashFile($imgInfo['imgId']).$imgInfo['imgId'].".".$imgInfo['imgExt'];
			$img_info['id'] = $imgInfo['imgId'];
			$img_info['ext'] = $imgInfo['imgExt'];
			$img_info['upload_url'] = $_IMG_CLUSTER_URL_PRE[array_rand($_IMG_CLUSTER_URL_PRE, 1)].$imageArrPath;
			return $img_info;
	    }

		//抓取图片
		require_once dirname(__FILE__) . '/HttpRequest.php';
		require_once dirname(__FILE__) . '/HttpResponse.php';
		require_once dirname(__FILE__) . '/HttpClient.php';
		$req = new HttpRequest();//发起http请求
		$req->url = $img_url;
		$res = $req->get();
		$file = $res->save();//保存文件到临时目录
		if($res->error()){
			return array('error' => "图片抓取错误".$res->statusCode);
		}

		//获取图片信息
		$this->src_type = 'http';
		$this->file = $file;
		$this->file_size = filesize($this->file);
		$this->size = getimagesize($this->file);

		//检查图片
		if (!$image_ext = $this->checkImgExt()) {
			return array('error' => $this->error_msg);
		}
		if (!$this->checkFileSize()) {
			return array('error' => $this->error_msg);
		}
		//原来接口上传图片不限制最小尺寸
		if (!$this->checkImgSize()) {
			return array('error' => $this->error_msg);
		}

		//检测重复图片
	    $md5file = md5_file($this->file);
		$objImage = new Image();
	    $imgInfo = $objImage->getImageByMd5($md5file);
	    if(!empty($imgInfo) && intval($imgInfo['imgId'])>0 && (!empty($imgInfo['imgExt']))){
			$_IMG_CLUSTER_URL_PRE = array(
				1 => 'http://img1.f.itc.cn/',
				2 => 'http://img2.f.itc.cn/',
			);
			$imageArrPath = $this->mProduct."/".ImageUtility::hashFile($imgInfo['imgId']).$imgInfo['imgId'].".".$imgInfo['imgExt'];
			$img_info['id'] = $imgInfo['imgId'];
			$img_info['ext'] = $imgInfo['imgExt'];
			$img_info['upload_url'] = $_IMG_CLUSTER_URL_PRE[array_rand($_IMG_CLUSTER_URL_PRE, 1)].$imageArrPath;
			return $img_info;
	    }

		//生成图片ID
		$imgData = array(
			'imgType' => $objImage::TYPE_DEFAULT,
			'imgExt' => $image_ext,
			'imgWidth' => $this->size[0],
			'imgHeight' => $this->size[1],
			'imgSize' => $this->file_size,
			'imgMd5Data' => $md5file,
			'imgMd5Url' => $md5url,
			'imgFrom' => $img_from,
			'imgFromId' => $img_fromid,
			'imgStatus' => $objImage::STATUS_OK,
			'imgUpdate' => date('Y-m-d h:i:s', time()),
		);
		$objImage->begin();
		$objImage->create($imgData);
		$image_id = $objImage->getImgId();
		if (!$image_id) {
			return array('error' => "图片ID为空");
		}

		//上传到搜狐云
		$image_fold = ImageUtility::hashFile($image_id);
		$image_key = $this->mProduct.'/'.$image_fold.$image_id.'.'.$image_ext;
		$client = $this->mScs;
		$result = $client->putObject(array(
			'Bucket' => SCS_BUCKET_SRC,
			'Key' => $image_key,
			'Body' => fopen($file, 'r+'),
			'Metadata' => $meta_data,
		));
		if (!empty($result['ObjectURL'])) {
			$objImage->commit();
			$_IMG_CLUSTER_URL_PRE = array(
				1 => 'http://img1.f.itc.cn/',
				2 => 'http://img2.f.itc.cn/',
			);
			$imageArrPath = $this->mProduct."/".ImageUtility::hashFile($image_id).$image_id.".".$image_ext;
			$img_info['id'] = $image_id;
			$img_info['ext'] = $image_ext;
			$img_info['upload_url'] = $_IMG_CLUSTER_URL_PRE[array_rand($_IMG_CLUSTER_URL_PRE, 1)].$imageArrPath;
			return $img_info;
		} else {
			$objImage->rollback();
			return array('error' => "图片上传失败");
		}
	}

	/**
	 * 迁移远程图片到搜狐云
	 *
	 * @return string
	 */
	public function moveImage($image_id, $image_ext, $meta_data = array()) {
		//获取图片URL
		$_IMG_CLUSTER_URL_PRE = array(
			1 => 'http://img1.f.itc.cn/',
			2 => 'http://img2.f.itc.cn/',
		);
		$image_path = $this->mProduct."/".ImageUtility::hashFile($image_id).$image_id.".".$image_ext;
		$image_url = $_IMG_CLUSTER_URL_PRE[1].$image_path;

		//抓取图片
		require_once dirname(__FILE__) . '/HttpRequest.php';
		require_once dirname(__FILE__) . '/HttpResponse.php';
		require_once dirname(__FILE__) . '/HttpClient.php';
		$req = new HttpRequest();//发起http请求
		$req->url = $image_url;
		$res = $req->get();
		$file = $res->save();//保存文件到临时目录
		if($res->error()){
			echo "图片(".$image_id.")抓取错误".$res->statusCode."\n";
			return false;
		}
		$md5file = md5_file($file);
		if ($md5file == EMPTY_MD5FILE || $md5file == MONITOR_MD5FILE) {
			$image_url = $_IMG_CLUSTER_URL_PRE[2].$image_path;
			$req->url = $image_url;
			$res = $req->get();
			$file = $res->save();
			if($res->error()){
				echo "图片(".$image_id.")抓取错误".$res->statusCode."\n";
				return false;
			}
			if ($md5file == EMPTY_MD5FILE || $md5file == MONITOR_MD5FILE) {
				echo "该图片(".$image_id.")可能已被删除\n";
				return false;
			}
		}

		//上传到搜狐云
		$image_fold = ImageUtility::hashFile($image_id);
		$image_key = $this->mProduct.'/'.$image_fold.$image_id.'.'.$image_ext;
		$client = $this->mScs;
		$result = $client->putObject(array(
			'Bucket' => SCS_BUCKET_SRC,
			'Key' => $image_key,
			'Body' => fopen($file, 'r+'),
			'Metadata' => $meta_data,
		));
		if (empty($result['ObjectURL'])) {
			return false;
		} else {
			return $md5file;
		}
	}

	/**
	 * 删除图片
	 *
	 * @return string
	 */
	public function deleteImage($image_id, $image_ext) {
		$client = $this->mScs;
		$image_fold = ImageUtility::hashFile($image_id);
		$image_key = $this->mProduct.'/'.$image_fold.$image_id.'.'.$image_ext;
		//拷贝文件
		$result = $client->copyObject(array(
			// Bucket is required
			'Bucket' => SCS_BUCKET_BAK,
			// CopySource is required
			'CopySource' => '/'.SCS_BUCKET_SRC.'/'.$image_key,
			// Key is required
			'Key' => $image_key,
		));
		//echo $result . "\n";

		//删除文件
		$result = $client->deleteObject(array(
			// Bucket is required
			'Bucket' => SCS_BUCKET_SRC,
			// Key is required
			'Key' => $image_key,
		));
		//echo $result . "\n";
		if (empty($result)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 恢复图片
	 *
	 * @return string
	 */
	public function recoverImage($image_id, $image_ext) {
		$client = $this->mScs;
		$image_fold = ImageUtility::hashFile($image_id);
		$image_key = $this->mProduct.'/'.$image_fold.$image_id.'.'.$image_ext;
		//拷贝文件
		$result = $client->copyObject(array(
			// Bucket is required
			'Bucket' => SCS_BUCKET_SRC,
			// CopySource is required
			'CopySource' => '/'.SCS_BUCKET_BAK.'/'.$image_key,
			// Key is required
			'Key' => $image_key,
		));
		//echo $result . "\n";

		//删除文件
		$result = $client->deleteObject(array(
			// Bucket is required
			'Bucket' => SCS_BUCKET_BAK,
			// Key is required
			'Key' => $image_key,
		));
		//echo $result . "\n";
		if (empty($result)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 获取图片信息
	 *
	 * @return array
	 */
	public function getImageHead($image_id, $image_ext) {
		$client = $this->mScs;
		$image_fold = ImageUtility::hashFile($image_id);
		$image_key = $this->mProduct.'/'.$image_fold.$image_id.'.'.$image_ext;
		$result = $client->headObject(array(
			'Bucket' => SCS_BUCKET_SRC,
			'Key' => $image_key,
		));
		return $result;
	}

    /**
     * 检查文件扩展名(是否是有效图片)
     *
     * @return bool|string
     */
    private function checkImgExt() {
        //对上传文件的扩展名初步检验
        if ($this->src_type == "upload") {
            $userphoto_name = $this->file['name'];
        } elseif($this->src_type == "http") {
            $userphoto_name = $this->file;
        }
        $photo_ext = pathinfo($userphoto_name);
        $photo_ext = strtolower($photo_ext['extension']);
        if (!in_array($photo_ext,$this->img_type)) {
			$this->error_msg = "无效的上传文件格式";
            return false;
        }
        return $photo_ext;
    }

    /* 检查图片大小(长和宽)
     *
     * @param int $w
     * @param int $h
     * @param bool $min  是否限制最小尺寸
     * @param bool $max  是否限制最大尺寸
     * @return bool
     */
    private function checkImgSize($w = null, $h = null, $min = false, $max = true){
        if($w != null && $h != null){
            if (($this->size[0] == $w) && ($this->size[1] == $h)){
                return true;
            } else {
                $this->error_msg = "请上传宽为{$w}, 高为{$h}的图片！";
				return false;
            }
        }

        if($min){
			if($this->size[0] < UPLOAD_MIN_WIDTH ){
                $this->error_msg = "你上传了宽小于".UPLOAD_MIN_WIDTH."像素的图片！";
				return false;
			}
			if($this->size[1] < UPLOAD_MIN_HEIGHT){
                $this->error_msg = "你上传了高小于".UPLOAD_MIN_HEIGHT."像素的图片！";
				return false;
			}
		}

        if($max){
			if($this->size[0] > UPLOAD_MAX_WIDTH ){
                $this->error_msg = "你上传了宽超过".UPLOAD_MAX_WIDTH."像素的图片，请先用工具改变尺寸后再上传！";
				return false;
			}
			if($this->size[1] > UPLOAD_MAX_HEIGHT){
                $this->error_msg = "你上传了高超过".UPLOAD_MAX_HEIGHT."像素的图片，请先用工具改变尺寸后再上传！";
				return false;
			}
		}

        return true;
    }

    /**
     * 检查文件大小
     *
     * @param int $size
     * @return bool
     */
    private function checkFileSize($size = 0){
        if(empty($size)) {
            $size = UPLOAD_MAX_FILE_SIZE;
        }
        if($this->file_size > $size){
			$this->error_msg = "请不要上传大于4M的文件！";
            return false;
        }
        return true;
    }
}

?>