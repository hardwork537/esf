var unitType = 1;
$(function () {
    $(".tabNav ul li").click(function () {
        $(".tabNav ul li").each(function () {
            $(this).removeClass();
        });
        $(this).addClass('curr');
        unitType = $(this).attr("unit_type");
        change_chart();
    });

	/* 模拟下拉框 */
	$(".dropdown").mouseenter(function(){
		if(!$(this).find(".dropdown_list").is(":animated")){//判断是否处于动画
			$(this).find(".dropdown_list").show();
			return false;
		}
	});
	$(".dropdown").mouseleave(function(){
		$(this).find(".dropdown_list").hide();
	});
	
	$(".dropdown_list li a").click(function(event){
		var text = $(this).text()+"<i></i>";
		var svalue = $(this).attr("src");
		$(this).parents(".dropdown").find(".dropdown_btn").html(text);
		$(this).parents(".dropdown").find("input[type=hidden]").val(svalue);
		$(this).parents(".dropdown_list").hide();
		return false;
	});
	
	/* 搜索输入框 */
	$("input.form_control").focus(function(){
		var defaultValue = $(this).attr("default-value");
		if($(this).val() == defaultValue){
			$(this).val("").removeClass("default");
		}
	})
	
	$("input.form_control").blur(function(){
		var defaultValue = $(this).attr("default-value");
		if($(this).val() == ""){
			$(this).val(defaultValue).addClass("default");
		}
	});
	
	Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });

	//初始化图表
	change_chart();
});

//异步获取满足条件的板块数据，并捆绑到指定对象(新版页面)
function get_region_new(district_id, obj)
{  
	$.ajax({
		type: "POST",
		url: base_url+"ajax/getRegion",
		data: "district_id=" + district_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
			if (data == null) {
				$("#"+obj).parents(".dropdown").find(".dropdown_btn").html("板块<i></i>");
				$("#"+obj).parents(".dropdown").find("input[type=hidden]").val(0);
			} else {
				$("#"+obj).parents(".dropdown").find(".dropdown_btn").html("板块<i></i>");
				$("#"+obj).parents(".dropdown").find("input[type=hidden]").val(0);
				$.each(data, function(i, n){
					$("<li><a href=\"javascript:void(0)\" src=\""+i+"\">"+n+"</a></li>").appendTo("#"+obj);
				});
				$(".dropdown_list li a").click(function(event){
					var text = $(this).text()+"<i></i>";
					var svalue = $(this).attr("src");
					$(this).parents(".dropdown").find(".dropdown_btn").html(text);
					$(this).parents(".dropdown").find("input[type=hidden]").val(svalue);
					$(this).parents(".dropdown_list").hide();
					return false;
				});
			}
		}
	});
}

//图表转换
function change_chart() {
	var intDistrictId = 0;
	var intRegionId = 0;
	var strParkName = '';
	var intParkId = 0;
	if ($("#menu_park").attr("class") == 'on'){
		var intParkId = parseInt($('#park_id').val());
		if( !intParkId ){
			alert('请选择小区！');
			return;
		}
		$(".highcharts_title span").html($('#park_id').parents(".dropdown").find(".dropdown_btn").text()+"小区");
	} else if($("#menu_region").attr("class") == 'on'){
		var intDistrictId = parseInt($('#district_id').val());
		var intRegionId = parseInt($('#region_id').val());
		var strParkName = $('#park_name').val();
		strParkName = strParkName == '请输入小区' ? '' : strParkName;
		if( !intRegionId ){
			alert('请选择板块！');
			return;
		}
		if( !strParkName ) $(".highcharts_title span").html($('#region_id').parents(".dropdown").find(".dropdown_btn").text()+"板块");
		else $(".highcharts_title span").html(strParkName+"小区");
	}

	/* 网民搜索分析&房源刷新走势 */
	$.ajax({
		type: "POST",
		url: base_url+"analyse/ajax/",
		data: 'content=1_'+unitType+'_'+intDistrictId+'_'+intRegionId+'_'+intParkId+"&park_name="+strParkName,
		dataType: "json",
		success: function(data1){
			$('#highcharts1').highcharts({
				chart: {
					zoomType: 'xy'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				credits: {
					enabled: false
				},
				xAxis: [{
					type: 'datetime',
					tickInterval: 3600*1000,
					labels: {
						formatter: function() {
							return Highcharts.dateFormat('%H:%M', this.value);                  
						}
					}
				}],
				yAxis: [{ // Primary yAxis
					title: {
						text: '网民搜索分析'
					}
				}, { // Secondary yAxis
					title: {
						text: '房源刷新走势 '
					},
					opposite: true
				}],
				tooltip: { 
					formatter: function() {
						return '<b>' + this.series.name + '</b><br>' + Highcharts.dateFormat('%H:%M',this.x) + ',' + this.y;
					}
				},
				legend: {
					
				},
				series: [{
					name: data1[0].name,
					color: '#f1683c',
					type: 'spline',
					data: data1[0].data,
					yAxis: 0,
					pointInterval: 900 * 1000, //间隔时间15分钟
					marker: { symbol: 'circle' }
				}, {
					name: data1[1].name,
					color: '#1d8bd1',
					type: 'spline',
					data: data1[1].data,
					yAxis: 1,
					pointInterval: 900 * 1000, //间隔时间15分钟
					marker: { symbol: 'circle' }
				}]
			});
		}
	});

	/* 价格供需比例 */
	$.ajax({
		type: "POST",
		url: base_url+"analyse/ajax/",
		data: 'content=2_'+unitType+'_'+intDistrictId+'_'+intRegionId+'_'+intParkId+"&park_name="+strParkName,
		dataType: "json",
		success: function(data2){
			$('#highcharts2').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				credits: {
					enabled: false
				},
				xAxis: {
					categories: data2[0].xlabel
				},
				yAxis: {
					min: 0,
					title: {
						text: ''
					}
				},
				legend: {
					enabled: false
				},
				tooltip: {
					headerFormat: '<p>{point.key}</p>',
					pointFormat: '<p>{series.name}: ' + '<b>{point.y}</b></p>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: data2[1].name,
					color: '#56b9f9',
					data: data2[1].data

				}, {
					name: data2[2].name,
					color: '#fdc12e',
					data: data2[2].data

				}]
			});
		}
	});

	/* 户型供需比例 */
	$.ajax({
		type: "POST",
		url: base_url+"analyse/ajax/",
		data: 'content=3_'+unitType+'_'+intDistrictId+'_'+intRegionId+'_'+intParkId+"&park_name="+strParkName,
		dataType: "json",
		success: function(data3){
			$('#highcharts3').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				credits: {
					enabled: false
				},
				xAxis: {
					categories: data3[0].xlabel
				},
				yAxis: {
					min: 0,
					title: {
						text: ''
					}
				},
				legend: {
					enabled: false
				},
				tooltip: {
					headerFormat: '<p>{point.key}</p>',
					pointFormat: '<p>{series.name}: ' + '<b>{point.y}</b></p>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: data3[1].name,
					color: '#56b9f9',
					data: data3[1].data

				}, {
					name: data3[2].name,
					color: '#fdc12e',
					data: data3[2].data

				}]
			});
		}
	});
}