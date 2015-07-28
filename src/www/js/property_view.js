//$(function(){
//	
//	/* for ie6 hover */
//	if(navigator.userAgent.indexOf('MSIE 6.0')>0){
//		/* sidebar star agent */
//		$('.thumSlider .prev').hover(function(){
//			$(this).addClass('prev_hover');
//		},function(){
//			$(this).removeClass('prev_hover');
//		});
//		$('.thumSlider .next').hover(function(){
//			$(this).addClass('next_hover');
//		},function(){
//			$(this).removeClass('next_hover');
//		});
//		$('.thumSlider .thum_prev').hover(function(){
//			$(this).addClass('thum_prev_hover');
//		},function(){
//			$(this).removeClass('thum_prev_hover');
//		});
//		$('.thumSlider .thum_next').hover(function(){
//			$(this).addClass('thum_next_hover');
//		},function(){
//			$(this).removeClass('thum_next_hover');
//		});
//		$('.mod_r_agent .btn').hover(function () {
//			$(this).addClass('btn_hover');
//		}, function () {
//			$(this).removeClass('btn_hover');
//		});
//	}
//
//	/* slider */	
//	//imgSliderInit("#topSlider",100);
//	imgSliderInit("#communitySlider",119);
//	function imgSliderInit(obj,swidth){
//		/* 初始化 */
//		var currTabsIdx = 0, 
//			index = 0, 
//			len = 0, 
//			pageSize = 6, 
//			w = swidth * len, 
//			currentPage = 0, 
//			totalPage = parseInt((len + pageSize -1) / pageSize);
//		var $thumTabs = $(obj).find(".thum_tabs li");
//		var $thumCon = $(obj).find(".thumSlider");
//		
//		thumShow();
//		
//		/* 图片类型切换 */
//		$thumTabs.click(function(){
//			currTabsIdx = $(this).index();
//			$thumTabs.removeClass('on').eq(currTabsIdx).addClass('on');
//			$thumCon.hide().eq(currTabsIdx).show();
//			thumShow();
//		});
//		
//		function thumShow(){
//			var $currThum = $thumCon.eq(currTabsIdx),
//				$imgWrap = $currThum.find(".imgWrap"),
//				$thum = $currThum.find(".thum"),
//				$thumImg = $thum.find(".thum_img"),
//				$thumNext = $thum.find(".thum_next"),
//				$thumPrev = $thum.find(".thum_prev"),
//				$thumListInner = $thum.find(".thumList_inner");
//			
//			len = $thumImg.length;
//			w = swidth * len;
//			currentPage = 0;
//			totalPage = parseInt((len + pageSize -1) / pageSize);
//			$thumListInner.width(w);
//			
//			if(totalPage == 1){
//				$thumNext.hide();
//				$thumPrev.hide();
//			}
//			$thumListInner.stop(true,false).animate({left: -swidth * currentPage * pageSize},300);
//			
//			/* 点击小图 */
//			$thumImg.click(function(){
//				index = $thumImg.index(this);			
//				showImg(index);
//			}).eq(0).click();
//			
//			/* 点击小图下一页 */
//			$thumNext.click(function(){
//				thumScrollRight();
//			});
//			
//			/* 点击小图上一页 */
//			$thumPrev.click(function(){			
//				thumScrollLeft();
//			});						
//		}
//		
//		function thumScrollRight(){
//			
//			if(currentPage < totalPage-1){
//				currentPage++;
//				$thumCon.eq(currTabsIdx).find(".thumList_inner").stop(true,false).animate({left: -swidth * currentPage * pageSize},300);				
//			}
//		}
//		
//		function thumScrollLeft(){
//			if(currentPage > 0){
//				currentPage--;
//				$thumCon.eq(currTabsIdx).find(".thumList_inner").stop(true,false).animate({left: -swidth * currentPage * pageSize},300);
//			}
//		}
//		
//		function showImg(index){
//			var $currThum = $thumCon.eq(currTabsIdx),
//				$imgWrap = $currThum.find(".imgWrap"),
//				$thum = $currThum.find(".thum"),
//				$thumImg = $thum.find(".thum_img");
//			$thumImg.find(".box").remove();
//			$thumImg.eq(index).prepend("<span class='box'><s></s></span>").addClass("selected").siblings().removeClass("selected");
//			$imgWrap.find(".con").eq(index).show().siblings().hide();	
//		}
//		
//		/* 点击大图下一 */
//		$thumCon.find(".next").click(function(){
//			if(index < len-1){
//				index++;
//				showImg(index);
//				if(index % pageSize == 0){
//					thumScrollRight();
//				}
//			}else{
//				if($thumTabs.length > 0){
//					currTabsIdx = currTabsIdx < $thumTabs.length-1 ? (currTabsIdx+1) : 0;
//					$thumTabs.eq(currTabsIdx).click();
//				}
//			}
//		});
//		
//		/* 点击大图上一张 */
//		$thumCon.find(".prev").click(function(){
//			if(index > 0){
//				if(index % pageSize == 0){
//					thumScrollLeft();
//				}
//				index--;
//				showImg(index);
//			}else{
//				if($thumTabs.length > 0){
//					currTabsIdx = currTabsIdx > 0 ? (currTabsIdx-1) : $thumTabs.length-1;
//					$thumTabs.eq(currTabsIdx).click();
//				}
//			}
//		});
//		
//	}
//});

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

    //var assortType = !!currentAssortType && !$("#mapIcons").find(".sp_icons").eq(currentAssortType-1).hasClass("li_gray") ? currentAssortType : firstAssortType;
    var assortType = 1;

    function bindEvent(){
        $('.i_close').click(function(){
            $(this).parents('.overLayer').hide();
        });
    }
    bindEvent();
    function initData(){
        var newObj = {};
        $.each(mapData,function(index, data){
            if(!newObj[data.type]){
                newObj[data.type] = [];
            }
            newObj[data.type].push(data.list);
        });
        return newObj;
    }
    var parkAssortObj = initData();

    // 百度地图API功能
    var map = null,
        lastOffsetX,
        lastOffsetY;

    /*默认显示小区标注*/
    function defaultOverlay(){
        var element = '<span class="marker marker1" style="white-space: nowrap;">'+ parkName +'<i class="i_bot2"></i></span>',
            myCustomOverlay = new customOverlay(element, new BMap.Point(parkX,parkY));
        map.addOverlay(myCustomOverlay);
    }

    function initMap(){
        map = new BMap.Map("mapContainer", {enableMapClick: false});
        map.centerAndZoom(new BMap.Point(parkX,parkY), 15);
        map.enableScrollWheelZoom();
        var bottom_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左下角，添加默认缩放平移控件
        map.addControl(bottom_left_control);
        map.addControl(top_left_navigation);

        defaultOverlay();
        $("#mapIcons").find(".sp_icons").removeClass("sp_hover");

        if(!!assortType){
            $("#mapIcons").find(".sp_icons").eq(assortType-1).addClass("sp_hover");
            setData(assortType);
        }
    }
    initMap();

    // 自定义覆盖物
    function customOverlay(element,point){
        this._element = $(element)[0];
        this._point = point;
    }
    customOverlay.prototype = new BMap.Overlay();
    customOverlay.prototype.initialize = function(map){
        this._map = map;
        if(this._element.className.indexOf("marker1") > -1){
            map.getPanes().markerPane.appendChild(this._element);
        }
        else if(this._element.className.indexOf("marker2") > -1){
            map.getPanes().labelPane.appendChild(this._element);
        }
        return this._element;
    }
    customOverlay.prototype.draw = function(){
        var pixel = map.pointToOverlayPixel(this._point);
        if(this._element.className.indexOf("marker1") > -1){
            this._element.style.left = pixel.x - 8 + "px";
            this._element.style.top  = pixel.y - 41 + "px";
        }
        else if(this._element.className.indexOf("marker2") > -1){
            this._element.style.left = pixel.x - 13 + "px";
            this._element.style.top  = pixel.y - 30 + "px";
        }
    }
    customOverlay.prototype.addEventListener = function(event,fun){
        this._element['on'+event] = fun;
    }


    function setData(type){
        var datas = parkAssortObj[type];
        $(map.getPanes().labelPane).empty();
        datas && $.each(datas, function(index,data){
            normalOverlay(data);
        });
    }

    /*周边配套*/
    function normalOverlay(data/*, relationship*/){
        var element = '<a href="javascript:;" class="marker marker2" data-id="'+ data.assort_id +'" data-x="'+ data.x +'"></a>',
            myCustomOverlay = new customOverlay(element, new BMap.Point(data.x,data.y));
        map.addOverlay(myCustomOverlay);
    }

    $("#mapIcons").on("click",".sp_icons",function(){
        /*if($(this).hasClass('li_gray')){
         return false;
         }*/
        //$("#defInfoWindow").hide();
        var type = $(this).attr("data-type");
        assortType = type;
        $(this).siblings().removeClass("sp_hover");
        $(this).addClass("sp_hover");
        setData(assortType);
    });
});