$(document).ready(function(){
	//初始化
	var default_group_name = '';
	var default_group_id = '';
	var default_suggest = '<span id="0">请输入关键字/上下方向键选择</span>';
	//$("#house_name").val(default_group_name);
	//$("#dict_house_id").val(default_group_id);
	$("#suggest").append(default_suggest);
	
	//获得焦点显示提示
	$("#agent_name").focus(function(){
		//alert(1);
		//$("#suggest").fadeIn();
	});
	
	//失去焦点隐藏提示
	$("#agent_name").blur(function(){
		$("#suggest").fadeOut();
	});
	$("#agent_name").keyup(function(e){
		var house_name = $(this).val();
		
		//监听上方向键选择
//		if ( e.keyCode==38 ) {
//			var select_span = $("#suggest .sel");
//			if ( select_span.attr("id") > 0 ) {
//				select_span.removeClass("sel");
//				select_span.prev().addClass("sel");
//			} else {
//				$("#suggest span:last").addClass("sel");
//			}
//			return;
//		}
		//监听下方向键选择
//		if ( e.keyCode==40 ) {
//			var select_span = $("#suggest .sel");
//			if ( select_span.attr("id") > 0 ) {
//				select_span.removeClass("sel");
//				select_span.next().addClass("sel");
//			} else {
//				$("#suggest span:first").addClass("sel");
//			}
//			return;
//		}
		//监听回车事件
//		if ( e.keyCode==13 ) {
//			var select_span = $("#suggest .sel");
//			if ( select_span.attr("id") > 0 ) {
//				$("#dict_house_id").val(select_span.attr("id"));
//				$("#house_name").val(select_span.text());
//				select_span.removeClass("sel");
//				//$("#suggest").fadeOut();
//			}
//			//else {
//				//$("#house_name").val(default_group_name);
//				//$("#dict_house_id").val(default_group_id);
//				
//			//}
//			$("#suggest").fadeOut();
//			//return ;	
//		}
		
		//过滤部分不相关的按键
		switch ( e.keyCode ) {
			//case 13:
				case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		//请求前先将下拉选项数据清空
		$("#suggest").hide();
		$("#suggest").empty();
		if ( house_name.length > 0 ) {
			
			$.ajax({
				type: "POST",
				url: base_url+"ajax/getSuggestShop",
				data: "q_word=" + house_name,
				error: function(XMLResponse){
					alert(XMLResponse.responseText);
				},
				dataType: "json",
				success: function( result ){
					//请求前先将下拉选项数据清空
					$("#suggest").hide();
					$("#suggest").empty();
					if ( result!=null && result!="" ) {
						$("#suggest").fadeIn();
						//创建下拉列表
						$(result).each(function(i, n){
							$("#suggest").append('<span id="'+n.agent_id+'">'+n.agent_name+'&nbsp;('+n.agent_accname+')'+'</span>');
						});
						//追加交互样式
						$("#suggest span").mouseover(function(){
							$("#suggest span").removeClass("sel");
							$(this).addClass("sel");
						});
						//移除交互样式
						$("#suggest span").mouseout(function(){
							$(this).removeClass("sel");
						});
						//点击事件
						$("#suggest span").click(function(){
							  getagentname($(this).text());
							  $("#suggest").fadeOut();
						});
						$("#suggest").fadeIn();
					} else {
						$("#suggest").hide();	
					}
				}
			});
		} else {
			$("#suggest").fadeOut();
		}
	});
});