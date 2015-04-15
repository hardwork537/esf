var reg_personal_password = /^[0-9A-Za-z~!@#\$%\^&\*\(\)_\+`\-\\=\[\];',\.\/\{\}\|:"<>\?]{6,16}$/;
var reg_personal_username = /^[a-zA-Z]\w{3,29}$/;
var reg_email = /^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
var reg_phone = /^[1][3|4|5|8]\d{9}$/;
var reg_qq = /^[1-9][0-9]{4,12}$/;

$(function () {

    /*头部菜单展开*/
    $(".quickLink").hover(function () {
        $(this).find(".menu_btn").addClass("hover");
        $(this).find(".bd").show();
    }, function () {
        $(this).find(".menu_btn").removeClass("hover");
        $(this).find(".bd").hide();
    })

    /* header city select */
    var $hdCity = $(".select_city"),
		$cityName = $hdCity.find(".hd"),
		$cityList = $hdCity.find(".cityArea_k"),
		$arrow = $cityName.find("s");
    $cityName.click(function () {
        if (!$cityList.is(":animated")) {
            $cityList.fadeIn("fast");
            $arrow.addClass("up");
        }
    });

    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest($cityList).length == 0) {
            if (!$cityList.is(":animated")) {
                $cityList.fadeOut("fast");
                $arrow.removeClass("up");
            }
        }
    });


    /* 左侧导航收缩 */
    $('#sideNav h2').click(function () {
        $(this).toggleClass("fold").next().toggle();
    });

});

function checkPersonalMail(str)
{
	
	var strVal = str.val();
	if(strVal == "")
	{
		var noticeMsg = str.attr("nullmsg");
		str.siblings(".notice").html(noticeMsg).removeClass("right").addClass("active error");
		return false;		  
	}
	if(!reg_email.test(strVal))
	{
		str.siblings(".notice").html("电子邮箱格式不对").removeClass("right").addClass("active error");
		return false;
	}
	$.ajax({
		type: "GET",
		url: "/ajax/checkPersonEmail/?rnd="+Math.random()*5,
		data: "email=" + str,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				str.siblings(".notice").html("").removeClass("error").addClass("active right");
			}else{
				str.siblings(".notice").html("这个邮箱已被别人占用啦").removeClass("right").addClass("active error");
				return false;
			}
		}
	}); 
}

function checkPersonalContactWay(str){
	if(str.val()=='')
	{
		str.siblings(".notice").html("").removeClass("active right error");
		return true;
	}
	if(!reg_qq.test($.trim(str.val())))
	{
		str.siblings(".notice").html("您输入的QQ格式有误").removeClass("right").addClass("active error");
		return false;
	}
	str.siblings(".notice").html("").removeClass("active error").addClass("right");
}

function checkPersonalPhone(str){
	if(str.val() == '')
	{
		str.siblings(".notice").html("").removeClass("active right error");
		return true;
	}
	if(!reg_phone.test(str.val()))
	{	
		str.siblings(".notice").html("您输入的手机号码格式有误").removeClass("right").addClass("active error");
		return false;
	}
	$.ajax({
		type: "GET",
		url: "/ajax/checkPersonPhone/?rnd="+Math.random()*5,
		data: "phone=" + str.val(),
		dataType: "json",
		async: false,
		cache: false,
		success: function(data){
			if(data == 1){
				str.siblings(".notice").html('已验证！').removeClass("active right error");//绑定信息显示
			}else{
				//data=0
				//手机修改 则显示未绑定信息 并 绑定信息显示
				str.siblings(".notice").html('尚未验证！<a href="javascript:void(0)" onclick="person_bing_phone()">马上验证</a>验证后可用手机号找回密码或者登录！').removeClass("error right").addClass("active");
			}
			//验证	
		}
	});
}

/**
* 绑定手机 phone 绑定手机号，
* 手机号：修改资料时输入的手机号；
* @author yanfang
*/

//个人修改资料页（or 其他页） 点绑定手机 检测 并记录memcache中 提交用户已输入的信息
function person_bing_phone(){
	var params = '';
	var phone = $("#phone").val();
	if(!phone){
		alert('手机号不能为空');
		return false;
	}
	if(!reg_phone.test(phone))
	{
		alert('手机格式有误');
		return false;
	}		
	params += 'phone='+ phone;
	
	$.ajax({
		type:"GET",
		url: "/ajax/checkPersonPhoneBind/?rnd="+Math.random()*5,
		data: params,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				window.location.href = '/index/phoneBind/';
			}else{
				alert("此手机号已与其它账户绑定,请更换后再绑定");
			}
		}
	});
}

/**
* 绑定手机 获取验证码
*/
function get_code_phone(){
	//actionVal == 'step1' 如果是绑定第一步，则成功时会跳转到第二步页面 否则不跳转（如：在输入验证码页再次点击发送验证码）
	var actionVal = arguments[0] ? arguments[0] : '';
	$.ajax({
		type:"GET",
		url: "/ajax/getPersonPhoneCode/?rnd="+Math.random()*5,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				if(actionVal == 'step1'){
					window.location.href = '/index/phoneBind/?action=step2';
				}else{
					if($("#span_disabled").length > 0 && $("#span_abled").length > 0){//倒计时显示处理
						$("#span_disabled").show();
						$("#span_abled").hide();
						timer = setInterval("CountDown()", 1000);
					}
				}
			}else{
				alert(data);
			}
		}
	});
}

var maxtime = 180;//倒计时 3分钟
/**
  * 绑定手机 发送验证码 倒计时
  */
function CountDown(){
	maxtime = maxtime - 1;
	document.getElementById("timer").innerHTML=maxtime;
	if(maxtime == 0){//时间到
		window.clearInterval(timer);
		maxtime = 180;
		document.getElementById("timer").innerHTML=maxtime;
		$("#span_disabled").hide();
		$("#span_abled").show();
	}
}

function get_submit_disable(res){
	if($("input[name='Submit1']").attr('disabled') === true){res = false;}
	return res;
}