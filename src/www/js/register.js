$(function(){
	window.onload = function(){
		document.getElementById('regForm').reset();
	}
	
	$('#j-mobile').blur(function(){
		var val = $(this).val();
		if(val == ''){
			showMsg(this,'请输入手机号');
			$('#j-getCode').addClass('disabled');
			$('#j-code').addClass('disabled').attr('disabled',true);
			return false;
		}
		if (!isMobile(val)) {
			showMsg(this,'手机号格式错误');
			$('#j-getCode').addClass('disabled');
			$('#j-code').addClass('disabled').attr('disabled',true);
			return false;
		}
		showMsg(this,'right');
		$('#j-getCode').removeClass('disabled');
		$('#j-code').removeClass('disabled').attr('disabled',false);
	});
	
	$('#j-code').blur(function(){
		var val = $(this).val();
		if(val.length == 0){
			showMsg(this,'请输入手机验证码');
			return false;
		}
		showMsg(this,'right');
	});
	
	$('#j-pwd').blur(function(){
		var val = $(this).val();
		if(val.length == 0){
			showMsg(this,'请输入密码');
			return false;
		}
        if (!checkPwd(val)) {
			showMsg(this,'密码至少为6位数字、字母的组合');
			return false;
		}
		showMsg(this,'right');
	});
    
    $('#j-repwd').blur(function(){
		var val = $(this).val();
		if(val.length == 0){
			showMsg(this,'请输入密码');
			return false;
		}
        if(val != $("#j-pwd").val()) {
            showMsg(this,'密码不一致');
			return false;
        }
		showMsg(this,'right');
	});
	
	$('#submitBtn').on('click',function(){
		$('.form-input').blur();
		if($('.msg-error').length > 0) return false;
		$.ajax({
			type: 'POST',
			url: '/register/do/',
            dataType: 'json',
			data: {
                phone: $("#j-mobile").val(),
                code: $("#j-code").val(),
                password: $("#j-pwd").val(),
                repassword: $("#j-repwd").val()
            },
			success: function (data) {
				if(data.status != 0) {
                    alert(data.info ? data.info : '注册失败，请稍后重试');
                    return false;
                } else {
                    alert('注册成功');
                    location.href = '/buy/';
                }
			}
		});  
	});
	
	$('#j-getCode').click(function(){
		if($(this).hasClass('disabled')) return false;
			
		/*发送验证码到手机*/
		$.ajax({
			type: 'POST',
			async: false,
            dataType: 'json',
			url: '/ajax/sendmessage/',
			data: {
                phone: $("#j-mobile").val()
            },
			success: function (data) {
				//code...
				codeWait(120);
			}
		});
	});
	
});

function checkPwd(pwd) {
    var filterPwd = /^[0-9a-zA-Z\-\.]{6,}$/;
	if (filterPwd.test(pwd)) {
		return true;
	}
	return false;
}

function isMobile(mobile) {
	var filterMobile = /^1\d{10}$/;
	if (filterMobile.test(mobile)) {
		return true;
	}
	return false;
}

function showMsg(obj,info){
	var cname = '', msg = '';
	if(info == 'right'){
		cname = 'msg-right';
	}else{
		cname = 'msg-error';
		msg = info;
	}
	$(obj).parents('.form-group').find('.msg').removeClass('msg-right msg-error').addClass(cname).html('<i></i>' + msg);
}

function codeWait(time) {
	$('#j-getCode').addClass("disabled");
	var timer = setInterval(function (){
		$('#j-getCode').html(time + '秒后重新获取');
		if (time > 0) {
			time--;
		}else{
			clearInterval(timer);
			$('#j-getCode').html('获取验证码').removeClass("disabled");
		}
	}, 1000);
}