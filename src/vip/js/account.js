$(function(){
	getShopSuggest();
});
function getShopSuggest(){
	var objShopId = $('#shoId');
	var objShopInput = $("#shopInput");
	var objAutocomplete = $("#shop_list");
	var defaultValue = objShopInput.attr("nullmsg");
	
	/* 初始化 */
	var items_idx = -1,
		shopName = "",
		shopId = "",
		flag1 = false,
		flag2 = false;
	
	/* 获得焦点时显示 */
	objShopInput.focus(function(){
		$(this).addClass("focus");
		if(objShopInput.val() == defaultValue){
			$(this).val("");
		}
	})
	
	$(document).bind("click", function (e) {
        var target = $(e.target);
		if (target.closest($("#shop")).length == 0) {
            if (!objAutocomplete.is(":animated")) {
				items_idx = -1;
                objAutocomplete.fadeOut(0);
				objAutocomplete.find("li").removeClass("hover");
				if(objShopInput.val() == ""){
					objShopInput.val(defaultValue);
					objShopInput.removeClass("focus");		
				}
            }
        }
    });
	
	/* 键盘输入 */
	objShopInput.keyup(function(e){
		strShopInput = $.trim(objShopInput.val());
		var $list = $("#shopList");
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
			if(strShopInput == ""){
				//closeBtn.hide();
				objShopId.val("");
				flag2 = false;
			}
		}
		
		//监听回车事件
		if ( e.keyCode == 13 ) {
			if(flag2){
				if(objAutocomplete.find("li.hover").length > 0){
					shopName = objAutocomplete.find("li.hover").attr("title");
					shopId = objAutocomplete.find("li.hover").attr("id");
					objShopInput.val(shopName);
					objShopId.val(shopId);
					objAutocomplete.hide();
				}
			}
			return;
		}
		
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		
		if( strShopInput.length < 0 ){
			return ;
		}

		$.ajax({
			type: "POST",
			url: "/ajax/getShopByComID",
			data: {q_word: strShopInput, xz_company_id: $("#comId").val()},
			dataType: "json",
			success: function( result ){
                $list.empty();
				if( result == null || result == "null" ){
					return;
				}
	            var content = "";
	            if(result){
	                $.each(result, function(i, n){
	                    content += "<li><a href='javascript:;' val='"+ n.shop_id+"'>"+n.shop_name+"</a></li>";
	                });
	            }
	            content += "<li><a href='javascript:;' val='0'>其他门店</a></li>";
	            $list.append(content);
	            $list.fadeIn();
	            $list.find("li").click(function(){
                    $(this).parent().siblings(".placeholder").hide();
	                $('#shopInput').val($(this).text());
	                $('#shopId').val($(this).find("a").attr("val"));
	                $('#shopInput').blur();
	                $list.fadeOut();
	         	   if($("#shopId").val() == 0)
	    		   {
	    			   $("#em_district").parents(".dropdown").attr("disable","");
	    			   $("#em_region").parents(".dropdown").attr("disable","");
	    		   }
	    		   else
	    		   {
	    			   data = getDistRegion();
	    			   if(data.disId != "")
	    			   {
	    				   $("#em_district").text(data.distName);
	    				   $("#em_region").text(data.regName);
	    				   $("#distinct_id").val(data.distId);
	    				   $("#region_id").val(data.regId);
	    				   $("#em_district").parents(".dropdown").attr("disable","disabled");
	    				   $("#em_region").parents(".dropdown").attr("disable","disabled");	
	    			   }
	    		   }
	            });
			}
		});
		
		function itemHover(items_idx){
			objAutocomplete.find("li").removeClass("hover");
			objAutocomplete.find("li").eq(items_idx).addClass("hover");
		}
	});
}