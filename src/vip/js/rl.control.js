$(document).ready(function() {
    // 登录框切换
    $(".lb_title li").live("click", function() {
        var li = $(this);
        li.siblings().removeClass("current").end().addClass("current");
        $(".login_mod").hide();
        $(li.children("a").attr("href")).show();
        return false;
    });



    function fixed(){
        $(".lb_txt[name!='yanzheng']").each(function () {
            $(this).focus();
            if ($(this).val() !='') {
                $(this).siblings(".placeholder").hide();
            }
            $(this).blur();
        })
    }

    $(".lb_txt[name!='yanzheng']").each(function () {
        if ($(this).val() !='') {
            $(this).siblings(".placeholder").hide();
            $(this).addClass("txt_blur");
        }

    })
    
    // 文本框默认输入
     $(".lb_txt").focus(function() {
        var $this = $(this);
        $this.addClass("txt_focus");
        if ($this.val() == this.defaultValue) {
            $this.val('');
        }
        if ($this.siblings(".placeholder")) {
            $this.siblings(".placeholder").hide();
        }        
    }).blur(function() {
        var $this = $(this);
        if ($this.val() == '') {
            $this.val(this.defaultValue).removeClass("txt_focus").removeClass("txt_blur");
            
            if ($this.siblings(".placeholder")) {
                $this.siblings(".placeholder").show();
            }
        } else {
            $this.removeClass("txt_focus").addClass("txt_blur");
        }
    });
    
    $(".placeholder").click(function() {
        var $this = $(this);
        $this.hide();
        $this.siblings(".lb_txt").show().focus();
    });
    
    $(".entering_form .btn").hover(function () {
        $(this).addClass("btn_hover");
    }, function() {
        $(this).removeClass("btn_hover");
    });

    // forget password popup
    $(".busi_forget").hover(function () {
        $(".popup_service").show();
    }, function () {
        $(".popup_service").hide();
    });

    // select_box控件
    $(".select_box").click(function (event){   
        event.stopPropagation();
        $(this).find(".options").toggle();
    });

    $(".select_box .select").click(function () {
        $(this).siblings("options").toggle();
    });

    $(document).click(function (event){
        var eo = $(event.target),
            city = $("#city");
        if($(".select_box").is(":visible") && eo.attr("class")!="options" && !eo.parent(".options").length) {
            $('.options').hide();
        }
        if(city.hasClass("selected")) {
            city.removeClass("selected");
            city.find(".city_details_box").hide();
        }
    });

    // 赋值给文本框
    $(".options a").click(function (e) {
        e.stopPropagation();
        var option = $(this);
        option.parent().siblings("span").text(option.text());
        option.parent().siblings("input").val(option.attr('val'));//给隐藏域赋值
        option.parent().hide();
        if(option.parent().siblings("input").attr('id') == 'district_id'){
        	$.post("/ajax/getCityRegion", {city_id: $('#city_id').val(), district_id: $('#district_id').val()},
        	   function(data){
        		  if(data){
        			 $("#hot_area_id").siblings(".options").empty();
        			 $('<a href="javascript:;" val="0">板块</a>').appendTo($("#hot_area_id").siblings(".options"));
        			 $.each(data, function(i, n){
        			    $('<a href="javascript:;" val="'+i+'">'+n+'</a>').appendTo($("#hot_area_id").siblings(".options"));
        			    $("#hot_area_id").siblings(".options").children('a').click(function (e){
        			    	e.stopPropagation();
        			        var option = $(this);
        			        option.parent().siblings("span").text(option.text());
        			        option.parent().siblings("input").val(option.attr('val'));//给隐藏域赋值
        			        option.parent().hide();
        			        validate_option(option.parent());//验证所选所在城市
        			    });
      	             });
        		  }else{
        			 $("#hot_area_id").siblings(".options").html('<a href="javascript:;" val="0">板块</a>');
        		  }
        	   },"json"
        	);
        }
        validate_option(option.parent());//验证所选所在城市
    });

    //城市下拉框改版
    $(".city_box").click(function(){
        $(this).toggleClass("selected");
        $(".city_details_box").toggle();
    });
    var $title = $(".title");
    $title.on("mouseover","span",function(){
        $(this).addClass("s_hover");
    });
    $title.on("mouseout","span",function(){
        $(this).removeClass("s_hover");
    });
    $title.on("click","span",function(e){
        e.stopPropagation();
        var index = $(this).index();
        $(this).siblings("span").removeClass("current");
        $(this).addClass("current");
        $(".city_details_box").find(".container").removeClass("current");
        $(".city_details_box").find(".container").eq(index).addClass("current");
    });
    $(".city_box a").click(function(e){
        e.stopPropagation();
        var option = $(".city_details_box");
        option.siblings("span").text($(this).text());
        option.siblings("input").val($(this).attr('val'));//给隐藏域赋值 
        option.hide();
        option.parent().removeClass("selected");
        $.post("/ajax/getDistrict", {city_id: $('#city_id').val(), district_id: $('#district_id').val()},
            function(data){
                if(data){
                    $("#district_id").siblings(".options").empty();
                    $("#hot_area_id").siblings(".options").empty();
                    $('<a href="javascript:;" val="0">城区</a>').appendTo($("#district_id").siblings(".options"));
                    $('<a href="javascript:;" val="0">板块</a>').appendTo($("#hot_area_id").siblings(".options"));
                    $.each(data, function(i, n){
                        $('<a href="javascript:;" val="'+i+'">'+n+'</a>').appendTo($("#district_id").siblings(".options"));
                        $("#district_id").siblings(".options").children('a').unbind('click');
                        $("#district_id").siblings(".options").children('a').click(function (e){
                            e.stopPropagation();
                            var option = $(this);
                            option.parent().siblings("span").text(option.text());
                            option.parent().siblings("input").val(option.attr('val'));//给隐藏域赋值
                            option.parent().hide();
                            if(option.parent().siblings("input").attr('id') == 'district_id'){
                                $.post("/ajax/getCityRegion", {city_id: $('#city_id').val(), district_id: $('#district_id').val()},
                                    function(data){
                                        if(data){
                                            $("#hot_area_id").siblings(".options").empty();
                                            $('<a href="javascript:;" val="0">板块</a>').appendTo($("#hot_area_id").siblings(".options"));
                                            $.each(data, function(i, n){
                                                $('<a href="javascript:;" val="'+i+'">'+n+'</a>').appendTo($("#hot_area_id").siblings(".options"));
                                                $("#hot_area_id").siblings(".options").children('a').click(function (e){
                                                    e.stopPropagation();
                                                    var option = $(this);
                                                    option.parent().siblings("span").text(option.text());
                                                    option.parent().siblings("input").val(option.attr('val'));//给隐藏域赋值
                                                    option.parent().hide();
                                                    validate_option(option.parent());//验证所选所在城市
                                                });
                                            });
                                        }else{
                                            $("#hot_area_id").siblings(".options").html('<a href="javascript:;" val="0">板块</a>');
                                        }
                                    },"json"
                                );
                            }
                            validate_option(option.parent());//验证所选所在城市
                        });
                    });
                }else{
                    $("#district_id").siblings(".options").html('<a href="javascript:;" val="0">城区</a>');
                    $("#hot_area_id").siblings(".options").html('<a href="javascript:;" val="0">板块</a>');
                }
            },"json"
        );
        validate_option(option);//验证所选所在城市
    });
});