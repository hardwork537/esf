/** community_list.js
 * authors : Hqyun
 * date : 2014-07-31 add
 */
$(document).ready(function(){
    $(".asyn_loading").scrollLoading();

    /**
     * 全区域可点击
     */
    function clickEvent(){
        var items = $("#communityList .list_item");
        items.each(function() {
            $(this).mouseenter(function(){
                $(this).addClass('list_hover').prev().addClass('list_prev');
            });
            $(this).mouseleave(function(){
                $(this).removeClass('list_hover').prev().removeClass('list_prev');
            });
            $(this).click(function(){
                var a = document.createElement("a");
                a.href = $(this).find('a').eq(0).attr('href');
                a.target = "_blank";
                document.body.appendChild(a);
                a.click();
            });
            $(this).find('a').each(function(){
                $(this).click(function(e){
                    if(e.stopPropagation){
                        e.stopPropagation();
                    }else{
                        window.event.cancelable = true;
                    }
                })
            });
        });
    }
    clickEvent();

    function bindEvent(){
        $(".house_rel li,.popup_collect .bd li").hover(function () {
            $(this).addClass("hover");
            $(this).find('.name').addClass("unl");
            $(this).find('.attr').addClass("c_txt5 unl");
            $(this).find('.price').addClass("unl");
        }, function () {
            $(this).removeClass("hover");
            $(this).find('.name').removeClass("unl");
            $(this).find('.attr').removeClass("c_txt5 unl");
            $(this).find('.price').removeClass("unl");
        });
    }
    bindEvent();
});