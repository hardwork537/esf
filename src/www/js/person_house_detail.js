$(function(){
	/* 分享 */
	$('.share_btn').click(function () {
	   var $shareList = $(this).parent().find('.share_list');
	   if (!$shareList.is(':animated')) {
		   $shareList.fadeIn('fast');
		   $(this).addClass('hover');
	   }
	})
   
	$(document).bind('click', function (e) {
		var target = $(e.target);
		if (target.closest('.share_list').length == 0) {
			var $shareList = $('.share_list');
			if (!$shareList.is(':animated')) {
				$shareList.fadeOut('fast');
				$('.share_btn').removeClass('hover');
			}
		}
	});
	
	/* for ie6 hover */
	if(navigator.userAgent.indexOf('MSIE 6.0')>0){
		/* sidebar star agent */
		$('.thumSlider .prev').hover(function(){
			$(this).addClass('prev_hover');
		},function(){
			$(this).removeClass('prev_hover');
		});
		$('.thumSlider .next').hover(function(){
			$(this).addClass('next_hover');
		},function(){
			$(this).removeClass('next_hover');
		});
		$('.thumSlider .thum_prev').hover(function(){
			$(this).addClass('thum_prev_hover');
		},function(){
			$(this).removeClass('thum_prev_hover');
		});
		$('.thumSlider .thum_next').hover(function(){
			$(this).addClass('thum_next_hover');
		},function(){
			$(this).removeClass('thum_next_hover');
		});
		$('.mod_r_agent .btn').hover(function () {
			$(this).addClass('btn_hover');
		}, function () {
			$(this).removeClass('btn_hover');
		});
	}
	
	// 导航锚点切换
	$('#around_anchor').click(function(){
		var anchor_name = $(this).attr('anchor-name');
		var _targetTop = $("[data-name='"+anchor_name+"']").offset().top - 50;//获取位置
		$('html,body').animate({scrollTop:_targetTop},0);//跳转
	});
	
	/* 导航锚点切换 */
	var $jSiderbar = $('#j_siderbar');
	var $anchorObj = $('[data-type=anchor]');
	$jSiderbar.find("li").click(function() {
		var anchor_name = $(this).attr('anchor-name');
		var _targetTop = $("[data-name='"+anchor_name+"']").offset().top - 50;//获取位置
		$('html,body').animate({scrollTop:_targetTop},0);//跳转
		
		$(this).siblings().removeClass('first_focus focus');
		if($(this).hasClass('first')){
			$(this).addClass('first_focus');
		}else {
			$(this).addClass('focus');
		}
	});
	$(window).scroll(function(){
		var top = $(window).scrollTop();		
		var start = $('[data-name=house_basic]').offset().top;
		/* 控制吸顶 */
		if(top > (start+50)){
			$('#house_quick_link').fadeIn(100);
		}else{
			$('#house_quick_link').fadeOut(100);
		}
		
		/* 控制左侧锚点 */
		if(top > (start-100)){
			$jSiderbar.addClass('j_siderbar_fixed');			
		}else{			
			$jSiderbar.removeClass('j_siderbar_fixed');
		}
		
		$jSiderbar.find('li').hover(function(){
			if($(this).hasClass('first')){
				$(this).addClass('first_hover');
			}else{
				$(this).addClass('hover');
			}
		},function(){
			$(this).removeClass('first_hover hover');
		});

		/* 根据滚动条位置控制左侧栏锚点相应的选中状态 */
		$anchorObj.each(function(i){
			if(top >= $(this).offset().top-100){
				var anchorName = $(this).attr("data-name");
				$jSiderbar.find("li").removeClass("first_focus focus");
				if(i == 0){
					$jSiderbar.find("li[anchor-name='"+anchorName+"']").addClass("first_focus");
				}else{
					$jSiderbar.find("li[anchor-name='"+anchorName+"']").addClass("focus");
				}

			}
		});
		
	}); 
	
	/* slider */	
	imgSliderInit("#topSlider",104);
	imgSliderInit("#communitySlider",104);
	function imgSliderInit(obj,swidth){
		/* 初始化 */
		var currTabsIdx = 0, 
			index = 0, 
			len = 0, 
			pageSize = 6, 
			w = swidth * len, 
			currentPage = 0, 
			totalPage = parseInt((len + pageSize -1) / pageSize);
		var $thumTabs = $(obj).find(".thum_tabs li");
		var $thumCon = $(obj).find(".thumSlider");
		
		thumShow();
		
		/* 图片类型切换 */
		$thumTabs.click(function(){
			currTabsIdx = $(this).index();
			$thumTabs.removeClass('on').eq(currTabsIdx).addClass('on');
			$thumCon.hide().eq(currTabsIdx).show();
			thumShow();
		});
		
		function thumShow(){
			var $currThum = $thumCon.eq(currTabsIdx),
				$imgWrap = $currThum.find(".imgWrap"),
				$thum = $currThum.find(".thum"),
				$thumImg = $thum.find(".thum_img"),
				$thumNext = $thum.find(".thum_next"),
				$thumPrev = $thum.find(".thum_prev"),
				$thumListInner = $thum.find(".thumList_inner");
			
			len = $thumImg.length;
			w = swidth * len;
			currentPage = 0;
			totalPage = parseInt((len + pageSize -1) / pageSize);
			$thumListInner.width(w);
			
			if(totalPage == 1){
				$thumNext.hide();
				$thumPrev.hide();
			}
			$thumListInner.stop(true,false).animate({left: -swidth * currentPage * pageSize},300);
			
			/* 点击小图 */
			$thumImg.click(function(){
				index = $thumImg.index(this);			
				showImg(index);
			}).eq(0).click();
			
			/* 点击小图下一页 */
			$thumNext.click(function(){
				thumScrollRight();
			});
			
			/* 点击小图上一页 */
			$thumPrev.click(function(){			
				thumScrollLeft();
			});						
		}
		
		function thumScrollRight(){
			
			if(currentPage < totalPage-1){
				currentPage++;
				$thumCon.eq(currTabsIdx).find(".thumList_inner").stop(true,false).animate({left: -swidth * currentPage * pageSize},300);				
			}
		}
		
		function thumScrollLeft(){
			if(currentPage > 0){
				currentPage--;
				$thumCon.eq(currTabsIdx).find(".thumList_inner").stop(true,false).animate({left: -swidth * currentPage * pageSize},300);
			}
		}
		
		function showImg(index){
			var $currThum = $thumCon.eq(currTabsIdx),
				$imgWrap = $currThum.find(".imgWrap"),
				$thum = $currThum.find(".thum"),
				$thumImg = $thum.find(".thum_img");
			$thumImg.find(".box").remove();
			$thumImg.eq(index).prepend("<span class='box'><s></s></span>").addClass("selected").siblings().removeClass("selected");
			$imgWrap.find(".con").eq(index).show().siblings().hide();	
		}
		
		/* 点击大图下一 */
		$thumCon.find(".next").click(function(){
			if(index < len-1){
				index++;
				showImg(index);
				if(index % pageSize == 0){
					thumScrollRight();
				}
			}else{
				if($thumTabs.length > 0){
					currTabsIdx = currTabsIdx < $thumTabs.length-1 ? (currTabsIdx+1) : 0;
					$thumTabs.eq(currTabsIdx).click();
				}
			}
		});
		
		/* 点击大图上一张 */
		$thumCon.find(".prev").click(function(){
			if(index > 0){
				if(index % pageSize == 0){
					thumScrollLeft();
				}
				index--;
				showImg(index);
			}else{
				if($thumTabs.length > 0){
					currTabsIdx = currTabsIdx > 0 ? (currTabsIdx-1) : $thumTabs.length-1;
					$thumTabs.eq(currTabsIdx).click();
				}
			}
		});
		
	}
	
	//相关房源横向滚动
	function recSlider(obj){
		var $slider = $(obj),
			$ul = $slider.find(".slider_wrap ul"),
			$li = $ul.find("li"),
			$prev = $slider.find(".prev"),
			$next = $slider.find(".next");
		
		$li.hover(function(){
			$(this).addClass("hover");
		},function(){
			$(this).removeClass("hover");
		});
		
		$li.each(function(){
			$(this).find('a').each(function(){
				$(this).click(function(e){
					if(e.stopPropagation){
						e.stopPropagation();
					}else{
						window.event.cancelable = true;
					}
				});
			});
			
			$(this).on("click",function(event){
				var a = document.createElement("a");
				a.href = $(this).find('a.link').attr('href');
				a.target = "_blank";
				document.body.appendChild(a);
				a.click();
			});
		});
		
		//初始化
		var len = $li.length,
			w = 190 * len, 
			currentPage = 0, 
			pageSize = 5,
			totalPage = parseInt((len + pageSize -1) / pageSize);	
		$ul.width(w);
		if(totalPage == 1){
			$prev.hide();
			$next.hide();
		}
		
		//点击下一页
		$next.click(function(){
			scrollRight();
		});
		
		//点击上一页
		$prev.click(function(){			
			scrollLeft();
		});
		
		function scrollRight(){	
			currentPage = (currentPage < totalPage-1) ? currentPage+1 : 0;
			$ul.stop(true,false).animate({left: -190 * currentPage * pageSize},300);
		}
		
		function scrollLeft(){
			currentPage = (currentPage > 0) ? currentPage-1 : totalPage-1;
			$ul.stop(true,false).animate({left: -190 * currentPage * pageSize},300);
		}
	}
	recSlider("#price_rec");
	recSlider("#area_rec");
})

/* 月供参考 */
$(function(){
	/* 2014年11月24日的基准利率 */
	lilv_array=new Array;
	lilv_array[1]=new Array;
	lilv_array[2]=new Array;
	lilv_array[1][1]=0.0560;//商贷
	lilv_array[1][2]=0.0600;//商贷
	lilv_array[1][3]=0.0600;//商贷
	lilv_array[1][4]=0.0600;//商贷
	lilv_array[1][5]=0.0600;//商贷
	lilv_array[1][6]=0.0615;//商贷
	lilv_array[2][1]=0.0375;//公积金
	lilv_array[2][2]=0.0375;//公积金
	lilv_array[2][3]=0.0375;//公积金
	lilv_array[2][4]=0.0375;//公积金
	lilv_array[2][5]=0.0375;//公积金
	lilv_array[2][6]=0.0425;//公积金 
	
	var loanRate = 0;
	var $refer = $('#reference_month'),
		$loanType = $('#loan_type'),
		$loanLtv = $('#loan_ltv'),
		$loanYears = $('#loan_years');
		
	var totalPrice = parseInt($('#totalPrice').text()*10000);
	
	var loanType = $loanType.find('.dropdown_btn span').attr('data-value'),
		loanLtv = $loanLtv.find('.dropdown_btn span').attr('data-value'),
		loanYears = $loanYears.find('.dropdown_btn span').attr('data-value'),
		monthPay = 0,
		loanTotal = 0,
		firstPay = 0,
		interestPay = 0,
		allpay = 0;
		
	/* 初始化 */
	monthLegend(loanType,loanLtv,loanYears);
	var $litems = $('#reference_month').find('.litems');
	for(var i=0,j=$litems.length; i<$litems.length; i++,j--){
		$litems.eq(i).css('z-index',j);
	}
	
	$('.dropdown').click(function(){
		var dropdown = this;
		var $menu = $(dropdown).find('.dropdown_menu');
		$(dropdown).parent().siblings().find('.dropdown_menu').hide();
		$menu.toggle();
		
		$menu.find('li').hover(function(){
			$(this).addClass('hover');
		},function(){
			$(this).removeClass('hover');
		});
		
		$menu.find('li').click(function(){
			var txt = $(this).text();
			var dataValue = $(this).attr('data-value');
			$(dropdown).find('.dropdown_btn span').text(txt).attr('data-value',dataValue);
			loanType = $loanType.find('.dropdown_btn span').attr('data-value');
			loanLtv = $loanLtv.find('.dropdown_btn span').attr('data-value');
			loanYears = $loanYears.find('.dropdown_btn span').attr('data-value');
			monthLegend(loanType,loanLtv,loanYears);
		});
	});
	
	$(document).bind('click', function (e) {
		var target = $(e.target);
		if (target.closest('.dropdown').length == 0) {
			if (!$('.dropdown_menu').is(':animated')) {
				$('.dropdown_menu').fadeOut('fast');
			}
		}
	});

	
	function monthLegend(loanType,loanLtv,loanYears){
		getRate(loanType,loanLtv,loanYears);
		var $firstPay = $('#first_pay');
		var payLtv = 10-loanLtv;
		firstPay = totalPrice * payLtv * 0.1;
		$firstPay.find('span:first').text(Math.round(firstPay/10000));
		$firstPay.find('span:last').text(payLtv);
		
		var $loanAmount = $('#loan_amount');
		loanTotal = totalPrice * loanLtv * 0.1;
		$loanAmount.find('span:first').text(Math.round(loanTotal/10000));
		$loanAmount.find('span:last').text(loanLtv);
		
		
		var loanRateMonth = loanRate / 12; 
		var loanMonth = loanYears*12;
        monthPay = loanTotal * loanRateMonth * Math.pow(1 + loanRateMonth, loanMonth) / (Math.pow(1 + loanRateMonth, loanMonth) - 1);
		allPay = monthPay * loanMonth;

		var $payInterest = $('#pay_interest');
		interestPay = allPay-loanTotal;
		$payInterest.find('span:first').text(Math.round(interestPay/10000));
		$payInterest.find('span:last').text((loanRate * 100).toFixed(2));
		
		var monthCharts = new Highcharts.Chart({
			chart: {
				renderTo: 'monthCharts',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			credits: {
				enabled: false		//右下角文字不显示
			},
			title: {
				useHTML: true,
				text: '<p class="title"><span class="gray">每月支出</span><br /><span class="price">'+Math.round(monthPay)+'</span>元</p>',
				align: 'center',
				verticalAlign: 'middle'
			},
			colors: ['#50c25f', '#fdd400', '#ff4600'] ,
			tooltip: {
				enabled: false
			},
			plotOptions: {
				pie: {
					allowPointSelect: false,	//允许选中，点击选中的扇形区可以分离出来显示 
					cursor: 'pointer',	//当鼠标指向扇形区时变为手型（可点击） 
					dataLabels: {
						enabled: false,	//设置数据标签可见，即显示每个扇形区对应的数据 
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'	//格式化数据 
					},
					innerSize: '70%'	//内径大小。尺寸大于0呈现一个圆环图。可以是百分比或像素值。百分比是相对于绘图区的大小。像素值被给定为整数。默认是：0。
				}
			},
			series: [{
				type: 'pie',
				name: 'Browser share',
				data: [firstPay, loanTotal, interestPay]
			}]
		});
	}
	
	function getRate(loanType,loanLtv,loanYears){
		if(loanType == 1){//商贷
			switch(loanYears)
			{
				case '1':
				loanRate = lilv_array[1][1];
				break;
				case '2':
				loanRate = lilv_array[1][2];
				break;
				case '3':
				loanRate = lilv_array[1][3];
				break;
				case '4':
				loanRate = lilv_array[1][4];
				break;
				case '5':
				loanRate = lilv_array[1][5];
				break;
				default:
				loanRate = lilv_array[1][6];
			}
		}else if(loanType == 2){//公积金
			if(loanYears <= 5){
				loanRate = lilv_array[2][5];
			}else {
				loanRate = lilv_array[2][6];
			}
		}			
	}
});

$(function () {
	var nodata = '<span class="nodata">暂无价格走势数据</span>';
	var priceData = $('#price_charts').attr('data-value');
	if(!priceData){
		$('#price_charts').html(nodata);
		return false;
	}
	priceData = eval('('+ priceData +')');
	if(priceData.length !== 3){
		return false;
	}
	$('#price_charts').html('');
	for(var j=1;j<=2;j++){
		if(!priceData[j]){
			priceData[j] = [name,data];
			priceData[j].name = "";
			priceData[j].data = "";
		}
		var data = priceData[j].data;
		var dataCount = priceData[j].data.length;
		for(var i=0;i<dataCount;i++){
			data[i] = parseInt(data[i]);
		}
		
	}
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
				return priceData[0].month[this.value];
			}
		},
		xAxis: {
			minPadding: 0,
			labels: {
				formatter: function() {
					return priceData[0].month[this.value];
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
					return this.value;
				},
				style: {
					fontSize: '14px',
					fontFamily: '宋体'
				}
			}
		},
		tooltip: {
			formatter: function() {
				return '<b>' + this.series.name + '</b><br>均价：' + this.y +'元'+'<br>日期：'+ priceData[0].month[this.x];
			}
		},
		legend: {
			enabled: false
		},
		series: [{
			color: '#ebf4ff',
			lineColor: '#3f90ff',
			lineWidth: 1,
			name: priceData[1].name,	
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
		   data: priceData[1].data
		}, {
			color: '#fff2ed',
			lineColor: '#ff7e4b',
			lineWidth: 1,
			name: priceData[2].name,
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
			data: priceData[2].data
		}]
	});
});
