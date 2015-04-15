//页眉部分快速导航
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


// 页卡
var $get = function(){
	var elements = new Array();
	var i = arguments.length;
	for (var i = 0; i < arguments.length; i++) {
		var element = arguments[i];
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (arguments.length == 1) {
			return element;
		}
		elements.push(element);
	}
	return elements;
}
function showtab(m,n,count){
	for(var i=1;i<=count;i++){
		if (i==n){
			$get("td_"+m+"_"+i).className="curr";
			$get("tab_"+m+"_"+i).className="show";
			}
		else {
			$get("td_"+m+"_"+i).className="";
			$get("tab_"+m+"_"+i).className="hidden";
			}
	}
}

$(document).ready(function() {
//首页搜索框确定按钮
 $('.jg_qj .btn').mouseover(
 	function(){
		$(this).addClass('btn-hover');
	});
 $('.jg_qj .btn').mouseout(
 	function(){
		$(this).removeClass('btn-hover');
	});

		
//租房联想框变色
 $('.lenovo li').mouseover(
 	function(){
		$(this).addClass('hover');
	});
 $('.lenovo li').mouseout(
 	function(){
		$(this).removeClass('hover');
	});


//租房首页右侧效果
 $("#zuHost dl dd").hover(function(){
		var tmp = $(this).index();
		$(this).parent("dl").find("dd").removeClass("active");
		$(this).addClass("active");
		$(this).parent("dl").find("dd").children(".pt-box").hide();
		$(this).children(".pt-box").show();
	})
 
//搜索框
	$(".box_menu span").click(
		function () {		 
			if(!$(this).parent().find(".hide_box").is(":animated")){//判断是否处于动画
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
			$(this).parent().parent().children("input[type=hidden]").val(low_price+"－"+top_price);
		} else {
			$(this).parent().parent().parent().find("span").text("不限");
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
	
	$(".news_list a").click(function(){
			$(this).parent().parent().find("span").html($(this).html()).removeClass("active");
			var type = $(this).attr("id");
			if ("news" == type) {
				$("#searchgroup").attr("action", "http://search.focus.cn/search.php");
			} else if ("blog" == type) {
				$("#searchgroup").attr("action", "http://blogsearch.sogou.com/blog");
			}
			
	})
	
	$("#forum_list a").click(function(){
			$(this).parent().parent().find("span").html($(this).html()).removeClass("active");
			var type = $(this).attr("id");
			if ("forum" == type) {
				var action_url = $("#forum_action").val();
				$("#searchyezhugroup").attr("action", action_url);
				$("#query_keyword").attr("name", "title");
				$("#art_type").val("");
			} else if ("article" == type) {
				$("#searchyezhugroup").attr("action", "http://search.focus.cn/search.php");
				$("#query_keyword").attr("name", "query_keyword");
				$("#art_type").val("forum");
			}
			
	})
	
	$("#xinfang").click(function(){
		$(this).parent().find("a").removeClass("active");
		$(this).addClass("active");
		$(".search_menu").find(".box_menu").eq(0).show();
		$(".search_menu").find(".box_menu").eq(1).hide();
		$(".search_menu").find(".box_menu").eq(2).hide();
		$(".box_menu i").css("left","48px");
		$(".search_m_box .box3").hide();
		$(".search_m_box .box3").eq(0).show();
		return false;
	})
	$("#xinwen").click(function(){
		$(this).parent().find("a").removeClass("active");
		$(this).addClass("active");
		$(".search_menu").find(".box_menu").eq(0).hide();
		$(".search_menu").find(".box_menu").eq(1).show();
		$(".search_menu").find(".box_menu").eq(2).hide();
		$(".box_menu i").css("left","325px");
		return false;
	})
	$("#luntan").click(function(){
		$(this).parent().find("a").removeClass("active");					
		$(this).addClass("active");
		$(".search_menu").find(".box_menu").eq(0).hide();
		$(".search_menu").find(".box_menu").eq(1).hide();
		$(".search_menu").find(".box_menu").eq(2).show();
		$(".box_menu i").css("left","367px");
		$(".search_m_box .box3").hide();
		$(".search_m_box .box3").eq(1).show();
		return false;
	})	

	

});

// JavaScript Document