var param = '';
if(typeof from != 'undefined')
    param += "from=" + from;
if(typeof fromId != 'undefined')
    param += param ? "&fromId="+fromId : "fromId="+fromId;
if(typeof type != 'undefined')
    param += param ? "&type="+type : "type="+type;
if(typeof typeId != 'undefined')
    param += param ? "&typeId="+typeId : "typeId="+typeId;

var url = '/ajax/uploadImage/';
if(param)
    url += "?" + param;

var uploader = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4', //用来指定上传方式，指定多个上传方式请使用逗号隔开
    browse_button: 'pickFiles', // 触发文件选择对话框的按钮，为那个元素id
    container: document.getElementById('container'), // ... or DOM Element itself
    url: url, //服务器端的上传页面地址
    flash_swf_url: '../js/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
    silverlight_xap_url: '../js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
    max_retries: 0, //当发生plupload.HTTP_ERROR错误时的重试次数，为0时表示不重试
    chunk_size: 0, //分片上传文件时，每片文件被切割成的大小，为数字时单位为字节。也可以使用一个带单位的字符串，如"200kb"。当该值为0时表示不使用分片上传功能
    unique_names: true, // 上传的文件名是否唯一   

    filters: {
        max_file_size: '2mb', //用来限定上传文件的大小
        mime_types: [
            {title: "Image files", extensions: "jpg,gif,png"},
            {title: "Zip files", extensions: "zip"}
        ],
        prevent_duplicates: true	//不允许选取重复文件
    },
    init: {
        PostInit: function () {	//当Init事件发生后触发
            //$('#fileList').innerHTML = '';

            $('#uploadFiles').on('click', function () {
                if (uploader.files.length > 0) {
                    uploader.start();	//调用实例对象的start()方法开始上传文件
                }
            });
        },
        FilesAdded: function (up, files) {	//当文件添加到上传队列后触发 
            plupload.each(files, function (file) {
                var html = '<div id="' + file.id + '" class="js-upload-item upload-item">' +
                        '<p class="img"></p>' +
                        '<i class="del"></i>' +
                        '</div>';
                $(html).prependTo('#uploadList');
                //实现图片预览功能
                previewImage(file, function (imgsrc) {
                    $('#' + file.id).find('.img').append('<img height="120px" width="160px" src="' + imgsrc + '" />');
                })
            });

            $('.js-upload-item').on({
                mouseenter: function () {
                    $(this).find('.del').show();
                },
                mouseleave: function () {
                    $(this).find('.del').hide();
                }
            });
            $('.js-upload-item .del').on('click', function () {
                var fileId = $(this).parent().attr('id');
                uploader.removeFile(fileId);
                $(this).parent().remove();
            });
        },
        UploadComplete: function (up, files) {
            //上传完以后的coding...
            $('#uploadModal').modal('toggle');
            $('.js-upload-item .del').each(function () {
                var fileId = $(this).parent().attr('id');
                uploader.removeFile(fileId);
                $(this).parent().remove();
            });

        },
        Error: function (up, err) {
            alert(err.message);
            //$('#console').html(err.code + ": " + err.message);
        }
    }
});

uploader.init();
//plupload中为我们提供了mOxie对象
//有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
//如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
    if (!file || !/image\//.test(file.type))
        return; //确保文件是图片
    if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
        var fr = new mOxie.FileReader();
        fr.onload = function () {
            callback(fr.result);
            fr.destroy();
            fr = null;
        }
        fr.readAsDataURL(file.getSource());
    } else {
        var preloader = new mOxie.Image();
        preloader.onload = function () {
            preloader.downsize(160, 120);//先压缩一下要预览的图片,宽300，高300
            var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
            callback && callback(imgsrc); //callback传入的参数为预览图片的url
            preloader.destroy();
            preloader = null;
        };
        preloader.load(file.getSource());
    }
}

$(function () {
    $('#imgList').find('.check').on('click', function () {
        $(this).parent('li').toggleClass('item-checked');
    });
});