/**
 * @abstract 执行页面提交一系列的操作判断
 * @author Yuntaohu <yuntaohu@51f.com>
 * @date 2011-03-08
 * @lastModify By Yuntaohu 2011-03-08
 *
 */

/** 
 * 页面跳转
 * string d 控制器页面
 * string a 操作类型
 */
function deal(d,a){ window.location="?do="+d+"&deal="+a;}

/**
* checkbox表单选定方法
* @author Yuntaohu <yuntaohu@51f.com>
* object 绑定对象
* 调用方法 aSelectAll(this)
*/
function aSelectAll(o)
{
	if ( o.checked === true ) {
		$(":checkbox").each(function(){
			
			if ( $(this).attr("name") != "ids_fs_weigui" &&  $(this).attr('disabled') != true) {
				$(this).attr("checked", "checked");
			}
		});
	} else {
		$(":checkbox").each(function(){
			if ( $(this).attr("name") != "ids_fs_weigui" &&  $(this).attr('disabled') != true) {
				$(this).attr("checked", "");
			}
		});
	}
}

/**
 * 同一表单提交到不同的地址
 * string url 表单即将提交的地址  参数类似"admin.php?do=subwayline&action=add"
 * @author chengchunxing <chunxingcheng@51f.com>
 * @date 2011-03-08
 * @lastModify By yangjun 2011-03-08
 */
function chooseSubmit(url){
	var obj = document.form1;
	obj.action = url;
	obj.submit();
}
function Submit(obj_name, url)
{
	eval("var o = document."+obj_name+";o.action='"+url+"';o.submit();");
}
/**
 * 异步获取满足条件的区域数据，并捆绑到指定对象
 * @author Yuntaohu <yuntaohu@51f.com>
 * int city_id 城市id
 * string 绑定对象名称
 */
function get_district(city_id, obj)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_district.json.php",
		data: "city_id=" + city_id,
		dataType: "json",
		success: function(data){
			
			$("#"+obj).empty();
	        if (data == null) {
	            $("<option value=\"0\">城区</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">城区</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
		}
	});
}

/**
 * 异步获取城市的经纪公司列表，并捆绑到指定对象
 * @author wuzhangshu <wuzhangshu@51f.com>
 * int city_id 城市id
 * string 绑定对象名称
 */
function get_company(city_id, obj)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_company_by_cityid.json.php",
		data: "city_id=" + city_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
	        if (data == null) {
	            $("<option value=\"0\">全部公司</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">全部公司</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
		}
	});
}

/**
* 异步获取满足条件的板块数据，并捆绑到指定对象
* @author Yuntaohu <yuntaohu@51f.com>
* int district_id 区域id
* string 绑定对象名称
*/
function get_region(district_id, obj)
{  
	$.ajax({
		type: "POST",
		url: base_url+"ajax/getRegion",
		data: "district_id=" + district_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
	        if (data == null) {
	            $("<option value=\"0\">板块</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">板块</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
		}
	});
}

/**
 * 异步获取满足条件的轨道线路，并捆绑到指定对象
 * @author Chengchunxing <chunxingcheng@51f.com>
 * int city_id 城市id
 * string 绑定对象名称
 */
function get_subwayline(city_id, obj)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_subwayline.json.php",
		data: "city_id=" + city_id,

		dataType: "json",
		success: function(data){
			$("#"+obj).empty();	
			//document.write(data);
	       if (data == null) {
	            $("<option value=\"0\">所有线路</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">所有线路</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
			
		}
	});
}
/**
 * 异步获取满足条件的轨道站点信息，并捆绑到指定对象
 * @author quanle<quanleliu@51f.com>
 * int subwayline_id 轨道线路id
 * string 绑定对象名称
 */
function get_subwaysite(subwayline_id, obj)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_subwaysite.json.php",
		data: "subwayline_id=" + subwayline_id,
		dataType: "json",
		//error: function(XMLResponse){
		//	//alert(XMLResponse.responseText);
		//},
		success: function(data){
			$("#"+obj).empty();	
			//document.write(data);
	       if (data == null) {
	            $("<option value=\"0\">站点</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">站点</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
			
		}
	});
}
/**
 * 异步获取满足条件的门店，并捆绑到指定对象
 * @author yangjun <junyang@51f.com>
 * int sector_id 区域id
 * string 绑定对象名称
 */
function get_shop(area_id, obj)
{
	$.ajax({
		type: "POST",
		url: base_url+"ajax/getShop/",
		data: "area_id=" + area_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
	       if (data == null) {
	            $("<option value=\"0\">所属门店</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">所属门店</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
			
		}
	});
}

/**
 * 异步获取满足条件的经纪人，并捆绑到指定对象
 * @author yangjun <junyang@51f.com>
 * int agent_id 区域id
 * string 绑定对象名称
 */
function get_realtor(shop_id, obj)
{
	$.ajax({
		type: "POST",
		url: base_url+"ajax/getRealtor/",
		data: "shop_id=" + shop_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
	       if (data == null) {
	            $("<option value=\"0\">所属经纪人</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">所属经纪人</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
			
		}
	});
}

/**
 * 异步获取经纪公司所属区域，并捆绑到指定对象
 * @author wuzhangshu <wuzhangshu@51f.com>
 * int company_id 区域id
 * string 绑定对象名称
 */
function get_sector(company_id, obj)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_sector.json.php",
		data: "company_id=" + company_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
	       if (data == null) {
	            $("<option value=\"0\">所属区域</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">所属区域</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
			
		}
	});
}

/**
 * 经纪人管理3级连动，小区、板块、公司
 * @param city_id
 * @param district_obj
 * @param hotarea_obj
 */
function get_district_hotarea_(city_id,district_obj,hotarea_obj)
{
	var district_id=null;
	var s=true;
	$.ajax({
		type: "POST",
		url: "./ajax/get_district.json.php",
		data: "city_id=" + city_id,
		dataType: "json",
		success: function(data){
			$("#"+district_obj).empty();
			
	        if (data == null) {
	            $("<option value=\"0\">城区</option>").appendTo("#"+district_obj);
	        } else {
	            $("<option value=\"0\">城区</option>").appendTo("#"+district_obj);
	            $.each(data, function(i, n){
	            	if(s)
	            	{
	            		district_id=i;
	            		s=false;
	            	}
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+district_obj);
	            });
	            get_hot_area(district_id,hotarea_obj);    
	        }
		}
	});
	get_company(city_id,'slccompany');
}

/**
 * 添加经纪公司帐户验证
 * @author liuli <liliu@51f.com>
 * object 对象
 */
function get_ent_accname(obj)
{ 
	if(checkAccountsPreg(obj))
	{
		$.ajax({
			type: "POST",
			cache: false,
			url: "./ajax/get_company_accname.php",
			data: "accname=" + $("#"+obj.name).val(),
			dataType: "text",
			success: function(data)
			{
				 $("#username_notice").empty().removeClass("FrameDivWarn").removeClass("FrameDivPass").removeClass("FrameDivMessage");
				 if(data=='true')
				 { 
					 $("#username_notice").append('<span style="COLOR:#ff0000"> × 帐号已经存在,请重新输入!</span>');
					 $("#username_notice").addClass('FrameDivWarn');
					 $("#Submit1").attr("disabled","disabled");
				 }
				 else
				 {
					 $("#username_notice").append('<span style="COLOR:#006600"> √ 可以注册！</span>');
					 $("#username_notice").addClass('FrameDivPass');
					 $("#Submit1").removeAttr("disabled");
				 }
			}
		});
	}
	else
	{
		$("#username_notice").empty().removeClass("FrameDivMessage").removeClass("FrameDivPass").addClass("FrameDivWarn");
		checkAccountsPreg_iswrong(obj,'username_notice');//方法在Jquery.from.ck.js
	}
		
}
/**
 * 获取城市销售客服
 * @param obj
 */
function get_crm_user(obj)
{
	get_crm_kf_xs(obj,'selsellid',4,'选择销售');
	get_crm_kf_xs(obj,'selserviceid',3,'选择客服');
}

/**
 * 获取城市销售客服
 * @param obj
 */
function get_crm_kf_xs(obj,sell,permission,message)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_crm_kf_xs.json.php",
		data: "city_id=" + obj.value+"&permission="+permission,
		dataType: "json",
		success: function(data){
			$("#"+sell).empty();
	       if (data == null) {
	            $("<option value=\"0\">"+message+"</option>").appendTo("#"+sell);
	        } else {
	            $("<option value=\"0\">"+message+"</option>").appendTo("#"+sell);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+sell);
	            });
	        }
			
		}
	});
}

/**
 * 经纪公司验证是否存在
 * @param obj
 * @param promptStr
 */
function get_dist_company_name(obj,promptStr)
{
	$.ajax({
		type: "POST",
		url: "ajax/get_dict_company.php",
		//url:"ajax/get_dict_company.json.php",
		data: "company="+obj.val()+"&city_id="+ $("#selcityid").val(),
		//data:"q_word=" + obj.val(),
		dataType: "json",
		error: function(XMLResponse){
			//alert(XMLResponse.responseText);
		},
		success: function(data){
			$("#"+promptStr).empty();
			if(data!=null){
				 $("#"+promptStr).empty().removeClass('FrameDivWarn');
				 checkFormatCompanyAbbr_preg(document.getElementById("company_name"),promptStr);
				 $("#Submit1").attr("disabled","disabled");
				 $("#Submit1").removeAttr("disabled");
			}else{
				 $("#"+promptStr).empty().addClass('FrameDivWarn');
				 $("#"+promptStr).append('<span style="COLOR:#ff0000"> × 公司输入错误,请重新输入!</span>');
				 $("#Submit1").attr("disabled","disabled");
			}
		}
	  });
	
}

//页面跳转
function global_url(url,obj){
	if ( obj.checked === true ){	
		window.location.href=url+"&"+obj.name+"="+obj.value
	}
	if ( obj.checked === false ){
		window.location.href=url;
	}
}

function CheckAll(form,obj,objall){
	//alert(form.elements[obj].length)
	for (var i=0;i<form.elements[obj].length;i++){
		var eobj = form.elements[obj][i];
		//alert(eobj.name)
		if (eobj.disabled==false){
			eobj.checked = document.getElementById(objall).checked;
		// alert(form.chkAll.checked)
		}
		 
	}
}

/**
 * 异步查询用户输入的帐号数据是否唯一
 * @author yangjun <junyang@51f.com>
 * input_obj 输入框对象
 */
function checkTheOne(input_obj)
{
	var str = input_obj.value;
	var obj = $("input[name="+input_obj.name+"]");
	$.ajax({
		type: "POST",
		url: "/ajax/checkFieldExist",
		data: "str=" + str,
		success: function(data){
			if(data == 0)
			{
				obj.parent().find("label > span").html(accname_error);
				return;
			}
			else if(data == 1)
			{
				obj.parent().find("label > span").html(accname_right);
				return;
			}
		}
	});
}

var accname_error="<span style='COLOR:#ff0000'> × 该用户名已经被注册，换个别的吧！</span>";
var accname_right="<span style='COLOR:#006600'> √ 可以注册！</span>";
var companyname_error="<span style='COLOR:#ff0000'> × 该名称已经存在，换个别的吧！</span>";
var companyname_right="<span style='COLOR:#006600'> √ 可以添加！</span>";


/**
 * 添加经纪公司字典公司简称，全称验证
 * @author liyajun <liyajun@51f.com>
 * object 对象
 */
function checkCompanyOne(obj,action,city_id)
{
	var str = obj.value;
	$.ajax({
		type: "POST",
		url: "./ajax/get_company_name_abbr.php",
		data: "action=" + action + "&city_id="+city_id+"&company=" + obj.value,
		//dataType: "json",
		success: function(data){
			if(data =="false")
			{
				$(obj).next("span").html(companyname_right);
				return;
			}else{
				$(obj).next("span").html(companyname_error);
				return;	
			}
		}
	});
}
/**
 * crm系统销售-客服区分城市
 * @author liyajun <liyajun@51f.com>
 * object 对象
 */
function get_sell_service(id,action){
	var	select=document.getElementById(id); 
	var	option = select.options[select.selectedIndex];
	var objcity = option.attributes.value;
	$.ajax({
		type: "POST",
		url: "./ajax/get_sell_service_list.php",
		data: "city_id="+objcity.value+"&action="+action,
		success: function(data){
		    var html;
			if(data =="false")
			{
				return;
			}else{
	            html = data;
	            $("#sell_service").empty();
	            $(html).appendTo("#sell_service");
	            $("#sell_service").show();
				return;	
			}
		}
	});
}
/**
 * crm系统小区添加，修改。物业类型，引入不同的配置文件。
 * object 对象
 */
function get_city_config(cityid){
	var	select=document.getElementById(cityid); 
	var	option = select.options[select.selectedIndex];
	var objcity = option.attributes.value;
	$.ajax({
		type: "POST",
		url: "./ajax/get_city_config.php",
		data: "city_id="+objcity.value,
		success: function(data){
		    var html;
			if(data =="false")
			{
				return;
			}else{
	            html = data;
	            $("#span_live_type").empty();
	            $(html).appendTo("#span_live_type");
	            $("#span_live_type").show();
				return;	
			}
		}
	});
}
/**
 * crm系统-添加小区区分城市
 * @author liyajun <liyajun@51f.com>
 * object 对象
 */
function get_city_house_isexit(id,nameid,house_id){
	var	select=document.getElementById(id); 
	var	option = select.options[select.selectedIndex];
	var objcity = option.attributes.value;
	var	house_name=document.getElementById(nameid).value;
	$.ajax({
		type: "POST",
		url: "./ajax/is_exits_house.php",
		data: "city_id="+objcity.value+"&q_word="+house_name+"&house_id="+house_id,
		success: function(data){
			if(data == 1)
			{				
				$('#house_name').next("span").html(house_name+'小区已存在');
				return false;
			}else{
				return true;	
			}
		}
	});
}
//----------------------------翟健雯 添加 vip-（公司-区域）系统经纪人统计-------------
/**
 * 异步获取满足条件的门店，并捆绑到指定对象
 * @author yangjun <junyang@51f.com>
 * int sector_id 区域id
 * string 绑定对象名称
 */
function get_agent_new(company_id,sector_id, obj)
{
	$.ajax({
		type: "POST",
		url: "./ajax/get_agent_new.json.php",
		data: "sector_id=" + sector_id+"&company_id=" + company_id,
		dataType: "json",
		success: function(data){
			$("#"+obj).empty();
	       if (data == null) {
	            $("<option value=\"0\">所属门店</option>").appendTo("#"+obj);
	        } else {
	            $("<option value=\"0\">所属门店</option>").appendTo("#"+obj);
	            $.each(data, function(i, n){
	                $("<option value="+i+">"+n+"</option>").appendTo("#"+obj);
	            });
	        }
			
		}
	});
}
//----------------------------翟健雯 添加 vip-（公司-区域）系统经纪人统计 end-------------

function HouseonClick(obj)
{
	if($('#housename').val() == '输入小区名称')
	{
		$('#housename').val('');
	}
}
function onBlurHouse(obj)
{
	if($('#housename').val() == '')
	{
		$('#housename').val('输入小区名称');
	}	
}
//------------------------程春杏 复选框全选具有相同name的复选框--------------------------
/**
 * 复选框全选具有相同name的复选框
 * @author chengchunxing <chunxingcheng@51f.com>
 * object o 事件操作对象
 * string name name值，具有该name的复选框将会被选中
 */
function selectAll(o,name)
{
	if ( o.checked === true ) {
		$(":checkbox").each(function(){
			
			if ( $(this).attr("name") == name &&  $(this).attr('disabled') != true) {
				$(this).attr("checked", "checked");
			}
		});
	} else {
		$(":checkbox").each(function(){
			if ( $(this).attr("name") == name &&  $(this).attr('disabled') != true) {
				$(this).attr("checked", "");
			}
		});
	}
}
/*
 * 获得经纪公司列表
 * xz_city_id城市ID
 * company_name公司名称,模糊查找
 * 
 */

function getCompanyList(xz_city_id,company_name)
{
	$.ajax({
		type: "POST",
		url: "ajax/get_dict_company_bycity.json.php",
		data: "q_word=" + encodeURIComponent(company_name) + "&xz_city_id=" + xz_city_id,
		dataType: "json",
		success: function( result ){
			if ( result!=null && result!="" ) {
				//创建下拉列表						
				$("#suggest").css("left",$("#company_name").offset().left);
    			$("#suggest").css("top",$("#company_name").offset().top + $("#company_name").outerHeight());
				$(result).each(function(i, n){							
					$("#suggest").append('<span id="'+n.company_id+'">'+n.company_name_abbr+'</span>');
				});
				//追加交互样式
				$("#suggest span").mouseover(function(){
					$("#suggest span").removeClass("sel");
					$(this).addClass("sel");
				});
				//移除交互样式
				$("#suggest span").mouseout(function(){
					$(this).removeClass("sel");
				});
				//点击事件
				$("#suggest span").click(function(){							
					var company_id=$(this).attr("id");							
					$("#company_id").val(company_id);													
					$("#company_name").val($(this).text());
					get_sector(company_id, 'sector_id'); //为下拉框赋值
					$("#suggest").fadeOut();
				});
				$("#suggest").fadeIn();
                $("#suggest").show();
			} else {
				$("#suggest").fadeOut();
			}
		}
	});
}

/**
 * 
 */

function getCompanyId(){
	//初始化	
	var default_group_name = '';
	var default_group_id = '';
	var default_suggest = '';
	$("#suggest").append(default_suggest);	
	//失去焦点隐藏提示
	$("#company_name").blur(function(){
		$("#suggest").fadeOut();
	});
	$("#company_name").keyup(function(e){
		var company_name = $(this).val();		
		//监听回车事件
		if ( e.keyCode==13 ) {
			var select_span = $("#suggest .sel");
			if ( select_span.attr("id") > 0 ) {
				$("#company_id").val(select_span.attr("id"));
				$("#company_name").val(select_span.text());				
				get_sector(select_span.attr("id"), 'sector_id'); //为下拉框赋值
				select_span.removeClass("sel");
				$("#suggest").fadeOut();
			} else {
				$("#company_name").val(default_company_name);
				$("#company_id").val(default_group_id);
				get_sector(default_group_id, 'sector_id'); //为下拉框赋值
				$("#suggest").fadeOut();
			}
			return;
		}
		//过滤部分不相关的按键
		switch ( e.keyCode ) {			
			case 13:case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 37:
			case 38:case 39:case 40:case 45:return;
		}

		//请求前先将下拉选项数据清空
		$("#suggest").empty();		
		if (company_name.length > 0 ) {
			var xz_city_id = $("#city_id").val();
			getCompanyList(xz_city_id,company_name );
		} else {
			$("#suggest").empty();
			$("#suggest").append(default_suggest);
			$("#company_id").val(default_group_id);			
			$("#company_name").val(default_group_name);
			get_sector(default_group_id, 'sector_id'); //为下拉框赋值
		}
	});
}