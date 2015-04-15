var mapObj,local,transit, driving,
    min_zoom_size = 11,
    default_zoom = 13,
    totalPage = 0,
    currentPage = 1,
    GLOBAL_SEARCH_RESULT = false,//全局检索状态
    ROUTE_STATUS = false,//检索路线状态
    GLOBAL_HOUSE_STATUS = true;//全部房源
var parkId='';
var SHOW_MORE_END = true
$(function(){
    $("#keyword").val('');
    initMap();

    ScreenControl.trigger();
    $(window).resize(); //初始化屏幕

    bindEvent();
    $(".results_nav").find("a").eq(0).click();
});

function bindEvent() {
    //筛选条件事件
    var filterNaV = $('#filter_nav');
    filterNaV.on("mouseenter", "li", function () {
        $('#filter_nav').find('li').removeClass('active');
        $(this).find('.sub_items').hide();
        $(this).addClass('active').find('.sub_items').show();
    });
    filterNaV.on("mouseleave", "li", function () {
        $(this).find('.sub_items').hide();
        $('#filter_nav').find('li').removeClass('active');
    });
    filterNaV.on("click", ".sub_items p", function () {
        var ids = $(this).attr("id");
        var value = $(this).text();
        var type = ids.substr(1);
        $(this).parent().hide();
        $(this).parent().siblings("a").text(value);
        $(this).parent().siblings("a").attr("value",type);
        if (type != 0 ) {
            mapObj.centerAndZoom(mapObj.getCenter(), default_zoom + 3);
        }

        getSearchHouse(1);
    });
    //地图操作事件（缩放，图层，商圈）
    $("#map_opt_wapper").on("click", "a", function () {

        if ($(this).hasClass("zoom_in")) {
            mapObj.zoomIn();
        }
        if ($(this).hasClass("zoom_out")) {
            if (mapObj.getZoom() - 1 < min_zoom_size) return false;
            mapObj.zoomOut();
        }

        if ($(this).hasClass("coverage")) {
            $(".tips_wapper").hide();
            $(".tips_wapper[tips-block='coverage']").show();
        }
        if ($(this).hasClass("business_area")) {
            $(".tips_wapper").hide();
            $(".tips_wapper[tips-block='business_area']").show();
        }
    });
    //图层上的按钮点击
    $(".tips_wapper").on("click", "a", function () {
        if ($(this).hasClass("close")) {
            $(".tips_wapper").hide();
        }
    });
    $(".tips_wapper[tips-block='coverage']").on("click", "li", function () {
        var content = $(this).find("a").text();
        var type = $(this).attr("data-type");
        local.searchInBounds(content, mapObj.getBounds());
        local.setSearchCompleteCallback(function (results) {
            showSearchResult(type, results)
        });
    });
    //地图圆圈覆盖物点击
    var lastZoom = default_zoom;
    $("#map_wapper").on("click", ".amp_area", function (event) {
        var ids = $(this).find(".area_id").text();
        var areaData = DISTRICT_CONF[ids];
        lastZoom = mapObj.getZoom();
        var point = new BMap.Point(areaData.x, areaData.y);
        addLocation(point);
        mapObj.centerAndZoom(point, default_zoom + 3);
    });

    mapObj.addEventListener("zoomend", function () {
        if(ROUTE_STATUS){
            return;
        }
        if (mapObj.getZoom() > default_zoom) {
            getDetailHouse();
        }
        else if (mapObj.getZoom() <= default_zoom && lastZoom > default_zoom) {
            showTotalHouse();
        }
        lastZoom = mapObj.getZoom();
    });
    //商圈搜索事件
    var select_wapper = $(".select_wapper");
    select_wapper.on("mouseenter", function () {
        $(this).find('ul').show();
        $(this).find('.current').removeClass('up').addClass('down');
    })
    select_wapper.on("mouseleave", function () {
        $(this).find('ul').hide();
        $(this).find('.current').removeClass('down').addClass('up');
    });
    select_wapper.on("click", "li", function () {
        var content = "";
        if ($(this).parent().parent().hasClass("city")) {
            var distId = $(this).attr("val");
            var parent = $(this).parent();
            parent.siblings('div').text($(this).text());
            parent.hide();
            $.ajax({
                url: '/ajax/getRegionByDistId',
                type:'post',
                data:{distId:distId},
                success:function(data){
                    data = eval('('+data+')');
                    if (data!== null && data !== undefined && data !== '') {
                        $(".street .opt_list").empty();
                        $.each(data, function (i, n) {
                            content += "<li val='" + i + "' lng='" + n.bX + "' lat='" + n.bY + "'>" + n.name + "</li>"
                        });
                        $(".street .opt_list").append(content);
                        parent.find('.current').removeClass('down').addClass('up');
                    }
                    else{
                        parent.siblings('div').text("请选择区县");
                    }
                }
            });
        }
        if ($(this).parent().parent().hasClass("street")) {
            var lng = $(this).attr('lng');
            var lat = $(this).attr('lat');
            //清除地图上的标记
            $(mapObj.getPanes().markerPane).empty();
            $(mapObj.getPanes().labelPane).empty();

            if (lng != 0 || lat != 0) {
                //定位并放大
                var parent = $(this).parent();
                parent.siblings('div').text($(this).text());
                parent.hide();
                parent.find('.current').removeClass('down').addClass('up');
                var point = new BMap.Point(lng, lat);
                addLocation(point);
                mapObj.centerAndZoom(point, min_zoom_size + 5);
            }
            else {
                $(this).parent().siblings('div').text("请选择商圈");
            }
        }
    });
    $(".input_clear").on("click", function () {
        $(this).hide();
        $(this).parent().addClass("no_result");
        $(this).siblings("input").val('');
        resultUl.hide();
    });
    //搜索框
    var searchBlock = $(".search_Block"),
        resultUl = $(".search_results ul");
    var thread = null;
    searchBlock.on("keyup","#keyword",function(){
        var value = $(this).val(),
            content = '';
        if(value == ""){
            $(this).parent().addClass("no_result");
            resultUl.hide();
            $(".input_clear").hide();
            return;
        }
        else{
            $(".input_clear").show();
            $(this).parent().removeClass("no_result");
        }
        window.clearTimeout(thread);
        thread = window.setTimeout(function(){
            //从服务器获得匹配的数据
            $.ajax({
                url:'/ajax/getParkName',
                type:"post",
                data:{q:value,is_showb:0},
                success:function(data){
                    var datas = eval('('+data+')');
                    resultUl.empty();
                    $.each(datas,function(index,data){
                        content += "<li data-id='"+data.id+"' data-x='"+data.BdX+"' data-y='"+data.BdY+"' data-avg-price='"+data.salePrice+"'>"+data.name+"</li>"
                    });
                    resultUl.append(content);
                    resultUl.show();
                }
            });
        },200);
    });
    searchBlock.on("keypress","#keyword",function(e){
        var key = e.which; //e.which是按键的值
        if (key == 13) {
            $("#map_lp_submit").click();
        }
    });
    $("#map_lp_submit").on("click",function(){
        var keyword = searchBlock.find("#keyword").val();
        $(".results_list_b .nav_desc").show();
        $(".results_list_b #result_pager").show();
        resultUl.hide();

        if(keyword == ""){
            return;
        }
        $(mapObj.getPanes().markerPane).empty();
        GLOBAL_SEARCH_RESULT = true;
        $.ajax({
            url: '/ajax/getPark',
            type:"post",
            data:{q:keyword},
            success:function(data){
                data = eval('('+data+')');
                if (data.code == 200){
                    parkId = data.id;
                    $("#chooseParkId").val(data.id);
                    mapObj.centerAndZoom(new BMap.Point(data.BdX,data.BdY),default_zoom + 3);
                }
                else{
                    local.search(keyword);
                    local.setSearchCompleteCallback(showSearchResultKW);
                    mapObj.setZoom(default_zoom + 3);
                }
            },
            error:function(){
                local.search(keyword);
                local.setSearchCompleteCallback(showSearchResultKW);
                mapObj.setZoom(default_zoom + 3);
            }
        });

    });
    resultUl.on("click","li",function(){
        GLOBAL_SEARCH_RESULT = true;
        var x = $(this).attr("data-x"),
            y = $(this).attr("data-y"),
            id = $(this).attr("data-id");

        $("#chooseParkId").val(id);
        parkId = id;
        $("#keyword").val($(this).text());
        $(this).parent().hide();
        mapObj.centerAndZoom(new BMap.Point(x,y),default_zoom + 3);
        //$(".amp_house[data-id="+id+"]").click();

    });

    //房源事件，查看详情
    var mapWapper = $("#map_wapper");
    mapWapper.on("click", ".amp_house", function () {
        $('.amp_house').attr('data_focus', 0).removeClass("active");

        $(this).attr('data_focus', 1).addClass("active");//小区标签设为选中样式
        $(".results_route").hide();
        $(".results_nav").show();
        $(".results_list").show();
        $(".results_list_b").hide();
        $(".results_search").hide();

        var resultContainer = $(".results_title");
        house = resultContainer.find(" #select_house");
        resultContainer.find("h3").text($(this).find(".address").text());
        house.attr("x", $(this).find(".h_lng").text());
        house.attr("y", $(this).find(".h_lat").text());
        house.attr("house_id", $(this).find(".h_id").text());
        house.find("a").attr("href", "/xiaoqu/" + $(this).find(".h_id").text() + "/");
        house.find("a").text($(this).find(".h_name").text());
        parkId = $(this).find(".h_id").text();
        $("#chooseParkId").val(parkId);
        GLOBAL_HOUSE_STATUS = false;
        showSearchDetailResult();
    });
    mapWapper.on("mouseenter", ".amp_house", function () {
        $(this).addClass('active');
    });
    mapWapper.on("mouseleave", ".amp_house[data_focus != 1]", function () {
        $(this).removeClass('active');
    });
    //右边框事件
    var scrollPanel = $(".scroll_panel");
    scrollPanel.on("click", ".results_close_icon", function () {
        GLOBAL_SEARCH_RESULT = false;
        GLOBAL_HOUSE_STATUS = true;
        $("#chooseParkId").val("");
        $(".amp_house").attr('data_focus', 0).removeClass("active");
        $(".amp_house").show();

        ROUTE_STATUS = false;
        parkId='';
        $(".results_route").hide();
        $(".results_search").hide();
        $(".results_list_b").hide();
        getDetailHouse();

    });

    scrollPanel.on("click", ".results_nav a", function () {
        $(this).siblings("a").removeClass("active");
        $(this).addClass("active");
        var type = $(this).attr("v");
        var currentElement = $(".results_list ul[class=" + type + "]");
        currentElement.siblings().hide();
        currentElement.show();

        if (type == "realtor") {
            $(".results_nav").find(".person").hide();
            $(".results_nav").find(".realtor").show();
        }
        else if (type == "person") {
            $(".results_nav").find(".realtor").hide();
            $(".results_nav").find(".person").show();
        }

        if (mapObj.getZoom() > default_zoom) {
            getDetailHouse();
        }
    });
    var scrollheight, scrollTop;
    $(".scroll_panel").scroll(function(){
        scrollheight = $(this).height();
        scrollTop = $(this).scrollTop();
        if (scrollTop + scrollheight - 100 >= $("#scroll_bd").height()) {
            showMore();
        }
    });

    //全景跳转
    $(".results_title").on("click", "#map_vista", function () {
        var resultContainer = $(".results_title");
        var house = resultContainer.find(" #select_house");
        var id = house.attr("house_id");
        $(this).attr("href", "/map/street?parkId="+id);
    });
    //路线规划
    $(".results_title").on("click", "#map_find_way", function () {
        $(".amp_house[data_focus != 1]").hide();
        ROUTE_STATUS = true;
        var endAddress = $("#select_house a").text();
        $(".search_wapper").find(".start_wapppr input").val('')
        $(".search_wapper").find(".end_wapppr input").val(endAddress);
        $(".results_search").show();
        $(".results_nav").hide();
        $(".results_list").hide();
        ScreenControl.scrollFooter();
    });
    $(".start_wapper").on("keyup", "input", function () {
        if ($(this).val() == '') {
            $(this).parent().addClass("input_tips");
        }
        else {
            $(".start_wapper").removeClass("need_tips");
            $(this).parent().removeClass("input_tips");
        }
    });
    //计算，找出起点
    var startPoint;
    var endPoint;
    $("#calculate_way").on("click", function () {
        var startInput = $(".start_wapper input");
        if(startInput.attr("x") && startInput.attr("y")){
            var x = startInput.attr("x");
            var y = startInput.attr("y");
            startPoint = new BMap.Point(x, y);
            $("#nav_bus").click();
        }
        else{
            var keyWord = $(".start_wapper input").val();
            $(".results_list_b .nav_desc").hide();
            $(".results_list_b #result_pager").hide();
            if (keyWord == '') {
                $(".start_wapper").addClass("need_tips");
                return;
            }
            GLOBAL_SEARCH_RESULT = false;
            $(mapObj.getPanes().markerPane).empty();
            local.search(keyWord);
            local.setSearchCompleteCallback(showSearchResultKW);
        }
    });

    $(".results_list_b").on("mouseenter","li",function(){
        var lng = $(this).attr("data_x"),
            lat = $(this).attr("data_y");
        mapObj.centerAndZoom(new BMap.Point(lng, lat),mapObj.getZoom());
    });

    $("#results_search_close").on("click",function(){
        $(".results_close_icon").click();
    });
    //路线检索

    $(".results_list_b").on("click","li",function(){
        if(GLOBAL_SEARCH_RESULT){
            GLOBAL_HOUSE_STATUS = true;
            showSearchAllResult();
        }
        else{
            var x = $(this).attr("data_x");
            var y = $(this).attr("data_y");
            startPoint = new BMap.Point(x, y);
            $("#nav_bus").click();
        }
    });
    $(".results_route").on("click","a",function(){
        $(this).addClass("active");
        $(this).siblings().removeClass("active");
        $(".results_route").show();
        $(".results_list_b").hide();

        var endX = $("#select_house").attr("x");
        var endY = $("#select_house").attr("y");
        endPoint = new BMap.Point(endX, endY);

        ROUTE_STATUS = true;
        var overlays = mapObj.getOverlays();
        $.each(overlays,function(index,overlay){
            if(!(overlay instanceof ComplexCustomOverlay)){
                mapObj.removeOverlay(overlay);
            };
        });

        if($(this).attr("id") == "nav_bus"){
            transit.search(startPoint,endPoint);

            transit.setSearchCompleteCallback(function(results){
                if(transit.getStatus() == BMAP_STATUS_SUCCESS){
                    showTransitRoute(results);
                }
            });
        }
        else{
            driving.search(startPoint,endPoint);
            driving.setSearchCompleteCallback(function(results){
                if(driving.getStatus() == BMAP_STATUS_SUCCESS){
                    showDrivingRough(results);
                }
            });
        }
    });
    //页码切换
    $("#result_pager").on("click","a",function(){
        var page = $(this).attr("page");
        currentPage = page;
        local.gotoPage(parseInt(page));

    });
    $("#clear_condition").on("click",function(){
        $(".results_close_icon").click();
    });

    $(".results_route").on("mouseenter",".detail_item",function(){
        if($("#nav_car").hasClass("active")){
            return;
        }
        var results = transit.getResults();
        var index = $(this).index();
        drawTransitLine(results.getPlan(index));
    });
    $(".results_route").on("mouseenter","li",function(){
        var index = $(this).index();
        if($("#nav_bus").hasClass("active") || index == 0){
            return;
        }
        var results = driving.getResults();
        var planIndex = $(this).parent().parent().attr("data_index");
        var plan = results.getPlan(planIndex);
        var route = plan.getRoute(0);
        var startPosition = route.getStep(index - 1).getPosition();
        var endPosition = route.getStep(index).getPosition();
        var drivingDetail =  new BMap.DrivingRoute(mapObj);
        drivingDetail.search(startPosition,endPosition);
        drivingDetail.setSearchCompleteCallback(function(result){
            var path = result.getPlan(0).getRoute(0).getPath();
            showCurrentDrivingPoint(path);
        });
    });
    var start_marker,
        clickEventListener = null;
    //选择气点
    $("#search_start_icon").on("click",function(){
        $(mapObj.getPanes().markerPane).empty();

        /*根据地图点击坐标 添加起点标注*/
        clickEventListener = mapObj.addEventListener("click",function(e){
            $('.start_wapper input').attr('x', e.point.lng).attr('y', e.point.lat);
            addStartLocation(e.point);
            $('#start_input').val('已选择起点').parent().removeClass('input_tips');
        });
        mapObj.removeEventListener(clickEventListener);
    });
    //地图可视化区域改变时触发事件
    mapObj.addEventListener("moveend",function(){
        if(mapObj.getZoom() <= default_zoom || ROUTE_STATUS){
            return;
        }
        getDetailHouse();
    });

    //切换地图模式和列表模式
    $('.model_Block > a:eq(0)').click(function(){
        var url = location.href;
        var arrTmp = url.replace(/http:\/\//,'').split('/');
        url = 'http://'+arrTmp[0]+'/'+UNIT_TYPE+'/';
        if($('#first').attr('value') > 0){
            url = url+'j'+$('#first').attr('value');
        }
        if($('#second').attr('value') > 0){
            url = url+'h'+$('#second').attr('value');
        }
        if(UNIT_TYPE == 'sale'){
            if($('#third').attr('value') > 0){
                url = url+'m'+$('#third').attr('value');
            }
        }else{
            if($('#third').attr('value') > 0){
                url = url+'f'+$('#third').attr('value');
            }
        }
        if($('#keyword').attr('data-id') > 0){
            url = url+'x'+$('#keyword').attr('data-id');
        }
        window.open(url);
    });
    //切换城市
    $(".select_city").click(function () {
        var $cityArea = $(this).parent().find(".select_city_panel");
        if (!$cityArea.is(":animated")) {
            $cityArea.fadeIn("fast");
            $(this).find('em').addClass("arrow_up_b");
            $(this).find('em').removeClass("arrow_down_b");
        }
    });
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest(".select_city_panel").length == 0) {
            var $cityArea = $(".select_city_panel");
            if (!$cityArea.is(":animated")) {
                $cityArea.fadeOut("fast");
                $(".select_city").find('em').addClass("arrow_down_b");
                $(".select_city").find('em').removeClass("arrow_up_b");
            }
        }
    });
}
function getDetailHouse(){
    //ajax请求地图展现区域的小区
    var geoInfo = getGeoInfo();
    showDetailHouse(geoInfo);

}
function getGeoInfo(){
    var bounds = mapObj.getBounds(),
        geoObj = {};
    var lngMin = bounds.getSouthWest().lng,
        lngMax = bounds.getNorthEast().lng,
        latMin = bounds.getSouthWest().lat,
        latMax = bounds.getNorthEast().lat;
    geoObj = {"lngMin":lngMin,"lngMax":lngMax,"latMin":latMin,"latMax":latMax};
    return geoObj;
}
//显示更多数据
var houseCurrentPage = 1;
function showMore(){
    if(SHOW_MORE_END == false){
        return;
    }
    $("#scroll_loading").show();
    houseCurrentPage++;
    showResultMore(houseCurrentPage);
}
function showResultMore(page){
    if(SHOW_MORE_END == false){
        return;
    }
    SHOW_MORE_END = false;
    var url = $("#strUnitType").val() == 'sale' ? "/map/shou/" : "/map/zu/";
    var moreFlag =  1;
    $.ajax({
        url:url,
        type:"post",
        data:{"action":$(".n_tab.active").attr("data"),"moreFlag":moreFlag,"parkId":parkId,"page":page,"a":$("#third").attr("value"),"p":$("#first").attr("value"),"b":$("#second").attr("value")},
        success:function(data){
            var ulClass = $(".results_nav").find(".active").attr("v");
            $(".all_list").find("."+ulClass).append(data);
            $("#scroll_loading").hide();
            SHOW_MORE_END = true;
        }
    });

}
//公交路线
function showTransitRoute(results){
    var length = results.getNumPlans(),
        content = "";
    $(".route_detail_items").empty();
    for(var i = 0; i < length; i++){
        var result = results.getPlan(i);
        var lineLength = result.getNumLines();
        var lineName = "",
            ulContent = "";

        var routeCount = result.getNumRoutes();
        for(var k = 0; k < routeCount; k++){
            var walk = result.getRoute(k);
            if (walk.getDistance(false) > 0){
                var arrived = !result.getLine(k)?"终点":result.getLine(k).getGetOnStop().title;
                ulContent += "<li><i class='map_icon person'></i>步行"+walk.getDistance(true) + "到达" + arrived +"</li>";
            }
            if(k < result.getNumLines()){
                var line = result.getLine(k);
                ulContent += "<li><i class='map_icon bus'></i>乘坐"+line.title+"，途径"+line.getNumViaStops()+"站,"+"到达"+line.getGetOffStop().title +"</li>";
                lineName += line.title.split("(")[0];
                if(k < (lineLength - 1)){
                    lineName += "→";
                }
            }
        }
        content += "<div class='detail_item' data_index='"+i+"'><div class='item_title'><div class='item_title_wapper'>" +
            "<span class='time'>约"+result.getDuration()+"</span>"+lineName+"</div></div><ul>"+ulContent+"</ul></div>";
    }
    drawTransitLine(results.getPlan(0));
    addStartPoint(results.getStart().point);
    $(".route_detail_items").append(content);
    ScreenControl.scrollFooter();
}
//驾车路线
function showDrivingRough(results){
    var length = results.getNumPlans(),
        content = "";
    $(".route_detail_items").empty();
    for(var i = 0; i < length; i++){
        var result = results.getPlan(i);
        var drivingLength = result.getNumRoutes(),
            roughContent = "";
        for(var j = 0; j < drivingLength; j++){
            var driving = result.getRoute(j);
            var stepCount = driving.getNumSteps();
            for(var k = 0; k < stepCount; k++){
                var step = driving.getStep(k);
                roughContent += "<li><span class='station'>"+step.getDescription(false)+"</span></li>";
            }
        }
        content += "<div class='detail_item' data_index='"+i+"'><div class='item_title'><div class='item_title_wapper'>" +
            "<span class='time'>约"+result.getDuration()+"</span>距离目的地大约是"+result.getDistance()+"</div></div><ul>"+roughContent+"</ul></div>";
    }
    $(".route_detail_items").append(content);
    ScreenControl.scrollFooter();
}
function initMap(){
    mapObj = new BMap.Map("allMap",{enableMapClick:false});
    mapObj.enableScrollWheelZoom();
    mapObj.setMinZoom(min_zoom_size);
    mapObj.setCurrentCity(CITY_NAME);
    local = new BMap.LocalSearch(mapObj);

    transit = new BMap.TransitRoute(mapObj);
    driving = new BMap.DrivingRoute(mapObj ,{renderOptions:{map: mapObj, autoViewport: true}});

    showTotalHouse();
}
//显示总的房源数
function showTotalHouse(){
    mapObj.clearOverlays();
    $.each(DISTRICT_CONF,function(key,value){
        addCircleOverlay(value);
    });
}
//显示详细的房源
function showDetailHouse(geoInfo){
    $.ajax({
        url:'/ajax/getParkMapMark',
        type:"post",
        data:{lngMin:geoInfo.lngMin,lngMax:geoInfo.lngMax,latMin:geoInfo.latMin,latMax:geoInfo.latMax,type:$(".n_tab.active").attr("data")},
        success:function(data){
            data = eval('('+data+')');
            mapObj.clearOverlays();
            if(data==''){
                $(".tips_wapper[tips-block=no_result]").show();
                return;
            }
            $(".tips_wapper[tips-block=no_result]").hide();
            if (data){
                parkId = '';
                $.each(data, function(index,data){
                    parkId = parkId+data.id+',';
                    addDetailOverlay(data);
                });
                if ($("#chooseParkId").val()){
                    $(".amp_house[data-id="+$("#chooseParkId").val()+"]").click();
                }
                parkId = parkId.substr(0, parkId.length-1);

            }
            if(GLOBAL_HOUSE_STATUS){
                showSearchAllResult();
            }

        },
        error:function(){
            mapObj.clearOverlays();
        }
    });
}
//显示根据小区检索出来的房源
function showSearchDetailResult(){
    var scrollBd = $("#scroll_bd");
    houseCurrentPage = 1;
    getSearchHouse(houseCurrentPage);
    scrollBd.find(".results_title").show();
    scrollBd.find(".search_list").show();
    $(".results_close_wapper").show();
    ScreenControl.scrollFooter();
}
//显示所有的房源
function showSearchAllResult(){
    var scrollBd = $("#scroll_bd");
    houseCurrentPage = 1;
    getSearchHouse(houseCurrentPage);
    scrollBd.find(".all_list").show();

    scrollBd.find(".results_list").show();
    scrollBd.find(".results_nav").show();
    scrollBd.find(".results_title").hide();
    $(".results_close_wapper").hide();
    ScreenControl.scrollFooter();
}
function getSearchHouse(page){
    var scrollBd = $("#scroll_bd");
    scrollBd.find(".all_list").empty();
    $("#scroll_loading").show();
    var url = $("#strUnitType").val() == 'sale' ? "/map/shou/" : "/map/zu/";
    //alert(url);
    $.ajax({
        url:url,
        type:"post",
        data:{"action":$("#strUnitType").val(),"parkId":parkId,"page":page,"a":$("#third").attr("value"),"p":$("#first").attr("value"),"b":$("#second").attr("value")},
        success:function(data){
            scrollBd.find(".all_list").append(data);
            $("#scroll_loading").hide();
            $(".results_nav a").each(function(){
                if($(this).hasClass("active")){
                    var type = $(this).attr("v");
                    var currentElement = $(".results_list ul[class=" + type + "]");
                    currentElement.siblings().hide();
                    currentElement.show();

                    if (type == "realtor") {
                        $(".results_nav").find(".person").hide();
                        $(".results_nav").find(".realtor").show();
                    }
                    else if (type == "person") {
                        $(".results_nav").find(".realtor").hide();
                        $(".results_nav").find(".person").show();
                    }
                }
            });
        }
    });
}
//显示根据条件检索的覆盖物
function showSearchResult(type,results){
    $(mapObj.getPanes().floatPane).empty();
    var count = results.getCurrentNumPois();
    for(var i = 0; i < count; i++){
        var result = results.getPoi(i);
        addSearchResultIcon(type,result);
    }
}
//显示根据关键字检索的覆盖物
function showSearchResultKW(results){
    var content = "";
    $(mapObj.getPanes().markerPane).empty();
    $(".results_list_b").show();
    $(".results_route").hide();
    $(".results_nav").hide();
    $(".results_list").hide();

    $(".results_list_b .num_c").text(results.getNumPois());
    totalPage = results.getNumPages();
    resetPage(currentPage,totalPage);

    $(".results_list_b ul").empty();
    var count = results.getCurrentNumPois();
    for(var i = 0; i < count; i++){
        var result = results.getPoi(i);
        addSearchResultKW(i, result);
        if(i == 0){
            mapObj.centerAndZoom(result.point, mapObj.getZoom());
            content += "<li data_refer = 'refer_"+i+"' data_x = '"+result.point.lng+"' data_y = '"+result.point.lat+"' class='first'><i class='marker_icon result_"+i+"'></i>" +
                "<div class='bd'><h4><a href='javascript:void(0);'>"+result.title+"</a></h4><p>"+result.address+"</p></div></li>";
        }
        else{
            content += "<li data_refer = 'refer_"+i+"' data_x = '"+result.point.lng+"' data_y = '"+result.point.lat+"'><i class='marker_icon result_"+i+"'></i>" +
                "<div class='bd'><h4><a href='javascript:void(0);'>"+result.title+"</a></h4><p>"+result.address+"</p></div></li>";
        }
    }
    $(".results_list_b ul").append(content);
    ScreenControl.scrollFooter();
}
function resetPage(iPage, iTotalPage){
    var iCutPre = 3;
    var iCutAll = 5;
    var tmp = '';

    if( iPage < iCutPre ){ //靠前
        iCutPre = iPage-1;
        iCutEnd = iCutAll-iCutPre;
    }else if( iTotalPage-iPage<iCutAll-iCutPre ){ //靠后
        iCutEnd = iTotalPage-iPage;
        iCutPre = iCutAll-iCutEnd;
    }else{ //正好
        iCutPre = iCutPre;
        iCutEnd = iCutAll-iCutPre;
    }

    iPage = iPage<1? 1: iPage;
    iPage = iPage>iTotalPage? iTotalPage: iPage;

    tmp = iPage-1<1? 1: iPage-1;
    iTotalPage = parseInt(iTotalPage);
    var html = iTotalPage<=1? '': '<a href="javascript:void('+tmp+');" class="flip prev" page="'+tmp+'">上一页</a>';
    if(html != ''){
        var tmp = iPage-iCutPre;
        if( iPage-iCutPre<3 ){
            for(var i=1; i<iPage; i++ ){
                html += '<a href="javascript:void('+i+');" page="'+i+'">'+i+'</a>';
            }
        }else{
            html += iPage-iCutPre>2? '<a href="javascript:void(1);" page="1">1</a><s>...</s>': '';
            for(var i=tmp; i<iPage; i++ ){
                html += '<a href="javascript:void('+i+');" page="'+i+'">'+i+'</a>';
            }
        }

        html += '<a href="javascript:void('+iPage+');" class="active" page="'+iPage+'">'+iPage+'</a>';

        if( iTotalPage-iCutEnd-iPage<1 ){
            for(var i=parseInt(iPage)+1; i<=iTotalPage; i++ ){
                html += '<a href="javascript:void('+i+');" page="'+i+'">'+i+'</a>';
            }
        }else{
            tmp = parseInt(iPage)+parseInt(iCutEnd);
            for(var i=parseInt(iPage)+1; i<tmp; i++ ){
                html += '<a href="javascript:void('+i+');" page="'+i+'">'+i+'</a>';
            }
            html += '<s>...</s><a href="javascript:void('+iTotalPage+');" page="'+iTotalPage+'">'+iTotalPage+'</a>';
        }

        tmp = parseInt(iPage)+1>iTotalPage? iTotalPage: parseInt(iPage)+1;
        html += iPage==iTotalPage? '': '<a href="javascript:void('+tmp+');" class="flip next"  page="'+tmp+'">下一页</a>';
    }
    $('#result_pager').html(html);
    return true;
}
/**
 * [WIN 窗口布局变化时对应容器的大小以及位置控制]
 * @type {Object}
 */
var ScreenControl = {
    scrollPanel: function() {
        // 设置滚动区域的高度为当前窗口的高度-110
        $('.scroll_panel').height($(window).height() - 104);
        $('#map_wapper').height($(window).height() - 104);
        $('.results_close').width($('.scroll_panel')[0].clientWidth);

    },
    scrollFooter: function() {
        // 如果不存在滚动条，将footer固定到底部
        if ($('.scroll_panel').height() >= document.getElementById('scroll_bd').scrollHeight + 60) {
            $('#scroll_footer').addClass('ps_ft');
        }  else {
            $('#scroll_footer').removeClass('ps_ft');
        }
    },
    tipsWapper: function() {
        if ($(window).width() > 1024) {
            $('.tips_wapper').width($(window).width() - 600);
            $('#map_wapper').width($(window).width() - 600);
            $('.map_layer_Block').addClass('ml');
            $('.mapLogoBlock').show();
        } else {
            $('.tips_wapper').width(400);
            $('#map_wapper').width(400);
            $('.map_layer_Block').removeClass('ml');
            $('.mapLogoBlock').hide();
        }

    },
    trigger: function() {
        mapObj.centerAndZoom(CITY_NAME, default_zoom);
        $(window).resize(function() {
            ScreenControl.scrollPanel();
            ScreenControl.scrollFooter();
            ScreenControl.tipsWapper();
        });
    }
}


