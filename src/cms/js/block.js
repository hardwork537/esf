/** block.js
 * authors : Hqyun
 * date : 2014-07-09 add
 */

$(document).ready(function() {
    $(".icon_del").click(function() {
        $(this).parent().parent().remove();     
    })

    /**
     * 检测已经输入多少字
     */
    function checkNumber(obj, totalNumber) {
        //检测还可以输入多少字
        /*var len = obj.val().length,
         obj1 = obj.next().hasClass('b_num') ? obj.next() : obj.next().find('.b_num'),
         num = totalNumber - len;
         obj1.html(num);*/

        //已经输入多少字
        var len = obj.val().length,
                obj1 = obj.next().hasClass('b_num') ? obj.next() : obj.next().find('.b_num');

        obj1.html(len);
        if (len >= parseInt(totalNumber)) {
            alert('已经输入最多了。');
            return;
        }
    }

    /**
     * clone
     */
    function cloneFun(obj1) {
        var add_type = obj1.attr('type');
        if('add_image' == add_type) {
            var lunbo_num = $(".lunbo_image").length;
            if(lunbo_num >= 4) {
                alert('轮播大图最多只能有4个');
                return false;
            }
            add_image(obj1);
        } else if('add_comment' == add_type) {
            add_comment(obj1);
        } else if('add_content' == add_type) {
            add_content(obj1);
        } else if('add_prop' == add_type) {
            add_prop(obj1);
        }     
    }
    function add_prop(obj1) {
        var prevObj = obj1.parents('.control-group').prev();
        var cloneObj = prevObj.clone(true);
        var hasNum = $('.recommend_prop').length;
        var nowNum = hasNum + 1;
        var chineseNum = transfer(nowNum);
        
        cloneObj.find(".recommend_prop").text("推荐" + chineseNum + "：");
        cloneObj.find("input").val("");
        cloneObj.find("textarea").text("");
        cloneObj.find(".block_id").attr("id", "block_id_"+nowNum);

        obj1.parent().parent().before(cloneObj);
    }
    
    function add_comment(obj1) {
        var prevObj = obj1.parents('.control-group').prev();
        var cloneObj = prevObj.clone(true);
        var hasNum = $('.broker_comment').length;
        var nowNum = hasNum + 1;
        var chineseNum = transfer(nowNum);
        
        cloneObj.find(".broker_comment").text("经纪人点评" + chineseNum + "：");
        cloneObj.find("input").val("");
        cloneObj.find(".b_num").text(0);
        cloneObj.find("textarea").text("");

        obj1.parent().parent().before(cloneObj);
    }
    
    function add_content(obj1) {
        var prevObj = obj1.parents('.control-group').prev();
        var cloneObj = prevObj.clone(true);
        var hasNum = $('.block_content').length;
        var nowNum = hasNum + 1;
        var chineseNum = transfer(nowNum);
        var content_index = now_index;
        now_index += 1;       
        cloneObj.find(".block_content").text("模块" + chineseNum + "：");
        cloneObj.find(".content_box").html('<script id="editor_'+now_index+'" class="ueditor_list" type="text/plain" style="width:560px;height:300px;" name="content['+content_index+']"></script>');
        cloneObj.find(".content_title").attr("name", "title["+content_index+"]");
        cloneObj.find(".content_summary").attr("name", "summary["+content_index+"]");
        cloneObj.find(".content_comm").attr("name", "recommend_comm["+content_index+"]");
        cloneObj.find("input").val("");
        cloneObj.find(".b_num").text(0);
        cloneObj.find("textarea").text("");
        cloneObj.find(".icon_del").show();
        obj1.parent().parent().before(cloneObj);
        UE.getEditor('editor_'+now_index, {
            autoHeight: false,
            toolbars: [
                ['source','undo','redo','justifyleft','justifyright','justifycenter','justifyjustify','bold', 'italic', 'underline','removeformat', 'formatmatch', '|','simpleupload', 'cleardoc']
            ],
            imageUrl: "/public/admin/static/js/php/controller.php",
            elementPathEnabled: false
        });
    }  
    
    function add_image(obj1) {
        var prevObj = obj1.parents('.control-group').prev();
        var cloneObj = prevObj.clone(true);
        var hasNum = $('.lunbo_image').length;
        var nowNum = hasNum + 1;
        var chineseNum = transfer(nowNum);
        

        cloneObj.find(".lunbo_image").text("轮播大图" + chineseNum + "：");
        cloneObj.find("input").val("");
        cloneObj.find(".b_num").text(0);
        cloneObj.find(".upload_image_show").hide();
        cloneObj.find(".image_default").show().removeAttr("style");
        cloneObj.find("input[type=file]:first").attr("id", "big_upload_" + nowNum);
        cloneObj.find("input[type=file]:last").attr("id", "small_upload_" + nowNum);
        cloneObj.find(".icon_del").show();

        obj1.parent().parent().before(cloneObj);
    }
    
    /**
     * 绑定事件
     */
    function bindEvents() {
        var showHide = $('.showHide');

        //显示隐藏
        showHide.click(function() {
            var nxtCon = $(this).parent().next(),
                    text = $(this).hasClass('open') ? '收起' : '展开';
            $(this).toggleClass('open');
            $(this).html(text);
            nxtCon.toggle();
        });

        //检测已经输入多少字
        var hasInput = $('.ipt_con');
        hasInput.keyup(function() {
            checkNumber($(this), $(this).attr('maxlength'));
        });

        //clone效果
        var copy = $('.btnClone');
        copy.click(function() {
            cloneFun($(this));
        });
    }
    bindEvents();

    /**
     * ajax提交表单
     */
    function ajaxData(url, obj) {
        console.log('ajaxData');
        // TODO
    }

    function transfer(number) {
        var danwei = Array("", "十", "百", "千", "万", "十", "百", "千", "亿");
        //var input = parseInt(number);
        var input = number.toString();
        var l = input.length;
        var a = new Array(l);
        var b = new Array(l);
        var result = "";

        for (var i = 0; i < l; i++)
        {
            a[i] = input.substr(i, 1);
            b[i] = getchinese(a[i]);
            result += b[i] + danwei[l - i - 1];
        }

        return result;
    }
    function getchinese(p) {
        var input = p;
        if (input == "0")
            return "零";
        else if (input == "1")
            return "一";
        else if (input == "2")
            return "二";
        else if (input == "3")
            return "三";
        else if (input == "4")
            return "四";
        else if (input == "5")
            return "五";
        else if (input == "6")
            return "六";
        else if (input == "7")
            return "七";
        else if (input == "8")
            return "八";
        else if (input == "9")
            return "九";
    }     
});

/**
* 异步获取板块
*/
function get_block(obj_id, check_id, city_id,area_id) {
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(city_id)=="undefined" || city_id==""){
        city_id = 0;
    }
    if(typeof(area_id)=="undefined" ||  area_id==""){
        area_id = 0;
    }
    $.request({
        url: "/ajax/getregion/",
        data: "distId=" + area_id+"&cityId="+city_id,
        async:false,
        callback: function(data){
            $("#" + obj_id).empty();
            $.each(data, function(i, n){
                if(check_id == i) {
                    $('<option selected="selected" value=' + i + ">" + n + "</option>").appendTo("#" + obj_id);
                } else {
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }                   
            });
        }
    });
}


