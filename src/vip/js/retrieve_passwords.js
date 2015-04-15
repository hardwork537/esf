/**
 * [二手房用户密码找回JS]
 * @author [niekai]
 * @data [2013-08-25]
 */


$(function(){

	var validate_txt= {
		vtxt_1: '用户名不能为空',
		vtxt_2: '手机号码不能为空',
		vtxt_3: '用户名或者手机号码输入不正确',
		vtxt_4: '验证码输入不正确',
		vtxt_5: '验证码不能为空',
		vtxt_6: '密码格式不正确',
		vtxt_7: '密码不能为空',
		vtxt_8: '两次输入密码不一致',
		vtxt_9: '确认密码不能为空'
	}

	/*expors global for ifream call*/
	window.step_callBack = function(id, msg) {
		var _el = $('#' + id);
		_el.show().find('span').text(msg);
	}
		
	var nromal = (function(){
		/*var initialize*/
		var wapepr_el = $('.step_wapper'),
			inputs_el = $('input[type]', wapepr_el),
			form_el = $('form', wapepr_el);

		/*validate all*/
		function validate_normal() {
			var _pass = true;
			inputs_el.each(function(){
				var _show_el = $(this).parent().next();
				if ($(this).val().length ==0) {
					_show_el.show().find('span').text(validate_txt[$(this).attr('check')]);
					_pass = false;
				} else {
					wapepr_el.attr('id') == 'step_03' || _show_el.hide();
				}
			});
			return _pass;
		}

		/*event handler process*/
		form_el.submit(function(){
			if(!validate_normal()) {
				return false;
			}
		});
	})();	



	/*validate for setp_03*/
	var validate_for_step03 = (function(){

		var wapper_step03 = $('#step_03'),
			input_el_A = $('input.msg_A'),
			input_el_B = $('input.msg_B'),
			el_A_msg = input_el_A.parent().next(),
			el_B_msg = input_el_B.parent().next(),
			el_A_currect = input_el_A.parent().next().next(),
			el_B_currect = input_el_B.parent().next().next(),
			form_el_step03 = $('form', wapper_step03);

		var passAll = false;
		var reg = /^[\w]{6,12}$/;

		input_el_A.bind({
			keyup: function(){
				if (input_el_A.val().match(reg)) {
					passA = true;
					el_A_msg.hide();
					el_A_currect.show();
				} else {
					el_A_currect.hide();
				}
				isNull($(this), 'msg_A');
				isSame();
			}
		});

		input_el_B.bind({
			keyup: function() {
				isSame();
				isNull($(this), 'msg_B');
			}
		});

		/*compare input_A and input_B*/
		function isSame() {
			/*when the input_A is currect and input_B.val>0 ,then compare*/
			if (input_el_A.val().match(reg) &&　input_el_B.val().length > 0) {
				if (input_el_A.val() == input_el_B.val()) {
					el_B_currect.show();
					el_B_msg.hide();
					passAll = true;
				} else {
					el_B_msg.show().find('span').text(validate_txt.vtxt_8);
					el_B_currect.hide();
					passAll = false;
				}
			} else {
				el_B_currect.hide();
			}
		}

		function isNull(_el, msgClass) {
			if (_el.val().length == 0) {
				_el.addClass(msgClass);
			} else {
				_el.removeClass(msgClass);
			}

		}

		form_el_step03.submit(function(){
			/*when submit,validate the inputA is match the reg*/
			if (!input_el_A.val().match(reg) && input_el_A.val().length >0) {
				el_A_msg.show().find('span').text(validate_txt.vtxt_6);
				return false;
			}
			if (!passAll) {
				return false;
			}
		});
	})();

});
