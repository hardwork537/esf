function show_comms() {
    var cityId = $("select[name=cityId]").val();
    
    $(".comm_name").unbind("keyup");
    $(".comm_name").bind("keyup", function(e) {
        get_comms(this, cityId, e);
    });
    $(".comm_name").unbind("focus");
    $(".comm_name").bind("focus", function() {
        if($(this).prev().find("ul").html() != "") {
            $(this).prev().show();
        }
    });
    $(".comm_name").blur(function() {
        $(this).prev().hide();
    });   
}

function get_comms(obj, cityId, e) {
    var comm_name = trim($(obj).val());
    //过滤部分不相关的按键
    switch (e.keyCode) {
        case 13:
        case 16:
        case 17:
        case 18:
        case 20:
        case 33:
        case 34:
        case 35:
        case 36:
        case 37:
        case 38:
        case 39:
        case 40:
        case 45:
            return;
    }

    //请求前先将下拉选项数据清空
    var before_obj = $(obj).prev()
    before_obj.hide().find("ul").html("");
    
    if (comm_name.length > 0) {
        var data_request = "q_word=" + encodeURIComponent(comm_name) + "&cityId=" + cityId;
        $.ajax({
            type: "POST",
            url: "/ajax/getParkByCity/",
            data: data_request,
            dataType: "json",
            success: function(result) {
                if (result != null && result != "") {
                    $(result).each(function(i, n){	
                        before_obj.find("ul").show().append('<li><a>'+ n.parkName + '</a></li>');
                        before_obj.show();
                    });
                    
                    //点击事件
                    $(obj).prev().find("li").mousedown(function(){	
                        $(obj).val($(this).text());
                        before_obj.hide().find("ul").html("");
                    });                   
                } 
            }
        });
    } 
}
