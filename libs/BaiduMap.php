<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 9/29/14
 * Time: 3:54 PM
 */
    class BaiduMap{
        /*
         * input
    1：GPS设备获取的角度坐标;

    2：GPS获取的米制坐标、sogou地图所用坐标;

    3：google地图、soso地图、aliyun地图、mapabc地图和amap地图所用坐标

    4：3中列表地图坐标对应的米制坐标

    5：百度地图采用的经纬度坐标

    6：百度地图采用的米制坐标

    7：mapbar地图坐标;

    8：51地图坐标

        output:
        5：bd09ll(百度经纬度坐标),

6：bd09mc(百度米制经纬度坐标);
        */
		private static $_instanceCache;
        private $inputGps = 1;
        private $inputGpsMetricCoordinates = 2;
        private $inputCoordinates = 3;
        private $outputBaiduLonLat = 5;
        private $outputBaiduMetricCoordinates = 6;
        private $ak = 'HeucyeFvEwTzVA6rwOWnqbGs';
        private $sk = 'iF8YuygaHznsAyPkFxRdiQiM3vKERZSl';
        private $errorCode;
        private $rhError = array(
            1       =>	'服务器内部错误',
            2       =>	'请求参数非法',
            3       =>	'权限校验失败',
            4       =>	'配额校验失败',
            5 	    =>  'ak不存在或者非法',
            101     =>	'服务禁用',
            102 	=>  '不通过白名单或者安全码不对',
            202     =>  '无请求权限',
            203     =>  '无请求权限',
            204     =>  '无请求权限',
            205     =>  '无请求权限',
            210     =>  '无请求权限',
            210     =>  '无请求权限',
            231     =>	'用户uid，ak不存在',
            232     =>	'用户、ak被封禁',
            234 	=>  'sn签名计算错误',
            210     =>  '权限资源不存在',
            345     =>  '分钟配额超额',
            346     =>	'月配额超额',
            347 	=>  '年配额超额',
            348     =>	'永久配额超额无请求权限',
            355 	=>  '日配额超额',
            350     =>	'配额资源不存在',
        );
        private $staticMapUrl = "http://api.map.baidu.com/staticimage";

        public function __construct(){

        }

        public function getStaticMapUrl($x, $y, $height = '179', $width = '207',$inputType=3){
            if ($inputType != $this->outputBaiduLonLat){
                $arLonLat = $this->getLonLat($x, $y, $inputType);
            }
            else{
                $arLonLat = array(
                    'x' =>  $x,
                    'y' =>  $y,
                );
            }

            if (!$arLonLat) return false;
            $array = array(
                'center'    =>  $arLonLat['x'].",".$arLonLat['y'],
                "height"    =>  $height,
                "width"     =>  $width,
                "zoom"      =>  17,
                "markers"   =>  $arLonLat['x'].",".$arLonLat['y'],
            );
            return $this->staticMapUrl."?".http_build_query($array);
        }

        public function getLonLat($x, $y, $inputType=3, $outputType=5){ //默认火星坐标
            if ( empty($x) || empty($y) ) {
                return '';
            }
//            var_dump($x);
//            var_dump($y);
//            die();
            $cacheKey = MCDefine::BAIDU_MAP."x:".$x."y:$y";
            $memCache = Mem::Instance();
            $info = $memCache->get($cacheKey);//不需要清楚cache，因为x，y变了，自然key也变了
            if ($info === false){
                $url = "http://api.map.baidu.com/geoconv/v1/";
                $array = array(
                    "coords"    =>  $x.",".$y,
                    "from"  =>  $inputType,
                    "to"    =>  $outputType,
                    "ak"    =>  $this->ak,
                );
                $sn = $this->caculateAKSN($this->ak, $this->sk, '/geoconv/v1/' ,$array);
                $requestUrl = $url.'?'.http_build_query($array)."&sn=".$sn;
                $info = Curl::GetResult($requestUrl, array(), '', 3, 0, '', 'get');
                $info = json_decode($info, true);
                if ($info['status'] == 0){
                    $memCache->set($cacheKey, json_encode($info['result'][0]),3600*24*30);
                    return $info['result'][0];
                }
                else{
                    $this->errorCode = $info['status'];
                    return false;
                }
            }
            $info = json_decode($info, true);
            return $info;

        }

        public function getErrorMessage(){
            return $this->rhError[$this->errorCode];
        }

        /**
         * @brief 计算SN签名算法
         * @param string $ak access key
         * @param string $sk secret key
         * @param string $url url值，例如: /geosearch/nearby 不能带hostname和querstring，也不能带？
         * @param array  $querystring_arrays 参数数组，key=>value形式。在计算签名后不能重新排序，也不能添加或者删除数据元素
         * @param string $method 只能为'POST'或者'GET'
         */
        public function caculateAKSN($ak, $sk, $url, $querystring_arrays, $method = 'GET'){
            if ($method === 'POST'){
                ksort($querystring_arrays);
            }
            $querystring = http_build_query($querystring_arrays);
            return md5(urlencode($url.'?'.$querystring.$sk));
        }

		public static function instance ($cache = true){
			if($cache){
				if(isset(self::$_instanceCache)){
					return self::$_instanceCache;
				}
				self::$_instanceCache = new self;
				return self::$_instanceCache;
			}else{
				return new self;
			}

		}
    }