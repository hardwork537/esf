// JavaScript Document
$(document).ready(function(){
	//下拉选择层
	$(".item-drop-list").hide();
	$(".item-drop").click(
		function(){
			if(!$(this).children(".item-drop-list").is(":animated")){//�ж��Ƿ��ڶ���
				$(this).children(".item-drop-list").fadeIn();
				$(".item-drop-list").not($(this).children(".item-drop-list")).fadeOut(0);
				return false;
			}
		});
	$(".item-drop-list span a").click(function(event){
		var text = $(this).text()
		var svalue = $(this).attr("src");
		//alert($(this).parent().parent().parent(".item-drop").children("em").text());
		$(this).parent().parent().parent(".item-drop").children("em").text(text);
		$(this).parent().parent().parent(".item-drop").children("input[type=hidden]").val(svalue);
		$(this).parent().parent().fadeOut();
		$("#search_form").attr("action", $(this).attr("target"));
		return false;
	});
	$(document).click(function(event){
		$(".item-drop-list").fadeOut(200);
	});
});