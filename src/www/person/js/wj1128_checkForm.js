/*
 * @abstract
 * nmsg: 为空提示语
 * emsg: 错误提示语
 * 
 * dmsg: a类型"默认"的提示语
 * tmsg: a类型"正则"的错误提示语
 * cmsg: a类型"长度"的错误提示语
 */
var flag_result = true;//获取表单校验结果
var global_phone = 0;//手机号码
$(document).ready(function() {

    $( '.tn' ).blur(function (){
        //console.log($(this).attr("name"));
        checkAll( $(this),false );
    });
    $( '.tn' ).focus(function (){
        var datatype = $(this).attr('datatype').split("~");
        var obj = $(this);

        switch( datatype[0] ){
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
                obj.parent().parent().children('span').each(function (){
                    var objTmp = $(this);
                    var boolIsEmTmp = objTmp.html();
                    if( boolIsEmTmp.indexOf('tn')<0 ){ return false; }
                    var thisInputObjTmp = objTmp.children('input');
                    setRight(thisInputObjTmp);
                });
                setNotice(obj, obj.attr('nmsg') );
                break;
            default:
                break;
        }
    });

    $(".selectAll").click(function () {
        var flag_select = $(this).attr("checked")? 'checked': false;
        $(this).parent().parent().siblings('span').each(function (){
            $(this).children('label').children(':checkbox').attr("checked", flag_select);
        });
    });

    $("#checkFormSubmit").click( function() {
        $("#personUnit").submit( function() {
            var flag = true;
            $('.tn').each(function (){
                //console.log('sdfsdfdsf');
                if( checkAll( $(this), true )!=true ){
                   // console.log( $(this).attr('name') );
                    $(this).focus();
                    flag = false;
                    // return false;
                }
            });
            flag_result = flag;
            if(!flag) return false;
//	        var flag2 = true;
//	        $.ajax({
//	            type: "POST",
//	            url: "/ajax/checklogin.php?r="+Math.random(),
//	            dataType: "html",
//	            async: false,
//	            success: function( data ){
//	            	if(1 != data){//data==1表示已经登陆，否则弹出快速登陆
//	            		$('#screen').show();
//						var objPopBox = $('#popBox');
//						objPopBox.fadeIn();
//						objPopBox.center();
//	                	flag2 = false;
//	            	}
//	            }
//	        });
//	            return flag2;
        });
        if(flag_result){
            $("#personUnit").submit();//@TODO由于联想下拉框需要回车补填，导致与表单冲突
        }
        //延迟提交
        if((lazyDo instanceof Function) && !flag_result){
            lazyDo();
        }
    });
});

function getCode(){
    var strPhone = $('input[name="contact_phone"]').val();
    var strPhoneCode = $('input[name="phoneCode"]').val();
    var intPhoneType = $('input[name="phone_type"]').val();
    $.ajax({
        type: "POST",
        url: "/ajax/getPhoneCode?r="+Math.random(),
        data: "contact_phone="+strPhone+'&phoneCode='+strPhoneCode+'&phone_type='+intPhoneType,
        dataType: "html",
        async: false,
        success: function( data ){
            var len = data.length;
            if( len<5 ) {
                $('input[name="phoneCode"]').prop('disabled','');//启用文本框
                $('input[name="phoneCode"]').val("");
                setTime();
            }else if( /^\d+$/.test(data) ){
                $('input[name="phoneCode"]').prop('disabled','');//启用文本框
                $('input[name="phoneCode"]').val(data);
            }else{
                setError( $('input[name="phoneCode"]'), data );
            }
        }
    });
}
var intTime = 180;
function setTime(){
    var obj = $('#codeMsg');
    obj.html('验证码已发送，3分钟内未收到，请重新获取('+intTime+')');
    if( intTime>0 ){
        window.setTimeout("setTime()",1000);
    }else{
        obj.html('<a href="javascript:getCode();" style="color:#0073C6;">重新获取验证码</a>');
        intTime = 180;
    }
    intTime = parseInt(intTime)-1;
}



//验证所有表单元素
function checkAll(obj, boolSubmit){
    var datatype = obj.attr('datatype').split("~");
    var thisVal = trim(obj.val());
    var valLength = thisVal.length;
    var contact_phone = $("input[name='contact_phone']").val();
    var phoneCode = $("input[name='phoneCode']").val();
    if (contact_phone && phoneCode && global_phone != contact_phone){
        $.ajax({
            url:"/ajax/checkPhoneCode",
            type:"post",
            data:{"code":phoneCode,'phone':contact_phone,'phoneType':$('input[name="phone_type"]').val(),'unitType': $("#strUnitType").val()},
            success:function(data){
                if (data == 1){
                    global_phone = contact_phone;
                    $.ajax({
                        url:"/ajax/recordPhone",
                        type:"post",
                        data:{"phone":contact_phone},
                        success:function(data){
                      //      $.cookie(contact_phone, 1, { path: '/', expires: 1 });
                        }
                    });
                }
            }
        });
    }
    switch( datatype[0] ){
        case 'n':
            if( valLength<1 || isNaN(thisVal) ){
                setError(obj); return false;
            }
            if( thisVal.indexOf('.')>=0 ){
                setError(obj); return false;
            }
            thisVal = parseInt(thisVal);
            if( thisVal<parseInt(datatype[1]) || thisVal>parseInt(datatype[2]) ){
                setError(obj); return false;
            }
            setRight(obj); return true;
            break;
        case 'f':
            if( valLength<1 || isNaN(thisVal) ){
                setError(obj); return false;
            }
            var tmp = thisVal.split(".");
            if( tmp[1] && tmp[1].length>2 ){
                setError(obj); return false;
            }
            thisVal = parseFloat(thisVal);
            if( thisVal<parseFloat(datatype[1]) || thisVal>parseFloat(datatype[2]) ){
                setError(obj); return false;
            }
            setRight(obj); return true;
            break;
        case 'z':
            if( valLength<parseInt(datatype[1]) || valLength>parseInt(datatype[2]) ){
                setError(obj); return false;
            }
            var reg = eval( obj.attr('preg') );
            if( !reg.test(thisVal) ){
                setError(obj); return false;
            }
            setRight(obj); return true;
            break;
        case 'a':
            if(obj.attr('wmsg') && obj.attr('wmsg')==obj.val()){  setError(obj, obj.attr('nmsg')); return false; }
            if( valLength<1 ){ setError(obj, obj.attr('nmsg')); return false; }
            if( valLength<parseInt(datatype[1]) || valLength>parseInt(datatype[2]) ){ setError(obj, obj.attr('cmsg')); return false;}

            if( obj.attr('preg') ){
                var reg = eval( obj.attr('preg') );
                if( !reg.test(thisVal) ){ setError(obj, obj.attr('tmsg')); return false; }
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
            switch( datatype[1] ){
                case 'n':
                    obj.parent().parent().children('span').each(function (){
                        objTmp = $(this);

                        strHtmlTmp = objTmp.html();
                        if( strHtmlTmp.indexOf('tn')<0 ){ return false; }

                        objInputTmp = objTmp.children('input');
                        setRight(objInputTmp);
                        strInputValTmp = trim(objInputTmp.val());
                        inputValTmp1 = parseInt(strInputValTmp);
                        datatype = objInputTmp.attr('datatype').split("~");
                        if( strInputValTmp.length<1||isNaN(strInputValTmp)||strInputValTmp.indexOf('.')>=0||inputValTmp1<parseInt(datatype[2])||inputValTmp1>parseInt(datatype[3]) ){
                            if( objNow=='' ) objNow = objInputTmp;
                        }
                    });
                    break;
                case 'f':
                    obj.parent().parent().children('span').each(function (){
                        objTmp = $(this);

                        strHtmlTmp = objTmp.html();
                        if( strHtmlTmp.indexOf('tn')<0 ){ return false; }

                        objInputTmp = objTmp.children('input');
                        setRight(objInputTmp);
                        strInputValTmp = trim(objInputTmp.val());
                        var tmp = strInputValTmp.split(".");
                        inputValTmp1 = parseFloat(strInputValTmp);
                        datatype = objInputTmp.attr('datatype').split("~");
                        if( strInputValTmp.length<1||isNaN(strInputValTmp)||inputValTmp1<parseFloat(datatype[2])||inputValTmp1>parseFloat(datatype[3])||(tmp[1] && tmp[1].length>2) ){
                            if( objNow=='' ) objNow = objInputTmp;
                        }
                    });
            }
            if( objNow=='' ){ setRight(obj);return true;
            }else{ setError(objNow, objNow.attr('emsg') ); return false; }
            break;
            break;
        case 'c':
            if( valLength<1 || isNaN(thisVal) ){
                setError(obj); return false;
            }
            if( thisVal.indexOf('.')>=0 ){
                setError(obj); return false;
            }
            thisVal = parseInt(thisVal);
            if( thisVal<parseInt(datatype[1]) || thisVal>parseInt(datatype[2]) ){
                setError(obj); return false;
            }
            return validateLenovo();
            break;
        case 'd':
            var arrD = new Array();
            obj.parent().parent().children('span').each(function (){
                objTmp = $(this);

                strHtmlTmp = objTmp.html();
                if( strHtmlTmp.indexOf('tn')<0 ){ return false; }

                objInputTmp = objTmp.children('input');

                arrD.push( objInputTmp );
                setRight(objInputTmp);
            });

            var fltFirstVal = parseFloat( trim( arrD[0].val() ) );
            var fltFirstDataType = arrD[0].attr('datatype');
            var fltMin = fltFirstDataType.split("~");
            fltMin = parseFloat( fltMin[1] );

            var fltSecondVal = parseFloat( trim( arrD[1].val() ) );
            var fltSecondDataType = arrD[1].attr('datatype');
            var fltMax = fltSecondDataType.split("~");
            fltMax = parseFloat( fltMax[1] );

            inputValTmp1 = parseFloat(strInputValTmp);
            datatype = objInputTmp.attr('datatype').split("~");

            var objNow = '';
            var msg = '';
            for(var i in arrD){
                if( objNow=='' ){
                    if( !isNaN(fltSecondVal)||fltFirstVal>fltMax ){
                        if( isNaN(fltFirstVal) ){ objNow=arrD[i];msg=objNow.attr('nmsg');  }
                        if( fltFirstVal<fltMin ){ objNow=arrD[i];msg='请输入大于'+fltMin+'的数字';  }
                        if( fltSecondVal>fltMax ){ objNow=arrD[i];msg='请输入小于'+fltMax+'的数字';  }
                        if( fltFirstVal>=fltSecondVal ){ objNow=arrD[i];msg='请输入小于'+fltSecondVal+'的数字';  }
                        var tmp = arrD[i].val();
                        tmp = tmp.split(".");
                        if( tmp[1] && tmp[1].length>2 ){ objNow=arrD[i];msg=objNow.attr('tmsg');  }
                    }else{
                        objNow=arrD[i];msg=objNow.attr('nmsg');
                    }
                }
            }

            if( objNow=='' ){ setRight(obj);return true;
            }else{ setError(objNow, msg ); return false; }
            break;
        default:
            break;


    }
}


function trim(str){
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function changeClass(obj, newClass){
    obj.removeClass('bor_red bor_blue bor_green cure cureCen cureRed cureRight');
    obj.addClass(newClass);
}
function setError(obj, msg){
    var objSpan = obj.parent('span');  //input上一级span
    changeClass(objSpan, 'bor_red');

    var objChild = objSpan.siblings('.flag'); //提示信息class
    changeClass(objChild, 'cureRed');

    var objChildChild = objChild.children('span'); //提示信息下一级span
    changeClass(objChildChild, 'cureCen');
    var msg = msg? msg: obj.attr('emsg');
    objChildChild.html( msg );
}
function setNotice(obj, msg, className1, className2){
    var objSpan = obj.parent('span');  //input上一级span
    changeClass(objSpan, 'bor_blue');

    var objChild = objSpan.siblings('.flag'); //提示信息class
    var className1 = className1? className1: 'cure';
    changeClass(objChild, className1);

    var objChildChild = objChild.children('span'); //提示信息下一级span
    var className2 = className2? className2: 'cureCen';
    changeClass(objChildChild, className2);
    var msg = msg? msg: obj.attr('nmsg');
    objChildChild.html( msg );
}
function setRight(obj, msg){
    var objSpan = obj.parent('span');  //input上一级span
    changeClass(objSpan, '');

    var objChild = objSpan.siblings('.flag'); //提示信息class
    changeClass(objChild, 'flag cureRight');

    var objChildChild = objChild.children('span'); //提示信息下一级span
    objChildChild.attr('class','');
    var msg = msg? msg: '';
    objChildChild.html(msg);
}
