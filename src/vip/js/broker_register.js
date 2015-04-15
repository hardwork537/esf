	$(document).ready(function(){
		city_id = $('#city_id').val();
        district_id = $('#district_id').val();
		form_status = new Array();
		$.each($(".reg_msg"), function(i, n){
			form_status[i] = 0;
		});		
		$("#formUser").submit(function(){
			$(".lb_txt").focus();
			$("#reg_name").blur();
			$("#reg_password").blur();
			$("#reg_password2").blur();
			$("#real_name").blur();
			validate_option($(".options:eq(0)"));
			validate_option($(".options:eq(1)"));
            validate_option($(".city_details_box"));
			$("#company").blur();
			$("#store").blur();
			$("#mobile").blur();
			$("#verify_code").blur();
			validate_xieyi();
			if(jQuery.inArray(0, form_status) == -1){
				return true;
			}else{
				return false;
			}
		});
		
		$('#reg_agree').click(function(){
			if($(this).attr('checked') == 'checked'){
				$('#submit_btn').removeAttr('disabled');
				$('#submit_btn').addClass('btn1');
				$('#submit_btn').removeClass('btn_disable');
			}else{				
				$('#submit_btn').attr('disabled','disabled');
				$('#submit_btn').removeClass('btn1');
				$('#submit_btn').addClass('btn_disable');
			}
		})

		//账号
		$("#reg_name").blur(function(){
			var reg = /^\w{5,15}$/;
			var str = $(this).val();
			var obj = $(this);
			if(str == '') {showTip(obj, 1, '用户名不能为空'); return; }
			if(!reg.test(str)){
				showTip(obj, 1, '用户名格式不正确');//文字待定
			}else{
				$.get("/ajax/getAccount?rnd="+Math.random()*5, {accname: str},function(data){
					if(data == null){
						showTip(obj, 0, '');
					}else{
						showTip(obj, 1, '用户名已存在');
					}
				},"json");
			}
		});
		//密码
		$("#reg_password").blur(function(){
			var reg = /^\w{6,16}$/;
			var str = $(this).val();
			var obj = $(this);
			if(str == '') {showTip(obj, 1, '密码不能为空'); return;}
			if(!reg.test(str)){
				showTip(obj, 1, '密码格式不正确');
			}else{
				showTip(obj, 0, '');
			}
		});
		//重复密码
		$("#reg_password2").blur(function(){
			var str = $(this).val();
			var str1 = $("#reg_password").val();
			var obj = $(this);
			if(str == '') {showTip(obj, 1, '确认密码不能为空'); return;}
			if(str != str1){
				showTip(obj, 1, '两次密码输入不一致');
			}else{
				showTip(obj, 0, '');
			}
		});
		//真实姓名
		$("#real_name").blur(function(){
			var reg = /^[\u4e00-\u9fa5]{2,6}$/;
			var str = $(this).val();
			var obj = $(this);
			if(str == '') {showTip(obj, 1, '姓名不能为空'); return;}
			if(!reg.test(str)){
				showTip(obj, 1, '请输入2-6个中文字');
			}else{
				showTip(obj, 0, '');
			}
		});

		//所属公司
        var flag = false;
		$("#company").keyup(function(){
			var str = $(this).val();
			var obj = $(this);
            var $list = obj.parent().find(".rg_data_list");
			$.post("/ajax/getDictCompanyByCity", {q_word: str, xz_city_id: $('#city_id').val()},function(data){
                $list.empty();
				if(data && data.length > 0){
					$.each(data, function(i, n){
						$('<li><a href="javascript:;" val="'+n.company_id+'">'+n.company_name_abbr+'</a></li>').appendTo($list);
					});
                    $list.fadeIn();
                    $list.on("click","li",function(e){
                        e.stopPropagation();
                        flag = true;
                        $(this).parent().siblings(".placeholder").hide();
                        $('#company').val($(this).text());
                        $('#company_id').val($(this).find("a").attr("val"));
                        flag = false;
                        $('#company').blur();
                    });
				}
				
			},"json");
		});
		$("#company").blur(function(){
            var obj = $(this);
            setTimeout(function(){
                if(flag){
                    return;
                }
                var str = obj.val();
                var $list = obj.parent().find(".rg_data_list");
                if(str == '') {showTip(obj, 1, '所属公司不能为空'); return;}
                $.post("/ajax/getCompanyExist", {city_id: $('#city_id').val(), company_name_abbr: str},function(data){
                    if(data == null){
                        $.post("/ajax/getCityCrmInfo", {city_id: $('#city_id').val()},function(crmData){
                            if(crmData){
                                showTip(obj, 1, '暂时没有这家公司，请致电'+crmData+' 添加公司');
                            }
                        },"json");
                    }else{
                        showTip(obj, 0, '');
                    }
                    $('#company_id').val(data);
                },"json");
                $list.fadeOut();
            },200);
		});
		//所属门店
		$("#store").keyup(function(){
	        var $list = $(this).parent().find(".rg_data_list");
	        var str = $(this).val();
	        $.post("/ajax/getShopByComID", {q_word: str, xz_company_id: $('#company_id').val()},function(data){
	            $list.empty();
	            var content = "";
	            if(data){
	                $.each(data, function(i, n){
	                    content += "<li><a href='javascript:;' val='"+ n.shop_id+"'>"+n.shop_name+"</a></li>";
	                });
	            }
	            content += "<li><a href='javascript:;' val='0'>其他门店</a></li>";
	            $list.append(content);
	            $list.fadeIn();
	            $list.on("click","li",function(e){
                    e.stopPropagation();
                    flag = true;
                    $(this).parent().siblings(".placeholder").hide();
	                $('#store').val($(this).text());
	                $('#shop_id').val($(this).find("a").attr("val"));
	                $('#store').blur();
                    flag = false;
	                $list.fadeOut();
	            });

	        },"json");
	    });
		$("#store").blur(function(){
            var thiz = $(this);
            setTimeout(function() {
                    if (flag) {
                        return;
                    }
                    var reg = /^([a-z0-9A-Z]|[\u4e00-\u9fa5]){2,15}$/;
                    var str = thiz.val();
                    var obj = thiz;
                    var $list = obj.parent().find(".rg_data_list");
                    $list.fadeOut();
                    if (str == '') {
                        showTip(obj, 1, '所属门店不能为空');
                        return;
                    }
                    if (!reg.test(str)) {
                        showTip(obj, 1, '请输入2-15个字');
                    } else {
                        showTip(obj, 0, '');
                    }
                }
                ,200);

		});
        $(".rg_data_list").mouseenter(function(){
            $(this).show();
        });
        $(".rg_data_list").mouseleave(function(){
            $(this).hide();
        });
        $(".rg_data_list").click(function(){
            $(this).show();
        });
		//手机
		$("#mobile").blur(function(){
			var reg=/^[1][3|4|5|7|8]\d{9}$/;
			var str = $(this).val();
			var obj = $(this);
			if(str == '') {showTip(obj, 1, '手机号码不能为空'); return;}
			if(!reg.test(str)){
				showTip(obj, 1, '手机号码格式不正确');
			}else{
				$.get("/ajax/getRealtorTel?rnd="+Math.random()*5, {broker_tel: str}, function(data){
					if(data == null){
						showTip(obj, 0, ''); 
					}else{
						$.post("/ajax/getCityCrmInfo", {city_id: $('#city_id').val()},function(crmData){
							if(crmData){
								showTip(obj, 1, '该号码已注册过，如有疑问请拨'+crmData);
							}
						},"json");
					}
				},"json");
			}
		});
		//验证码
		$("#verify_code").keyup(function(){
			var str = $(this).val();
			if(str.length < 4){
				return;  
			}
			var obj = $(this);
			if(str == '') {showTip(obj, 1, '验证码不能为空'); return;}
			$.get("/ajax/getCheckcode?code="+str, function(data){
				if(data == 1){
					showTip(obj, 0, '');
				}else{
					showTip(obj, 1, '验证码输入不正确');
				}
			});
		});
        $("#verify_code").blur(function(){
            var thiz = $(this);
            setTimeout(function(){
                if(thiz.val() == "请输入验证码"){
                    showTip(thiz, 1, '验证码不能为空'); return;
                }
            },200)
        });
	});
	
	function validate_xieyi(){
		var obj = $('#reg_agree');
		if($('#reg_agree').attr('checked') == undefined){
			showTip(obj, 1, '请勾选用户协议');
		}else{
			showTip(obj, 2, '');
		}
	}
	
	function showTip(obj, errno, message)
	{
		var okClass = 'success png';
		var failClass = 'error';
		var index = $('.reg_msg').index(obj.siblings('.reg_msg'));
		if(errno == 0){
			obj.removeClass('lb_error');
			form_status[index] = 1;
			$('.reg_msg:eq('+index+')').empty();
			$('.reg_msg:eq('+index+')').html("<span class='"+okClass+"'></span>");			
		}else if(errno == 1){
			obj.addClass('lb_error');
			form_status[index] = 0;
			$('.reg_msg:eq('+index+')').empty();
			$('.reg_msg:eq('+index+')').html("<span class='"+failClass+"'></span>");
		}else{
			obj.addClass('lb_error');
			form_status[index] = 1;
			$('.reg_msg:eq('+index+')').empty();
		}
		$('.reg_msg:eq('+index+') span').text(message);
	}
	
	//验证所在城市

	function validate_option(obj){
		var obj = obj.siblings('input');
		var str = obj.val();
		var errMsg = '';
		switch(obj.attr('id')){
			case 'city_id':
				errMsg = '请选择所属城市';
				break;
			case 'district_id':
				errMsg = '请选择城区板块';
				break;
			case 'hot_area_id':
				errMsg = '请选择板块';
				break;
			default:
				errMsg = '';
				break;
		}
		if(str == 0){
			switch(obj.attr('id')){
				case 'city_id':
					//showTip($('#district_id').parent(), 1, '请选择城区板块');
					$('#district_id').siblings('span').text($('#district_id').siblings('.options').children('a:eq(0)').text());
					$('#district_id').val(0);
					$('#hot_area_id').siblings('span').text($('#hot_area_id').siblings('.options').children('a:eq(0)').text());
					$('#hot_area_id').val(0);
					city_id = $('#city_id').val();
					$('#district_id').parent().siblings('.reg_msg').empty();
					break;
				case 'district_id':
					$('#hot_area_id').siblings('span').text($('#hot_area_id').siblings('.options').children('a:eq(0)').text());
					$('#hot_area_id').val(0);
                    district_id = $('#district_id').val();
					break;
				default:
					break;
			}
			showTip(obj.parent(), 1, errMsg);
		}else{
			showTip(obj.parent(), 0, '');
			if(obj.attr('id') == 'city_id' && city_id != obj.val()){
				
				//showTip($('#district_id').parent(), 1, '请选择城区板块');
				$('#district_id').siblings('span').text($('#district_id').siblings('.options').children('a:eq(0)').text());
				$('#district_id').val(0);
				$('#hot_area_id').siblings('span').text($('#hot_area_id').siblings('.options').children('a:eq(0)').text());
				$('#hot_area_id').val(0);
				city_id = $('#city_id').val();
				$('#district_id').parent().siblings('.reg_msg').empty();
			}
            else if(obj.attr('id') == 'district_id' && district_id != str){
                $('#hot_area_id').siblings('span').text($('#hot_area_id').siblings('.options').children('a:eq(0)').text());
                $('#hot_area_id').val(0);
                $('#hot_area_id').parent().siblings('.reg_msg').empty();
                district_id = $('#district_id').val();
            }
			else if(obj.attr('id') == 'district_id' && $('#hot_area_id').val() == 0){
				showTip($('#hot_area_id').parent(), 1, '请选择板块');				
			}
			else{
				obj.parent().removeClass('lb_error');
				//showTip(obj.parent(), 0, '');
			}
		}
	}