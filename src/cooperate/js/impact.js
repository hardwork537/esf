function menuOnClick(lang,n){
var menuButton = 'menuButton';//导航的ID
var contentButton = 'contentButton';//内容的ID
var menu_alo = '';//导航激活时CLASS
var menu_none = 'form_tab li curr';//导航未激活时CLASS
for(i=1;i<=lang;i++){
document.getElementById(menuButton+i).className = menu_alo;
document.getElementById(contentButton+i).style.display = 'none';
}

document.getElementById(menuButton+n).className = menu_none;
document.getElementById(contentButton+n).style.display = 'inline-block';
}

function menuOnClick_zj(lang,n){
	var menuButton = 'menuButton_zj';//导航的ID
	var contentButton = 'menuContent_zj';//内容的ID
	var menu_alo = '';//导航激活时CLASS
	var menu_none = 'form_tab li curr';//导航未激活时CLASS

for(var j=1;j<=lang;j++){
	document.getElementById(menuButton+j).className = menu_alo;
	document.getElementById(contentButton+j).style.display = 'none';
}
	document.getElementById(menuButton+n).className = menu_none;
	document.getElementById(contentButton+n).style.display = 'inline-block';
}


function areaOnClick(lang,n){
	var menuButton = 'area';//导航的ID
	var contentButton = 'node';//内容的ID
	var menu_alo = 'is_now';//导航激活时CLASS
	var menu_none = 'none';//导航未激活时CLASS
		
	for(i=0;i<=lang;i++){
		document.getElementById(menuButton+i).className = menu_none;
		document.getElementById(contentButton+i).style.display = 'none';
	}	
	
	if(n > 0){
		document.getElementById(menuButton+n).className = menu_alo;
		document.getElementById(contentButton+n).style.display = 'inline-block';
		}
	if(n == 0){
		document.getElementById(menuButton+n).className = 'red';
		document.getElementById(contentButton+n).style.display = 'none';
		}
}


function mainMenuOnClick(n){
	var mainMenuTitle="mainMenuTitle";
	var spread = "spread";
	var wrap ="wrap";
	if(document.getElementById(mainMenuTitle + n).className == spread){
			document.getElementById(mainMenuTitle + n).className = wrap;
			}else{
				document.getElementById(mainMenuTitle + n).className = spread;
			}
	}


function inputAndCue(){
	var onButton="shouqi";
	var button_display = "is_display";
	var button_close = "is_close";
	var contentId = "guiZe_cen";
	//按钮
	if(document.getElementById(onButton).className == button_display ){
			document.getElementById(onButton).className = button_close;
			}else{
				document.getElementById(onButton).className = button_display;
			}
	//内容
	
	if(document.getElementById(contentId).style.display == "block"){
			document.getElementById(contentId).style.display = "none";
			}else{
				document.getElementById(contentId).style.display = "block";
			}
	}
function navOnClick(lang,n){
	var menuButton = 'menuButton';//导航的ID
	var contentButton = 'contentButton';//内容的ID
	var menu_alo = '';//导航激活时CLASS
	var menu_none = 'curr';//导航未激活时CLASS
	for(i=1;i<=lang;i++){
		document.getElementById(menuButton+i).className = menu_alo;
		document.getElementById(contentButton+i).style.display = 'none';
	}
	
	document.getElementById(menuButton+n).className = menu_none;
	document.getElementById(contentButton+n).style.display = 'block';
}


function OnClink(){
		var s, e = 0
		for(var i = 0; i < d; ++i){
			var m = menu[i];
			for(var j = 0; j < m.length ; ++j){
				if(this.id == m[j]){ s = i,e = j};
			}			
		}
		
		for(a in menu[s]){
			document.getElementById( menu[s][a]).className = menuClass;	
			document.getElementById( Box[s][a]).className = boxClass;		
			}
			this.className = menuClass_hover;
			document.getElementById(Box[s][e]).className = boxClass_hover;
	}		
	
	m = function(){//给所有按钮添加鼠标监听；
		for(var a = 0; a < d; ++a){
			var n = menu[a];
			for(var b = 0; b < n.length ; ++b){
				var c = document.getElementById(n[b]);
				c[mouseState] = OnClink;
				}			
			}
	}
	
	function cueOnOver(event,box){	
		document.getElementById(box).style.display = "block";
		event.onmouseout = function(){					
			document.getElementById(box).style.display = "none";			
		}
	}
	
	
function menuFix() {
        var sfEls = document.getElementById("pop_nav").getElementsByTagName("dd");
        for (var i=0; i<sfEls.length; i++) {
                sfEls[i].onmouseover=function() {
                this.className+=(this.className.length>0? " ": "") + "sfhover";
                }
                sfEls[i].onMouseDown=function() {
                this.className+=(this.className.length>0? " ": "") + "sfhover";
                }
                sfEls[i].onMouseUp=function() {
                this.className+=(this.className.length>0? " ": "") + "sfhover";
                }
                sfEls[i].onmouseout = function() {
                this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"),"");
                }
        }
}



function ulonclick(obj){
	if(obj.nodeName != "A" && obj.nodeName != "a"){
		alert(obj.nodeName);	
		}
	
	}

//=================================2011.8.12 更新部分=====================================

//鼠标经过 修改class
function onBy(hande){
	hande.className = hande.className.replace("show","");
	hande.className += " show";
	hande.className = hande.className.replace("none","");
	}
function onOut(hande){
	hande.className = hande.className.replace("none","");
	hande.className += " none";
	hande.className = hande.className.replace("show","");
	}

	
//页眉部分快速导航
function showgaoji(aid,did){
    var obj = document.getElementById(aid);
    var divotherChannel=document.getElementById(did);
    obj.className = "menu_btn hover";
    divotherChannel.style.display = "block";
}


function hideotherchannel(aid,did){
    var divotherChannel=document.getElementById(did);
    var mydd=document.getElementById(aid);
    if(divotherChannel.style.display!="none"){
            divotherChannel.style.display="none";
            mydd.className="menu_btn";
    }
}


	
//元素名称：m_n; tab_m_n  Tab菜单切换
//count:交换的个数
var $get = function(){
	var elements = new Array();
	var i = arguments.length;
	for (var i = 0; i < arguments.length; i++) {
		var element = arguments[i];
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (arguments.length == 1) {
			return element;
		}
		elements.push(element);
	}
	return elements;
}

function showtab(m,n,count){
	for(var i=1;i<=count;i++){
		$get("td_"+m+"_"+i).className = $get("td_"+m+"_"+i).className.replace("curr","");
		$get("tab_"+m+"_"+i).className = $get("tab_"+m+"_"+i).className.replace("show","hidden");
	}
	$get("td_"+m+"_"+n).className += " curr";
	$get("tab_"+m+"_"+n).className = $get("tab_"+m+"_"+n).className.replace("hidden","show");
}// Tab菜单切换结束

function busRouteOnClick(n){
	var mainMenuTitle="busRoute";
	var inFoShow = "inFoShow";
	var inFoHidden ="inFoHidden";
	if(document.getElementById(mainMenuTitle + n).className == inFoShow){
			document.getElementById(mainMenuTitle + n).className = inFoHidden;
			}else{
				document.getElementById(mainMenuTitle + n).className = inFoShow;
			}
	}


//获取鼠标坐标	
function mousePos(e){
	var x,y;
	var e = e||window.event;
		return {
			x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft,
			y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop
		};
	};
//跟随鼠标移动框，经纪人管理系统提示符
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

//列表，地图 弹出框
function onHoverPop(event,PopBox,l,t){
	if(l == ''){ l = 0};
	if(t == ''){ t = 0};
	var top = mousePos(event).y;
	var left = mousePos(event).x;
	document.getElementById(PopBox).style.left = left + l + "px";
	document.getElementById(PopBox).style.top = top + t + "px";
	document.getElementById(PopBox).style.display = "block";
}


		