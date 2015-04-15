
/*
$(function () {
    //地区,地铁 js交互
    $(".innertabshow").children().hide();
    $(".innertabnav").children().removeClass("on");

    $(".innertabnav").children().click(function () {
        $(this).parent().siblings().removeClass("selected");
        $(this).addClass("on");
        $(this).addClass("selected");
        $(this).siblings().removeClass("on");
        $(this).siblings().removeClass("selected");
        var index = $(this).parent().children().index(this);
        $(this).parent().parent().siblings(".innertabshow").children().eq(index).show().siblings().hide();
    })

    $(".top_filter_item .area .spacial").click(function () {
        $(this).parent().siblings(".innertabshow").children().hide();
        $(this).siblings().children().removeClass("on");
        $(this).siblings().children().removeClass("selected");
        $(this).addClass("selected");

    })

})
*/
//下拉选择层
$(function () {
    $(".item_drop_list").hide();
    $(".item_drop").hover(
		function () {
		    if (!$(this).children(".item_drop_list").is(":animated")) {//判断是否处于动画
		        $(this).find("em").removeClass("arrow_down_a").addClass("arrow_up_a");
		        $(this).children(".item_drop_list").show();
		        $(".item_drop_list").not($(this).children(".item_drop_list")).hide(0);

		    }
		}, function () {
		    if (!$(this).children(".item_drop_list").is(":animated")) {//判断是否处于动画
		        $(this).find("em").removeClass("arrow_up_a").addClass("arrow_down_a");
		        $(this).children(".item_drop_list").hide();
		        

		    }
		});

});

//上部输入框数字判断正误，错误提示
$(function () {

    //$(".input_data").each(function () { $(this).val(""); })

    $(".input_box .input_data").blur(function () {
        if ($(this).parent().parent().find(".input_data").val() == "") {
            return false;
        } else { $(this).parent().parent().find(".btn").show(); }

    })

    $(".input_box .input_data").focus(function () {
        $(".input_data").siblings(".tips").hide();
        $(".input_data").css({ "borderColor": "#b8d2ec", "backgroundColor": "#fff" });
        $(this).css("borderColor", "#1369C0");
		$(this).parents(".inputboxWrap").find(".btn").show();
    })

    $(".inputboxWrap .btn").click(function () {
        var flag = 0;
        $(this).parent().parent().find(".input_data").each(function () {
            var value = $(this).val();
            if (value != '' && !/^[0-9]*[1-9][0-9]*$/.test(value)) {
                $(this).css({ "borderColor": "#d80100", "backgroundColor": "#fffff0" });
                $(this).siblings(".tips").show();
                flag = 1;
            } else {
                $(this).css({ "borderColor": "#b8d2ec", "backgroundColor": "#fff" });
                $(this).siblings(".tips").hide();
            }
        })
        if (flag == 1) return false;

        var max = $(this).parent().parent().children(".input_box").eq(1).find(".input_data").val();
        var min = $(this).parent().parent().children(".input_box").eq(0).find(".input_data").val();
        var type = $(this).parent().parent().children(".input_box").eq(0).find(".input_data").attr('data_type');
        var tongji = $(this).parent().parent().children(".input_box").eq(0).find(".input_data").attr('tongji');
        if (min =='' && max ==''){
            $(this).css({ "borderColor": "#d80100", "backgroundColor": "#fffff0" });
            $(this).siblings(".tips").show();
            return false;
        }
        if (max == ''){
            max = type == 'price' ? 99999 : 9999;
        }
        if (min == ''){
            min = 1;
        }
        if (parseInt(min) > parseInt(max)) {
            var tmp = min;
            min = max;
            max = tmp;
        }
        jumpUrl(min,max,type,tongji);
    })


    $(".icon_close_s").click(function () {
        $(this).parent().hide();
    })

    function jumpUrl(min,max,type, tongji){
        var new_url = window.location.href;
        switch(type){
            case 'price':
                new_url = /^(.+)(j[0-9]+(-[0-9]+){0,1})(.*)$/i.test(new_url)
                    ? new_url.replace(/^(.+)(j[0-9]+(-[0-9]+){0,1})(.*)$/i, '$1j'+min+'-'+max+'$4')
                    : new_url.replace(/^([^?]+)(\??[^?]*)$/i, '$1j'+min+'-'+max+'$2');
                break;
            case 'area':
                new_url = /^(.+)(m[0-9]+(-[0-9]+){0,1})(.*)$/i.test(new_url)
                    ? new_url.replace(/^(.+)(m[0-9]+(-[0-9]+){0,1})(.*)$/i, '$1m'+min+'-'+max+'$4')
                    : new_url.replace(/^([^?]+)(\??[^?]*)$/i, '$1m'+min+'-'+max+'$2');
                break;
        }
        if (tongji ==  1){
            new_url = new_url.replace(/(from=[\w_]+&spm=[\d\w\.]+)(&.+)*/,"$2");
            new_url = new_url.replace('?&','?');
            var indexUrl = new_url.indexOf('?');
            if (indexUrl === -1 || indexUrl == new_url.length - 1){
                new_url = new_url.replace('?','');
                new_url = new_url + '?from=sale_sdsx&spm=o.o.o.18.o';
            }
            else{
                new_url = new_url + '&from=sale_sdsx&spm=o.o.o.18.o';
            }
        }
        window.location.href = new_url;
    }

})

