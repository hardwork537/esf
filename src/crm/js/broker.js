function trim(str) {
    return str == null ? "" : str.replace(/(^[\s +-]*)|([\s +-]*$)/g, "");
}

///**
// * 生成Url参数
// */
//function get_string(str, data_export){
//    var _v = [];
//    var _vstr = [];
//    if(null == str) {
//        try {
//            var $url = location.href;
//        } catch (e) {
//            var $url = window.top.location.href;
//        }
//        $start = $url.indexOf('?') + 1;
//        if(0 == $start) {
//            _vstr.push("action=export");
//            return _vstr.join("&");
//        }
//        $end = $url.length;
//        $Query_String = $url.substring($start, $end);
//    } else {
//        $Query_String = str;
//    }
//    if(null == data_export) {
//        _vstr.push("action=export");
//    }
//    $Get = $Query_String.split('&');
//    for (var i in $Get) {
//        $tmp = $Get[i].split('=');
//        _v[$tmp[0]] = encodeURIComponent(trim($tmp[1])); 
//    }
//    for(var i in _v){
//        if(!_v[i] || 0 == _v[i]){
//            continue;
//        }
//        _vstr.push(i+"="+_v[i]);
//    }    
//    
//    return _vstr.join("&");
//}
//
///**
// * 经纪人管理3级连动，区域、板块、公司
// * @param cityId
// * @param area_name
// * @param block_name
// */
//function get_area_block(cityId, city_name, area_name, block_name)
//{
//    var area_id = null;   
//    var area_id = 0;
//    
//    $("#company_id").val(0);
//    $("#company_name").val('');
//    $("#agent_id").val(0);
//    $("#agent_name").val('');
//    //var s = true;
//    $.ajax({
//        type: "POST",
//        url: "/ajax/area/",
//        data: "cityId=" + cityId,
//        dataType: "json",
//        success: function(data){
//            $("#" + area_name).empty();
//
//            if (data == null) {
//                $("<option value=\"0\">全部</option>").appendTo("#" + area_name);
//            } else {
//                $("<option value=\"0\">全部</option>").appendTo("#" + area_name);
//                $.each(data, function(i, n){
//                    //if(s) {
//                        //area_id = i;
//                        //s = false;
//                    //}
//                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + area_name);
//                });
//                get_block(area_id, city_name, block_name);    
//            }
//        }
//    });
//}
//
///**
//* 异步获取满足条件的板块数据，并捆绑到指定对象
//* int area_id 区域id
//* string 绑定对象名称
//*/
//function get_block(area_id, city_name, block_name, block_id, need_default)
//{
//    var	select=document.getElementById(city_name); 
//    var	option = select.options[select.selectedIndex];
//    var objcity = option.attributes.value;
//    var block_id = null == block_id ? 0 : block_id;
//    $.ajax({
//        type: "POST",
//        url: "/ajax/block/",
//        data: "area_id=" + area_id+"&cityId="+objcity.value,
//        dataType: "json",
//        success: function(data){
//            $("#" + block_name).empty();
//            if (data == null) {
//                if(null == need_default) {
//                    $("<option value=\"0\">全部</option>").appendTo("#" + block_name);
//                }
//                    
//            } else {
//                if(null == need_default) {
//                    $("<option value=\"0\">全部</option>").appendTo("#" + block_name);
//                }
//                $.each(data, function(i, n){
//                    if(block_id == i) {
//                        $('<option selected="selected" value=' + i + ">" + n + "</option>").appendTo("#" + block_name);
//                    } else {
//                        $("<option value=" + i + ">" + n + "</option>").appendTo("#" + block_name);
//                    }                   
//                });
//            }
//        }
//    });
//}
//
///**
// * crm系统销售-客服区分城市
// *
// * object 对象
// */
//function get_sell(id, sell_name)
//{
//    var	select=document.getElementById(id); 
//    var	option = select.options[select.selectedIndex];
//    var objcity = option.attributes.value;
//    $.ajax({
//        type: "POST",
//        url: "/ajax/crmuser/",
//        data: "cityId="+objcity.value+"&type=sell",
//        success: function(data){
//            var data = eval("(" + data + ")");  
//            if(data == null) {
//                return;
//            }
//            var sell_data = data.sell;
//            $("#" + sell_name).empty();
//            if (sell_data == null) {
//                $("<option value=\"0\">全部</option>").appendTo("#" + sell_name);
//            } else {
//                $("<option value=\"0\">全部</option>").appendTo("#" + sell_name);
//                $.each(sell_data, function(i, n){
//                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + sell_name);
//                });
//            }
//        }
//    });
//}
//
///**
// * crm系统销售-团队区分城市
// *
// * object 对象
// */
//function get_team(id, team_name)
//{
//    var	select=document.getElementById(id); 
//    var	option = select.options[select.selectedIndex];
//    var objcity = option.attributes.value;
//    $.ajax({
//        type: "POST",
//        url: "/ajax/teams/",
//        data: "cityId="+objcity.value,
//        success: function(data){
//            var data = eval("(" + data + ")");            
//            if(data == null) {
//                return;
//            }         
//            var team_data = data.data;           
//            $("#" + team_name).empty(); 
//            $("<option value=\"0\">全部</option>").appendTo("#" + team_name);
//            if (team_data != null) {               
//                $.each(team_data, function(i, n){
//                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + team_name);
//                });
//            }
//        }
//    });
//}


function get_companyid(company_name_name, company_id_name, company_suggest_name, limit)
{
    //初始化	
    var default_group_name = '';
    var default_group_id = '';
    var default_suggest = '';
    $("#"+company_suggest_name).append(default_suggest);	
    //失去焦点隐藏提示
    $("#"+company_name_name).blur(function(){
        $("#"+company_suggest_name).fadeOut();
    });
    $("#"+company_name_name).unbind('keyup');
    $("#"+company_name_name).keyup(function(e){
        
        var company_name = $(this).val();
        //监听回车事件
//        if( e.keyCode==13 ) {
//            var select_span = $("#suggest");
//            if(select_span.attr("id") > 0 ) {
//                $("#company_id").val(select_span.attr("id"));
//                $("#company_name").val(select_span.text());				
//                select_span.removeClass("sel");
//                $("#company_suggest").fadeOut();
//            } else {
//                $("#company_name").val(default_company_name);
//                $("#company_id").val(default_group_id);
//                $("#company_suggest").fadeOut();
//            }
//            return;
//        }
        //过滤部分不相关的按键
        switch ( e.keyCode ) {			
            case 13:case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
            case 38:case 39:case 40:case 45:return;
        }

        //请求前先将下拉选项数据清空
        $("#"+company_suggest_name).empty();	
        $("#"+company_id_name).val(0);
        if (company_name.length > 0 ) {
            var cityId = $("#cityId").val();
            get_company_list(cityId, company_name, company_name_name, company_id_name, company_suggest_name, limit);
        } else {
            $("#"+company_suggest_name).empty();
            $("#"+company_suggest_name).append(default_suggest);
            $("#"+company_id_name).val(default_group_id);			
            $("#"+company_name_name).val(default_group_name);
        }
    });
}

/*
 * 获得经纪公司列表
 * cityId       城市ID
 * company_name  公司名称,模糊查找
 * 
 */
function get_company_list(cityId, company_name, company_name_name, company_id_name, company_suggest_name, limit)
{
    var data_request = "q_word=" + encodeURIComponent(company_name) + "&cityId=" + cityId;
    if(null != limit) {
        data_request = data_request + '&num=' + limit;
    }

    $.ajax({
        type: "POST",
        url: "/ajax/getcompanybycityid/",
        data: data_request,
        dataType: "json",
        success: function( result ){
            if (result != null && result != "" ) {
                $("#"+company_suggest_name).remove();
                $("#"+company_name_name).after('<ul role="menu" class="dropdown-menu pull-left" id="'+company_suggest_name+'"></ul>');
                //创建下拉列表						
                $(result).each(function(i, n){							
                    $("#"+company_suggest_name).append('<li id="' + n.company_id + '"><a>'+ n.company_name_abbr + '</a></li>');
                });
                
                //点击事件
                $("#"+company_suggest_name+" li").click(function(){							
                    var company_id = $(this).attr("id");							
                    $("#"+company_id_name).val(company_id);													
                    $("#"+company_name_name).val($(this).text());
                    $("#"+company_suggest_name).remove();
                });
                //$("#company_suggest").fadeIn();
                $("#"+company_suggest_name).show();
            } else {
                $("#"+company_suggest_name).remove();
                //$("#company_suggest").fadeOut();
            }
        }
    });
}

function get_agentid(agent_name_name, agent_id_name, agent_suggest_name, area_id_name, block_id_name, company_name_name, limit)
{  
    //初始化	
    var default_group_name = '';
    var default_group_id = '';
    var default_suggest = '';
    $("#"+agent_suggest_name).append(default_suggest);	
    //失去焦点隐藏提示
    $("#"+agent_name_name).blur(function(){
        $("#"+agent_suggest_name).fadeOut();
    });
    $("#"+agent_name_name).unbind('keyup');
    $("#"+agent_name_name).keyup(function(e){
        var agent_name = $(this).val();		
        //alert(agent_name);
        //监听回车事件
//        if( e.keyCode==13 ) {
//            var select_span = $("#agent_suggest");
//            if(select_span.attr("id") > 0 ) {
//                $("#agent_id").val(select_span.attr("id"));
//                $("#agent_name").val(select_span.text());				
//                select_span.removeClass("sel");
//                $("#agent_suggest").fadeOut();
//            } else {
//                $("#agent_name").val(default_company_name);
//                $("#agent_id").val(default_group_id);
//                $("#agent_suggest").fadeOut();
//            }
//            return;
//        }
        //过滤部分不相关的按键
        switch ( e.keyCode ) {			
            case 13:case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
            case 38:case 39:case 40:case 45:return;
        }

        //请求前先将下拉选项数据清空
        $("#"+agent_suggest_name).empty();		
        if (agent_name.length > 0 ) {
            var cityId = $("#cityId").val();
            var area_id = $("#"+area_id_name).val();
            var block_id = $("#"+block_id_name).val();
            var company_name = $("#"+company_name_name).val();
            get_agent_list(company_name, cityId, area_id, block_id, agent_name, agent_name_name, agent_id_name, agent_suggest_name, limit);
        } else {
            $("#"+agent_suggest_name).empty();
            $("#"+agent_suggest_name).append(default_suggest);
            $("#"+agent_id_name).val(default_group_id);			
            $("#"+agent_name_name).val(default_group_name);
        }
    });
}

/*
 * 获得经纪门店列表
 * cityId       城市ID
 * agent_name    门店名称,模糊查找
 * 
 */
function get_agent_list(company_name, cityId, area_id, block_id, agent_name, agent_name_name, agent_id_name, agent_suggest_name, limit)
{
    var data_request = "q_word=" + encodeURIComponent(agent_name) + "&cityId=" + cityId + '&distId=' + area_id + '&regId=' + block_id + '&comName=' + encodeURIComponent(company_name);
    if(null != limit) {
        data_request = data_request + '&num=' + limit + '&type=edit';
    }
    $.ajax({
        type: "POST",
        url: "/ajax/getshopbycity/",
        data: data_request,
        dataType: "json",
        success: function( result ){
            if ( result!=null && result!="" ) {
                $("#"+agent_suggest_name).remove();
                $("#"+agent_name_name).after('<ul role="menu" class="dropdown-menu pull-left" id="'+agent_suggest_name+'"></ul>');
                //创建下拉列表						
                $(result).each(function(i, n){							
                    $("#"+agent_suggest_name).append('<li id="' + n.agent_id + '"><a>'+ n.agent_name + '</a></li>');
                });
                
                //点击事件
                $("#"+agent_suggest_name+" li").click(function(){							
                    var agent_id = $(this).attr("id");							
                    $("#"+agent_id_name).val(agent_id);													
                    $("#"+agent_name_name).val($(this).text());
                    $("#"+agent_suggest_name).remove();
                });
                //$("#agent_suggest").fadeIn();
                $("#"+agent_suggest_name).show();
            } else {
                $("#"+agent_suggest_name).remove();
                //$("#agent_suggest").fadeOut();
            }
        }
    });
}
