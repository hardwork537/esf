$(function () {
	/* 房源筛选 */
	topFilters();
	
	/* 列表全区域可点 */
	houseListClick();
	
	/* 最近浏览过的房源置顶 */
	if($("#sidebarFixed").length > 0){
		sidebarFixed();
	}
	
	/* 你是不是在找 */
	if($("#findSlider").length > 0){
		findSlider();
	}
	
	/* 房价走势 */
	priceCharts();
})

/* 列表全区域可点 */
function houseListClick(){
	var $items = $("#listItem").find(".items");
	$items.each(function(){
		$(this).hover(function(){
			if($(document.body).hasClass("wide")){
				$(this).find(".items_title h2 a").addClass("on");
			}
			$(this).addClass("hover");
			$(this).prev(".items").addClass("nobd");
		},function(){
			$(this).find(".items_title h2 a").removeClass("on");
			$(this).removeClass("hover");
			$(this).prev(".items").removeClass("nobd");
		});
		
		$(this).find('a').each(function(){
			$(this).click(function(e){
				if(e.stopPropagation){
					e.stopPropagation();
				}else{
					window.event.cancelable = true;
				}
			});
		});		
		
		$(this).click(function(){
			var a = document.createElement("a");
			a.href = $(this).find('a.link').attr('href');
			a.target = "_blank";
			document.body.appendChild(a);
			a.click();
		});		
	});
}

/* 最近浏览过的房源置顶 */
function sidebarFixed(){
	var toTop = $("#sidebarFixed").offset().top-10;
	$(window).scroll(function(){
		var scrollTop = $(window).scrollTop();
		if($(".main").height() + $(".main").offset().top > toTop){
			if(scrollTop > toTop && scrollTop < $(".main").height() + $(".main").offset().top-$("#sidebarFixed").height()-50){
				$("#sidebarFixed").addClass("sidebar_fixed");
			}else{
				$("#sidebarFixed").removeClass("sidebar_fixed");
			}
		}		
	});
}

/* 你是不是在找 */
function findSlider(){
	var $focus = $("#findSlider"),
		$prev = $focus.find(".prev"),
		$next = $focus.find(".next"),
		$focusImg = $focus.find("ul"),
		$snavBtn;
	
	/* 过滤空的li,并给li添加index */
	var $initLi = $focusImg.find("li");
	for(var i=0,j=0; i<$initLi.length; i++){
		if($initLi.eq(i).children().length == 0){
			$initLi.eq(i).remove();
		}else {
			$initLi.eq(i).attr("data-index",j);
			j++;
		}
	}
	
	/* 初始化 */
	var index = 0,
		length = $focusImg.find("li").length,
		cloneLen = 1,
		imgWidth = 160;
	
	$focusImg.css("width",imgWidth*length);
	
	/* 图片大于1的时候，鼠标hover显示左右按钮，并添加底部的圆点 */
	if(length > 1){
		for(var i=0; i<length; i++){
			$focus.find(".snav").append("<span></span>");			
		}
		$snavBtn = $focus.find(".snav span");
		$snavBtn.eq(0).addClass("on");
		$focus.find(".btn").show();
		/* 自动播放 */
		startTimer();
		$focus.mouseenter(function(){
			endTimer();
		}).mouseleave(function(){
			startTimer();
		});
	}
	
	function startTimer(){				
		timer = setInterval(function(){
			index++;			
			if(index == length) index=0;
			$focusImg.css("left",0);
			$focusImg.find("li:first").clone().appendTo($focusImg);
			scrollRight();
		},3000);
	}

	function endTimer(){
		if(timer){
			clearInterval(timer);
		}
	}
	
	/* 点击往前 */
	$prev && $prev.click(function(){
		if (!$focusImg.is(":animated")) {/* 不加改判断会导致快速点击时出错 */
			$focusImg.css("left",-imgWidth);
			$focusImg.find("li:last").clone().prependTo($focusImg);
			scrollLeft();
		}
	});
	
	/* 点击往后 */
	$next && $next.click(function(){
		if (!$focusImg.is(":animated")) {
			$focusImg.css("left",0);
			$focusImg.find("li:first").clone().appendTo($focusImg);
			scrollRight();
		}
	});
	
	/* 点击底部圆点 */
	$snavBtn && $snavBtn.click(function(){
		if (!$focusImg.is(":animated")) {
			var sIndex = $(this).index();
			$(this).addClass("on").siblings().removeClass("on");
			if(sIndex > index){
				var targetIndex = $focusImg.find("li[data-index='"+sIndex+"']").index();
				$focusImg.find("li:lt("+targetIndex+")").clone().appendTo($focusImg);
				$focusImg.find("li:eq("+targetIndex+")").clone().prependTo($focusImg);
				$focusImg.find("li:lt("+targetIndex+")").detach();
				scrollRight();
			}
			if(sIndex < index){
				var targetIndex = $focusImg.find("li[data-index='"+sIndex+"']").index();
				$focusImg.css("left",-imgWidth);
				$focusImg.find("li:gt("+(targetIndex-1)+")").clone().prependTo($focusImg);
				scrollLeft();
			}
		}
	});
	
	function scrollLeft(){
		$focusImg.animate({left:0},300,moveLeft);
	}
	
	function scrollRight(){
		$focusImg.animate({left:-imgWidth},300,moveRight);
	}
	
	function moveLeft(){
		$focusImg.find("li:last").detach();
		index = $focusImg.find("li:first").attr("data-index");
		$snavBtn.eq(index).addClass('on').siblings().removeClass('on');
	};
	
	function moveRight(){
		$focusImg.find("li:first").detach();
		$focusImg.css("left",0);	
		index = $focusImg.find("li:first").attr("data-index");
		$snavBtn.eq(index).addClass('on').siblings().removeClass('on');
	};
	
}

/* 房价走势 */
function priceCharts(){
	var nodata = '<span class="nodata">暂无价格走势数据</span>';
	var priceData = $('#price_charts').attr('data-value');
	if(!priceData){
		$('#price_charts').html(nodata);
		return false;
	}
	priceData = eval('('+ priceData +')');
	if(!priceData[1]){
		priceData[1] = [name,data];
		priceData[1].name = "";
		priceData[1].data = "";
	}
	var data = priceData[1].data,
		dataCount = data.length;
	for(var i=0;i<dataCount;i++){
		data[i] = parseInt(data[i]);
	}
	var month = priceData[0].month,
		maxValue = priceData[0].maxValue,
		minValue = priceData[0].minValue;
		
	$('#price_charts').highcharts({
		chart: {
			type: 'line',
			marginLeft: 60
		},
		credits: {
			enabled: false
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		labels: {
			formatter: function() {
				return month[this.value];
			}
		},
		xAxis: {
			minPadding: 0,
			labels: {
				formatter: function() {
					return month[this.value];
				},
				style: {
					fontSize: '12px',
					fontFamily: '宋体'
				}
			},
			lineColor: '#dbdbdb',
			tickLength: 0,
			tickInterval: 1
		},
		yAxis: {
			title: {
				text: ''
			},
			lineColor: '#dbdbdb',
			lineWidth: 1,
			tickWidth: 1,
			tickLength: 4,
			tickColor: '#dbdbdb',
			tickPixelInterval: 45,
			gridLineColor: '#eee',
			gridLineWidth: 1,
			labels: {  
				formatter: function() {
					return this.value;
				},
				style: {
					fontSize: '12px',
					fontFamily: '宋体'
				}
			}
		},
		tooltip: {
			formatter: function() {
				return '均价：' + this.y + '元<br>日期：' + month[this.x];
			}
		},
		legend: {
			enabled: false
		},
		series: [{
			color: '#fff2ed',
			lineColor: '#ff7e4b',
			lineWidth: 1,
			name: '',
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
						radius: 7
					}
				}
			},	
		   data: data
		}]
	});
}
