<?php
/**
 * 图片公共的方法
 * @author qiyaguo <qiyaguo@sohu-inc.com>
 * @date 2014/8/11
 */

class ImageUtility {
    //图片上传服务器URL
    const UPLOAD_SERVER_URL = 'http://v2.imgup.focus.cn/';

    /**
     * 检查文件名是否合法. 
     *
     * @param string $file_name 名字 
     * @param int $max_len 允许的最大字节长度. 
     * @return bool true:合法;false:非法; 
     */
    public static function checkPhotoName($file_name,$max_len) {
        $file_name = trim($file_name);
        $max_len = intval($max_len);
        //判断长度.
        $f_len = strlen($file_name);
        if( 0 >= $f_len || $max_len < $f_len ) {
            return false;
        }
        //非法词验证.
        //todo
        return true;
    }

    /**
     * 根据图片ID散列地址. 
     *
     * @param int $file_id  图片ID 
     * @return String       地址字符串   
     */
    public static function hashFile($file_id) {
        $file_id = intval($file_id);
        if($file_id <= 0 ) {
            return null;
        }
        $hash_base_number = _IMG_HASH_KEY_;
        if ( $file_id >= 72000000 ) {
        	$d1 = intval( $file_id/($hash_base_number*$hash_base_number) );
			$hash_path = "$d1/";
			return $hash_path;
        }
        $d1 = intval( $file_id/($hash_base_number*$hash_base_number) ); 
        $d2 = intval( ($file_id%($hash_base_number*$hash_base_number)) / ($hash_base_number) ); 
        $d3 = $file_id % $hash_base_number; 
        $hash_path = "$d1/$d2/$d3/"; 
        return $hash_path; 
    }


    /**
     * 获取图片文件名. (默认返回原图地址,如果高宽都大于0返回相应大小的图片地址)
     *
     * @param int $file_id  图片ID 
     * @param string $extension 扩展名(jpg,gif等). 
     * @param int $width 图片宽度. 
     * @param int $height 图片高度. 
     * @return string 图片名称
     */
    public static function getImgFileName($file_id=0,$extension='jpg',$width=0,$height=0) {
        $file_id = intval($file_id);
        $extension = trim($extension);
        $width = intval($width);
        $height = intval($height);
        if($file_id <= 0 || empty($extension)){
            return null;
        }
        if ($width>0 && $height>0) {
            return "{$width}x{$height}_{$file_id}.{$extension}";
        } else {
            return "{$file_id}.{$extension}";
        }
    }

        
    /**
     * 获取图片url地址. 
     *
     * @param String $product_name  查询图片所在产品的名称(esf,ihome) 
     * @param int $file_id  图片ID 
     * @param string $extension 扩展名(jpg,gif等). 
     * @param int $width 图片宽度. 
     * @param int $height 图片高度. 
     * @return string 图片url地址 
     */
    public static function getImgUrl($product_name='',$file_id=0,$extension='jpg',$width=0,$height=0) {
        $product_name = trim($product_name);
        $file_id = intval($file_id);
        $extension = trim($extension);
        $width = intval($width);
        $height = intval($height);
        if(empty($product_name) || $file_id <= 0 || empty($extension)){
            return null;
        }

        global $_IMG_PRODUCTS_DEFINE;
        if( !in_array($product_name, $_IMG_PRODUCTS_DEFINE) ) {
            return null;
        }

        /*
        //判断规格是否合法
        if( !in_array("{$width}x{$height}",$_IMG_FORMATS_DEFINE) ) {
            return null;
        }
        */
        
        //相对地址.
        $relative_path = self::hashFile($file_id);
        //文件名称.
        $file_name = self::getImgFileName($file_id,$extension,$width,$height);

        global $_IMG_CLUSTER_URL_PRE;
        if (is_array($_IMG_CLUSTER_URL_PRE)) {
            $rand_server_num = array_rand($_IMG_CLUSTER_URL_PRE, 1);
            $rand_server = $_IMG_CLUSTER_URL_PRE[$rand_server_num];
        } else {
            $rand_server = "http://img1.f.itc.cn/";
        }
        
        $img_url = $rand_server.$product_name."/".$relative_path.$file_name;

        return $img_url;
    }
    
    /**
     * 获取upload服务器图片url地址(为了解决预览的延时问题) 
     *
     * @param String $product_name  查询图片所在产品的名称(esf,ihome) 
     * @param int $file_id  图片ID 
     * @param string $extension 扩展名(jpg,gif等). 
     * @param int $width 图片宽度. 
     * @param int $height 图片高度. 
     * @return string 图片url地址 
     */
    public static function getUploadImgUrl($product_name='',$file_id=0,$extension='jpg',$width=0,$height=0) {
        $product_name = trim($product_name);
        $file_id = intval($file_id);
        $extension = trim($extension);
        $width = intval($width);
        $height = intval($height);
        if(empty($product_name) || $file_id <= 0 || empty($extension)){
            return null;
        }

        global $_IMG_PRODUCTS_DEFINE;
        if( !in_array($product_name, $_IMG_PRODUCTS_DEFINE) ) {
            return null;
        }
        
        //相对地址.
        $relative_path = self::hashFile($file_id);
        //文件名称.
        $file_name = self::getImgFileName($file_id,$extension,$width,$height);
        //图片预览地址
        $img_url = self::UPLOAD_SERVER_URL."upload/".$product_name."/".$relative_path.$file_name;

        return $img_url;
    }
}

?>