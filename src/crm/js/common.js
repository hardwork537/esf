//吸顶
/*function fixshow(min_height) {
    var window_height = $(window).height();
    var sidebar_height = $(".sidebar").height();
    min_height ? min_height = min_height : min_height = 80;
    $(window).scroll(function () {
        var s = $(window).scrollTop();
        if (s > min_height) {
            $(".sidebar").addClass("affix");
            $(".sidebar").css("height", String(window_height) + "px");
        } else {
            $(".sidebar").removeClass("affix")
            $(".sidebar").css("height", String(sidebar_height) + "px");
        };
    });
};

$(function () {
    fixshow();

    var height = $(window).height() - 80;
    $(".sidebar").css("min-height", String(height) + "px");
    $(".main").css("min-height", String(height) + "px");
})*/

function regurl(url){
var reg=/^\//gi;
  var reg2=/\/$/gi;
  url=url.replace(reg,"");
  return url=url.replace(reg2,"");
}

function checkurl(){
  var locationurl=window.location.pathname;
  locationurl = regurl(locationurl);
  var location_arr=locationurl.split("/");
  var default_arr=["index","show"];
  if(location_arr.length==1){
    location_arr.push(default_arr);
  }else if(location_arr.length==2){
     location_arr.push(default_arr[1])
  }
  locationurl=location_arr.join("/");
  
  $(".sidebar a").each(function(){
    var href_str=$(this).attr("href");
	href_str = regurl(href_str);
	var href_arr=href_str.split("/");
	if(href_arr.length==1){
      href_arr.push(default_arr);
	}else if(href_arr.length==2){
		 href_arr.push(default_arr[1])
	}
	href_str=href_arr.join("/");
	if(href_str==locationurl){
	 $(this).addClass("active");
	}else{
	  $(this).removeClass("active");
	}
  })
}

function caculateh(){
 //左右栏最小高度设置
    var height = $(window).height() - 200;
    $(".sidebar").css("min-height", String(height) + "px");
    $(".main").css("min-height", String(height) + "px");
}

$(function () {

   caculateh();

   //==========左侧导航添加active==============
   checkurl();

	
	
    //===============输入框控制==================
    $(".search_input").focus(function () {
        if (!$(this).val() == '') {
            $(this).siblings(".dropdown-menu").fadeIn("fast");
        } else { $(this).siblings(".dropdown-menu").fadeOut("fast"); }

    }).blur(function () {

        $(this).siblings(".dropdown-menu").fadeOut("fast");

    }).on('input', function (e) {

        if (!$(this).val() == '') {
            $(this).siblings(".dropdown-menu").fadeIn("fast");
        } else { $(this).siblings(".dropdown-menu").fadeOut("fast"); }

    })
    //外部限制10条
    $(".search_form .search_input").on('input', function (e) {

        if ($(this).siblings(".dropdown-menu ").children("li").length > 10) {

            for (var i = 10; i < $(this).siblings(".dropdown-menu").children("li").length; i++) {

                $(this).siblings(".dropdown-menu").children("li").eq(i).hide();
            }
        }

    });

    //内部限制5条
    $(".edittable .search_input").on('input', function (e) {

        if ($(this).siblings(".dropdown-menu ").children("li").length > 5) {
            for (var i = 5; i < $(this).siblings(".dropdown-menu").children("li").length; i++) {
                $(this).siblings(".dropdown-menu").children("li").eq(i).hide();
            }
        }

    });

})

//删除弹出层
$(document).ready(function () {
    $('.td_delete').bind('click', function (e) {
        if (!$(".arrow_tips").is(":visible")) {
            $(this).siblings(".arrow_tips").fadeIn("fast") //显示效果
        }

    })
    $(".close_mylabel").click(function () {
        $(this).parent().parent().fadeOut();
    })
    $(".operate .arrow_tips .btn").click(function () {
        $(this).parent().parent().parent().parent().fadeOut();
    })
});


//返回顶部
function gotoTop(min_height) {
    $("#toTop").click(
        function () {
            $('html,body').animate({ scrollTop: 0 }, 200);
        })
        min_height ? min_height = min_height : min_height = 0;
        $(window).scroll(function () {
            var s = $(window).scrollTop();
            if (s > min_height) {
                $("#toTop").fadeIn(100);
            } else {
                $("#toTop").fadeOut(200);
            };
        });
};
$(function () {
    cacutetotopgap();
    gotoTop();
    $(window).resize(function () {
        cacutetotopgap();
		caculateh();
    });
})

function cacutetotopgap() {
    var winwidth = $(window).width();
    var itemswidth = $(".items").width();
    var totopgap = 0;
    if (winwidth >= 1548) {
        totopgap = itemswidth / 2 + 10;
       
    } else {
        totopgap = winwidth / 2 - 64;
     
    } 
    $("#toTop").css("margin-left", String(totopgap) + "px")
}


//table hover上去底色改变


