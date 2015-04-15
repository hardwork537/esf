$(function(){
	/* park search */
	getIdxSuggest();
});

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
			url: "/ajax/getParkName/",
			data: "q=" + strIndexInput,
			dataType: "json",
			success: function( result ){
                objHotSearch.hide();
                objLenove.empty();
				if( result == null || result == "null" ){
					return;
				}
				$(result).each(function(i, n){
					$('<li title="'+n.name+'"><a href="javascript:void(0)" id="'+n.id+'"><i>约'+n.houseValidSum+'套</i>'+n.name+'<span class="area">'+n.distName+'&nbsp;&nbsp;'+n.regName+'</span></a></li>').appendTo(objLenove).on('click',function (){
						objIndexInput.val(n.name);
						objIndexHouseId.val( n.id );
						objAutocomplete.hide();
						idxSearchSubmit();
						return false;
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




