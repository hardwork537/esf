base_url = "/";
souce_url = "http://src.me/admin/";
/**
 * 根据城市异步获取销售
 * obj_id, city_id,area_id,block_id,team_id
 */
function getXiaoshou(obj_id,check_id,city_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(city_id)=="undefined" || city_id==""){
        city_id = 1;
    }
	if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '全部';
    }
    
    $.request({
        url: "/ajax/getXiaoshouByCityId/"+city_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
			$("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            if (msg.data == null) {
				return false;
            }
			$.each(msg.data, function(i, n){
				if(i==check_id){
				   $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
				}else{
				   $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id); 
				}
			});

        }
    });
}

/**
 * 根据城市异步获取客服
 * obj_id, city_id,area_id,block_id,team_id
 */
function getKefu(obj_id,check_id,city_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(city_id)=="undefined" || city_id==""){
        city_id = 1;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '全部';
    }
    $.request({
        url: "/ajax/getKefuByCityId/"+city_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
			$("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            if (msg.data == null) {
				return false;
            } 
			$.each(msg.data, function(i, n){
				if(i==check_id){
				   $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
				}else{
				   $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id); 
				}
			});

        }
    });
}

/**
 * 异步加载区域
 */
function getDist(obj_id,check_id,city_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(city_id)=="undefined" || city_id==""){
        city_id = 1;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    $.request({
        url: "/ajax/getDistByCityId/"+city_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
			
            if (msg.data == null) {
                return false;
            }
            $.each(msg.data, function(i, n){
                if(i==check_id){
                    $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                }else{
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }
            });

        }
    });
}

/**
 * 异步加载大区
 */
function getArea(obj_id,check_id,com_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(com_id)=="undefined" || com_id==""){
        com_id = 1;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    $.request({
        url: "/ajax/getAreaByComId/"+com_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
			
            if (msg.data == null) {
                return false;
            }
            $.each(msg.data, function(i, n){
                if(i==check_id){
                    $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                }else{
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }
            });

        }
    });
}

/**
 * 异步门店
 */
function getShop(obj_id,check_id,area_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(area_id)=="undefined" || area_id==""){
        area_id = 1;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    $.request({
        url: "/ajax/getShopByAreaId/"+area_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
			
            if (msg.data == null) {
                return false;
            }
            $.each(msg.data, function(i, n){
                if(i==check_id){
                    $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                }else{
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }
            });

        }
    });
}

/**
 * 异步加载经纪人
 */
function getReal(obj_id,check_id,shop_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(shop_id)=="undefined" || shop_id==""){
        shop_id = 1;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    $.request({
        url: "/ajax/getRealByShopId/"+shop_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
			
            if (msg.data == null) {
                return false;
            }
            $.each(msg.data, function(i, n){
                if(i==check_id){
                    $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                }else{
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }
            });

        }
    });
}

/**
 * 异步加载轨交线路
 */
function getMetro(obj_id,check_id,city_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(city_id)=="undefined" || city_id==""){
        city_id = 1;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    $.request({
        url: "/ajax/getMetroByCityId/"+city_id+"/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
            
            if (msg.data == null) {
                return false;
            }
            $.each(msg.data, function(i, n){
                if(i==check_id){
                    $("<option value=" + i + " selected>" + n + "</option>").appendTo("#" + obj_id);
                }else{
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }
            });

        }
    });
}

/**
* 异步获取板块
*/
function getRegion(obj_id,check_id,district_id,default_name)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(district_id)=="undefined" || district_id==""){
        district_id = 0;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    $.request({
        url: "/ajax/getRegByDistId/" + district_id + "/",
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
            
            if(null == msg.data) {
                return false;
            }
            
            $.each(msg.data, function(i, n){
                if(check_id == i) {
                    $('<option selected="selected" value=' + i + ">" + n + "</option>").appendTo("#" + obj_id);
                } else {
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }                   
            });
        }
    });
}

/**
* 异步获取端口
*/
function getPort(obj_id,check_id,city_id,port_type,default_name, pay_type)
{
    if(typeof(obj_id)=="undefined"){
        return false;
    }
    if(typeof(check_id)=="undefined"){
        check_id = 0;
    }
    if(typeof(city_id)=="undefined" || city_id==""){
        city_id = 0;
    }
    if(typeof(port_type)=="undefined" || port_type==""){
        port_type = 0;
    }
    if(typeof(default_name)=="undefined" || default_name==""){
        default_name  = '';
    }
    if(typeof(pay_type)=="undefined"){
        pay_type = 2;
    }
    $.request({
        url: "/ajax/getPortByCityId/" + city_id + "/" + port_type + "/?type="+pay_type,
        data: "",
        async:false,
        callback: function(msg){
            $("#" + obj_id).empty();
            if(default_name) {
                $("<option value=\"0\">"+default_name+"</option>").appendTo("#" + obj_id);
            }
            
            if(null == msg.data) {
                return false;
            }
            
            $.each(msg.data, function(i, n){
                if(check_id == i) {
                    $('<option selected="selected" value=' + i + ">" + n + "</option>").appendTo("#" + obj_id);
                } else {
                    $("<option value=" + i + ">" + n + "</option>").appendTo("#" + obj_id);
                }                   
            });
        }
    });
}

/**
* 显示百度地图
*/
function showBaiduMap(obj,defaultShowArea){
	if($("#baiduMapArea").length==0){
		$(obj).after('<div id="baiduMapArea" style="display:none"><p><input type="text" id="searchArea" /><input type="button" id="searchAreaButton" value="搜索">&nbsp;&nbsp;坐标XY:<input type="text" id="baiduX" readonly />X<input type="text" id="baiduY" readonly /><input type="button" id="saveXY" value="确认"></p><p id="BaiduMapContain" style="height:500px;width:800px;"></p></div>');
	}
	if($("#baiduMapArea").is(":hidden")){
		$("#baiduMapArea").show();
		var X = $("#X").val();
		var Y = $("#Y").val();
		var map = new BMap.Map("BaiduMapContain");
		map.enableAutoResize() ;//自适应容器大小
		map.addControl(new BMap.NavigationControl());  //右上角，仅包含平移和缩放按钮。
		if(X && Y){
			//var point = new BMap.Point(116.400244,39.92556);
			var point = new BMap.Point(X,Y);
			map.centerAndZoom(point, 12);
			var marker = new BMap.Marker(point);  // 创建标注
			map.addOverlay(marker);              // 将标注添加到地图中
			marker.enableDragging();    //可拖拽
			map.enableScrollWheelZoom();//可以鼠标滚动 缩放比例尺
			marker.addEventListener("dragend",function(e){
				var p = marker.getPosition();//获取marker的位置
				$("#baiduX").val(p.lng);
				$("#baiduY").val(p.lat);
			});
			marker.addEventListener("click",function(e){
				var p = marker.getPosition();//获取marker的位置
				$("#baiduX").val(p.lng);
				$("#baiduY").val(p.lat);
			});
		}else{
			map.centerAndZoom(defaultShowArea, 12);
			map.enableScrollWheelZoom();//可以鼠标滚动 缩放比例尺
		}
		var local = new BMap.LocalSearch(map, {
				renderOptions:{map: map}
			});
		$("#searchAreaButton").click(function(){
			var s = $("#searchArea").val();
			if(s){
				local.search(s);
				local.setInfoHtmlSetCallback(function(rs){
					rs.marker.enableDragging(); 
					rs.marker.addEventListener("dragend",function(e){
						var p = rs.marker.getPosition();//获取marker的位置
						$("#baiduX").val(p.lng);
						$("#baiduY").val(p.lat);
					});
					rs.marker.addEventListener("click",function(e){
						var p = rs.marker.getPosition();//获取marker的位置
						$("#baiduX").val(p.lng);
						$("#baiduY").val(p.lat);
					});
				});

			}
		});
		$("#saveXY").click(function(){
			if($("#baiduX").val() && $("#baiduY").val()){
				$("#X").val($("#baiduX").val());
				$("#Y").val($("#baiduY").val());
			}
		});
		
	}else{
		$("#baiduMapArea").hide();
	}
}


/**
 * 用户注销
 * @returns  bool
 */
function loginout(){
    if(!confirm("确定要注销系统吗?")) return false;
    $.request({
        url: "/login/out",
        callback: function(msg) {
            if (msg.status == 0) {
               location.reload(false);
            }
        }
    });
    return true;
}

$(function (){
    $('body').on('click', '.model_notice .back', function(e){
         $('.model_notice').modal('hide');
         location.reload(false);
    });
    $('body').on('click', '.model_notice .continue', function(e){
         $('.model_notice').modal('hide');
         $('.addmodal').click();
    });
    $('body').on('click', '.model_notice .close', function(e){
         $('.model_notice').modal('hide');
         location.reload(false);
    });
    
    //全选
    $(".checkall").change(function(){
        if($(this).is(":checked")){
            $(".checkall").prop("checked",true)
            $(".checkone").prop("checked",true);
        }else{
            $(".checkall").prop("checked",false)
            $(".checkone").prop("checked", false);
        }
        
    });
    
     //全选
    $(".checkone").change(function(){  
        if($(this).is(":checked")){
            if($(".checkone").length == $(".checkone:checked").length) {
                $(".checkall").prop("checked", true);
            }            
        } else {
            $(".checkall").prop("checked", false);
        } 
    });
	
	//菜单控制
    if(typeof(menu) != "undefined"){
        $("#"+menu).addClass("open");
    }
    if(typeof(moudle) != "undefined"){
        $("#"+menu+" ."+moudle).addClass("active");
    }
	
	/**
	 *联想输入
	*/
	var appendClass = "lx dropdown-menu pull-left";
	var appendObj = null , hideObj=null;
	var lxCityId = $("#cityId").val();
	if(lxCityId=="" || typeof(lxCityId) == "undefined"){
		lxCityId = 0;
	}
	$("input.autoComplete").unbind('keyup').unbind('keypress').keyup(function(e){
		switch ( e.keyCode ) {			
            case 16:case 17:case 18:case 20:case 33:case 34:case 35:case 36:case 45:return;
        }
		_this = $(this);
		var ajaxUrl = _this.attr("url");
		var hideInputName =  _this.attr("toName");
		var thisval =  _this.val();
		var nums =  _this.attr("nums");
		
		if(nums=="" || typeof(nums)=="undefined") nums = 10;
		if(ajaxUrl=="" || hideInputName=="" || typeof(ajaxUrl) == "undefined" || typeof(hideInputName) == "undefined"){
			 return false;
		}

		if(_this.siblings('.lx').length<1){
			_this.after('<ul role="menu" style="display:none" class="'+appendClass+'"></ul>');
		}

		appendObj = _this.siblings('.lx');
		hideObj = _this.siblings('input[name='+hideInputName+']');

		if(thisval==""){
			appendObj.empty().hide();
			hideObj.val($(this).attr(""));
			return false;
		}

		if(appendObj.is(":visible") && appendObj.find("li").length>0){
			if(e.keyCode==40){//向下选择
				if(appendObj.find("li.cur").length>0 && appendObj.find("li.cur~li").length>0){
					appendObj.find("li.cur").removeClass("cur").next("li").addClass("cur");
				}else{
					appendObj.find("li:last").removeClass("cur");
					appendObj.find("li:first").addClass("cur");
				}
				return false;
			}else if(e.keyCode==38){//向上选择
				if(appendObj.find("li.cur").length>0 && appendObj.find("li.cur").prev("li").length>0){
					appendObj.find("li.cur").removeClass("cur").prev("li").addClass("cur");
				}else{
					appendObj.find("li:first").removeClass("cur");
					appendObj.find("li:last").addClass("cur");
				}
				return false;
			}
			else if(e.keyCode==37 || e.keyCode==39 || e.keyCode==13) {//左右键赋值
				if(appendObj.find("li.cur").length>0){
					hideObj.val(appendObj.find("li.cur").attr("id"));
					_this.val(appendObj.find("li.cur").text());
					appendObj.empty().hide();
				}
				return false;
			}
		}

		$.request({
			url: ajaxUrl,
			data: "keyword="+_this.val()+"&nums="+nums+"&cityId="+lxCityId,
			method:"post",
			callback:function(lists){
				 hideObj.val("");
				 if (lists.data != null &&  lists.data!= "" && lists.data.length>0  && typeof(lists.data)!= "undefined"  ) {
					appendObj.empty();		
					$(lists.data).each(function(i, n){
						appendObj.append('<li id="' + n.id + '"><a>'+ n.name + '</a></li>');
					});
                    hideObj.val(lists.data[0].id);//如果不选， 默认选择第一个
					appendObj.find("li").click(function(){                                       
							hideObj.val($(this).attr("id"));
							_this.val($(this).find('a').text());
							appendObj.empty().hide();
                                                        
                                                        var funcName = _this.attr('callback');
                                                        if(typeof(funcName)!="undefined") {
                                                            eval(funcName);
                                                        }
						});
					appendObj.show();
				 }else{
                                     appendObj.remove();;
                                 }
			}
		});
		
	}).blur(function(){
		$(this).siblings('.lx').fadeOut();
	});

});
