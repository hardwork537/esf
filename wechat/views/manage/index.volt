<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>房源上下架</title>
<link href="{{'wechat/css/common.css?20140905'|staticUrl}}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/list.css?20140905'|staticUrl}}" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]> 
<script src="{{'wechat/js/css3-mediaqueries.js'|staticUrl}}" type="text/javascript"></script>
<![endif]--> 
<script src="{{'wechat/js/zepto-1.1.3.min.js?20140905'|staticUrl}}" type="text/javascript"></script>
<script src="{{'wechat/js/manage.js?20140915'|staticUrl}}" type="text/javascript"></script>
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
<body >
{% if bolShowTab == 1 %}
<div class="head head_updown">
    <ul class=" toptab clearfix">
       <li class=" active"> <a> 出 售 </a> </li>
       <li> <a class="right"> 出 租 </a> </li>
    </ul>
</div>
 {% endif %}
<div class="wrapper" {% if bolShowTab == 0 %}style="padding-top:0px;"{% endif %}>
	<div id="scroller">
		<div ut="{{ unittype }}">
			<ul class="toptab2 clearfix"> 
			  <li> <a> 已发布 <i> {{ intOnLineCount }} </i> </a> </li>
			  <li class="active"> <a> 待发布 <i> {{ intOffLineCount }} </i> </a> </li>
			</ul>
			<div class="tabshow"> 
			  <!--已发布  =============start -->
			  <ul class="houselist houselist2 clearfix" style="display:none;">
			  </ul>
			  <!--已发布  =============end -->
			  <!--待发布  =============start -->
			  <ul class="houselist clearfix">
					{% include "manage/offlinelist.volt" %}
			  </ul>
			  <!--待发布  =============end -->
			</div>
		</div>
	
{% if bolShowTab == 1 %}
    <div style="display:none" ut="rent"> 
		<ul class="toptab2 clearfix">
		  <li> <a> 已发布 <i> 0 </i> </a> </li>
		  <li class="active"> <a> 待发布 <i> 0 </i> </a> </li>
		</ul>
		<div class="tabshow"> 
		  <!--已发布  =============start -->
			<ul class="houselist houselist2 clearfix">
			</ul>
		  <!--已发布  =============end -->
		  <!--待发布  =============start -->
			<ul class="houselist clearfix"> 
			</ul>
		  <!--待发布  =============end -->
		</div>
	</div>
	<img width="30" class="loading2" src="{{'wechat/images/loading2.gif'|staticUrl }}" style="display:none;"/>
{% endif %}
	</div>

</div>
<input type="hidden" value="{{ leftOnLineNum }}" name="hdLeftOnlineNum" />
{% if bolShowTab == 1 %}
<input type="hidden" value="0" name="hdLeftOnlineNum"/>
{% endif %}
<div class="foot"> <a id="btn_up" class="btn btn_fresh turngray" onclick="unitUpAndDown();" ldimg="{{'wechat/images/loading.gif'|staticUrl}}"> 上 架 ( 0/{{ leftOnLineNum }} )</a> </div>
<div class="popBox1">
   <p><span style=" margin-left:20px;" id="sp_msg1"></span></p>
</div>
</body>
</html>