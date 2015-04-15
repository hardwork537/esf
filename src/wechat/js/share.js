var initFunc = function () {
        $(".houseshare li .col_right").bind("touchstart", function () { $(this).addClass("active"); });
        $(".houseshare li .col_right").bind("touchend", function () { $(this).removeClass("active"); });
		
		$(".toptab > li").click(function(){
			var index = $(this).index();
			$(this).addClass('active');
			$(this).siblings().eq(0).removeClass('active');
			$("#scroller > ul").eq(index).show().siblings().hide();
			if($.trim($("#scroller > ul").eq(index).html()) == '')
			{
				$("#scroller").children('img').show();
				$.ajax({
					type: "POST",
					url: "ajax",
					data: {"do":"sharelist"},
					async: false,
					success: function(data){
						var jsonData;
						if(data.indexOf('status') < 0)
						{
							jsonData = {"status":1, "content":data};
						}
						else
						{
							jsonData = eval("("+data+")");
						}
						if(jsonData.status == -1)
						{
							window.location.href = jsonData.url;
						}
						else
						{
							$("#scroller").children('img').hide();
							$("#scroller > ul").eq(index).html(jsonData.content);
							$(".toptab > li").unbind('click');
							$(".round_share").unbind('click');
							initFunc();
						}
					}
				});
			}
		});

		$(".round_share").click(function(){
			var houseUrl = $(this).parent().siblings().eq(1).attr("hl");
			var houseTitle = $(this).parent().siblings().eq(0).find(".tittle").html();
			var houseImg = $(this).parent().siblings().eq(2).val();
			$.ajax({
				type: "POST",
				url: "ajax",
				data: {'do':'share'},
				success: function(data){
					var jsonData = eval("("+data+")");
					window.location.href = houseUrl;
				}
			});
		});
    }
    $(initFunc);

	function houseDetail(url)
	{
		$.ajax({
				type: "POST",
				url: "ajax",
				data: {'do':'share'},
				success: function(data){
					var jsonData = eval("("+data+")");
					window.location.href = url;
				}
			});
	}