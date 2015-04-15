<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>焦点通</title>
<link href="{{'wechat/css/common.css'|staticUrl}}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/login.css'|staticUrl}}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
    <div class="loginSuccess">
    	{% if success == 1 %}
		<div class="loginSuccess">
			{% if fromlogin == 0 %}
				<div class="title"><span class="icon-sure"><i></i></span><h1>已登录并绑定成功</h1></div>
				<p>即刻返回，获得焦点通便捷服务</p>
			{% else %}
				<div class="title"><span class="icon-sure"><i></i></span><h1>亲，不需要重新登录哦</h1></div>
				<p>你的账号已经登录并绑定成功了</p>
			{% endif %}
		</div>
		<!--<p></p>-->
	{% else %}
		<div class="title"><span class="icon-sure"><i></i></span><h1>非法请求</h1></div>
		<!--<p></p>-->
	{% endif %}
    </div>
</div>
</body>
</html>
