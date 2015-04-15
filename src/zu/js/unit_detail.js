//定义常量intUlLeft 使用于图片滚动left属性值，因为ie7,8与其他的浏览器获得的left不同，导致滚动不正常，所以定义了此变量，使各浏览器值都是相同正常的
var intUlLeft = 0;
/////////////////////////////图片切换开始//////////////////////////////////////////
/**
 * 检测指定字符串是否在数组中，如果存在，返回键值，否则返回false;
 * 
 * str 待检测的字符串 arr 待检测的数组
 * 
 * @author Steven
 */
function in_array(str, arr) {
	if (!str || str.length < 1)
		return -1;
	if (!arr || arr.length < 1)
		return -1;
	for ( var i = 0, j = arr.length; i < j; i++) {
		if (arr[i] == str)
			return i;
	}
	return -1;
}
/**
 * 图片滚动js
 * 
 * @author Steven
 */
function scroolImgNew(opt) {
	// default paramer
	var bImgId = '#' + opt.bigImage.imageId;
	var sImgId = '#' + opt.smallImage.imageId;
	var lBtn = opt.moveButton.leftBtn;
	var rBtn = opt.moveButton.rightBtn;
	var bSrc = opt.bigImage.imageSrc;
	var sSrc = opt.smallImage.imageSrc;
	var dNum = opt.smallImage.defaultLoadNum;
	var bSize = opt.bigImage.imageSize;
	var sSize = opt.smallImage.imageSize;
	var cls = opt.smallImage.selectClass;
	var srcNum = sSrc.length || bSrc.length;
	var step = opt.step;
	var dp = $(sImgId).position();
	var minw = dp.left - (srcNum - dNum) * step;
	var loadBigImg = function(bImgId, src) {
		var img = new Image();
		img.onload = function() {
			$(bImgId).find("img").attr("src", src);
		};
		img.src = src;
	};
	var smallImgClick = function() {
		if ($(this).hasClass(cls)) {
			return;
		}
		var surl = $(this).find('img').attr("src");
		var num = in_array(surl, sSrc);
		// 控制左按钮的可控性
		if (surl == sSrc[0]) {
			$('#' + lBtn[0]).attr('class', 'focus_l');
		} else {
			$('#' + lBtn[0]).attr('class', 'focus_l');
		}
		// 控制右按钮的可控性
		if (surl == sSrc[srcNum - 1]) {
			$('#' + rBtn[0]).attr("class", "focus_r");
		} else {
			$('#' + rBtn[0]).attr("class", "focus_r");
		}

		var url = bSrc[num];
		$(sImgId + ' li').removeClass(cls);
		$(this).addClass(cls);
		loadBigImg(bImgId, url);
	};

	// 未传大图地址
	if (!bSrc || bSrc.length < 1) {
		for ( var i = 0; i < srcNum; i++) {
			bSrc[i] = sSrc[i].replace(sSize, bSize);
		}
	}

	// 初始化小图区域
	$(sImgId).width(srcNum * step);
	$('#' + lBtn[0]).attr('class', 'focus_l');
	if(srcNum==1){
		$('#' + rBtn[0]).attr('class', 'focus_r');
		return false;
	}
	for ( var i = 0, j = lBtn.length; i < j; i++) {
		// 左
		$('#' + lBtn[i]).click(function() {
			var cp = $(sImgId).position();
			cp.left = intUlLeft;
			if ((dp.left - cp.left) % step > 0)
				return false;
			var CurrSrc = $(sImgId + " ." + cls + " img").attr("src");
			var num = in_array(CurrSrc, sSrc);
			$('#' + rBtn[0]).attr('class', 'focus_r');
			if (CurrSrc == sSrc[1]){
				$('#' + lBtn[0]).attr('class', 'focus_l');
			}
			if (cp.left < dp.left) {
				// 移动
				$(sImgId).animate({
					left : (cp.left + step)
				}, 1000);
                intUlLeft = cp.left + step;
				// 大图
				var url = bSrc[num - 1];
				$(sImgId + ' li').removeClass(cls);
				$($(sImgId + ' li')[num - 1]).addClass(cls);
				loadBigImg(bImgId, url);
			} else {
				if (num > 0) {
					var url = bSrc[num - 1];
					$(sImgId + ' li').removeClass(cls);
					$($(sImgId + ' li')[num - 1]).addClass(cls);
					loadBigImg(bImgId, url);
				}
			}
		});
		// 右
		$("#" + rBtn[i])
				.click(
						function() {
							var cp = $(sImgId).position();
							cp.left = intUlLeft;
							if ((dp.left - cp.left) % step > 0)
								return false;
							var CurrSrc = $(sImgId + " ." + cls + " img").attr(
									"src");
							var num = in_array(CurrSrc, sSrc);
							$('#' + lBtn[0]).attr('class', 'focus_l');
							if (CurrSrc == sSrc[srcNum - 2]){
								$('#' + rBtn[0]).attr("class", "focus_r");
							}
							if (cp.left > minw) {
								// 异步加载小图
								var imgArr = $(sImgId + " li");
								var imgNum = imgArr.length;
								if (imgNum < srcNum) {
									$(sImgId).append('<li><a href="javascript:;"><img width="80" height="60" src="'
															+ sSrc[imgNum]
															+ '" class="" /><s></s></a></li>');
									$(sImgId + ' li').filter(":last").click(smallImgClick);
								}

								// 移动
								$(sImgId).animate({
									left : (cp.left - step)
								}, 1000);
								intUlLeft = cp.left - step;
								// 大图
								var url = bSrc[num + 1];
								$(sImgId + ' li').removeClass(cls);
								$($(sImgId + ' li')[num + 1]).addClass(cls);
								loadBigImg(bImgId, url);
							} else {
								// 大图
								if (num < (srcNum - 1)) {
									$(sImgId + ' li').removeClass(cls);
									$($(sImgId + ' li')[num + 1]).addClass(cls);
									var url = bSrc[num + 1];
									loadBigImg(bImgId, url);
								}
							}
						});
	}

	// 小图点击事件
	$(sImgId + ' li').click(smallImgClick);
}
function scroolImg(arr) {
	// 房源详情页滚动图片js
	var opt = {
		'bigImage' : {
			'imageId' : 'image_wrap',
			'imageSrc' : [],
			'imageSize' : '600x450'
		},
		'smallImage' : {
			'imageId' : 'smallImg',
			'imageSrc' : arr,
			'imageSize' : '180x120',
			'defaultLoadNum' : '3',
			'selectClass' : 'active'
		},
		'moveButton' : {
			'leftBtn' : [ 'leftbtn'],
			'rightBtn' : [ 'rightbtn']
		},
		'step' : 90
	};
	scroolImgNew(opt);
};
// ///////////////////////////图片切换结束//////////////////////////////////////////

function AddFavorite(sURL, sTitle) {
	try {
		window.external.addFavorite(sURL, sTitle);
	} catch (e) {
		try {
			window.sidebar.addPanel(sTitle, sURL, "");
		} catch (e) {
			alert("加入收藏失败，请使用Ctrl+D进行添加"); 
		}
	}
}