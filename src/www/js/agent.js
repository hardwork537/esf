$(function(){
	tabs($("#flist_mod .tab"),$("#flist_mod .tab_con"));
	
	/* house slider */
	if($("#house_rec").length > 0){
		recHouseSlider();
	}

	/* 给经纪人留言 */
	$("#messageTextArea").focus(function(){
		if($(this).val() == this.defaultValue){
			$(this).val("");
			$(this).addClass("focus");
		}
	}).blur(function(){
		if(trim($(this).val()) == ""){
			$(this).val(this.defaultValue);
			$(this).removeClass("focus");
		}
	});
	
	/* 输入框聚焦时 */
	$("#gbookform .input_data").focus(function () {
        $(this).siblings(".tips").hide();
        $(this).addClass("focus");
    }).blur(function(){
		$(this).removeClass("focus");
	});
	
	/* 验证手机号 */
	$("#phonenum").blur(function(){	
		var contactValues = $(this).val();
		if(contactValues != ''){
			var reg = new RegExp(/^0{0,1}1[0-9]{10}$/);
			if(!reg.test(contactValues)){
				$(this).siblings(".tips").show();
			}
		}
	});
	
	/* 提示框关闭按钮 */
	$("#gbookform .tips a").click(function(){
		$(this).parent().hide();
	});
	
});
/* 输入框字数监听 */
var intLen = 140;
var theObj = document.getElementById("messageTextArea");

if("oninput" in document){
	theObj.addEventListener("input",checkWord,false);
}else{
	theObj.onpropertychange = checkWord;
}

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
/* 去除左右空格 */
function trim(str){   
	str = str.replace(/(^\s*)|(\s*$)|\r|\n/g, "")
	return str;   
} 

function getCodeAgain(){
	document.getElementById('checkcode').src='/ajax/checkcode?id='+Math.random()*5;
}

function checkForm(){
	if (!checkText()){
		return false;
	}
	if (!checkMobile()){
		return false;
	}
	if (!checkCode()){
		return false;
	}
	return true;
}

function checkCode(){
	var code = $.trim($("#code").val());
	if (code == ''){
		$("#code").addClass("error");
		$("#code").siblings(".tips").show();
		return false;
	}
	return true;
}

function checkText(){
	var obj = document.getElementById('messageTextArea');
	var val = trim(obj.value);
	if( val == '' || val == obj.defaultValue ){
		alert('请输入留言！');
		return false;
	}
	return true;
}
		
function checkMobile(){
	var phonenum_value = $("#phonenum").val();
	if (!phonenum_value || phonenum_value == null || !/1[3-8]+\d{9}/.test(phonenum_value)) {
		$("#phonenum").addClass("error");
		$("#phonenum").siblings(".tips").show();
		return false;
	} else {
		$("#phonenum").removeClass("error");
		$("#phonenum").siblings(".tips").hide();
		return true;
	}
}
function submitsuccess(){
	$("#remainnum").html('140');
	$("#checkcode").attr("src","/checkcode.php?id="+Math.random()*5);
	$("#messageTextArea").removeClass("error");
	$("#gbookform").get(0).reset();

}
/*精品房源效果*/
function recHouseSlider(){
	var $slider = $("#house_rec");
	var $sliderImg = $slider.find(".house_rec_img ul");
	var $sliderInfo = $slider.find(".house_rec_info .info");
	var $snav = $slider.find(".snav");
	var $snavBtn;
	/* 初始化 */
	var index = 0,
		length = $sliderImg.find("li").length,
		imgWidth = 351;
	
	/* 图片多于1张的时候生成底部的圆点 */
	var snavHtml = "";
	if(length > 1){
		for(var i=0; i<length; i++){
			snavHtml += "<span></span>";
		}
		$snav.append(snavHtml);
		$snavBtn = $snav.find("span");
		$snavBtn.eq(0).addClass("on");
		
		/* 自动播放 */
		startTimer();
		$slider.mouseenter(function(){
			endTimer();
		}).mouseleave(function(){
			startTimer();
		});
	}
	
	/* 初始化图片列表的总宽度 */
	$sliderImg.css("width",imgWidth*length);
	
	
	function startTimer(){				
		timer = setInterval(function(){
			index++;			
			if(index == length) index=0;
			$sliderImg.css("left",0);
			$sliderImg.find("li:first").clone().appendTo($sliderImg);
			scrollRight();
		},3000);
	}

	function endTimer(){
		if(timer){
			clearInterval(timer);
		}
	}
	
	$snavBtn && $snavBtn.click(function(){
		var sIndex = $(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		if(sIndex > index){
			var targetIndex = $sliderImg.find("li[data-index='"+sIndex+"']").index();
			$sliderImg.find("li:lt("+targetIndex+")").clone().appendTo($sliderImg);
			$sliderImg.find("li:eq("+targetIndex+")").clone().prependTo($sliderImg);
			$sliderImg.find("li:lt("+targetIndex+")").detach();
			scrollRight();
		}
		if(sIndex < index){
			var targetIndex = $sliderImg.find("li[data-index='"+sIndex+"']").index();
			$sliderImg.css("left",-imgWidth);
			$sliderImg.find("li:gt("+(targetIndex-1)+")").clone().prependTo($sliderImg);
			scrollLeft();
		}
	});
	
	function scrollLeft(){
		$sliderImg.animate({left:0},300,moveLeft);
	}
	
	function scrollRight(){
		$sliderImg.animate({left:-imgWidth},300,moveRight);
	}
	
	function moveLeft(){
		$sliderImg.find("li:last").detach();
		index = $sliderImg.find("li:first").attr("data-index");
		$snavBtn.eq(index).addClass('on').siblings().removeClass('on');
		$sliderInfo.eq(index).show().siblings().hide();
	};
	
	function moveRight(){
		$sliderImg.find("li:first").detach();
		$sliderImg.css("left",0);	
		index = $sliderImg.find("li:first").attr("data-index");
		$snavBtn.eq(index).addClass('on').siblings().removeClass('on');
		$sliderInfo.eq(index).show().siblings().hide();
	};
	/* 全区域可点 */
	var $clickBlock = $("#house_rec").find(".house_rec_wrap");
	$clickBlock.find('a').each(function(){
		$(this).click(function(e){
			if(e.stopPropagation){
				e.stopPropagation();
			}else{
				window.event.cancelable = true;
			}
		});
	});		
	
	$clickBlock.click(function(){
		var a = document.createElement("a");
		a.href = $sliderImg.find("li:first a").attr('href');
		a.target = "_blank";
		document.body.appendChild(a);
		a.click();
	});		
}
