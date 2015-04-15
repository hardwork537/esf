var reg_email = /^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
var reg_phone = /^[1][3|4|5|8]\d{9}$/;
var reg_qq = /^[1-9][0-9]{4,12}$/;
var email = '';
$(function(){
 	email = $.trim($("#person_email").val());
	
	/* focus and blur */
	$("input[type='text']").focus(function () {
		var focusmsg = $(this).attr("focusmsg");
		$(this).next("span").html(focusmsg).removeClass().addClass("notice");
	})
	
	/* 邮箱 */
	$("#person_email").blur(function () {
		checkPersonalMail($(this));
	});
	
	/* 手机 */
	$("#phone").blur(function () {
		checkPersonalPhone($(this));
	});
	
	/* QQ */
	$("#contact_way").blur(function(){
		checkPersonalContactWay($(this));
	});
	
	/* 重填时 form的提示信息也同时还原 */
	$("#qx").click(function(){
		$(".form_group span[role='tips']").html("").removeClass();
		$("#person_email").val($("#email_tmp").val());
		$("#phone").val($("#phone_tmp").val());
		$("#contact_way").val($("#contact_way_tmp").val());
		
		if(!$.trim($("#phone_tmp").val())){
			$("#phone").next("span").html("");//手机值为空，则绑定信息隐藏
		}else if($("#is_phone_bind_tmp").val() == 1){
			$("#phone").next("span").html('<i></i>已验证！').removeClass().addClass("right");
		}else{
			$("#phone").next("span").html('尚未验证！&nbsp;<a href="javascript:void(0)" onclick="person_bing_phone()">马上验证</a>&nbsp;验证后可用手机号找回密码或者登录！').removeClass().addClass("right");
		}
	})
	
	/* form 提交 */
	$("#formUser").submit(function(){
		//返回结果变量
		var res = true;
		if(checkPersonalMail($("#person_email")) === false){res = false;}
		res = get_submit_disable(res);
		if(checkPersonalPhone($("#phone")) === false){res = false;}
		res = get_submit_disable(res);
		if(checkPersonalContactWay($("#contact_way")) === false){res = false;}
		res = get_submit_disable(res);
		return res;
	});
	
})

function checkPersonalMail(str){
	var strVal = $.trim(str.val());
	if(strVal == ""){
		str.next("span").html("电子邮箱不能为空").removeClass().addClass("error");
		return false;		  
	}
	if(!reg_email.test(strVal)){
		str.next("span").html("电子邮箱格式不对").removeClass().addClass("error");
		return false;
	}
	if(strVal === email){
		return;
	}
	$.ajax({
		type: "GET",
		url: "/ajax/checkPersonEmail/?rnd="+Math.random()*5,
		data: "email=" + strVal,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				str.next("span").html("<i></i>").removeClass().addClass("right");
			}else{
				str.next("span").html("这个邮箱已被别人占用啦").removeClass().addClass("error");
				return false;
			}
		}
	});
}

function checkPersonalPhone(str){
	var strVal = $.trim(str.val());
	if(strVal == ''){
		str.next("span").html("").removeClass();
		return true;
	}
	if(!reg_phone.test(strVal)){	
		str.next("span").html("您输入的手机号码格式有误").removeClass().addClass("error");
		return false;
	}
	$.ajax({
		type: "GET",
		url: "/ajax/checkPersonPhone/?rnd="+Math.random()*5,
		data: "phone=" + strVal,
		dataType: "json",
		async: false,
		cache: false,
		success: function(data){
			if(data == 1){
				str.next("span").html('<i></i>已验证！').removeClass().addClass("right");//绑定信息显示
			}else{
				//data=0
				//手机修改 则显示未绑定信息 并 绑定信息显示
				str.next("span").html('<i></i>尚未验证！&nbsp;<a href="javascript:void(0)" onclick="person_bing_phone()">马上验证</a>&nbsp;验证后可用手机号找回密码或者登录！').removeClass().addClass("right");
			}
			//验证	
		}
	});
}

function checkPersonalContactWay(str){
	var strVal = $.trim(str.val());
	if(strVal == ''){
		str.next("span").html("").removeClass();
		return true;
	}
	if(!reg_qq.test(strVal)){
		str.next("span").html("您输入的QQ格式有误").removeClass().addClass("error");
		return false;
	}
	str.next("span").html("").removeClass().addClass("right");
}

/**
* 绑定手机 phone 绑定手机号，
* 手机号：修改资料时输入的手机号；
* @author yanfang
*/

//个人修改资料页（or 其他页） 点绑定手机 检测 并记录memcache中 提交用户已输入的信息
function person_bing_phone(){
	var params = '';
	var phone = $("#phone").val();
	params += 'phone='+ phone;
	$.ajax({
		type:"GET",
		url: "/ajax/checkPersonPhoneBind/?rnd="+Math.random()*5,
		data: params,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				window.location.href = '/index/phoneBind/';
			}else{
				alert("此手机号已与其它账户绑定,请更换后再绑定");
			}
		}
	});
}

/**
* 绑定手机 获取验证码
*/
function get_code_phone(){
	//actionVal == 'step1' 如果是绑定第一步，则成功时会跳转到第二步页面 否则不跳转（如：在输入验证码页再次点击发送验证码）
	var actionVal = arguments[0] ? arguments[0] : '';
	$.ajax({
		type:"GET",
		url: "/ajax/getPersonPhoneCode/?rnd="+Math.random()*5,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				if(actionVal == 'step1'){
					window.location.href = '/index/phoneBind/?action=step2';
				}else{
					if($("#span_disabled").length > 0 && $("#span_abled").length > 0){//倒计时显示处理
						$("#span_disabled").show();
						$("#span_abled").hide();
						timer = setInterval("CountDown()", 1000);
					}
				}
			}else{
				alert(data);
			}
		}
	});
}

var maxtime = 180;//倒计时 3分钟
/**
  * 绑定手机 发送验证码 倒计时
  */
function CountDown(){
	maxtime = maxtime - 1;
	document.getElementById("timer").innerHTML=maxtime;
	if(maxtime == 0){//时间到
		window.clearInterval(timer);
		maxtime = 180;
		document.getElementById("timer").innerHTML=maxtime;
		$("#span_disabled").hide();
		$("#span_abled").show();
	}
}


function get_submit_disable(res){
	if($("input[name='Submit1']").attr('disabled') === true){res = false;}
	return res;
}