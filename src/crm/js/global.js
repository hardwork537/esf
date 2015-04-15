/**
 * 模仿PHP的$_GET
 * @param {type} attr
 * @returns {String}
 */
base_url = "/";
souce_url = "http://src.esf.focus.cn/upload/crm/";
function _GET() {
    var _v = [];
    try {
        var $url = location.href;
    } catch (e) {
        var $url = window.top.location.href;
    }
    if($url.indexOf('?')<=0){
        return false;
    }
    $start = $url.indexOf('?') + 1;
    $end = $url.length;
    $Query_String = $url.substring($start, $end);
    
    $Get = $Query_String.split('&');
    for (var i in $Get) {
        $tmp = $Get[i].split('=');
        _v[$tmp[0]] = $tmp[1]; 
        //_v = decodeURIComponent($tmp[1]);
        
    }
    return _v;
}

/**
 * 生成Excel导出Url参数
 * @returns {undefined}
 */
function _excelQueryString(){
    var _vstr = [];
    var _v = _GET();
    for(var i in _v){
        if(i=="page"){
            continue;
        }
        _vstr.push(i+"="+_v[i]);
    }    
    _vstr.push("action=export");
    return _vstr.join("&");
}

/**
 * 不带page的Url参数串
 * @returns {undefined}
 */
function _nopageQueryString(){
    var _vstr = [];
    var _v = _GET();
    for(var i in _v){
        if(i=="page"){
            continue;
        }
        _vstr.push(i+"="+_v[i]);
    }
    return _vstr.join("&");
}

/**
 * Url参数串
 * @returns {undefined}
 */
function _QueryString(){
    var _vstr = [];
    var _v = _GET();
    for(var i in _v){
        _vstr.push(i+"="+_v[i]);
    }
    return _vstr.join("&");
}


/**
 * 用户注销
 * @returns  bool
 */
function loginout(){
    if(!confirm("确定要注销系统吗?")) return false;
    $.request({
        url: "/login/out/",
        callback: function(msg) {
            if (msg.status == 0) {
               location.reload(false);
            }
        }
    });
    return true;
}

/**
 * 异步获取团队
 * obj_id
 */
function get_team(obj_id,check_id,cityId)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(cityId)=="undefined"  || cityId==""){
        cityId = 0;
    }
    $.request({
        url: "/ajax/getteams/",
        data: "cityId="+cityId,
        async:false,
        callback:function(msg){
            var team_data = msg; 
            $("#" + obj_id).empty(); 
            $("<option value=\"0\">全部</option>").appendTo("#" + obj_id);
            if (team_data != null) {      
                $.each(team_data, function(i, n){
                    if(i==check_id){
                        $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                    }else{
                        $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                    }
                    
                });
            }
        }
    });
}

/**
 * 异步获取销售
 * obj_id, cityId,area_id,block_id,team_id
 */
function get_sell(obj_id,check_id,cityId,area_id,block_id,team_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(cityId)=="undefined" || cityId==""){
        cityId = 0;
    }
    if(typeof(area_id)=="undefined" ||  area_id==""){
        area_id = 0;
    }
    if(typeof(block_id)=="undefined" ||  block_id==""){
        block_id = 0;
    }
    if(typeof(team_id)=="undefined" ||  team_id==""){
        team_id = 0;
    }
    
    $.request({
        url: "/ajax/crmuser/",
        data: "cityId="+cityId+"&distIdd="+area_id+"&regId="+block_id+"&teamId="+team_id,
        async:false,
        callback: function(msg){
            var sell_data = msg.data;
            $("#" + obj_id).empty();
            var default_option_name = null == default_name ? '全部' : '无';
            if (sell_data == null) {
                $("<option value=\"0\">"+default_option_name+"</option>").appendTo("#" + obj_id);
            } else {
                $("<option value=\"0\">"+default_option_name+"</option>").appendTo("#" + obj_id);
                $.each(sell_data, function(i, n){
                    if(i==check_id){
                       $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                    }else{
                       $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id); 
                    }
                });
            }
        }
    });
}

/**
 * 异步加载区域
 */
function get_area(obj_id,check_id,cityId)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(cityId)=="undefined" || cityId==""){
        cityId = 0;
    }
    $.request({
        url: "/ajax/getdistricts/",
        data: "cityId=" + cityId,
        async:false,
        callback: function(data){
            $("#" + obj_id).empty();

            if (data == null) {
                $("<option value=\"0\">全部</option>").appendTo("#" + obj_id);
            } else {
                $("<option value=\"0\">全部</option>").appendTo("#" + obj_id);
                $.each(data, function(i, n){
                    if(i==check_id){
                        $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                    }else{
                        $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                    }
                });
            }
        }
    });
}

/**
* 异步获取板块
*/
function get_block(obj_id,check_id,cityId,area_id,need_default)
{
    
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(cityId)=="undefined" || cityId==""){
        cityId = 0;
    }
    if(typeof(area_id)=="undefined" ||  area_id==""){
        area_id = 0;
    }
    $.request({
        url: "/ajax/getregions/",
        data: "distId=" + area_id+"&cityId="+cityId,
        async:false,
        callback: function(data){
            $("#" + obj_id).empty();
            if(null == need_default) {
                $("<option value=\"0\">全部</option>").appendTo("#" + obj_id);
            }
            
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

/**
 * 异步获取端口
 * obj_id
 */
function get_port(obj_id,cityId,check_id)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(cityId)=="undefined"  || cityId==""){
        cityId = 0;
    }
    $.request({
        url: "/ajax/getportbycity/",
        data: "cityId="+cityId,
        async:false,
        callback:function(data){          
            $("#" + obj_id).empty(); 
            $("<option value=\"0\">全部</option>").appendTo("#" + obj_id);
            if (data != null) {      
                $.each(data, function(i, n){
                    if(check_id == i) {
                        $('<option selected="selected" value=' + i + ">" + n + "</option>").appendTo("#" + obj_id);
                    } else {
                        $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                    }                     
                });
            }
        }
    });
}

/**
 * 添加经纪公司帐户验证
 * @author tonyzhao <tonyzhao@sohu-inc.com>
 * object 对象
 */
function get_ent_accname(accname)
{ 
    var is_exist;
    if(checkAccountsPreg(accname)) {
        $.ajax({
            url: "/ajax/getcompanyaccount/",
            data: "accname=" + accname,
            dataType:"json", 
            async:false,
            success: function(data){
                if(data.status==1) { 
                    $.error('帐号已经存在,请重新输入!');
                    is_exist = false;
                } else {
                    is_exist = true;
                }              
            }
        });
        return is_exist;
    } else {
        $.error('账号格式错误，请重新输入');
        return false;
    }
		
}

var arrSubmit=new Array();
var pregAccounts=/^[\w+]{5,15}$/; //帐户匹配规则
var pregPasswd=/^[\w+]{6,16}$/;//密码匹配规则

/**
 * 帐户正则检测
 * @param obj
 * @param promptStr
 * @returns
 */
function checkAccountsPreg(accname)
{
    if(pregFromString(accname, pregAccounts)) {
        return true;
    }
    return false;
}

/**
 * 密码正则
 * @param pwd
 * @param promptStr
 */
function checkFormatPasswdPreg(pwd)
{
    return checkPreg(pwd,6,16,pregPasswd);
}

/**
 * 验证通用方法
 * @param string pwd
 * @param mixLength　最大长度
 * @param maxLength  最小长度
 * @param preg  正则表达式
 */
function checkPreg(pwd,mixLength,maxLength,preg)
{
    if(pwd.length>=mixLength && pwd.length<=maxLength) {
        if(pregFromString(pwd, preg)){ 
            return true;
        } else {
            $.error('格式错误，仅限英文字母、数字、字符!');
            return false;
        }
    } else {    
        if(pwd==null || pwd=="") {
            $.error('密码不能为空');
        } else {
            $.error('格式错误，密码长度为6-16位!');
        }
        return false;
    }   
}

/**
 * 二次密码验证
 * @param obj
 * @param master
 * @param promptStr
 */
function checkPasswdConformPreg(repwd,master)
{
    var bReturn = checkPreg(repwd,6,16,pregPasswd);
    if(bReturn){
        masterpasswd=$("#"+master).val();
        if(masterpasswd!=repwd) {
           $.error('确认密码和密码不一致，请重新输入');
           return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

/**
 * 正则匹配
 * @param str  匹配字符串
 * @param pregtype 正则表达式
 * @returns
 */
function pregFromString(str, pregtype)
{
    if(pregtype.test(str)) return true;
    return false;
}

$(function (){
    $('body').on('click', '.model_notice .back', function(e){
         $('.model_notice').modal('hide');
         location.reload(false);
    });
    $('body').on('click', '.model_notice .continue', function(e){
         $('.model_notice').modal('hide');
         $('.addmodal').click();
    });
    $('body').on('click', '.model_notice .close', function(e){
         $('.model_notice').modal('hide');
         location.reload(false);
    });
    
    //全选
    $(".checkall").change(function(){
        if($(this).is(":checked")){
            $(".checkall").prop("checked",true)
            $(".checkone").prop("checked",true);
        }else{
            $(".checkall").prop("checked",false)
            $(".checkone").prop("checked", false);
        }
        
    });
    
     //全选
    $(".checkone").change(function(){
        if($(this).not(":checked")){
            $(".checkall").prop("checked", false);
        } 
    });
  
})
