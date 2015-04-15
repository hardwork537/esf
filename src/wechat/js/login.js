function showPopup(msg){
	$("#loginPopup p.text").text(msg);
	$("#loginPopup").show();
	var h = $(".popBox").height()/2;
	$(".popBox").css('margin-top','-'+h+'px');
	$(".popBtn").click(function(){
		closePopup("#loginPopup");
	});
}


function closePopup(obj) {
	$(obj).hide();
	$('html').unbind('mousewheel');
	//火狐下的鼠标滚动事件
	$('html').unbind('DOMMouseScroll');
}

function scrollFunc(e){
	e=e||window.event;
	if (e&&e.preventDefault){
		e.preventDefault();
		e.stopPropagation();
	}else{ 
		e.returnvalue=false;  
		return false;     
	}
}

function isEmail(email){
	var filterEmail=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; 
	if(filterEmail.test(email)){
		return true;
	}
	return false;
}

function isMobile(mobile){
	var filterMobile=/^((13[0-9])|(15[^4,\D])|(18[0,2,3,5-9]))\d{8}$/;
	if(filterMobile.test(mobile)){
		return true;
	}
	return false;
}

function isPwd(pwd){
	var filterPwd=/^[a-z0-9A-Z]{6,18}$/;
	if(filterPwd.test(pwd)){
		return true;
	}
	return false;
}

var wait=60; 
function mobileCodeTime(t) { 
	if (wait == 0){ 
		$('#mobile-code-get').click(sendCode);
		$(t).text("获取验证码"); 
		$(t).removeClass("mobile-code-get-disable");
		$(t).addClass("mobile-code-get");
		wait = 60; 
		$("#mobileTips").hide();
	} else { 
		$('#mobile-code-get').unbind('click');
		$(t).text(wait+"秒重新获取"); 
		$(t).removeClass("mobile-code-get");
		$(t).addClass("mobile-code-get-disable");
		wait--; 
		setTimeout(function() { 
			mobileCodeTime(t) 
		}, 
		1000) 
	} 
} 

window.showMobile = function(mobile){
	mobile = mobile.substr(0, 3) + '****' + mobile.substr(7, 4);
	if(!document.getElementById("mobileTips")){
		$("body").append(
			'<div class="mobile-tips" id="mobileTips">'+
    			'<div class="mobile-tips-l"><span class="icon-warning"><i></i></span></div>'+
			    '<div class="mobile-tips-r">已向'+'<span class="mobile-text">'+mobile+'</span>'+'发送短信，请在输入框中填写短信验证码</div>'+
			'</div>'
		);
	}
	$("#mobileTips").show();
	$("#mobileTips .mobile-text").show().text(mobile);
	return this;
}
