/**
 * 上传截图坐标
 * @param opt_div 操作div
 * @param img_call_back 回调函数
 * @return
 */
function JsUploadImgSend(opt_div, img_call_back) {
	var brokerNo = $("#realtorNo").val()=="" || $("#realtorNo").val()==undefined ? "" : $("#realtorNo").val();
	$.ajax( {
		url : base_url+"ajax/uploadJsImg?opt=sendimg",
		data : "fname=" + $("#" + opt_div + "_fname").val() 
				+ "&x1=" + $("#" + opt_div + "_x1").val()
				+ "&y1=" + $("#" + opt_div + "_y1").val()
				+ "&x2=" + $("#" + opt_div + "_x2").val()
				+ "&y2=" + $("#" + opt_div + "_y2").val()
				+ "&brokerNo=" + brokerNo,
		type : "post",
		dataType : "json",
		success : function(sdata) {
			if (sdata.code == "ok") {
				if (typeof(img_call_back) === 'function') {
					img_call_back.call(this, sdata.res);
				} else {
					alert("上传成功");
				}				
				$("#btn_save").attr("disabled", true); //用attr来设置、获取jQuery没有封装的属性；	
				CountDown(10);
				ias.setOptions({'disable':true,'hide':true});
			} else {
				alert(sdata.msg);
			}
		}
	});
}

var isinerval;	
function CountDown(times) { //给function包上一个$()是让它在ready的时候执行	
    if (times < 0) {
        $("#btn_save").val("上传").attr("disabled", false);
        $("#btn_save").hide();
        //times = 10;
        return;
    }
    $("#btn_save").val("上传("+times+")");
    times--;    
    isinerval = setTimeout("CountDown("+times+")", 1000)
}

/**
 * 图片上传截取处理
 * 
 * @param file_btn_id
 *            上传图片按钮id
 * @param opt_div
 *            控制图片处理div标签id
 * @param ratio
 *            选择范围比例
 * @param img_call_back
 *            处理成功后的回调函数
 * @return
 */
function JsUploadImg(file_btn_id, opt_div, ratio, img_call_back) {
	$("#btn_save").remove();
	if( isinerval !='' ) {
		clearTimeout(isinerval);
	}
	$.ajaxFileUpload( {
		url :base_url+"ajax/uploadJsImg?opt=upimg&file=" + file_btn_id,
		secureuri : false,
		fileElementId : file_btn_id,
		dataType : "json",
		success : function(data, status) {
			if (data.code != "ok") {
				alert(data.msg);
			} else {
				$(".imgareaselect-selection").parent().remove();
				$(".imgareaselect-outer").remove();
				opt_img = opt_div + "_img";
				$("#" + opt_div).html("<img id=" + opt_img + " src=''/>"
									+ "<input type='hidden' id='" + opt_div+ "_x1'/>"
									+ "<input type='hidden' id='"+ opt_div + "_x2'/>"
									+ "<input type='hidden' id='" + opt_div+ "_y1'/>"
									+ "<input type='hidden' id='"+ opt_div + "_y2'/>"
									+ "<input type='hidden' id='"+ opt_div + "_fname'/>"
									+ "<input type='button' value='&nbsp;上传&nbsp;' id='btn_save'  class='save_file2' onclick='JsUploadImgSend(\""+opt_div+"\","+img_call_back+")'/>"
								);
				iniw = 50;
				bl = ratio.split(":");
				iniy = iniw * bl[1] / bl[0];
				$("#" + opt_img).attr("src", base_url+"ajax/getCutImg?f=" + data.src);
				$("#" + opt_div + "_fname").val(data.src);
				$("#" + opt_div + "_x1").val(0);
				$("#" + opt_div + "_x2").val(iniw);
				$("#" + opt_div + "_y1").val(0);
				$("#" + opt_div + "_y2").val(iniy);
				ias = $("#" + opt_img).imgAreaSelect( {
					x1: 0,
					x2: iniw,
					y1: 0,
					y2: iniy,
					handles : true,
					aspectRatio : ratio,
					instance: true, 
					onSelectEnd : function(img, selection) {
						$("#" + opt_div + "_x1").val(selection.x1);
						$("#" + opt_div + "_x2").val(selection.x2);
						$("#" + opt_div + "_y1").val(selection.y1);
						$("#" + opt_div + "_y2").val(selection.y2);
					}
				});
			}
		},
		error: function(data, status, e){  									
			alert(e);
		}
	});
}
