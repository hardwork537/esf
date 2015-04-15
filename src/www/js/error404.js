$(function(){

    function funSwitch(obj){
        obj.hover(function(){
            if($(this).eq(0).parent().hasClass('dd_item_1')){
                obj.removeClass('hover');
            }
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
            if($(this).eq(0).parent().hasClass('dd_item_1')){
                $(this).addClass('hover');
            }
        });

        obj.each(function(){
            $(this).find('a').each(function(){
                $(this).click(function(e){
                    if(e.stopPropagation){
                        e.stopPropagation();
                    }else{
                        window.event.cancelable = true;
                    }
                });
            });

            $(this).on("click",function(event){
                var a = document.createElement("a");
                a.href = $(this).find('a.link').attr('href');
                a.target = "_blank";
                document.body.appendChild(a);
                a.click();
            });
        });
    }
    funSwitch($('.error_b .dd_item'));
    funSwitch($('.dd_item_1 li'));

    //相关房源横向滚动
    function recSlider(obj){
        var $slider = $(obj),
            $ul = $slider.find(".slider_wrap ul"),
            $li = $ul.find("li"),
            $prev = $slider.find(".prev"),
            $next = $slider.find(".next");

        $li.hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });

        $li.each(function(){
            $(this).find('a').each(function(){
                $(this).click(function(e){
                    if(e.stopPropagation){
                        e.stopPropagation();
                    }else{
                        window.event.cancelable = true;
                    }
                });
            });

            $(this).on("click",function(event){
                var a = document.createElement("a");
                a.href = $(this).find('a.link').attr('href');
                a.target = "_blank";
                document.body.appendChild(a);
                a.click();
            });
        });

        //初始化
        var len = $li.length,
            w = 190 * len,
            currentPage = 0,
            pageSize = 5,
            totalPage = parseInt((len + pageSize -1) / pageSize);
        $ul.width(w);
        if(totalPage == 1){
            $prev.hide();
            $next.hide();
        }

        //点击下一页
        $next.click(function(){
            scrollRight();
        });

        //点击上一页
        $prev.click(function(){
            scrollLeft();
        });

        function scrollRight(){
            currentPage = (currentPage < totalPage-1) ? currentPage+1 : 0;
            $ul.stop(true,false).animate({left: -190 * currentPage * pageSize},300);
        }

        function scrollLeft(){
            currentPage = (currentPage > 0) ? currentPage-1 : totalPage-1;
            $ul.stop(true,false).animate({left: -190 * currentPage * pageSize},300);
        }
    }
    recSlider("#price_rec");
});