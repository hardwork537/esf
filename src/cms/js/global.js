base_url = "/";
//关闭当前窗口并刷新父窗口
function closeWindow() {
    window.opener.location.href = window.opener.location.href;
    window.close();  
}


//用户注销
function loginout(){
    if(!confirm("确定要注销系统吗?")) {
        return false;
    } 
    $.request({
        url: "/login/out",
        callback: function(msg) {
            if (msg.status == 0) {
               location.reload(false);
            }
        }
    });
    return true;
}

//上传图片  
function upload_image(image_id) {
    $.ajaxFileUpload ({
        url           : '/ajax/uploadimage/',
        secureuri     : false,
        fileElementId : 'image_upload',
        dataType      : 'json',
        success : function (data, status){
            if(data.status  == 0) {
                //上传成功
                _create_image_box(data.msg, image_id);
            } else {
                //上传失败
                var msg = data.msg ? data.msg : '上传失败，请稍后重试！';
                $.error(msg);
                $.toTop();
            }
        },
        error: function (data, status, e){
            $.error(data['responseText']);         
        }
    });
}

//上传图片成功回调函数
function _create_image_box(data, image_id) {
    $("input[name='imageId']").val(data.id);
    $("input[name='imageExt']").val(data.ext);
    $("#image_default").hide();
    $("#" + image_id).show().find("img:first").attr("src", data.upload_url);   
} 