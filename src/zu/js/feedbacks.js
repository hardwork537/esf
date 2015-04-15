function checkWord(theObj){
     var intLen = 140;
     var theVal = theObj.value;
     var theLen = theVal.length;
     var theLimitWord = intLen-parseInt(theLen);
     document.getElementById('limitWord').innerHTML = theLimitWord;
     if( theLen>intLen ){
        theVal = theVal.substring(0, intLen);
        theObj.value = theVal;
     }
}
function checkLast(){
	var strVal = document.getElementById('theContent').value;
    if( strVal == '' || strVal == "请留下您的宝贵意见"){
        alert('请输入反馈内容！');
        return false;
    }
}

/* 提交成功 */
function feedbackSuccess(){
    alert('反馈成功！');
    $("#theContent").val("");
    document.getElementById('limitWord').innerHTML = 140;
}

/* 显示提示框 */
function myalert(txt1,txt2){
    var type = txt1;	/*提示类型*/
    var msg = txt2;		/*提示文字*/
    //var html;
    alert(msg);
    /*1表示尚未登录，2表示错误信息，3表示反馈成功*/
    /*
    if(type == 1){
        html = '<div class="pop_con clearfix">'+
            '<em class="icon_tips"></em>'+
            '<p>'+msg+'</p>'+
            '</div>'+
            '<div class="pop_ft">'+
            '<a class="btn" onclick="popupClose()" href="http://i.focus.cn/login" target="_blank">登&nbsp;&nbsp;录</a>没有账号？<a class="link" href="http://i.focus.cn/reg" target="_blank">立即注册</a>'+
            '</div>'

    }
    if(type == 2){
        html = '<div class="pop_con clearfix">'+
            '<em class="icon_tips"></em>'+
            '<p>'+msg+'</p>'+
            '</div>'+
            '<div class="pop_ft">'+
            '<a class="btn" onclick="popupClose()" href="javascript:;">确定</a>'+
            '</div>'
    }
    if(type == 3){
        html = '<div class="pop_con clearfix">'+
            '<em class="icon_sure"></em>'+
            '<p>'+msg+'</p>'+
            '</div>'+
            '<div class="pop_ft">'+
            '<a class="btn" onclick="popupClose()" href="javascript:;">确定</a>'+
            '</div>'
    }*/
    //$("#popFeedback .bd").html(html);
    //$("#popFeedback").show();
}

/* 关闭弹出框
function popupClose(){
    $(".popup").hide();
}*/