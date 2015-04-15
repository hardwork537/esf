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

//详情页焦点图
function show(index){
	$(".focus_pic li").stop(false,true).fadeOut();
	$(".focus_pic li").eq(index).stop(false,true).fadeIn();
	$(".focus_about li").stop(false,true).fadeOut();
	$(".focus_about li").eq(index).stop(false,true).fadeIn();
	$(".focus_btn li").removeClass("active")
	.eq(index).addClass("active");
}

//
$(document).ready(function() {
	if(document.getElementById('td_2_1')) document.getElementById('td_2_1').className="curr";//热门小区默认显示第一个
	if(document.getElementById('tab_2_1')) document.getElementById('tab_2_1').className="show";//热门小区默认显示第一个

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

 //经纪人列表页搜索按钮变色
 $('.serchBarCen .blockSH .btn').mouseover(
 	function(){
		$(this).addClass('btn-hover');
	});
 $('.serchBarCen .blockSH .btn').mouseout(
 	function(){
		$(this).removeClass('btn-hover');
	});

 //价格范围确定按钮变色
 $('.jg_qj .btn').mouseover(
 	function(){
		$(this).addClass('btn-hover');
	});
 $('.jg_qj .btn').mouseout(
 	function(){
		$(this).removeClass('btn-hover');
	});

 //列表页确定按钮变色
 $('.serchBarCen .blockSD .inputBox .btn').mouseover(
 	function(){
		$(this).addClass('btn-hover');
	});
 $('.serchBarCen .blockSD .inputBox .btn').mouseout(
 	function(){
		$(this).removeClass('btn-hover');
	});
//列表页房源列表变色
 $('.boxLC .boxLClist').mouseover(
 	function(){
		$(this).addClass('hover');
	});
 $('.boxLC .boxLClist').mouseout(
 	function(){
		$(this).removeClass('hover');
	});
//个人中心求购列表
$(".boxLC table tr").hover(function(){
	$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
		});
$(".boxLC table").find("tr:last").addClass("last");

//下拉选择层
	$(".item-drop").hover(
		function(){
			if(!$(this).children(".item-drop-list").is(":animated")){//判断是否处于动画
				$(this).addClass("hover");
				$(this).children(".item-drop-list").show();
				$(".item-drop-list").not($(this).children(".item-drop-list")).hide(0);
				return false;
			}
		},function(){
			if(!$(this).children(".item-drop-list").is(":animated")){//判断是否处于动画
				$(this).removeClass("hover");
				$(this).children(".item-drop-list").hide();
				//$(".item-drop-list").not($(this).children(".item-drop-list")).fadeIn(0);
				return false;
			}
		});

	$(".item-drop-list span a").click(function(event){
		$(this).parent().parent().parent().removeClass("hover");
		var text = $(this).text()
		var svalue = $(this).attr("src");
		$(this).parent().parent().parent(".item-drop").children("em").text(text);
		$(this).parent().parent().parent(".item-drop").children("input[type=hidden]").val(svalue);
		$(this).parent().parent().fadeOut();
		return false;
	});

	$(document).click(function(event){
		$(".item-drop-list").fadeOut(200);
		$(".item-drop-list").parent().removeClass("hover");
	});


	//文本域变色
	 $(".txtarea").focus(function(){

	 	$(this).css("color","#333");
	 	if($(this).val() ==this.defaultValue)
		{
           $(this).val("");
 		}
	 }).blur(function(){
		if ($(this).val() == '')
		{
		  $(this).val(this.defaultValue);
		  $(this).css("color","#999");
		}
	});



	//分页滚动
	//scrollLogo("#ppA","1","3","#leftA","#rightA");
});

//分页滚动
function scrollLogo(obj,sun,cot,leftID,rightID){
	var $cur = 1;//初始化显示的版面
	var $i = sun;//每版显示数
	var $i1 = cot;//每行显示几个
	var $i2 = Math.ceil($i1 - $i);//每行显示几个
    var $len = $(obj).children().length;
    var $pages = Math.ceil($len / $i -$i2);
	var $w = $(obj).children().width();
	if($pages==1){
		$(rightID).addClass("arrNone")
		$(leftID).addClass("arrNone")
	}
	$(rightID).click(function(){

        if (!$(obj).is(':animated')) {
            if ($cur != $pages) {
				$(obj).animate({
                    left: '-=' + $w
                }, 500);
                $cur++;
				$(leftID).addClass("arrL")
				if ($cur == $pages) {
					//$(rightID).addClass("arrR-n")

					}
            }
        }
    });
	$(leftID).click(function(){
       if (!$(obj).is(':animated')) {  //判断展示区是否动画
            if ($cur == 1) {   //在第一个版面时,再向前滚动到最后一个版面
              //  $(".arrR").removeClass("arrR-n")
            }
            else {
                $(obj).animate({
                    left: '+=' + $w
                }, 500); //改变left值,切换显示版面
                $cur--; //版面累减
				//$(".arrR").removeClass("arrR-n")
				//if ($cur == 1) {
					//$(leftID).removeClass("arrL")
					//$(rightID).removeClass("arrR-n")
					//}
            }
        }
    });


}
// JavaScript Document