<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>登录焦点通</title>
<link href="{{'wechat/css/common.css'|staticUrl}}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/login.css'|staticUrl}}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
<form class="form login-form" id="login-form" onsubmit="bkLogin();return false;">
    <div class="form-group">
        <label class="form-label" for="userName">账&nbsp;号</label>
        <input type="text" class="form-input" id="userName" name="userName" placeholder="输入经纪人账号" autocomplete="off" data-role="账号">
	</div>
    <div class="form-group">
        <label class="form-label" for="passWord">密&nbsp;码</label>
        <input type="password" class="form-input" id="passWord" name="passWord" placeholder="输入密码" autocomplete="off" data-role="密码">
	</div>
    <button class="btn" type="submit">登&nbsp;&nbsp;录</button>
    <div class="forget-pwd-row"><a href="{{strForgetPwd}}openid={{strOpenId}}&token={{strToken}}">忘记密码？</a></div>
	<input type="hidden" value="{{strOpenId}}" name="openid" id="openid"/>
	<input type="hidden" value="{{strToken}}" name="token" id="token" />
</form>
</div>
<div class="popup" id="loginPopup" style="display:none;">
	<div class="popmask"></div>
	<div class="popBox loginPop">
        <p class="text"></p>
        <a href="javascript:void(0);" class="popBtn">确&nbsp;认</a>
	</div>
</div>

<!--[if lt IE 9]> 
<script src="{{'wechat/js/css3-mediaqueries.js'|staticUrl}}" type="text/javascript"></script>
<![endif]--> 
<script src="{{'wechat/js/zepto-1.1.3.min.js'|staticUrl}}" type="text/javascript"></script>
<script src="{{'wechat/js/login.js'|staticUrl}}" type="text/javascript"></script>
<script type="text/javascript">
function bkLogin()
{
	var msg = "请填写";
	var myarr = [];
	$(".form-input").each(function(){
		if($(this).val() == ''){
			myarr.push($(this).attr("data-role"));
		}
	})
	
	if(myarr.length > 0){
		msg += myarr.join("、");
		showPopup(msg);
		return false;
	}
	else
	{
		$.ajax({
			type: "POST",
			async: false,
			data: {"do":"login", "openid":$("#openid").val(), "token":$("#token").val(), "password":$("#passWord").val(), "username":$("#userName").val()},
			url: "{{HttpStaticUrl}}/login/reqlogin",
			success:function(data){
				var jsonobj=eval('('+data+')');
				if(jsonobj.status < 1)
				{
					showPopup(jsonobj.content);
				}
				else
				{
					window.location.href = jsonobj.content;
				}
			}
		});
	}
}
</script>
</body>
</html>
