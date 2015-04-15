//ҳü���ֿ��ٵ���
function showQuick(aid,did){
    var obj = document.getElementById(aid);
    var divotherChannel=document.getElementById(did);
    obj.className = "menu_btn hover";
    divotherChannel.style.display = "block";
}
function hideQuick(aid,did){
    var divotherChannel=document.getElementById(did);
    var mydd=document.getElementById(aid);
    if(divotherChannel.style.display!="none"){
            divotherChannel.style.display="none";
            mydd.className="menu_btn";
    }
}



$(document).ready(function() {
    getParkSuggest();
//下拉选择层
	$(".item-drop-list").hide();
	$(".item-drop").click(
		function(){		 
			if(!$(this).children(".item-drop-list").is(":animated")){//�ж��Ƿ��ڶ���
				$(this).addClass("hover");
				$(this).children(".item-drop-list").fadeIn();
				$(".item-drop-list").not($(this).children(".item-drop-list")).fadeOut(0);
				return false;
			}			 
		});
	$(".item-drop-list span a").click(function(event){
		var objThis2Parent = $(this).parent().parent();
		objThis2Parent.parent().removeClass("hover");
		var text = $(this).text()
		var svalue = $(this).attr("src");
		var objThis3Parent = objThis2Parent.parent(".item-drop");
		objThis3Parent.children("em").text(text);
		objThis3Parent.children("input[type=hidden]").val(svalue).focus().blur();
		objThis2Parent.fadeOut();
		$("#search_form").attr("action", $(this).attr("target"));
		return false;
	});
	$(document).click(function(event){
		$(".item-drop-list").fadeOut(200);
		$(".item-drop-list").parent().removeClass("hover");
	});	
	
	//�۸�Χȷ����ť��ɫ
 $('.searchBot .inputBox_k .btn').mouseover(
 	function(){
		$(this).addClass('btn-hover');
	});
 $('.searchBot .inputBox_k .btn').mouseout(
 	function(){
		$(this).removeClass('btn-hover');
	});

	//�ı������ֱ�ɫ
 $('.textAreaBox textarea').focus(
 	function(){
	    $(this).addClass('btn-hover');
	 });
 $('.textAreaBox textarea').mouseover(
 	function(){
	    $(this).removeClass('btn-hover');
	 });

		
//�ⷿ������ɫ
 $('.lenovo li').mouseover(
 	function(){
		$(this).addClass('hover');
	});
 $('.lenovo li').mouseout(
 	function(){
		$(this).removeClass('hover');
	});
	
//������
	$(".box_menu span").click(
		function () {		 
			if(!$(this).parent().find(".hide_box").is(":animated")){//�ж��Ƿ��ڶ���
				$(this).parent().parent().find("span").removeClass("active");
				$(this).addClass("active");
				
				$(".hide_box").hide();
				$(this).parent().find(".hide_box").fadeIn();
				//$(".hide_box").not($(this).find(".hide_box")).fadeOut(0);
				return false;
			}			 
		});
	$(".hide_box").click(function(){
		return false;							  
	})
	
	$(".hide_box a").click(function(event){
		var text = $(this).html()
		var svalue = $(this).attr('val')
		
		$(this).parent().parent().parent().find("span").html(text).removeClass("active");
		//$(this).parent().parent("#select_list").find("input[type=hidden]").val(svalue);
		$(this).parent().parent().children("input[type=hidden]").val(svalue);
		$(this).parent().parent(".hide_box").fadeOut();
		
		return false;
	});
	$("#priceset").click(function(event){
		var low_price = $("#low_price").val();
		var top_price = $("#top_price").val();
		if (low_price>0 || top_price>0)
		{
			$(this).parent().parent().parent().find("span").text(low_price+"-"+top_price);
			$(this).parent().parent().children("input[type=hidden]").val(low_price+"��"+top_price);
		} else {
			$(this).parent().parent().parent().find("span").text("����");
			$(this).parent().parent().children("input[type=hidden]").val("");
		}
		$(this).parent().parent().parent().find("span").removeClass("active");
		$(this).parent().parent(".hide_box").fadeOut();	
		return false;
	});
	
	$(document).click(function(event){
		$(".hide_box").fadeOut(200);
		$(".box_menu.newsbg .news .news_list").fadeOut();
		$(".box_menu span").removeClass("active");
	});
	
	
	$(".box_menu.newsbg .news span").click(function(){
		if(!$(this).parent().find(".hide_box").is(":animated")){
			
			$(this).parent().find(".news_list").fadeIn();
			
		}
	})
	

});

$(function(){

   //��¼ע��
	

   $('.dengLu .newUser').click(function(){
	   var h = $(document).height();
	   $('#screen').css({ 'height': h });	
	   $('#screen').show();
	   $('#popBox02').center();
	   $('#popBox02').fadeIn();
	   $('#popBox').hide();
	  return false;
   });
   
})

jQuery.fn.center = function(loaded) {
  var obj = this;
  if ( obj.is(':hidden')) return false;
  body_width = parseInt($(window).width());
  body_height = parseInt($(window).height());
  block_width = parseInt(obj.width());
  block_height = parseInt(obj.height());

  left_position = parseInt((body_width/2) - (block_width/2)  + $(window).scrollLeft());
  if (body_width<block_width) { left_position = 0 + $(window).scrollLeft(); };

  top_position = parseInt((body_height/2) - (block_height/2) + $(window).scrollTop());
  if (body_height<block_height) { top_position = 0 + $(window).scrollTop(); };

  if(!loaded) {

	  obj.css({'position': 'absolute'});
	  obj.css({ 'top': top_position, 'left': left_position });
	  $(window).bind('resize', function() {
		  obj.center(!loaded);
	  });
	  $(window).bind('scroll', function() {
		  obj.center(!loaded);
	  });

  } else {
	  obj.stop();
	  obj.css({'position': 'absolute'});
	  obj.animate({ 'top': top_position }, 200, 'linear');
  }
}

	
// JavaScript Document
function getParkSuggest(){
    var objParkId = $('#person_house_id');
    var objParkInput = $("#person_house_name");
    var objAutocomplete = $("#parkAutocomplete");

    /* 初始化 */
    var items_idx = -1,
        houseName = "",
        houseId = "",
        flag1 = false,
        flag2 = false;

    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest($("#idx_autocomplete")).length == 0) {
            if (!objAutocomplete.is(":animated")) {
                items_idx = -1;
                objAutocomplete.fadeOut(0);
                objAutocomplete.find("li").removeClass("hover");
                if(objParkInput.val() == ""){
                    objParkInput.removeClass("focus");
                }
            }
        }
    });

    /* 键盘输入 */
    objParkInput.keyup(function(e){
        strIndexInput = $.trim(objParkInput.val());
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
                objParkId.val("");
                flag2 = false;
            }
        }

        //监听回车事件
        if ( e.keyCode == 13 ) {
            if(flag2){
                if(objAutocomplete.find("li.hover").length > 0){
                    houseName = objAutocomplete.find("li.hover").attr("title");
                    houseId = objAutocomplete.find("li.hover").attr("id");
                    objParkInput.val(houseName);
                    objParkId.val(houseId);
                    objAutocomplete.hide();
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

        $.ajax({
            type: "POST",
            url: "/ajax/getParkName/",
            data: "q=" + strIndexInput + "&cityId=" + cityId + "&is_showb=0",
            dataType: "json",
            success: function( result ){
                objAutocomplete.empty();
                if( result == null || result == "null" ){
                    return;
                }
                $(result).each(function(i, n){
                    $('<li title="'+n.name+'" id="'+n.id+'">'+n.name+'</li>').appendTo(objAutocomplete).on('click',function (){
                        houseName = $(this).text();
                        houseId = $(this).attr("id");
                        objParkInput.val(houseName);
                        objParkId.val(houseId);
                        objAutocomplete.hide();
                        setRight($(objParkInput));
                        return false;
                    });
                });
                objAutocomplete.show();
            }
        });

        function itemHover(items_idx){
            objAutocomplete.find("li").removeClass("hover");
            objAutocomplete.find("li").eq(items_idx).addClass("hover");
        }

        objAutocomplete.find("li").hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });

    });

}

function getParkSuggest(){
    var objParkId = $('#person_house_id');
    var objParkInput = $("#person_house_name");
    var objAutocomplete = $("#parkAutocomplete");
    var objParkAddress = $("#personParkAddress");

    /* 初始化 */
    var items_idx = -1,
        houseName = "",
        houseId = "",
        flag1 = false,
        flag2 = false,
        parkAddress='';

    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest($("#idx_autocomplete")).length == 0) {
            if (!objAutocomplete.is(":animated")) {
                items_idx = -1;
                objAutocomplete.fadeOut(0);
                objAutocomplete.find("li").removeClass("hover");
                if(objParkInput.val() == ""){
                    objParkInput.removeClass("focus");
                }
            }
        }
    });

    /* 键盘输入 */
    objParkInput.keyup(function(e){
        strIndexInput = $.trim(objParkInput.val());
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
                objParkId.val("");
                flag2 = false;
            }
        }

        //监听回车事件
        if ( e.keyCode == 13 ) {
            if(flag2){
                if(objAutocomplete.find("li.hover").length > 0){
                    houseName = objAutocomplete.find("li.hover").attr("title");
                    houseId = objAutocomplete.find("li.hover").attr("id");
                    parkAddress = objAutocomplete.find("li.hover").attr("address");
                    objParkInput.val(houseName);
                    objParkId.val(houseId);
                    objParkAddress.html(parkAddress).show();
                    objAutocomplete.hide();
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

        /* 模拟数据
         objAutocomplete.empty();
         var li = '<li title="仁恒河滨城" id="1">仁恒河滨城</li><li title="瑞虹新城一期" id="2">瑞虹新城一期</li><li title="中凯城市之光" id="3">中凯城市之光</li>';
         $(li).appendTo(objAutocomplete).on('click',function (){
         houseName = $(this).text();
         houseId = $(this).attr("id");
         objParkInput.val(houseName);
         objParkId.val(houseId);
         objAutocomplete.hide();
         setRight($(objParkInput));
         return false;
         });
         objAutocomplete.show(); */
        var cityId = $("#cityId").val();
        $.ajax({
            type: "POST",
            url: "/ajax/getParkName/",
            data: "q=" + strIndexInput + "&cityId=" + cityId + "&is_showb=0",
            dataType: "json",
            success: function( result ){
                objAutocomplete.empty();
                if( result == null || result == "null" ){
                    return;
                }
                $(result).each(function(i, n){
                    $('<li address="'+ n.address +'" title="'+n.name+'" id="'+n.id+'">'+n.name+'</li>').appendTo(objAutocomplete).on('click',function (){
                        houseName = $(this).text();
                        houseId = $(this).attr("id");
                        parkAddress = $(this).attr("address");
                        objParkInput.val(houseName);
                        objParkId.val(houseId);
                        objParkAddress.html(parkAddress).show();
                        objAutocomplete.hide();
                        setRight($(objParkInput));
                        return false;
                    });
                });
                objAutocomplete.show();
            }
        });

        function itemHover(items_idx){
            objAutocomplete.find("li").removeClass("hover");
            objAutocomplete.find("li").eq(items_idx).addClass("hover");
        }

        objAutocomplete.find("li").hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });

    });

}



