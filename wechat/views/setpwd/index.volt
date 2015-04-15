<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>设置新密码</title>
<link href="{{'wechat/css/common.css?20140717'|staticUrl}}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/login.css?20140630'|staticUrl}}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
<form class="form setpwd-form" id="setpwd-form" onsubmit="resetpwd();return false;">
    <div class="form-group">
        <label class="form-label" for="password">新密码</label>
        <input type="password" class="form-input" id="password" placeholder="输入新密码" autocomplete="off" data-role="新密码">
	</div>
    <div class="form-group">
        <label class="form-label" for="passwordSecond">确&nbsp;认</label>
        <input type="password" class="form-input" id="passwordSecond" placeholder="再次输入新密码" autocomplete="off" data-role="确认密码">
	</div>
	<input type="hidden" value="{{ broker_accname }}" id="broker_accname" />
	<input type="hidden" value="{{ broker_id }}" id="broker_id" />
	<input type="hidden" value="{{ openid }}" id="openid" />
	<input type="hidden" value="{{ t }}" id="t" />
	<input type="hidden" value="{{ token }}" id="token" />
	<input type="hidden" value="{{ tel }}" id="tel" />
    <button class="btn" type="submit">完&nbsp;&nbsp;成</button>
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
<script src="js/css3-mediaqueries.js" type="text/javascript"></script>
<![endif]--> 
<script src="{{'wechat/js/zepto-1.1.3.min.js?20140625'|staticUrl}}" type="text/javascript"></script>
<script src="{{'wechat/js/login.js?20140722'|staticUrl}}" type="text/javascript"></script>
<script type="text/javascript">
function resetpwd()
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
	else if(!isPwd($("#password").val()) || !isPwd($("#passwordSecond").val())){
		showPopup("密码仅限6-16位英文、数字");
		return false;
	}
	else if($("#password").val() != $("#passwordSecond").val())
	{
		showPopup("两次密码不一致 请再次确认");
		return false;
	}
	$.ajax({
			type: "POST",
			async: false,
			url: "{{HttpStaticUrl}}/setpwd",
			data: {"password":$("#password").val(), "passwordSecond":$("#passwordSecond").val(), "broker_accname":$("#broker_accname").val(), "broker_id":$("#broker_id").val(), "openid":$("#openid").val(), "t":$("#t").val(),"token":$("#token").val(),"tel":$("#tel").val()},
			success: function(data){
				var jsonData = eval('('+data+')');
				if(jsonData.status == 1)
				{
					window.location.href=jsonData.content;
				}
				else
				{
					showPopup(jsonData.content);
					return false;
				}
			}
		});	
}
</script>
</body>
</html>
