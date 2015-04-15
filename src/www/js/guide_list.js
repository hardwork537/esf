$(function(){
	tabs(".footer_xqHot .tab_nav li",".footer_xqHot .tab_con ul");
	
	/* topFilters */
	$("#topFilters").find(".selected_list dd span").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});
	
	/* list items hover */
	var $items = $("#listItem").find(".items");
	$items.hover(function(){
		$(this).addClass("items_hover");
	},function(){
		$(this).removeClass("items_hover");
	});
	
	/* guide search */
	guideSuggest();
	
})
function guideSuggest(){
	var objIndexHouseId = $('#guide_house_id');
	var objIndexInput = $("#guideSearchInput");
	var objAutocomplete = $("#guideAutocomplete");
	var defaultValue = objIndexInput.attr("data-holder");
	var closeBtn = $("#guideSearch").find(".icon_clear");
	
	/* 初始化 */
	var items_idx = -1,
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
		$(this).addClass("focus");
		if(objIndexInput.val() == defaultValue){
			$(this).val("");
		}
	})
	
	$(document).bind("click", function (e) {
        var target = $(e.target);
		if (target.closest($("#guideSearch")).length == 0) {
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
			if(strIndexInput == ""){
				closeBtn.hide();
				objIndexHouseId.val("");
				flag2 = false;
			}
		}
		
		//监听回车事件
		if ( e.keyCode == 13 ) {
			if(flag2){
				if(objAutocomplete.find("li.hover").length > 0){
					houseName = objAutocomplete.find("li.hover").attr("title");
					houseId = objAutocomplete.find("li.hover").attr("id");
					objIndexInput.val(houseName);
					objIndexHouseId.val(houseId);
					objAutocomplete.hide();
					guideSearchSubmit();
				}
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
		
		/*$.ajax({
			type: "POST",
			url: "/ajax/getParkName",
			data: "q=" + strIndexInput,
			dataType: "json",
			success: function( result ){
                objAutocomplete.empty();
				if( result == null || result == "null" ){
					return;
				}
				$(result).each(function(i, n){
					$('<li title="'+n.name+'">'+n.name+'</li>').appendTo(objAutocomplete).on({
						"click": function(){
							objIndexInput.val(n.name);
							objAutocomplete.hide();
							guideSearchSubmit();
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
		});*/
		
		function itemHover(items_idx){
			objAutocomplete.find("li").removeClass("hover");
			objAutocomplete.find("li").eq(items_idx).addClass("hover");
		}
	});
	
	function guideSearchSubmit(){
		var strText = objIndexInput.val();
		if( strText == defaultValue ) return ;
		/* 添加链接 */
	}
}