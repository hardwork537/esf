function SWFImage(){
	var loginName = $("#loginName").val()=="" || $("#loginName").val()==undefined ? "" : $("#loginName").val();
	var city = arguments[1] ? arguments[1] : "bj";
	var upload_limit = arguments[2] ? arguments[2] : 0;
	var buttonId = arguments[3] ? arguments[3] : "";
	var divBox = arguments[4] ? arguments[4] : "";
	var prefix_1 = arguments[5] ? arguments[5] : "";
	var prefix_2 = arguments[6] ? arguments[6] : "";
	var product = "esf";
	var watertype = $("#"+buttonId).attr("water") =="ture" || $("#"+buttonId).attr("water") == undefined ? 1 : 0;
	var file_limit = "2 MB"
	var already_upload_image_num = 0;
	var inputHeight = $("#"+buttonId).attr("height")==undefined||$("#"+buttonId).attr("height")==''? 36: $("#"+buttonId).attr("height");
	var inputWidth = $("#"+buttonId).attr("width")==undefined||$("#"+buttonId).attr("width")==''? 122: $("#"+buttonId).attr("width");
	var show_button = $("#"+buttonId).attr("src") =="" || $("#"+buttonId).attr("src") == undefined ? "uploading.png" : $("#"+buttonId).attr("src");
	var realtorNo = $("#realtorNo").val()=="" || $("#realtorNo").val()==undefined ? "" : $("#realtorNo").val();
	var login_id = $("#loginId").val()=="" || $("#loginId").val()==undefined ? "" : $("#loginId").val();
	var setting = {
	            upload_url: "http://my.esf.focus.cn/ajax/multiUpload/",
	            post_params: {"PHPSESSID": "", 'loginName':loginName,'loginId':login_id,'city':city,'product':product,'watertype':watertype,'realtorNo':realtorNo},
	            file_size_limit : file_limit,
	            file_types : "*.jpg;*.png;*.gif",
	            file_types_description : "JPG Images",
	            file_upload_limit : 0,
	            file_queue_limit : 0,
	            swfupload_loaded_handler : function(){},
	            file_queued_handler : function(){already_upload_image_num++;if(this.getStats().files_queued+document.getElementsByName(divBox+'[]').length > upload_limit){this.cancelUpload();}},
	            file_queue_error_handler : fileQueueError_new,
	            file_dialog_complete_handler : upload_start,
	            upload_progress_handler : uploadProgress,
	            upload_error_handler : uploadError_new,
	            upload_success_handler : uploadSuccess_new,
	            upload_complete_handler : uploadComplete_new,
	            button_image_url : "http://src.esf.itc.cn/www/img/"+show_button,
	            button_placeholder_id : buttonId,
	            button_width: inputWidth,
	            button_height: inputHeight,
	            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
	            button_cursor: SWFUpload.CURSOR.HAND,
	            flash_url : "http://src.esf.itc.cn/www/swf/swfupload.swf",
	            debug: false
			};
	function uploadSuccess_new(file, serverData) {
	    try {
	    	call_back_function(divBox, serverData, prefix_1, prefix_2);
	    } catch (ex) {}
	}
	function uploadError_new(file, errorCode, message){
		try {
	        switch (errorCode) {
	        case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
	        	document.getElementById(buttonId+"_notice").innerHTML="最多上传"+upload_limit+"张";
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
	function upload_start(selected, queued){
		var strButtonType = '';
		if( divBox=='huxing') strButtonType = '户型图';
		else if(divBox=='shinei') strButtonType = '室内图';

		 if( selected > upload_limit ){
			 alert("最多上传"+upload_limit+"张"+strButtonType);
			 for(i = 0; i< 10; i++){
				 this.cancelUpload();
			 }
			 return;
		 }
		 already_upload_image_num = document.getElementsByName(divBox+'[]').length;
		 if(already_upload_image_num < upload_limit){
			 this.startUpload();
		 } else {
			 document.getElementById("upload_notice").innerHTML="最多上传"+upload_limit+"张"+strButtonType;
		 }
	}
	function fileQueueError_new(file, errorCode, message) {
	    try {
	        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
	            alert("此类图片不能超过"+upload_limit+"张.\n");
	            return;
	        }
	        switch (errorCode) {
	        case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
	            alert("请不要上传超过"+file_limit+'的文件。');
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
	function queueComplete(numFilesUploaded) {}
	function uploadComplete_new(){ document.getElementById("upload_notice").innerHTML="上传完毕";}
	function uploadProgress(file, bytesLoaded, bytesTotal) { document.getElementById("upload_notice").innerHTML="上传  "+file.name+" 中";}
	function uploadError(file, errorCode, message) {alert(errorCode);}
	new SWFUpload(setting);
}