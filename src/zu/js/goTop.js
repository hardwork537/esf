//返回顶部
$(document).ready(function(){
	$("#gotopbtn a").click(function(){ //向上按钮绑定单击事件
		$("html, body").animate({ scrollTop  : 0} , 400);
		return false;
	})
								   
	function goTop()
	{
		var scrollTop2=$(document).scrollTop(); 
		if(scrollTop2>360)
		{	
			if(window.navigator.userAgent.indexOf('MSIE 6')!=-1)
			{
				//IE6
				$("#gotopbtn a").css("position","absolute");
				$("#gotopbtn a")[0].style.top=$(document).scrollTop()+360+'px';
				
			}
			else
			{ 
				$("#gotopbtn a").css("position","fixed");
			}
			$("#gotopbtn a").css("display","block");
		}
		else
		{
			$("#gotopbtn a").css("position","");
			$("#gotopbtn a").css("display","none");
		}
	};
	addScrollEvent(goTop);
			
});

//滚动

function addScrollEvent(fn)
{
	var oldscroll = window.onscroll;
	if(typeof window.onscroll!="function")
	{
		window.onscroll = fn;
	}
	else
	{
		window.onscroll = function()
		{
			oldscroll();
			fn();
		}
	}
}
