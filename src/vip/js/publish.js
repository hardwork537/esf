$(function(){
	if(navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0){
		var formGroup = $("#publishForm").find(".form_group");
		var dropDown = $("#publishForm").find(".dropdown");
		for(var i=0,j=100;i<formGroup.length;i++,j--){
			formGroup.eq(i).css("z-index",j);
			dropDown.eq(i).css("z-index",j);
		}
	}
	/* suggest */
	$("#suggest").find("span").hover(function(){
		$(this).addClass("sel");
	},function(){
		$(this).removeClass("sel");
	});
	
	/* 模拟下拉框 */
	$(".dropdown").mouseenter(function(){
		if(!$(this).find(".dropdown_list").is(":animated")){//判断是否处于动画
			//$(".dropdown_list").hide();
			$(this).find(".dropdown_list").show();
			return false;
		}
	});
	$(".dropdown").mouseleave(function(){
		$(this).find(".dropdown_list").hide();
	});
	$(".dropdown_list li a").click(function(event){
        var thiz = $(this).parent().parent().parent().siblings(".notice_hover")
        thiz.empty();
        thiz.removeClass("notice_warn").addClass("notice_right");

		var text = $(this).text()+"<i></i>";
		var svalue = $(this).attr("src");
		$(this).parents(".dropdown").find(".dropdown_btn").html(text);
		$(this).parents(".dropdown").find("input[type=hidden]").val(svalue);
		$(this).parents(".dropdown_list").fadeOut();
		return false;
	});
	
	/* 复选框全选 */
	$(".selectAllUnit").click(function () {
        var flag_select = $(this).is(":checked");
		var checkbox = $(this).parents(".input_group").find(':checkbox');
		for(var i=0; i<checkbox.length; i++){
			checkbox.eq(i).attr("checked",flag_select);
		}
    });
	
	$("input[type='text']").focus(function () {
		$(this).removeClass("Validform_error");
        var NoticeHtml = "<b></b>"+$(this).attr("nullmsg");
        if( $(this).attr('name')=='title' ){
            $(this).siblings('b').html('');
            $(this).keyup();
            NoticeHtml = '';
        } 
        var obj_span = $(this).parent().children("span");
        obj_span.removeClass();
        obj_span.addClass("notice_hover");
        obj_span.html(NoticeHtml);
    });

	
	$.each(district, function(i,n){
		$('<span value="'+i+'"><a href="javascript:void(0)">'+n+'</a></span>').appendTo("#district_list").click(function(){
			$(this).parent().siblings("em").text($(this).text()); //显示区域名称
			$("#district_id").val($(this).attr("value")); //设置隐藏区域值
			$("#region_id").val(''); //初始化板块隐藏区域值
			$.each(region[$(this).attr("value")], function(i,n){
				$('<span value="'+i+'"><a href="javascript:void(0)">'+n+'</a></span>').appendTo("#region_list").click(function(){
					$(this).parent().siblings("em").text($(this).text()); //显示区域名称
                    // $("#region_id").val($(this).attr("value"));
					$("#region_id").val($(this).attr("value")).focus().blur(); //设置隐藏区域值
				});
			}); //构建板块下拉列表
		});
	});
	
	var this_em_district = '';
    $(".item-drop input[type='hidden']").each(function () {
        var hidden_val = $(this).val();
        var em_value = '';
        var this_name = $(this).attr('name');
        if(  hidden_val == ''  ){
            switch(this_name){
                case 'district_id':
                    em_value = '城区';
                    break;
                case 'region_id':
                    em_value = '板块';
                    break;
                case 'bedroom':
                    em_value = '居室';
                    break;
                case 'living_room':
                    em_value = '无厅';
                    break;
                case 'bathroom':
                    em_value = '无卫';
                    break;
                case 'exposure':
                    em_value = '朝向';
                    break;
                case 'property_type':
                    em_value = '请选择';
                    break;
                case 'build_type':
                    em_value = '建筑类型';
                    break;
                case 'danwei':
                    em_value = '元/月';
                    break;
                case 'rent_price_type':
                    em_value = '押一付三';
                    break;
                case 'share_type':
                    em_value = '主卧';
                    break;
				case 'property_nature':
					em_value = '个人产权';
					break;
            }
        }else{
            switch(this_name){
                case 'district_id':
                    if(parseInt(hidden_val)!=0||hidden_val==''){
                        em_value = district[hidden_val];
                        this_em_district = hidden_val;
                    }else{
                        em_value = '城区';
                        this_em_district = '';
                        $(this).val('');
                    }
                    break;
                case 'region_id':
                    if(this_em_district!=''&&hidden_val!=0){
                        em_value = region[this_em_district][hidden_val];
                    }else{
                        em_value = '板块'
                        $(this).val('');
                    }
                    break;
                default:
                    em_value = $(this).siblings('div').children().children('a[src="'+hidden_val+'"]').html();
            }
        }
        $(this).siblings('em').html(em_value);
    });
	
	$("input[name='title']").keyup(function(e){
        var val = $(this).val();
        var intObjLen = val.length;
        intObjLen = intObjLen>29? 0: 30-intObjLen;
        var html = '还可输入<font style="color:red;">'+intObjLen+'</font>个字';
        $(this).siblings('b').html(html);
    });
	
	$("input[name='title']").blur(function(){
        $("input[name='title']").siblings('b').html('');
    });
	
	$("input[name='property_type']").siblings('ul').children('li').children('a').click(function (){
        theProperty_typeEffect($(this).attr('src'),0);//非初始化，赋值1
		//为了解决楼层区域隐藏前，楼层信息输入校验正确后，区域被隐藏，又被显示后奇怪的通过校验的bug(此时文本框内容被清空)
		//$('#floor').blur();
		//$('#floor_max').blur();
    });
	
	$("input[name='custom_id']").blur(function () {
		if( $(this).val() == '' )
			$(this).siblings('span').html('');
	});
	
    $("input[name='custom_code']").blur(function () {
        if( $(this).val() == '' )
            $(this).siblings('span').html('');
    });
	
	var dateTime=new Date();
    year = dateTime.getFullYear();
	$("input[name='build_year']").blur(function () {
        if( $(this).val() > year ){
            obj_build_year = $(this).siblings('span');
            obj_build_year.removeClass();
            obj_build_year.addClass("notice_hover notice_warn");
            obj_build_year.html('<b></b>'+$(this).val()+'年，您穿越了');
            $(this).val('');
        }
    });
	
	$("input[name='danwei']").siblings('ul').children('li').children('a').click(function () {
		var danweiLimit = parseInt($(this).attr('src'))==1? 'yuan_month': 'yuan_mi_month';
		var danweiMsg = parseInt($(this).attr('src'))==1? '请输入100～1000000之间的整数': '请输入1～100之间的数字';
		$("input[name='rent_price']").attr('datatype', danweiLimit);
		$("input[name='rent_price']").attr('nullmsg', danweiMsg);
		$("input[name='rent_price']").attr('errormsg', danweiMsg);
		$("input[name='rent_price']").blur();
    });
	
	if( $("input[name='rent_price']") != undefined ){ //防止浏览器报错使js中断执行       
		var danweiLimit = parseInt($("input[name='danwei']").val())==1? 'yuan_month': 'yuan_mi_month';
		var danweiMsg = parseInt($("input[name='danwei']").val())==1? '请输入100～1000000之间的整数': '请输入1～100之间的数字';
		$("input[name='rent_price']").attr('datatype', danweiLimit);
		$("input[name='rent_price']").attr('nullmsg', danweiMsg);
		$("input[name='rent_price']").attr('errormsg', danweiMsg);
	}
	
	var rent_type = $("input[name='rent_type']");
    rent_type.click(function () {
        var dis = $(this).val()==1? 'inline-block': 'none';
        $(this).parent().siblings('span').css('display', dis);        
    });
    if( rent_type.attr('checked') ) rent_type.parent().siblings('span').css('display', 'none');
});

$(function(){
    var intMaxLength = $('#intLastUnitNum').val() < 3 ? parseInt($('#intLastUnitNum').val()) + parseInt($('.house_tags a').siblings('.active').size()) : 3;
    var bIsUnitOver = $('#intLastUnitNum').val()? true: false;    $('.house_tags a').click(function (){
        var obj = this;
        var jobj = $(obj);
        if( obj.className.indexOf('active') ){ /* 排除不能选型标签的 */            
            if( !obj.className.indexOf('noneLink') ){
                //alert('您的设置特色标签的数量已经用完了！');
            }else if( jobj.siblings('.active').size()<intMaxLength ){
                jobj.children('input').attr('name', 'sign[]');
                jobj.addClass('active');
            }else{
                //alert('一个房源最多设置三个特色标签');
            }
        }else{
            jobj.children('input').attr('name', '');
            jobj.removeClass('active');
        }
        selectOther( jobj );
    });
    function selectOther(jObj){
        var tmp = '';
        var intSize = jObj.parent().children('.active').size();
        jObj.parent().children('a').each(function (){
            tmp = $(this).attr('class');
            if( intSize>=intMaxLength ){/* 个数超出限定的 */
                if( tmp.indexOf('active') ){ $(this).attr('class', 'noneLink'); }/* 没有active的 */
            }else{
                if( !tmp.indexOf('noneLink')&&bIsUnitOver ){ $(this).attr('class', ''); }/* 有noneLink的 */
            }
        });      
    }
    selectOther($('.house_tags a'));
});

/* 修改theProperty_typeEffect函数,加入了一个参数isInit，作用是判断是否为初始化时使用。
 * 1. 初始化时，isInit为默认值1
 * 2. 非初始化时，isInit赋值0，此时菜单内值出现变动后，用户重新选择物业类型时，这里会还原成“物业类型”，且value值为空。
 */
function theProperty_typeEffect(theIds,isInit){        
	var objFloor = $("input[name='floor']");
	var objFloorMax = $("input[name='floor_max']");
	var objBuildType = $("input[name='build_type']");
	var theId = parseInt(theIds);
	var em_value = '建筑类型';
	switch(theId){
		case 7:
			objBuildType.val('');
			objBuildType.parent('span').css('display', 'none');

			objFloor.val('');
			objFloorMax.val('');
			objFloor.parents('.form_group').css('display', 'none');
			objFloor.attr("disabled", "disabled");
			objFloor.attr('ignore', 'ignore');
			objFloorMax.attr("disabled", "disabled");
			objFloorMax.attr('ignore', 'ignore');
			break;
		case 2: case 4:
			if(isInit == 0){
				objBuildType.parent('span').children('em').html(em_value);
				objBuildType.val('');
			}
			
			objFloor.val('');
			objFloorMax.val('');
			objFloor.parents('.form_group').css('display', 'none');
			objFloor.attr("disabled", "disabled");
			objFloor.attr('ignore', 'ignore');
			objFloorMax.attr("disabled", "disabled");
			objFloorMax.attr('ignore', 'ignore');
			
			objBuildType.parent('span').css('display', 'inline-block');
			objBuildType.parent('span').children('.item-drop-list').children().css('display', 'none');
			for(var i=0,len=arrBuildTypeConf[city][theId-1][theId].length;i<len;i++)
			{
				var index = arrBuildTypeConf[city][theId-1][theId][i]-1;
				objBuildType.parent('span').children('.item-drop-list').children('span:eq('+index+')').css('display', 'inline');
			}
			break;
		case 1: case 3: case 6:
			if(isInit == 0){
				objBuildType.parent('span').children('em').html(em_value);
				objBuildType.val('');
			}
			
			objFloor.parents('.form_group').css('display', 'block');
			objFloor.attr("disabled", false);
			objFloor.attr('ignore', '');
			objFloorMax.attr("disabled", false);
			objFloorMax.attr('ignore', '');

			objBuildType.parent('span').css('display', 'inline-block');
			objBuildType.parent('span').children('.item-drop-list').children().css('display', 'none');
			for(var i=0,len=arrBuildTypeConf[city][theId-1][theId].length;i<len;i++)
			{
				var index = arrBuildTypeConf[city][theId-1][theId][i]-1;
				objBuildType.parent('span').children('.item-drop-list').children('span:eq('+index+')').css('display', 'inline');
			}
			break;
		default:
			objBuildType.val('');
			objBuildType.parent('span').css('display', 'none');
			
			objFloor.parents('.form_group').css('display', 'block');
			objFloor.attr("disabled", false);
			objFloor.attr('ignore', '');
			objFloorMax.attr("disabled", false);
			objFloorMax.attr('ignore', '');
	}
}
theProperty_typeEffect($("#property_type").val(),1);

function faBu(){
	$(".inputform").submit();
}

function checkareaprice(areaAvgPrice,intArea,gets,min,tag,bias){
	//板块均价限制放开请去掉下面几行   
	if(tag == 2){
		if( gets!='' && gets<min ){
			$('#continuefabu').show();
			$('#priceNotice').html('您所填写的价格低于本小区均价30%<br/><br/>请您核实后再确认发布');
			$('#overlay').overlay({
				mask: {	color: '#AAA',	loadSpeed: 200,	opacity:0.8	},
				api:true
			}).load();
		}else{
			faBu();
		}
	}
}

function checkPricepre(tag){
	var area = $('#area').val();
	area = parseInt(area); 
	var intAvgPrice = $('#price').attr('avg_price');
	var areaAvgPrice = $('#price').attr('avg_region');
	var salenum = $('#price').attr('sale_count');
	var max_avg_price = $('#price').attr('max_avg_price');
	var bias = $('#bias').val();
	var maxnum = $('#maxnum').val();
	$('#price_park').val(intAvgPrice);
	$('#price_region').val(areaAvgPrice);
	$('#price_district').val(max_avg_price);
	$('#sale_count').val(salenum);
	var gets = $('#price').val();
	var intGets = parseFloat(gets);
	var intArea = $('#area').val();
	intArea = parseFloat(intArea);
	var tip = '';
	if(max_avg_price && max_avg_price!=0){
		 max_avg_price = max_avg_price/10000;
		 var max_price = max_avg_price*intArea;
		 if(gets>max_price){
			var _targetTop = $('#price').offset().top;//获取位置
			jQuery("html,body").animate({scrollTop:_targetTop},0);
			$('#price').siblings('.notice_hover').addClass('notice_warn').html('<b></b>均价过高，请确认售价');
			return 'warn';
		 }
	}
	if(intAvgPrice && intAvgPrice!=0 ){
		intAvgPrice = intAvgPrice/10000;
		var max = intAvgPrice*1.3*intArea;
		var min = intAvgPrice*0.7*intArea;
		var min_house = intAvgPrice*bias*intArea;
		$('#price').attr('min_max', min+'_'+max);
		salenum = parseInt(salenum);
		maxnum  = parseInt(maxnum);
		if(salenum >= maxnum){
			if(gets<min_house){
				if(tag == 2){
					$('#tag').val(1);
					$('#continuefabu').hide();
					showtip();
				}else{
					$('#tag').val(1);
					return 'warning';
				}
			}else{
				if(tag == 2){
					checkareaprice(areaAvgPrice,intArea,gets,min,tag,bias);
				}else{
					tip = checkareaprice(areaAvgPrice,intArea,gets,min,tag,bias);
					return tip;
				}
			}
		
		}else{
			if(tag == 2){
				 checkareaprice(areaAvgPrice,intArea,gets,min,tag,bias);
			}else{
				 tip = checkareaprice(areaAvgPrice,intArea,gets,min,tag,bias);
				 return tip;
			}
		}
	}else{
		// 板块均价打开时，去掉下面三行 
		if(tag == 2){
			faBu();
		}
	}
}

function showtip(){
	$('#priceNotice').html('您所填写的价格过低<br/><br/>请您核实后再确认发布');
	$('#overlay').overlay({
		mask: {	color: '#AAA',	loadSpeed: 200,	opacity:0.8	},
		api:true
	}).load();
}

var editFlag = false;
$("form :input").change(function (){
	editFlag = true;
});
$(".dropdown_list li a").click(function(){
	editFlag = true;
});
$(window).bind('beforeunload', function(){ 
	if(editFlag){
		return '确认离开此页吗？'; 
	}
});

//开盘时间及交房时间选择
function changeDay(year, month, objday){
	var nowdate = new Date(year, month, 0);
	var days = nowdate.getDate();
	var strhtml = "<li><a href=\"javascript:void(0)\" src=\"0\">不选择</a></li>";
	for(var i=1; i<=days; i++){
		strhtml += "<li><a href=\"javascript:void(0)\" src=\""+i+"\">"+i+"日</a></li>";
	}
	$("#"+objday).parent(".dropdown").find(".dropdown_list").html(strhtml);
	$("#"+objday).parent(".dropdown").find(".dropdown_list li a").click(function(event){
		var text = $(this).text()+"<i></i>";
		var svalue = $(this).attr("src");
		$(this).parents(".dropdown").find(".dropdown_btn").html(text);
		$(this).parents(".dropdown").find("input[type=hidden]").val(svalue);
		$(this).parents(".dropdown_list").hide();
		return false;
	});
}

//配套设施与楼层相关限制
filterFacility();
function filterFacility(){
	var floor = parseInt($("#floor").val());
	var floor_max = parseInt($("#floor_max").val());
	$("input[name='facility[]']").each(function(){
		if ($(this).parent(".label").text() == '花园/小院')
		{
			if (floor == 1 || floor == floor_max) {
				$(this).attr("disabled", "");
				$(this).parent().show();
			} else {
				$(this).attr("disabled", "disabled");
				$(this).parent().hide();
			}
		}
		if ($(this).parent(".label").text() == '储藏室/地下室')
		{
			if (floor == 1 || floor < 0) {
				$(this).attr("disabled", "");
				$(this).parent().show();
			} else {
				$(this).attr("disabled", "disabled");
				$(this).parent().hide();
			}
		}
	});
}
