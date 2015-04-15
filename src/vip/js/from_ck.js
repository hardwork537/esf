/**
 * Ajax用户注册验证 JS
*/
//XMLHttpRequest 
	var xmlhttp = false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e2) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	function Ajax(data,strType){
		var strUrl=null;
		if(strType=="user")
			strUrl="/admin.php?do=adduser&action=checkuser&code=";
		xmlhttp.open("GET",strUrl+document.getElementById("txtcode").value,true);
		xmlhttp.send(null);
	    document.getElementById('username_notice').innerHTML = process_request;//显示状态
		xmlhttp.onreadystatechange=function(){
			if (4==xmlhttp.readyState){
				if (200==xmlhttp.status){
				var responseText = xmlhttp.responseText;
				   if (responseText=="true" ){
				   ck_user("true");
				      }
				   else{
				   ck_user("false");
				   }
				}else{
					alert("发生错误!");
				}
			}
		};
	}
	function chkUserName(obj,strType){
	     if (checkUser(obj.value)== false)
		  {
			obj.className = "FrameDivWarn";
			showInfo("username_notice",msg_un_format);
            change_submit("true");
		  }
		else if(obj.value.length<1){
			obj.className = "FrameDivWarn";
			showInfo("username_notice",msg_un_blank);
            change_submit("true");
		}

		else if(obj.value.length<2){
			obj.className = "FrameDivWarn";
			showInfo("username_notice",username_shorter);
            change_submit("true");
		}
		else if(obj.value.length>15)
		{
			obj.className= "FrameDivWarn";
			showinfo("username_notice",msg_un_format);
			change_submit("true");
		}
		else{
			//调用Ajax函数,向服务器端发送查询
			Ajax(obj.value,strType);
		}			

	}
//--------------用户名检测---------------------//
function ck_user(result)
{
  if ( result == "true" )
  {  
    document.getElementById('txtcode').className = "FrameDivWarn";
	showInfo("username_notice",msg_un_registered);
    change_submit("true");//禁用提交按钮
  }
  else
  { 
    document.getElementById('txtcode').className = "FrameDivPass";
	showInfo("username_notice",msg_can_rg);
    change_submit("false");//可用提交按钮
  }
}

function checkUser(t)
{
	szMsg="[#%&'\",;:=!^@]";
	var username=/^[a-zA-Z0-9][a-zA-Z0-9_]{4,15}$/;
	if(username.test(t)) {return true;} else {return false;}
	for(i=1;i<szMsg.length+1;i++){
	     if(t.indexOf(szMsg.substring(i-1,i))>-1){
	      //alertStr="请勿包含非法字符如[#_%&'\",;:=!^]";
	      return false;
	     }
	    }
	    return true;
}

function checks(t){
    szMsg="[#%&'\",;:=!^@]";
     //alertStr="";
    for(i=1;i<szMsg.length+1;i++){
     if(t.indexOf(szMsg.substring(i-1,i))>-1){
      //alertStr="请勿包含非法字符如[#_%&'\",;:=!^]";
      return false;
     }
    }
    return true;
   }
//-----------EMAIL检测--------------------------------//
function checkEmail(email)
{
 if (chekemail(email.value)==false)

  {
    email.className = "FrameDivWarn";
	showInfo("email_notice",msg_email_format);
	change_submit("true");  
 } 
 
else
   {
   showInfo("email_notice",info_right);
   email.className = "FrameDivPass";
   change_submit("false"); 
   }
}

function chekemail(temail) {  
 var pattern = /^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;  
 if(pattern.test(temail)) {  
  return true;  
 }  
 else {  
  return false;  
 }  
} 
//--------------------姓名检测-----------------------------//
function checktruename(truename)
{
	if(chktruename(truename.value)==false)
	{
		truename.className = "FrameDivWarn";
		showInfo("truename_notice",msg_truename_format);
	    change_submit("true"); 
	}
	else
	{
		showInfo("truename_notice",info_right);
		truename.className = "FrameDivPass";
		change_submit("false"); 
	}
}
function chktruename(truename)
{
  var msg=/^(\w|[\u4e00-\u9fa5]){2,10}$/;
  if(msg.test(truename)){return true;}
  else {return false;}
}
//--------------------城市名称检测-----------------------------//
function checkcityname(cityname)
{
   if(chkcityname(cityname.value)==false)
   {
	    cityname.className = "FrameDivWarn";
		showInfo("truename_notice",msg_cityname_format);
	    change_submit("true"); 
   }
   else{
	    showInfo("truename_notice",info_right);
	    cityname.className = "FrameDivPass";
		change_submit("false"); 
   }
}

//城市名只能输入汉字
function chkcityname(cityname)
{
   var msg=/^[\u4e00-\u9fa5]{2,4}$/;
   if(msg.test(cityname)){return true;}else {return false;}
}
//检测标题是否大于两个汉字，用户板块，城区的判断 yangjun
function chkname(name)
{
   var msg=/^\S{2,}$/;
   if(msg.test(name)){return true;}else {return false;}
}

//--------------------城市编码检测-----------------------------//
function checkcitycode(citycode)
{
   if(chkcitycode(citycode.value)==false)
   {
	    citycode.className = "FrameDivWarn";
		showInfo("citycode_notice",msg_citycode_format);
	    change_submit("true"); 
   }
   else
   {
	    showInfo("citycode_notice",info_right);
	    citycode.className = "FrameDivPass";
		change_submit("false"); 
   }
}

function chkcitycode(citycode)
{
   var msg=/^[0-9]{2,5}$/;   
   if(msg.test(citycode)){return true;}else {return false;}
}
//--------------------城市全拼检测-----------------------------//
function checkcityspell(cityspell)
{
	   if(chkcityspell(cityspell.value)==false)
	   {
		    cityspell.className = "FrameDivWarn";
			showInfo("cityspell_notice",msg_cityspell_format);
		    change_submit("true"); 
	   }
	   else
	   {
		    showInfo("cityspell_notice",info_right);
		    cityspell.className = "FrameDivPass";
			change_submit("false"); 
	   }
}

function chkcityspell(cityspell)
{
   var msg=/^[a-zA-Z]{4,20}$/;
   if(msg.test(cityspell)){return true;}else {return false;}
}


function checkcityabbr(cityabbr)
{
	
	   if(chkcityabbr(cityabbr.value)==false)
	   {
		    cityabbr.className = "FrameDivWarn";
			showInfo("cityabbr_s_notice",msg_cityabbr_format);
		    change_submit("true"); 
	   }
	   else
	   {
		    showInfo("cityabbr_s_notice",info_right);
		    cityabbr.className = "FrameDivPass";
			change_submit("false"); 
	   }

}

function chkcityabbr(cityabbr)
{
   var msg=/^[a-zA-Z]{2,10}$/;
   if(msg.test(cityabbr)){return true;}else {return false;}
}

function checkcityweight(cityweight)
{
	var weightVal = parseInt(cityweight.value);
	if(weightVal > 1000 || weightVal < 0 || cityweight.value == '')
	{
		errMsg = "权重值不在0-1000范围内!";
		showInfo("weight_notice",errMsg);
		return false;
	}
	var msg = /^([0-9]|[1-9][0-9]+)$/;
	if(msg.test(cityweight.value))
	{
		errMsg = "<span style=COLOR:#006600> √ 填写正确!</span>";
		showInfo("weight_notice",errMsg);
		return true;
	}
	else 
	{
		errMsg = "权重输入不合法，请输入数字!";
		showInfo("weight_notice",errMsg);
		return false;
	}
	
}



//--------------------手机检测-----------------------------//
function checkmobile(mobile)
{
   if(chekmobile(mobile.value)==false)
   {
	   mobile.className = "FrameDivWarn";
	   showInfo("mobile_notice",msg_mobile_format);
	   change_submit("true");  
   }
   else
   {
	   showInfo("mobile_notice",info_right);
	   mobile.className = "FrameDivPass";
	   change_submit("false"); 
   }
}

function chekmobile(mobile)
{
   var msg= /^1[3|4|5|7|8]\d{9}$/;
   if(msg.test(mobile)){return true;}
   else{return false;}
}

function checkmobileortel(mobile)
{
	if(chkmobileortel(mobile.value)==false)
   {
	   mobile.className = "FrameDivWarn";
	   showInfo("mobile_notice",msg_mobile_tel_format);
	   change_submit("true");  
   }
   else
   {
	   showInfo("mobile_notice",info_right);
	   mobile.className = "FrameDivPass";
	   change_submit("false"); 
   }
}
/**
 * 只验证为'数字'或'_'
 * @param mobile
 * @returns {Boolean}
 */
function chkmobileortel(mobile)
{
   var msg=/^[0-9-]{1,15}$/;
   if(msg.test(mobile)){return true;}
   else{return false;}
}
//--------------------邮编检测-----------------------------//
function checkpostcode(postcode)
{
	if(chkpostcode(postcode.value)==false)
	   {
		   postcode.className = "FrameDivWarn";
		   showInfo("postcode_notice",msg_postcode_format);
		   change_submit("true");  
	   }
	   else
	   {
		   showInfo("postcode_notice",info_right);
		   postcode.className = "FrameDivPass";
		   change_submit("false"); 
	   } 	
}

function chkpostcode(postcode)
{
   var msg=/^[0-9]{6}$/; 
   if(msg.test(postcode)){return true;}
   else{return false;}
}
//---------------------检查是否为数字-----------------------------//
function check_sale( max_unit_sale )
{
	   if(chk_sale(max_unit_sale.value)==false)
	   {
		    max_unit_sale.className = "FrameDivWarn";
			showInfo("sale_notice",msg_max_unit_sale_format);
		    change_submit("true"); 
	   }
	   else
	   {
		    showInfo("sale_notice",info_right);
		    citycode.className = "FrameDivPass";
			change_submit("false"); 
	   }	
	
}


function chk_sale(num)
{
   var msg=/^\d{1,5}$/;
   if(msg.test(num)){return true;}else {return false;}
}


//--------------------密码检测-----------------------------//
function check_password( password )
{
    if ( password.value.length < 6 )
    {
		showInfo("password_notice",password_shorter_s);
		password.className = "FrameDivWarn";
		change_submit("true");//禁用提交按钮
    }
	else if(password.value.length > 30){
		showInfo("password_notice",password_shorter_m);
		password.className = "FrameDivWarn";
		change_submit("true");//禁用提交按钮
		}
    else
    {
		showInfo("password_notice",info_right);
		password.className = "FrameDivPass";
		change_submit("false");//允许提交按钮
    }
}

function check_conform_password( conform_password )
{
    password = document.getElementById('txtpasswd').value;
    
    if ( conform_password.value.length < 6 )
    {
		showInfo("conform_password_notice",password_shorter_s);
		conform_password.className = "FrameDivWarn";
		change_submit("true");//禁用提交按
        return false;
    }
    if ( conform_password.value!= password)
    {
		showInfo("conform_password_notice",confirm_password_invalid);
		conform_password.className = "FrameDivWarn";
		change_submit("true");//禁用提交按
    }
    else
    {   
	    conform_password.className = "FrameDivPass";
		showInfo("conform_password_notice",info_right);
		change_submit("false");//允许提交按钮
    }
}
//* *--------------------检测密码强度-----------------------------* *//

function checkIntensity(pwd)
{
  var Mcolor = "#FFF",Lcolor = "#FFF",Hcolor = "#FFF";
  var m=0;

  var Modes = 0;
  for (i=0; i<pwd.length; i++)
  {
    var charType = 0;
    var t = pwd.charCodeAt(i);
    if (t>=48 && t <=57)
    {
      charType = 1;
    }
    else if (t>=65 && t <=90)
    {
      charType = 2;
    }
    else if (t>=97 && t <=122)
      charType = 4;
    else
      charType = 4;
    Modes |= charType;
  }

  for (i=0;i<4;i++)
  {
    if (Modes & 1) m++;
      Modes>>>=1;
  }

  if (pwd.length<=4)
  {
    m = 1;
  }

  switch(m)
  {
    case 1 :
      Lcolor = "2px solid red";
      Mcolor = Hcolor = "2px solid #DADADA";
    break;
    case 2 :
      Mcolor = "2px solid #f90";
      Lcolor = Hcolor = "2px solid #DADADA";
    break;
    case 3 :
      Hcolor = "2px solid #3c0";
      Lcolor = Mcolor = "2px solid #DADADA";
    break;
    case 4 :
      Hcolor = "2px solid #3c0";
      Lcolor = Mcolor = "2px solid #DADADA";
    break;
    default :
      Hcolor = Mcolor = Lcolor = "";
    break;
  }
  document.getElementById("pwd_lower").style.borderBottom  = Lcolor;
  document.getElementById("pwd_middle").style.borderBottom = Mcolor;
  document.getElementById("pwd_high").style.borderBottom   = Hcolor;

}
//--------------注册协议复选框状态检测---------------------//
function check_agreement(){
  if (document.formUser.agreement.checked==false)
  {
	 showInfo("agreement_notice",agreement);
     change_submit("true");//允许提交
}
  else
  {
	showInfo("agreement_notice",info_right);
	change_submit("false");//允许提交按
	}
}


//-------------处理注册程序-----------------------------//
function register() {
if(document.formUser.username.value=="")
	{
	showclass("username","FrameDivWarn");
	showInfo("username_notice",msg_un_blank);
	  document.formUser.username.focus();
	  return false;
	 }
 /*
  else if(document.formUser.email.value=="")
	{
	  showclass("email","FrameDivWarn");
	  showInfo("email_notice",msg_email_blank);
	  document.formUser.email.focus();
	  return false;
	 }	

 else if(document.formUser.password.value=="")
	{
	showclass("password","FrameDivWarn");
	showInfo("password_notice",password_empty);
      document.formUser.password.focus();
	  return false;
	 }
 else if(document.formUser.confirm_password.value=="")
	{
	showclass("confirm_password","FrameDivWarn");
	showInfo("conform_password_notice",confirm_password_invalid);
      document.formUser.password.focus();
	  return false;
	 }
 else if(document.formUser.agreement.checked==false)
	{
	//showclass("agreement","FrameDivWarn");
	showInfo("agreement_notice",agreement);
      document.formUser.agreement.focus();
	  return false;
	 }*/
}

//------------ 按钮状态设置-----------------------------//
function change_submit(zt)
{
     if (zt == "true"){
    	 document.forms['formUser'].elements['Submit1'].disabled = 'disabled';
     }else {
    	 document.forms['formUser'].elements['Submit1'].disabled = '';
     }
}
//------公用程序------------------------------------//
	function showInfo(target,Infos){
    document.getElementById(target).innerHTML = Infos;
	}
	 
	function showclass(target,Infos){
    document.getElementById(target).className = Infos;
	}	
	
	function getByIdValue(target){
		return document.getElementById(target).value;
	}
	
	 function showInfo2(target,Infos){
   		$("#"+target).html(Infos);
	}
	 
	function showclass2(target,Infos){
   		$("#"+target).addClass(Infos);
	}
	function moveclass2(target,Infos){
   		$("#"+target).removeClass(Infos);
	}
	//个人中心 改版 页面布局不同写了相应的方法 added by yanfang 2013-2-5
	function showHtml(target,Infos){
		if(Infos){
			$("#"+target).html('<span class="cureCen">'+Infos+"</span>");
		}else{
			$("#"+target).html("");
		}
	}	
	
	
//-----------------以下为门店--经纪人模块检测---------------------//
//--------------------2~4个汉字的检测-----------------------------//
function checkChinese(str)
{
    var flag;
   if(str.value == '' || Utils.isChinese(str.value) == false || str.value.length > 6 || str.value.length < 2)
   {
	    str.className = "FrameDivWarn";
		showInfo("broker_name_notice",msg_truname_format);
	    change_submit("true");
	    flag = false;
   }
   else
   {
	    showInfo("broker_name_notice",info_right);
	   // str.className = "Submit1";
		change_submit("false");
       flag = true;
   }	
   return flag;
}
//----------------------3~12个字母或者数字的检测-----------------------------//
function checkAccname(str){
    var flag;
	 var msg=/^[a-zA-Z0-9_]{5,15}$/;
      if(msg.test(str.value)) {
          flag = getAccname(str.value);
	  }else {
	      str.className = "FrameDivWarn";
		 // showInfo("broker_accname_notice",msg_indie_borker_accname_tishi);
	      showInfo("broker_accname_notice","账号长度为5-15位(仅限英文字母、数字、下划线)");
		  change_submit("true");
          flag = false;
	  }
    return flag;
}

function getAccname(str)
{
    var flag;
	$.ajax({
		type: "GET",
		url: base_url+"ajax/getAccname",
		data: "accname=" + str,
		dataType: "json",
        async:false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == null){
				 str.className = "FrameDivWarn";
				 showInfo("broker_accname_notice",info_right);
	             change_submit("false");
                flag = true;
			}else{
				str.className = "FrameDivPass";
				showInfo("broker_accname_notice",msg_un_registered);
				change_submit("true");
                flag = false;
			}
		}
	  });
    return flag;
}

//crm经纪人转移 检测门店
function checkagent_name(str)
{
	$.ajax({
		type: "POST",
		url: "ajax/get_agent_name.json.php",
		data: "agent_accname=" + str+"&city_id="+$("#city_id").val(),
		dataType: "json",
		error: function(XMLResponse){
			alert(XMLResponse.responseText);
		},
		success: function(data){
			//alert(data)
			if(data == null || data == "" ){
			
				showInfo("agentnameshow","门店账号错误！");
				change_submit("true"); 
			}else{
				 $.each(data, function(i, n){ 
					//获取转移经纪人公司id 和  门店公司id 做比较
						if(i=="company_id"){
							if($("#company_id_zhuanyi").attr("value")!=n){
								showInfo("agentnameshow","不能跨公司转移");
								change_submit("true"); 
								return false;
							}
						}						
					  //获取门面id
						if(i=="agent_id"){
							 $("#agent_id").val(n);
						}
						//获取门面名称
						if(i=="agent_name"){
							 showInfo("agentnameshow",n);
						}
					
//					 //获取转移经纪人公司id 和  门店公司id 做比较
//					if(!isNaN(n)){
//						if($("#company_id_zhuanyi").attr("value")!=n){
//							showInfo("agentnameshow","不能跨公司转移");
//							change_submit("true"); 
//							return false;
//						}
//					}
//					
//				  //获取门面id
//					if(!isNaN(i)){
//						 $("#agent_id").val(i);
//					}
//					//获取门面名称
//					if(isNaN(n)){
//						 showInfo("agentnameshow",n);
//					}
					 change_submit("false"); 
				});
				
			}
		}
	  });
}


//----------------------经纪人手机号检测-----------------------------//
function checkBrokerTel(str,id)
{
    var flag;
	 var msg=/^[1][3|4|5|7|8]\d{9}$/;
      if(msg.test(str.value)) {
		  //去掉手机号唯一性验证
		  flag = getBrokerTel(str.value,id);
	  }
	  else 
      {
	      str.className = "FrameDivWarn";
		  showInfo("broker_tel_notice",msg_broker_tel_format);
		  change_submit("true"); 
		  flag = false;
	  }
    return flag;
}

function getBrokerTel(str,id)
{
    var flag;
	$.ajax({
		async:false,
		cache: false,
		type: "GET",
		url: base_url+"ajax/getRealtorMobile",
		data: "broker_tel=" + str+"&broker_id=" + id+"&r="+Math.random(),
		dataType: "json",
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			//alert(data);
			if(data == null){
				 str.className = "FrameDivWarn";
				 showInfo("broker_tel_notice",info_right);
	             change_submit("false");
                flag = true;
				 
			}else{
				str.className = "FrameDivPass";
				showInfo("broker_tel_notice",msg_tel_registered);
				change_submit("true");
                flag = false;
			}
		}
	  });
    return flag;
}
//----------------------6~18个字母或者数字的检测-----------------------------//
function checkPwd(str)
{
    var flag;
	 var msg=/^[a-zA-Z0-9]{6,18}$/;
      if(msg.test(str.value))
	  {
		  str.className = "Submit1";
		  showInfo("textpasswd_notice",info_right);
	      change_submit("false");
          flag = true;
	  }
	  else 
      {
	      str.className = "FrameDivWarn";
		  showInfo("textpasswd_notice",msg_pwd_format);
		  change_submit("true");
          flag = false;
	  }
        return flag;
}
//--------------确认密码验证--------------------------------------//
function checkConfirm(pwd)
{
	//上次输入的密码
    var flag;
	var textpwd = document.getElementById('txtpasswd').value;
	var msg=/^[a-zA-Z0-9]{6,18}$/;
	if(pwd.value == '' || pwd.length < 6 || pwd.length > 18 || msg.test(pwd.value) === false || pwd.value != textpwd)
	{
		 pwd.className = "FrameDivPass";
		 showInfo("confirm_pwd_notice",confirm_password_invalid);
		 change_submit("true");
        flag = false;
	}
	else
	{
		 pwd.className = "Submit1";
		 showInfo("confirm_pwd_notice",info_right);
		 change_submit("false");
        flag = true;
	}
    return flag;
}

//--------------------------经人房源发布表单js验证statr------出租和出售 公用-----------------------------



function gethousename(str)
{
	// alert(encodeURIComponent(str));
	$.ajax({
		type: "GET",
		url: "ajax/get_housename.json.php",
		data: "housename="+encodeURIComponent(str),
		dataType: "json",
		error: function(XMLResponse){
			alert(XMLResponse.responseText);
		},
		
		success: function(data){
			//alert(data);
			if(data == null){  
				showclass("notice_housenametext","bor_red");
				showclass("notice_housename","notice_warn");
				showInfo("notice_housename",mag_broker_housenameError);
	             change_submit("true"); 
			}else{	
				
				showclass("notice_housenametext","bor_gra");
				showclass("notice_housename","notice_right");
				showInfo("notice_housename",mag_broker);
				change_submit("false"); 
				$(data).each(function(i, n){
					//alert()
					 $("#dict_house_id").val(n.house_id);
					 $("#house_name").val(n.house_name);
					 $("#build_year").val(n.build_year);
					 $("#lately_opt").fadeOut();
					 //获取版块 
					 get_hot_area(n.district_id, 'hot_area_id')
					// 初始化版块
					 $("#district_id").val(n.district_id);//初始化城区
					 //延时执行函数
					 setTimeout(voidliu,"200"); 
					 function voidliu(){
						 $("#hot_area_id").val(n.hot_area_id)
					 }
				});
				gethximg();//调用小区对应居室的户型图
			}
		}
	  });
}
//门店验证
function getagentname(str){
 
	$.ajax({
		type: "GET",
		url: base_url+"ajax/getShopCheck",
		data: "agent_name="+encodeURIComponent(str),
		dataType: "json",
		success: function(data){
			if(data == null){  
//				showclass("notice_housenametext","bor_red");
//				showclass("notice_housename","notice_warn");
				$("#agent_id").attr('value','');
     			showInfo("agentnotice",'× 门店名称错误，请输入正确门店!');
	             change_submit("true"); 
			}else{	
//				showclass("notice_housenametext","bor_gra");
				showclass("agentnotice","notice_right");
				showInfo("agentnotice","<span style=COLOR:#006600> √ 填写正确!</span>"); 
				$(data).each(function(i, n){
					 $("#agent_id").val(n.id);
					 $("#agent_name").val(n.name);
				});
				change_submit("false");
			}
		}
	  });
}
//门店验证
function checkagentname(str){
//		showclass("notice_housenametext","bor_blu");
//		showclass("notice_housename","notice_hover");
		showInfo("agentnotice","请输入门店名称！");
		str.onblur  = function (){
		   if(str.value == ''){ 
//				showclass("notice_housenametext","bor_red");
//				showclass("notice_housename","notice_warn");
				showInfo("agentnotice","× 门店名称错误，请输入正确门店!");
				change_submit("true"); 
		   }
		   else {
			   getagentname(str.value);
		  }
		}
}

//检测个人发布房源是否重复手机号
function checkpertel(str){
	 
		showclass("notice_teltext","bor_blu");
		showclass("notice_tel","notice_hover cue_phone_place");
		showInfo("notice_tel",msg_tel_registeredper_me);
		str.onblur  = function (){	
		   if(Utils.isMobile(str.value)==false || str.value==""){ 
				showclass("notice_teltext","bor_red");
				showclass("notice_tel","notice_warn cue_phone_place");
				//alert(str.value)
				if(str.value==""){
					showInfo("notice_tel","<p><b></b> × 手机号不能为空，请重新输入!</p>");
					//showInfo("notice_tel","1");
					
				}else{
					//alert()
					showInfo("notice_tel","<p><b></b>您输入的手机号码格式有误</p>");
					//showInfo("notice_tel","2");
					
				}
				change_submit("true"); 
		   }
		   else {
			    showclass("notice_teltext","bor_gra");
				showclass("notice_tel","notice_right cue_phone_place ");
				showInfo("notice_tel",mag_broker);
				change_submit("false"); 
			   //getPerAccname(str.value);
		  }
		}
}

///检测个人发布房源是否重复手机号
function getPerAccname(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/get_personal.json.php",
		data: "Personaname=" + str,
		dataType: "json",
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == null){
				showclass("notice_teltext","bor_gra");
				showclass("notice_tel","notice_right");
				showInfo("notice_tel",mag_broker);
				change_submit("false"); 
			}else{
				showclass("notice_teltext","bor_red");
				showclass("notice_tel","notice_warn");
				showInfo("notice_tel",msg_tel_registeredper);
	            change_submit("true"); 	
				
			}
		}
	  });
}
//小区名称验证个人/经纪人
function checkname(str){
		showclass("notice_housenametext","bor_blu");
		showclass("notice_housename","notice_hover");
		showInfo("notice_housename",mag_broker_housename);
		str.onblur  = function (){
		   if(str.value == ''){ 
				showclass("notice_housenametext","bor_red");
				showclass("notice_housename","notice_warn");
				showInfo("notice_housename",mag_broker_housenameError);
				change_submit("true"); 
		   }
		   else {
			    gethousename(str.value);
		  }
		}
}
function checkForbiddenSymbol(str)
{
	var reg = /^([a-zA-Z])|([0-9])|([　])|([\u4E00-\u9FA5]+)|([(]+)|([)]+)|([\x20])|([、])|([/])|([——])|([-])|([·])|([,])|([，])|([.])|([（])|([）])|([&])|([+])|([#])|([;])|([；])|([。])|([!])|([！])$/;
	var reg1 = /^$/;
	//var str=document.getElementById(element_id).value;
	var _sivalue=false; 
	for(var i =0; i<str.length;i++){ 
		var _iKeyCode =str.substring(i,i+1); 
		
		if(!reg.test(_iKeyCode)){ 
			_sivalue=true; 
		} 
	} 
	if(_sivalue){ 
		return false; 
	}
	return true;
}

//标题验证
function checkbrokername(str){
	showclass("notice_titletext","bor_blu");
	showclass("notice_title","notice_hover");
	showInfo("notice_title",mag_broker_title);
	
	str.onblur  = function (){
		if(str.value == '' || str.value.length > 30 || str.value.length < 4 || !checkForbiddenSymbol(str.value)){
		//if(str.value == '' || str.value.length > 30 || str.value.length < 4 ){
			showclass("notice_titletext","bor_red");
			showclass("notice_title","notice_warn");
			showInfo("notice_title",mag_broker_titleError);
		    change_submit("true"); 
	   }
	   else {
		   	showclass("notice_titletext","bor_gra");
			showclass("notice_title","notice_right");
			showInfo("notice_title",mag_broker);
			change_submit("false"); 
	   }		
	}
}
//内部编号验证
//3~36个字母或者数字的检测
function checkbrokercustom(str)
{
	showclass("notice_customtext","bor_blu");
	showclass("notice_custom","notice_hover");
	showInfo("notice_custom",mag_broker_custom_bh);
	str.onblur  = function (){
	  var msg=/^[\S\s]{3,36}$/;
      if(!msg.test(str.value) && str.value !=""){
    	  showclass("notice_customtext","bor_red");
		  showclass("notice_custom","notice_warn");
		  showInfo("notice_custom",mag_broker_bh_customError);
		  change_submit("true"); 
	  }
	  else  {
		showclass("notice_customtext","bor_gra");
		showclass("notice_custom","notice_right");
		 if(str.value !=""){
			 showInfo("notice_custom",mag_broker); 
		 }else{
			 showInfo("notice_custom",""); 
		 }
		
		change_submit("false"); 
	  }
     }
}

//认证编码验证
//3~12个字母或者数字的检测
function checkbrokercustom_code(str)
{
	showclass("notice_custom_codetext","bor_blu");
	showclass("notice_custom_code","notice_hover");
	showInfo("notice_custom_code",mag_broker_custom);
	str.onblur  = function (){
	  var msg=/^[a-zA-Z0-9]{3,12}$/;
    if(!msg.test(str.value) && str.value !=""){
  	  showclass("notice_custom_codetext","bor_red");
		  showclass("notice_custom_code","notice_warn");
		  showInfo("notice_custom_code",mag_broker_customError);
		  change_submit("true"); 
	  }
	  else  {
		showclass("notice_custom_codetext","bor_gra");
		showclass("notice_custom_code","notice_right");
		 if(str.value !=""){
			 showInfo("notice_custom_code",mag_broker); 
		 }else{
			 showInfo("notice_custom_code",""); 
		 }
		
		change_submit("false"); 
	  }
   }
}
//建筑面积 2-10000之间
function IsNumeric_4(str){
	showclass("notice_areatext","bor_blu");
	showclass("notice_area","notice_hover area_place");
	showInfo("notice_area",mag_broker_area);
	str.onblur  = function (){
		
		if( /^[1-9]{1}\d*(\.\d{1,2})?$/.test(str.value) == false || str.value < 2 || str.value > 10000 ){
			showclass("notice_areatext","bor_red");
			showclass("notice_area","notice_warn area_place");
			showInfo("notice_area",mag_broker_areaError);
		    change_submit("true"); 
		}else{
			showclass("notice_areatext","bor_gra");
			showclass("notice_area","notice_right area_place");
			showInfo("notice_area",mag_broker);
			change_submit("false"); 
		}	
	}
}

//出售面积 2-20000之间
function IsNumeric_sale(str){
	showclass("notice_areatext","bor_blu");
	showclass("notice_area","notice_hover area_place");
	showInfo("notice_area",mag_broker_area_sale);
	str.onblur  = function (){
		
		if( /^[1-9]{1}\d*(\.\d{1,2})?$/.test(str.value) == false || str.value < 2 || str.value > 20000 ){
			showclass("notice_areatext","bor_red");
			showclass("notice_area","notice_warn area_place");
			showInfo("notice_area",mag_broker_areaError);
		    change_submit("true"); 
		}else{
			showclass("notice_areatext","bor_gra");
			showclass("notice_area","notice_right area_place");
			showInfo("notice_area",mag_broker);
			change_submit("false"); 
		}	
	}
}

//金钱
//2-100000之间；售价
function IsNumeric_42(str){
	showclass("notice_pricetext","bor_blu");
	showclass("notice_price","notice_hover rent_place");
	showInfo("notice_price",mag_broker_price);
	str.onblur  = function (){
		if( /^[1-9]{1}\d*(\.\d{1,2})?$/.test(str.value) == false || str.value < 2 || str.value >100000  ){
			showclass("notice_pricetext","bor_red");
			showclass("notice_price","notice_warn rent_place");
			showInfo("notice_price",mag_broker_priceError);
		    change_submit("true"); 
		}else{
			showclass("notice_pricetext","bor_gra");
			showclass("notice_price","notice_right rent_place" );
			showInfo("notice_price",mag_broker);
			change_submit("false"); 
		}	
	}
}
//租金
function IsNumeric_43(str){
	showclass("notice_pricetext","bor_blu");
	showclass("notice_price","notice_hover rent_place");
	//租金按月算或者按天算 1：月 2：天
	if($("#danwei").val()==1){
		showInfo("notice_price",mag_broker_price_rent);
	}else if($("#danwei").val()==2){
		showInfo("notice_price",mag_broker_price_rent_100);
	}
	
	str.onblur  = function (){
		if($("#danwei").val()==1){
			if( /^[1-9]{1}\d*(\.\d{1,2})?$/.test(str.value) == false || str.value < 100 || str.value >1000000  ){
				showclass("notice_pricetext","bor_red");
				showclass("notice_price","notice_warn rent_place");
				showInfo("notice_price",mag_broker_priceError_rent_price);
			    change_submit("true"); 
			}else{
				showclass("notice_pricetext","bor_gra");
				showclass("notice_price","notice_right rent_place" );
				showInfo("notice_price",mag_broker);
				change_submit("false"); 
			}	
		}else if($("#danwei").val()==2){
			if( /^[1-9]{1}\d*(\.\d{1,2})?$/.test(str.value) == false || str.value < 1 || str.value >100  ){
				showclass("notice_pricetext","bor_red");
				showclass("notice_price","notice_warn rent_place");
				showInfo("notice_price",mag_broker_priceError_rent_price);
			    change_submit("true"); 
			}else{
				showclass("notice_pricetext","bor_gra");
				showclass("notice_price","notice_right rent_place" );
				showInfo("notice_price",mag_broker);
				change_submit("false"); 
			}	
		}
		
	}
}

//租金&&单位
function IsNumeric_danwei(str){
		if($("#danwei").val()==1){
			if( Utils.isDigit(str.value) == false || str.value < 100 || str.value >1000000  ){
				showclass("notice_pricetext","bor_red");
				showclass("notice_price","notice_warn rent_place");
				showInfo("notice_price",mag_broker_priceError_rent_price);
			    change_submit("true"); 
			}else{
				showclass("notice_pricetext","bor_gra");
				showclass("notice_price","notice_right rent_place" );
				showInfo("notice_price",mag_broker);
				change_submit("false"); 
			}	
		}else if($("#danwei").val()==2){
			if( Utils.isDigit(str.value) == false || str.value < 1 || str.value >100  ){
				showclass("notice_pricetext","bor_red");
				showclass("notice_price","notice_warn rent_place");
				showInfo("notice_price",mag_broker_priceError_rent_price);
			    change_submit("true"); 
			}else{
				showclass("notice_pricetext","bor_gra");
				showclass("notice_price","notice_right rent_place" );
				showInfo("notice_price",mag_broker);
				change_submit("false"); 
			}	
		}
}

//建筑年代
function cbuildyear(str){
	
	showclass("notice_yeartext","bor_blu");
	showclass("notice_year","notice_hover");
	showInfo("notice_year",mag_broker_year);
	str.onblur  = function (){
		var NewDate = new Date();
		if( Utils.isFloat(str.value) == false || str.value.length !=4   ||   str.value > NewDate.getFullYear()){
			showclass("notice_yeartext","bor_red");
			showclass("notice_year","notice_warn");
			showInfo("notice_year",mag_broker_yearError);
		    change_submit("true"); 
		}else{
			showclass("notice_yeartext","bor_gra");
			showclass("notice_year","notice_right");
			showInfo("notice_year",mag_broker);
			change_submit("false"); 
		}
	}
}

//验证楼层
function floorc(str){
		if( getByIdValue("floor")=="" || getByIdValue("floor_max")=="" ){
			//alert()
			showclass("notice_floor","notice_warn");
			showInfo("notice_floor",mag_broker_floorcError1); 
			 change_submit("true"); 
	    }else{
	    	if(parseInt(getByIdValue("floor")) > parseInt(getByIdValue("floor_max")) || Utils.isDigit(getByIdValue("floor_max"))==false ||  Utils.isInteger(getByIdValue("floor"))==false || getByIdValue("floor_max")==0 || getByIdValue("floor") == 0 ){
	    		showclass("notice_floor","notice_warn");
				showInfo("notice_floor",mag_broker_floorcError); 
				 change_submit("true"); 
		    }else{
				showclass("notice_floor","notice_right");
				showInfo("notice_floor",mag_broker);
				change_submit("false"); 
		    }
	    }	
}
//
//出售验证
//	function sale_check(){
//		//if(bottomcheck()==false){return false;}
//		if($("#title").val()==""){alert("标题不能为空!");return false;}
//		if($("#custom_id").val()==""){alert("房源编号不能为空!");return false;}
//		if($("#house_name").val()==""){alert("小区名称不能为空!");return false;}
//		if($("#district_id").val()=="0"){alert("请选择城区!");return false;}
//		if($("#hot_area_id").val()=="0"){alert("请选择版块!");return false;}
//		if($("#bedroom").val()=="0"){ alert("请选择居室!"); return false; }
//		if($("#living_room").val()=="0"){alert("请选择厅!");return false;}
//		if($("#bathroom").val()=="0"){alert("请选择卫  !");return false;}
//		if($("#exposure").val()=="0"){alert("请选择朝向  !");return false;}
//		if($("#area").val()==""){alert("请填写面积!");return false;}
//		if($("#price").val()==""){alert("请填写售价!");return false;}
//		if($("#floor").val()==""){alert("请填写楼层!");return false;}
//		if($("#build_year").val()==""){alert("请填写建筑年代!");return false;}
//		if($("sale_description").val()==""){alert("请填写房源描述!");return false;}
//	} 

////出租公共验证
//function rent_check(){
//	if(bottomcheck()==false){return false;}
//	if(getByIdValue("rent_price")==""){alert("请填写租金!");return false;}
//	if(getByIdValue("rent_price_type")=="0"){alert("请选择支付类型!");return false;}
//} 
////出租出售公共验证
//function bottomcheck(){
//	if(getByIdValue("title")==""){alert("标题不能为空!");return false;}
//	if(getByIdValue("custom_id")==""){alert("房源编号不能为空!");return false;}
//	if(getByIdValue("house_name")==""){alert("小区名称不能为空!");return false;}
//	if(getByIdValue("district_id")=="0"){alert("请选择城区!");return false;}
//	if(getByIdValue("hot_area_id")=="0"){alert("请选择版块!");return false;}
//	if(getByIdValue("bedroom")=="0" || getByIdValue("bedroom")==""){
//		alert("请选择居室!");
//		return false;
//	}
//	if(getByIdValue("living_room")=="0"){alert("请选择厅!");return false;}
//	if(getByIdValue("bathroom")=="0"){alert("请选择卫  !");return false;}
//	if(getByIdValue("exposure")=="0"){alert("请选择朝向  !");return false;}
//	if(getByIdValue("area")==""){alert("请填写面积!");return false;}
//}
//
////个人房源发布验证
//function personalcheck(){
//	if(getByIdValue("title")==""){alert("标题不能为空!");return false;}
//	if(getByIdValue("house_name")==""){alert("小区名称不能为空!");return false;}
//	if(getByIdValue("district_id")=="0"){alert("请选择城区!");return false;}
//	if(getByIdValue("hot_area_id")=="0"){alert("请选择版块!");return false;}
//	if(getByIdValue("bedroom")=="0"){alert("请选择居室!");return false;}
//	if(getByIdValue("living_room")=="0"){alert("请选择厅!");return false;}	
//	if(getByIdValue("bathroom")=="0"){alert("请选择居卫!");return false;}	
//	if(getByIdValue("exposure")=="0"){alert("请选择朝向!");return false;}
//	if(getByIdValue("area")==""){alert("请填写面积!");return false;}
//	if(getByIdValue("rent_price")==""){alert("请填写租金!");return false;}
//	if(getByIdValue("rent_price_type")=="0"){alert("请选择交付类型!");return false;}	
//	if(getByIdValue("tel")==""){alert("请填写手机号!");return false;}
//	if(getByIdValue("contact_name")==""){alert("请填写姓名!");return false;}	
//	if(document.getElementById("agree").checked==false){alert("请您阅读并同意《用户协议》!");return false;}	
//}


function saletype(str,obj){
	if(str=="1"){
		document.getElementById(obj).style.display = "";
	}else{
		document.getElementById(obj).style.display ="none";
	}
	if(str=="2" || str=="4" || str=="7" || str=="8"){
		document.getElementById('louceng').style.display = 'none';
	}else{
		document.getElementById('louceng').style.display ="";
	}
	if(str=="0"){
		showclass("notice_property","notice_warn");
		showInfo("notice_property",msg_unit_property_type);
		change_submit("true"); 
	}else{
		showclass("notice_property","notice_right");
		showInfo("notice_property",mag_broker);
		change_submit("false"); 
	}
}

//获取设置标签值
function tagcome(theform,thename,obj){
	var tform=document.forms[theform];
	var values='';
	for(var i=0;i<tform.length;i++){
	   var e=tform.elements[i];
	   if(e.type=='checkbox'&&e.name==thename+"[]"){
		   if(e.checked){
			   if(values==""){
				   values=e.value;
			    }else{  values=values+','+e.value; }
			 
		   }   
		}
	}
	if(values==""){
		alert("请选择需要处理的房源");
		return false;
	}
	divPop("?do="+obj+"&action=tag&"+thename+"="+values);
}

//crm独立经纪人操作
function crmbrocome(theform,thename,url){
	var tform=document.forms[theform];
	
	var values='';
	for(var i=0;i<tform.length;i++){
	   var e=tform.elements[i];
	 
	   if(e.type=='checkbox' && e.name==thename+"[]"){
		
		   if(e.checked){
			
			   if(values==""){
				   
				   values=e.value;
			    }else{  values=values+','+e.value; }
			 
		   }   
		}
	}
//alert(values)
	if(values==""){
		alert("请选择需要处理的数据");
		return false;
	}
	//divPop("?do=independent&status=tag&"+thename+"="+values);
location.href="?do=independent&status="+url+"&id="+values;
}
/*--------------------------------独立经纪人注册---------------------------------------------*/
//经纪人账户验证
function checkBrokerAccname_d(str){ 
	  if(str.val()=='')
	  {
		  showclass("broker_accname_notice","notice_warn");
		  showclass("broker_accname_notice0","bor_red");
		  showInfo("broker_accname_notice",msg_indie_borker_accname_kong);
		  return false;
	  }
	  var msg=/^\w{5,15}$/;
      if(msg.test(str.val()))
	  {		 
		 getBrokerAccname_d(str.val()); 
	  }
	  else 
      {
		  showclass("broker_accname_notice0","bor_red");
		  showclass("broker_accname_notice","notice_warn");
		  showInfo("broker_accname_notice",msg_indie_borker_accname_guize);
		  return false;
	  }
}
function getBrokerAccname_d(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/get_enterprise_account.json.php?rnd="+Math.random()*5,
		data: "accname=" + str,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == null){
			     showclass("broker_accname_notice0","");
		  		 showclass("broker_accname_notice","notice_right");
				 showInfo("broker_accname_notice",mag_broker);
	             change_submit("false"); 
			}else{
				showclass("broker_accname_notice0","bor_red");
		  		showclass("broker_accname_notice","notice_warn");
				showInfo("broker_accname_notice",msg_indie_borker_accname_weiyi);
				change_submit("true"); 
			}
		}
	  });
}
//密码验证
function checkBrokerPassword_d(str){
	  if(str.val()=='')
	  {
		  showclass("textpasswd_notice0","bor_red");
		  showclass("textpasswd_notice","notice_warn");
		  showInfo("textpasswd_notice",msg_indie_borker_password_kong);
		  return false; 
	  }
	  //var msg = /[\w|!|@|#|$|%|^|&|*|(|)|_|+]{6,16}/g;
	 // var msg = /^[\w|!|@|#|$|%|^|&|*|(|)|_|+]{6,16}$/g;
	 //var msg = /[0-9A-Za-z_#$!@#$%^&\*\(\s)]{6,16}/;
	  var msg = /^\w{6,16}$/;
      if(msg.test(str.val()))
	  { 
		  showclass("textpasswd_notice0","");
		  showclass("textpasswd_notice","notice_right");
		  showInfo("textpasswd_notice",mag_broker);
	      change_submit("false"); 
	  }else{
		  showclass("textpasswd_notice0","bor_red");
		  showclass("textpasswd_notice","notice_warn");
		  showInfo("textpasswd_notice",msg_indie_borker_password_guize);
		  return false; 
	 }
}
//重复密码验证
function checkBrokerRepassword_d(str){
	 if(str.val()=='')
	  {
		  showclass("confirm_pwd_notice0","bor_red");
		  showclass("confirm_pwd_notice","notice_warn");
		  showInfo("confirm_pwd_notice",msg_indie_borker_password_kong);
		  return false; 
	  }
      if(str.val()!=$("#broker_password").val())
	  {
		  showclass("confirm_pwd_notice0","bor_red");
		  showclass("confirm_pwd_notice","notice_warn");
		  showInfo("confirm_pwd_notice",msg_indie_borker_repassword_guize);
		  return false; 
	  }else{
		  showclass("confirm_pwd_notice0","");
		  showclass("confirm_pwd_notice","notice_right");
		  showInfo("confirm_pwd_notice",mag_broker);
	      change_submit("false"); 
	 }
}
//姓名验证
function checkBrokerName_d(str){
	 if(str.val()=='')
	  {
		  showclass("broker_name_notice0","bor_red");
		  showclass("broker_name_notice","notice_warn");
		  showInfo("broker_name_notice",msg_indie_borker_name_kong);
		  return false; 
	  }
	  var msg=/^[\u4e00-\u9fa5]{2,6}$/;
      if(!msg.test(str.val()))
	  {
		  showclass("broker_name_notice0","bor_red");
		  showclass("broker_name_notice","notice_warn");
		  showInfo("broker_name_notice",msg_indie_borker_name_guize);
		  return false; 
	  }else{
		  showclass("broker_name_notice0","");
		  showclass("broker_name_notice","notice_right");
		  showInfo("broker_name_notice",mag_broker);
	      change_submit("false"); 
	 }
}
//城市验证
function checkCity_d(str){
	  if(str.val()==0)
	  {
		  showclass("city_id_notice","notice_warn");
		  showInfo("city_id_notice",msg_city_id_kong);
		  return false; 
		  change_submit("true"); 
	  }else{
		  showclass("city_id_notice","notice_right");
		  showInfo("city_id_notice",mag_broker);
		  change_submit("false"); 
	 }
}
//区域验证
function checkDistrict_d(str1,str2){
	  if(str1.val()==0 || str2.val()==0)
	  {
		  showclass("district_id_notice","notice_warn");
		  showInfo("district_id_notice",msg_district_id_kong);
		  return false;
		  change_submit("true"); 
	  }else{
		  showclass("district_id_notice","notice_right");
		  showInfo("district_id_notice",mag_broker);
		  change_submit("false"); 
	  }
}

//公司验证
function checkCompany_d(str)
  {
	  if(str.val()=='')
	  {
		  showclass("company_notice0","bor_red");
		  showclass("company_notice","notice_warn");
		  showInfo("company_notice",msg_company_kong);
		  return false; 
	  }else{
		 getCompany_d(str.val());
		 
	  }
}

function getCompany_d(str)
{
	$.ajax({
		type: "POST",
		url: base_url+"ajax/getCompanyExist",
		data: "company_name_abbr=" + encodeURIComponent(str) + "&city_id="+ $("#city_id").val(),
		dataType: "json",
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			 
			if(data != null){
				 $("#company_id").val(data);
				 showclass("company_notice0","");
		  		 showclass("company_notice","notice_right");
				 showInfo("company_notice",mag_broker);
	             change_submit("false"); 
			}else{
				showclass("company_notice0","bor_red");
		  		showclass("company_notice","notice_warn");;
				showInfo("company_notice",msg_company_guize);
				change_submit("true"); 
			}
		}
	  });
}
//门店验证
function checkAgent_d(str)
  {
	  if(str.val()=='')
	  {
		  showclass("shop_info_notice0","bor_red");
		  showclass("shop_info_notice","notice_warn");
		  showInfo("shop_info_notice",msg_agent_info_kong);
		  return false; 
	  }else{
		  showclass("shop_info_notice0","");
		  showclass("shop_info_notice","notice_right");
		  showInfo("shop_info_notice",mag_broker);
	      change_submit("false"); 
	 }
}


//手机号验证
function checkBrokerTel_d(str)
  {
	  if(str.val()=='')
	  {
		  showclass("realtor_tel_notice0","bor_red");
		  showclass("realtor_tel_notice","notice_warn");
		  showInfo("realtor_tel_notice",msg_indie_borker_tel_kong);
		  return false; 
	  }
	  var msg=/^[1][3|4|5|8]\d{9}$/;
      if(msg.test(str.val()))
	  {
		 getBrokerTel_d(str.val());
	  }
	  else 
      {
	      showclass("realtor_tel_notice0","bor_red");
		  showclass("realtor_tel_notice","notice_warn");
		  showInfo("realtor_tel_notice",msg_indie_borker_tel_guize);
		  return false; 
	  }
}

function getBrokerTel_d(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/get_broker_tel.json.php?rnd="+Math.random()*5,
		data: "broker_tel=" + str,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		
		success: function(data){
			if(data == null){
				 showclass("broker_tel_notice0","");
		  		 showclass("broker_tel_notice","notice_right");
				 showInfo("broker_tel_notice",mag_broker);
	             change_submit("false"); 
			}else{
				showclass("broker_tel_notice0","bor_red");
		  		showclass("broker_tel_notice","notice_warn");;
				showInfo("broker_tel_notice",msg_indie_borker_tel_weiyi);
				change_submit("true"); 
			}
		}
	  });
}

//邮箱验证
function checkBrokerMail_d(str)
  {
	  if(str.val()=='')
	  {
		  showclass("realtor_mail_notice0","bor_red");
		  showclass("realtor_mail_notice","notice_warn");
		  showInfo("realtor_mail_notice",msg_indie_borker_mail_kong);
		  return false; 
	  }
	  var msg=/^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
      if(msg.test(str.val()))
	  {
		 getBrokerMail_d(str.val());
	  }
	  else 
      {
	      showclass("realtor_mail_notice0","bor_red");
		  showclass("realtor_mail_notice","notice_warn");
		  showInfo("realtor_mail_notice",msg_indie_borker_mail_guize);
		  return false; 
	  }
}

function getBrokerMail_d(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/get_broker_mail.json.php?rnd="+Math.random()*5,
		data: "broker_mail=" + str,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			
			if(data == null){
				 showclass("broker_mail_notice0","");
		  		 showclass("broker_mail_notice","notice_right");
				 showInfo("broker_mail_notice",mag_broker);
	             change_submit("false"); 
			}else{
				showclass("broker_mail_notice0","bor_red");
		  		showclass("broker_mail_notice","notice_warn");;
				showInfo("broker_mail_notice",msg_indie_borker_mail_weiyi);
				change_submit("true"); 
			}
		}
	  });
}

/*//验证码
function checkBrokerYanzheng_d(str)
  {
	  if(str.val()=='')
	  {
		  showclass("yanzheng_notice0","bor_red");
		  showclass("yanzheng_notice","notice_warn");
		  showInfo("yanzheng_notice",msg_indie_yanzheng_kong);
		  return false; 
	  }
	  else if(str.val()!= $("#yanzheng2").val())
	 {
		  showclass("yanzheng_notice0","bor_red");
		  showclass("yanzheng_notice","notice_warn");
		  showInfo("yanzheng_notice",msg_indie_yanzheng_guize);
		  return false; 
	  }else{
		  showclass("yanzheng_notice","notice_right");
		  showInfo("yanzheng_notice",mag_broker);
	 }
}
*/
//是否阅读协议
/*function checkBrokerReadDeal(str){
	   alert(str+"fsdf");
	  if(str.checked == false){
		  showclass("xieyi_notice","notice_warn");
		  showInfo("xieyi_notice",msg_indie_xieyi_kong);
		  return false; 
	  }else{
		  showclass("xieyi_notice","notice_right");
		  showInfo("xieyi_notice",mag_broker);
		  change_submit("false"); 
	  }
}*/

function checkBrokerReadDeal(){
  if (document.formUser.xieyi.checked==false)
  {
	  showclass("xieyi_notice","notice_warn");
      showInfo("xieyi_notice",msg_indie_xieyi_kong);
      return false; 
}
  else
  {
	 showclass("xieyi_notice","notice_right");
		  showInfo("xieyi_notice",mag_broker);
		  change_submit("false"); 
	}
}
//------------------------------------------用户中心-公司资料修改 -begin----------------------------------//
//公司地址
function checkCompanyAddress(str){
	moveclass2("company_address_notice0","bor_blu");
	moveclass2("company_address_notice","notice_hover");
	if(str.val()!=""){	
		showclass2("company_address_notice","notice_right");			
		showInfo2("company_address_notice",mag_broker);
	}else{
		showInfo2("company_address_notice","");
	}
}
//公司电话
function checkCompanyTel(str){
	moveclass2("company_tel_notice","notice_hover");		
	moveclass2("company_tel_notice0","bor_blu");	
	moveclass2("company_tel_notice0","bor_red");	
	if(str.val()!=""){
		var msg=/^[0-9]{8}$/i;
      	if(msg.test(str.val()))
	  	{
			showclass2("company_tel_notice","notice_right");			
			showInfo2("company_tel_notice",mag_broker);
			return true;
		}else{
			showclass2("company_tel_notice0","bor_red");
			showclass2("company_tel_notice","notice_warn");			
			showInfo2("company_tel_notice",msg_company_tel_guize);
			return false;
		}
		
	}else{
		showInfo2("company_tel_notice","");
		return true;
	}
}
//公司传真
function checkCompanyFax(str){
	moveclass2("company_fax_notice0","bor_blu");
	moveclass2("company_fax_notice","notice_hover");
	moveclass2("company_fax_notice0","bor_red");	
	if(str.val()!=""){
		var msg=/^[0-9]{8}$/i;
      	if(msg.test(str.val()))
	  	{
			showclass2("company_fax_notice","notice_right");			
			showInfo2("company_fax_notice",mag_broker);
			return true;
		}else{
			showclass2("company_fax_notice0","bor_red");
			showclass2("company_fax_notice","notice_warn");			
			showInfo2("company_fax_notice",msg_company_fax_guize);
			return false;
		}
		
	}else{
		showInfo2("company_fax_notice","");
		return true;
	}
}
//公司简介
function checkCompanyInfo(str){
	moveclass2("company_describe_notice0","bor_blu");
	if(str.val()!=""){	
		showclass2("company_describe_notice","notice_right");			
		showInfo2("company_describe_notice",mag_broker);
	}else{
		showInfo2("company_describe_notice","");
	}
}
//------------------------------------------用户中心-公司资料修改 -end----------------------------------//
//------------------------------------------用户中心-门店资料修改 -begin----------------------------------//
//区域验证
function checkDistrict_d2(str1,str2){
	  if(str1.val()==0 || str2.val()==0)
	  {
		  moveclass2("district_id_notice","notice_right");
		  showclass2("district_id_notice","notice_warn");
		  showInfo2("district_id_notice",msg_district_id_kong);
		  return false;
	  }else{
	      moveclass2("district_id_notice","notice_warn");
		  showclass2("district_id_notice","notice_right");
		  showInfo2("district_id_notice",mag_broker);
		  return true;
	  }
}
//门店地址
function checkAgentAddress(str){
	moveclass2("agent_address_notice","notice_hover");
	moveclass2("agent_address_notice0","bor_blu");
	if(str.val()!=""){	
		showclass2("agent_address_notice","notice_right");			
		showInfo2("agent_address_notice",mag_broker);
		
	}else{
		showInfo2("agent_address_notice","");
	}
}

//门店传真
function checkAgentFax(str){
	moveclass2("agent_fax_notice0","bor_blu");	
	moveclass2("agent_fax_notice0","bor_red");
	moveclass2("agent_fax_notice","notice_hover");
	if(str.val()!=""){
		var msg=/^[0-9]{8}$/i;
      	if(msg.test(str.val()))
	  	{
			showclass2("agent_fax_notice","notice_right");			
			showInfo2("agent_fax_notice",mag_broker);
			change_submit("false"); 
			return true;
		}else{
			showclass2("agent_fax_notice0","bor_red");
			showclass2("agent_fax_notice","notice_warn");			
			showInfo2("agent_fax_notice",msg_agent_fax_guize);
			return false;
		}
		
	}else{
		showInfo2("agent_fax_notice","");
	}
}
//门店电话
function checkAgentTel(str){
	moveclass2("agent_tel_notice0","bor_blu");	
	moveclass2("agent_tel_notice0","bor_red");
	moveclass2("agent_tel_notice","notice_hover");
	if(str.val()!=""){
		var msg=/^[0-9]{8}$/i;
      	if(msg.test(str.val()))
	  	{
			showclass2("agent_tel_notice","notice_right");			
			showInfo2("agent_tel_notice",mag_broker);
			return true;
		}else{
			showclass2("agent_tel_notice0","bor_red");
			showclass2("agent_tel_notice","notice_warn");			
			showInfo2("agent_tel_notice",msg_agent_tel_guize);
			return false;
		}
		
	}else{
		showInfo2("agent_tel_notice","");
	}
}
//门店介绍
function checkAgentInfo(str){
	moveclass2("agent_info_notice0","bor_blu");
	if(str.val()!=""){	
		showclass2("agent_info_notice","notice_right");			
		showInfo2("agent_info_notice",mag_broker);
	}else{
		showInfo2("agent_info_notice","");
	}
}
//------------------------------------------用户中心-门店资料修改 -end----------------------------------//
//------------------------------------------用户中心-经纪人资料修改 -begin----------------------------------//
//------------------------企业经纪人
function checkBrokerTel_v(str){
	  moveclass2("realtor_tel_notice0","bor_blu");	
	  moveclass2("realtor_tel_notice0","bor_red");
	  moveclass2("realtor_tel_notice","notice_hover");
	  if(str.val()=='')
	  {
		  showclass2("realtor_tel_notice0","bor_red");
		  showclass2("realtor_tel_notice","notice_warn");
		  showInfo2("realtor_tel_notice",msg_broker_tel_kong_v);
		  return false; 
	  }
	  var msg=/^[1][3|4|5|7|8]\d{9}$/;
      if(msg.test(str.val()))
	  {
		  //去掉手机唯一性验证 5.30 程春杏
		 /*getBrokerTel_v(str.val());
		 if($("#a").val()==1){
			 return false;
     	 }else{
			 return true;
	     }*/
		 
		 showclass2("realtor_tel_notice0","");
		 showclass2("realtor_tel_notice","notice_right");
		 showInfo2("realtor_tel_notice",mag_broker);
		 return true; 
	  }
	  else 
      {
	      showclass2("broker_tel_notice0","bor_red");
		  showclass2("broker_tel_notice","notice_warn");
		  showInfo2("broker_tel_notice",msg_broker_tel_guize_v);
		  return false; 
	  }
}


function getBrokerTel_v(str)
{
	var broker_id = $("#broker_id").val();
	$.ajax({
		type: "GET",
		url: "ajax/get_broker_tel.json.php?rnd="+Math.random()*5,
		data: "broker_tel=" + str + "&broker_id=" + broker_id,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == null){
				 showclass2("broker_tel_notice0","");
		  		 showclass2("broker_tel_notice","notice_right");
				 showInfo2("broker_tel_notice",mag_broker);
				 $("#a").val("2");
			}else{
				showclass2("broker_tel_notice0","bor_red");
		  		showclass2("broker_tel_notice","notice_warn");
				showInfo2("broker_tel_notice",msg_broker_tel_weiyi_v);
				$("#a").val("1");
			}
		}
	  });
}

function checkBrokerDescribe_v(str){
	moveclass2("realtor_describe_notice0","bor_blu");
	if(str.val()!=""){	
		showclass2("realtor_describe_notice","notice_right");			
		showInfo2("realtor_describe_notice",mag_broker);
	}else{
		showInfo2("realtor_describe_notice","");
	}
}
//------------------------独立经纪人
//公司验证
function checkCompany_v(str)
  {
	  moveclass2("company_name_abbr_notice0","bor_blu");	
	  moveclass2("company_name_abbr_notice0","bor_red");
//	  moveclass2("company_name_abbr_notice","notice_hover");
	  moveclass2("company_name_abbr_notice","notice_right");
	  moveclass2("company_name_abbr_notice","notice_warn");
	  if(str.val()!='')
	  {
		  getCompany_v();
		  if($("#b").val()=='1'){
			 return false;
     	  }else {
			 return true;
	      }
	  }else{
		  showclass2("company_name_abbr_notice0","bor_red");
		  showclass2("company_name_abbr_notice","notice_warn");
		  showInfo2("company_name_abbr_notice",msg_company_kong);
		  return false; 
	  }
}

function getCompany_v()
{
	var city_id =  $("#city_id").val();
	var str =  $("#company_name").val();
	$.ajax({
		type: "POST",
		url: base_url+"ajax/getCompanyExist?rnd="+Math.random()*5,
		data: "company_name_abbr=" + encodeURIComponent(str) + "&city_id="+ city_id,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data != null){
				 $("#company_id").val(data);
				 showclass2("company_name_abbr_notice0","");
		  		 showclass2("company_name_abbr_notice","notice_right");
				 showInfo2("company_name_abbr_notice",mag_broker);
				
			}else{
				$("#company_id").val("");
				showclass2("company_name_abbr_notice0","bor_red");
		  		showclass2("company_name_abbr_notice","notice_warn");;
				showInfo2("company_name_abbr_notice",msg_company_guize);
			}
		}
	  });
}

//门店
function checkAgentinfo_v(str){
	moveclass2("shop_info_notice0","bor_blu");	
	moveclass2("shop_info_notice0","bor_red");
	moveclass2("shop_info_notice","notice_hover");
	moveclass2("shop_info_notice","notice_right");
	moveclass2("shop_info_notice","notice_warn");
	if(str.val()!=""){
		showclass2("shop_info_notice","notice_right");			
		showInfo2("shop_info_notice",mag_broker);
		return true;
	}else{
	    showInfo2("shop_info_notice",msg_broker_agent_info_kong_v);
		showclass2("shop_info_notice","notice_warn");
		showclass2("shop_info_notice0","bor_red");
		return false;
	}
}
//邮箱

function checkBrokerMail_v(str){
	  moveclass2("realtor_mail_notice0","bor_blu");	
	  moveclass2("realtor_mail_notice0","bor_red");
//	  moveclass2("broker_mail_notice","notice_hover");
	  if(str.val()=='')
	  {
		  showclass2("realtor_mail_notice0","bor_red");
		  showclass2("realtor_mail_notice","notice_warn");
		  showInfo2("realtor_mail_notice",msg_broker_mail_kong_v);
		  return false; 
	  }
	  var msg=/^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
      if(msg.test(str.val()))
	  {
		 getBrokerMail_v(str.val());
		 if($("#c").val()==1){
			 return false;
     	 }else{
			 return true;
	     }
	  }
	  else 
      {
	      showclass2("realtor_mail_notice0","bor_red");
		  showclass2("realtor_mail_notice","notice_warn");
		  showInfo2("realtor_mail_notice",msg_broker_mail_guize_v);
		  return false; 
	  }
}


function getBrokerMail_v(str)
{
	var broker_id = $("#realtor_id").val();
	$.ajax({
		type: "GET",
		url: base_url+"ajax/getRealtorMail?rnd="+Math.random()*5,
		data: "realtor_mail=" + str + "&realtor_id=" + broker_id,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == null){
				 showclass2("realtor_mail_notice0","");
		  		 showclass2("realtor_mail_notice","notice_right");
				 showInfo2("realtor_mail_notice",mag_broker);
				 $("#c").val("2");
			}else{
				showclass2("realtor_mail_notice0","bor_red");
		  		showclass2("realtor_mail_notice","notice_warn");
				showInfo2("realtor_mail_notice",msg_broker_mail_weiyi_v);
				$("#c").val("1");
			}
		}
	  });
}

function checkComanyId(str){
      moveclass2("company_name_abbr_notice0","bor_blu");	
	  moveclass2("company_name_abbr_notice0","bor_red");
	  moveclass2("company_name_abbr_notice","notice_hover");
	  moveclass2("company_name_abbr_notice","notice_right");
	  moveclass2("company_name_abbr_notice","notice_warn");
	 var str = $("#company_id").val();
	 if($('#company_name').val()=='')
	 {
		  showclass2("company_name_abbr_notice0","bor_red");
		  showclass2("company_name_abbr_notice","notice_warn");
		  showInfo2("company_name_abbr_notice",msg_company_kong);
		  return false; 
	 }
	 else
	 {
		  if(str !=''){
			showclass2("company_name_abbr_notice0","");
			showclass2("company_name_abbr_notice","notice_right");
			showInfo2("company_name_abbr_notice",mag_broker);
			return true;
	 	}else{
			showclass2("company_name_abbr_notice0","bor_red");
	    	showclass2("company_name_abbr_notice","notice_warn");
			showInfo2("company_name_abbr_notice",msg_company_kong);
			return false;
		 }
	 }
	
}

//------------------------------------------用户中心-经纪人资料修改 -end----------------------------------//


//------------------------------------------用户中心-修改密码 -end----------------------------------//
//原密码
function checkPassword0_d(str){
	moveclass2("txtpasswd0_notice0","bor_blu");
	moveclass2("txtpasswd0_notice0","bor_red");
	moveclass2("txtpasswd0_notice","notice_hover");
	if(str.val()==""){	
	    showclass2("txtpasswd0_notice0","bor_red");
		showclass2("txtpasswd0_notice","notice_warn");			
		showInfo2("txtpasswd0_notice",msg_password0_kong);
		return false;
	}else{
		 //return getPassword0_d(str);
		 getPassword0_d(str);
		 if($("#a").val()==1){
			 return false;
     	 }else{
			 return true;
	     }
	}
}
function getPassword0_d(str){
	moveclass2("txtpasswd0_notice0","bor_blu");
	moveclass2("txtpasswd0_notice0","bor_red");
	moveclass2("txtpasswd0_notice","notice_hover");
	var ent_accname = $("#ent_accname").html();
	var passwd = str.val();
	$.ajax({  
		type: "GET",
		url: base_url+'ajax/get_password?rnd='+Math.random()*5,
		data: "ent_accname=" +ent_accname +"&passwd=" + $.md5(passwd),
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == null){
			    showclass2("txtpasswd0_notice0","bor_red");
		  		showclass2("txtpasswd0_notice","notice_warn");;
				showInfo2("txtpasswd0_notice",msg_password0_cuowu);
				$("#a").val("1");
			}else{
		  		 showclass2("txtpasswd0_notice","notice_right");
				 showInfo2("txtpasswd0_notice",mag_broker);
				$("#a").val("2");
			}
		}
	  });
}
//新密码验证
function checkPassword_d(str){
	  moveclass2("txtpasswd_notice0","bor_blu");
	  moveclass2("txtpasswd_notice0","bor_red");
	  moveclass2("txtpasswd_notice","notice_hover");
	  if(str.val()=='')
	  {
		  showclass2("txtpasswd_notice0","bor_red");
		  showclass2("txtpasswd_notice","notice_warn");
		  showInfo2("txtpasswd_notice",msg_password_kong);
		  return false; 
	  }
	 var msg = /^[_0-9a-z]{6,16}$/i;
     if(msg.test(str.val()))
	  { 
		  showclass2("txtpasswd_notice","notice_right");
		  showInfo2("txtpasswd_notice",mag_broker);
		  return true;
	  }else{
		  showclass2("txtpasswd_notice0","bor_red");
		  showclass2("txtpasswd_notice","notice_warn");
		  showInfo2("txtpasswd_notice",msg_password_guize);
		  return false; 
	 }
}

//重复密码验证
function checkRepassword_d(str){
	 moveclass2("txtrepasswd_notice0","bor_blu");
	 moveclass2("txtrepasswd_notice0","bor_red");
	 moveclass2("txtrepasswd_notice","notice_hover");
	 if(str.val()=='')
	  {
		  
		  showclass2("txtrepasswd_notice0","bor_red");
		  showclass2("txtrepasswd_notice","notice_warn");
		  showInfo2("txtrepasswd_notice",msg_password_kong);
		  return false; 
	  }
      var msg = /^[_0-9a-z]{6,16}$/i;
     if(msg.test(str.val()))
	  { 
		    if(str.val()!=$("#txtpasswd").val())
	      {
		      moveclass2("txtrepasswd_notice","notice_right");
		      moveclass2("txtrepasswd_notice0","bor_blu");
		      showclass2("txtrepasswd_notice","notice_warn");
		      showInfo2("txtrepasswd_notice",msg_repassword_guize);
		      return false; 
	      }else{
		      moveclass2("txtrepasswd_notice0","bor_blu");
		      showclass2("txtrepasswd_notice","notice_right");
		      showInfo2("txtrepasswd_notice",mag_broker);
		      return true;
	     }
	  }else{
		  moveclass2("txtrepasswd_notice","notice_right");
		  moveclass2("txtrepasswd_notice0","bor_blu");
		  showclass2("txtrepasswd_notice","notice_warn");
		  showInfo2("txtrepasswd_notice",msg_password_guize);
		  return false; 
	 }
      
}

//------------------------------------------用户中心-修改密码 -end----------------------------------//

//------------------------------------------CRM系统-添加或修改客户 -begin----------------------------------//
//判断账号是否存在
function checkAccname_c(str,city_id){
	if(str.value==""){	
		document.getElementById("accname_notice").innerHTML = "账号不能为空";
	}else{
		getAccname_c(str,city_id);
	}
}
function getAccname_c(str,city_id){
	var ent_accname = str.value;
	var city_id = city_id ;
	var ent_type = document.getElementById("ent_type").value;
	$.ajax({
		type: "POST",
		url: base_url+"ajax/get_accname_exist_byTypeCity.php?rnd="+Math.random()*5,
		data: "ent_accname=" +ent_accname +"&city_id=" + city_id +"&ent_type=" + ent_type ,
		dataType: "json",
		cache: false,
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			if(data == 0){
			   document.getElementById("accname_notice").innerHTML = "账号不存在";
			}else{
		  	   document.getElementById("accname_notice").innerHTML = "";
			}
		}
	  });
}
function qingkongAccname(){
	var obj = document.getElementById("ent_accname");
	obj.value = '';
}
//------------------------------------------CRM系统-添加或修改客户 -end----------------------------------//

/**
 * CRM 经纪人合并 验证
 * 1、目标经纪人是否 启用，是否同公司，是否同类型
 */
function checkBrokerForMerger(name)
{
	$.ajax({
		type: "POST",
		url: "ajax/get_broker_by_name.json.php",
		data: "accname=" + name,
		dataType: "json",
		success: function(data){
			if(data == null || data == "" ){			
				showInfo("accnameshow","经纪人账号错误！");
				change_submit("true"); 
			}else{
				 $.each(data, function(i, n){
					if(!isNaN(i)){
						 $("#d_broker_id").val(i);
					}
					showInfo("accnameshow",n);
					change_submit("false"); 
				});				
			}
		}
	  });
}
/**
 * CRM 经纪人转移房源 验证
 * 
 */
function checkBrokerForMoveUnit(name)
{
	$.ajax({
		type: "POST",
		url: "ajax/get_broker_by_name_for_moveunit.json.php",
		data: "accname=" + name,
		dataType: "json",
		success: function(data){
			//alert(data);
			if(data == null || data == "" ){			
				showInfo("accnameshow","经纪人账号错误！");
				change_submit("true"); 
			}else{
				 $.each(data, function(i, n){
					if(!isNaN(i)){
						 $("#d_broker_id").val(i);
					}
					showInfo("accnameshow",n);
					change_submit("false"); 
				});				
			}
		}
	  });
}

//个人 密码验证（因为规则不同，添加此方法） person_password
function checkPersonPassword_d(str){
	  var strVal = str.val();
	  if(strVal=='')
	  {
		  showclass("textpasswd_notice0","wrong");
		  showInfo("textpasswd_notice",msg_person_password_kong);
		  return false; 
	  }
	  var strLen = strVal.length;
	  if(strLen < 6 || strLen > 16)
	  {
		  showclass("textpasswd_notice0","wrong");
		  showInfo("textpasswd_notice",msg_person_password_length);
		  return false; 	  	
	  }
	  var msg = /^[0-9A-Za-z~!@#\$%\^&\*\(\)_\+`\-\\=\[\];',\.\/\{\}\|:"<>\?]{6,16}$/;
      if(msg.test(strVal))
	  { 
		  showclass("textpasswd_notice0","right");
		  showInfo("textpasswd_notice",mag_broker);
	      change_submit("false"); 
	  }else{
		  showclass("textpasswd_notice0","wrong");
		  showInfo("textpasswd_notice",msg_person_password_guize);
		  return false; 
	 }
}

//个人重复密码验证（因为规则不同，添加此方法） person_repassword
function checkPersonRepassword_d(str){
	 if(str.val()=='')
	  {
		  showclass("confirm_pwd_notice0","wrong");
		  showInfo("confirm_pwd_notice",msg_person_repassword_kong);
		  return false; 
	  }
      if(str.val()!=$("#person_password").val())
	  {
		  showclass("confirm_pwd_notice0","wrong");
		  showInfo("confirm_pwd_notice",msg_person_repassword_guize);
		  return false; 
	  }else{
		  showclass("confirm_pwd_notice0","right");
		  showInfo("confirm_pwd_notice",mag_broker);
	      change_submit("false"); 
	 }
}

/**
 * 个人用户名 验证 
 * @author yanfang
 * @since 2011-11-18
 **/
function checkPersonAccname_d(str){ 
	  var str_val = str.val();
	  if(str_val=='')
	  {
		  showclass("person_accname_notice0","wrong");
		  showInfo("person_accname_notice",msg_person_accname_kong);
		  return false;
	  }
	  var msg=/^[a-zA-Z]/;
	  if(!msg.test(str_val)){
		  showclass("person_accname_notice0","wrong");
		  showInfo("person_accname_notice",msg_person_accname_guize_begin);
		  return false;	  	
	  }	 
	  var msgTmp = /^[a-zA-Z]\w*$/;
	  if(!msgTmp.test(str_val)){
			showclass("person_accname_notice0","wrong");
			showInfo("person_accname_notice",msg_person_accname_guize);
			return false;		
	  }	   
	  if(str_val.length < 4){
		  showclass("person_accname_notice0","wrong");
		  showInfo("person_accname_notice",msg_person_accname_length);
		  return false;	  	  	
	  }

	  var msg1=/^[a-zA-Z]\w{3,29}$/;
      if(msg1.test(str_val))
	  {		
		 getPersonAccname_d(str_val); 
	  }
	  else 
      {
		  showclass("person_accname_notice0","wrong");
		  showInfo("person_accname_notice",msg_person_accname_guize);
		  return false;
	  }
}
//检测 个人用户名
function getPersonAccname_d(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/check_person_exist.json.php?rnd="+Math.random()*5,
		data: "username=" + str,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
			     showclass("person_accname_notice0","right");
				 showInfo("person_accname_notice",mag_broker);
	             change_submit("false"); 
			}else{
				showclass("person_accname_notice0","wrong");
				showInfo("person_accname_notice",msg_person_accname_weiyi);
				change_submit("true"); 
			}
		}
	  });
}

//个人 邮箱验证
function checkPersonMail_d(str)
  {
  	  var strVal = str.val();
	  if(strVal == "")
	  {
		  showclass("person_email_notice0","wrong");
		  showInfo("person_email_notice",msg_person_email_kong);
		  return false;		  
	  }
	  var msg=/^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
      if(!msg.test(strVal))
	  { 
	      showclass("person_email_notice0","wrong");
		  showInfo("person_email_notice",msg_person_email_guize);
		  return false; 	  			  
	  }
      getPersonEmail_d(strVal); 	  
}

//检测 个人邮箱 added by yanfang 2013-1-30
function getPersonEmail_d(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/check_person_exist.json.php?rnd="+Math.random()*5,
		data: "email=" + str,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				showclass("person_email_notice0","right");
				showInfo("person_email_notice",mag_broker);
		        change_submit("false"); 	
			}else{
			    showclass("person_email_notice0","wrong");
				showInfo("person_email_notice",msg_person_email_weiyi);
				change_submit("true"); 
			}
		}
	  });
}

//个人中心改版 邮箱验证 added by yanfang 2013-2-5
function checkPersonalMail(str)
  {
  	  var strVal = str.val();
	  if(strVal == "")
	  {
		  showclass("person_email_notice0",class_personal_error_notice0);
		  showclass("person_email_notice",class_personal_error_notice);
		  showHtml("person_email_notice",msg_person_email_kong);
		  return false;		  
	  }
      if(!reg_email.test(strVal))
	  { 
	      showclass("person_email_notice0",class_personal_error_notice0);
	      showclass("person_email_notice",class_personal_error_notice);
		  showHtml("person_email_notice",msg_person_email_guize);
		  return false; 	  			  
	  }
      getPersonalEmail(strVal); 	  
}

//检测 个人邮箱
function getPersonalEmail(str)
{
	$.ajax({
		type: "GET",
		url: "ajax/check_person_exist.json.php?rnd="+Math.random()*5,
		data: "email=" + str,
		dataType: "json",
		cache: false,
		success: function(data){
			if(data == null){
				showclass("person_email_notice0",class_personal_init_notice0);
//				showclass("person_email_notice",class_personal_init_notice);
				showclass("person_email_notice","");
				showInfo("person_email_notice",msg_personal_suc);
		        change_submit("false"); 	
			}else{
			    showclass("person_email_notice0",class_personal_error_notice0);
			    showclass("person_email_notice",class_personal_error_notice);
				showHtml("person_email_notice",msg_person_email_weiyi);
				change_submit("true"); 
			}
		}
	  });
}

//个人 验证码验证
function checkPersonYanzheng_d(str){
	 var yanzheng =  $("#yanzheng").val();
	 if(yanzheng == ''){
		  showclass("yanzheng_notice0","wrong");
		  showInfo("yanzheng_notice",msg_person_yanzheng_kong);
		  return false; 	 	
	 }
	 $.ajax({
			type: "GET",
			url: "ajax/get_checkcode.php",
			data: "code=" + yanzheng,
			success: function(result){ 
				if(result == true){
					showclass("yanzheng_notice0","right");
		  			showInfo("yanzheng_notice",mag_broker);
		  			change_submit("false"); 
				}else{
					showclass("yanzheng_notice0","wrong");
		  			showInfo("yanzheng_notice",msg_person_yanzheng_guize);
		  			change_submit("true");
				}
			}
	 });
}
function checkPersonReadDeal(){
  if (document.formUser.xieyi.checked==false)
  {
	  showclass("xieyi_notice0","wrong");
      showInfo("xieyi_notice",msg_person_xieyi_kong);
      return false; 
}
  else
  {
	showclass("xieyi_notice0","right");
		  showInfo("xieyi_notice",mag_broker);
		  change_submit("false"); 
	}
}

/**
  * 判断form的submit的disable值
  * yanfang
  * 2011-12-5
  **/
function get_submit_disable(res){
	if($("input[name='Submit1']").attr('disabled') === true){res = false;}
	return res;
}

/**
 * 获取小区对应居室的户型图
 * @author 周海龙 <hailongzhou@sohu-inc.com>
 * @date 2011-12-21 
 */
function gethximg(){
	var house_id = $("#dict_house_id").val();
	var bedroom = $("#bedroom").val();
	if(house_id != null && bedroom != null){
		$.ajax({
    		async:false,
    		type: "GET",
    		url: "/ajax/get_house_huxingtu.json.php",
    		data: "house_id="+house_id+"&bedroom=" + bedroom,
    		dataType: "json",    	
    		success: function(data){
    			_data = data;
    			$("#house_huxing").empty().show();
    			if(_data!=null){
    				var cp = ' width="100" height="75"  ';
        			$.each(_data,function(i,e){
        				$("#house_huxing").append('<dd><a href="'+e['bigUrl']+'" target="_blank"><img'+cp+' src="'+e['url']+'" /></a><p>&nbsp;&nbsp;<input id="'+ e['image_id'] +'_c" type="checkbox" style="float:none;" name="'+e['image']+'" value="'+e['url']+'" onclick=\"javascript:sethximg(this);\" />设为户型图</p></dd>');
        			});
        			var h = $("#house_huxing").css("height");
    				if(parseInt(h)>288){//大概超出三排户型图的时候增加滚动条
    					$("#house_huxing").css({
    						"overflow-y":"scroll",
    						"height":"288"
    					});
    				}else{
    					$("#house_huxing").css({
    						"overflow-y":"auto",
    						"height":"auto"
    					});
    				}
    			}else{
    				$("#house_huxing").hide();
    			}
    		}    	
    	});
	}
	
}

/**
 * 设置为户型图
 * @author 周海龙 <hailongzhou@sohu-inc.com>
 * @date 2011-12-21 
 */
function sethximg(obj){
	var image = obj.name.split(".");
	var image_url = obj.value;
	var cp = ' width="100" height="75"  ';
	if(obj.checked==true){//设置
		//判断户型图数量
		var inthximg = $("#huxing dd").size();
		if(parseInt(inthximg)<5){
			$("#huxing").append('<dd id="'+image[0]+'"><img'+cp+' src="'+image_url+'" /><p><input type="hidden" name="image[1][0][]" value="' + image[0] + '.' + image[1] + '">&nbsp;&nbsp;<a href="javascript:void(0)" onclick="$(\'#' + image[0] + '_c\').attr(\'checked\',false);$(\'#' + image[0] + '\').remove()">删除</a></p></dd>');
		}else{
			obj.checked=false;
			alert("户型图最多上传5张");
		}
	}else{//取消设定
		$("#"+image[0]).remove();
	}
}
