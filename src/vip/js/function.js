// JavaScript Document
//添加收藏夹
function AddFavorite(sURL, sTitle) {
	try {
		window.external.addFavorite(sURL, sTitle);
	} catch (e) {
		try {
			window.sidebar.addPanel(sTitle, sURL, "");
		} catch (e) {
			alert("加入收藏失败，请使用Ctrl+D进行添加");
		}
	}
}

//设置主页
function setHomepage(B) {
	if (document.all) {
		document.body.style.behavior = "url(#default#homepage)";
		document.body.setHomePage(B);
	} else {
		if (window.sidebar) {
			if (window.netscape) {
				try {
					netscape.security.PrivilegeManager
							.enablePrivilege("UniversalXPConnect");
				} catch (C) {
					alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config并回车,然后将项 signed.applets.codebase_principal_support 值该为true");
				}
			}
			var A = Components.classes["@mozilla.org/preferences-service;1"]
					.getService(Components.interfaces.nsIPrefBranch);
			A.setCharPref("browser.startup.homepage", B);
		} else {
			alert("您所使用的浏览器暂不支持此功能。您需要手动将【" + B + "】设置为首页。");
		}
	}
	return false;
}

//设置桌面
function toDesktop(F, D, C) {
	try {
		var B = new ActiveXObject("WScript.Shell");
		var A = B.CreateShortcut(B.SpecialFolders("Desktop") + "\\" + F
				+ ".url");
		A.TargetPath = D;
		A.Save();
	} catch (E) {
		location.href = C;
	}
	return false;
}

//20140915 公共头部下拉列表
$(function(){
	$("#head").find(".snav").hover(function(){
		$(this).addClass("snav_hover");
		$(this).find(".snav_bd").show();
	},function(){
		$(this).removeClass("snav_hover");
		$(this).find(".snav_bd").hide();
	});
	
	$("#head").find(".snav_bd .p_info2").hover(function(){
		$(this).addClass("p_hover");
	},function(){
		$(this).removeClass("p_hover");
	});

    var isIE = !!window.ActiveXObject,
        isIE6 = isIE && !window.XMLHttpRequest,
        isIE8 = isIE && !!document.documentMode,
        isIE7 = isIE && !isIE6 && !isIE8;
    if(isIE6 || isIE7 || isIE8){
        $(".list_def").find(".item").hover(function(){
            $(this).addClass("hover");
        },function(){
            $(this).removeClass("hover");
        });
    }
});
$(function(){
	$(".newsList li").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});
});

//鼠标跟随效果
//获取鼠标坐标
function mousePos(e){
	var x,y;
	var e = e||window.event;
		return {
			x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft,
			y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop
		};
	};
//跟随鼠标移动框，鼠标移开消失
function followMouse(event,obj,box,l,t){
	if(!arguments[3]) l = 0;
	if(!arguments[4]) t = 0;
	var top = mousePos(event).y;
	var left = mousePos(event).x;
	document.getElementById(box).style.left = left + l + "px";
	document.getElementById(box).style.top = top + t + "px";
	document.getElementById(box).style.display = "block";
		obj.onmouseout = function(){
			document.getElementById(box).style.display = "none";
	}
}