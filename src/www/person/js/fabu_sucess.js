$(function () {
    keepSelectedBrokerSame();

    $("body").on("click", "input[name='brokerids[]']", function () {
        var $thisRadio = $(this);
        if ($thisRadio.is(':checked')) {
            if ($("#selectedA span").length >= 3) {
                $thisRadio.prop('checked', '');
                alert('最多只能委托给3位经纪人');
            } else {
                $("#selectedA").append('<span id="sel' + $thisRadio.val() + '">' + $thisRadio.attr("bname") + '<a href="javascript:void(0)"></a><input type="hidden" name="broker_ids[]" value="' + $thisRadio.val() + '"/></span>');
                var brokerId = $thisRadio.val();
                //删除追加已选中经纪人名称 操作
                $("#sel" + brokerId).click(function () {
                    $("input[name='brokerids[]'][value='" + brokerId + "']").prop('checked', '');
                    $(this).remove();
                });
            }
        } else {
            $("#sel" + $thisRadio.val()).remove();
        }

    })

    //删除已选中经纪人名称 操作
    $("body").on("click", "#selectedA span a" , function () {

        var broker_id = $(this).parent("span").attr("id");
        broker_id = broker_id.replace('sel', '');
        $("input[name='brokerids[]'][value='" + broker_id + "']").attr('checked', '');
        $(this).parent("span").remove();
    });

    })
 

function scroolBroker(arrBroker) {
    var opt = {
        'imagebroker': {
            'itemId': 'ppA',
            'selectedId': 'selectedA',
            'imageSrc': arrBroker
        },
        'moveButton': {
            'leftBtn': ['leftA'],
            'rightBtn': ['rightA'],
            'leftClass': 'arrL',
            'rightClass': 'arrR',
            'leftNoneClass': 'arrLnone',
            'rightNoneClass': 'arrRnone'
        },
        'imageItem': {
            'defaultLoadNum': '4'
        }
    };
    // default paramer
    var itemImageId = '#' + opt.imagebroker.itemId; //经纪人列表元素id
    var selectedId = '#' + opt.imagebroker.selectedId; //您已经选择页面id
    var arrImageSrc = opt.imagebroker.imageSrc; //经纪人数组信息字符串
    var lBtn = opt.moveButton.leftBtn; //left元素id
    var rBtn = opt.moveButton.rightBtn; //right元素id
    var dNum = opt.imageItem.defaultLoadNum; //默认显示数量
    var srcNum = arrImageSrc.length; //经纪人数组总数
    var step = $(itemImageId).children().width() + 7; //ul中li的宽度 + margin-right的值，计算出每次滚动的宽度值
    var curNum = 1;
    var pageNum = srcNum - dNum;

    // 初始化图片区域
    $(itemImageId).width(srcNum * step);
    if (srcNum <= dNum) {
        $('#' + lBtn).addClass(opt.moveButton.leftNoneClass);
        $('#' + rBtn).addClass(opt.moveButton.rightNoneClass);
        return false;
    }

    $('#' + lBtn).click(function () {
        if (!$(itemImageId).is(':animated')) {
            $('#' + rBtn).removeClass(opt.moveButton.rightNoneClass).addClass(opt.moveButton.rightClass);
            if (curNum != 1) {
                $(itemImageId).animate({
                    left: '+=' + step
                }, 500);
                curNum--;
            }
            if (curNum != 1) {
                $('#' + lBtn).removeClass(opt.moveButton.leftNoneClass).addClass(opt.moveButton.leftClass);
            } else {
                $('#' + lBtn).addClass(opt.moveButton.leftNoneClass);
            }
        }
    });

    $("#" + rBtn).click(function () {
        if (!$(itemImageId).is(':animated')) {  //判断展示区是否动画
            $('#' + lBtn).removeClass(opt.moveButton.leftNoneClass).addClass(opt.moveButton.leftClass);
            if (curNum <= pageNum) {
                var imgArr = $(itemImageId + " li");
                var imgNum = imgArr.length;
                if (imgNum < srcNum) {
                    $(itemImageId).append('<li>' + arrImageSrc[imgNum] + '</li>');
                    keepSelectedBrokerSame();
                    //追加元素操作事件
                    $(itemImageId + " li:last").find("input[name='brokerids[]']").click(function () {
                        var $thisRadio = $(this);
                        if ($thisRadio.attr('checked') === true) {
                            if ($("#selectedA span").length >= 3) {
                                $thisRadio.attr('checked', '');
                                alert('最多只能委托给3个经纪人');
                            } else {
                                $("#selectedA").append('<span id="sel' + $thisRadio.val() + '">' + $thisRadio.attr("bname") + '<a href="javascript:void(0)"></a><input type="hidden" name="broker_ids[]" value="' + $thisRadio.val() + '"/></span>');
                                var brokerId = $thisRadio.val();
                                //删除追加已选中经纪人名称 操作
                                $("#sel" + brokerId).click(function () {
                                    $("input[name='brokerids[]'][value='" + brokerId + "']").attr('checked', '');
                                    $(this).remove();
                                });
                            }
                        } else {
                            $("#sel" + $thisRadio.val()).remove();
                        }
                    });
                }
                $(itemImageId).animate({
                    left: '-=' + step
                }, 500); //改变left值,切换显示版面
                curNum++; //版面累加
            }
            if (curNum <= pageNum) {
                $('#' + rBtn).removeClass(opt.moveButton.rightNoneClass).addClass(opt.moveButton.rightClass);
            } else {
                $('#' + rBtn).addClass(opt.moveButton.rightNoneClass);
            }

        }
    });
}

/**
*刷新页面 经纪人列表点击滚动切换时保持与您已经选择的项一致
*
*/
function keepSelectedBrokerSame() {
    var $selectedBrokerids = $("input[name='brokerids[]']:checked"); //经纪人列表的选中的checkbox
    var intBrokeridsLen = $selectedBrokerids.length;
    var $selectedBrokerIds = $("input[name='broker_ids[]']"); //您已经选择的项
    var intBrokerIdsLen = $selectedBrokerIds.length;
    //您已经选择的项
    var arrSelectBrokerIds = new Array();
    for (var i = 0; i < intBrokerIdsLen; i++) {
        arrSelectBrokerIds[i] = parseInt($selectedBrokerIds.eq(i).val(), 10);
    }
    //经纪人列表的选中的checkbox
    var arrSelectBrokerids = new Array();
    for (var i = 0; i < intBrokeridsLen; i++) {
        arrSelectBrokerids[i] = parseInt($selectedBrokerids.eq(i).val(), 10);
    }
    //循环 经纪人列表的选中的checkbox 保持一致
    for (var i = 0; i < intBrokeridsLen; i++) {
        var val = $selectedBrokerids.eq(i).val();
        val = parseInt(val, 10);
        if (!arrSelectBrokerIds || $.inArray(val, arrSelectBrokerIds) == -1) {
            $selectedBrokerids.eq(i).attr('checked', '');
        }
    }
    //循环 您已经选择的项 保持一致
    for (var i = 0; i < intBrokerIdsLen; i++) {
        var val = $selectedBrokerIds.eq(i).val();
        val = parseInt(val, 10);
        if ($.inArray(val, arrSelectBrokerids) == -1) {
            $("input[name='brokerids[]'][value=" + val + "]").attr('checked', 'checked');
        }
    }
}