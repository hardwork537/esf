/* 管理出租房源 */
var checkedLen = $("input[name='sid[]']:checked").length;
$(function(){
    setOption();
    $("input[name='sid[]']").click(function(){
        checkedLen = $("input[name='sid[]']:checked").length;
        setOption();
    });
    /* 展示更多小区 */
    var $showItems = $("#filters").find(".items_a");
    var showFlag = true;
    $showItems.find(".showmore").click(function(){
        if(showFlag){
            $(this).html("收起<i class='up'></i>");
            $showItems.css("height","auto");
            showFlag = false;
        }else {
            $(this).html("展开<i class='down'></i>");
            $showItems.css("height","27px");
            showFlag = true;
        }
    });

    /* 输入框 */
    $("input.form_control").focus(function(){
        var defaultValue = $(this).attr("default-value");
        if($(this).val() == defaultValue){
            $(this).val("").removeClass("default");
        }
    })

    $("input.form_control").blur(function(){
        var defaultValue = $(this).attr("default-value");
        if($(this).val() == ""){
            $(this).val(defaultValue).addClass("default");
        }
    });

    $(".mod_option").find(".dropdown_list li a").click(function(){
        $(".mod_option").find(".dropdown_list li a").removeClass("selected");
        $(this).addClass("selected");
    });

    $(".page").find(".dropdown_list li a").click(function(){
        $(".page").find(".dropdown_list li a").removeClass("selected");
        $(this).addClass("selected");
    });

    /* 模拟下拉框 */
    var $dropdown = $(".dropdown");
    $dropdown.hover(function(){
        var that = this;
        var $menu = $(that).find(".dropdown_list");
        $dropdown.not(that).find(".dropdown_list").hide();
        $menu.toggle();
    });
    $(".clickReason").mouseover(function(){
        $(this).siblings(".detailReason").toggle();
    });
    $(".clickReason").mouseout(function(){
        $(this).siblings(".detailReason").toggle();
    });
    $(".dropdown_btn").mouseover(function(){
        $(this).addClass("hover");
    });
    $(".dropdown_btn").mouseout(function(){
        $(this).removeClass("hover");
    });
});

function viewRefreshLog(sid){
    $.ajax({
        type:"POST",
        async:false,
        url: base_url+"rent/refreshList/",
        cache: false,
        data:"houseId="+sid,
        dataType:"html",
        success: function(html){
        	$("#refreshLogModal").html(html);
            $("#refreshLogModal").show();
            $("#exposeMask").show();

            //刷新纪录tab切换
            $("#refreshLogModal .modal_tabNav li").click(function(){
                if($(this).hasClass("curr")){
                    return;
                }
                var index = $(this).index();
                $(this).siblings().removeClass("curr");
                $(this).addClass("curr");

                $(".panel_body").find(".modal_refreshList").hide();
                $(".panel_body").find(".modal_refreshList").eq(index).show();
            });
            /* 关闭弹出窗 */
            $("[rel='closeModal']").click(function(){
                $(this).parents(".modal").hide();
                $("#exposeMask").hide();
            });
        }
    });
}

function setTagsModal(sid){
    $.ajax({
        type:"POST",
        async:false,
        url: base_url+"rent/flag/",
        cache: false,
        data:"unit_id="+sid,
        success: function(html){
            $("#setTagsModal").html(html);
            $("#setTagsModal").show();
            $("#exposeMask").show();
        }
    });
}

function gotoFilterUrl(name, num){
    if($("#rent_id").val() == "输入房源编号或备注信息" || $("#rent_id").val() == "") $("#rent_id").attr("disabled", true);
    $("#strLocation").attr("disabled", true);
    if (name) $("#"+name).val(num);
    $('#rent_select_form').submit();
}

function submitOffline(sid){
    if(typeof sid == 'undefined' && checkedLen < 1) return;
    if(sid){
        $(".select_all").attr('checked',false);
        $("input[name='sid[]']:checked").attr('checked',false);
        $('#sid_'+sid).attr('checked','checked');
    }
    var chkids = new Array();
    $("input[name='sid[]']:checked").each(function(){
        chkids.push($(this).val());
    });
    $.ajax({
        type:"POST",
        traditional :true,//传递数组
        url:base_url+"rent/offline/",
        data:{'sid[]':chkids, 'location':$("input[name='location']").val()},
        dataType:"json",
        success: function (data) {
            if( data.showtype == 'toast' ){
                $("#notice_message .message_content").html(data.msg);
                if (data.msg) $("#notice_message").show();
                setTimeout(function(){$("#notice_message").fadeOut()},3000);
				if (data.location) setTimeout(function(){window.location.href = data.location},1500);
            }else{
                $("#manageModal .p_tips").html('<i class="icons icon_error"></i>'+data.msg);
                if (data.msg) $("#manageModal").show();
				$("#manageModal #btnSure").click(function(){
					if (data.location) window.location.href = data.location;
				});
            }
        }
    });
}

function submitOnline(sid){
    if(typeof sid == 'undefined' && checkedLen < 1) return;
    if(sid){
        $(".select_all").attr('checked',false);
        $("input[name='sid[]']:checked").attr('checked',false);
        $('#sid_'+sid).attr('checked','checked');
    }
    var chkids = new Array();
    $("input[name='sid[]']:checked").each(function(){
        chkids.push($(this).val());
    });
    $.ajax({
        type:"POST",
        traditional :true,//传递数组
        url:base_url+"rent/online/",
        data:{'sid[]':chkids, 'location':$("input[name='location']").val()},
        dataType:"json",
        success: function (data) {
            if( data.showtype == 'toast' ){
                $("#notice_message .message_content").html(data.msg);
                if (data.msg) $("#notice_message").show();
                setTimeout(function(){$("#notice_message").fadeOut()},3000);
				if (data.location) setTimeout(function(){window.location.href = data.location},1500);
            }else{
                $("#manageModal .p_tips").html('<i class="icons icon_error"></i>'+data.msg);
                if (data.msg) $("#manageModal").show();
				$("#manageModal #btnSure").click(function(){
					if (data.location) window.location.href = data.location;
				});
            }
        }
    });
}

function submitDel(sid){
    if(typeof sid == 'undefined' && checkedLen < 1) return;
    $("#confirmModal .sid").val(sid);
	$("#confirmModal").show();
}
function submitDelSave(){
    $("#confirmModal").hide();
    var sid = $("#confirmModal .sid").val();
    if(sid){
        $(".select_all").attr('checked',false);
        $("input[name='sid[]']:checked").attr('checked',false);
        $('#sid_'+sid).attr('checked','checked');
    }
    var chkids = new Array();
    $("input[name='sid[]']:checked").each(function(){
        chkids.push($(this).val());
    });

    if(chkids.length == 0){
        chkids.push(sid);
    }
	$.ajax({
		type:"POST",
		traditional :true,//传递数组
		url:base_url+"rent/del/",
		data:{'sid[]':chkids, 'location':$("input[name='location']").val()},
		dataType:"json",
		success: function (data) {
			if( data.showtype == 'toast' ){
				$("#notice_message .message_content").html(data.msg);
				if (data.msg) $("#notice_message").show();
				setTimeout(function(){$("#notice_message").fadeOut()},3000);
				if (data.location) setTimeout(function(){window.location.href = data.location},1500);
			}else{
				$("#manageModal .p_tips").html('<i class="icons icon_error"></i>'+data.msg);
				if (data.msg) $("#manageModal").show();
				$("#manageModal #btnSure").click(function(){
					if (data.location) window.location.href = data.location;
				});
			}
		}
	});
}
function setUnitRecommend(id){
    window.location.href = base_url+"rent/setRe/?unit_id="+id+"&location="+$('#strLocation').val();
}
function delUnitRecommend(id){
    window.location.href = base_url+"rent/delRe/?unit_id="+id+"&location="+$('#strLocation').val();
}
function selectAll(obj){
    if (obj.checked == true){
        $("input[name='sid[]']").each(function(){this.checked=true;});
    }else{
        $("input[name='sid[]']").each(function(){this.checked=false;});
    }
    checkedLen = $("input[name='sid[]']:checked").length;
    setOption();
}

//定时刷新
function gotoFlushUrl(sid){
    if(typeof sid == 'undefined' && checkedLen < 1) return;
    if(sid){
        var select_value = sid;
    }else{
        if($("input[name='sid[]']:checked").length<1){
            alert("请选择房源");return;
        }
        var select_value = '';
        var flag_weigui = 0;
        $("input[name='sid[]']:checked").each(function(){
            var flag = $(this).parent().parent().hasClass("warning");
            if(flag){
                flag_weigui += 1;
            }
            if(!flag){
                select_value == '' ? select_value += $(this).attr('value') : select_value += ","+$(this).attr('value');
            }
        });
        if(flag_weigui>=1  && select_value == ''){
            alert("嗷，你勾选了违规房源，此类房源不会在前台显示，请重新选择。");
            return;
        };
    }
    window.location.href = base_url+'refresh/timePoint/?ids='+select_value+'&house_type=1';
    if(window.event){
        window.event.returnValue = false;
    }
}

//取消定时
function submit_del_flush(sid){
    if(typeof sid == 'undefined' && checkedLen < 1) return;
    if(sid){
        $("input[name='sid[]']:checked").attr('checked',false);
        $('#sid_'+sid).attr('checked','checked');
    }
    var select_value = '';
    $("input[name='sid[]']:checked").each(function(){
        select_value == '' ? select_value += $(this).attr('value') : select_value += ","+$(this).attr('value');
    });
    $.ajax({
        type:"POST",
        url:"/rent/delFlush",
        data:"ids="+select_value,
        dataType:"json",
        success: function (data) {
            if( data.success == 1 ){
                $("input:checked").each(function(){
                    if(!$(this).parent().parent().hasClass("warning")){
                        $(this).parent().parent().find("td").eq(5).find("p").eq(0).hide();
                    }
                });
            }
            $("#notice_message .message_content").html(data.msg);
            $("#notice_message").show();
            setTimeout(function(){$("#notice_message").fadeOut()},1500);
            $("input[type='checkbox']").attr("checked",false);
            checkedLen = 0;
            setOption();
        }
    });
}

//手动刷新
function submit_flush(sid){
    if(typeof sid == 'undefined' && checkedLen < 1) return;
    if(sid){
        $("input[name='sid[]']:checked").attr('checked',false);
        $('#sid_'+sid).attr('checked','checked');
    }
    var sid_num = $("input[name='sid[]']:checked").length;
    var limits = parseInt("{{ refreshNum }}");
    if(sid_num>limits){
        alert("最多可以同时手动刷新"+limits+"条房源");return;
    }
    var flag_weigui = 0;
    var select_value = '';
    $("input[name='sid[]']:checked").each(function(){
        var flag = $(this).parent().parent().hasClass("warning");
        if(flag){
            flag_weigui += 1;
        }
        if(!flag){
            select_value == '' ? select_value += $(this).attr('value') : select_value += ","+$(this).attr('value');
        }
    });
    if(flag_weigui>=1  && select_value == ''){
        alert("嗷，你勾选了违规房源，此类房源不会在前台显示，请重新选择。");
        return;
    };
    $.ajax({
        type:"POST",
        url:"/rent/flush",
        data:"ids="+select_value,
        dataType:"json",
        success: function (data) {
            if( data.success == 1 ){
                $("input:checked").each(function(){
                    if(!$(this).parent().parent().hasClass("warning")){
                        $(this).parent().parent().find("td").eq(2).text("刚刚");
                        $(this).parent().parent().find("td").eq(5).find("p").eq(1).text("(今日已刷)").removeClass("c_orange").addClass("c_gray");
                    }
                });
                $("#notice_message .message_content").html("刷新成功，当日剩余刷新<em class='red'>"+data.msg+"</em>次");
            }else{
                $("#notice_message .message_content").html(data.msg);
            }
            $("#notice_message").show();
            setTimeout(function(){$("#notice_message").fadeOut()},1500);
            $("input[type='checkbox']").attr("checked",false);
            checkedLen = 0;
            setOption();
        }
    });
}
function setOption(){
    checkedLen < 1 ? $(".mod_option").addClass("mod_option_disable") : $(".mod_option").removeClass("mod_option_disable");
}
