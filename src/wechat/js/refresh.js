var tab_index = 0;

function changenum() 
{
	var upnum_all = $(".toptab3").eq(tab_index).children().eq(2).children(".green").html();
	var checkednum = $(".houselist").eq(tab_index).find(".LabelSelected").length;
	if($("#scroller > div").eq(tab_index).find(".nomsg").length > 0)
	{
		$("#btn_up").hide();
		$(".wrapper").css('padding-bottom','0');
	}
	else
	{
		$("#btn_up").html("刷新 ( " + checkednum + "/" + upnum_all + " )");
		$(".wrapper").css('padding-bottom','40px');
		$("#btn_up").show();

		if (checkednum != 0) {
			$("#btn_up").removeClass("turngray");
		} else {
			$("#btn_up").addClass("turngray");
		}
	}
}
	
var initFunc = function () 
{
	/*初始化tab_index*/
	$(".toptab > li").each(function(){
		if($(this).hasClass("active"))
		{
			tab_index = $(this).index();
		}
	});
	changenum();
	
	$(".toptab > li").click(function()
	{
		tab_index = $(this).index();
		$(this).siblings().removeClass("active");
		$(this).addClass("active");
		$("#scroller > div").eq(tab_index).siblings().hide();
		var listContent = $.trim($("#scroller > div").eq(tab_index).html());
		if(listContent == '')
		{
			$("#scroller").children("img").show();
			//$("#btn_up").html("刷新 (0/0)");
			$("#btn_up").hide();
			$.ajax({
				type: "POST",
				url: "ajax",
				data: {"unitype":$("#scroller > div").eq(tab_index).attr("ut"), "do":'getFlushRentList'},
				success: function(data){
				if(data.indexOf('status') > 0)
				{
					var jsonobj=eval('('+data+')');
					if(jsonobj.status == -1)
					{
						window.location.href = jsonobj.url;
					}
					else if(jsonobj.status == -100)
					{
						location.reload(true);
					}
				}
				else
				{
					$("#scroller > div").eq(tab_index).html(data);
					$("#scroller").children("img").hide();
					
					$(".toptab > li").unbind("click");
					$(".mask").unbind("click");
					$("#btn_up").unbind("click");
					$(".warn").unbind("click");
					initFunc();
				}
				$("#scroller > div").eq(tab_index).show();
				changenum();
				//$("#btn_up").show();
			}});
		}
		else
		{
			$("#scroller > div").eq(tab_index).show();
			changenum();
		}
	});
	
	$(".mask").click(function () 
	{
		if ($(this).parent().find(".radio").hasClass("LabelSelected")) {
			$(this).parent().find(".CheckBoxClass").removeAttr("checked");
			$(this).parent().find(".radio").removeClass("LabelSelected");
		} else {
			$(this).parent().find(".CheckBoxClass").attr("checked", 'true');
			if($(this).parent().find(".CheckBoxClass").attr("weigui") > 0)
			{
				//showMsg("不能刷新违规房源");
				return false;
			}
			else
			{
				var maxLeftNum = parseInt($.trim($(".toptab3").eq(tab_index).children().eq(2).children(".green").html()));
				var checkednum = $(".houselist").eq(tab_index).find(".LabelSelected").length;
				
				if(checkednum >= maxLeftNum)
				{
					//showMsg("最多只能刷新"+maxLeftNum+"个房源");
					return false;
				}
				$(this).parent().find(".radio").addClass("LabelSelected");
			}
		}
		changenum();
	});

	$("#btn_up").click(function () 
	{
		if ($(this).hasClass("turngray")) {
			return false;
		} else {
			$("#btn_up").prepend("<img src=\""+imgurl+"\"/>");

			var strUnitIds = getCheckedIds();
			$.ajax({
			type: "POST",
			url: "ajax",
			data: {"unitype":$("#scroller > div").eq(tab_index).attr("ut"), "unitid":strUnitIds, "do":'flush'},
			success: function(data){
				var jsonobj=eval('('+data+')');
				if(jsonobj.status == 1)
				{	
					$(".toptab3").eq(tab_index).children().eq(2).children(".green").html(jsonobj.leftNum);
				
					var arrUnitId;
					if(strUnitIds.indexOf(',') < 0)
					{
						arrUnitId = [strUnitIds];
					}
					else
					{
						arrUnitId = strUnitIds.split(',');
					}
					
					var doneNum = parseInt($.trim($(".toptab3").eq(tab_index).children().eq(0).children(".green").html())) + arrUnitId.length;
					$(".toptab3").eq(tab_index).children().eq(0).children(".green").html(doneNum);
					
					for(var i = 0; i < arrUnitId.length; i++)
					{
						var spname1 = 'sp_flush_' + arrUnitId[i];
						var spname2 = 'sp_flush_day_' + arrUnitId[i];
						$("#"+spname1).html(jsonobj.freshTime);
						$("#"+spname2).html("今日已刷");
						$("#"+spname2).removeClass("orange");
						$("#"+spname2).addClass("green");
					}
					
					cancelChked();
					changenum();	
					showMsg("已刷新");
				}
				else if(jsonobj.status == -1)
				{
					window.location.href = jsonobj.url;
				}
				else if(jsonobj.status == -100)
				{
					location.reload(true);
				}
			}
			});
		  
		}
	});

	$(".warn").click(function (){
		showMsg1("违规房源无法刷新哦");
	});
};
	
function getCheckedIds()
{
  var strUnitIds = '';
  $(".houselist").eq(tab_index).find(".LabelSelected").each(function(){
	strUnitIds += $(this).attr("unitid")+',';
	});
	if(strUnitIds != '')
	{
		strUnitIds = strUnitIds.substr(0,strUnitIds.length - 1);
	}
	return strUnitIds;
}

function cancelChked()
{
	$(".houselist").eq(tab_index).find(".LabelSelected").each(function(){
		$(this).parent().find(".CheckBoxClass").removeAttr("checked");
		$(this).parent().find(".radio").removeClass("LabelSelected");
	});
}
	
function showMsg(msg)
{
	$("#sp_msg").html(msg);
	 $(".popBox").show();
			$(".popBox").animate({
				opacity: 1
			}, 1000,
	'ease-out');
			setTimeout(function () {
				$(".popBox").animate({opacity: 0}, 2000,'ease-out');
				$("#btn_up img").remove();
				},2000);
}

function showMsg1(msg)
{
	$("#sp_msg1").html(msg);
	 $(".popBox1").show();
			$(".popBox1").animate({
				opacity: 1
			}, 1000,
	'ease-out');
			setTimeout(function () {
				$(".popBox1").animate({opacity: 0}, 2000,'ease-out');
				},2000);
}

$(initFunc);