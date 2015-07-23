$(function(){
	window.onload = function(){
        if(document.getElementById('profileForm'))
            document.getElementById('profileForm').reset();
	}
	
    //取消收藏
	$('.j-favorite').click(function(){
        var jFavorite = $(this);
        $.ajax({
			type: 'POST',
			url: '/ajax/delfav/',   
			data: {
                houseId: $(this).parent("li").attr('houseId')
            },
            dataType: 'json',
			success: function (data) {
				if(data.status != 0) {
                    alert(data.info ? data.info : '取消收藏失败');
                } else {
                    if(jFavorite.hasClass('favorite-on'))
                        jFavorite.removeClass('favorite-on');
                    
                    location.reload(false);
                }
			}
		});          		
	});
    
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
	
    $('#modifyPwd').on('click',function(){
		$(this).hide();
        $("span.modify-pwd").show().find("input").val('');
	});
    
    function checkPwd(pwd) {
        var filterPwd = /^[0-9a-zA-Z\-\.]{6,}$/;
        if (filterPwd.test(pwd)) {
            return true;
        }
        return false;
    }

	$('.btn-submit').on('click',function(){
        if($("span.modify-pwd").is(":visible")) {
            var oldPwd = $("#j-oldpwd").val();
            var pwd = $("#j-pwd").val();
            var rePwd = $("#j-repwd").val();
            if(oldPwd || pwd || rePwd) {
                if(!oldPwd) {
                    alert('请输入旧密码');
                    return false;
                }
                if(!pwd) {
                    alert('请输入新密码');
                    return false;
                } else if(!checkPwd(pwd)) {
                    alert('新密码至少为6位字母、数字、其他字符组合');
                    return false;
                }
                if(!rePwd) {
                    alert('请再次输入新密码');
                    return false;
                } else if(pwd != rePwd) {
                    alert('两次密码输入不一致');
                    return false;
                }
            }
        }
		$('.form-input').blur();
		if($('.msg-error').length > 0) return false;
		
		$.ajax({
			type: 'POST',
			url: '/my/modifyuser/',   
			data: {
                name: $("#j-username").val(),
                sex: $("input[name='sex']:checked").val(),
                phone: $("#j-mobile").val(),
                oldPwd: $("#j-oldpwd").val(),
                pwd: $("#j-pwd").val(),
                rePwd: $("#j-repwd").val()
            },
            dataType: 'JSON',
			success: function (data) {
				if(0 == data.status) {
                    location.reload(false);
                } else {
                    alert(data.info ? data.info : '修改失败');
                    return false;
                }
			}
		});  
	});
	
    if($('.btn-reset')) {
        $('.btn-reset').on('click',function(){
            document.getElementById('profileForm').reset();
            $('.form-group .msg').removeClass('msg-error msg-right').html('').hide();
        });
    }	
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
