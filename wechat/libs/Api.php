<?php
/**
 * 微信服务号交互操作类
 * 调用该类需要先引入/config/wechat.config.inc.php 配置文件
 * @author jackchen chenzhicheng@sohu-inc.com
 * @since 2014-09-16
 */

class Api 
{
	const CURL_EXCUTE_TIME = 5;
	const CURL_CONNECT_WAIT_TIME = 3;

	private $mError;
	
	private static $mInstance;
	
	/**
	 * 私有化构造函数，防止在类外部通过new关键字来创建实例
	 */
	private function __construct(){}
	
	/**
	 * 私有化克隆函数，防止通过clone函数来克隆对象
	 */
	private function __clone(){}
	
	public static function getInstance()
	{
		if(!isset(self::$mInstance))
		{
			self::$mInstance = new Api();
		}
	
		return self::$mInstance;
	}
	
	/**
	 * 获取错误信息
	 * @return string
	 */
	public function getError()
	{
		return $this->mError;
	}
	
	/**
	 * 解析微信请求的post信息
	 * @param void
	 * @return array key,value组成的数组
	 */
	public function parseRequestMsg()
	{
		$strPostContent = $GLOBALS["HTTP_RAW_POST_DATA"];
		if(!empty($strPostContent))
		{
			$objPostMsg = simplexml_load_string($strPostContent, 'SimpleXMLElement', LIBXML_NOCDATA);
		}
		else
		{
			$objPostMsg = false;
		}
		return $objPostMsg;
	}

	/**
	 * 将curl请求返回的json格式字符串解析成数组
	 * @param string $strJson 需要解析的Json格式的字符串
	 * @return array 解析之后的数组
	 */
	public function parseCurlResult($strJson)
	{
		//return $this->convertStringCode();
		return json_decode($strJson, true);
	}
	
	/**
	 * 生成微信响应信息xml文本
	 * @param array $arrMsgData array('ToUserName'=>'消息接收者（收到消息的FromUserName）','FromUserName'=>'服务号的APPID','Content'=>'消息内容')
	 * @return string 成功，false 失败
	 */
	public function createTextResponseMsg(array $arrMsgData)
	{
		if(empty($arrMsgData['ToUserName']) || empty($arrMsgData['FromUserName']) 
		|| empty($arrMsgData['Content']))
		{
			$strResponseMsg = false;
		}
		else
		{

			$strResponseMsg = '<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[%s]]></MsgType>
									<Content><![CDATA[%s]]></Content>
								</xml>';
			$strResponseMsg = sprintf($strResponseMsg, $arrMsgData['ToUserName'], $arrMsgData['FromUserName'], time(), 'text', $arrMsgData['Content']);
		}
		return $strResponseMsg;
	}
	
	/**
	 * 通过用户授权接口获取的code值来获取微信用户的openid
	 * 
	 * @param string $strCode
	 * @return string or bool 成功返回openid,失败返回false
	 */
	public function getOpenIdByCode($strCode)
	{
		$strOpenId = '';
		$strReqUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . WEIXIN_APPID . '&secret=' . WEIXIN_APPSECRET . '&code=' . $strCode . '&grant_type=authorization_code';
		$mixedResponse = $this->curlRequest($strReqUrl);
		if($mixedResponse !== false)
		{
			$arrJson = $this->parseCurlResult($mixedResponse);
			if(isset($arrJson['openid']))
			{
				$strOpenId = $arrJson['openid'];
			}
			else
			{
				$this->mError = '获取openid出错，错误号：' . $arrJson['errcode'] . ',错误信息：' . $arrJson['errmsg'];
				return false;
			}
		}
		else
		{
			return false;
		}
		return $strOpenId;
	}
	
	/**
	 * 获取调用微信接口的全局access_token
	 */
    public function getGlobalAccessToken()
    {
        /*先从缓存中获取，缓存中没有或者过期，再从微信服务器中获取*/
        $strMcKey = 'wx_global_access_token';
        $objMc = new Mem();
        $strAccessToken = $objMc->Get($strMcKey);
        $nowtime = time();
        $create_menutime = $objMc->Get('wx_global_createmenu_nowtime');
        if($create_menutime === false){
            $strAccessToken = false;
        }
        if($create_menutime != false){
            $menutime = $nowtime - $create_menutime;
            if($menutime >= 6000){
                $strAccessToken = false;
            }
        }
        //$strAccessToken = false;
        if($strAccessToken === false)
        {
            $strReqUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WEIXIN_APPID . '&secret=' . WEIXIN_APPSECRET;
            $mixedResponse = $this->curlRequest($strReqUrl);

            if($mixedResponse !== false)
            {
                $arrJson = $this->parseCurlResult($mixedResponse);

                if(isset($arrJson['access_token']))
                {
                    $strAccessToken = $arrJson['access_token'];
                }
                else
                {
                    $this->mError = '获取access_token出错，错误号：' . $arrJson['errcode'] . ',错误信息：' . $arrJson['errmsg'];
                    return false;
                }
            }
            else
            {
                return false;
            }
            $objMc->Set($strMcKey, $strAccessToken, 7000);
            $objMc->Set('wx_global_createmenu_nowtime', $nowtime);
        }

        return $strAccessToken;
    }
	
	/**
	 * 检查菜单数据是否符合规定
	 * @param array $arrMenuData 菜单数据
	 * @param string $MenuType 菜单类型，button，sub_button
	 * @param string $bolHasChild 是否包含子菜单
	 * @return boolean true of false
	 */
	public function checkMenu($arrMenuData, $MenuType = 'button', $bolHasChild = false)
	{
		if(!$bolHasChild)
		{
			if(!isset($arrMenuData['type']))
			{
				return false;
			}
			if($arrMenuData['type'] == 'click')
			{
				if(!isset($arrMenuData['key']) || strlen($arrMenuData['key']) > 128)
				{
					$this->mError = 'key长度为1-128个字符';
					return false;
				}
			}
			else if($arrMenuData['type'] == 'view')
			{
				if(!isset($arrMenuData['url']) || strlen($arrMenuData['url']) > 256)
				{
					$this->mError = 'url长度为1-256个字符';
					return false;
				}
			}
			else
			{
				$this->mError = '菜单类型错误（click或者view）';
				return false;
			}
		}
		if(!isset($arrMenuData['name']))
		{
			$this->mError = '菜单必须设置名称';
			return false;
		}
		else 
		{
			if( $MenuType == 'button' && strlen($arrMenuData['name']) > 16)
			{
				$this->mError = '一级菜单名称不能超过8个汉字';
				return false;
			}
			else if($MenuType == 'sub_button' && strlen($arrMenuData['name']) > 40)
			{
				$this->mError = '二级菜单名称不能超过20个汉字';
				return false;
			}
		}
		return true;
	}
	/**
	 * 自定义微信服务号菜单
	 * @param array $arrMenuData 格式如下
	 * $arrMenuData[0] = array('type'=>'click','name'=>'','key'=>'')
	 * $arrMenuData[1] = array('name'=>'','sub_button'=>array());
	 * $arrMenuData[1]['sub_buttion'][0] = array('type'=>'view','name'=>'','url'=>'');
	 * @return boolean false 创建失败，true 创建成功
	 */
	public function createMenu(array $arrMenuData)
	{
		$arrButton = array();
		$intButton = count($arrMenuData);
		if(empty($arrMenuData) || $intButton > 3)
		{
			$this->mError = '一级菜单数量为1-3个';
			return false;
		}
		
		for($i = 0; $i < $intButton; $i++)
		{
			if(isset($arrMenuData[$i]['sub_button']))
			{
				$intSubButton = count($arrMenuData[$i]['sub_button']);
				if(empty($arrMenuData[$i]['sub_button']) || $intSubButton > 5)
				{
					$this->mError = '二级菜单数量为1-5个';
					return false;
				}
				else
				{
					for($j = 0; $j < $intSubButton; $j++)
					{
						if(!$this->checkMenu($arrMenuData[$i]['sub_button'][$j],'sub_button', false))
						{
							return false;
						}
					}
				}
				if(!$this->checkMenu($arrMenuData[$i],'button', true))
				{
					return false;
				}
			}
			else
			{
				if(!$this->checkMenu($arrMenuData[$i],'button', false))
				{
					return false;
				}
			}
		}
		
		
		$arrButton['button'] = $arrMenuData;
		/*$arrButton = $this->convertStringCode($arrButton, 'GBK', 'UTF-8');*/
		$arrButton = $this->getUrlencode($arrButton);
		$jsonButton = urldecode(json_encode($arrButton));
		
		$mixedAccessToken = $this->getGlobalAccessToken();
		
		if($mixedAccessToken === false)
		{
			return false;
		}
		
		$strReqUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $mixedAccessToken;
		
		$mixedResponse = $this->curlRequest($strReqUrl,'post', $jsonButton);
		
		if($mixedResponse !== false)
		{
			$arrJson = $this->parseCurlResult($mixedResponse);
			
			if($arrJson['errcode'] == '0')
			{
				return true;
			}
			else
			{
				$this->mError = '创建菜单失败，错误号：' . $arrJson['errcode'] . ',错误信息：' . $arrJson['errmsg'];
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	/**
	 * 
	 * @param string $strRequestUrl 请求url
	 * @param string $strRequestType 请求类型
	 * @param string $aryRequestData 请求数据
	 * @param string $strProxy 代理主机地址
	 * @return mixed
	 */
	public function curlRequest($strRequestUrl, $strRequestType = 'get', $strRequestData = '', $strProxy = '')
	{
		$strProxy = trim($strProxy);
		$strUserAgent ='Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
		$resCh = curl_init(); /*初始化CURL句柄*/
		
		if(!empty($strProxy))
		{
			curl_setopt ($resCh, CURLOPT_PROXY, $strProxy);/*设置代理服务器*/
		}
		
		curl_setopt($resCh, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($resCh, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($resCh, CURLOPT_URL, $strRequestUrl);  /*设置请求的URL*/
		curl_setopt($resCh, CURLOPT_RETURNTRANSFER, 1);    /*设为TRUE把curl_exec()结果转化为字串，而不是直接输出*/
		
		if($strRequestType == 'post')
		{
			curl_setopt($resCh, CURLOPT_POST, 1);					  /*启用POST提交*/
			curl_setopt($resCh, CURLOPT_POSTFIELDS, $strRequestData); /*设置POST提交的字符串*/
		}
		
		curl_setopt($resCh, CURLOPT_TIMEOUT, self::CURL_EXCUTE_TIME); 				/*执行超时时间*/
		curl_setopt($resCh, CURLOPT_CONNECTTIMEOUT, self::CURL_CONNECT_WAIT_TIME); /*连接等待超时时间*/
		
		curl_setopt($resCh, CURLOPT_AUTOREFERER, 1);	/*自动生成referer*/
		curl_setopt($resCh, CURLOPT_USERAGENT, $strUserAgent);	/*HTTP请求User-Agent:头*/
		
		$mixedResponseData = curl_exec($resCh); 	/*执行预定义的CURL*/
		
		if($mixedResponseData === false)
		{
			$intErrorNo = curl_errno($resCh);
			$strErrorstr = curl_error($resCh);
			$this->mError = 'curl调用出错，错误号：' . $intErrorNo . ',错误信息：' . $strErrorstr;
		}
		curl_close($resCh);
		return $mixedResponseData;
		
	}
	
	/**
	 * 通过openId获取用户的基本信息
	 * @param string $strOpenId 微信用户相对于服务号的唯一ID 
	 * @return array 用户信息组成的数据
	 */
	public function getUserBaseInfo($strOpenId)
	{
		/*获取access_token*/
		$strAccessToken = $this->getGlobalAccessToken();
		
		if($strAccessToken === false)
		{
			return false;
		}
		
		$strUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='. $strAccessToken . '&openid=' . $strOpenId . '&lang=zh_CN';

		$mixedResponse = $this->curlRequest($strUrl, 'get');
		
		if($mixedResponse !== false)
		{
			$arrJson = $this->parseCurlResult($mixedResponse);
			
			if(isset($arrJson['errcode']))
			{
				$this->mError = '获取个人基本信息失败，错误号：' . $arrJson['errcode'] . ',错误信息：' . $arrJson['errmsg'];
				return false;
			}
			else
			{
				return $arrJson;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 
	 * @param mixed $mixedConvertData 需要转换编码的数据，可以是字符串也可以是数组
	 * @param string $strInCharset 原编码
	 * @param string $strOutCharset 待转换的编码
	 * @return mixed 编码转换之后的数据
	 */
	public function convertStringCode($mixedConvertData, $strInCharset='UTF-8', $strOutCharset='GBK')
	{
		if( !isset( $mixedConvertData ) )
		{
			return '';
		}
		if(is_array($mixedConvertData) )
		{
			if( empty( $mixedConvertData ) )
			{
				$mixedReturn = array();
			}
			else
			{
				foreach($mixedConvertData as $key=>$value)
				{
					$mixedReturn[$key] =$this->convertStringCode($value, $strInCharset, $strOutCharset);
				}
			}
		}
		else
		{
			$mixedReturn = iconv($strInCharset, $strOutCharset.'//TRANSLIT', $mixedConvertData);
		}
		return $mixedReturn;
	}
	
	/**
	 * 对数据进行urlencode,支持数组
	 * @param mixed $mixedConvertData 需要进行urlencode的数据
	 * @return string
	 */
	public function getUrlencode($mixedConvertData)
	{
		if( !isset( $mixedConvertData ) )
		{
			return '';
		}
		if(is_array($mixedConvertData) )
		{
			if( empty( $mixedConvertData ) )
			{
				$mixedReturn = array();
			}
			else
			{
				foreach($mixedConvertData as $key=>$value)
				{
					$mixedReturn[$key] =$this->getUrlencode($value);
				}
			}
		}
		else
		{
			$mixedReturn = urlencode($mixedConvertData);
		}
		return $mixedReturn;
	}
	
	/**
	* 验证请求是否来源于微信服务器
	* * return bool true-请求来源于微信，false-非法请求
	*/
	public function checkSignature()
	{
		$strSignature = isset($_GET["signature"]) ? $_GET["signature"] : '';
		$strTimestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
		$strNonce	  = isset($_GET["nonce"]) ? $_GET["nonce"] : '';

		$arrRequest = array(WEIXIN_TOKEN, $strTimestamp, $strNonce);
		sort($arrRequest, SORT_STRING);
		$strRequest = implode( $arrRequest );
		$strEncryptRequest  = sha1( $strRequest );

		if( strcmp($strEncryptRequest, $strSignature) == 0 )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}

?>