$(document).ready(function(){
	$.ajax({
		type: "POST",
		url: "http://esf.focus.cn/ajax/getCheckLogin",
		dataType: "json",
		success: function( result ){
			if ( result!=null && result!="" ) {
				$("#beforeLogin").remove();
				$("#afterLogin").show().html('您好!'+'<a href="http://vip.esf.focus.cn/login.php">'+result.ent_name+'</a> '+' <a href="http://vip.esf.focus.cn/user/logout/">退出</a>');
			}
		}
	});
});
