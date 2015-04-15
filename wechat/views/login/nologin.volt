<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>焦点通</title>
<link href="{{ "wechat/css/common.css"|staticUrl }}" rel="stylesheet" type="text/css" />
<link href="{{ "wechat/css/login.css"|staticUrl  }}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
    <div class="loginSuccess">
    	<!--<div class="title"><span class="icon-sure"><i></i></span><h1>已登录并绑定成功</h1></div>-->
        <p>{{showMsg}}</p>
    </div>
</div>
</body>
</html>
