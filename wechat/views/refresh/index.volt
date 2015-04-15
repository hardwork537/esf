<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>手动刷新</title>
<link href="{{'wechat/css/common.css?20140717'|staticUrl }}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/list.css?20140717'|staticUrl }}" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]> 
<script src="{{'wechat/js/css3-mediaqueries.js?20140625'|staticUrl}}" type="text/javascript"></script>
<![endif]--> 
<script src="{{'wechat/js/zepto-1.1.3.min.js?20140625'|staticUrl}}" type="text/javascript"></script>
<script type="text/javascript">
	function onBridgeReady(){
		WeixinJSBridge.call('hideOptionMenu');
	}
	$(document).ready(function(){
		if (typeof WeixinJSBridge == "undefined"){
			if( document.addEventListener ){
				document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
			}else if (document.attachEvent){
				document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
				document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
			}
		}else{
			onBridgeReady();
		}
	});
</script>
</head>
<body>
<div class="head head_refresh">
{% if bolShowTab == 1 %}
    <ul class="toptab clearfix" style=" border-bottom:solid 1px #e3e3e3;">
       <li class="active"> <a> 出 售 </a> </li>
       <li> <a class="right"> 出 租 </a> </li>
    </ul>
{% endif %}
</div>
<div class="wrapper" {% if bolShowTab == 0 %} style="padding-top:0"{% endif %}>
	<div id="scroller">
		<div ut="{{ unittype }}">{% include "refresh/main.volt" %}</div>
		{% if bolShowTab == 1 %}
		<div style="display:none" ut="rent"></div>
		{% endif %}
		<img width="30" class="loading2" src="{{'wechat/images/loading2.gif'|staticUrl}}" style="display:none;"/>
	</div>
</div>

<div class="foot"> <a id="btn_up" class="btn btn_fresh turngray">刷新 ( 0/0 )</a> </div>

<div class="popBox">
   <p> <img width="30" src="{{'wechat/images/icon_done.png'|staticUrl}}" /> <span style=" margin-left:15px;" id="sp_msg"> 已刷新 </span></p>
</div>
<div class="popBox1">
   <p><span style=" margin-left:25px;" id="sp_msg1"></span></p>
</div>
</body>
</html>
