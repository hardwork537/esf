/** community_view.js
 * authors : Hqyun
 * date : 2014-08-14 add
 */
$(document).ready(function(){
    var parkAssortObj5 = {},//储存不同距离的数据
        parkAssortObj10 = {},
        parkAssortObj15 = {};

    var parkAssortObj, //处理后按type分类的新对象
        sameAddressObj; //处理后把同经纬度的对象进行整合
    //初始化周边配套的数据

    bindEvents();
    if(!isNullObj(parkAssortData)) {
        var assortType = typeof(parkAssortData[15][0]['type']) !== 'undefined' ? parkAssortData[15][0]['type'] : '';
        initViewData();
        resetData(parkAssortObj15);
        sameAddressObj = getSameAddressObj();
        var count = 0;
        $.each(parkAssortObj15, function (k) {
            count++;
        });
        if (count < 5) {
            $(".tab_change").eq(1).click();
        }
        setTimeout(function(){initMap()},100);
    }

    /*
     * 绑定事件
     */
    function bindEvents(){
        var dd_rank = $('.dd_rank'),
            em_btn = $("#priceChart2 .time_btn"),
            tab_change = $(".tab_change"),
            mapBubbleLeft = $("#mapBubbleLeft"),
            mapMainLeft = $("#mapMainLeft"),
            distance_tab = $(".distance_tab"),
            distance_tabs = $(".distance_tabs"),
            mapMainRight = $('#mapMainRight'),
            viewRight = $(".viewRight"),
            mapNav = $("#mapNav"),
            defInfoWindow = $("#defInfoWindow");

        /*板块排名*/
        dd_rank.hover(function(){
            $(this).find('.rank_info').toggle();
        });

        /*价格走势图切换*/
        em_btn.click(function(){
            var idx = em_btn.index(this);
            $(this).addClass('on').siblings().removeClass('on');

            var priceData = (idx === 0) ? priceTrend.price6 : priceTrend.price12;
            priceTrend && areaChart(priceData);
        });

        /*周边配套tab切换*/
        tab_change.on("click",function(){
            tab_change.siblings(".tab_change").addClass("selected");
            $(this).removeClass("selected");

            var type = $(this).data("type");
            switch (type){
                case "map":
                    resetData(parkAssortObj15);
                    mapBubbleLeft.hide();
                    mapMainLeft.show();
                    distance_tabs.hide();
                    setTimeout(function(){initMap()},100);
                    break;
                case "bubble":
                    mapMainLeft.hide();
                    distance_tabs.show();
                    mapBubbleLeft.show();
                    distance_tabs.find(".selected").click();
                    break;
            }
        });
        tab_change.on("mouseover",function(){
            $(this).addClass("t_hover");
        });
        tab_change.on("mouseout",function(){
            $(this).removeClass("t_hover");
        });

        mapMainRight.on("mouseenter",".items",function(){
            $(this).addClass('hover');
            var type_id = $(this).attr('data-id');
            $('#mapMainLeft .marker2[data-id = '+type_id+']').addClass("marker_curr");
        });
        mapMainRight.on("mouseleave",".items",function(){
            $(this).removeClass('hover');
            var type_id = $(this).attr('data-id');
            $('#mapMainLeft .marker2[data-id = '+type_id+']').removeClass("marker_curr");
        });

        $('.i_close').click(function(){
            $(this).parents('.overLayer').hide();
        });

        distance_tab.click(function(){
            distance_tab.removeClass("selected");
            $(this).addClass("selected");
            var type = $(this).find("a").data("type");
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

        mapBubbleLeft.on("click",".sp_circle,.ie_circle",function(){
            $(this).siblings().removeClass("hover");
            $(this).addClass("hover");
            var type = $(this).data("type");

            viewRight.find(".li_tabs[data-type = '"+type+"']").click();

        });

        //ie678
        var color = "";
        mapBubbleLeft.on("mouseover",".ie_vml",function(){
            $(this)[0].fillcolor = '#fff';
            $(this)[0].strokeweight = 4;
            var type = $(this).parent().data("type");
            var relationArr = setRelationship(parseInt(type));
            switch (relationArr[2]){
                case "c1":
                    color = "#3f90ff";;
                    break;
                case "c2":
                    color = "#ff7e4b";
                    break;
                case "c3":
                    color = "#2ecc7c";
                    break;
            }
        });

        mapBubbleLeft.on("mouseout",".ie_vml",function(){
            $(this)[0].fillcolor = color;
            $(this)[0].strokeweight = 2;
        });

        mapBubbleLeft.on("mouseover",".sp_circle,.ie_circle",function(){
            $(this).siblings().removeClass("hover");
            $(this).addClass("hover");
            $(this).find("em").addClass("e_hover");
            var type = $(this).data("type");

            viewRight.find(".li_tabs").removeClass("hover");
            viewRight.find(".li_tabs[data-type = '"+type+"']").addClass("hover");
        });

        mapBubbleLeft.on("mouseout",".sp_circle,.ie_circle",function(){
            $(this).removeClass("hover");
            $(this).find("em").removeClass("e_hover");
            viewRight.find(".li_tabs").removeClass("hover");
        });

        mapNav.on("click",".li_tabs",function(){
            var type = $(this).attr("data-type");
            assortType = type;
            defInfoWindow.hide();
            $(this).siblings().removeClass("selected");
            $(this).addClass("selected");
            setData(assortType);
        });

        viewRight.on("click",".li_tabs",function(){
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
            defInfoWindow.hide();
            $(".details").hide();
            $(this).siblings().removeClass("selected");
            $(this).addClass("selected");
            $("."+name).show();

            mapBubbleLeft.find(".sp_circle").removeClass("hover");
            var ele = mapBubbleLeft.find(".sp_circle[data-type='"+type+"']");
            ele.addClass("hover");
            ele.find("em").addClass("e_hover");
            var index = $(this).index();
            if(index <= 8){
                $(".round_tabs").animate({scrollTop:0},0);
            }
            if(index > 8){
                $(".round_tabs").animate({scrollTop:100},100);
            }
            setData(assortType);
        });

        viewRight.on("mouseover",".li_tabs",function(){
            if($(this).hasClass("li_gray")){
                return;
            }
            $(this).siblings().removeClass("hover");
            $(this).addClass("hover");
            var type = $(this).data("type");

            mapBubbleLeft.find(".sp_circle").removeClass("hover");
            mapBubbleLeft.find(".sp_circle[data-type='"+type+"']").addClass("hover");
            mapBubbleLeft.find(".sp_circle[data-type='"+type+"']").find("em").addClass("e_hover");

            mapBubbleLeft.find(".ie_circle[data-type='"+type+"']").find(".ie_vml").mouseover();
        });

        viewRight.on("mouseout",".li_tabs",function(){
            $(this).removeClass("hover");
            mapBubbleLeft.find(".sp_circle").removeClass("hover");
            mapBubbleLeft.find(".sp_circle").find("em").removeClass("e_hover");
            var type = $(this).data("type");
            mapBubbleLeft.find(".ie_circle[data-type='"+type+"']").find(".ie_vml").mouseout();
        });
    }

    function resetData(data){
        var mapBubbleLeft = $("#mapBubbleLeft"),
            li_tabs = $(".li_tabs"),
            count = 0,
            bubbleContent = "",
            ieBubbleContent = "",
            index = 0;
        parkAssortObj = data;
        $(".details").hide();
        li_tabs.addClass("li_gray").removeClass("selected");
        li_tabs.find(".details").empty();
        //重置气泡数据
        mapBubbleLeft.empty();

        if(isNullObj(parkAssortObj)){
            mapBubbleLeft.html("<span class='nodata'>暂无数据！</span>");
            return;
        }
        $.each(parkAssortObj,function(k,v){
            count++;
        });

        $.each(parkAssortObj,function(k,v){
            //重置右边模块数据
            var content = "",
                $thiz = $(".li_tabs[data-type = '"+k+"']"),
                name = $thiz.data("name");
            var parkId = $("#hiddenParkId").val();

            $thiz.removeClass("li_gray");
            $thiz.find(".count").html(v.length + "个");
            $("."+name).empty();

            $.each(v,function(index,data){
                if(index < 3){
                    content += "<ul><li class='items' data-id='"+ data.assort_id+"'><a class='a_tit' href='/xiaoqu/"+parkId+"/ditu/"+k+"'>"+ data.assort_name+"</a><em>"+ data.distance+"米</em></li>";
                }
            });
            if(v.length > 3){
                content += "<a href='/xiaoqu/"+parkId+"/ditu/"+k+"' class='more'>更多</a></ul>";
            }
            else{
                content += "</ul>";
            }

            $("."+name).append(content);

            var length = v.length;

            if(parseInt(k) <= 9){
                var top = random(80,210) + "px",
                    left = "";
                if(count < 4){
                    left = (680*index/(count) + (80/count)) +"px";
                }
                else{
                    left = (490*index/(count-1) + (80/count)) +"px";
                }
                var relationArr = setRelationship(parseInt(k));
                var aniClass = index % 2 == 0 ? " ani_down":" ani_up";
                var wClass = length < 9 ? " w"+length: " w9";
                if(parseInt(k) == 9 && count == 9){
                    top = "80px";
                }

                var color = "",
                    strokecolor = "";

                switch (relationArr[2]){
                    case "c1":
                        color = "#3f90ff";
                        strokecolor = "#217dfb";
                        break;
                    case "c2":
                        color = "#ff7e4b";
                        strokecolor = "#fa6428";
                        break;
                    case "c3":
                        color = "#2ecc7c";
                        strokecolor = "#01ba5c";
                        break;
                }
                bubbleContent += "<span class='sp_circle "+relationArr[2] + aniClass + wClass +"' data-name='"+ relationArr[0]+"' data-type='"+k+"' style='left:"+left+"; top:"+top+"'><div class='content'><em><i class='icons "+ relationArr[1]+"'></i></em><span class='word'>"+relationArr[0]+"("+length+")</span></div></span>";
                ieBubbleContent += "<span class='ie_circle' data-name='"+ relationArr[0]+"' data-type='"+k+"'><v:oval fillcolor='"+color+"' strokecolor='"+strokecolor+"' strokeweight='2' class='ie_vml "+wClass+"' style='position:absolute; overflow:hidden;left:"+left+"; top:"+top+"'><div class='content_v "+relationArr[2]+" '><em><i class='icons "+ relationArr[1]+"'></i></em><span class='word'>"+relationArr[0]+"("+length+")</span></div></v:oval></span>";
                index++;
            }

        });

        var isIE = !!window.ActiveXObject,
            isIE6 = isIE && navigator.userAgent.indexOf("MSIE 6.0")>0,
            isIE8 = isIE && navigator.userAgent.indexOf("MSIE 8.0")>0,
            isIE7 = isIE && navigator.userAgent.indexOf("MSIE 7.0")>0,
            currentContent = "",
            left = 0,
            width = "";

        if(!isIE8 && !isIE6 && !isIE7){
            for(var i = 0; i < 6; i++){
                left = 100 * i + 40 + "px";
                width = random(50,150) + "px";
                bubbleContent += "<span class='bg_circle' style='left: "+left+"; width:"+width+"; height:"+width+"'></span>";
            }
            currentContent = bubbleContent;
        }else{
            currentContent = ieBubbleContent;
        }

        mapBubbleLeft.append(currentContent);

        var randomIndex = random(0,7);
        $(".bg_circle").each(function(index){
            var thiz = $(this);
            if(randomIndex == index){
                thiz.addClass("ani");
            }else{
                var ran = random(500,8000);
                setTimeout(function(){thiz.addClass("ani")},ran);
            }
        });
        var flag = false;
        $(".li_tabs").each(function(){
            if(!$(this).hasClass("li_gray") && !flag){
                var thiz = $(this);
                flag = true;
                setTimeout(function(){thiz.click();thiz.mouseout();},200);
            }
        });
    }

    /*
     * 初始化按照距离和类型分类的周边
     */
    function initViewData(){
        $.each(parkAssortData,function(k, v){
            var newObj={};
            $.each(v,function(index,data){
                var typeInt = parseInt(data.type);
                if(typeInt <= 9){
                    if(!newObj[data.type]){
                        newObj[data.type] = [];
                    }
                    newObj[data.type] = data.list;
                }
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

    /*
     * 取出重复的地址标签
     */
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

    /*
     * 建立对应关系
     * @param kw
     */
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

    /*
     * 生成随机数
     * @param min max
     */
    function random(min,max){
        return Math.floor(min+Math.random()*(max-min));
    }

    //判断对象是否为空
    function isNullObj(obj){
        for(var i in obj){
            if(obj.hasOwnProperty(i)){
                return false;
            }
        }
        return true;
    }

    /*
     * 周边配套地图信息
     */
    var map = null,
        lastOffsetX,
        lastOffsetY;

    function initMap(){
        var mapNav = $("#mapNav"),
            defInfoWindow = $("#defInfoWindow");

        map = new BMap.Map("mapMainLeft", {enableMapClick: false});
        map.centerAndZoom(new BMap.Point(parkX,parkY), 15);
        map.enableScrollWheelZoom();
        var bottom_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左下角，添加默认缩放平移控件
        map.addControl(bottom_left_control);
        map.addControl(top_left_navigation);
        defaultOverlay();
        mapNav.find("li").removeClass("selected");
        mapNav.find("li").eq(assortType-1).addClass("selected");
        setData(assortType);
        defInfoWindow.hide();

        map.addEventListener("dragging", function (e) {
            var currentTarget = e.currentTarget;
            //var defInfoWindow = $("#defInfoWindow");
            defInfoWindow.css("top",parseInt(defInfoWindow.css("top").split("px")[0]) + currentTarget.offsetY - lastOffsetY);
            defInfoWindow.css("left",parseInt(defInfoWindow.css("left").split("px")[0]) + currentTarget.offsetX - lastOffsetX);
            lastOffsetX = currentTarget.offsetX;
            lastOffsetY = currentTarget.offsetY;
        });
        map.addEventListener("zoomend", function (e) {
            defInfoWindow.hide();
        });
    }

    /*
     * 自定义覆盖物
     * @param element
     * @param point
     */
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

        if(datas && datas.length > 0){
            $.each(datas, function(index,data){
                normalOverlay(data, relationship);
            });
        }

    }

    /*
     * 周边配套
     * @param data
     * @param relationship
     */
    function normalOverlay(data, relationship){
        var element = '<a href="javascript:;" class="marker marker2" data-id="'+ data.assort_id +'" data-x="'+ data.x +'"><i class="'+relationship[1]+'"></i></a>',
            myCustomOverlay = new customOverlay(element, new BMap.Point(data.x,data.y)),
            mapMainRight = $("#mapMainRight");

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
            if(len >= 3 && mapMainRight.hasClass("viewRight")){
                infoWindow.css({'left':offLeft + 'px', 'top':offTop - 148 + 'px'});
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

        });

        myCustomOverlay.addEventListener('mouseout',function(){
            var type_id = $(this).attr('data-id');
            $('#mapMainRight .items[data-id = '+type_id+']').removeClass("hover");
        });
    }

    /*
     * 默认显示小区标注
     */
    function defaultOverlay(){
        var element = '<span class="marker marker1" style="white-space: nowrap;">'+ parkName +'<i class="icons i_bot2"></i></span>',
            myCustomOverlay = new customOverlay(element, new BMap.Point(parkX,parkY));
        map.addOverlay(myCustomOverlay);
    }

    /**
     * 图片切换(自动播放、左右切换)
     */
    function imgSlider(){
        var focus = $('#topFousSlider'),
            idx = 0,
            speed = 5000,
            thum = $('#silderNav i'),
            len = thum.length,
            bigImg = focus.find('.l_img'),
            btn = $('.l_btn'),
            btnPre = $('#btnPre'),
            btnNxt = $('#btnNxt'),
            nav = $('#silderNav');

        var bg_width = len * 24 + 'px';
        nav.css("width", bg_width);
        if(len === 1){
            nav.hide();
        }

        function doPlay() {
            (idx === len - 1 || idx > len - 1) ? idx = 0 : idx += 1;
            thum.eq(idx).addClass('selected').siblings().removeClass('selected');
            (len > 1) && doAnimate();
        }

        function bindEvent(){
            thum.mouseenter(function(){
                idx = $('#silderNav i').index(this);
                switchImg(idx);
                doAnimate();
            });

            thum.mouseleave(function(){
                timer = setInterval(doPlay, speed);
            });

            btn.hover(function(){
                $(this).find('i').toggle();
            });

            //点击上一张
            btnPre.click(function(){
                (idx <= 0) ? idx = len -1 : idx --;
                switchImg(idx);
                doAnimate();
                timer = setInterval(doPlay, speed);
            });

            //点击下一张
            btnNxt.click(function(){
                (idx === len - 1) ? idx =0 : idx ++;
                switchImg(idx);
                doAnimate();
                timer = setInterval(doPlay, speed);
            });
        }
        bindEvent();

        function switchImg(index){
            clearInterval(timer);
            thum.eq(idx).addClass('selected').siblings().removeClass('selected');
        }

        function doAnimate(){
            bigImg.hide();
            bigImg.eq(idx).show();
            bigImg.filter('.curr').stop().animate({ 'opacity': '0' }, 800).removeClass('curr');
            bigImg.eq(idx).stop().animate({ 'opacity': '1' }, 300).addClass('curr');
        }

        var timer = setInterval(doPlay, speed);
    }
    imgSlider();

    /**
     * 图表-环形图（老年人占比 & 租房占比）
     * @param obj 图表对象
     */
    function pieChart(obj,val){
        var bg_img1 = 'bg_old.png',
            bg_img2 = 'bg_rent.png',
            bg_img3 = 'bg_plant.png',
            cls1 = 'icon_old',
            cls2 = 'icon_rent',
            cls3 = 'icon_plant',
            col1 = '#3f90ff',
            col2 = '#ff7e4b',
            col3 = '#2ecc71',
            bg_img, cls, col;

        if(obj.attr('id') === 'oldChart'){
            bg_img = bg_img1;
            cls = cls1;
            col = col1;
        }else if(obj.attr('id') === 'rentChart'){
            bg_img = bg_img2;
            cls = cls2;
            col = col2;
        }else{
            bg_img = bg_img3;
            cls = cls3;
            col = col3;
        }

        var str = '<p class="icon_com '+ cls +' for_ie"><v:oval style="width:60px;height:60px;position:absolute;left:0; top:0; overflow:hidden;" fillcolor="'+ col +'" stroked="f" class="ie_vml"><v:fill type="frame" src="img/'+ bg_img +'" class="ie_vml" /></v:oval></p>';

        var num1 = parseInt(val), num2 = parseInt(100 - num1);
        obj.highcharts({
            chart: {
                renderTo: obj,
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                useHTML: true,
                text: str,
                align: 'center',
                verticalAlign: 'middle'
            },
            tooltip: {
                enabled: false
            },
            colors: [col, '#dcdcdc'] ,
            plotOptions: {
                pie: {
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'	//格式化数据
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                type: 'pie',
                innerSize: '95%',
                data: [
                    ['优质房源', num1],
                    ['非优质房源', num2]
                ]
            }]
        });
    }

    var lastTop = "";
    $(window).scroll(function(){
        var top = $(window).scrollTop();
        (top > 620) ? $('#communityFixedTop').fadeIn(100) : $('#communityFixedTop').fadeOut(100);

        if(top >= 700 && lastTop < 700){
            pieChart($('#oldChart'),$('#oldChart').attr('data-value'));
            pieChart($('#rentChart'),$('#rentChart').attr('data-value'));
            pieChart($('#plantChart'),$('#plantChart').attr('data-value'));
        }
        lastTop = top;
    });

    /**
     * 图表-柱状图（房价对比）
     * @param obj 图表对象
     */
    function columnChart(){
        var priceChart = $('#priceChart'),
            txt1 = priceChart.attr('txt1'),
            txt2 = priceChart.attr('txt2'),
            num1 = parseInt(priceChart.attr('num1')),
            num2 = parseInt(priceChart.attr('num2'));

        priceChart.highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                lineColor: '#ccc',
                categories: [
                    txt1,
                    txt2
                ],
                labels: {
                    style: {
                        fontSize:'14px',
                        fontFamily:'宋体'
                    }
                }
            },
            yAxis: {
                title: {
                    text : ''
                },
                labels: {
                    formatter: function() {
                        return this.value + '元';
                    },
                    style: {
                        fontSize:'14px',
                        fontFamily:'宋体'
                    }
                },
                lineColor: '#ccc',
                lineWidth: 1 //基线宽度
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br>' + this.x + '均价：' + this.y + '元';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            series: [{
                name:'房价对比',
                data: [
                    { name: txt1, y: num1, color: '#3f90ff'},
                    { name: txt2, y: num2, color: '#ff7e4b'}
                ],
                dataLabels: {
                    formatter: function() {
                        return '<span style="font:100 12px/1 arial;"><b style="font:700 20px/1 arial;">' + this.y + '</b>' + ' 元/平</span>';
                    },
                    enabled: true,
                    rotation: -360,
                    color: '#000',
                    align: 'right',
                    x: 48,
                    y: -8
                }
            }]
        });
    }
    columnChart();

    /**
     * 图表-区域图（价格走势）
     */
    function areaChart(data){
        if(!data){
            var nodata = '<span class="nodata">暂无数据！</span>';
            $("#areaChart").html(nodata);
        }else{
            var month = data[0].month,
                park = data[1].parkData,
                regin = data[2].regionData,
                areaChart = $('#areaChart'),
                txt1 = areaChart.attr('txt1'),
                txt2 = areaChart.attr('txt2');
			var parkSum = 0, reginSum = 0;
			$.each(park,function(i,val){
				parkSum += val;
			});
			$.each(regin,function(i,val){
				reginSum += val;
			});
			var parkSeries = {
                    color: '#f2f9ff',
                    lineColor: '#3f90ff',
                    lineWidth: 1,
                    name: txt1,
                    marker: {
                        symbol: 'circle',
                        fillColor: '#3f90ff',
                        lineColor: '#fff',
                        lineWidth: 2,
                        radius: 6,
                        states: {
                            hover: {
                                lineColor: '#fff',
                                lineWidth: 2,
                                radius: 9
                            }
                        }
                    },
                    data: park
                };
			var reginSeries = {
                    color: '#fff2ed',
                    lineColor: '#ff7e4b',
                    lineWidth: 1,
                    name: txt2,
                    marker: {
                        symbol: 'circle',
                        fillColor: '#ff7e4b',
                        lineColor: '#fff',
                        lineWidth: 2,
                        radius: 6,
                        states: {
                            hover: {
                                lineColor: '#fff',
                                lineWidth: 2,
                                radius: 9
                            }
                        }
                    },
                    data: regin
                };

            areaChart.highcharts({
                chart: {
                    type: 'area',
                    marginLeft: 70
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                labels: {
                    formatter: function() {
                        return month[this.value];
                    }
                },
                xAxis: {
                    minPadding: 0,
                    labels: {
                        formatter: function() {
                            return month[this.value];
                        },
                        style: {
                            fontSize: '14px',
                            fontFamily: '宋体'
                        },
                        y: 35
                    },
                    lineColor: '#dbdbdb',
                    tickLength: 0,
                    tickInterval: 1
                },
                yAxis: {
                    min: 0,
                    showFirstLabel: false,
                    title: {
                        text: ''
                    },
                    lineColor: '#dbdbdb',
                    lineWidth: 1,
                    tickWidth: 1,
                    tickLength: 6,
                    tickColor: '#dbdbdb',
                    gridLineWidth: 1,
					gridLineColor: '#eee',
                    labels: {
                        formatter: function() {
                            return this.value + '元';
                        },
                        style: {
                            fontSize: '14px',
                            fontFamily: '宋体'
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    followPointer: true,
                    formatter: function() {
                        var s = month[this.x] + '均价：<br>';
                        $.each(this.points,function(){
                            s += this.series.name + ': <b>' + this.y +'元' + '</b><br>';
                        });
                        return s;
                    },
                    style: {
                        lineHeight: '20px'
                    }
                },
                legend: {
                    enabled: false
                },
                series: (function(){
					if(parkSum>reginSum){
						return [parkSeries,reginSeries];
					}else{
						return [reginSeries,parkSeries]
					}
				})()
            });
        }
    }
    priceTrend && areaChart(priceTrend.price6);

    /**
     * 全区域可点击
     * @param obj
     */
    function clickEvent(obj){
        var items = obj;
        items.length && items.each(function() {
            $(this).mouseenter(function(){
                $(this).addClass('list_hover');
            });
            $(this).mouseleave(function(){
                $(this).removeClass('list_hover');
            });
            $(this).click(function(){
                var a = document.createElement("a");
                a.href = $(this).find('a').eq(0).attr('href');
                a.target = "_blank";
                document.body.appendChild(a);
                a.click();
            });
            $(this).find('a').each(function(){
                $(this).click(function(e){
                    if(e.stopPropagation){
                        e.stopPropagation();
                    }else{
                        window.event.cancelable = true;
                    }
                })
            });
        });
    }
    clickEvent($('#viewEsf .li_items'));
    clickEvent($('#viewCommunity .li_items'));
    clickEvent($('#viewHot .li_items'));
});