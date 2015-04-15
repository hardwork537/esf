/**
 * @abstract  Jquery扩展库 
 * @copyright Sohu-inc Team.
 * @author    Rady (yifengcao@sohu-inc.com)
 * @date      2014-05-28 14:12:18
 * @version   Crm 1.0
 */
;(function($){
	$.fn.extend({
                smodal:function(_config){
                    var config = {
                        title:"", //标题
                        act:"" //隐藏表单代表处理动作
                    }
                    _config && $.extend(config, _config);
                    $(".errortips").hide();
                    $(this).find("form input[name=act]").val("");
                    config.title && $(this).find(".modal-title").html(config.title);
                    config.act ?  $(this).find("form input[name=act]").val(config.act) : $(this).find("form input[name=act]").val("");
                    
                    $(this).modal({
                        backdrop:"static",
                        show:true
                    });
                },
                setmodallist:function(list){
                    try{
                        if(typeof list == "object"){
                            _str ="";
                            for(i=0;i<list.length;i++){
                                _str += '<a class="uline  '+(list[i]['class'] ? list[i]['class']:"")+'"  href="'+(list[i]['url'] ? list[i]['url'] : "javascript:;")+'">'+list[i]['txt']+'</a>';
                            }
                            $(this).find(".model_list").html(_str);
                        } 
                    }catch(e){
                         $(this).find(".model_list").html(""); 
                    }
                },
		formCheck:function(_config){
			var config = {
				showType:"error",
				callback:""
			};
			_config && $.extend(config, _config);
			var _requires = $(this).find("[required=true]");
			var _flag = true;
			_requires.each(function(){
				_require = $(this);
				_notice = _require.attr("info");
				_tagName = _require[0].tagName.toLowerCase();
				if(!_notice){
                                    _flag = false;
                                   return true; 
                                } 
				if(_tagName=="input" || _tagName=="select" || _tagName=="textarea"){
					if(_require.val()==""){
						_require.error();
						$.isFunction($[config.showType]) ? $[config.showType](_notice+"不能为空!") : ($.isFunction(config.showType) && config.showType(_notice+"不能为空!"));
						_flag = false;
						return false;
					}else{
						_require.clearError();
                                                $.clearError();
					}
				}
			});
                        if(_flag==false){
                            return false;
                        }
			_flag && config.callback && $.isFunction(config.callback) && config.callback(); 
			return _flag;
		},
		error:function(){
			$(this).addClass("error");
		},
		clearError:function(){
			$(this).removeClass("error");
		},
		ajaxSubmit:function(_option){
			option = {
				//data:$(this).serialize().toQueryParams(),
                                data:$(this).serialize(),
				url:$(this).attr("action") || "",
				title:$(this).attr("form_tittle") || "",
				type:$(this).attr("method") || "get",
				callback:'',
				isCheck:true, //是否执行表单检查
				formCheck:{
					showType:"error"
				}
			};
			_option && $.extend(option,_option);
			if(option.isCheck){
				option.formCheck.callback = function(){
					$.request(option);
				}
				$(this).formCheck(option.formCheck);
			}else{
				delete option.isCheck;
				delete option.formCheck;
				$.request(option);
			}
			return true;
		},
                delConfirm : function(title,message, callback) {
                    if($(".arrow_tips:visible").length>0) return false;
                    if( title == null ) title = '删除确认';
                    if( message == null ) message = '删除确认改条记录吗?';
                    $(this).after($("#arrow_tips").clone().attr("id","_arrow_tips"));
                    $("#_arrow_tips .title").html(title);
                    $("#_arrow_tips .message").html(message);
                    $("#_arrow_tips").fadeIn("fast");
                    $("#_arrow_tips .close_mylabel").click(function(){
                        $("#_arrow_tips").fadeOut().remove();
                        //if( callback ) callback(false);
                    });
                    $("#_arrow_tips .btn-no").click(function(){
                        $("#_arrow_tips").fadeOut().remove();
                        //if( callback ) callback(false);
                    });
                    $("#_arrow_tips .btn-yes").click(function(result){
                        if( callback ) callback(true);
                    });
                    return ;
                },
                delSuccess:function(message){
                    if( message == null ) message = '删除成功';
                    $("#_arrow_tips").remove();
                    $(this).after($("#del_success_tips").clone().attr("id","_del_success_tips"));
                    $("#_del_success_tips .message").html(message);
                    $("#_del_success_tips").fadeIn("fast");
                     $("#_del_success_tips .close_mylabel").click(function(){
                        $("#_del_success_tips").fadeOut("fast").remove();
                    });
                    $("#_del_success_tips .btn-no").click(function(){
                        $("#_del_success_tips").fadeOut("fast").remove();
                    });
                },
                delFail:function(message){
                    if( message == null ) message = '删除失败';
                    $("#_arrow_tips").remove();
                    $(this).after($("#del_success_tips").clone().attr("id","_del_fail_tips"));
                    $("#_del_fail_tips .message").html(message);
                    $("#_del_fail_tips").fadeIn("fast");
                    $("#_del_fail_tips .close_mylabel").click(function(){
                        $("#_del_fail_tips").fadeOut().remove();
                    });
                    $("#_del_fail_tips .btn-no").click(function(){
                        $("#_del_fail_tips").fadeOut().remove();
                    });
                },
                
	});
	$.extend({
                resetNoticeModal:function(){
                    $(".model_notice .model_title").html("");
                    $(".model_notice .model_info").html("");
                    $(".model_notice .model_list").html("");
                },
		error:function(msg){
                    $(".errortips span").html(msg);
                    $(".errortips").show();
		},
                clearError:function(msg){
                    $(".errortips span").html();
                    $(".errortips").hide();
		},
                success:function(){
                    $(".errortips").hide();
                },
                info:function(msg){
			
		},
                errorModal:function(title,msg,list){
                    $.resetNoticeModal();
                    title && $("#model_fail .model_title").html(title);
                    msg && $("#model_fail .model_info").html(msg); 
                    $("#model_fail").setmodallist(list);  
                    $("#model_fail").modal({backdrop:"static",show:true})
		},
		successModal:function(title,msg,list){
                    $.resetNoticeModal();
                    title && $("#model_success .model_title").html(title);
                    msg && $("#model_success .model_info").html(msg); 
                    $("#model_success").setmodallist(list);  
                    $("#model_success").modal({backdrop:"static",show:true})
		},
                
		request:function(_option){
			option = {
				data:null,
				url:"",
				title:"",
                                async:true, 
				type:"get",
				callback:'',
			}
			_option && $.extend(option,_option);
			option.data && (option.data ="_t="+$.time()+"&"+option.data) ;
			ajax_option = {
				error:function(){
                                    $.errorModal("系统超时( timeout ),请稍后再试!");
				},
				success:function(data){
					if(typeof data != "object"){
						try{
							data = $.parseJSON(data);
							typeof data != "object"  && (function(){$.errorModal("请检查系统错误（warnning）或者返回格式错误");
							return false;})();
						}catch(e){
							typeof data != "object"  && (function(){$.errorModal("请检查系统错误（warnning）或者返回格式错误");
							return false;})();
						}
					}
					$.isFunction(option.callback)&&option.callback(data);
				},
				timeout:6000,
				dataType:"html",
			};
			$.extend(ajax_option,option);
			$.ajax(ajax_option);
		},
		time:function(){
			return Date.parse(new Date())/1000;
		}
	})
})(jQuery);