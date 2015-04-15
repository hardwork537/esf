$(document).ready(function(){
	$.ajax({
		type: "POST",
		url: "/ajax/wj3_begin.php",
		dataType: "json",
		success: function( result ){
			if ( result!=null && result!="" ) {
				$("#beforeLogin").remove();
				$("#afterLogin").show().html(result.login);
				if(result.is_person_type){
					$("#personQuickLink").show();
					$("#vipQuickLink").hide();
				}
			}
		}
	});
});