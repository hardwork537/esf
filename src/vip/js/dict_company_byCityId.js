$(document).ready(function(){
	//初始化
	var default_group_name = '';
	var default_group_id ;
	var default_suggest = '';
	//$("#company_name").val(default_group_name);
	$("#company_id").val(default_group_id);
	$("#suggest").append(default_suggest);
	//获得焦点显示提示
	
	  xz_city_id = $("#city_id").val();
	
	//获得焦点显示提示
	$("#company_name").focus(function(){
		$("#suggest").fadeIn();
	});
	//失去焦点隐藏提示
	$("#company_name").blur(function(){
		$("#suggest").fadeOut();
	});
	$("#company_name").keyup(function(e){
		var company_name = $(this).val();
	
		//监听回车事件
		if ( e.keyCode==13 ) {
			var select_span = $("#suggest .sel");
			if ( select_span.attr("id") > 0 ) {
				$("#company_id").val(select_span.attr("id"));
				$("#company_name").val(select_span.text());
				select_span.removeClass("sel");
				$("#suggest").fadeOut();
			} else {
				$("#company_name").val(default_company_name);
				$("#company_id").val(default_group_id);
				$("#suggest").fadeOut();
			}
			return;
		}
		//过滤部分不相关的按键
		switch ( e.keyCode ) {
			case 13:case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		//请求前先将下拉选项数据清空
		$("#suggest").empty();
		if ( company_name.length > 0 ) {
			$.ajax({
				type: "POST",
				url: "ajax/get_dict_company_bycity.json.php",
				data: "q_word=" + encodeURIComponent(company_name) + "&xz_city_id=" + xz_city_id,
				dataType: "json",
				
				success: function( result ){
					if ( result!=null && result!="" ) 
					{
						//创建下拉列表
						$(result).each(function(i, n){
							$("#suggest").append('<span id="'+n.company_id+'">'+n.company_name_abbr+'</span>');
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
							$("#company_id").val($(this).attr("id"));
							$("#company_name").val($(this).text());
							$("#suggest").fadeOut();
						});
						$("#suggest").fadeIn();
					} else {
						$("#suggest").fadeOut();
					}
				}		
			});
		} else {
			$("#suggest").append(default_suggest);
			$("#company_id").val(default_group_id);
			$("#company_name").val(default_group_name);
		}
	});
});