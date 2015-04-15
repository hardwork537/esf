function SWFImage(){
	var login_name = arguments[0] ? arguments[0] : "";
	var city = arguments[1] ? arguments[1] : "bj";
	var upload_limit = arguments[2] ? arguments[2] : 0;
	var buttonId = arguments[3] ? arguments[3] : "";
	var divBox = arguments[4] ? arguments[4] : "";
	var prefix_1 = arguments[5] ? arguments[5] : "";
	var prefix_2 = arguments[6] ? arguments[6] : "";
	var product = "esf";
	var watertype = $("#"+buttonId).attr("water") =="ture" || $("#"+buttonId).attr("water") == undefined ? 1 : 0;
	var file_limit = "4 MB"
	var already_upload_image_num = 0;
	var show_button = $("#"+buttonId).attr("src") =="" || $("#"+buttonId).attr("src") == undefined ? "uploading.png" : $("#"+buttonId).attr("src");
	var show_width = $("#"+buttonId).attr("width") =="" || $("#"+buttonId).attr("width") == undefined ? 80 : $("#"+buttonId).attr("width");
	var show_height = $("#"+buttonId).attr("height") =="" || $("#"+buttonId).attr("height") == undefined ? 29 : $("#"+buttonId).attr("height");
	var brokerNo = $("#brokerNo").val()=="" || $("#brokerNo").val()==undefined ? "" : $("#brokerNo").val();
	var companyNo = $("#companyNo").val()=="" || $("#companyNo").val()==undefined ? "" : $("#companyNo").val();
	var setting = {
	            upload_url: "http://127.0.0.1/html/jiaodiantong/upload.php",
	            post_params: {"PHPSESSID": "", 'p_login_name':login_name,'city':city,'product':product,'watertype':watertype,'brokerNo':brokerNo,'companyNo':companyNo},
	            file_size_limit : file_limit,
	            file_types : "*.jpg;*.jpeg;*.png;*.gif;",
	            file_types_description : "JPG Images",
	            file_upload_limit : 0,
	            file_queue_limit : 0,
	            swfupload_loaded_handler : function(){},
	            file_queued_handler : function(){already_upload_image_num++;if(this.getStats().files_queued+$("#"+divBox).find("img").length > upload_limit){this.cancelUpload();}},
	            file_queue_error_handler : fileQueueError_new,
	            file_dialog_complete_handler : upload_start,
	            upload_progress_handler : uploadProgress,
	            upload_error_handler : uploadError_new,
	            upload_success_handler : uploadSuccess_new,
	            upload_complete_handler : uploadComplete_new,
	            button_image_url : show_button,
	            button_placeholder_id : buttonId,
	            button_width: show_width,
	            button_height: show_height,
	            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
	            button_cursor: SWFUpload.CURSOR.HAND,
	            flash_url : "http://127.0.0.1/html/jiaodiantong/js/swfupload/swfupload.swf",
				custom_settings: {
                    upload_target: ""
                },
	            debug: false
			};
	function uploadSuccess_new(file, serverData) {
	    try {
	        if( serverData.indexOf('error')>-1 ) this.cancelUpload();
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
		 if( selected > upload_limit ){
			 alert("最多上传"+upload_limit+"张");
			 for(i = 0; i< 10; i++){
				 this.cancelUpload();
			 }
			 return;
		 }
		 already_upload_image_num = $("#"+divBox).find("img").length;
		 if(already_upload_image_num < upload_limit){
			 for(var j=0; j<selected; j++){
			 	creatImgWrap(divBox);
			 }
			 this.startUpload();
			 
		 } else {
			 document.getElementById(buttonId+"_notice").innerHTML="最多上传"+upload_limit+"张";
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
	function queueComplete(numFilesUploaded) {}
	function uploadComplete_new(){
		if (this.getStats().files_queued ==0){
			document.getElementById(buttonId+"_notice").innerHTML="上传完毕！";
		} else {
			document.getElementById(buttonId+"_notice").innerHTML="上传成功，准备上传下张图片！";
		}
	}
	/*function uploadProgress(file, bytesLoaded, bytesTotal) { 
		document.getElementById(buttonId+"_notice").innerHTML="正在上传  "+file.name+" 中……";
	}*/
	function uploadProgress(file, bytesLoaded){
		console.log(file.id);
		try {
			var percent = Math.ceil((bytesLoaded / file.size) * 100);
			$('#shinei').find('.fileProgress').css('width', percent + '%');  
			/*var progress = new FileProgress(file, this.customSettings.upload_target);
			progress.setProgress(percent);*/
/*			if (percent === 100) {
				progress.setStatus("创建缩略图中");
				progress.toggleCancel(false, this);
			}
			else {
				progress.setStatus("上传中");
				progress.toggleCancel(true, this);
			}
*/		} 
		catch (ex) {
			this.debug(ex);
		}
	}
	
	
	function uploadError(file, errorCode, message) {alert(errorCode);}
	new SWFUpload(setting);
	
	function FileProgress(file, targetID){
		this.fileProgressID = "divFileProgress";
		
		this.fileProgressWrapper = document.getElementById(this.fileProgressID);
		if (!this.fileProgressWrapper) {
			this.fileProgressWrapper = document.createElement("div");
			this.fileProgressWrapper.className = "progressWrapper";
			this.fileProgressWrapper.id = this.fileProgressID;
			
			this.fileProgressElement = document.createElement("div");
			this.fileProgressElement.className = "progressContainer";
/*			
			var progressCancel = document.createElement("a");
			progressCancel.className = "progressCancel";
			progressCancel.href = "#";
			progressCancel.style.visibility = "hidden";
			progressCancel.appendChild(document.createTextNode(" "));
			
			var progressText = document.createElement("div");
			progressText.className = "progressName";
			progressText.appendChild(document.createTextNode(file.name));
*/				
			var progressBar = document.createElement("div");
			progressBar.className = "progressBarInProgress";
			
/*			var progressStatus = document.createElement("div");
			progressStatus.className = "progressBarStatus";
			progressStatus.innerHTML = "&nbsp;";
		
			this.fileProgressElement.appendChild(progressCancel);
			this.fileProgressElement.appendChild(progressText);
			this.fileProgressElement.appendChild(progressStatus);
*/			this.fileProgressElement.appendChild(progressBar);
			
			this.fileProgressWrapper.appendChild(this.fileProgressElement);
			
			document.getElementById(targetID).appendChild(this.fileProgressWrapper);
			fadeIn(this.fileProgressWrapper, 0);
			
		}
		else {
			this.fileProgressElement = this.fileProgressWrapper.firstChild;
/*			this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
*/		}
		
		this.height = this.fileProgressWrapper.offsetHeight;
		
	}
	
	FileProgress.prototype.setProgress = function(percentage){
		this.fileProgressElement.className = "progressContainer green";
		this.fileProgressElement.childNodes[0].className = "progressBarInProgress";
		this.fileProgressElement.childNodes[0].style.width = percentage + "%";
	};
	FileProgress.prototype.setComplete = function(){
		this.fileProgressElement.className = "progressContainer blue";
		this.fileProgressElement.childNodes[0].className = "progressBarComplete";
		this.fileProgressElement.childNodes[0].style.width = "";
		
	};
	FileProgress.prototype.setError = function(){
		this.fileProgressElement.className = "progressContainer red";
		this.fileProgressElement.childNodes[0].className = "progressBarError";
		this.fileProgressElement.childNodes[0].style.width = "";
		
	};
	FileProgress.prototype.setCancelled = function(){
		this.fileProgressElement.className = "progressContainer";
		this.fileProgressElement.childNodes[0].className = "progressBarError";
		this.fileProgressElement.childNodes[0].style.width = "";
		
	};
/*	FileProgress.prototype.setStatus = function(status){
		this.fileProgressElement.childNodes[2].innerHTML = status;
	};
*/	
	
	FileProgress.prototype.toggleCancel = function(show, swfuploadInstance){
		this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
		if (swfuploadInstance) {
			var fileID = this.fileProgressID;
			this.fileProgressElement.childNodes[0].onclick = function(){
				swfuploadInstance.cancelUpload(fileID);
				return false;
			};
		}
	};
	
	function creatImgWrap(p){
		console.log(p);
		var html = '<li id="" class="ms">'+
					'   <img alt="img01" src="" width="100" height="77" />'+
					'	<div class="fileProgress"></div>'+
					'    <p class="botCenP01">'+
					'       <input type="radio" disabled="disabled" name="logo" value="" /><label>封面</label>'+
					'       <input type="hidden" name="" value="" />'+
					'       <input type="hidden" name="" value="" />'+
					'       <a class="one" title="左移"></a>'+
					'       <a class="two" title="右移"></a>'+
					'       <a class="three" title="删除"></a>'+
					'    </p>'+
					'</li>';
		$("#shinei").append(html);
	}
}