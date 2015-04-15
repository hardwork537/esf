$(function(){
    initEvent();
    headEvent();
});
function initEvent(){
    var objIndexInput = $("#idxSearchInput");
    var defaultValue = objIndexInput.attr("data-holder");
    var closeBtn = $("#idxSearchBox").find(".icon_clear");
    var hdAutocomplete = $("#hdAutocomplete");
    var resultUl = hdAutocomplete.find("ul");
    var resultLi = hdAutocomplete.find("li");

    /* 清除输入框 */
    closeBtn.hover(function(){
        $(this).addClass("icon_clear_hover");
    },function(){
        $(this).removeClass("icon_clear_hover");
    });

    closeBtn.click(function(){
        objIndexInput.val("");
        $(this).hide();
    });

    /* 获得焦点时显示 */
    objIndexInput.focus(function(){
        $(this).addClass("focus");
        if(objIndexInput.val() == defaultValue){
            $(this).val("");
        }
    });
    objIndexInput.blur(function(){
        $(this).removeClass("focus");
        hdAutocomplete.hide();
        if(objIndexInput.val() == ""){
            $(this).val(defaultValue);
        }
    });
    var thread = null;
    objIndexInput.keyup(function(){
        var content = '';
        hdAutocomplete.hide();
        if($(this).val() != ""){
            closeBtn.show();
            window.clearTimeout(thread);
            thread = window.setTimeout(function(){
                //从服务器获得匹配的数据
                var datas = searchData;
                resultUl.empty();
                $.each(datas,function(index, data){
                    content += "<li data-id='"+data.article_id+"' title='"+data.article_name+"'>"+data.article_name+"</li>";
                });
                resultUl.append(content);
                hdAutocomplete.show();
            },200);
        }
        else{
            window.clearTimeout(thread);
            closeBtn.hide();
        }
    });
    hdAutocomplete.on("click",function(){
        hdAutocomplete.hide();
    });
}
function headEvent(){
        $(".side_bdtop").hover(function () {
            $(this).find(".tt span").addClass("c_txt5").addClass("unl");
        }, function () {
            $(this).find(".tt span").removeClass("c_txt5").removeClass("unl");
        })

        $(".bankuaiblock li").hover(function () {
            $(this).addClass("active");
            $(this).find("a.f14").addClass("unl");
        }, function () {
            $(this).removeClass("active");
            $(this).find("a.f14").removeClass("unl");
        })

        /*顶部图片切换*/
        $('.guideimg_nav li:first').addClass('current');
        $('.guideimgcon_box>div:gt(0)').css({ 'opacity': '0', 'z-index': '2' });
        $('.guideimgcon_box>div.visible').css('z-index', '3');
        var $index = 0;
        var $len = $('.guideimg_nav li').length;
        $('.guideimg_nav li').mouseover(function () {
            clearInterval(start);
            $index = $(this).index();
            $('.guideimg_nav li').removeClass('current');
            $(this).addClass('current');
            $('.guideimgcon_box>div').filter('.visible').stop().animate({ 'opacity': '0' }, 900).removeClass('visible');
            $('.guideimgcon_box>div>.tittle1').stop().animate({ left: '20px', opacity: "0" }, 10);
            $('.guideimgcon_box>div>.tittle2').stop().animate({ left: '-20px', opacity: "0" }, 10);
            $('.guideimgcon_box>div').stop().animate({ 'opacity': '0', "z-index": '2' }, 900);
            $('.guideimgcon_box>div:eq(' + $index + ')').stop().animate({ 'opacity': '1', "z-index": '4' }, 900).addClass('visible');
            $('.guideimgcon_box>div:eq(' + $index + ')>.tittle1').stop().animate({ left: '0px', opacity: "1" }, 500);
            $('.guideimgcon_box>div:eq(' + $index + ')>.tittle2').stop().delay(100).animate({ left: '0px', opacity: "1" }, 600);

        });
        $('.guideimg_box .guideimgcon_box div').mouseover(function () { clearInterval(start); })
        $('.guideimg_nav li,.guideimg_box .guideimgcon_box div').mouseout(function () {
            start = setInterval(change, 4000);
        });
        function change() {
            if ($index == $len - 1 || $index > $len - 1) {
                $index = 0;
            } else { $index += 1; }
            $('.guideimg_nav li').removeClass('current');
            $('.guideimg_nav li:eq(' + $index + ')').addClass('current');
            $('.guideimgcon_box>div').filter('.visible').stop().animate({ 'opacity': '0' }, 900).removeClass('visible');
            $('.guideimgcon_box>div>.tittle1').stop().animate({ left: '20px', opacity: "0" }, 10);
            $('.guideimgcon_box>div>.tittle2').stop().animate({ left: '-20px', opacity: "0" }, 10);
            $('.guideimgcon_box>div').stop().animate({ 'opacity': '0', "z-index": '2' }, 900);
            $('.guideimgcon_box>div:eq(' + $index + ')').stop().animate({ 'opacity': '1', "z-index": '4' }, 900).addClass('visible');
            $('.guideimgcon_box>div:eq(' + $index + ')>.tittle1').stop().animate({ left: '0px', opacity: "1" }, 500);
            $('.guideimgcon_box>div:eq(' + $index + ')>.tittle2').stop().delay(100).animate({ left: '0px', opacity: "1" }, 600);
        }
        var start = setInterval(change, 4000);
}
var searchData = [
    {
        "article_id": 37183,
        "article_name": "海关二村"
    },
    {
        "article_id": 37183,
        "article_name": "海关二村"
    },
    {
        "article_id": 37183,
        "article_name": "海关二村"
    },
    {
        "article_id": 37183,
        "article_name": "海关二村"
    },
    {
        "article_id": 37183,
        "article_name": "海关二村"
    }]