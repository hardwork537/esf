
var default_suggest = '<div id="0" style="color:#999999;margin-left:10px;">请使用上下键选择</div>';
$(document).ready(function(){
	//初始化
	var default_group_name = '';
	var default_group_id = '';
	//$("#park_name").val(default_group_name);
	//$("#park_id").val(default_group_id);
	$("#suggest").append(default_suggest);
	//获得焦点显示提示
//	$("#park_name").focus(function(){
//        $("#suggest").fadeIn();
//	});
	//失去焦点隐藏提示
//	$("#park_name").blur(function(){
//        alert($("#park_name").siblings('span').attr('class'));
//        if( $("#park_name").siblings('span').attr('class') == 'notice_hover'){
//            $("#suggest").fadeOut();
//        }
//	});
	$("#park_name").keyup(function(e){
		var group_name = $(this).val();

		//监听上方向键选择
		if ( e.keyCode==38 ) {
			var select_span = $("#suggest .sel");
			if ( select_span.attr("id") > 0 ) {
				select_span.removeClass("sel");
				select_span.prev().addClass("sel");
			}else{
				$("#suggest span:last").addClass("sel");
			}
			return;
		}
		//监听下方向键选择
		if ( e.keyCode==40 ) {
			var select_span = $("#suggest .sel");
			if ( select_span.attr("id") > 0 ) {
				select_span.removeClass("sel");
				select_span.next().addClass("sel");
			}else{
				$("#suggest span:first").addClass("sel");
			}
			return;
		}
		//监听回车事件
		if ( e.keyCode==13 ) {
			var select_span = $("#suggest .sel");
			if ( select_span.attr("id") > 0 ) {
				$("#park_name").val(select_span.text());
				select_span.removeClass("sel");
				$("#suggest").fadeOut();
				setDH(select_span.attr("district_id"), select_span.attr("region_id"), select_span.attr("id"));
			}else{
				$("#park_name").val('');
				$("#suggest").fadeOut();
			}
			$("#park_name").focus().blur();
			return;
		}
		//过滤部分不相关的按键
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		getData(group_name);
		
	});/* end of keyup*/

	$("#park_name").click(function (){
		getData( $(this).val() );
	});
});
function getData(strParkName){
	//请求前先将下拉选项数据清空
	//$("#suggest").empty();
	// if ( group_name.length > 0 ) {
	$.ajax({
		type: "POST",
		url: base_url+"ajax/suggestPark/",
		data: "q_word=" + strParkName,
		dataType: "json",
		success: function( result ){
			$("#suggest").empty();
			if ( result!=null && result!="" ) {

				if(strParkName.length > 0){ $(default_suggest).appendTo("#suggest");
				}else{ $('<div style="color:#999999;margin-left:10px;">您曾经发布过的小区</div>').appendTo("#suggest"); }
				//创建下拉列表
				$(result).each(function(i, n){	
					//新房房源只能发布建筑年代在4年内（包括4年）的小区
					if ($("#type").val() == 20){
						var nowdate = new Date();
						var nowyear = nowdate.getFullYear();
						if (n.build_year == '' || parseInt(n.build_year) < nowyear - 4) return true;
					}
					$('<span id="'+n.park_id+'" district_id="'+n.district_id+'" region_id="'+n.region_id+'">'+n.name+'</span>').appendTo("#suggest");
				});
				//追加交互样式
				$("#suggest span").mouseover(function(){
					$("#suggest span").removeClass("sel");
					$(this).addClass("sel");
				});
				//移除交互样式
				$("#suggest span").mouseout(function(){ $(this).removeClass("sel"); });
				//点击事件
				$("#suggest span").click(function(){
					$("#park_name").val($(this).text());
					//$("#park_name").focus().blur();
					setDH($(this).attr("district_id"), $(this).attr("region_id"), $(this).attr("id"));
					$("#suggest").fadeOut();
					$("#park_name").attr("valid", "posting");
					$.ajax({
						type: "POST",
						url: base_url+"ajax/checkPark/",
						data: "param=" + encodeURIComponent($(this).text()),
						dataType: "text",
						success: function( t ){
							var t_tmp = t.split('_');
							t = t_tmp[0]; 
							if ($.trim(t) == "y") {
								//以下是我修改的的
								//这个是针对建筑年代为空时，小区变化它也随着变化
								if( document.getElementById('build_year')!=undefined && parseInt(t_tmp[1])>0 ){
									var val_build_year = $('#build_year');
									val_build_year.val(t_tmp[1]);
									val_build_year.focus().blur();
								}
								if( document.getElementById('price')!=undefined ){
									$('#price').attr('avg_price', t_tmp[2]);
									$('#price').attr('avg_region', t_tmp[3]);
									$('#price').attr('sale_count', t_tmp[4]);
									$('#price').attr('max_avg_price', t_tmp[5]);
								}
								//到这里结束
								$("#park_name").attr("valid", "true");
								$("#park_name").removeClass("Validform_error");
								$("#park_name").siblings(".notice_hover").html("<b></b>");
								$("#park_name").siblings(".notice_hover").removeClass("notice_warn").addClass("notice_right");
							} else {
								$("#park_name").attr("valid", t);
							}
						},
						error: function() {
							$("#park_name").attr("valid", "出错了！请检查提交地址或返回数据格式是否正确！");
						}
					});
				});
				$("#suggest").fadeIn();
			}else{
				$("#suggest").fadeOut();
			}
		}
	});/* end of ajax*/
	// }
	$(document).bind("click", function (e) {
        var target = $(e.target);
		if (target.closest($("#parkGroup")).length == 0) {
            if (!$("#suggest").is(":animated")) {
                $("#suggest").fadeOut(0);
            }
        }
    });
}

function setDH(d, h, p) {
	if ( reset_dh == undefined ) return;
	$("#district_id").val(d);
	$("#district_list").parent().children("em").html(district[d]);
	$.each(region[d], function(i,n){
		// $("#region_list").append('<span value="'+i+'"><a href="javascript:void(0)">'+n+'</a></span>');
		$('<span value="'+i+'"><a href="javascript:void(0)">'+n+'</a></span>').appendTo("#region_list").click(function (){			
			$(this).parent().siblings("em").text($(this).text()); //显示区域名称
			$("#region_id").val($(this).attr("value")).focus().blur(); //设置隐藏区域值
			$("#region_list").fadeOut(); //隐藏区域列表
		});
	});
	$("#region_id").val(h);
	$("#region_list").parent().children("em").html(region[d][h]);
	$("#park_id").val(p);
}
