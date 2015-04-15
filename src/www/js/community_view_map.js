(function(){
    var assortType="1";
    var parkAssortObj5 = {},//储存不同距离的数据
        parkAssortObj10 = {},
        parkAssortObj15 = {};
    function countHeight(){
        setTimeout(function() {
            var header = $('#mapHeader'),
                header2 = $('.header2'),
                nav = $('#mapNav'),
                left = $('#mapMainLeft'),
                right = $('#mapMainRight');
            var h_header = header.height() + header2.height()|| 96,   //header高度
                h_nav = nav.height()+2 || 50,   //nav高度
                h_height = ($(window).height() - h_header - h_nav);
            left.css('height',h_height);
            right.css('height',h_height);
            initMap();
        }, 200);
    }
    var parkAssortObj, //处理后按type分类的新对象
        sameAddressObj; //处理后把同经纬度的对象进行整合

    //view 小区页面的地图初始化
    if($('#mapMainLeft').hasClass("viewLeft")){
        initViewData();
        resetData(parkAssortObj15);
        sameAddressObj = getSameAddressObj();
    }
    //详情 小区页面的地图初始化
    else{
        $( window ).on( 'resize', resize );
        function resize(){
            countHeight();
        }
        resize();
        parkAssortObj = initData();
        sameAddressObj = getSameAddressObj();
    }

    function bindEvent(){
        /*周边配套tab切换*/
        $(".tab_change").on("click",function(){
            $(".tab_change").siblings(".tab_change").addClass("selected");
            $(this).removeClass("selected");

            var type = $(this).data("type");
            switch (type){
                case "map":
                    $("#mapBubbleLeft").hide();
                    $("#mapMainLeft").show();
                    $(".distance_tabs").hide();
                    resetData(parkAssortObj15);
                    break;
                case "bubble":
                    $("#mapMainLeft").hide();
                    $(".distance_tabs").show();
                    $("#mapBubbleLeft").show();
                    break;
            }
        })

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

        $(".distance_tab").click(function(){
            $(".distance_tab").removeClass("selected");
            $(this).addClass("selected");
            var type = $(this).data("type");
            if(type == "15"){
                resetData(parkAssortObj15);
            }
            else if(type == "10"){
                resetData(parkAssortObj10);
            }
            else if(type == "5"){
                resetData(parkAssortObj5);
            }
        });

        $("#mapBubbleLeft").on("click",".sp_circle,.ie_circle",function(){
            $(this).siblings().removeClass("hover");
            $(this).addClass("hover");
            var type = $(this).data("type");

            $(".viewRight").find(".li_tabs").removeClass("selected");
            $(".viewRight").find(".li_tabs[data-type = '"+type+"']").addClass("selected");
        });
        //ie678
        var color = "";
        $("#mapBubbleLeft").on("mouseover",".ie_vml",function(){
            $(this)[0].fillcolor = '#fff';
            $(this)[0].strokeweight = 4;
            var type = $(this).parent().data("type");
            var relationArr = setRelationship(parseInt(type));
            switch (relationArr[2]){
                case "c1":
                    color = "#65a6ff";
                    break;
                case "c2":
                    color = "#ff7e4b";
                    break;
                case "c3":
                    color = "#38deba";
                    break;
            }
        });
        $("#mapBubbleLeft").on("mouseout",".ie_vml",function(){
            $(this)[0].fillcolor = color;
            $(this)[0].strokeweight = 2;
        });
        $("#mapBubbleLeft").on("mouseover",".sp_circle,.ie_circle",function(){
            $(this).siblings().removeClass("hover");
            $(this).addClass("hover");
            $(this).find("em").addClass("e_hover");
            var type = $(this).data("type");

            $(".viewRight").find(".li_tabs").removeClass("hover");
            $(".viewRight").find(".li_tabs[data-type = '"+type+"']").addClass("hover");
        });
        $("#mapBubbleLeft").on("mouseout",".sp_circle,.ie_circle",function(){
            $(this).removeClass("hover");
            $(this).find("em").removeClass("e_hover");
            $(".viewRight").find(".li_tabs").removeClass("hover");
        });
        $("#mapNav").on("click",".li_tabs",function(){
            var type = $(this).attr("data-type");
            assortType = type;
            $("#defInfoWindow").hide();
            $(this).siblings().removeClass("selected");
            $(this).addClass("selected");
            setData(assortType);
        });
        $(".viewRight").on("click",".li_tabs",function(){
            var type = $(this).attr("data-type");
            var name = $(this).attr("data-name");
            if($(this).hasClass("li_gray")){
                return;
            }
            if($(this).hasClass("selected")){
                $(this).removeClass("selected");
                $("."+name).hide();
                return;
            }
            assortType = type;
            $("#defInfoWindow").hide();
            $(this).siblings().removeClass("selected");
            $(this).addClass("selected");
            $("."+name).show();

            $("#mapBubbleLeft").find(".sp_circle").removeClass("hover");
            $("#mapBubbleLeft").find(".sp_circle[data-type='"+type+"']").addClass("hover");
            setData(assortType);
        });
        $(".viewRight").on("mouseover",".li_tabs",function(){
            if($(this).hasClass("li_gray")){
                return;
            }
            $(this).siblings().removeClass("hover");
            $(this).addClass("hover");
            var type = $(this).data("type");

            $("#mapBubbleLeft").find(".sp_circle").removeClass("hover");
            $("#mapBubbleLeft").find(".sp_circle[data-type='"+type+"']").addClass("hover");

            $("#mapBubbleLeft").find(".ie_circle[data-type='"+type+"']").find(".ie_vml").mouseover();


        });
        $(".viewRight").on("mouseout",".li_tabs",function(){
            $(this).removeClass("hover");
            $("#mapBubbleLeft").find(".sp_circle").removeClass("hover");
            var type = $(this).data("type");
            $("#mapBubbleLeft").find(".ie_circle[data-type='"+type+"']").find(".ie_vml").mouseout();
        });
    }
    bindEvent();

    function resetData(data){
        parkAssortObj = data;
        $(".li_tabs").addClass("li_gray").removeClass("selected");
        $(".li_tabs").find(".details").empty();

        var count = 0;
        $.each(parkAssortObj,function(k,v){
            if(parseInt(k) <= 9) {
                count++;
            }
        });

        var bubbleContent = "",
            ieBubbleContent = "",
            index = 0;

        $.each(parkAssortObj,function(k,v){
            //重置右边模块数据
            var content = "",
                $thiz = $(".li_tabs[data-type = '"+k+"']");
            console.log(v);
            $thiz.removeClass("li_gray");
            $thiz.find(".count").html(v.length + "个");
            var name = $thiz.data("name");
            $("."+name).empty();

            $.each(v,function(index,data){
                if(index < 3){
                    content += "<ul><li class='items' data-id='"+ data.assort_id+"'><a class='a_tit' href='#'>"+ data.assort_name+"</a><em>"+ data.distance+"米</em></li>";
                }
            });
            if(v.length > 3){
                content += "<a href='#' class='more'>更多</a></ul>";
            }
            else{
                content += "</ul>";
            }

            $("."+name).append(content);

            //重置气泡数据
            $("#mapBubbleLeft").empty();

            var length = v.length;

            if(parseInt(k) <= 9){
                var top = random(80,230) + "px";
                var left = (510*index/(count-1) + (80/count)) +"px";
                var relationArr = setRelationship(parseInt(k));
                var aniClass = index % 2 == 0 ? " ani_down":" ani_up";
                var wClass = length < 9 ? " w"+length: " w9";

                var color = "",
                    strokecolor = "";

                switch (relationArr[2]){
                    case "c1":
                        color = "#589eff";
                        strokecolor = "#3f90ff";
                        break;
                    case "c2":
                        color = "#ff7e4b";
                        strokecolor = "#ff723a";
                        break;
                    case "c3":
                        color = "#38deba";
                        strokecolor = "#26d4ae";
                        break;
                }
                bubbleContent += "<span class='sp_circle "+relationArr[2] + aniClass + wClass +"' data-name='"+ relationArr[0]+"' data-type='"+k+"' style='left:"+left+"; top:"+top+"'><div class='content'><em><i class='icons "+ relationArr[1]+"'></i></em><span class='word'>"+relationArr[0]+"("+length+")</span></div></span>";
                ieBubbleContent += "<span class='ie_circle' data-name='"+ relationArr[0]+"' data-type='"+k+"'><v:oval fillcolor='"+color+"' strokecolor='"+strokecolor+"' strokeweight='2' class='ie_vml "+wClass+"' style='position:absolute; overflow:hidden;left:"+left+"; top:"+top+"'><div class='content_v "+relationArr[2]+" '><em><i class='icons "+ relationArr[1]+"'></i></em><span class='word'>"+relationArr[0]+"("+length+")</span></div></v:oval></span>";
                index++;
            }

        });
        var isIE = !!window.ActiveXObject,
            isIE6 = isIE && !window.XMLHttpRequest,
            isIE8 = isIE && !!document.documentMode,
            isIE7 = isIE && !isIE6 && !isIE8;
        var currentContent = "",
            left = 0,
            width = "";

        if(!isIE8 && !isIE6 && !isIE7){
            for(var i = 0; i < 6; i++){
                left = 100 * i + 40 + "px";
                width = random(50,150) + "px";
                bubbleContent += "<span class='bg_circle' style='left: "+left+"; width:"+width+"; height:"+width+"'></span>";
            }

            currentContent = bubbleContent;
        }
        else{
            currentContent = ieBubbleContent;
        }

        $("#mapBubbleLeft").append(currentContent);
        setTimeout(function(){initMap()},200);

        $(".bg_circle").each(function(){
            var ran = random(500,8000);
            var thiz = $(this)
            setTimeout(function(){thiz.addClass("ani")},ran);
        })
    }

    //初始化按照距离和类型分类的周边
    function initViewData(){

        $.each(parkAssortData,function(k, v){
            var newObj={};
            $.each(v,function(index,data){
                if(!newObj[data.type]){
                    newObj[data.type] = [];
                }
                newObj[data.type] = data.list;
            });

            switch(k){
                case "5":
                    parkAssortObj5 = newObj;
                    break;
                case "10":
                    parkAssortObj10 = newObj;
                    break;
                case "15":
                    parkAssortObj15 = newObj;
                    break;
            }
        });
    }
    //初始化按照类型分类的周边
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

    function setRelationship(kw){
        var arry = [],
            assort_type = '',
            ids = '',
            clsName = '',
            colorCls = '';

        switch (kw)
        {
            case 1:
                assort_type = '地铁';
                clsName = 'icon_dt';
                colorCls = 'c1';
                break;
            case 2:
                assort_type = '银行';
                clsName = 'icon_yh';
                colorCls = 'c2';
                break;
            case 3:
                assort_type = '菜场';
                clsName = 'icon_cc';
                colorCls = 'c3';
                break;
            case 4:
                assort_type = '医院';
                clsName = 'icon_yy';
                colorCls = 'c1';
                break;
            case 5:
                assort_type = '药店';
                clsName = 'icon_yd';
                colorCls = 'c1';
                break;
            case 6:
                assort_type = '餐厅';
                clsName = 'icon_ct';
                colorCls = 'c2';
                break;
            case 7:
                assort_type = '学校';
                clsName = 'icon_xx';
                colorCls = 'c3';
                break;
            case 8:
                assort_type = '超市';
                clsName = 'icon_cs';
                colorCls = 'c2';
                break;
            case 9:
                assort_type = '公园';
                clsName = 'icon_gy';
                colorCls = 'c3';
                break;
            case 10:
                assort_type = '理发店';
                clsName = 'icon_lfd';
                break;
        }
        arry.push(assort_type,clsName,colorCls);
        return arry;
    }

    // 百度地图API功能
    var map = null;
    var lastOffsetX,
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
        $("#mapNav").find("li").eq(assortType-1).addClass("selected");
        setData(assortType);
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
    function setData(type){
        var datas = parkAssortObj[type],
            relationship = setRelationship(parseInt(type)),
            content = "";
        $(map.getPanes().labelPane).empty();

        $(".map_list").find(".f14 em").text(relationship[0]);
        $(".map_list").find(".f_simsun").text(0);
        $(".map_list").find(".listing").empty();
        if(datas && datas.length > 0){
            $(".map_list").find(".f_simsun").text(datas.length);
            $.each(datas, function(index,data){
                normalOverlay(data, relationship);
                content += '<li class="items" data-id="'+data.assort_id+'"><div class="title"><a class="a_tit" href="#">'+data.assort_name+'</a><em>'+data.distance+'米</em></div>';
                if(!!data.assort_address){
                    content += '<p class="address">'+data.assort_address+'</p>';
                }
                else{
                    content += '<p class="address"></p>';
                }
                content += '</li>';
            });
            $(".map_list").find(".listing").append(content);
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
            if(len >= 3 && $("#mapMainRight").hasClass("viewRight")){
                infoWindow.css({'left':offLeft + 'px', 'top':offTop - 148 + 'px'});
            }
            else if(len >= 5 && $("#mapMainRight").hasClass("map_list")){
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

            if(!$('#mapMainRight').hasClass("viewRight")){
                var top = $('#mapMainRight .items[data-id = '+type_id+']')[0].offsetTop;
                $('#mapMainRight').stop().animate({scrollTop:top},0);
            }
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

    // 自定义信息窗口
    /*function defInfoWindow(data){
     var type = setRelationship(data.type)[0],
     list = data.list,
     str = '<div id="defInfoWindow" class="overLayer">';
     str += '    <span class="layer_tit">' + type + '</span>';
     str += '    <div class="layer_con"><p class="fb">' + list.assort_name + '</p><p class="f_simsun">'+ list.assort_address +'</p></div>';
     str += '    <i class="icons i_close"></i><i class="icons i_bot1"></i>';
     str += '</div>';
     return str;
     }*/

    /*
     * 生成随机数
     * @param min max4
     */
    function random(min,max){
        return Math.floor(min+Math.random()*(max-min));
    }
})();
