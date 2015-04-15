function hidelayout() {
    $(".modal").hide().removeClass("in");
    $(".modal-backdrop").hide().removeClass("in");
    $(".outindex").removeClass("outindex");
}
jQuery.fn.liclick = function(modal_index) {
    if ($('.modal-backdrop').is(':hidden')) {
        hidelayout();
        var $index = $(this).index();
        $(this).addClass("outindex");
        $(this).append($(".modal").eq(modal_index));
        if ((modal_index == 1 || modal_index == 3) && $index == 3) {
            $(".modal").eq(modal_index).css({"left": "auto", "right": "0"});
        } else if ((modal_index == 1 || modal_index == 3) && $index < 3) {
            $(".modal").eq(modal_index).css({"left": "0", "right": "auto"});
        }

        $(".modal").eq(modal_index).find("input").val("");
        $(".modal").eq(modal_index).find(".upload_image_show").hide();
        $(".modal").eq(modal_index).find(".image_default").show();
        $(".modal").eq(modal_index).find(".errortips").hide();
        $(".modal").eq(modal_index).show().addClass("in");
        $(".modal-backdrop").show().addClass("in");
        var source_id = $(this).attr("source_id");
        var type = $(this).attr("type");

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
                    image_form_init(modal_index, msg.data);
                } else if(modal_index == 1) {
                    block_form_init(modal_index, msg.data);
                } else if(modal_index == 2) {
                    dynamic_form_init(modal_index, msg.data);
                } else if(modal_index == 3) {
                    active_form_init(modal_index, msg.data);
                }
            }
        });
    } else {
        return false
    }

}

function image_form_init(modal_index, data) {
    var obj = $(".modal").eq(modal_index);

    obj.find("input[name=title]").val(data.title);
    obj.find("textarea[name=desc]").text(data.desc);
    obj.find("span[name=count] strong").text(data.desc.length);
    obj.find("input[name=link_url]").val(data.link_url);
    obj.find("input[name=image_value]").val(data.image_value);
    obj.find(".image_default").hide();
    obj.find(".upload_image_show").show().find("img:first").attr("src", data.image_url);
}

function dynamic_form_init(modal_index, data) {
    var obj = $(".modal").eq(modal_index);

    obj.find("input[name='link_url[]']").each(function(i, n){
        if(data[i]) $(this).val(data[i].link_url);
    })
    if(data[0]) {
        obj.find("input[name=image_value]").val(data[0].image_value);
        obj.find(".image_default").hide();
        obj.find(".upload_image_show").show().find("img:first").attr("src", data[0].image_url);
    }
}

function block_form_init(modal_index, data) {
    var obj = $(".modal").eq(modal_index);

    obj.find("input[name=title]").val(data.title);
    obj.find("textarea[name=desc]").text(data.desc);
    obj.find("span[name=count] strong").text(data.desc.length);
    obj.find("input[name=link_url]").val(data.link_url);
    obj.find("input[name=image_value]").val(data.image_value);
    obj.find(".image_default").hide();
    obj.find(".upload_image_show").show().find("img:first").attr("src", data.image_url);
}

function active_form_init(modal_index, data) {
    var obj = $(".modal").eq(modal_index);

    obj.find("input[name=title]").val(data.title);
    obj.find("input[name=link_url]").val(data.link_url);
    obj.find("input[name=image_value]").val(data.image_value);
    obj.find(".image_default").hide();
    obj.find(".upload_image_show").show().find("img:first").attr("src", data.image_url);
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

function form_submit(source_type) {
    var form_id = "edit_" + source_type;
    var source_id = $("#" + form_id).parents("li." + form_id).attr("source_id");
    if(source_id < 1) {
        return false;
    }
    $("#" + form_id).find("input[name=source_id]").val(source_id);
    $("#" + form_id).find("input[name=source_type]").val(source_type);
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