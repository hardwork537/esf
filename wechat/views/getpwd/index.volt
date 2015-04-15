<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>找回密码</title>
<link href="{{'wechat/css/common.css?20140717'|staticUrl}}" rel="stylesheet" type="text/css" />
<link href="{{'wechat/css/login.css?20140630'|staticUrl}}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
<form class="form getpwd-form" id="getpwd-form" onsubmit="doChkCode();return false;">
    <div class="form-group">
        <label class="form-label" for="userName">账&nbsp;号</label>
        <input type="text" class="form-input" id="userName" placeholder="输入经纪人账号" autocomplete="off" data-role="账号">
	</div>
    <div class="form-group-mobile">
    	<div class="form-group">
            <label class="form-label" for="mobile">手机号</label>
            <input type="text" class="form-input" id="mobile" placeholder="输入手机号" autocomplete="off" data-role="手机号">
        </div>
        <a class="mobile-code-get" id="mobile-code-get" href="javascript:;">获取验证码</a>
	</div>
    <div class="form-group">
        <label class="form-label" for="mobileCode">验证码</label>
        <input type="text" class="form-input" id="mobileCode" placeholder="输入验证码" autocomplete="off" data-role="验证码">        
	</div>
    <div class="mobileCode-error">
        <div class="mobileCode-error-l"><span class="icon-warning-s"><i></i></span></div>
        <div class="mobileCode-error-r">验证码错误</div>
    </div>
	<input type="hidden" value="{{ openid }}" id="openid" />
	<input type="hidden" value="{{ token }}" id="token" />
    <button class="btn" type="submit">下一步</button>
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

$(function(){
	$('#mobile-code-get').click(sendCode);
});
function sendCode()
{
	if($('#userName').val() == '')
	{
		showPopup("请填写账号");
		return false;
	}
	if($('#mobile').val() == ''){
		showPopup("请填写手机号");
		return false;
	}
	else if(!isMobile($('#mobile').val())){
		showPopup("手机号格式不正确，检查下吧");
		return false;
	}
	else{
		var currentobj = this;
		/*发送验证码到手机*/
		$.ajax({
			type: "POST",
			async: false,
			url: "{{HttpStaticUrl}}/getpwd",
			data: {"do":'sendcode', "openid":$("#openid").val(), "token":$("#token").val(), "broker_accname":$("#userName").val(), "telephone":$("#mobile").val()},
			success: function(data){
				var jsonData = eval('('+data+')');
				if(jsonData.status == '1')
				{
					mobileCodeTime(currentobj);
					showMobile($('#mobile').val());
				}
				else
				{
					showPopup(jsonData.content);
				}
			}
		});
	}
}
function doChkCode()
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
	else{
		//不为空的情况下
		if(!isMobile($('#mobile').val())){
			showPopup("手机号格式不正确，检查下吧");
			msgFlag = 1;
			return false;
		}
		$.ajax({
			type: "POST",
			async: false,
			url: "{{HttpStaticUrl}}/getpwd",
			data: {"do":'checkcode', "openid":$("#openid").val(), "token":$("#token").val(), "broker_accname":$("#userName").val(), "telephone":$("#mobile").val(),"verifyNum":$("#mobileCode").val()},
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
}
</script>
</body>
</html>
