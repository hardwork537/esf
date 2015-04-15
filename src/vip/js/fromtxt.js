var city_tel = String("<{$city_fuwu_tel}>");
var process_request = "<img src='/images/loading.gif' width='16' height='16' border='0' align='absmiddle'>正在数据处理中...";
var username_empty = "<span style='COLOR:#ff0000'>  × 帐号不能为空!</span>";
var username_shorter = "<span style='COLOR:#ff0000'> × 帐号长度不能少于 4 个字符。</span>";
var username_invalid = "- 工号只能是由字母数字以及下划线组成。";
var password_empty = "<span style='COLOR:#ff0000'> × 登录密码不能为空。</span>";
var password_shorter_s = "<span style='COLOR:#ff0000'> × 登录密码不能少于 6 个字符。</span>";
var password_shorter_m = "<span style='COLOR:#ff0000'> × 登录密码不能多于 30 个字符。</span>";
var confirm_password_invalid = "<span style='COLOR:#ff0000'>  两次输入密码不一致!</span>";
var email_empty = "<span style='COLOR:#ff0000'> × Email 为空</span>";
var email_invalid = "- Email 不是合法的地址";
var agreement = "<span style='COLOR:#ff0000'> × 您没有接受协议</span>";
var msn_invalid = "- msn地址不是一个有效的邮件地址";
var qq_invalid = "- QQ号码不是一个有效的号码";
var home_phone_invalid = "- 家庭电话不是一个有效号码";
var office_phone_invalid = "- 办公电话不是一个有效号码";
var mobile_phone_invalid = "- 手机号码不是一个有效号码";
var msg_un_blank = "<span style='COLOR:#ff0000'> × 帐号不能为空!</span>";
var msg_un_length = "<span style='COLOR:#ff0000'> × 帐号最长不得超过15个字符</span>";
var msg_un_format = "<span style='COLOR:#ff0000'> × 长度为5-16位，请以字母、数字、下划线来命名!</span>";
var msg_un_registered = "<span style='COLOR:#ff0000'> × 帐号已经存在,请重新输入!</span>";
var msg_can_rg = "<span style='COLOR:#006600'> √ 可以注册！</span>";
var msg_email_blank = "<span style='COLOR:#ff0000'> × 邮件地址不能为空!</span>";
var msg_email_registered = " × 邮箱已存在,请重新输入!";
var msg_email_format = "<span style='COLOR:#ff0000'> × 邮件地址不合法!</span>";
var username_exist = "帐号 %s 已经存在";
var info_right="<span style='COLOR:#006600'> √ 填写正确!</span>";
var msg_mobile_format = "<span style='COLOR:#ff0000'> × 输入的手机号不合法，以13/15/18 开头的11位手机号为准!</span>";
var msg_mobile_tel_format = "<span style='COLOR:#ff0000'> × 输入号码不合法，以数字+'-' 的号码为准!</span>";
var msg_postcode_format = "<span style='COLOR:#ff0000'> × 邮编地址不合法!</span>";
var msg_truename_format="<span style='COLOR:#ff0000'>用户名为不为空的4个字符!</span>";
var msg_cityname_format="<span style='COLOR:#ff0000'>只能输入2-4个汉字!</span>";
var msg_districtname_format="<span style='COLOR:#ff0000'>城区名为2个以上字符!</span>";
var msg_citycode_format="<span style='COLOR:#ff0000'>城市为2-5个数字!</span>";
var msg_cityspell_format="<span style='COLOR:#ff0000'>城市全拼为4-20个字母!</span>";
var msg_cityabbr_format="<span style='COLOR:#ff0000'>城市简拼为2-5个字母!</span>";
var msg_number_format="<span style='COLOR:#ff0000'>只允许输入数字!</span>";
var msg_broker_tel_format = "<span style='COLOR:#ff0000'> × 手机号格式不对!</span>";
var msg_tel_registered = "<span style='COLOR:#ff0000'> × 手机号已经被注册，请重新输入!</span>";
//----------------add by guoqiangzhang at 2011-03-11-------------------------------//
var msg_truname_format="<span style='COLOR:#ff0000'>只能输入2~6个汉字!</span>";
var msg_accname_format="<span style='COLOR:#ff0000'>请输入3~13个字母、数字!</span>";
var msg_pwd_format="<span style='COLOR:#ff0000'>请输入6~18个字母、数字!</span>";


var msg_broker_name ="<span style='COLOR:#ff0000'>只能输入4~20个字符!</span>";
var msg_broker_year="<span style='COLOR:#ff0000'>请您正确填写建筑年代!</span>"
var mag_floor_max="<span style='COLOR:#ff0000'>请您正确填写楼层!</span>";
var mag_sale_name="<p><span style='COLOR:#ff0000'>请输入<b>3~12</b>个字符</span></p>";

//======================================
var mag_broker = "<p><b></b></p>"
	
var mag_broker_title ="<p><b></b>房源标题参与关键字搜索，应重点突出房源卖点</p>";
var mag_broker_titleError ="<p><b></b>只能输入4-30个字，<a href='http://help.esf.focus.cn/2_7.html' target='_blank'>查看经典范例</a></p>";

var mag_broker_custom_bh = "<p><b></b>请输入3~36个字母或数字</p>";
var mag_broker_bh_customError = "<p><b></b>只能输入3~36个字母或数字</p>";

var mag_broker_custom = "<p><b></b>请输入3~12个字母或数字</p>";
var mag_broker_customError = "<p><b></b>只能输入3~12个字母或数字</p>";

var mag_broker_area_sale = "<p><b></b>请输入数字，2-20000之间</p>";
var mag_broker_area = "<p><b></b>请输入数字，2-10000之间</p>";
var mag_broker_areaError = "<p><b></b>请您正确填写面积</p>";

var mag_broker_price = "<p><b></b>请输入数字，2-100000之间</p>";
var mag_broker_priceError = "<p><b></b>请您正确填写价格</p>";

var mag_broker_price_rent = "<p><b></b>请输入数字，100-1000000之间</p>";
var mag_broker_price_rent_100 = "<p><b></b>请输入数字，1-100之间</p>";
var mag_broker_priceError_rent = "<p><b></b>请您正确填写价格</p>";
var mag_broker_priceError_rent_price = "<p><b></b>请您正确填写租金</p>";

var mag_broker_year = "<p><b></b>例：2000</p>";
var mag_broker_yearError = "<p><b></b>请您正确填写建筑年代</p>";

var mag_broker_floorc = "<p><b></b>请填写数字, 如果是地下室请填写负数,例:-1</p>";
var mag_broker_floorcError = "<p><b></b>请您正确填写楼层</p>";
var mag_broker_floorcError1 = "<p><b></b>请您填写完整</p>";

var mag_broker_housename = "<p><b></b>请录入正确小区名称</p>";
//var mag_broker_housenameError = "<p><b></b>系统检测不到您所填写的小区名称</p>";

var msg_tel_registeredper_me = "<p><b></b>请录入手机号</p>";
var msg_tel_registeredper = "<p><b></b> × 手机号已经被注册，请重新输入!</p>";
var msg_tel_registeredperError = "<p><b></b> × 手机号不能为空，请重新输入!</p>";

//====================独立经纪人注册=====chengchunxing 2011-3-31================//
var msg_indie_borker_accname_tishi = "<p><b></b>账号长度为5-15位(仅限英文字母、数字、下划线)</p>";
var msg_indie_borker_accname_kong = "<p><b></b>用户名不能为空</p>";
var msg_indie_borker_accname_weiyi = "<p><b></b>该用户名已经被注册，换个别的吧</p>";
var msg_indie_borker_accname_guize = "<p><b></b>账号长度为5-15位(仅限英文字母、数字、下划线)</p>"

var msg_indie_borker_password_tishi =  "<p><b></b>密码长度为6-16位（仅限英文字母、数字、字符）</p>";
var msg_indie_borker_password_kong =  "<p><b></b>密码不能为空</p>";
var msg_indie_borker_password_guize =  "<p><b></b>密码长度为6-16位（仅限英文字母、数字、字符）</p>";
var msg_indie_borker_repassword_tishi =  "<p><b></b>请重新输入一次密码</p>";
var msg_indie_borker_repassword_guize =  "<p><b></b>两次输入密码不一致!</p>";

var msg_indie_borker_name_guize =  "<p><b></b>请输入2-6个中文字</p>";
var msg_indie_borker_name_kong =  "<p><b></b>姓名不能为空</p>";

var msg_city_id_kong =  "<p><b></b>请选择城市</p>";
var msg_district_id_kong =  "<p><b></b>请选择区域板块</p>";


var msg_company_tishi =  "<p><b></b>请输入关键字并在列表中选择一家公司</p>";
var msg_company_kong =  "<p><b></b>公司不能为空</p>";


var msg_agent_info_tishi =  "<p><b></b>请输入2-15个字，此处不用写公司名称</p>";
var msg_agent_info_kong =  "<p><b></b>门店名称不能为空</p>";

var msg_indie_borker_tel_tishi = "<p><b></b>请输入您的手机号</p>";
var msg_indie_borker_tel_kong = "<p><b></b>手机号不能为空</p>";
var msg_indie_borker_tel_weiyi = "<p><b></b>该号码已经被注册过了，如有疑问请拨打客服热线</p>";
var msg_indie_borker_tel_guize = "<p><b></b>您输入的手机号码格式有误</p>";
//var msg_indie_borker_tel_guize = "<p><b></b>您输入的手机号码格式有误</p>";

var msg_indie_borker_mail_tishi =  "<p><b></b>该邮箱用于找回密码，请您正确填写</p>";
var msg_indie_borker_mail_kong =  "<p><b></b>邮箱不能为空</p>";
var msg_indie_borker_mail_guize =  "<p><b></b>您输入的邮箱地址格式有误</p>";
var msg_indie_borker_mail_weiyi =  "<p><b></b>已经被注册了，换个别的吧</p>";

var msg_indie_yanzheng_tishi =  "<p><b></b>请输入验证码</p>";
var msg_indie_yanzheng_kong =  "<p><b></b>验证码不能为空</p>";
var msg_indie_yanzheng_guize =  "<p><b></b>您输入的验证码不正确</p>";

var msg_indie_xieyi_kong =  "<p><b></b>请您先阅读协议</p>";

var msg_company_tel = "<b></b>请输入8位电话号码，无需录入区号";

var msg_unit_property_type = "<p><b></b>请选择物业类型</p>"

//====================用户中心-=====chengchunxing 2011-4-20================//
//公司资料修改
var msg_company_address_tishi = "<p><b></b>30个字以内，不能填写电话和特殊符号</p>";

var msg_company_tel_tishi = "<p><b></b>请输入8位电话号码，无需录入区号</p>";
var msg_company_tel_guize = "<p><b></b>电话格式不对</p>";

var msg_company_fax_tishi = "<p><b></b>请输入8位传真号码，无需录入区号</p>";
var msg_company_fax_guize = "<p><b></b>传真格式不对</p>";

//门店资料修改
var msg_agent_address_tishi = "<p><b></b>门店地址限制30个字以内</p>";
var msg_agent_tel_tishi = "<p><b></b>请输入8位电话号码，无需录入区号</p>";
var msg_agent_tel_guize = "<p><b></b>电话格式不对</p>";

var msg_agent_fax_tishi = "<p><b></b>请输入8位传真号码，无需录入区号</p>";
var msg_agent_fax_guize = "<p><b></b>传真格式不对</p>";

//修改密码
var msg_password0_tishi = "<p><b></b>请输入原密码</p>";
var msg_password0_kong = "<p><b></b>原密码不能为空</p>";
var msg_password0_cuowu = "<p><b></b>原密码输入错误</p>";

var msg_password_tishi =  "<p><b></b>密码长度为6-16位（仅限英文字母、数字、字符）</p>";
var msg_password_kong =  "<p><b></b>密码不能为空</p>";
var msg_password_guize =  "<p><b></b>密码长度为6-16位（仅限英文字母、数字、字符）</p>";
var msg_repassword_tishi =  "<p><b></b>请重新输入一次密码</p>";
var msg_repassword_guize =  "<p><b></b>两次输入密码不一致!</p>";

//经纪人资料修改
var msg_broker_tel_tishi_v = "<p><b></b>请输入手机号</p>";
var msg_broker_tel_kong_v  = "<p><b></b>手机号不能为空</p>";
var msg_broker_tel_guize_v = "<p><b></b>手机号格式不对</p>";
var msg_broker_tel_weiyi_v =  "<p><b></b>已经被注册了，换个别的吧</p>";

var msg_broker_agent_info_tishi_v =  "<p><b></b>请输入2-15个字，此处不用写公司名称</p>";
var msg_broker_agent_info_kong_v =  "<p><b></b>门店不能为空</p>";

var msg_broker_mail_tishi_v = "<p><b></b>该邮箱用于找回密码，请正确填写</p>";
var msg_broker_mail_kong_v  = "<p><b></b>邮箱号不能为空</p>";
var msg_broker_mail_guize_v = "<p><b></b>邮箱号格式不对</p>";
var msg_broker_mail_weiyi_v =  "<p><b></b>已经被注册了，换个别的吧</p>";

//个人注册 修改资料 added by yanfang
var msg_person_accname_tishi = "<p><b></b>4-30个字符，仅限字母、数字、下划线，并以英文字母开头</p>";
var msg_person_accname_kong = "<p><b></b>用户名不能为空</p>";
var msg_person_accname_length = "<p><b></b>长度不够呢</p>";
var msg_person_accname_weiyi = "<p><b></b>这个名字已被别人占用啦</p>";
var msg_person_accname_guize_begin = "<p><b></b>用户名必须以英文字母开头</p>";
var msg_person_accname_guize = "<p><b></b>用户名仅限字母、数字、下划线</p>"

var msg_person_phone_tishi = "<p><b></b>请输入您的手机号</p>";
var msg_person_phone_guize = "<p><b></b>您输入的手机号码格式有误</p>";

var msg_person_qq_tishi = "<p><b></b>请输入您的QQ</p>";
var msg_person_qq_guize = "<p><b></b>您输入的QQ格式有误</p>";

var msg_person_msn_tishi = "<p><b></b>请输入您的MSN</p>";
var msg_person_msn_guize = "<p><b></b>您输入的MSN格式有误</p>";
//定义常量 联系方式类型
var person_contact_type_qq_val = 1;
var person_contact_type_msn_val = 2;

//===============个人注册 修改资料===============================
// yanfang 2011-11-21
var msg_person_accname_tishi = "4-30个字符，仅限字母、数字、下划线，并以英文字母开头";
var msg_person_accname_kong = "用户名不能为空";
var msg_person_accname_length = "长度不够呢";
var msg_person_accname_weiyi = "这个名字已被别人占用啦";
var msg_person_accname_guize_begin = "用户名必须以英文字母开头";
var msg_person_accname_guize = "用户名仅限字母、数字、下划线"

var msg_person_phone_tishi = "请输入您的手机号";
var msg_person_phone_guize = "手机号格式不对噢";

var msg_person_qq_tishi = "请输入您的QQ";
var msg_person_qq_guize = "QQ格式不对噢";

var msg_person_msn_tishi = "请输入您的MSN";
var msg_person_msn_guize = "MSN格式不对噢";

//前端改版 added by yanfang 2013-1-30
var msg_person_email_kong = "电子邮箱不能为空";
var msg_person_email_weiyi = "这个邮箱已被别人占用啦";
var msg_person_email_tishi =  "用于订阅房源、找回密码";
var msg_person_email_guize =  "Email地址格式不对噢";

var msg_person_housename_guize =  "小区需要从联想列表中选择";

//===============个人中心=============================
//修改密码
// 翟健雯 2011-11-21
var msg_person_password0_tishi = "请输入原密码";
var msg_person_password0_kong  = "原密码不能为空";
var msg_person_password0_cuowu = "原密码错误";

var msg_person_password_tishi =  "输6-16个字符，可由字母、数字、标点符号等字符组成，区分大小写";
var msg_person_password_kong =  "密码不能为空";
var msg_person_password_length =  "密码长度要求6-16个字符";
var msg_person_password_guize =  "密码仅可由字母、数字、标点符号等字符组成";
var msg_person_repassword_tishi =  "6-16个字符，可由字母、数字、标点符号等字符组成，区分大小写";
var msg_person_repassword_kong =  "确认密码不能为空";
var msg_person_repassword_guize =  "前后输入的密码不一致";

var msg_person_yanzheng_guize = "验证码错误，请重新输入";
var msg_person_yanzheng_kong = "验证码不能为空";
var msg_person_yanzheng_tishi = "请输入验证码";

var msg_person_xieyi_kong = "您未选中同意用户协议吧";
var msg_personal_suc = '<span class="cureRight"></span>';//提示正确的样式

//快速注册tishi added by yanfang 2013-2-28
var msg_person_accname_ks_tishi = "4-30个字符，并以英文字母开头";
var msg_person_password_ks_tishi =  "6-16个字符，区分大小写";

//=============== 样式名 =============================
var class_personal_init_notice0 = "txt";
var class_personal_init_notice = "flag";
var class_personal_focus_notice0 = "txt bor_blue";
var class_personal_focus_notice = "cure";
var class_personal_error_notice0 = "txt bor_red";
var class_personal_error_notice = "cureRed";

//=============== 正则 =============================
var reg_personal_password = /^[0-9A-Za-z~!@#\$%\^&\*\(\)_\+`\-\\=\[\];',\.\/\{\}\|:"<>\?]{6,16}$/;
var reg_personal_username = /^[a-zA-Z]\w{3,29}$/;
var reg_email = /^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
var reg_phone = /^[1][3|4|5|8]\d{9}$/;