/* 输入框字数监听 */
	var intLen = 140;
	var theObj = document.getElementById("feedbackTextArea");
	
	if("oninput" in document){
		theObj.addEventListener("input",checkWord,false);
	}else{
		theObj.onpropertychange = checkWord;
	}

	$("#feedbackTextArea").focus(function(){
		if($(this).val() == this.defaultValue){
			$(this).val("");
			$(this).addClass("focus");
		}
	}).blur(function(){
		if(trim($(this).val()) == ""){
			$(this).val(this.defaultValue);
			$(this).removeClass("focus");
		}
	})
	/* 提交 */
	function checkLast(){
		var val = trim(theObj.value);
		if( val == "" || val == theObj.defaultValue ){
			myalert("2","请输入反馈内容！");
			return false;
		}
	};

	function checkWord(){
		var theVal = trim(theObj.value);
		if(theVal == theObj.defaultValue) return ;
		var theLen = theVal.length;
		var theLimitWord = intLen-parseInt(theLen);
		if(theLen > intLen){
			theVal = theVal.substring(0, intLen);
			theObj.value = theVal;
			theLimitWord = 0;
		}
		document.getElementById("remainnum").innerHTML = theLimitWord;
	}

	/* 提交成功 */
	function feedback_success(){
		$("#feedbackTextArea").val("请您留下宝贵意见...").removeClass("focus");
		$("#remainnum").html("140");
	}

	/* 去除左右空格 */
	function trim(str){   
		str = str.replace(/(^\s*)|(\s*$)|\r|\n/g, "")
		return str;   
	} 

	/* 显示提示框 */
	function myalert(txt1,txt2){
		var type = txt1;	/*提示类型*/
		var msg = txt2;		/*提示文字*/
		var html;
		if(type == 1){/*1表示尚未登录，2表示错误信息，3表示反馈成功*/
			html = '<div class="pop_con clearfix">'+
						'<em class="icon_tips"></em>'+
						'<h5>'+msg+'</h5>'+
					'</div>'+
					'<div class="pop_ft">'+
						'<a class="btn" onclick="popupClose()" href="http://i.focus.cn/login" target="_blank">登&nbsp;&nbsp;录</a>没有账号？<a class="link" href="http://i.focus.cn/reg" target="_blank">立即注册</a>'+
					'</div>'
		}
		if(type == 2){
			html = '<div class="pop_con clearfix">'+
						'<em class="icon_tips"></em>'+
						'<h5>'+msg+'</h5>'+
					'</div>'+
					'<div class="pop_ft">'+
						'<a class="btn" onclick="popupClose()" href="javascript:;">确定</a>'+
					'</div>'
		}
		if(type == 3){
			html = '<div class="pop_con clearfix">'+
						'<em class="icon_sure"></em>'+
						'<h5>'+msg+'</h5>'+
					'</div>'+
					'<div class="pop_ft">'+
						'<a class="btn" onclick="popupClose()" href="javascript:;">确定</a>'+
					'</div>'
		}
		$("#popFeedback .bd").html(html);
		$("#popFeedback").show();
	}
	



/*
if(navigator.userAgent.indexOf("MSIE 6.0") > 0) {
	$('#popFeedback .mask').css({
		'position' : 'absolute',
		'height' : $(document).height()
	});
	var top = $(document).scrollTop();
	$('#popFeedback .popup_box').css({
		"position" : "absolute",
		"top" : top + 150
	});
	$(window).scroll(function() {
		var top = $(this).scrollTop();
		$('#popFeedback .popup_box').css({
			"top" : top + 150
		});
	});
}
*/
