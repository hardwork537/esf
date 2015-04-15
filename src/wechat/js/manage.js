Array.prototype.contains = function (element){   
	for (var i = 0; i < this.length; i++) 
	{
		if (this[i] == element)
		{   
			return true;   
		}  
	}
	return false;   
}

var tab_index = 0;
var toptab_index = 0;	
function changenum(toptab_index, tab_index) 
{
	if($(".tabshow").eq(toptab_index).children().eq(tab_index).find(".nomsg").length > 0)
	{
		$("#btn_up").hide();
		$(".wrapper").css('padding-bottom','0');
	}
	else
	{
		var upnum_all = 0;
		var checkednum = $("#scroller > div").eq(toptab_index).find(".houselist").eq(tab_index).find(".LabelSelected").length;
		if (tab_index == 1) {
			upnum_all = $("input[name='hdLeftOnlineNum']").eq(toptab_index).val();
			$("#btn_up").html("上 架 ( " + checkednum + "/" + upnum_all + " )");
		} else {
			$("#btn_up").html("下 架 ( " + checkednum + " )");
		}
		$(".wrapper").css('padding-bottom','40');
		$("#btn_up").show();
		if (checkednum != 0) {
			$("#btn_up").removeClass("turngray");
		} else {
			$("#btn_up").addClass("turngray");
		}
	}
}

function unitUpAndDown()
{
	if(!$("#btn_up").hasClass("turngray"))
	{
		var unitids = '';
		var unitType = $("#scroller > div").eq(toptab_index).attr("ut");
		var actType = tab_index == 0 ? 'offline' : 'online';
		$("#scroller > div").eq(toptab_index).find(".houselist").eq(tab_index).find(".LabelSelected").each(function(){
			unitids += $(this).attr("unitid") + ',';
		});
		if(unitids != '')
		{
			var ldImgUrl = $("#btn_up").attr('ldimg');
			$("#btn_up").prepend("<img src=\""+ldImgUrl+"\"/>");

			unitids = unitids.substr(0, unitids.length - 1);
			$.ajax({
				type: "POST",
				url: "ajax",
				ansyc: false,
				data: {"do": "unitupdown", "unitids": unitids, "unittype": unitType, "acttype": actType},
				success: function(data){
					var jsonData = eval("(" + data + ")");
					if(jsonData.status == -1)
					{
						window.location.href = jsonData.url;
					}
                    else if(jsonData.status == -2)
                    {
                        showMsg(jsonData.content);   //显示报错提示
                        setTimeout(function(){location.reload(true);},3000);   //延迟显示报错提示
                    }
					else if(jsonData.status == 1)
					{
						/*把操作成功的房源信息从对应的列表删除，将另一个列表清空*/
						var onlineNum = 0;
						var offlineNum = 0;
						var successIds = jsonData.successIds;
						var arrUnitid =  successIds.split(",");
						if(jsonData.successNum < arrUnitid.length)
						{
							arrUnitid = arrUnitid.slice(0, jsonData.successNum);
						}
						
						$("#scroller > div").eq(toptab_index).find(".houselist").eq(tab_index).find(".LabelSelected").each(function(){
							if(arrUnitid.contains($(this).attr("unitid")))
							{
								$(this).parent().parent().remove();
							}
						});
						$("#scroller > div").eq(toptab_index).find(".tabshow > ul").eq(tab_index).siblings().empty();
						
						
						if(tab_index == 0)
						{
							onlineNum = parseInt($("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(0).find("i").html()) - parseInt(jsonData.successNum);
							offlineNum = parseInt($("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(1).find("i").html()) + parseInt(jsonData.successNum);
						}
						else
						{
							onlineNum = parseInt($("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(0).find("i").html()) + parseInt(jsonData.successNum);
							offlineNum = parseInt($("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(1).find("i").html()) - parseInt(jsonData.successNum);
						}
						$("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(0).find("i").html(onlineNum);
						$("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(1).find("i").html(offlineNum);
						$("input[name='hdLeftOnlineNum']").eq(toptab_index).val(jsonData.leftOnlineNum);
						changenum(toptab_index, tab_index);
					}
					else if(jsonData.status == -100)
					{
						location.reload(true);
					}
				}
			});
		}
	}
}

var initFunc = function () {
	
	/*初始化toptab_index变量*/
	$(".toptab > li").each(function () {
		var index = $(this).index();
		if ($(this).hasClass("active")) {
			$("#scroller").children().eq(index).show().siblings().hide();
			toptab_index = $(this).index();
		}
	});
	
	/*初始化tab_index变量*/
	$("#scroller > div").eq(toptab_index).find(".toptab2 > li").each(function () {
		var index = $(this).index();
		if ($(this).hasClass("active")) {
			$(".tabshow").eq(toptab_index).children().eq(index).show().siblings().hide();
			tab_index = index;
		}
	});
	/*绑定父级标签点击事件*/
	$(".toptab > li").click(function () {
		$(this).addClass("active").siblings().removeClass("active");
		toptab_index = $(this).index();
		$("#scroller").children().eq(toptab_index).show().siblings().hide();
		$("#scroller").children().eq(toptab_index).find(".toptab2 li").each(function () {
			if ($(this).hasClass("active")) {
				tab_index = $(this).index();
				var subTabContent = $.trim($(".tabshow").eq(toptab_index).children().eq(tab_index).html());
				if(subTabContent == '')
				{
					$("#btn_up").hide();
					$(".tabshow").eq(toptab_index).children().eq(tab_index).siblings().hide();
					$("#scroller").children('img').show();
					var objJson = {};
					var topTab = $("#scroller > div").eq(toptab_index).attr("ut");
					var subTab = ['online','offline'];
					$.ajax({
						type:"POST",
						url:"ajax",
						data:{"do":'getupdownlist', "toptab":topTab, "subtab":subTab[tab_index]},
						success:function(data){

                            var index1=data.indexOf('!#')+2;
                            var index2=data.indexOf('#!');
                            var str =data.substring(index1,index2);
                            var arrContent = str.split('|');

							if(data.indexOf('status') < 0)
							{
								//var arrContent = data.split('|'+String.fromCharCode(16)+'|');
								objJson = {status:'1', content:arrContent[0], onlinenum:arrContent[1], offlinenum:arrContent[2],leftonlinenum:arrContent[3]};
							}
							else
							{
								objJson = eval('('+data+')');
							}

							if(objJson.status == -1)
							{
								window.location.href = objJson.url;
								return false;
							}
							else if(objJson.status == -100)
							{
								location.reload(true);
								return false;
							}
							else
							{
								//subTabContent = objJson.content;
								$("#scroller").children('img').hide();
                                $("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(0).find("i").html(arrContent[0]);
								$("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(1).find("i").html(arrContent[1]);
								$(".tabshow").eq(toptab_index).children().eq(tab_index).html(data);
								$("#scroller").children('img').hide();
								//$(".tabshow").eq(toptab_index).children().eq(tab_index).show();
								
								$(".toptab > li").unbind("click");
								$(".toptab2 li").unbind("click");
								$(".mask").unbind("click");
								$(".warn").unbind("click");
								$("input[name='hdLeftOnlineNum']").eq(toptab_index).val(arrContent[2]);
								initFunc();
								//$("#btn_up").show();
								$(".tabshow").eq(toptab_index).children().eq(tab_index).show().siblings().hide();
							}

						}
					});
				}
				else
				{
					$(".tabshow").eq(toptab_index).children().eq(tab_index).show().siblings().hide();
					changenum(toptab_index, tab_index);
				}
			}
		});
		
	});
    /*绑定子标签点击事件*/
	$(".toptab2 li").click(function () {
		$(this).addClass("active").siblings().removeClass("active");
		tab_index = $(this).index();
		var subTabContent = $.trim($(".tabshow").eq(toptab_index).children().eq(tab_index).html());
		if(subTabContent == '')
		{
			$("#btn_up").hide();
			$(".tabshow").eq(toptab_index).children().eq(tab_index).siblings().hide();
			$("#scroller").children('img').show();
			
			var objJson = {};
			var topTab = $("#scroller > div").eq(toptab_index).attr("ut");
			var subTab = ['online','offline'];
			$.ajax({
				type:"POST",
				url:"ajax",
				data:{"do":'getupdownlist', "toptab":topTab, "subtab":subTab[tab_index]},
				success:function(data){
                    var index1=data.indexOf('!#')+2;
                    var index2=data.indexOf('#!');
                    var str =data.substring(index1,index2);
                    var arrContent = str.split('|');

					if(data.indexOf('status') < 0)
					{
                        //var arrContent = data.split('|'+String.fromCharCode(16)+'|');
						objJson = {status:'1', content:arrContent[0], onlinenum:arrContent[1], offlinenum:arrContent[2],leftonlinenum:arrContent[3]};
					}
					else
					{
						objJson = eval('('+data+')');
					}

					if(objJson.status == -1)
					{
						window.location.href = objJson.url;
						return false;
					}
					else if(objJson.status == -100)
					{
						location.reload(true);
						return false;
					}
					else
					{
						//subTabContent = objJson.content;
                        $("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(0).find("i").html(arrContent[0]);
                        $("#scroller > div").eq(toptab_index).find(".toptab2 li").eq(1).find("i").html(arrContent[1]);
						$(".tabshow").eq(toptab_index).children().eq(tab_index).html(data);
						$("#scroller").children('img').hide();
						//$(".tabshow").eq(toptab_index).children().eq(tab_index).show();
						
						$(".toptab > li").unbind("click");
						$(".toptab2 li").unbind("click");
						$(".mask").unbind("click");
						$(".warn").unbind("click");
						$("input[name='hdLeftOnlineNum']").eq(toptab_index).val(arrContent[2]);
						initFunc();
						//$("#btn_up").show();
						$(".tabshow").eq(toptab_index).children().eq(tab_index).show().siblings().hide();
					}
				}
			});
		}
		else
		{
			$(".tabshow").eq(toptab_index).children().eq(tab_index).show().siblings().hide();
			changenum(toptab_index, tab_index);
		}
	});

	$(".warn").click(function () {
		if(tab_index == 1 && $(this).find(".sign_orange").length >0)
		{
			showMsg('违规房源无法上架哦');
			return false;
		}

		if(tab_index == 1 && $.trim($(this).find(".ml3").eq(0).html()) =='')
		{
			showMsg('木有室内图，无法上架哦');
			return false;
		}
	});

	changenum(toptab_index, tab_index);
	
	$(".mask").click(function () {
		if(tab_index == 1 && $(this).parent().hasClass("warn"))
		{
			return false;
		}

		if(tab_index == 1 && $.trim($(this).parent().find(".ml3").eq(0).html()) =='')
		{
			return false;
		}
		if ($(this).parent().find(".radio").hasClass("LabelSelected")) {
			$(this).parent().find(".CheckBoxClass").removeAttr("checked");
			$(this).parent().find(".radio").removeClass("LabelSelected");
		} else {
			if(tab_index == 1)
			{
				var tmpStr = $.trim($("#btn_up").html());
				/\d+\/(\d+)/.test(tmpStr);
				var leftOnlineNUm = RegExp.$1;
				var checkednum = $("#scroller > div").eq(toptab_index).find(".houselist").eq(tab_index).find(".LabelSelected").length;
				if(leftOnlineNUm <= checkednum)
				{
					return false;
				}
			}
			$(this).parent().find(".CheckBoxClass").attr("checked", 'true');
			$(this).parent().find(".radio").addClass("LabelSelected");
		}
		changenum(toptab_index, tab_index);
	});
}
$(initFunc);


function showMsg(msg)
{
	$("#sp_msg1").html(msg);
	 $(".popBox1").show();
			$(".popBox1").animate({
				opacity: 1
			}, 1000,
	'ease-out');
			setTimeout(function () {
				$(".popBox1").animate({
					opacity: 0
				}, 2000,
	'ease-out');
			}, 2000);
}
