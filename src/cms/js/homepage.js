function hidelayout() {
    $("#edit_box").hide();
    $(".modal-backdrop").hide().removeClass("in");
}
jQuery.fn.liclick = function(modal_index) {
    if ($('.modal-backdrop').is(':hidden')) {
        hidelayout();
        $("#edit_box").show();
        $(".modal-backdrop").show().addClass("in");
        
        var source_id = $(this).attr("source_id");
        var type = $(this).attr("type");
        
        $("#edit_image input[name=source_id]").val(source_id);
        $("#edit_image input[name=source_type]").val('image');
        
        $.ajax({
            type: "post",
            data: "cityId="+cityId+"&type="+type+"&source_id="+source_id,
            url: "/ajax/homesource/",
            success: function(msg) {
                msg = JSON.parse(msg);
                if(0 != msg.status) {
                    return false;
                }
                if(modal_index == 0) {
                    image_form_init(msg.data);
                } 
            }
        });
    } else {
        return false
    }

}

function image_form_init(data) {
    var obj = $("#edittable");
    
    obj.find("input[name=title]").val(data.title);
    obj.find("textarea[name=desc]").text(data.desc);
    obj.find("span[name=count] strong").text(data.desc.length);
    obj.find("input[name=link_url]").val(data.link_url);
    obj.find("input[name=image_value]").val(data.image_value);
    obj.find(".image_default").hide();
    obj.find(".upload_image_show").show().find("img:first").attr("src", data.image_url);
}

function form_submit(source_type) {
    var form_id = "edit_" + source_type;
    var source_id = $("#edit_source_id_" + form_id).parents("li." + form_id).attr("source_id");
    if(source_id < 1) {
        return false;
    }
    //$("#" + form_id).find("input[name=source_id]").val(source_id);
    //$("#" + form_id).find("input[name=source_type]").val(source_type);
    $("#" + form_id).find("input[name=act]").val("edit");
    $("#" + form_id).ajaxSubmit({
        "url":"/homepage/edit/",
        "formCheck": {
            showType: "error",
            errorId : form_id + "_error_show"
        },
        "callback":function(msg){
            if(msg.status==0){
                $.error(msg.info, form_id + "_error_show");
                window.location.href = "/homepage/edit/?cityId="+cityId;
            }else{
                $.error(msg.info, form_id + "_error_show");
            }
        }
    });
    $("#" + form_id).submit();
}

//去除前后空格
function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

//上传图片  
function upload_image_multi(obj) {
    var default_obj = $(obj).siblings(".image_default");
    var value_obj = $(obj).prev();
    var show_obj = $(obj).siblings(".upload_image_show");
    var image_upload_id = $(obj).attr("id");

    $.ajaxFileUpload ({
        url           : '/ajax/uploadimage/',
        secureuri     : false,
        fileElementId : image_upload_id,
        dataType      : 'json',
        success : function (data, status){
            if(data.status  == 0) {
                //上传成功
                _create_image_box_multi(data.msg, default_obj, value_obj, show_obj);
            } else {
                //上传失败
                var params = image_upload_id.split("_");
                var form_id = 'edit_'+params[0]+'_error_show'
                var msg = data.msg ? data.msg : '上传失败，请稍后重试！';
                $.error(msg, form_id);
            }
        },
        error: function (data, status, e){
            $.error(data['responseText']);         
        }
    });
}

//上传图片成功回调函数
function _create_image_box_multi(data, default_obj, value_obj, show_obj) {
    value_obj.val(data.id + "." + data.ext);
    show_obj.show().find("img").attr("src", data.upload_url);
    default_obj.hide();
}

$(document).ready(function() {
    $(".del_photo").click(function() {
        $(this).parent().parent().hide().siblings(".image_default").show();
        $(this).parent().siblings(".image_value").val("");
    });
    var flag = false;
    $('.guideicon_nav i, .guideimg_nav li').click(function() {
        $(this).liclick(0);
        var index = $(this).index();
        $('.guideicon_nav i').removeClass('current');
        $('.guideimg_nav li').removeClass('current')
        $('.guideicon_nav i').eq(index).addClass('current');
        $('.guideimg_nav li').eq(index).addClass('current');
    });
    $("body").on("click", "#edit_box .close,#edit_box .btn-default", function() {
        hidelayout();
        flag = false;
    })

    // 输入框字数统计

    $("textarea[name=input_count]").on("keyup", function() {
        var count = trim($(this).val()).length;
        $(this).siblings('span[name=count]').find('strong').text(count);
    })

    /*顶部图片切换*/
    $('.guideicon_nav i:first').addClass('current');
    $('.guideimgcon_box>div:gt(0)').css({'opacity': '0', 'z-index': '2'});
    $('.guideimgcon_box>div.visible').css('z-index', '3');
    var $index = 0;
    var $len = $('.guideicon_nav i').length;
    $('.guideicon_nav i').click(function() {
        $index = $(this).index();
        changeImg($index);
    });
    $('.guideimg_nav li').click(function() {
        $index = $(this).index();
        changeImg($index);
    });
    function changeImg(index) {
        flag = true;
        $('.guideicon_nav i').removeClass('current');
        $('.guideimg_nav li').removeClass('current')
        $('.guideicon_nav i').eq(index).addClass('current');
        $('.guideimg_nav li').eq(index).addClass('current');
        $('.guideimgcon_box>div').filter('.visible').stop().animate({'opacity': '0'}, 900).removeClass('visible');
        $('.guideimgcon_box>div>.tittle1').animate({left: '20px', opacity: "0"}, 10);
        $('.guideimgcon_box>div>.tittle2').animate({left: '-20px', opacity: "0"}, 10);
        $('.guideimgcon_box>div:eq(' + index + ')').stop().animate({'opacity': '1'}, 900).addClass('visible');
        $('.guideimgcon_box>div:eq(' + index + ')>.tittle1').animate({left: '0px', opacity: "1"}, 500);
        $('.guideimgcon_box>div:eq(' + index + ')>.tittle2').delay(100).animate({left: '0px', opacity: "1"}, 600);
    }
    function change() {
        if (flag) {
            return;
        }
        if ($index == $len - 1 || $index > $len - 1) {
            $index = 0;
        } else {
            $index += 1;
        }
        $('.guideicon_nav i').removeClass('current');
        $('.guideimg_nav li').removeClass('current')
        $('.guideicon_nav i').eq($index).addClass('current');
        $('.guideimg_nav li').eq($index).addClass('current');
        $('.guideimgcon_box>div').filter('.visible').stop().animate({'opacity': '0'}, 900).removeClass('visible');
        $('.guideimgcon_box>div>.tittle1').animate({left: '20px', opacity: "0"}, 10);
        $('.guideimgcon_box>div>.tittle2').animate({left: '-20px', opacity: "0"}, 10);
        $('.guideimgcon_box>div:eq(' + $index + ')').stop().animate({'opacity': '1'}, 900).addClass('visible');
        $('.guideimgcon_box>div:eq(' + $index + ')>.tittle1').animate({left: '0px', opacity: "1"}, 500);
        $('.guideimgcon_box>div:eq(' + $index + ')>.tittle2').delay(100).animate({left: '0px', opacity: "1"}, 600);
    }
    var start = setInterval(change, 3000);  

});