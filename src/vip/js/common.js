$(function () {
    $(".sideBar .sideBox li").hover(function () { $(this).addClass("hover"); }, function () { $(this).removeClass("hover"); })
	/* header */
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
	
})
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
//获取鼠标坐标
function mousePos(e){
	var x,y;
	var e = e||window.event;
	return {
		x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft,
		y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop
	};
};

