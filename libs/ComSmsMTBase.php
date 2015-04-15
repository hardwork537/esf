<?php
/**
 * @abstract 短信下行方法类
 * @author By jackchen
 * @date 2014-09-01
 */

require_once __DIR__.'/../config/config.inc.php';

class ComSmsMTBase extends  ComSocket {
	/**
	 * 代理url
	 * @var unknown_type
	 */
	protected $_proxy_url = "http://10.11.162.28/sms/inf_sms_send.php";
	/**
	 * 代理host
	 * @var unknown_type
	 */
	protected $_proxy_host = '';
	
    /**
     * MT接口地址
     */
    protected $_host = "middle.sms.sohu.com";

    /**
     * MT接口端口
     */
    protected $_port = 10000;

    /**
     * 一些参数的配置
     */
    protected $_config = array();

    /**
     * 目标手机号码，可为多个
     */
    private $_dest_numbers = array();
    /**
     * 目标手机发送信息
     *
     * @var string
     */
    private $_dest_message = '';

    public function __construct($config = array()) {
        $this->_config = empty($config) ? $this->getDefaultConfig() : $config;
    }

    public function getDefaultConfig(){
        return $GLOBALS['CONFIG_MT_DEFAULT'];
    }
    
    /**
     * 群发短信时，设置要群发的手机号码，返回数组
     */
    public function setDestNumbers($dest_numbers) {
        $this->_dest_numbers = $dest_numbers;
        return $this;
    }
     /**
      * 设置sms内容
      *
      * @param string $msg
      * @return object
      */
    public function setMessage($msg) {
        $this->_dest_message = $msg;
        return $this;
    }
    /**
     * 获取当前sms内容
     *
     * @return string
     */
    public function getMessage() {
        return $this->_dest_message ;
    }

    /**
     * 构造请求字符串，这个字符串是通过fwrite发送到服务器的
     *
     * @param array $info 请求信息组成的数组
     * @return string 构造的HTTP请求字符串
     */
    public function buildRequestString($destMobile,$message) { 
        //检测网关类型
        $sohuMoHost = $this->_host;
        $sohuMoPort = $this->_port;

        $appid     = $this->_config['appid'];
        $srcMobile = $this->_config['srcMobile'];

        $column_id = $this->_config['column_id'];
        $link_id   = $this->_config['link_id'];
        $Key       = $this->_config['key'];

        $Command = "10";
        $Appid = $appid;
        $PackLen = 546 + strlen($message);
        $Routeid = "501";
        $Columnid = $column_id; // 产品ID
        $Msgtype = "4"; // 4=订阅下发
        $GivenFee = "0"; // 以分为单位每条短信向用户赠送的话费(联通使用),填0
        $FirstMoMt = "2"; // 免费下发填2
        $ChargeWho = "3"; // 对谁计费，0对目的，1对源，2对ISP，3以ChargeNumber为准
        $ChargeNumber = $destMobile;
        $ChargeNumberType = "0"; // 号码类型 （1）0 真实号码  （2） 1 伪码。默认添0
        $SrcNumber = $srcMobile;
        $DestNumCount = "1";
        $DestNumber = $destMobile;
        $DestNumberType = "0"; // 号码类型 （1）0 真实号码  （2） 1 伪码，默认添0
        $AtTime = "";
        $ValidTime = "";
        $MsgFmt = "515"; // 短信息数据格式：0,ASCII串3,写卡4,bin8：UCS2 15：汉字 ，默认添15,免费的业务代码,msgFmt为515
        $ContentLen = strlen($message);
        $Content = $message;
        $BinContent = "";
        $LinkId = $link_id;
        $ComeFrom = $appid;
        $Priority = "0";
        $Md5 = md5($Appid . $Columnid . $ChargeNumber . $SrcNumber . $DestNumber . $Content . $ComeFrom . $Key);

        $info = array(
            'Command'          => sprintf("%-2s", $Command),
            'Appid'            => sprintf("%-6s", $Appid),
            'PackLen'          => sprintf("%-4s", $PackLen),
            'Routeid'          => sprintf("%-4s", $Routeid),
            'Columnid'         => sprintf("%-4s", $Columnid),
            'Msgtype'          => sprintf("%-4s", $Msgtype),
            'GivenFee'         => sprintf("%-4s", $GivenFee),
            'FirstMoMt'        => sprintf("%-4s", $FirstMoMt),
            'ChargeWho'        => sprintf("%-4s", $ChargeWho),
            'ChargeNumber'     => sprintf("%-21s",$ChargeNumber),
            'ChargeNumberType' => sprintf("%-4s", $ChargeNumberType),
            'SrcNumber'        => sprintf("%-21s", $SrcNumber),
            'DestNumCount'     => sprintf("%-4s", $DestNumCount),
            'DestNumber'       => sprintf("%-21s", $DestNumber),
            'DestNumberType'   => sprintf("%-4s" , $DestNumberType),
            'AtTime'           => sprintf("%-20s", $AtTime),
            'ValidTime'        => sprintf("%-20s", $ValidTime),
            'MsgFmt'           => sprintf("%-4s" , $MsgFmt),
            'ContentLen'       => sprintf("%-4s" , $ContentLen),
            'Content'          => $Content,
            'BinContent'       => sprintf("%-320s", $BinContent),
            'LinkId'           => sprintf("%-20s" , $LinkId),
            'ComeFrom'         => sprintf("%-11s" , $ComeFrom),
            'Priority'         => sprintf("%-4s"  , $Priority),
            'Md5'              => sprintf("%-32s" , $Md5)
        );
        $msg = implode('', $info);

        //var_dump($info);

        // $msg = sprintf("%- 2s%- 6s%- 4s%- 4s%- 4s%- 4s%- 4s%- 4s%- 4s%- 21s%- 4s%- 21s%- 4s%- 21s%- 4s%- 20s%- 20s%- 4s%- 4s%s%- 320s%- 20s%- 11s%- 4s%- 32s",
        //     $Command,$Appid,$PackLen,$Routeid,$Columnid,$Msgtype,$GivenFee,$FirstMoMt,$ChargeWho,$ChargeNumber,
        //     $ChargeNumberType,$SrcNumber,$DestNumCount,$DestNumber,$DestNumberType,$AtTime,$ValidTime,$MsgFmt,
        //     $ContentLen,$Content,$BinContent,$LinkId,$ComeFrom,$Priority,$Md5);

        return $msg;
    }

    /**
     * 主动下行短信，外部调用此接口发送短信
     * @param string $destMobile 目标手机
     * @message 消息
     * 
     */
    public function focusSubmit($destMobile,$message='') {
        if (empty($destMobile)) return -1;

        if (empty($message)) {
            $message = $this->getMessage();
        }
        if (empty($message)) {
            return -1;
        }
        //增加环境区分
        if(defined("_SYSTEM_TITLE_")) {
        	$message = _SYSTEM_TITLE_.$message;
        }
		$response = Curl::GetResult($this->_proxy_url, 'destMobile='.$destMobile.'&message='.$message, '', 10, 0, $this->_proxy_host);

		if($response != false){
			$response = json_decode($response, true);
			if($response['errno'] == '0'){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}

        //return $response;
    }
    
    /**
     * 发送搜狐的短信端口
     * @param $destMobile
     * @param $message
     */
    public function focusSubmitByProxy($destMobile,$message=''){
    	if (empty($destMobile)) return -1;

        if (empty($message)) {
            $message = $this->getMessage();
        }
        if (empty($message)) {
            return -1;
        }

        $requestStr = $this->buildRequestString($destMobile, $message);
        $response = parent::request($requestStr);
        $this->disconnect();
        return $response;
    }
}
?>