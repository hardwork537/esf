$(document).ready(function () {
    $(".side_bdtop").hover(function () {
        $(this).find(".tt span").addClass("c_txt5").addClass("unl");
    }, function () {
        $(this).find(".tt span").removeClass("c_txt5").removeClass("unl");
    })

    $(".bankuaiblock li").hover(function () {
        $(this).siblings().find(".inner").css("border-right-color", "#e6e6e6");
        $(this).prev().find(".inner").css("border-right-color", "#fff");
        $(this).addClass("active").find(".inner").css("border-right-color", "#b8d2ec");
        $(this).find("a.f14").addClass("unl");
    }, function () {
        $(".bankuaiblock li .inner").css("border-right-color", "#e6e6e6");
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
        start = setInterval(change, 5000);
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
    var start = setInterval(change, 5000);
});