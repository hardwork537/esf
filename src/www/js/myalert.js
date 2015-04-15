/**
 * 用div模仿alert弹窗
 */

window.myalert = function() {
	var msg = arguments[0] ? arguments[0] : '';//提示文本
	var html;
	if(msg == '您尚未登录！'){
		html='<h5 class="title"><em class="icon icon_tips"></em>'+msg+'</h5><p class="mt20"><a href="http://i.focus.cn/login" onclick="$(\'#alert_popupCure\').hide();" class="btn btn2 mr10" target="_blank">登&nbsp;&nbsp;录</a><span class="c_txt2">没有账号？</span>&nbsp;<a href="http://i.focus.cn/reg" onclick="$(\'#alert_popupCure\').hide();" class="txt_link2" target="_blank">立即注册</a></p>';
	}
	if(msg == '反馈成功！'){
		html='<h5 class="title"><em class="icon icon_sure"></em>'+msg+'</h5><p class="mt20"><a onclick="$(\'#alert_popupCure\').hide();" href="javascript:void(0);" class="btn btn2 mr10">确&nbsp;&nbsp;定</a></p>';
	}
	if(msg == '请输入反馈内容！'){
		html='<h5 class="title"><em class="icon icon_tips"></em>'+msg+'</h5><p class="mt20"><a onclick="$(\'#alert_popupCure\').hide();" href="javascript:void(0);" class="btn btn2 mr10">确&nbsp;&nbsp;定</a></p>';
	}
	if( !document.getElementById('alert_popupCure') ){
		$("body").append(
				'<div class="popup" id="alert_popupCure">'+
					'<div class="mask"></div>'+
					'<div class="popBox popup_feedback">'+
						'<div class="hd clearfix"><h5 class="fl f14 fb">用户反馈</h5><a class="fr icon icon_close mt10" href="javascript:void(0);" onclick="$(\'#alert_popupCure\').hide();" title="关闭"></a></div>'+
						'<div class="bd clearfix">'+html+'</div></div>');
	}
	$("#alert_popupCure").show(0, setMyMask);
	$("#alert_popupCure .bd").show().html(html);
	return this;
}
function setMyMask() {
	if(navigator.userAgent.indexOf("MSIE 6.0") > 0) {
        $('#alert_popupCure .mask').css({
			'position' : 'absolute',
			'height' : $(document).height()
		});
        var top = $(document).scrollTop();
		$('#alert_popupCure .popBox').css({
            "position" : "absolute",
            "top" : top + 150
        });
        $(window).scroll(function() {
			var top = $(this).scrollTop();
			$('#alert_popupCure .popBox').css({
	            "top" : top + 150
	        });
		});
	}
}
