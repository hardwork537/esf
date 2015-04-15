/**
 * @description util.js
 * @创建人： 陈德昭
 * @date 2011-03-08
 */
var Utils = new Object();

/**
 * 去除字符串空格
 */
Utils.trim = function(text) {
    return text.replace(/(^\s*)|(\s*$)/g, "");
}

/**
 * 去除字符串左空格
 */
Utils.ltrim = function(text) {
    return text.replace(/(^\s*)/g, "");
}

/**
 * 去除字符串右空格
 */
Utils.rtrim = function(text) {
    return text.replace(/(\s*$)/g, "");
}

/**
 * 返回字符串的实际长度, 一个汉字算2个长度
 */
Utils.len = function(text) {
    return text.replace(/[^\x00-\xff]/g, "**").length;
}

/**
 * 是否是有效邮箱
 */
Utils.isEmail = function(email) {
    var email_format = /^(\d|[a-zA-Z])+(-|\.|\w)*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    return email_format.test(email);
}

/**
 * 是否是有效QQ
 */
Utils.isQQ = function(qq) {
    var qq_format = /^[1-9][0-9]{4,12}$/;
    return qq_format.test(qq);
}

/**
 * 是否是有效密码
 */
Utils.isPassword = function(str) {
	var pattern = /^[\w-]{6,16}$/;
    return pattern.test(str);
}

/**
 * 是否是中文字符
 */
Utils.isChinese = function(str) {
    var regexp = /^[\u4e00-\u9fa5]*$/g;
    return regexp.test(str);
}

/**
 * 是否是数字
 */
Utils.isDigit = function(str) {
    var regexp = /^[0-9]+$/;
    return regexp.test(str);
}

/**
 * 是否是整数
 */
Utils.isInteger = function(str) {
    var regexp = /^(-|\+)?\d+$/;
    return regexp.test(str);
}

/**
 * 是否是小数
 */
Utils.isFloat = function(str) {
    var regexp = /^(-|\+)?\d+\.{0,1}\d+$/;
    return regexp.test(str);
}

/**
 * 是否是有效邮编
 */
Utils.isPostalCode = function(str) {
    var regexp = /(^[0-9]{6}$)/;
    return regexp.test(str);
}

/**
 * 是否是有效电话
 */
Utils.isPhone = function(str) {
    var regexp = /(^[0-9]{3,4}\-[0-9]{3,8}$)|(^[0-9]{3,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)/;
    return regexp.test(str);
}

/**
 * 是否是有效手机号
 */
Utils.isMobile = function(str) {
    var regexp = /(^0{0,1}1[0-9]{10}$)/;
    return regexp.test(str);
}

/**
 * Cookie操作
 */
Utils.cookie = new Object();
Utils.cookie.domain = 'focus.cn';
Utils.cookie.path = '/';
Utils.cookie.hours = 24;//默认1天

/**
 * 设置cookie
 */
Utils.cookie.setCookie = function(name,value,hours,domain,path) {
    hours = hours ? hours : Utils.cookie.hours;
    domain = domain ? domain : Utils.cookie.domain;
    path = path ? path : Utils.cookie.path;
    var exp  = new Date();
    exp.setTime(exp.getTime() + hours*3600000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";domain=" + domain + ";path=" + path;
}

/**
 * 取得cookie
 */
Utils.cookie.getCookie = function(name) {
    var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return null;
}

/**
 * 删除cookie
 */
Utils.cookie.delCookie = function(name,domain,path) {
    domain = domain ? domain : Utils.cookie.domain;
    path = path ? path : Utils.cookie.path;
    var exp = new Date();
    exp.setTime(exp.getTime() - 3600000);
    document.cookie = name + "=0" + ";expires=" + exp.toGMTString() + ";domain=" + domain + ";path=" + path;
}