function getStyle(obj, name)
{
	if(obj.currentStyle)
	{
		return obj.currentStyle[name];
	}
	else
	{
		return getComputedStyle(obj, false)[name];
	}
}

function startMove(obj, name, iTarget, fnEnd)
{
	clearInterval(obj.timer);
	obj.timer=setInterval(function (){
		if(name=='opacity')
		{
			var cur=Math.round(parseFloat(getStyle(obj, name))*100);
		}
		else
		{
			var cur=parseInt(getStyle(obj, name));
		}
		
		var speed=(iTarget-cur)/6;
		speed=speed>0?Math.ceil(speed):Math.floor(speed);
		
		if(cur==iTarget)
		{
			clearInterval(obj.timer);
			if(fnEnd)
			{
				fnEnd();
			}
		}
		else
		{
			if(name=='opacity')
			{
				obj.style.filter='alpha(opacity:'+(cur+speed)+')';
				obj.style.opacity=(cur+speed)/100;
			}
			else
			{
				obj.style[name]=cur+speed+'px';
			}
		}
	}, 30);
}

function scroolImg()
{
	var oImgBox = document.getElementById("gdPicBd");
	var oBtn01 = document.getElementById("scrollArrLeft");
	var oBtn02 = document.getElementById("scrollArrRight");
	var oUl = oImgBox.getElementsByTagName("ul")[0];
	var aLi = oUl.getElementsByTagName("li");
	var oImgNum = document.getElementById("srocallImgNum");
	
	var now= 0;
	var start=0;
	var end=0;
	
	oUl.style.width=aLi.length*aLi[0].offsetWidth+'px';
	oImgNum.innerHTML="<em>"+(now+1)+"</em><strong>/</strong>"+aLi.length;
	
	function scrollauto()
	{
		
		if(now>end)
		{
			start++;
			end++;
		}
		else if(now<start)
		{
			start--;
			end--;
		}
		
		startMove(oUl, 'left', -start*160);
	
	}

	setInterval(function(){
		tab();
		
		scrollauto();
		
			
	},5000)

	oBtn01.onclick=function ()
	{
		now--;
		
		if(now==-1)
		{
			now=aLi.length-1;
			
			start=aLi.length-1;
			end=aLi.length-1;
		}
		
		oImgNum.innerHTML="<em>"+(now+1)+"</em><strong>/</strong>"+aLi.length;
		scrollauto();
	};
	
	oBtn02.onclick=function ()
	{
		tab();
		scrollauto();
	};
	
	function tab()
	{
		now++;
		
		if(now==aLi.length)
		{
			now=0;

			start=0;
			end=0;
		}
		oImgNum.innerHTML="<em>"+(now+1)+"</em><strong>/</strong>"+aLi.length;
	};

};

scroolImg();