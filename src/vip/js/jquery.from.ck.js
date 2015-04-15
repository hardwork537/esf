
/**
 * @abstract 执行页面验证一系列的操作判断
 * @author liuli <liliu@51f.com>
 * @date 2011-03-28
 * @lastModify By liuli 2011-03-28
 *
 */

var arrSubmit=new Array();

/**
 *用户帐户提示
 *@param object obj 对象
 *@param string promptStr 显示信息标签ID
 */
function checkFormatAccounts(obj,promptStr)
{
	$("#"+promptStr).empty().removeClass("FrameDivWarn").removeClass("FrameDivPass").addClass('FrameDivMessage');//切换提示信息
	//showinfo(promptStr,msg_accounts_format_legal);
	$("#"+promptStr).empty().removeClass("FrameDivMessage").addClass('FrameDivWarn');//切换提示信息
	showinfo(promptStr,msg_accounts_format);
	return false;
}

/**
 * 帐户正则检测
 * @param obj
 * @param promptStr
 * @returns
 */
function checkAccountsPreg(obj)
{
   if(pregFromString(obj.value,pregAccounts))
   {
	   arrSubmit[obj.name]='true';
	   return true;
   }
   return false;
}

/**
 * 帐户正则错误提示
 * @param obj
 * @param promptStr
 */
function checkAccountsPreg_iswrong(obj,promptStr)
{
	$("#"+promptStr).empty().removeClass("FrameDivPass").removeClass("FrameDivMessage");//清空当前提示信息
	showinfo(promptStr,msg_accounts_format);
	//checkMessageFormat(obj,promptStr,msg_accounts_format);
}

/**
 * 公司简称提示
 * @param obj
 * @param promptStr
 */
function checkFormatCompanyAbbr(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_company_abbr_format_length);
}

/**
 * 公司简称正则
 * @param obj
 * @param promptStr
 */
function checkFormatCompanyAbbr_preg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,16,pregCompany,msg_company_abbr_legal,msg_company_abbr_format_length_max,msg_company_abbr_format);

}

/**
 * 密码提示
 * @param obj 表单对象
 * @param promptStr  信息标签ID
 */
function checkFormatPasswd(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_passwd_format_length);
}

/**
 * 密码正则
 * @param obj
 * @param promptStr
 */
function checkFormatPasswdPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,6,16,pregPasswd,msg_passwd_format_legal,msg_passwd_format_length_max,msg_passwd_format);
}
/**
 * 二次输入密码提示
 * @param obj
 * @param promptStr
 */
function checkPasswdConformFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_passwd_format_prompt);
}

/**
 * 二次密码验证
 * @param obj
 * @param master
 * @param promptStr
 */
function checkPasswdConformPreg(obj,master,promptStr)
{
	var bReturn = checkPreg(obj,promptStr,6,16,pregPasswd,'','',msg_passwd_format_compare_null);
	if(bReturn){
		masterpasswd=$("#"+master).val();
		if(masterpasswd!=obj.value)
		{

			$("#"+promptStr).empty().removeClass("FrameDivPass").removeClass("FrameDivMessage").addClass("FrameDivWarn");
			showinfo(promptStr,msg_passwd_format_compare);
		}
		else
		{
			checkFormatPasswdPreg(obj,promptStr);
		}
	}
}
/**
 * 真实姓名提示
 * @param obj
 * @param promptStr
 */
function checkTrueNameFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_truename_format_length);
}

/**
 * 真实姓名正则
 * @param obj
 * @param promptStr
 */
function checkTrueNameFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,5,pregTrueName,msg_truename_format_legal,msg_truename_format_length_max,msg_truename_format);
}

/**
 * 手机提示
 * @param obj
 * @param promptStr
 */
function checkMobileFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_mobile_format_length);
}

/**
 * 手机正则
 * @param obj
 * @param promptStr
 */
function checkMobileFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,11,11,pregMobile,msg_mobile_format_legal,msg_mobile_format_legal,msg_mobile_format);
}

/**
 * 公司提示
 * @param obj
 * @param promptStr
 */
function checkCompanyFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_company_format_length);
}

/**
 * 公司正则
 * @param obj
 * @param promptStr
 */
function checkCompanyFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,50,pregCompany,msg_company_format_legal,msg_company_format_length_max,msg_company_format);
}


/**
 * 门店提示
 * @param obj
 * @param promptStr
 */
function checkAgentFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_agent_format_legal);
}

/**
 * 区域提示
 * @param obj
 * @param promptStr
 */
function checkSectorFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_sector_format_legal);
}

function checkCompanyFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_dcompany_format_legal);
}
/**
 * 区域正则
 * @param obj
 * @param promptStr
 */
function checkSectorFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,50,pregSector,msg_sector_format_length,msg_sector_format_length_max,msg_sector_format);
}

function checkCompanyDictionaryFormatPreg(obj,promptStr,city_id)
{
	var bReturn=checkPreg(obj,promptStr,2,50,pregCompany,msg_dcompany_format_length,msg_dcompany_format_length_max,msg_dcompany_format);
	if(bReturn){
	var	select=document.getElementById( "city")
	var	option = select.options[select.selectedIndex];
	var objcity = option.attributes.value;
		checkCompanyOne(obj,promptStr,objcity.value);
	}
}

/**
* crm后台验证公司名称专用
*
*/
function checkCRMCompanyDictionaryFormatPreg(obj,promptStr,city_id){
	var bReturn=checkPreg(obj,promptStr,2,100,pregCRMCompany,msg_dcompany_format_length,msg_dcompany_format_length_max,msg_dcompany_format);
	if(bReturn){
	var	select=document.getElementById("city")
	var	option = select.options[select.selectedIndex];
	var objcity = option.attributes.value;
		checkCompanyOne(obj,promptStr,objcity.value);
	}
}

/**
 * 城区提示
 * @param obj
 * @param promptStr
 */
function checkDistrictFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_district_format_legal);
}

/**
 * 城区正则
 * @param obj
 * @param promptStr
 */
function checkDistrictFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,5,pregDistrict,msg_district_format_length,msg_district_format_length_max,msg_district_format);
}

/**
 * 板块提示
 * @param obj
 * @param promptStr
 */
function checkHotAreaFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_hotarea_format_legal);
}

/**
 * 板块正则
 * @param obj
 * @param promptStr
 */
function checkHotAreaFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,7,pregHotArea,msg_hotarea_format_length,msg_hotarea_format_length_max,msg_hotarea_format);
}

/**
 * 坐标提示
 * @param obj
 * @param promptStr
 */
function checkCoordFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_coord_format_legal);
}

/**
 * 坐标正则
 * @param obj
 * @param promptStr
 */
function checkCoordFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,15,pregCoord,msg_coord_format_length,msg_coord_format_length_max,msg_coord_format);
}

/**
 * 门店正则
 * @param obj
 * @param promptStr
 */
function checkAgentFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,15,pregAgent,msg_agent_format_length,msg_agent_format_length_max,msg_agent_format);
}

/**
 * 门店帐号正则
 * @param obj
 * @param promptStr
 */
function checkAgentAccNameFormatPreg(obj,promptStr)
{
	var bReturn = checkPreg(obj,promptStr,2,15,pregAccounts,msg_accounts_format_legal,msg_accounts_format_length,msg_accounts_format_null);
	if(bReturn){
		checkTheOne(obj);
	}
}

/**
 * 区域帐号正则
 * @param obj
 * @param promptStr
 */
function checkSectorAccNameFormatPreg(obj,promptStr)
{
	var bReturn = checkPreg(obj,promptStr,2,15,pregAccounts,msg_accounts_format_legal,msg_accounts_format_length,msg_accounts_format_null);
	if(bReturn){
		checkTheOne(obj);
	}
}

/**
 * 区域帐号正则
 * @param obj
 * @param promptStr
 */
function checkCompanyNameFormatPreg(obj,promptStr)
{
	var bReturn = checkPreg(obj,promptStr,2,50,pregCompany,msg_dcompany_format_legal,msg_dcompany_format_length,msg_dcompany_format_null);
	if(bReturn){
		checkCompanyOne(obj,1,'sector_accname');
	}
}


/**
 * 邮箱提示
 * @param obj
 * @param promptStr
 */
function checkMailFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_mail_format_length);
}

/**
 * 邮箱正则
 * @param obj
 * @param promptStr
 */
function checkMailFormatPreg(obj,promptStr)
{
	checkPreg(obj,promptStr,2,50,pregMail,msg_mail_format_length,msg_mail_format_length_max,msg_mail_format);
}

/**
 * 端口提示
 * @param obj
 * @param promptStr
 */
function checkPortFormat(obj,promptStr)
{
	checkMessageFormat(obj,promptStr,msg_port_format_length);
}

/**
 * 端口正则
 * @param obj
 * @param promptStr
 */
function checkPortFormatPreg(obj,promptStr,clickPort)
{
	if(false === $("input[name="+clickPort+"]").attr("checked")){
		checkPreg(obj,promptStr,1,4,pregPort,msg_port_format_legal,msg_port_format_length_max,msg_port_format);
	}
}


/**
 * 点击提示信息
 * @param obj object对象
 * @param promptStr 提示信息标签ID
 * @param msg_format　提示信息
 */
function checkMessageFormat(obj,promptStr,msg_format)
{
	$("#"+promptStr).empty().removeClass("FrameDivWarn").removeClass("FrameDivPass").addClass("FrameDivMessage");//清空当前提示信息
	showinfo(promptStr,msg_format);
}

/**
 * 验证通用方法
 * @param obj object对象
 * @param promptStr 提示信息标签ID
 * @param mixLength　最大长度
 * @param maxLength  最小长度
 * @param preg  正则表达式
 * @param msg_format_preg 正则错误提示信息
 * @param msg_format_max  超过最大长度提示信息
 * @param msg_format_null 为空提示信息
 */
function checkPreg(obj,promptStr,mixLength,maxLength,preg,msg_format_preg,msg_format_max,msg_format_null)
{
  	if(obj.value.length>=mixLength && obj.value.length<=maxLength)
  	{

  		if(pregFromString(obj.value,preg)){
            arrSubmit[obj.name]='true';
  			$("#"+promptStr).empty().removeClass("FrameDivMessage").addClass("FrameDivPass");//切换样式
	        showinfo(promptStr,msg_access_printf);
	        FormatNameDisabled('Submit1',false);
	        return true;
  		}else{
  			$("#"+promptStr).empty().removeClass("FrameDivMessage").addClass("FrameDivWarn");//切换样式
			showinfo(promptStr,msg_format_preg);
  		}
  	}
  	if(obj.value.length>maxLength)
  	{
  		$("#"+promptStr).empty().removeClass("FrameDivMessage").addClass("FrameDivWarn");
		showinfo(promptStr,msg_format_max);
  	}
  	if(obj.value==null || obj.value=="")
  	{
  		$("#"+promptStr).empty().removeClass("FrameDivMessage").addClass("FrameDivWarn");
		showinfo(promptStr,msg_format_null);
  	}
}

/**
 * 正则匹配
 * @param str  匹配字符串
 * @param pregtype 正则表达式
 * @returns
 */
function pregFromString(str,pregtype)
{
    if(pregtype.test(str)) return true;
    return false;
}

/**
 * 显示提示信息
 * @param obj 显示信息标签ID
 * @param message  信息内容
 */
function showinfo(obj,message)
{
   $("#"+obj).append(message);
}

/**
 * 禁用表单提交
 * @param string
 */
function FormatDisabled(obj,status)
{
	if(status)
	{
		$("#"+obj.name).attr("disabled","disabled");
	}
	else
	{
		$("#"+obj.name).removeAttr("disabled");
	}

}

function FormatNameDisabled(obj,status)
{
	if(status)
	{
		$("#"+obj).attr("disabled","disabled");
	}
	else
	{
		$("#"+obj).removeAttr("disabled");
	}

}

function checksubmit()
{
	//alert(arrSubmit['txtaccounts']);
	if(arrSubmit['txtpasswd'] && arrSubmit['txtaccounts'] && arrSubmit['txtrepasswd'])
	{
		FormatNameDisabled('Submit1',false);
		return true;
	}
	else
	{
		get_dist_company_name($("#company_name"),'conform_company_abbr_notice');
		get_ent_accname(document.getElementById('txtaccounts'));
		checkFormatPasswdPreg(document.getElementById('txtpasswd'),'password_notice');
		checkPasswdConformPreg(document.getElementById('txtrepasswd'),'txtpasswd','conform_password_notice');
		FormatNameDisabled('Submit1',true);
		 return false;
	}

}

function checksubmitAgent(type)
{
	if('add' == type){
		checkAgentFormatPreg(document.getElementById('shop_name'),'c_name');
		checkAgentAccNameFormatPreg(document.getElementById('shop_accname'),'c_accname');
		checkFormatPasswdPreg(document.getElementById('passwd'),'c_pass');
		checkPasswdConformPreg(document.getElementById('passwd_i'),'passwd','c_pass_i');
		checkPortFormatPreg(document.getElementById('max_port'),'c_port','all_max_port');
	//alert(arrSubmit['txtaccounts']);
		if(arrSubmit['shop_accname'] && arrSubmit['passwd']&& arrSubmit['shop_name']){
			FormatNameDisabled('Submit1',false);
			return true;
		}else{

			FormatNameDisabled('Submit1',true);
			return false;
		}
	}else if('edit' == type){
		checkAgentFormatPreg(document.getElementById('shop_name'),'c_name');
		if(arrSubmit['shop_name']){
			FormatNameDisabled('Submit1',false);
			return true;
		}else{

			FormatNameDisabled('Submit1',true);
			return false;
		}
	}else{

	}

}

function checkSubmitSector(type)
{
	if(type == 'add')
	{
		checkSectorFormatPreg(document.getElementById('sector_name'),'sectorname_notice');
		checkSectorAccNameFormatPreg(document.getElementById('ent_accname'),'sectoraccname_notice');
		checkFormatPasswdPreg(document.getElementById('passwd'),'pass_notice');
		checkPasswdConformPreg(document.getElementById('repeat_passwd'),'passwd','repeat_passwd_notice');
		if(arrSubmit['sector_name'] && arrSubmit['ent_accname'] && arrSubmit['passwd'] && arrSubmit['repeat_passwd'])
		{
			FormatNameDisabled('Submit1',false);
			return true;
		}
		else
		{
			FormatNameDisabled('Submit1',true);
			return false;
		}
	}
	elseif (type = 'update')
	{
		checkSectorFormatPreg(document.getElementById('sector_name'),'sectorname_notice');
		if(arrSubmit['sector_name']){
			FormatNameDisabled('Submit1',false);
			return true;
		}else{

			FormatNameDisabled('Submit1',true);
			return false;
		}
	}
/*	if(arrSubmit['sector_accname'] && arrSubmit['passwd'])
	{
		FormatNameDisabled('btn_submit',false);
		return true;
	}
	else
	{
		FormatNameDisabled('btn_submit',true);
		 return false;
	}*/
}


//	function onsubmit(event) {
//	 return confirm();
//	 return true;
//	 }


var pregPasswd=/^[\w+]{6,16}$/;//密码匹配规则
var pregAccounts=/^[\w+]{5,15}$/; //帐户匹配规则
var pregCompany=/([~!@#$%&*()`=+,.;?<>-]|\\|\/|\'|\")/; //公司名称匹配规则
var pregTrueName=/^[\u4e00-\u9fa5]{2,5}$/; //真实姓名
var pregMobile=/^1[3|5|8]\d{9}$/; //手机
var pregCompany=/^[\u4e00-\u9fa5|\w+]{2,50}$/;  //公司名称
var pregCRMCompany=/^.{2,100}$/;  //公司名称
var pregAgent=/^[\u4e00-\u9fa5\w]{2,15}$/; //门店
var pregMail=/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;  //Mail
var pregPort=/^[1-9][0-9]{0,3}$/; //端口数量

//yangjun
var pregDistrict=/^[\u4e00-\u9fa5]{2,5}$/;//城区名称
var pregHotArea=/^[\u4e00-\u9fa5\w]{2,7}$/;//板块名称
var pregCoord=/\d{0,5}\.\d{0,15}/; //坐标
var pregSector=/^[\u4e00-\u9fa5\w]{2,15}$/; //区域

