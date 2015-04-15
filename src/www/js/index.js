$(function(){
	/* focus slider */
	sliderFocus();
	
	/* index search */
	getIdxSuggest();
});

function sliderFocus(){
	var $focus = $("#idx_focus"),
		$prev = $focus.find(".prev"),
		$next = $focus.find(".next"),
		$focusImg = $focus.find(".focusImg"),
		$snav = $focus.find(".snav"),
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
		imgWidth = 740;
	
	$focusImg.css("width",imgWidth*length);
	
	/* 图片大于1的时候，鼠标hover显示左右按钮，并添加底部的圆点 */
	var snavHtml = "";
	if(length > 1){
		for(var i=0; i<length; i++){
			snavHtml += "<span></span>";		
		}
		$snav.append(snavHtml);
		$snavBtn = $snav.find("span");
		$snavBtn.eq(0).addClass("on");
		$focus.hover(function(){
			$focus.find(".btn").show();
		},function(){
			$focus.find(".btn").hide();
		});
		
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
	
	$prev && $prev.click(function(){
		if (!$focusImg.is(":animated")) {
			$focusImg.css("left",-imgWidth);
			$focusImg.find("li:last").clone().prependTo($focusImg);
			scrollLeft();
		}
	});
	
	$next && $next.click(function(){
		if (!$focusImg.is(":animated")) {
			$focusImg.css("left",0);
			$focusImg.find("li:first").clone().appendTo($focusImg);
			scrollRight();
		}
	});
	
    $snavBtn && $snavBtn.click(function(){
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


function getIdxSuggest(){
	var objIndexHouseId = $('#index_house_id');
	var objIndexInput = $("#idxSearchInput");
	var objAutocomplete = $("#idx_autocomplete");
	var defaultValue = objIndexInput.attr("data-holder");
	var closeBtn = $("#idxSearchBox").find(".icon_clear");
	var objHotSearch = $("#idxHotsearch");
	var objLenove = $("#idxLenove");
	
	/* 初始化 */
	var items_idx = -1,
		houseName = "",
		houseId = "",
		flag1 = false,
		flag2 = false;
	
	/* 清除输入框 */
	closeBtn.hover(function(){
		$(this).addClass("icon_clear_hover");
	},function(){
		$(this).removeClass("icon_clear_hover");
	});
	
	closeBtn.click(function(){
		objIndexInput.val("");
		$(this).hide();
	});
	
	/* 获得焦点时显示 */
	objIndexInput.focus(function(){
		objAutocomplete.fadeIn(0);	
		$(this).addClass("focus");
		if(objIndexInput.val() == defaultValue){
			$(this).val("");
			objHotSearch.fadeIn(0);
		}
	})
	
	objAutocomplete.find("li").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	})
	
	/* 热门搜索 */
	objHotSearch.find("li").on("click",function(){
		houseName = $(this).attr("title");
		houseId = $(this).attr("id");
		objIndexInput.val(houseName);
		objIndexHouseId.val(houseId);
		objAutocomplete.fadeOut(0);
		idxSearchSubmit();
	})
	
	$(document).bind("click", function (e) {
        var target = $(e.target);
		if (target.closest($("#idxSearchBox")).length == 0) {
            if (!objAutocomplete.is(":animated")) {
				items_idx = -1;
                objAutocomplete.fadeOut(0);
				objAutocomplete.find("li").removeClass("hover");
				if(objIndexInput.val() == ""){
					objIndexInput.val(defaultValue);
					objIndexInput.removeClass("focus");		
				}
            }
        }
    });
	
	/* 键盘输入 */
	objIndexInput.keyup(function(e){
		strIndexInput = $.trim(objIndexInput.val());
		closeBtn.show();
		flag2 = true;
		
		/* 监听上方向键选择 */
		if(e.keyCode == 38){	
			items_idx--;
			if(items_idx < 0){
				items_idx = objAutocomplete.find("ul:visible li").length-1;
			}
			itemHover(items_idx);
			flag2 = true;
		}
		
		/* 监听下方向键选择 */
		if(e.keyCode == 40){
			items_idx++;
			if(items_idx >= objAutocomplete.find("ul:visible li").length){
				items_idx = 0;
			}	
			itemHover(items_idx);
			flag2 = true;	
		}
		
		/* 监听删除键 */
		if(e.keyCode == 8 || e.keyCode == 46){
			if(strIndexInput == ""){
				closeBtn.hide();
				objIndexHouseId.val("");
				flag2 = false;
			}
		}
		
		//监听回车事件
		if ( e.keyCode == 13 ) {
			if(flag2){
				if(objAutocomplete.find("ul:visible li.hover").length > 0){
					houseName = objAutocomplete.find("ul:visible li.hover").attr("title");
					houseId = objAutocomplete.find("ul:visible li.hover").attr("id");
					objIndexInput.val(houseName);
					objIndexHouseId.val(houseId);
					objAutocomplete.hide();
				}
				idxSearchSubmit();
			}
			return;
		}
		
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		
		if( strIndexInput.length < 0 ){
			return ;
		}

		$.ajax({
			type: "POST",
			url: "/ajax/getParkName",
			data: "q=" + strIndexInput,
			dataType: "json",
			success: function( result ){
                objHotSearch.hide();
                objLenove.empty();
				if( result == null || result == "null" ){
					return;
				}
				$(result).each(function(i, n){
					var title = n.name.replace(/<[^>]+>/g, "");
					$('<li title="'+title+'"><a href="javascript:void(0)" id="'+n.id+'"><i>约'+n.saleValid+'套</i>'+n.name+'<span class="area">'+n.distName+'&nbsp;&nbsp;'+n.regName+'</span></a></li>').appendTo(objLenove).on({
						"click": function(){
							objIndexInput.val(title);
							objIndexHouseId.val( n.id );
							objAutocomplete.hide();
							idxSearchSubmit();
							return false;
						},
						"mouseenter": function(){
							objLenove.find("li").removeClass("hover");
							$(this).addClass("hover");
						},
						"mouseleave": function(){
							$(this).removeClass("hover");
						}
					});
				});
                objLenove.show();
			}
		});
		
		function itemHover(items_idx){
			objAutocomplete.find("ul:visible li").removeClass("hover");
			objAutocomplete.find("ul:visible li").eq(items_idx).addClass("hover");
		}
	});
	
	function idxSearchSubmit(){
		var strText = objIndexInput.val();
		if( strText == defaultValue ) return ;
		var strHeadDomain = $('#index_domain').val()? $('#index_domain').val(): '';
		var strHeadData = $('#index_data').val()? '/'+$('#index_data').val()+'/': '/sale/';
		var strParam = $('#index_house_id').val()!=0
				? $('#index_data').val()=='xiaoqu'? $('#index_house_id').val(): 'x'+$('#index_house_id').val()
				: '?k='+strText;
		window.location.href = strHeadDomain + strHeadData + strParam;
	}
}




