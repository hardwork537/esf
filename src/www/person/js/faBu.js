

//页眉部分快速导航
function showQuick(aid,did){
    var obj = document.getElementById(aid);
    var divotherChannel=document.getElementById(did);
    obj.className = "menu_btn hover";
    divotherChannel.style.display = "block";
}
function hideQuick(aid,did){
    var divotherChannel=document.getElementById(did);
    var mydd=document.getElementById(aid);
    if(divotherChannel.style.display!="none"){
        divotherChannel.style.display="none";
        mydd.className="menu_btn";
    }
}



$(document).ready(function() {

    getParkSuggest();

//下拉选择层
    $(".item-drop-list").hide();
    $(".item-drop").click(
        function(){
            if(!$(this).children(".item-drop-list").is(":animated")){//判断是否处于动画
                $(this).addClass("hover");
                $(this).children(".item-drop-list").fadeIn();
                $(".item-drop-list").not($(this).children(".item-drop-list")).fadeOut(0);
                return false;
            }
        });
    $(".item-drop-list span a").click(function(event){
        var objThis2Parent = $(this).parent().parent();
        objThis2Parent.parent().removeClass("hover");
        var text = $(this).text()
        var svalue = $(this).attr("src");
        var objThis3Parent = objThis2Parent.parent(".item-drop");
        objThis3Parent.children("em").text(text);
        objThis3Parent.children("input[type=hidden]").val(svalue).focus().blur();
        objThis2Parent.fadeOut();
        $("#search_form").attr("action", $(this).attr("target"));
        return false;
    });
    $(document).click(function(event){
        $(".item-drop-list").fadeOut(200);
        $(".item-drop-list").parent().removeClass("hover");
    });

    //价格范围确定按钮变色
    $('.searchBot .inputBox_k .btn').mouseover(
        function(){
            $(this).addClass('btn-hover');
        });
    $('.searchBot .inputBox_k .btn').mouseout(
        function(){
            $(this).removeClass('btn-hover');
        });

    //文本域文字变色
    $('.textAreaBox textarea').focus(
        function(){
            $(this).addClass('btn-hover');
        });
    $('.textAreaBox textarea').mouseover(
        function(){
            $(this).removeClass('btn-hover');
        });


//租房联想框变色
    $('.lenovo li').mouseover(
        function(){
            $(this).addClass('hover');
        });
    $('.lenovo li').mouseout(
        function(){
            $(this).removeClass('hover');
        });

//搜索框
    $(".box_menu span").click(
        function () {
            if(!$(this).parent().find(".hide_box").is(":animated")){//判断是否处于动画
                $(this).parent().parent().find("span").removeClass("active");
                $(this).addClass("active");

                $(".hide_box").hide();
                $(this).parent().find(".hide_box").fadeIn();
                //$(".hide_box").not($(this).find(".hide_box")).fadeOut(0);
                return false;
            }
        });
    $(".hide_box").click(function(){
        return false;
    })

    $(".hide_box a").click(function(event){
        var text = $(this).html()
        var svalue = $(this).attr('val')

        $(this).parent().parent().parent().find("span").html(text).removeClass("active");
        //$(this).parent().parent("#select_list").find("input[type=hidden]").val(svalue);
        $(this).parent().parent().children("input[type=hidden]").val(svalue);
        $(this).parent().parent(".hide_box").fadeOut();

        return false;
    });
    $("#priceset").click(function(event){
        var low_price = $("#low_price").val();
        var top_price = $("#top_price").val();
        if (low_price>0 || top_price>0)
        {
            $(this).parent().parent().parent().find("span").text(low_price+"-"+top_price);
            $(this).parent().parent().children("input[type=hidden]").val(low_price+"－"+top_price);
        } else {
            $(this).parent().parent().parent().find("span").text("不限");
            $(this).parent().parent().children("input[type=hidden]").val("");
        }
        $(this).parent().parent().parent().find("span").removeClass("active");
        $(this).parent().parent(".hide_box").fadeOut();
        return false;
    });

    $(document).click(function(event){
        $(".hide_box").fadeOut(200);
        $(".box_menu.newsbg .news .news_list").fadeOut();
        $(".box_menu span").removeClass("active");
    });


    $(".box_menu.newsbg .news span").click(function(){
        if(!$(this).parent().find(".hide_box").is(":animated")){

            $(this).parent().find(".news_list").fadeIn();

        }
    })




});

$(function(){



    $('.dengLu .newUser').click(function(){
        var h = $(document).height();
        $('#screen').css({ 'height': h });
        $('#screen').show();
        $('#popBox02').center();
        $('#popBox02').fadeIn();
        $('#popBox').hide();
        return false;
    });

})

jQuery.fn.center = function(loaded) {
    var obj = this;
    if ( obj.is(':hidden')) return false;
    body_width = parseInt($(window).width());
    body_height = parseInt($(window).height());
    block_width = parseInt(obj.width());
    block_height = parseInt(obj.height());

    left_position = parseInt((body_width/2) - (block_width/2)  + $(window).scrollLeft());
    if (body_width<block_width) { left_position = 0 + $(window).scrollLeft(); };

    top_position = parseInt((body_height/2) - (block_height/2) + $(window).scrollTop());
    if (body_height<block_height) { top_position = 0 + $(window).scrollTop(); };

    if(!loaded) {

        obj.css({'position': 'absolute'});
        obj.css({ 'top': top_position, 'left': left_position });
        $(window).bind('resize', function() {
            obj.center(!loaded);
        });
        $(window).bind('scroll', function() {
            obj.center(!loaded);
        });

    } else {
        obj.stop();
        obj.css({'position': 'absolute'});
        obj.animate({ 'top': top_position }, 200, 'linear');
    }
}


// JavaScript Document

//checkForm
/*
 * @abstract
 * nmsg: 为空提示语
 * emsg: 错误提示语
 *
 * dmsg: a类型"默认"的提示语
 * tmsg: a类型"正则"的错误提示语
 * cmsg: a类型"长度"的错误提示语
 */
var flag_result = true; //获取表单校验结果
$(document).ready(function () {

    $('.tn').blur(function () {
        checkAll($(this), false);
    });
    $('.tn').focus(function () {
        var datatype = $(this).attr('datatype').split("~");
        var obj = $(this);
        switch (datatype[0]) {
            case 'n':
            case 'f':
            case 'z':
                setNotice(obj, obj.attr('nmsg'));
                break;
            case 'a':
                // setFlag(datatype[3], false);
                setNotice(obj, obj.attr('dmsg'));
                break;
            case 'b':
            case 'd':
                obj.parent().parent().children('span').each(function () {
                    var objTmp = $(this);
                    var boolIsEmTmp = objTmp.html();
                    if (boolIsEmTmp.indexOf('tn') < 0) { return false; }
                    var thisInputObjTmp = objTmp.children('input');
                    setRight(thisInputObjTmp);
                });
                setNotice(obj, obj.attr('nmsg'));
                break;
            default:
                break;
        }
    });

    $(".selectAll").click(function () {
        var flag_select = $(this).prop("checked") ? 'checked' : false;
        $(this).parent().parent().siblings('span').each(function () {
            $(this).children('label').children(':checkbox').prop("checked", flag_select);
        });
    });

    $("#checkFormSubmit").click(function () {
        $("#personUnit").submit(function () {
            var flag = false;
            $('.tn').each(function () {
                if (checkAll($(this), true) != true) {
                    // alert( $(this).attr('name') );
                    // $(this).focus();
                    flag = true;
                    // return false;
                }
            });
            if (flag) return false;
            var flag2 = true;
            $.ajax({
                type: "POST",
                url: "/ajax/checkLogin/?r=" + Math.random(),
                dataType: "html",
                async: false,
                success: function (data) {
                    if (1 != data) {//data==1表示已经登陆，否则弹出快速登陆
                        $('#screen').show();
                        var objPopBox = $('#popBox');
                        objPopBox.fadeIn();
                        objPopBox.center();
                        flag2 = false;
                    }
                }
            });
            return flag2;
        });
        if (flag_result) {
            $("#personUnit").submit(); //@TODO由于联想下拉框需要回车补填，导致与表单冲突
        }
        //延迟提交
        if ((lazyDo instanceof Function) && !flag_result) {
            lazyDo();
        }
    });
});

function getCode() {
    var strPhone = $('input[name="contact_phone"]').val();
    var strPhoneCode = $('input[name="phoneCode"]').val();
    var intPhoneType = $('input[name="phone_type"]').val();
    $.ajax({
        type: "POST",
        url: "/ajax/getPhoneCode/?r=" + Math.random(),
        data: "contact_phone=" + strPhone + '&phoneCode=' + strPhoneCode + '&phone_type=' + intPhoneType,
        dataType: "html",
        async: false,
        success: function (data) {
            var len = data.length;
            if (len < 5) {
                $('input[name="phoneCode"]').attr('disabled', false); //启用文本框
                setTime();
            } else if (/^\d+$/.test(data)) {
                $('input[name="phoneCode"]').attr('disabled', false); //启用文本框
                $('input[name="phoneCode"]').val(data);
            } else {
                setError($('input[name="phoneCode"]'), data);
            }
        }
    });
}
var intTime = 180;
function setTime() {
    var obj = $('#codeMsg');
    obj.html('验证码已发送，3分钟内未收到，请重新获取(' + intTime + ')');
    if (intTime > 0) {
        window.setTimeout("setTime()", 1000);
    } else {
        obj.html('<a href="javascript:getCode();" style="color:#0073C6;">重新获取验证码</a>');
        intTime = 180;
    }
    intTime = parseInt(intTime) - 1;
}



//验证所有表单元素
function checkAll(obj, boolSubmit) {
    var datatype = obj.attr('datatype').split("~");
    var thisVal = trim(obj.val());
    var valLength = thisVal.length;
    switch (datatype[0]) {
        case 'n':
            if (valLength < 1 || isNaN(thisVal)) {
                setError(obj); return false;
            }
            if (thisVal.indexOf('.') >= 0) {
                setError(obj); return false;
            }
            thisVal = parseInt(thisVal);
            if (thisVal < parseInt(datatype[1]) || thisVal > parseInt(datatype[2])) {
                setError(obj); return false;
            }
            setRight(obj); return true;
            break;
        case 'f':
            if (valLength < 1 || isNaN(thisVal)) {
                setError(obj); return false;
            }
            var tmp = thisVal.split(".");
            if (tmp[1] && tmp[1].length > 2) {
                setError(obj); return false;
            }
            thisVal = parseFloat(thisVal);
            if (thisVal < parseFloat(datatype[1]) || thisVal > parseFloat(datatype[2])) {
                setError(obj); return false;
            }
            setRight(obj); return true;
            break;
        case 'z':
            if (valLength < parseInt(datatype[1]) || valLength > parseInt(datatype[2])) {
                setError(obj); return false;
            }
            var reg = eval(obj.attr('preg'));
            if (!reg.test(thisVal)) {
                setError(obj); return false;
            }
            setRight(obj); return true;
            break;
        case 'a':
            if (obj.attr('wmsg') && obj.attr('wmsg') == obj.val()) { setError(obj, obj.attr('nmsg')); return false; }
            if (valLength < 1) { setError(obj, obj.attr('nmsg')); return false; }
            if (valLength < parseInt(datatype[1]) || valLength > parseInt(datatype[2])) { setError(obj, obj.attr('cmsg')); return false; }

            if (obj.attr('preg')) {
                var reg = eval(obj.attr('preg'));
                if (!reg.test(thisVal)) { setError(obj, obj.attr('tmsg')); return false; }
            }
            setRight(obj); return true;
            break;
        case 'b':
            var objNow = '';

            var objTmp = ''; //初始化循环内对象变量
            var strHtmlTmp = ''; //初始化循环内html的值
            var objInputTmp = ''; //循环内的input对象
            var strInputValTmp = ''; //循环内的input对象的值
            var inputValTmp1 = ''; //转换后的循环内的input对象的值
            switch (datatype[1]) {
                case 'n':
                    obj.parent().parent().children('span').each(function () {
                        objTmp = $(this);

                        strHtmlTmp = objTmp.html();
                        if (strHtmlTmp.indexOf('tn') < 0) { return false; }

                        objInputTmp = objTmp.children('input');
                        setRight(objInputTmp);
                        strInputValTmp = trim(objInputTmp.val());
                        inputValTmp1 = parseInt(strInputValTmp);
                        datatype = objInputTmp.attr('datatype').split("~");
                        if (strInputValTmp.length < 1 || isNaN(strInputValTmp) || strInputValTmp.indexOf('.') >= 0 || inputValTmp1 < parseInt(datatype[2]) || inputValTmp1 > parseInt(datatype[3])) {
                            if (objNow == '') objNow = objInputTmp;
                        }
                    });
                    break;
                case 'f':
                    obj.parent().parent().children('span').each(function () {
                        objTmp = $(this);

                        strHtmlTmp = objTmp.html();
                        if (strHtmlTmp.indexOf('tn') < 0) { return false; }

                        objInputTmp = objTmp.children('input');
                        setRight(objInputTmp);
                        strInputValTmp = trim(objInputTmp.val());
                        var tmp = strInputValTmp.split(".");
                        inputValTmp1 = parseFloat(strInputValTmp);
                        datatype = objInputTmp.attr('datatype').split("~");
                        if (strInputValTmp.length < 1 || isNaN(strInputValTmp) || inputValTmp1 < parseFloat(datatype[2]) || inputValTmp1 > parseFloat(datatype[3]) || (tmp[1] && tmp[1].length > 2)) {
                            if (objNow == '') objNow = objInputTmp;
                        }
                    });
            }
            if (objNow == '') {
                setRight(obj); return true;
            } else { setError(objNow, objNow.attr('emsg')); return false; }
            break;
            break;
        case 'c':
            if (valLength < 1 || isNaN(thisVal)) {
                setError(obj); return false;
            }
            if (thisVal.indexOf('.') >= 0) {
                setError(obj); return false;
            }
            thisVal = parseInt(thisVal);
            if (thisVal < parseInt(datatype[1]) || thisVal > parseInt(datatype[2])) {
                setError(obj); return false;
            }
            return validateLenovo();
            break;
        case 'd':
            var arrD = new Array();
            obj.parent().parent().children('span').each(function () {
                objTmp = $(this);

                strHtmlTmp = objTmp.html();
                if (strHtmlTmp.indexOf('tn') < 0) { return false; }

                objInputTmp = objTmp.children('input');

                arrD.push(objInputTmp);
                setRight(objInputTmp);
            });

            var fltFirstVal = parseFloat(trim(arrD[0].val()));
            var fltFirstDataType = arrD[0].attr('datatype');
            var fltMin = fltFirstDataType.split("~");
            fltMin = parseFloat(fltMin[1]);

            var fltSecondVal = parseFloat(trim(arrD[1].val()));
            var fltSecondDataType = arrD[1].attr('datatype');
            var fltMax = fltSecondDataType.split("~");
            fltMax = parseFloat(fltMax[1]);

            inputValTmp1 = parseFloat(strInputValTmp);
            datatype = objInputTmp.attr('datatype').split("~");

            var objNow = '';
            var msg = '';
            for (var i in arrD) {
                if (objNow == '') {
                    if (!isNaN(fltSecondVal) || fltFirstVal > fltMax) {
                        if (isNaN(fltFirstVal)) { objNow = arrD[i]; msg = objNow.attr('nmsg'); }
                        if (fltFirstVal < fltMin) { objNow = arrD[i]; msg = '请输入大于' + fltMin + '的数字'; }
                        if (fltSecondVal > fltMax) { objNow = arrD[i]; msg = '请输入小于' + fltMax + '的数字'; }
                        if (fltFirstVal >= fltSecondVal) { objNow = arrD[i]; msg = '请输入小于' + fltSecondVal + '的数字'; }
                        var tmp = arrD[i].val();
                        tmp = tmp.split(".");
                        if (tmp[1] && tmp[1].length > 2) { objNow = arrD[i]; msg = objNow.attr('tmsg'); }
                    } else {
                        objNow = arrD[i]; msg = objNow.attr('nmsg');
                    }
                }
            }

            if (objNow == '') {
                setRight(obj); return true;
            } else { setError(objNow, msg); return false; }
            break;
        default:
            break;
    }
}


function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function changeClass(obj, newClass) {
    obj.removeClass('bor_red bor_blue bor_green cure cureCen cureRed cureRight');
    obj.addClass(newClass);
}
function setError(obj, msg) {
    var objSpan = obj.parent('span');  //input上一级span
    changeClass(objSpan, 'bor_red');

    var objChild = objSpan.siblings('.flag'); //提示信息class
    changeClass(objChild, 'cureRed');

    var objChildChild = objChild.children('span'); //提示信息下一级span
    changeClass(objChildChild, 'cureCen');
    var msg = msg ? msg : obj.attr('emsg');
    objChildChild.html(msg);
}
function setNotice(obj, msg, className1, className2) {
    var objSpan = obj.parent('span');  //input上一级span
    changeClass(objSpan, 'bor_blue');

    var objChild = objSpan.siblings('.flag'); //提示信息class
    var className1 = className1 ? className1 : 'cure';
    changeClass(objChild, className1);

    var objChildChild = objChild.children('span'); //提示信息下一级span
    var className2 = className2 ? className2 : 'cureCen';
    changeClass(objChildChild, className2);
    var msg = msg ? msg : obj.attr('nmsg');
    objChildChild.html(msg);
}
function setRight(obj, msg) {
    var objSpan = obj.parent('span');  //input上一级span
    changeClass(objSpan, '');

    var objChild = objSpan.siblings('.flag'); //提示信息class
    changeClass(objChild, 'flag cureRight');

    var objChildChild = objChild.children('span'); //提示信息下一级span
    objChildChild.attr('class', '');
    var msg = msg ? msg : '';
    objChildChild.html(msg);
}

function getParkSuggest(){
    var objParkId = $('#person_house_id');
    var objParkInput = $("#person_house_name");
    var objAutocomplete = $("#parkAutocomplete");
    var objParkAddress = $("#personParkAddress");

    /* 初始化 */
    var items_idx = -1,
        houseName = "",
        houseId = "",
        flag1 = false,
        flag2 = false,
        parkAddress = '';

    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest($("#idx_autocomplete")).length == 0) {
            if (!objAutocomplete.is(":animated")) {
                items_idx = -1;
                objAutocomplete.fadeOut(0);
                objAutocomplete.find("li").removeClass("hover");
                if(objParkInput.val() == ""){
                    objParkInput.removeClass("focus");
                }
            }
        }
    });

    /* 键盘输入 */
    objParkInput.keyup(function(e){
        strIndexInput = $.trim(objParkInput.val());
        flag2 = true;

        /* 监听上方向键选择 */
        if(e.keyCode == 38){
            items_idx--;
            if(items_idx < 0){
                items_idx = objAutocomplete.find("li").length-1;
            }
            itemHover(items_idx);
            flag2 = true;
        }

        /* 监听下方向键选择 */
        if(e.keyCode == 40){
            items_idx++;
            if(items_idx >= objAutocomplete.find("li").length){
                items_idx = 0;
            }
            itemHover(items_idx);
            flag2 = true;
        }

        /* 监听删除键 */
        if(e.keyCode == 8 || e.keyCode == 46){
            if(strIndexInput == ""){
                objParkId.val("");
                flag2 = false;
            }
        }

        //监听回车事件
        if ( e.keyCode == 13 ) {
            if(flag2){
                if(objAutocomplete.find("li.hover").length > 0){
                    houseName = objAutocomplete.find("li.hover").attr("title");
                    houseId = objAutocomplete.find("li.hover").attr("id");
                    parkAddress = objAutocomplete.find("li.hover").attr("address");
                    objParkInput.val(houseName);
                    objParkId.val(houseId);
                    objParkAddress.html(parkAddress).show();
                    objAutocomplete.hide();
                }
            }
            return;
        }

        switch ( e.keyCode ) {
            case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
            case 38:case 39:case 40:case 45:return;
        }

        if( strIndexInput.length < 0 ){
            return ;
        }

        /* 模拟数据
         objAutocomplete.empty();
         var li = '<li title="仁恒河滨城" id="1">仁恒河滨城</li><li title="瑞虹新城一期" id="2">瑞虹新城一期</li><li title="中凯城市之光" id="3">中凯城市之光</li>';
         $(li).appendTo(objAutocomplete).on('click',function (){
         houseName = $(this).text();
         houseId = $(this).attr("id");
         objParkInput.val(houseName);
         objParkId.val(houseId);
         objAutocomplete.hide();
         setRight($(objParkInput));
         return false;
         });
         objAutocomplete.show(); */
        $.ajax({
            type: "POST",
            url: "/ajax/getParkName/",
            data: "q=" + strIndexInput + "&cityId=" + cityId + "&is_showb=0&unit_type="+$("#unit_type").val(),
            dataType: "json",
            success: function( result ){
                objAutocomplete.empty();
                if( result == null || result == "null" ){
                    return;
                }
                $(result).each(function(i, n){
                    $('<li address="'+ n.address+'" title="'+n.name+'" id="'+n.id+'">'+n.name+'</li>').appendTo(objAutocomplete).on('click',function (){
                        houseName = $(this).text();
                        houseId = $(this).attr("id");
                        parkAddress = $(this).attr("address");
                        objParkInput.val(houseName);
                        objParkId.val(houseId);
                        objParkAddress.html(parkAddress).show();
                        objAutocomplete.hide();
                        setRight($(objParkInput));
                        return false;
                    });
                });
                objAutocomplete.show();
            }
        });

        function itemHover(items_idx){
            objAutocomplete.find("li").removeClass("hover");
            objAutocomplete.find("li").eq(items_idx).addClass("hover");
        }

        objAutocomplete.find("li").hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });

    });

}

