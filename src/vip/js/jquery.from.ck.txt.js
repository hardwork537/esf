/**
 * @abstract 执行页面验证一系列的操作判断
 * @author liuli <liliu@51f.com>
 * @date 2011-03-28
 * @lastModify By liuli 2011-03-28
 *
 */

var imgonload_right="<img src='http://src.esf.focus.cn/upload/images/img_load_right.png' />";
var imgonload_wrong="<img src='http://src.esf.focus.cn/upload/images/img_load_wrong.png' />";

//------------------------------------------独立经纪人验证---------------------------------------------//
var msg_access_printf="<span style='COLOR:#006600'> √ 可以注册！</span>";
//用户验证提示信息
var msg_accounts_format="<span style='COLOR:#ff0000'> × 用户名长度不合要求，请重新输入！</span>";
var msg_accounts_format_length="<span style='COLOR:#ff0000'>输入不合法,帐户仅限英文字母、数字、下划线！</span>";
var msg_accounts_format_sole="<span style='COLOR:#ff0000'> × 该用户名已经被注册，换个别的吧！</span>";
var msg_can_rg = "<span style='COLOR:#006600'> √ 可以注册！</span>";
var msg_accounts_format_legal="<span style='COLOR:#4574ae'>请输入5-15个字符(包括英文字母、数字、下划线)！</span>";
var msg_accounts_format_null="<span style='COLOR:#ff0000'> × 用户名不能为空！</span>";

//密码验证提示信息
var msg_passwd_format="<span style='COLOR:#ff0000'> × 密码不能为空 ！</span>";
var msg_passwd_format_length="<span style='COLOR:#4574ae'>密码长度为6-16位，仅限英文字母、数字、字符！</span>";
var msg_passwd_format_compare="<span style='COLOR:#ff0000'>两次输入密码不一致！</span>";
var msg_passwd_format_prompt="<span style='COLOR:#4574ae'>请重新输入一次密码</span>";
var msg_passwd_format_length_max="<span style='COLOR:#ff0000'> × 密码长度不合要求，请重新输入！</span>";
var msg_passwd_format_legal="<span style='COLOR:#ff0000'>输入不合法,密码仅限英文字母、数字、字符！</span>";
var msg_passwd_format_compare_null="<span style='COLOR:#ff0000'> × 重复密码不能为空！</span>";

//真实姓名
var msg_truename_format="<span style='COLOR:#ff0000'> × 姓名不能为空！</span>";
var msg_truename_format_length="<span style='COLOR:#4574ae'>（备注：限制2-5个中文字）！</span>";
var msg_truename_format_legal="<span style='COLOR:#ff0000'>输入不合法,帐户仅限汉字、字母、数字、下划线！</span>";
var msg_truename_format_length_max="<span style='COLOR:#ff0000'>输入不合法,限制2-5个中文字</span>";

//手机号码
var msg_mobile_format="<span style='COLOR:#ff0000'> × 手机号不能为空！</span>";
var msg_mobile_format_length="<span style='COLOR:#4574ae'>请输入您的手机号（备注：限制11位手机段数字）！</span>";
var msg_mobile_format_legal="<span style='COLOR:#ff0000'>输入不合法,帐户仅限为数字13、15、18开头！</span>";

//公司
var msg_company_format="<span style='COLOR:#ff0000'> × 公司不能为空！</span>";
var msg_company_format_length="<span style='COLOR:#ff0000'> × 请输入关键字并在列表中选择一家公司！</span>";
var msg_company_format_legal="<span style='COLOR:#ff0000'>输入不合法,帐户仅限汉字、字母、数字、下划线！</span>";
var msg_company_format_length_max="<span style='COLOR:#ff0000'>输入不合法,帐户仅限汉字、字母、数字、下划线！</span>";

//公司简称
var msg_company_abbr_format="<span style='COLOR:#ff0000'> × 公司简称不能为空！</span>";
var msg_company_abbr_format_length="<span style='COLOR:#4574ae'> × 请输入关键字并在列表中选择一家公司！</span>";
var msg_company_abbr_legal="<span style='COLOR:#ff0000'>输入不合法,帐户仅限汉字、字母、数字、下划线！</span>";
var msg_company_abbr_format_length_max="<span style='COLOR:#ff0000'>输入不合法,帐户仅限汉字、字母、数字、下划线！</span>";

//门店
var msg_agent_format="<span style='COLOR:#ff0000'> × 门店不能为空！</span>";
var msg_agent_format_length="<span style='COLOR:#ff0000'> × 输入不合法,帐户仅限汉字、字母、数字、下划线！</span>";
var msg_agent_format_legal="<span style='COLOR:#4574ae'>请输入2-15个字，此处不用写公司名称！</span>";
var msg_agent_format_length_max="<span style='COLOR:#ff0000'>x 门店长度不合要求，请重新输入2-15个字！</span>";

//邮箱
var msg_mail_format="<span style='COLOR:#ff0000'> × 邮箱不能为空！</span>";
var msg_mail_format_length="<span style='COLOR:#ff0000'> × 该邮箱用于找回密码，请您正确填写！</span>";
var msg_mail_format_legal="<span style='COLOR:#ff0000'> × 您输入的邮箱地址格式有误！</span>";
var msg_mail_format_length_max="<span style='COLOR:#ff0000'> × 您输入的邮箱地址格式有误！</span>";

//端口
var msg_port_format="<span style='COLOR:#ff0000'> × 端口数量不能为空！</span>";
var msg_port_format_length="<span style='COLOR:#ff0000'> × 请输入您要为这个门店分配的端口个数（限制最多可填写4位数）！</span>";
var msg_port_format_legal="<span style='COLOR:#ff0000'> × 只能输入数字！</span>";
var msg_port_format_length_max="<span style='COLOR:#ff0000'> × 只能输入1-4个数字！</span>";

//CRM城区
var msg_district_format="<span style='COLOR:#ff0000'> × 城区不能为空！</span>";
var msg_district_format_length="<span style='COLOR:#ff0000'> × 输入不合法,请输入2-5个汉字！</span>";
var msg_district_format_legal="<span style='COLOR:#4574ae'><span style='COLOR:#4574ae'>请输入2-5个汉字！</span></span>";
var msg_district_format_length_max="<span style='COLOR:#ff0000'> × 城区长度不合要求，请重新输入2-5个字！</span>";

//CRM板块
var msg_hotarea_format="<span style='COLOR:#ff0000'> × 板块不能为空！</span>";
var msg_hotarea_format_length="<span style='COLOR:#ff0000'> × 输入不合法,请输入2-7个字！</span>";
var msg_hotarea_format_legal="<span style='COLOR:#4574ae'><span style='COLOR:#4574ae'>请输入2-7个字！</span></span>";
var msg_hotarea_format_length_max="<span style='COLOR:#ff0000'> × 板块长度不合要求，请重新输入2-7个字！</span>";

//CRM坐标
var msg_coord_format="<span style='COLOR:#ff0000'> × 坐标不能为空！</span>";
var msg_coord_format_length="<span style='COLOR:#ff0000'> × 输入不合法,请输入数字！</span>";
var msg_coord_format_legal="<span style='COLOR:#4574ae'>请点击下方按钮获取坐标！</span>";
//var msg_coord_format_legal="<span style='COLOR:#4574ae'><span style='COLOR:#4574ae'>请输入数字！</span></span>";
var msg_coord_format_length_max="<span style='COLOR:#ff0000'> × 请输入数字！</span>";

//CRM区域
var msg_sector_format="<span style='COLOR:#ff0000'> × 区域不能为空！</span>";
var msg_sector_format_length="<span style='COLOR:#ff0000'> × 区域名称不合法,请输入2-15个字！</span>";
var msg_sector_format_legal="<span style='COLOR:#4574ae'>请输入2-15个字！</span>";
var msg_sector_format_length_max="<span style='COLOR:#ff0000'> × 区域名称不合法，请输入2-15个字！</span>";

//CRM经济公司字典
var msg_dcompany_format="<span style='COLOR:#ff0000'> × 输入不能为空！</span>";
var msg_dcompany_format_length="<span style='COLOR:#ff0000'> × 输入不合法,请重新输入2-50个字符！</span>";
var msg_dcompany_format_legal="<span style='COLOR:#4574ae'>请输入2-100个字符！</span>";
var msg_dcompany_format_length_max="<span style='COLOR:#ff0000'> × 名称长度不合要求，请重新输入2-100个字符！</span>";
