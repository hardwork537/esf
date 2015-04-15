$(document).ready(function() {
	//入口事件
	var url = document.location.href.replace(/sch([0-9_]+)\//gi, '');
	//学校搜索下拉框
	$("#searchData").keyup(function(e){
		var sVal= $(this).val();
		if( null == sVal || "" == sVal ){
			$("#suggest_school").hide();
			return false;
		}
		//过滤部分不相关的按键
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		if ( sVal.length>0 ) {
			// var dateTime=new Date();
			// var randomNum = String(dateTime.getSeconds())+String(dateTime.getMinutes());
			$.ajax({
				type: "POST",
				url: "/ajax/school?r="+Math.random(),
				data: "q_word=" + sVal + "&q_type=" + $(this).attr('school_type'),
				dataType: "json",
				success: function(result){
					$("#suggest_school").empty();
					if ( result != null )
                    {
						$.each(result, function(i,n){ //为了兼容万恶的IE6 写了两遍
							$('<li><a href="'+n.url+'">'+n.school_name+'</a></li>').appendTo("#suggest_school");
						});
					}else {
						$("#suggest_school").html('<li><a href="JavaScript:void(0)">没有找到该学校</a></li>');
					}
					$("#suggest_school").fadeIn(10);
				}
			});
		}
	});
});
