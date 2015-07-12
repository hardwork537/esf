$(function(){
	window.onload = function(){
		document.getElementById('loginForm').reset();
	}
	
	$('#j-mobile').blur(function(){
		var val = $(this).val();
		if(val == ''){
			showMsg(this,'请输入手机号');
			return false;
		}
		if (!isMobile(val)) {
			showMsg(this,'手机号格式错误');
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
		showMsg(this,'right');
	});
	
	$('#submitBtn').on('click',function(){
		$('.form-input').blur();
		if($('.msg-error').length > 0) return false;
		$.ajax({
			type: 'POST',
			url: '/login/do/',   
			data: {
                phone: $("#j-mobile").val(),
                password: $("#j-pwd").val()
            },
            dataType: 'json',
			success: function (data) {
				if(data.status != 0) {
                    alert(data.info ? data.info : '登录失败');
                } else {
                    location.href = '/my/';
                }
			}
		});  
	});
	
});


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
