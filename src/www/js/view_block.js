/** block.js
 * authors : Hqyun
 * date : 2014-07-09 add
 */

$(document).ready(function(){
    /**
     * fixed top
     */
    function fixedTop(){
        var min_height = 700;
        $(window).scroll(function () {
            var top = $(window).scrollTop(),
                obj = $('#fixedTop');
            (top > min_height) ? obj.fadeIn(100) : obj.fadeOut(200);
        });
    }
    fixedTop();
	
	tabs(".community_house_rel .tab_nav li",".community_house_rel .tab_con ul");
	
	$(".community_house_rel .tab_con li").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});

    /**
     * Tab切换
     * @param {object} obj1 tabs对象，obj2 con对象
     */
    function tabSwitch(obj1, obj2){
        obj1.mouseenter(function(){
            var idx = obj1.index(this);
            obj1.removeClass('on');
            $(this).addClass('on');
            obj2.hide();
            obj2.eq(idx).show();
        });
    }
    tabSwitch($("#tabNavList li"), $('#hotCommunityList ul'));
	
	/**
     * 导航锚点切换--方案二
     * @param {object} obj
     */
    function anchors(){
        $(".block_content").click(function() {
            $(".block_content").removeClass("a_curr");
            var content_id = $(this).attr("tag_name");
            $("a[tag_name='"+content_id+"']").addClass("a_curr");
            var _targetTop = $('#'+content_id).offset().top - 10;//获取位置
            jQuery("html,body").animate({scrollTop:_targetTop},300);//跳转
        });
    }
    anchors();

    /**
     * 全区域可点击
     */
    function clickEvent(){
        var li = $('.hot_community_list_pic').find('li');
        li.mouseenter(function(){
            $(this).addClass('hover');
        });
        li.mouseleave(function(){
            $(this).removeClass('hover');
        });
    }
    clickEvent();

    /* j_sliderbar */
    $(window).scroll(function(){
        var top = $(window).scrollTop(),
            start = $("#mod_content1").offset().top-20,
            $jSiderbar = $("#j_siderbar");

        if( top > start ){
            $jSiderbar.addClass("j_siderbar_fixed");
            $jSiderbar.find('.bar_a').click(function(){
                $(this).addClass('focus').siblings().removeClass('focus');
            });

            if($("#mod_content1").length > 0 && top >= $("#mod_content1").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name1']").addClass("focus");
            }
            if($("#mod_content2").length > 0 && top >= $("#mod_content2").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name2']").addClass("focus");
            }
            if($("#mod_content3").length > 0 && top >= $("#mod_content3").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name3']").addClass("focus");
            }
            if($("#mod_content4").length > 0 && top >= $("#mod_content4").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name4']").addClass("focus");
            }
            if($("#mod_content5").length > 0 && top >= $("#mod_content5").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name5']").addClass("focus");
            }
            if($("#mod_content6").length > 0 && top >= $("#mod_content6").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name6']").addClass("focus");
            }
            /*if($("#mod_content7").length > 0){
                if(top >= $("#mod_content7").offset().top){
                    $jSiderbar.find(".bar_a").removeClass("focus");
                    $jSiderbar.find("a[anchor-name='block_agent']").addClass("focus");
                }
            }else{
                $jSiderbar.find("li[anchor-name='block_agent']").hide();
            }*/
            if($("#mod_content7").length > 0 && top >= $("#mod_content7").offset().top-5){
                $jSiderbar.find(".bar_a").removeClass("focus");
                $jSiderbar.find("a[anchor-name='block_name7']").addClass("focus");
            }
        }else{
            $jSiderbar.removeClass("j_siderbar_fixed");
        }
    });

    /**
     * 图片切换
     */
    function imgSlider(){
        var focus = $('#topFousSlider'),
            idx = 0,//timer = null,
            speed = 5000,
            thum = $('#thumImgCon .thum_img'),
            len = thum.length;
        var bigImg = focus.find('.big_img'),
            tit1 = focus.find('em'),
            tit2 = focus.find('i');
			
		var bg_width = len * 66 + 3 + 'px';
        $('#thumImgCon').css("width",bg_width);	

		if(len === 1){
            $('#thumImgCon').hide();
        }		

        function doPlay() {
            (idx === len - 1 || idx > len - 1) ? idx = 0 : idx += 1;
            thum.removeClass('thum_curr');
            thum.eq(idx).addClass('thum_curr');
            //doAnimate();
			(len > 1) && doAnimate();
        }

        function bindEvent(){
            thum.mouseenter(function(){
                idx = $('#thumImgCon .thum_img').index(this);
                switchImg(idx);
                doAnimate();
            });

            thum.mouseleave(function(){
                timer = setInterval(doPlay, speed);
            });

            bigImg.mouseenter(function(){
                idx = focus.find('.big_img').index(this);
                switchImg(idx);
            });

            bigImg.mouseleave(function(){
                timer = setInterval(doPlay, speed);
            });
        }
        bindEvent();

        function switchImg(index){
            clearInterval(timer);
            thum.removeClass('thum_curr');
            thum.eq(idx).addClass('thum_curr');
        }

        function doAnimate(){
            bigImg.hide();
            bigImg.eq(idx).show();

            bigImg.filter('.big_curr').stop().animate({ 'opacity': '0' }, 800).removeClass('big_curr');
            tit1.animate({ left: '20px', opacity: "0" }, 10);
            tit2.animate({ left: '-20px', opacity: "0" }, 10);

            bigImg.eq(idx).stop().animate({ 'opacity': '1' }, 300).addClass('big_curr');
            tit1.eq(idx).animate({ left: '0px', opacity: "1" }, 500);
            tit2.eq(idx).delay(50).animate({ left: '0px', opacity: "1" }, 600);
        }

        var timer = setInterval(doPlay, speed);
    }
    imgSlider();
});