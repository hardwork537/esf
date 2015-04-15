//页眉部分快速导航
function showQuick(aid,did){
    var obj = document.getElementById(aid);
    var divotherChannel=document.getElementById(did);
    obj.className = "menu_btn hover";
    divotherChannel.style.display = "block";
}
function hideQuick(aid,did){
    var divotherChannel=document.getElementById(did);
    var mydd=document.getElementById(aid);
    if(divotherChannel.style.display!="none"){
		divotherChannel.style.display="none";
		mydd.className="menu_btn";
    }
}

//切换城市
$(function(){
	$(".select_city em").toggle(
	
	function(event){
		//event.stopPropagation();	
		var $cityArea = $(this).parent().parent().find(".cityArea_k");			   
		if(!$cityArea.is(":animated")){//判断是否处于动画
			$cityArea.fadeIn();
			$(this).addClass("selectIng");
			
		}
	},function(){
		var $cityArea = $(this).parent().parent().find(".cityArea_k");
		if(!$cityArea.is(":animated")){//判断是否处于动画
			$cityArea.fadeOut();
			$(this).removeClass("selectIng");
		}	
	});
});	

//处理文本域	
$(function(){
	 $(":input[type=text]").focus(function(){
	 $(this).addClass("focus");
	 if($(this).val() ==this.defaultValue){  
                  $(this).val("");
				  $(this).css("color","#333");
 } 
}).blur(function(){
		$(this).removeClass("focus");
		if ($(this).val() == '') {
				  $(this).val(this.defaultValue);
				  $(this).css("color","#adadad");
		 }
		});
})

//搜索框
$(function(){
	//点击显示热门搜索
	$("#hot_house_hit").toggle(
	  function () {
	    $(this).addClass("active");
	    $("#suggest_d").hide();
	    $("#hot_house").show();
	  },
	  function () {
	    $(this).removeClass("active");
	    $("#hot_house").hide();
	    if($("#suggest_d ul li").length > 0 && $("#suggest_d").is(":hidden")){
	    	$("#suggest_d").show();
	    }	    
	  }
	); 	
	$(".options span").click(
		function () {		 
			if(!$(this).parent().find(".hide_box").is(":animated")){//判断是否处于动画
				$(this).parent().parent().find("span").removeClass("selectHand");
				$(this).addClass("selectHand");
				
				$(".hide_box").hide();
				$(this).parent().find(".hide_box").fadeIn();
				//$(".hide_box").not($(this).find(".hide_box")).fadeOut(0);
				return false;
			}			 
		});
	$(".hide_box").click(function(){
		return false;							  
	})
	
	$(".hide_box a").click(function(event){
		var text = $(this).attr("dname");
		var svalue = $(this).attr('val');
		var $hideBoxA = $(this).parent().parent(); 
		
		$hideBoxA.parent().find("span").html(text);
		$hideBoxA.children().find("a").removeClass("active");
		$hideBoxA.parent().find("span").removeClass("selectHand");
		$(this).addClass("active");
		//$(this).parent().parent("#select_list").find("input[type=hidden]").val(svalue);
		//$hideBoxA.children("input[type=hidden]").val(svalue);
		$hideBoxA.fadeOut();
		
		return false;
	});
	
	$(document).click(function(event){
		//event.stopPropagation();
		$(".hide_box").fadeOut(200);
		$(".box_menu span").removeClass("selectHand");
		$(".cityArea_k").fadeOut(200);
		$(".select_city em").removeClass("selectIng");
	});
})

//焦点图
$(function(){
	//转换为页面css的布局格式
	$("#ifocus_piclist ul li").each(function(i){
		//广告那边js返回来的代码:<div class="tc" style="" id="focus01"><a target="_blank" href="http://bj.esf.focus.cn"><img border="0" src="http://static1.f.itc.cn/bj/esf/src/201307/2400947_24_140118.jpg"></a><br><a target="_blank" href="http://bj.esf.focus.cn">测试广告第1贞</a></div>
		//转换后的内容格式： <a target="_blank" href="http://bj.esf.focus.cn"><img border="0" src="http://static1.f.itc.cn/bj/esf/src/201307/2400947_24_140118.jpg"><p>测试广告第1贞</p></a><s></s>
		var strCon = $(this).find("div").html();
		var arrCon = new Array();
		if($.trim(strCon) == ""){
			$(this).remove();
		}else{
			arrCon = strCon.split(/<br>/i);
			if(typeof(arrCon[1]) !== "undefined"){
				arrCon[1] = arrCon[1].replace(/<(?:.|\s)*?>/g, "");
				strCon = "<p>" + arrCon[1] + "</p></a>";
			}else{
				strCon = "</a>";
			}
			arrCon[0] = arrCon[0].replace(/<\/a>/i, strCon);
			$(this).html(arrCon[0]+"<s></s>");
			$("#ifocus_btn").append("<span></span>");
		}
	});
	$("#ifocus_btn span").eq(0).addClass("curr");
	$("#ifocus_piclist ul li").eq(0).show();
	
	var temp = 0;
	var old = 0;
	var timer = null;
	$(".blockALA .lab span").mouseenter(function(){
											
		temp = $(this).index();
		if(old == $(this).index())
		{
			return;
		}
		else
		{
			tab()
		}
	})
	function tab()
	{
		old  = temp;
		$(".blockALA .lab span").removeClass("curr");
		$(".blockALA .lab span").eq(temp).addClass("curr");
		$(".blockALA .img li").hide();
		$(".blockALA .img li").eq(temp).show();
	}
	function next()
	{
		temp++;
		if(temp==$(".blockALA .lab span").length)
		{
			temp=0;
		}
		tab();
	}
	timer = setInterval(next,3000)
	
	$(".blockALA").mouseover(function(){
		clearInterval(timer);										   
	})
	
	$(".blockALA").mouseout(function(){
		timer = setInterval(next,3000)								   
	})
})

//按钮
$(function(){
	$(".btn").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});	
});