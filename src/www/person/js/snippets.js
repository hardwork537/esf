$(function() {
    var $textInput = $('.main_f input.usr_input,.main_f input.pwd_input');
    var $labelHold = $('.main_f label.placeholder');
    var $forgetPwd = $('.forgetPwd');
    var $content = $('.conttent1_f,.conttent2_f');
    var $tabHead = $('.login_tab_f li');
    var $bk_hold = $('.bk_hold');
    $labelHold.click(function() {
        $(this).hide().prev().focus();
    });
    $textInput.focus(function() {
        $(this).siblings('.bk_hold').show();
		$(this).next().hide();
    })
    $textInput.blur(function() {
        var $input = $(this);
        $(this).siblings('.bk_hold').hide();
        if($input.val() === '') {
            $input.next().show();
        } else {
            $input.next().hide();
        }
    }).blur();
    $forgetPwd.hover(function() {
        var $tip = $(this).next();
        if($tip.css('display') === 'none')  {
            $tip.show();
        } else {
            $tip.hide();
        }
    });
    $tabHead.each(function(index) {
        var $li = $(this);
        $li.click(function() {
            $content.hide();
            $tabHead.removeClass('curr');
            $li.addClass('curr');            
            $($li.attr('data')).show();
        });
    });
});
function initFun(){
	var $forgetPwd = $('.forgetPwd_f');
	var $textInput = $('input.old_usr,input.old_pwd');
	var $labelHold = $('label.old_hold');
	$forgetPwd.hover(function() {
		var $tip = $(this).next();
		if($tip.css('display') === 'none')  {
			$tip.show();
		} else {
			$tip.hide();
		}
	});
	$labelHold.click(function() {
		$(this).hide().prev().focus();
	});
	$textInput.focus(function() {
		$(this).siblings('.bk1_hold').show();
		$(this).next().hide();
	})
	$textInput.blur(function() {
		var $input = $(this);
		$(this).siblings('.bk1_hold').hide();
		if($input.val() === '') {
			$input.next().show();
		} else {
			$input.next().hide();
		}
	}).blur();
}		