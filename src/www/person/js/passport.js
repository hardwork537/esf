$(function(){		
	//通行证登录
	$("#passport_login_bu").click(function(){
		var username = $('#usernamein').val();
		var password = $('#passwordin').val();				
		if(username==''||password==''){
			$('#noticein').html('请输入用户名和密码');
			$('#noticein').show();
			return false;	
		}else{
			$.ajax({
				url:"/ajax/passport_check.json.php",
				type:"post",
				dataType:'json',
				data:{
					username:username,
					password:password
				},
				success: function(data){    
					var status = data.status;
					var person = data.person;
					var reurl = $("#passport_reurl").val();
					var objDate = new Date();
					
					if(status === '0'){
						if(person>0){
							if(reurl != ''){
								var ru = reurl;
							}else{
								var ru = 'http://my.esf.focus.cn/index.php';
							}							
						}else{
							var ru = 'http://my.esf.focus.cn/login.php';
						}								
						var passport_url = 'http://passport.sohu.com/sso/login_js.jsp';
						var passport_params = {
							'userid'       : username,
							'password'     : password,
							'appid'        : 1028,
							'persistentcookie' : 1,
							'pwdtype'      : 0,
							't'            : objDate.getTime(),
							'ru'           : ru 
						};
						tuan_post(passport_url, passport_params);
				
					}else if(status === '3'){
						$('#noticein').html('用户名或密码错误');
						$('#noticein').show();
					}else{
						$('#noticein').html('登录失败');
						$('#noticein').show();
					}	
				}
			})									
		}		
	})
	//旧账号登录			
	$('#person_login_bu').click(function(){
		var username = $('#usr_name2').val();
		var password = $('#password2').val();
		if(username==''||password==''){
			$('#notice_person').html('请输入用户名和密码');
			$('#notice_person').show();
			return false;	
		}else{
			$.ajax({
				url:"/ajax/passport_olduser.json.php",
				type:"post",
				dataType:'json',
				data:{
					username:username,
					password:password
				},
				success: function(data){    
					var status = data.status;			
					if(status === '2'){	
						$('#usr_name2').val(data.person);			
						showDiaBMsg();									
					}else{
						$('#notice_person').html(data.msg);
						$('#notice_person').show();
					}
				}
			})									
		}			
	})	
})
//旧帐号升级为通行证
function update_submit(){
	var passport = $('#uppassport').val();
	var username = $('#usr_name2').val();
	var password = $('#uppassword').val();
	if(passport==''||password==''){		
		$('#notice_update').html('请输入旧账号密码');
		$('#notice_update').show();
		return false;	
	}else{
		var reg = /^[a-zA-Z0-9]{1,16}$/;
		if(!reg.test(passport)){
			$("#notice_update").html('用户名为1-16个字母或数字');
			$("#notice_update").show();	
			$("#notice_update").focus();
			return false;
		}else{
			$("#notice_update").hide();	
		}
		$.ajax({
			url:"/ajax/passport_update.json.php",
			type:"post",
			dataType:'json',
			data:{
				passport:passport,
				username:username,
				password:password
			},
			success: function(data){    
				var status = data.status;		
				if(status == '2'){
					showDiaGMsg(data.userid,true);
				}else{
					$("#notice_update").html(data.msg);
					$("#notice_update").show();	
				}
			}
		})									
	}			
}
//旧账号绑定已有通行证
function bind_submit(){
	var passport = $('#bindpassport').val();
	var password = $('#bindpassword').val();
	var username2 = $('#usr_name2').val();
	var password2 = $('#password2').val();
	if(passport==''||password==''){
		$('#notice_bind').html('请输入用户名和密码');
		$('#notice_bind').show();
		return false;	
	}else{
		$.ajax({
			url:"/ajax/passport_bind.json.php",
			type:"post",
			dataType:'json',
			data:{
				passport:passport,				
				password:password,
				username2:username2,
				password2:password2
			},
			success: function(data){    
				var status = data.status;		
				if(status == '1'){
					showDiaGMsg(passport,false);
				}else{
					$("#notice_bind").html(data.msg);
					$("#notice_bind").show();	
				}
			}
		})									
	}			
}
//JS以POST方法访问某URL
function tuan_post(URL, PARAMS) {        
	var temp = document.createElement("form");        
	temp.action = URL;        
	temp.method = "post";        
	temp.style.display = "none";        
	for (var x in PARAMS) {        
		var opt = document.createElement("textarea");        
		opt.name = x;        
		opt.value = PARAMS[x];        
		temp.appendChild(opt);        
	}        
	document.body.appendChild(temp);        
	temp.submit();        
	return temp;        
};		
//关闭
function showDiaAMsg(re){
	$.dialog($('#diaA').html(), {
		confirmButClass: 'ok',
		cancelButClass: 'close_f',
		initFun: initFun
	});	
	switch(re){
		case 'B':$('#msgreturn').bind('click',function(){showDiaBMsg();});break;
		case 'E':$('#msgreturn').bind('click',function(){showDiaEMsg();});break;
		case 'F':$('#msgreturn').bind('click',function(){showDiaFMsg();});break;			
	}	
}
//导入
function showDiaBMsg(){
	$('.dialog').remove();
	$.dialog($('#diaB').html(), {
		confirmButClass: 'ok',
		cancelButClass: 'close_f',
		initFun: initFun
	});	
	$('#updateusername').html($('#usr_name2').val());	
}
//升级
function showDiaEMsg(){
	$('.dialog').remove();
	$.dialog($('#diaE').html(), {
		confirmButClass: 'ok',
		cancelButClass: 'close_f',
		initFun: initFun
	});	
	$('#uppassport').val($('#usr_name2').val());	
	$('#uppassport').focus();
	$('#uppassport').blur(function(){
		var reg = /^[a-zA-Z0-9]{1,16}$/;
		if(!reg.test($(this).val())){
			$("#notice_update").html('用户名为1-16个字母或数字');
			$("#notice_update").show();	
			$("#notice_update").focus();
		}else{
			$("#notice_update").hide();
		}		
	});	
}
//绑定
function showDiaFMsg(){
	$('.dialog').remove();
	$.dialog($('#diaF').html(), {
		confirmButClass: 'ok',
		cancelButClass: 'close_f',
		initFun: initFun
	});		
}
//升级或绑定成功
function showDiaGMsg(pas,up){
	$('.dialog').remove();
	$.dialog($('#diaG').html(), {
		confirmButClass: 'ok',
		cancelButClass: 'close_f',
		initFun: initFun
	});	
	if(up){
		$("#upfish_info").html('升级搜狐通行证成功！');	
	}else{
		$("#upfish_info").html('绑定搜狐通行证成功！');	
	}
	$('#fpassportspan').html(pas);
	$('#usernamein').val(pas);
	$('#passport_login_a').bind('click',function(){
		$('.dialog').remove();		
		$('#usernamein').focus();
		$('#usernamein').blur();
		$('#passwordin').val('');	
		$('#maintab1').attr("class",'curr br');
		$('#maintab2').attr("class",'');
		$('#tab1').show();
		$('#tab2').hide();
	});	
	$('#passport_login_b').bind('click',function(){	
		$('#usernamein').focus();
		$('#usernamein').blur();
		$('#passwordin').val('');	
		$('#maintab1').attr("class",'curr br');
		$('#maintab2').attr("class",'');
		$('#tab1').show();
		$('#tab2').hide();
	});	
}
function clearInput(){
	$('#passwordin').val('');	
	$('#password2').val('');
}