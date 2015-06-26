$(function(){
	window.onload = function(){
		document.getElementById('profileForm').reset();
	}
	
	$('#j-username').blur(function(){
		var val = $(this).val();
		if(val == ''){
			showMsg(this,'请输入姓名');
			return false;
		}
	});
	
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
	
	$('.btn-submit').on('click',function(){
		$('.form-input').blur();
		if($('.msg-error').length > 0) return false;
		
		$.ajax({
			type: 'POST',
			url: '',   
			data: '',
			success: function (data) {
				// success code...
			}
		});  
	});
	
	$('.btn-reset').on('click',function(){
		document.getElementById('profileForm').reset();
		$('.form-group .msg').removeClass('msg-error msg-right').html('').hide();
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
