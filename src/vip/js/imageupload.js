var uploaded_num = 0;//新上传成功图片的个数
function SWFImage(){
	var login_name = arguments[0] ? arguments[0] : "";
	var login_id = arguments[1] ? arguments[1] : 0;
	var upload_limit = arguments[2] ? arguments[2] : 0;
	var buttonId = arguments[3] ? arguments[3] : "";
	var divBox = arguments[4] ? arguments[4] : "";
	var prefix_1 = arguments[5] ? arguments[5] : "";
	var prefix_2 = arguments[6] ? arguments[6] : "";
	var product = "esf";
	var watertype = $("#"+buttonId).attr("water") =="ture" || $("#"+buttonId).attr("water") == undefined ? 1 : 0;
	var file_limit = "4 MB";
	uploaded_num = 0;//每次函数调用归零
	var already_num = $("#"+divBox).find("li.ms").length;
	var show_button = $("#"+buttonId).attr("src") =="" || $("#"+buttonId).attr("src") == undefined ? "uploading.png" : $("#"+buttonId).attr("src");
	var show_width = $("#"+buttonId).attr("width") =="" || $("#"+buttonId).attr("width") == undefined ? 80 : $("#"+buttonId).attr("width");
	var show_height = $("#"+buttonId).attr("height") =="" || $("#"+buttonId).attr("height") == undefined ? 29 : $("#"+buttonId).attr("height");
	var realtorNo = $("#realtorNo").val()=="" || $("#realtorNo").val()==undefined ? "" : $("#realtorNo").val();
	var companyNo = $("#companyNo").val()=="" || $("#companyNo").val()==undefined ? "" : $("#companyNo").val();
	var setting = {
		upload_url: base_url+"ajax/multiUpload/",
		post_params: {"PHPSESSID": "",'loginName':login_name,'loginId':login_id,'product':product,'watertype':watertype,'realtorNo':realtorNo,'companyNo':companyNo},
		file_size_limit : file_limit,
		file_types : "*.jpg;*.jpeg;*.png;*.gif;",
		file_types_description : "JPG Images",
		file_upload_limit : 0,
		file_queue_limit : 0,
		swfupload_loaded_handler : function(){},
		file_queued_handler : fileQueued_new,
		file_queue_error_handler : fileQueueError_new,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler: uploadStart_new,
		upload_error_handler : uploadError_new,
		upload_success_handler : uploadSuccess_new,
		upload_complete_handler : uploadComplete_new,
		upload_progress_handler : uploadProgress,
		button_image_url : show_button,
		button_placeholder_id : buttonId,
		button_width: show_width,
		button_height: show_height,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		flash_url : src_url+"js/swfupload/swfupload.swf",
		custom_settings : {
		},
		debug: false
	};
	
	
	/*完成选择文件（fileDialogComplete） → 开始上传文件（uploadStart） → 上传处理（uploadProgress） → 上传成功（uploadSuccess） → 上传完成（uploadComplete）  → 列队完成（queueComplete）*/
	
	function fileQueued_new(file){
	}
	function fileQueueError_new(file, errorCode, message) {
	    try {
	        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
	            alert("此类图片不能超过"+upload_limit+"张.\n");
	            return;
	        }
	        switch (errorCode) {
	        case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
	            alert("图片大小超过4M无法上传.");
	            break;
	        case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
	            alert("文件内容为空.");
	            break;
	        case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
	            alert("不支持的文件类型.");
	            break;
	        default:
	            if (file !== null) {
	                alert("未知错误");
	            }
	            break;
	        }
	    } catch (ex) {
	        this.debug(ex);
	    }
	}
	function fileDialogComplete(selected, queued){//只在选择文件后执行一次
		if(selected == 0 || queued == 0) return;
		$("#b_"+divBox+"_notice").text("").hide();//错误提示语句清空
		uploaded_num = 0; //每次选择完文件后将新上传成功图片的个数置为0
		already_num = $("#"+divBox).find("li.ms").length;
		if(already_num >= upload_limit){
			this.cancelUpload();
			//$("#"+divBox).find("li:last").remove();
		}else{
			$("#"+divBox).parents(".bd").find(".uploadAdd").addClass("uploadAdd_hidden");
			this.startUpload();
			/* 选择上传文件后显示进度条，并判断是否超过限制张数，如果超过限制张数，则上传个数直接显示限制张数 */
			$("#"+divBox).parents(".bd").find(".uploadProgress").show();
			$("#"+divBox).parents(".bd").find(".uploadProgressVal").width(0);
			$("#"+divBox).parents(".bd").find(".uploadedNum").text(0);

			if(queued > upload_limit-already_num){
				$("#"+divBox).parents(".bd").find(".totalNum").text(upload_limit-already_num);
			}else {
				$("#"+divBox).parents(".bd").find(".totalNum").text(queued);
			}
		}
	}
	
	function uploadStart_new(file){	
		already_num = $("#"+divBox).find("li.ms").length;
		var that = this;
		if(already_num >= upload_limit){
			that.cancelUpload();
		}else {
			var liHtml = '<li class="ms">'+
					'   <span class="imgWrap"></span>'+
					'	<div class="fileProgressBar"><div class="fileProgressVal"></div></div>'+
					'   <div class="fileInfo">'+
					'		<p class="fileName">'+file.name+'</p>'+
					'		<p class="fileSize"><span class="fileCancel">移除</span>'+(file.size/1024/1024).toFixed(1)+'M</p>'+
					'   </div>'+
					'</li>';
			$("#"+divBox).append(liHtml);	
			$(".fileCancel").click(function(){
				that.cancelUpload();
				$(this).parents("li.ms").remove();
			});
		}
	}
	function uploadProgress(file, bytesLoaded){
		try {
			var percent = Math.ceil((bytesLoaded / file.size) * 100);
			$("#"+divBox).find('li:last .fileProgressVal').css('width', percent + '%'); 
		} 
		catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadSuccess_new(file, serverData) {	
	    try {
			if( serverData.indexOf('error')>-1 ){				
				if (serverData.indexOf('300')>-1) {
					alert('上传失败，你上传了尺寸小于300x300像素的图片！');
				} else if (serverData.indexOf('5000')>-1) {
					alert('上传失败，你上传了尺寸大于5000x5000像素的图片！');
				} else {
					alert('上传失败！');
				}
				$("#"+divBox).find("li:last").remove();
				already_num = $("#"+divBox).find("li.ms").length;
				if(already_num < upload_limit){
					$("#"+divBox).parents(".bd").find(".uploadAdd").removeClass("uploadAdd_hidden");
				}
			}else{				
				eval("var serverData = " + serverData + ";");
				_create_image_box(divBox, serverData);
				
				/* 上传成功后更新进度条 */
				uploaded_num++;
				var total_num = $("#"+divBox).parents(".bd").find(".totalNum").text();
				var percent = parseInt(uploaded_num/total_num*100);
				$("#"+divBox).parents(".bd").find(".uploadProgressVal").animate({width:percent+"%"});
				$("#"+divBox).parents(".bd").find(".uploadProgressVal em").text(percent+"%");
				$("#"+divBox).parents(".bd").find(".uploadedNum").text(uploaded_num);
				
				/* 判断是否达到限制张数，若达到限制张数，则隐藏添加按钮 */
				already_num = $("#"+divBox).find("li.ms").length;
			}
	    } catch (ex) {
			this.debug(ex);
		}
	}
	function uploadError_new(file, errorCode, message){
		/* 文件上传被中断或是文件没有成功上传时会触发该事件。停止、取消文件上传或是在uploadStart事件中返回false都会引发这个事件，但是如果某个文件被取消了但仍然还在队列中则不会触发该事件 */
		try {
	        switch (errorCode) {
	        case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				already_num = $("#"+divBox).find("li.ms").length;
				if(already_num >= upload_limit){
	        		 $("#b_"+divBox+"_notice").text("最多上传"+upload_limit+"张").show();	
				}
	            break;
	        default:
	            if (file !== null) {
	                alert("未知错误");
	            }
	            break;
	        }
	    } catch (ex) {
	        this.debug(ex);
	    }
	}
	
	function uploadComplete_new(file){	
		try {
			already_num = $("#"+divBox).find("li.ms").length;
			if (this.getStats().files_queued > 0) {
				if(already_num >= upload_limit-1){
					$("#"+divBox).parents(".bd").find(".uploadAdd").addClass("uploadAdd_hidden");
				}else {
					$("#"+divBox).parents(".bd").find(".uploadAdd").removeClass("uploadAdd_hidden");
				}
			}else {
				if(already_num == upload_limit){
					$("#"+divBox).parents(".bd").find(".uploadAdd").addClass("uploadAdd_hidden");
				}else {
					$("#"+divBox).parents(".bd").find(".uploadAdd").removeClass("uploadAdd_hidden");
				}
				setTimeout(function(){
					$("#"+divBox).parents(".bd").find(".uploadProgress").hide();
					$("#"+divBox).parents(".bd").find(".uploadProgressVal").width(0);
					$("#"+divBox).parents(".bd").find(".uploadProgressVal em").text(0);
				},500);
			}
		} catch (ex) {
			this.debug(ex);
		}
	}	
	
	
	
	new SWFUpload(setting);
}

