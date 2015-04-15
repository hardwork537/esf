//去除前后空格
function trim(str){
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

function getDate() {
    var now = new Date();
    var y = now.getFullYear();
    var m = now.getMonth() + 1;
    var d = now.getDate();
    m = m < 10 ? "0" + m : m;
    d = d < 10 ? "0" + d : d;
    
    return y+"-"+m+"-"+d;
}

function _GET() {
    var _v = [];
    try {
        var $url = location.href;
    } catch (e) {
        var $url = window.top.location.href;
    }
    if($url.indexOf('?')<=0){
        return false;
    }
    $start = $url.indexOf('?') + 1;
    $end = $url.length;
    $Query_String = $url.substring($start, $end);
    
    $Get = $Query_String.split('&');
    for (var i in $Get) {
        $tmp = $Get[i].split('=');
        _v[$tmp[0]] = $tmp[1]; 
        //_v = decodeURIComponent($tmp[1]);
        
    }
    return _v;
}
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

function caculateh() {
    var height = $(window).height() - 200;
    $(".sidebar").css("min-height", String(height) + "px");
    $(".main").css("min-height", String(height) + "px");
}

$(function () {

    //左右栏最小高度设置
    caculateh();


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
//$(document).ready(function () {
//    $('.td_delete').bind('click', function (e) {
//        if (!$(".arrow_tips").is(":visible")) {
//            $(this).siblings(".arrow_tips").fadeIn("fast") //显示效果
//        }
//
//    })
//    $(".close_mylabel").click(function () {
//        $(this).parent().parent().fadeOut();
//    })
//    $(".operate .arrow_tips .btn").click(function () {
//        $(this).parent().parent().parent().parent().fadeOut();
//    })
//});


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
/**
* 检测已经输入多少字
*/
function checkNumber(obj, totalNumber) {
   //已经输入多少字
   var len = obj.val().length,
   obj1 = obj.next().hasClass('b_num') ? obj.next() : obj.next().find('.b_num');
   obj1.html(' '+len+' ');
   if (len >= parseInt(totalNumber)) {
       alert('已经输入最多了。');
       return;
   }
}

/**
* 绑定事件
*/
function bindEvents() {
   //检测已经输入多少字
   var hasInput = $('.ipt_con');
   hasInput.keyup(function() {
       checkNumber($(this), $(this).attr('maxlength'));
   });
}
    
$(function () {  
    bindEvents(); 
    cacutetotopgap();
    gotoTop();
    $(window).resize(function () {
        cacutetotopgap();
        caculateh();
    });
    
    //全选
    $(".checkall").change(function(){
        if($(this).is(":checked")){
            $(".checkall").prop("checked",true)
            $(".checkone").prop("checked",true);
        }else{
            $(".checkall").prop("checked",false)
            $(".checkone").prop("checked", false);
        }
        
    });
    
     //单选
    $(".checkone").change(function(){
        var is_all_check = true;
        $(".checkone").each(function() {
            if(!$(this).is(":checked") && is_all_check) {
                is_all_check = false;
            }
        });
        is_all_check ? $(".checkall").prop("checked", true) : $(".checkall").prop("checked", false);
    });

    //table hover上去底色改变
    function addBgColor(){
        var table_tr = $('.table_reset tbody tr');
        table_tr.each(function(){
            $(this).mouseenter(function(){
                $(this).addClass('hover_bg');
            });
            $(this).mouseleave(function(){
                $(this).removeClass('hover_bg');
            });
        });
    }
    addBgColor();
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




