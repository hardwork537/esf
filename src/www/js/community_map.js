(function(){
    var assortType = !!currentAssortType && !$("#mapNav").find("li").eq(currentAssortType-1).hasClass("li_gray")?currentAssortType:firstAssortType;
    function countHeight(){
        setTimeout(function() {
            var header = $('#mapHeader'),
                nav = $('#mapNav'),
                left = $('#mapMainLeft'),
                right = $('#mapMainRight');
            var h_header = header.height() || 56,   //header高度
                h_nav = nav.height()+2 || 50,   //nav高度
                h_height = ($(window).height() - h_header - h_nav);
            left.css('height',h_height);
            right.css('height',h_height);
            initMap();
        }, 200);
    }

    $( window ).on( 'resize', resize );

    function resize(){
        countHeight();
    }
    resize();

    function bindEvent(){
        $('#mapMainRight').on("mouseenter",".items",function(){
            $(this).addClass('hover');
            var type_id = $(this).attr('data-id');
            $('#mapMainLeft .marker2[data-id = '+type_id+']').addClass("marker_curr");
        });
        $('#mapMainRight').on("mouseleave",".items",function(){
            $(this).removeClass('hover');
            var type_id = $(this).attr('data-id');
            $('#mapMainLeft .marker2[data-id = '+type_id+']').removeClass("marker_curr");
        });

        $('.i_close').click(function(){
            $(this).parents('.overLayer').hide();
        });
    }
    bindEvent();
    function initData(){
        var newObj = {};
        $.each(parkAssortData,function(index, data){
            if(!newObj[data.type]){
                newObj[data.type] = [];
            }
            newObj[data.type].push(data.list);
        });
        return newObj;
    }
    var parkAssortObj = initData();
    //取出重复的地址标签
    function getSameAddressObj(){
        var newObj = {};
        $.each(parkAssortObj,function(key,val){
            var typeObj = {};
            $.each(val,function(index,data){
                if(!typeObj[data.x]){
                    typeObj[data.x] = [];
                }
                typeObj[data.x].push(data);
            });
            newObj[key] = typeObj;
        });
        return newObj;
    }
    var sameAddressObj = getSameAddressObj();

    function setRelationship(kw){
        var arry = [],
            assort_type = '',
            ids = '',
            clsName = '';

        switch (kw)
        {
            case 1:
                assort_type = '地铁';
                clsName = 'icon_dt';
                break;
            case 2:
                assort_type = '银行';
                clsName = 'icon_yh';
                break;
            case 3:
                assort_type = '菜场';
                clsName = 'icon_cc';
                break;
            case 4:
                assort_type = '医院';
                clsName = 'icon_yy';
                break;
            case 5:
                assort_type = '药店';
                clsName = 'icon_yd';
                break;
            case 6:
                assort_type = '餐厅';
                clsName = 'icon_ct';
                break;
            case 7:
                assort_type = '学校';
                clsName = 'icon_xx';
                break;
            case 8:
                assort_type = '超市';
                clsName = 'icon_cs';
                break;
            case 9:
                assort_type = '公园';
                clsName = 'icon_gy';
                break;
            case 10:
                assort_type = '理发店';
                clsName = 'icon_lfd';
                break;
        }
        arry.push(assort_type,clsName);
        return arry;
    }

    // 百度地图API功能
    var map = null,
        lastOffsetX,
        lastOffsetY;
    function initMap(){
        map = new BMap.Map("mapMainLeft", {enableMapClick: false});
        map.centerAndZoom(new BMap.Point(parkX,parkY), 15);
        map.enableScrollWheelZoom();
        var bottom_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左下角，添加默认缩放平移控件
        map.addControl(bottom_left_control);
        map.addControl(top_left_navigation);

        defaultOverlay();
        $("#mapNav").find("li").removeClass("selected");
        if(!!assortType){
            $("#mapNav").find("li").eq(assortType-1).addClass("selected");
            setData(assortType);
        }
        $("#defInfoWindow").hide();

        map.addEventListener("dragging", function (e) {
            var currentTarget = e.currentTarget;
            var defInfoWindow = $("#defInfoWindow");
            defInfoWindow.css("top",parseInt(defInfoWindow.css("top").split("px")[0]) + currentTarget.offsetY - lastOffsetY);
            defInfoWindow.css("left",parseInt(defInfoWindow.css("left").split("px")[0]) + currentTarget.offsetX - lastOffsetX);
            lastOffsetX = currentTarget.offsetX;
            lastOffsetY = currentTarget.offsetY;
        });
        map.addEventListener("zoomend", function (e) {
            $("#defInfoWindow").hide();
        });
    }

     // 自定义覆盖物
    function customOverlay(element,point){
        this._element = $(element)[0];
        this._point = point;
    }
    customOverlay.prototype = new BMap.Overlay();
    customOverlay.prototype.initialize = function(map){
        this._map = map;
        if(this._element.className.indexOf("marker1") > -1){
            map.getPanes().markerPane.appendChild(this._element);
        }
        else if(this._element.className.indexOf("marker2") > -1){
            map.getPanes().labelPane.appendChild(this._element);
        }

        return this._element;
    }
    customOverlay.prototype.draw = function(){
        var pixel = map.pointToOverlayPixel(this._point);
        if(this._element.className.indexOf("marker1") > -1){
            this._element.style.left = pixel.x - 8 + "px";
            this._element.style.top  = pixel.y - 41 + "px";
        }
        else if(this._element.className.indexOf("marker2") > -1){
            this._element.style.left = pixel.x - 13 + "px";
            this._element.style.top  = pixel.y - 30 + "px";
        }
    }
    customOverlay.prototype.addEventListener = function(event,fun){
        this._element['on'+event] = fun;
    }

    $("#mapNav").on("click",".li_tabs",function(){
        if($(this).hasClass('li_gray')){
            return false;
        }
        $("#defInfoWindow").hide();
        var type = $(this).attr("data-type");
        assortType = type;
        $(this).siblings().removeClass("selected");
        $(this).addClass("selected");
        setData(assortType);
    });
    function setData(type){
        var datas = parkAssortObj[type],
            relationship = setRelationship(parseInt(type)),
            content = "",
            list_info = $('#listInfo'),
            r_num = $('#rightNum'),
            r_title = $('#rightTitle');

        $(map.getPanes().labelPane).empty();
        r_title.text(relationship[0]);
        r_num.text(0);
        list_info.empty();

        if(datas && datas.length > 0){
            r_num.text(datas.length);
            $.each(datas, function(index,data){
                normalOverlay(data, relationship);
                content += '<li class="items" data-id="'+data.assort_id+'"><div class="title"><a class="a_tit" href="#">'+data.assort_name+'</a><em>'+data.distance+'米</em>' + '</div>';
                if(!!data.assort_address){
                    content += '<p class="address">'+data.assort_address+'</p>';
                }
                else{
                    content += '<p class="address"></p>';
                }
                content += '</li>';
            });
            list_info.append(content);
        }
    }

    /*周边配套*/
    function normalOverlay(data, relationship){
        var element = '<a href="javascript:;" class="marker marker2" data-id="'+ data.assort_id +'" data-x="'+ data.x +'"><i class="'+relationship[1]+'"></i></a>',
            myCustomOverlay = new customOverlay(element, new BMap.Point(data.x,data.y));
        map.addOverlay(myCustomOverlay);

        myCustomOverlay.addEventListener('click',function(){
            var offset = $(this).offset(),
                offLeft = offset.left - 119,
                offTop = offset.top - 115;
            var infoWindow = $('#defInfoWindow'),
                type = relationship[0];
            infoWindow.find('.layer_tit').text(type);

            var x = $(this).data("x"),
                content = "";
            $("#defInfoWindow .container").empty();
            var currentData = sameAddressObj[assortType];
            var len = currentData[x].length;
            $.each(currentData[x],function(index,data){
                content += '<div class="layer_con"><p class="fb con_p1" title="'+data.assort_name+'">'+data.assort_name+'</p>';
                if(!!data.assort_address){
                    content += '<p class="f_simsun con_p2">'+data.assort_address+'</p>';
                }
                else{
                    content += '<p class="f_simsun con_p2"></p>';
                }
                content += '</div>';
            });
            if(len >= 5){
                infoWindow.css({'left':offLeft + 'px', 'top':offTop - 304 + 'px'});
            }
            else{
                infoWindow.css({'left':offLeft + 'px', 'top':offTop - (76 * (len - 1)) + 'px'});
            }
            $("#defInfoWindow .container").append(content);
            infoWindow.show();
        });

        myCustomOverlay.addEventListener('mouseover',function(){
            var type_id = $(this).attr('data-id');
            $('#mapMainRight .items[data-id = '+type_id+']').addClass("hover");
            var top = $('#mapMainRight .items[data-id = '+type_id+']')[0].offsetTop;
            $('#mapMainRight').stop().animate({scrollTop:top},0);
        });

        myCustomOverlay.addEventListener('mouseout',function(){
            var type_id = $(this).attr('data-id');
            $('#mapMainRight .items[data-id = '+type_id+']').removeClass("hover");
        });
    }

    /*默认显示小区标注*/
    function defaultOverlay(){
        var element = '<span class="marker marker1" style="white-space: nowrap;">'+ parkName +'<i class="icons i_bot2"></i></span>',
            myCustomOverlay = new customOverlay(element, new BMap.Point(parkX,parkY));
        map.addOverlay(myCustomOverlay);
    }
})();
