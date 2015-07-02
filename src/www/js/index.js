$(function(){
	var searchInput = $('#searchInput'),
		autocomplete = $('#autocomplete'),
		searchLenove = $('#searchLenove');
	
	var placeholder = searchInput.attr('placeholder');
	
	/* 初始化 */
	var idx = -1,
		houseName = '';
	
	searchInput.focus(function(){
		if ($(this).val() == placeholder) {
			$(this).val('');
		}
	}).blur(function(){
		if ($(this).val() == '') {
			$(this).val(placeholder);
		}
	});
	
	searchLenove.find('li').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	})
	
	$(document).on('click', function (e) {
        var target = $(e.target);
		if (target.closest($('.search-box')).length == 0) {
            if (!searchLenove.is(':animated')) {
				idx = -1;
                searchLenove.fadeOut(0);
				searchLenove.find('li').removeClass('hover');
				if(searchInput.val() == ''){
					searchInput.val(placeholder);
					searchInput.removeClass('focus');		
				}
            }
        }
    });
	
	/* 键盘输入 */
	searchInput.keyup(function(e){
		strIndexInput = $.trim(searchInput.val());
		
		/* 监听上方向键选择 */
		if(e.keyCode == 38){	
			idx--;
			if(idx < 0){
				idx = searchLenove.find('li').length-1;
			}
			itemHover(idx);
		}
		
		/* 监听下方向键选择 */
		if(e.keyCode == 40){
			idx++;
			if(idx >= searchLenove.find('li').length){
				idx = 0;
			}	
			itemHover(idx);
		}
		
		/* 监听删除键 */
		if(e.keyCode == 8 || e.keyCode == 46){
			//code
		}
		
		//监听回车事件
		if ( e.keyCode == 13 && searchLenove.find("li.hover").length > 0 ) {
			houseName = searchLenove.find("li.hover").attr("title");
			houseId = searchLenove.find("li.hover").attr("id");
			searchInput.val(houseName);
			houseId.val(houseId);
			searchLenove.hide();
			return;
		}
		
		switch ( e.keyCode ) {
			case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}
		
		if( strIndexInput.length < 0 ){
			return;
		}

		$.ajax({
			type: "POST",
			url: "",
			data: '',
			dataType: "json",
			success: function( result ){
                searchLenove.empty();
				if( result == null || result == "null" ){
					return;
				}
				$(result).each(function(i, n){
					$('<li title="'+n.name+'" id="'+n.id+'"><span class="name">'+n.name+'</span><span class="area">'+n.distName+'&nbsp;&nbsp;'+n.regName+'</span><span class="num">约'+n.saleValid+'套</span></li>').appendTo(searchLenove).on({
						"click": function(){
							searchInput.val(n.name);
							//houseId.val(n.id);
						},
						"mouseenter": function(){
							searchLenove.find("li").removeClass("hover");
							$(this).addClass("hover");
						},
						"mouseleave": function(){
							$(this).removeClass("hover");
						}
					});
				});
                searchLenove.show();
			}
		});
	});
	
	function itemHover(idx){
		searchLenove.find('li').removeClass("hover").eq(idx).addClass("hover");
	}
});