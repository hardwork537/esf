<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>分享房源</title>
<link href="{{'wechat/css/common.css?20140717'|staticUrl}}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/list.css?20140717'|staticUrl}}" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]> 
<script src="{{'wechat/js/css3-mediaqueries.js|asseturl'|staticUrl}}" type="text/javascript"></script>
<![endif]--> 
<script src="{{'wechat/js/zepto-1.1.3.min.js?20140625'|staticUrl}}" type="text/javascript"></script>
<script src="{{'wechat/js/share.js?20140723'|staticUrl}}" type="text/javascript"></script>
</head>
<body>
<div class="head head_share">
 {% if bolShowTab == 1 %}
    <ul class=" toptab clearfix" style=" border-bottom:solid 1px #e3e3e3;">
       <li id="li_sale" class="active"> <a id="a_sale"> 出 售 </a> </li>
       <li id="li_rent"> <a id="a_rent" class="right"> 出 租 </a> </li>
    </ul>
{% endif %}
</div>
<div class="wrapper" style="{% if bolShowTab == 0 %}padding-top:0;{% endif %}padding-bottom:0;">
	<div id="scroller">
		<ul class="houselist houseshare houselist2 clearfix">
                {% include "share/list.volt" %}
		</ul>
		{% if bolShowTab == 1 %}
		<ul class="houselist houseshare houselist2 clearfix" style="display:none;">
		</ul>
		{% endif %}
		<img width="30" class="loading2" src="{{'wechat/images/loading2.gif'|staticUrl}}" style="display:none;"/>
	</div>
</div>
</body>
</html>
