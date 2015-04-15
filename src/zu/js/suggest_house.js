$(document).ready(function(){


	//初始化
	var default_group_name = '';
	var default_group_id = '0';
	var default_suggest = '<li><a href="javascript:void(0)"><span class="fl">请输入小区名称或地址/上下方向键选择</span></a></li>';

	$("#house_id_head").val(default_group_id);

	//获得焦点显示提示
	$("#head_house_name").focus(function(){
		if($("#head_house_name").attr("value") == '请输入小区名或房源特征'){
			$("#head_house_name").val('');
		}
		$("#suggest_d").fadeIn();
	});
	//失去焦点隐藏提示
	$("#head_house_name").blur(function(){
		if($("#head_house_name").attr("value") == ''){
			$("#head_house_name").val('请输入小区名或房源特征');
		}
		$("#suggest_d").fadeOut();
	});
	$("#head_house_name").keyup(function(e){
		$("#house_id_head").val(default_group_id);
		var group_name = $(this).val();
		//监听上方向键选择
		if ( e.keyCode==38 ) {
			var select_span = $("#suggest .hover");
			if ( select_span.children("a").attr("id") > 0 ) {
				select_span.removeClass("hover");
				select_span.prev().addClass("hover");
			} else {
				$("#suggest li:first").addClass("hover");
			}
			return;
		}


		//监听下方向键选择
		if ( e.keyCode==40 ) {
			var select_span = $("#suggest .hover");
			if ( select_span.children("a").attr("id") > 0 ) {
				select_span.removeClass("hover");
				select_span.next().addClass("hover");
			} else {
				$("#suggest li:first").addClass("hover");
			}
			return;
		}
		//监听回车事件
		if ( e.keyCode==13 ) {
			var objSelectA = $("#suggest .hover").children('a')
			var AHtml = objSelectA.html();
			var AId = objSelectA.attr('id');
			$("#house_id_head").val(AId);
			if( AHtml!=null ) $("#head_house_name").val(AHtml);
			$("#suggest_d").hide();
			 top_search();
			return;
		}
		//alert( e.keyCode);
		//过滤部分不相关的按键
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		//最后一次回格延时隐藏
		if(e.keyCode == 8 && group_name.length == 0){
			var suggestHide = setTimeout("$('#suggest_d').hide()",500);
		}
		//请求前先将下拉选项数据清空
		if ( group_name.length > 0 ) {
			$("#searchKeyWord").val(group_name);
			$.ajax({
				type: "POST",
				url: "/ajax/rentParkName",
				data: "q_word=" + group_name,
				dataType: "json",
				success: function( result ){
					var strHeadData = $('#strHeadData').val();
					var strThisJumpDomain = $('#strHeadCityDomain').val()? $('#strHeadCityDomain').val(): '';
					//请求前先将下拉选项数据清空
					$("#suggest").empty();
					if ( result!=null && result!="" ) {
						//创建下拉列表
						  switch  (strHeadData){
								case  'rent':  //租房
									$(result).each(function(i, n){
										$("#suggest").append('<li><a href="javascript:void(0)" id="'+n.house_id+'">'+n.house_name+'</a><em>约 '+n.sum_rent+' 套 </em></li>');
									});
									strThisJumpDomain += '/rent/x~';
									break;
								case  'house':  //找小区
									$(result).each(function(i, n){
										$("#suggest").append('<li><a href="javascript:void(0)" id="'+n.house_id+'">'+n.house_name+'</a><em></em></li>');
									});
									strThisJumpDomain += '/xiaoqu/~/';
								 break;
								case  'sale':  //'sale'售房 //最好是default
									$(result).each(function(i, n){
										$("#suggest").append('<li><a href="javascript:void(0)" id="'+n.house_id+'">'+n.house_name+'</a><em>约 '+n.sum_sale+' 套 </em></li>');
									});
									strThisJumpDomain += '/sale/x~';
								  break;
							  }
						//联想框变色
						$('.lenovo li').mouseover(
						function(){
						$(this).addClass('hover');
						});
						$('.lenovo li').mouseout(
						function(){
						$(this).removeClass('hover');
						});
						$("#suggest li").click(function(){
							var thisId = $(this).children("a").attr("id");
							$("#house_id_head").val(thisId);
				            $("#head_house_name").val($(this).children("a").text());
				            window.location.href = strThisJumpDomain.replace('~', thisId); //此参数为全局变量，会影响到个人中心头部的url跳转
				            //页面提交
				            $("#suggest_d").hide();
                            return false;
						});
						$("#suggest_d").fadeIn(10);
					} else {
						$("#suggest_d").hide();
					}
				}
			});
		} else {
			$('#suggest_d').hide()
		}
	});
});