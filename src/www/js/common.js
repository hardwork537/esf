$(function(){
    var url = window.location.href;
    if (url.indexOf("my.esf.focus.cn") === -1){
        $.ajax({
            url:'/ajax/getUserInfo',
            type:'get',
            success:function(data){
                if (data){
                    $("#hd_login").html(data);
                    /* header login and register */
                    $("#hd_login").find(".menu").hover(function(){
                        $(this).addClass("menu_hover");
                        $(this).find(".menu_bd").show();
                        if($("#global_top_nav").length > 0){
                            $(this).find("s").hide();
                            if($(this).hasClass("login")){
                                $(this).next(".menu").find("s").hide();
                            }
                        }
                    },function(){
                        $(this).removeClass("menu_hover");
                        $(this).find(".menu_bd").hide();
                        if($("#global_top_nav").length > 0){
                            $(this).find("s").show();
                            if($(this).hasClass("login")){
                                $(this).next(".menu").find("s").show();
                            }
                        }
                    });

                }
            }
        });
    }
    else{
        $("#hd_login").find(".menu").hover(function(){
            $(this).addClass("menu_hover");
            $(this).find(".menu_bd").show();
            if($("#global_top_nav").length > 0){
                $(this).find("s").hide();
                if($(this).hasClass("login")){
                    $(this).next(".menu").find("s").hide();
                }
            }
        },function(){
            $(this).removeClass("menu_hover");
            $(this).find(".menu_bd").hide();
            if($("#global_top_nav").length > 0){
                $(this).find("s").show();
                if($(this).hasClass("login")){
                    $(this).next(".menu").find("s").show();
                }
            }
        });
    }
	
	/* header city select */
	var $hdCity = $("#hd_city"),
		$cityName = $hdCity.find(".city_name"),
		$cityList = $hdCity.find(".city_list"),
		$arrow = $cityName.find("i");
	$cityName.click(function(){
		if (!$cityList.is(":animated")){
			$cityList.fadeIn("fast");
			$arrow.removeClass("down").addClass("up");
		}
	});
	
	$(document).bind("click", function (e) {
        var target = $(e.target);
		if (target.closest($cityList).length == 0) {
            if (!$cityList.is(":animated")) {
                $cityList.fadeOut("fast");
				$arrow.removeClass("up").addClass("down");
            }
        }
    });
	
	/* global nav */
	if($("#site_nav").length > 0){
		$("#site_nav").find("li:not('.on')").hover(function(){
			$(this).next().addClass("nobg");
		},function(){
			$(this).next().removeClass("nobg");
		});
		$("#site_nav").find("li.on").next().addClass("nobg");
	}
	
	/* goto top */
	$("#toTop").click(function(){
		$("html,body").animate({scrollTop:0},120);
	})
	$(window).bind("scroll",function(){
		var toTop = $("#toTop");
		var scrollTopH = $(document).scrollTop();
		var windowH = $(window).height();
		(scrollTopH > 0) ? toTop.show() : toTop.hide();//判断滚动条是否有变化		
	});
	
	/* start agent 全区域可点 */
	agentClick();
});
function agentClick(){
	var $items = $("#starAgent_mod .items");
	$items.each(function(){
		$(this).hover(function(){
			$(this).addClass("hover");
		},function(){
			$(this).removeClass("hover");
		});
		
		$(this).find('a').each(function(){
			$(this).click(function(e){
				if(e.stopPropagation){
					e.stopPropagation();
				}else{
					window.event.cancelable = true;
				}
			});
		});		
		
		$(this).click(function(){
			var a = document.createElement("a");
			a.href = $(this).find('a.link').attr('href');
			a.target = "_blank";
			document.body.appendChild(a);
			a.click();
		});		
	});
}

/* list top filters */
function topFilters(){
	var $inputData = $("#topFilters .input_data");
	var $inputTip = $("#topFilters .input_tip");
	var $inputBtn = $("#topFilters .input_btn");
	var $searchInput = $("#topFilters .search_txt");
	
	if(navigator.userAgent.indexOf("MSIE 6.0") > 0){
		$inputBtn.hover(function(){
			$(this).addClass("input_btn_hover");
		},function(){
			$(this).removeClass("input_btn_hover");
		});
	}
	
	$inputData.focus(function(){
		$(this).addClass("input_focus");
	}).blur(function(){
		$(this).removeClass("input_focus");
	});

	$inputBtn.click(function(){
		var flag = 0;
		var that = this;
		$(that).siblings(".input_data").each(function () {
			var value = $(this).val();
			var reg = /^[0-9]*[1-9][0-9]*$/;
			if (value == '' || !reg.test(value)) {
				$(this).addClass("input_error");
				flag = 1;
			} else {
				$(this).removeClass("input_error");
			}
		});
		
		if (flag == 1) return false;
		
		var minValue = $(this).parents(".input_wrap").find("input[data-value='min']").val();
		var maxValue = $(this).parents(".input_wrap").find("input[data-value='max']").val();
		var type = $(this).parents(".input_wrap").attr('data-type');
		var tongji = $(this).parents(".input_wrap").attr('tongji');
		if (maxValue == ''){
			maxValue = type == 'price' ? 99999 : 9999;
		}
		if (minValue == ''){
			minValue = 1;
		}
		if (parseInt(minValue) > parseInt(maxValue)) {
			var tmp = minValue;
			minValue = maxValue;
			maxValue = tmp;
		}
		jumpUrl(minValue,maxValue,type,tongji);
	});
	
	var $itemsDrop = $("#topFilters .item_drop");
    $itemsDrop.hover(function(){
		if (!$(this).find(".item_drop_list").is(":animated")) {//判断是否处于动画
			$(this).find(".item_drop_btn").addClass("hover");
			$(this).find(".item_drop_list").show().siblings().find(".item_drop_list").hide();
		}
	}, function () {
		if (!$(this).find(".item_drop_list").is(":animated")) {//判断是否处于动画
			$(this).find(".item_drop_btn").removeClass("hover");
			$(this).find(".item_drop_list").hide();
		}
	});
	
	var checkbox = $("#topFilters .checkbox");
	var checkboxInput = $("#topFilters .feature input");
	$(checkbox).hover(function(){
		$(this).addClass("checkbox_hover");
	},function(){
	   	$(this).removeClass("checkbox_hover"); 
	});

	$(checkboxInput).click(function(){
		if ($(this).is(":checked")) {
			$('label[for='+$(this).attr('id')+']').addClass('checkbox_checked');
		}else {
			$('label[for='+$(this).attr('id')+']').removeClass('checkbox_checked');
		}
	});
	
	$searchInput.focus(function(){		
		if($(this).val() == this.defaultValue){
			$(this).val("").removeClass("search_txt_default");
		}
	}).blur(function(){
		if($(this).val() == ""){
			$(this).val(this.defaultValue).addClass("search_txt_default");
		}
	});
	
}

function onlyNum(event){//只允许输入数字键和回格键、删除键
	var e = window.event || event;  
	var key = e.keyCode || e.which;  
	if(!((event.keyCode >= 48&&event.keyCode <= 57)||(event.keyCode >= 96&&event.keyCode <= 105) || key == 8 || key == 46)){
		if(window.event){//ie
			e.returnValue = false;
		}
		else e.preventDefault();//firefox
	}
}

function jumpUrl(minValue,maxValue,type, tongji){
	var new_url = window.location.href;
	switch(type){
		case 'price':
			new_url = /^(.+)(j[0-9]+(-[0-9]+){0,1})(.*)$/i.test(new_url)
				? new_url.replace(/^(.+)(j[0-9]+(-[0-9]+){0,1})(.*)$/i, '$1j'+minValue+'-'+maxValue+'$4')
				: new_url.replace(/^([^?]+)(\??[^?]*)$/i, '$1j'+minValue+'-'+maxValue+'$2');
			break;
		case 'area':
			new_url = /^(.+)(m[0-9]+(-[0-9]+){0,1})(.*)$/i.test(new_url)
				? new_url.replace(/^(.+)(m[0-9]+(-[0-9]+){0,1})(.*)$/i, '$1m'+minValue+'-'+maxValue+'$4')
				: new_url.replace(/^([^?]+)(\??[^?]*)$/i, '$1m'+minValue+'-'+maxValue+'$2');
			break;
	}
	if (tongji ==  1){
		new_url = new_url.replace('from=sale_sdsx&spm=o-o-o-18-o','');
		var indexUrl = new_url.indexOf('?');
		if (indexUrl === -1 || indexUrl == new_url.length - 1){
			new_url = new_url.replace('?','');
			new_url = new_url + '?from=sale_sdsx&spm=o-o-o-18-o';
		}
		else{
			new_url = new_url + '&from=sale_sdsx&spm=o-o-o-18-o';
		}
	}
	window.location.href = new_url;
}


/* tab鼠标滑过切换 */
function tabs(obj1, obj2){
	$(obj1).hover(function(){
		var idx = $(obj1).index(this);
		$(obj1).removeClass('on');
		$(this).addClass('on');
		$(obj2).hide();
		$(obj2).eq(idx).show();
	});
}


/* ie6 hover */
$(function(){

    /* sideBarFixed for All guide single-page  */
    function sideBarFixed(){
        var sideBar = $("#sideBarInner"),
            top1 = sideBar.offset().top,
            main = $(".main"),
            main_height = main.height(),
            sideBar_height = sideBar.height();

        (main_height > sideBar_height) && $(window).scroll(function(){
            var scrollTop = $(window).scrollTop(),
                top2 = main_height + main.offset().top - sideBar_height,
                ie6 = !!window.ActiveXObject && !window.XMLHttpRequest,
                style = sideBar[0].style,
                dom = '(document.documentElement || document.body)';

            if(scrollTop > top1 && scrollTop < top2){
                ie6 ? sideBar.css({position: "absolute",top:style.setExpression('top', 'eval(' + dom + '.scrollTop'+') + "px"')}) : sideBar.css({position: "fixed",top: 0});
            }else{
                if(scrollTop > top2){
                    ie6 ? sideBar.css({position: "absolute",top:style.setExpression('top', 'eval(' + top2 + ') + "px"')}) : sideBar.css({position: "absolute",top: top2});
                }else{
                    ie6 ? sideBar.css({position: "absolute",top:style.setExpression('top', 'eval(' + top1 + ') + "px"')}) : sideBar.css({position: "absolute",top: top1});
                }
            }
        });
    }
    $("#sideBarInner").length && sideBarFixed();
    /*fixedBottom*/
    function fixedBottm(){
        var bottom = $('#bottomFixed'),
            close = $('#bottomClose'),
            erroClose = $('#erroClose'),
            errorContainer = $('#errorContainer'),
            ipt = $('#inputTel'),
            sell = $('#bottomSellHouse');

        function reg(){
            var tel = ipt.val(),
                telReg = !!tel.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/);
            return telReg;
        }

        close&&close.click(function(){
            bottom.hide();
            $.cookie("closeButtomFlag",1,{"expires":70,"domain":"esf.focus.cn",'path':'/'});
        });

        ipt&&ipt.blur(function(){
            if(reg() === false){
                errorContainer.show();
                ipt.addClass('error');
            }else{
                ipt.removeClass('error');
                errorContainer.hide();
            }
        });

        erroClose&&erroClose.click(function(){
            errorContainer.hide();
            ipt.removeClass('error');
            ipt.focus();
        });

        sell&&sell.click(function(){
            if(reg() === false){
                errorContainer.show();
                ipt.addClass('error');
            }else{
                ipt.removeClass('error');
                errorContainer.hide();
                window.open('http://my.esf.focus.cn/index.php?action=add&do=sale&phone='+ipt.val(), '_self','');
            }
        });
    }
    //fixedBottm();

	if(navigator.userAgent.indexOf("MSIE 6.0")>0){
		/* header search button */
		$("#hdSearchBox .btn").hover(function(){
			$(this).addClass("btn_hover");
		},function(){
			$(this).removeClass("btn_hover");
		});

        /* popup */
        $('.popup .mask').css({
            'position' : 'absolute',
            'height' : $(document).height()
        });
        var top = $(document).scrollTop();
        $('.popup .popup_box').css({
            "position" : "absolute",
            "top" : top + 150
        });
        $(window).scroll(function() {
            var top = $(this).scrollTop();
            $('.popup .popup_box').css({
                "top" : top + 150
            });
        });
		
		/* footer_xqHot */
		$(".footer_xqHot .pc li").hover(function(){
			$(this).addClass("hover");
		},function(){
			$(this).removeClass("hover");
		});
		
	}
})


/* 添加收藏夹 */
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

/* 设置主页 */
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

/* 设置桌面 */
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


/* 关闭弹出框 */
function popupClose(){
    $(".popup").hide();
}