var timeIdx = 0;
var nodata = '<span class="nodata">暂无数据</span>';
var price6,price12;
$(function () {
	price6 = priceTrend.price6;
	price12 = priceTrend.price12;
    if(typeof(priceTrend)!="undefined"){
		areaChart(price6);
	}else{
		$("#priceCharts").html(nodata);
	}
	
	tabs(".community_house_rel .tab_nav li",".community_house_rel .tab_con ul");
	
	/* for ie6 hover */
	if(navigator.userAgent.indexOf("MSIE 6.0")>0){
		$(".community_house_rel .tab_con li").hover(function(){
			$(this).addClass("hover");
		},function(){
			$(this).removeClass("hover");
		});
	}
	
	/* 选择房价走势的时间间隔 */
	var $timeBtn = $("#price_trend .time_btn");
	$timeBtn.click(function(){
		timeIdx = $timeBtn.index(this);
		$timeBtn.removeClass('on');
		$(this).addClass('on');
		var priceData = (timeIdx === 0) ? price6 : price12;
		areaChart(priceData);
	});
	
	/* 对比小区搜索 */
	getDtlSuggest();
	
	/* 最近浏览小区点击对比 
	$("#relateCompare a").click(function(){
		var comparedObj = $(this).text();
		var comparedId = $(this).attr("id");
		showCompared(comparedObj,comparedId);
	});*/
}); 

/* 小区对比 search */
function getDtlSuggest(){
	var objDtlHouseId = $('#dtl_house_id');
	var objDtlInput = $("#dtlSearchInput");
	var objAutocomplete = $("#dtl_autocomplete");
	var defaultValue = objDtlInput.attr("data-holder");
	var clearBtn = $("#dtlSearchBox").find(".icon_clear");
	
	/* 初始化 */
	var items_idx = -1,
		houseName = "",
		houseId = "",
		flag1 = false,
		flag2 = false;
	
	/* 清除输入框 */
	clearBtn.hover(function(){
		$(this).addClass("icon_clear_hover");
	},function(){
		$(this).removeClass("icon_clear_hover");
	});
	
	clearBtn.click(function(){
		objDtlInput.val("");
		$(this).hide();
	});
	
	/* 获得焦点时显示 */
	objDtlInput.focus(function(){
		$(this).addClass("focus");
		if(objDtlInput.val() == defaultValue){
			$(this).val("");
		}
	})
	
	objAutocomplete.find("li").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	})
	
	$(document).bind("click", function (e) {
        var target = $(e.target);
		if (target.closest($("#dtlSearchBox")).length == 0) {
            if (!objAutocomplete.is(":animated")) {
				items_idx = -1;
                objAutocomplete.fadeOut(0);
				objAutocomplete.find("li").removeClass("hover");
				if(objDtlInput.val() == ""){
					objDtlInput.val(defaultValue);
					objDtlInput.removeClass("focus");		
				}
            }
        }
    });
	
	/* 键盘输入 */
	objDtlInput.keyup(function(e){
		strDtlInput = $.trim(objDtlInput.val());
		clearBtn.show();
		flag2 = true;
		
		/* 监听上方向键选择 */
		if(e.keyCode == 38){
			items_idx--;
			if(items_idx < 0){
				items_idx = objAutocomplete.find("li").length-1;
			}
			itemHover(items_idx);
			flag2 = true;
		}
		
		/* 监听下方向键选择 */
		if(e.keyCode == 40){
			items_idx++;
			if(items_idx >= objAutocomplete.find("li").length){
				items_idx = 0;
			}	
			itemHover(items_idx);
			flag2 = true;	
		}
		
		/* 监听删除键 */
		if(e.keyCode == 8 || e.keyCode == 46){
			if(strDtlInput == ""){
				clearBtn.hide();
				objDtlHouseId.val("");
				flag2 = false;
			}
		}
		
		//监听回车事件
		if ( e.keyCode == 13 ) {
			if(flag2){
				if(objAutocomplete.find("li.hover").length > 0){
					houseName = objAutocomplete.find("li.hover").attr("title");
					houseId = objAutocomplete.find("li.hover").attr("id");
					objDtlInput.val(houseName);
					objDtlHouseId.val(houseId);
					objAutocomplete.hide();
				}
				//dtlSearchSubmit();
			}
			return;
		}
		
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		
		if( strDtlInput.length < 0 ){
			return ;
		}
		
		/* 模拟Ajax数据 */
		/*
		objAutocomplete.empty();
		var li = '<li title="仁恒河滨城" id="1">仁恒河滨城</li><li title="瑞虹新城一期" id="2">瑞虹新城一期</li><li title="中凯城市之光" id="3">中凯城市之光</li>';
		$(li).appendTo(objAutocomplete).on('click',function (){
			var comparedObj = $(this).text();
			var comparedId = $(this).attr("id");
			objDtlInput.val(comparedObj);
			objAutocomplete.hide();
			showCompared(comparedObj,comparedId);
			return false;
		});
		objAutocomplete.show();
		*/
		$.ajax({
			type: "POST",
			url: "/ajax/getParkName",
			data: "q=" + strDtlInput,
			dataType: "json",
			success: function( result ){
                objAutocomplete.empty();
				if( result == null || result == "null" ){
					return;
				}
				$(result).each(function(i, n){
					var title = n.name.replace(/<[^>]+>/g, "");
					$('<li title="'+title+'" id="'+n.id+'">'+n.name+'</li>').appendTo(objAutocomplete).on({
						"click": function(){
							objDtlInput.val(title);
							objAutocomplete.hide();
							showCompared(title,n.id);
							return false;
						},
						"mouseenter": function(){
							objAutocomplete.find("li").removeClass("hover");
							$(this).addClass("hover");
						},
						"mouseleave": function(){
							$(this).removeClass("hover");
						}
					});
				});
                objAutocomplete.show();
			}
		});
		
		function itemHover(items_idx){
			objAutocomplete.find("li").removeClass("hover");
			objAutocomplete.find("li").eq(items_idx).addClass("hover");
		}
	});
	
	function dtlSearchSubmit(){
		var strText = objDtlInput.val();
		if( strText == defaultValue ) return ;
	}
}

/* 显示对比小区的数据 */
function showCompared(comparedObj,comparedId){
	//$("#comparedData").empty();
	//var html = '<h2>'+comparedObj+'</h2>';
	$("#priceCharts").attr("txt2",comparedObj);
	$("#priceChartsTime .txt2").text("- "+comparedObj);
	
	//获取对比小区的价格数据
	$.ajax({
		type: "POST",
		url: "/ajax/getCompareParkData",
		data: "parkId=" + comparedId,
		dataType: "json",
		success: function( result ){
			if( result == null || result == "null" ){
				return;
			}
			result  = eval(result);
			//html += result.parkHousePriceHtml;
			priceTrend = result.priceTrend[0];
			price6 = priceTrend.price6;
			price12 = priceTrend.price12;
			var priceData = (timeIdx === 0) ? price6 : price12;
			areaChart(priceData);
		}
	});
	/* 模拟ajax数据 返回对比小区表格 */
	//html += ' <table cellspacing="0" cellpadding="0" width="100%" border="0"><tbody><tr><th>出租均价</th><td>一居 1620元/月</td><td>两居  3934元/月</td><td>三居  4600元/月</td><td>三居以上 8767元/月</td></tr><tr><th>出售均价</th><td>一居 29643元/㎡</td><td>两居  20644元/㎡</td><td>三居  16198元/㎡</td><td>三居以上 16229元/㎡</td></tr></tbody></table>';
	//$("#comparedData").append(html);
	
	/* 模拟ajax数据 返回相应的图表数据 */
	//priceTrend.price6[2].regionData = [7000,9000,12000,1500,1000,8000];
	//priceTrend.price12[2].regionData = [9000,11000,13000,7500,8000,12800,11800,13000,11900,11800,11500,11000];

}

/* 房价走势图表-区域图 */
function areaChart(data){
	if(!data){
		$("#priceCharts").html(nodata);
	}else{
		$("#priceCharts").html('');
		var month = data[0].month,
			park = data[1].parkData,
			regin = data[2].regionData,
			$areaChart = $('#priceCharts'),
			txt1 = $areaChart.attr('txt1'),
			txt2 = $areaChart.attr('txt2');
			
		$areaChart.highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: ''
			},
			credits: {
				enabled: false
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				minPadding: 0,
				labels: {
					formatter: function() {
						return month[this.value];
					},
					style: {
						fontSize: '14px',
						fontFamily: '宋体'
					}
				},
				lineColor: '#dbdbdb',
				tickLength: 0,
				tickInterval: 1
			},
			yAxis: {
				min: 0,
				minPadding: 0,
				showFirstLabel: false,
				startOnTick: false,
				title: {
					text: ''
				},
				lineColor: '#dbdbdb',
				lineWidth: 1,
				tickWidth: 1,
				tickLength: 6,
				tickColor: '#dbdbdb',
				gridLineColor: '#eee',
				gridLineWidth: 1,
				labels: {
					formatter: function() {
						return this.value + '元';
					}
				},
				lineWidth: 1 //基线宽度
			},
			tooltip: {
				formatter: function() {
					return '<b>' + this.series.name + '</b><br>' + month[this.x] + '均价：' + this.y + '元';
				}
			},
			legend: {
				enabled: false
			},
			plotOptions: {
				area: {
					marker: {
						lineWidth: 1,
						lineColor: '#faf5fa'
					},
					marker: {
						symbol: 'circle',
						radius: 2,
						states: {
							hover: {
								enabled: true
							}
						}
					},
					pointStart: 0
				}
			},
			series: [{
				color: '#fff2ed',
				lineColor: '#ff7e4b',
				lineWidth: 1,
				name: txt1,
				marker: {
					symbol: 'circle',
					fillColor: '#ff7e4b',
					lineColor: '#fff',
					lineWidth: 2,
					radius: 5,
					states: {
						hover: {
							lineColor: '#fff',
							lineWidth: 2,
							radius: 8
						}
					}
				},
				data: park
			},
			{
				color: '#ebf4ff',
				lineColor: '#3f90ff',
				lineWidth: 1,
				name: txt2,
				marker: {
					symbol: 'circle',
					fillColor: '#3f90ff',
					lineColor: '#fff',
					lineWidth: 2,
					radius: 5,
					states: {
						hover: {
							lineColor: '#fff',
							lineWidth: 2,
							radius: 8		
						}
					}
				},
				data: regin
			}]
		});
	}
}
				