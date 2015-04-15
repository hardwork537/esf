//画公交检索路线
var polyline;
function drawTransitLine(plan){
    // 绘制步行线路
    $(mapObj.getPanes().markerPane).empty();
    var overlays = mapObj.getOverlays();
    $.each(overlays,function(index,overlay){
        if(!(overlay instanceof ComplexCustomOverlay)){
            mapObj.removeOverlay(overlay);
        };
    });
    for (var i = 0; i < plan.getNumRoutes(); i ++){
        var walk = plan.getRoute(i);
        if (walk.getDistance(false) > 0){
            polyline = new BMap.Polyline(walk.getPath(), {strokeColor: "green"});
            mapObj.addOverlay(polyline);
        }
    }
    // 绘制公交线路
    for (i = 0; i < plan.getNumLines(); i ++){
        var line = plan.getLine(i);
        polyline = new BMap.Polyline(line.getPath());
        mapObj.addOverlay(polyline);
    }
}
//画当前查看的路线
function showCurrentDrivingPoint(path){
    var overlays = mapObj.getOverlays();
    $.each(overlays,function(index, overlay){
        if(overlay instanceof BMap.Polyline && overlay.getStrokeColor() == 'red'){
            mapObj.removeOverlay(overlay);
        };
    });
    var polyline = new BMap.Polyline(path,{strokeColor: "red"});
    mapObj.addOverlay(polyline);
    mapObj.setViewport(path);
}
function addCircleOverlay(data){
    var element = "<div class='amp_area'><div class='empty_pt'></div><h5>"+data.name+"</h5><p>"+data.total+"套</p><p class='area_id' style='display: none'>"+data.id+"</p></div>";
    var myCompOverlay = new ComplexCustomOverlay(element, new BMap.Point(data.x,data.y));
    mapObj.addOverlay(myCompOverlay);
}

function addDetailOverlay(data){
    var element2 = "<div class='amp_house' data-id='"+data.id+"'><div class='container'><div class='h_num'><em class='num_c'>"+data.c+"</em>套</div><span class='h_name'>"+data.name+"</span><span class='h_price'>"+data.salePrice+"元/平</span><div style='display: none'><span class='address'>"+data.address+"</span><span class='h_id'>"+data.id+"</span><span class='h_lng'>"+data.BdX+"</span><span class='h_lat'>"+data.BdY+"</span></div></div><div class='icon'></div></div>";
    var myCompOverlay = new ComplexCustomOverlay(element2, new BMap.Point(data.BdX,data.BdY));
    mapObj.addOverlay(myCompOverlay);
}
//根据条件检索的icon
function addSearchResultIcon(type,data){
    var className = "amp_icon " + type;
    var element3 = "<i class='"+className+"' tag='place-search'></i>";
    var myCompOverlay = new ComplexCustomOverlay(element3, data.point);
    mapObj.addOverlay(myCompOverlay);
}
function addLocation(point){
    $(mapObj.getPanes().markerPane).empty();
    var element4 = "<i class='marker_icon_center'></i>";
    var myCompOverlay = new ComplexCustomOverlay(element4, point);
    mapObj.addOverlay(myCompOverlay);
}
//导航起点
function addStartPoint(point){
    var element5 = "<i class='start_point'></i>";
    var myCompOverlay = new ComplexCustomOverlay(element5, point);
    mapObj.addOverlay(myCompOverlay);
}
//检索路径起点
function addStartLocation(point){
    var element6 = "<i class='marker_icon start_location'></i>";
    var myCompOverlay = new ComplexCustomOverlay(element6, point);
    mapObj.addOverlay(myCompOverlay);
}
//根据关键字检索的icon
function addSearchResultKW(index,data){
    var currentIndex = "refer_" + index;
    var className = "marker_icon_wapper result_" + index;
    var element5 = "<div class='"+className+"' data_refer = '"+currentIndex+"'><i></i></div>";
    var myCompOverlay = new ComplexCustomOverlay(element5, data.point);
    mapObj.addOverlay(myCompOverlay);
}
// 复杂的自定义覆盖物
function ComplexCustomOverlay(element,point){
    this._element = $(element)[0];
    this._point = point;
}
ComplexCustomOverlay.prototype = new BMap.Overlay();
ComplexCustomOverlay.prototype.initialize = function(map){
    this._map = map;
    if(this._element.className.indexOf("amp_icon") > -1){
        mapObj.getPanes().floatPane.appendChild(this._element);
    }
    else if(this._element.className.indexOf("marker_icon") > -1){
        mapObj.getPanes().markerPane.appendChild(this._element);
    }
    else{
        mapObj.getPanes().labelPane.appendChild(this._element);
    }
    return this._element;
}
ComplexCustomOverlay.prototype.draw = function(){
    var map = this._map;
    var pixel = map.pointToOverlayPixel(this._point);
    if(this._element.className.indexOf("amp_area") > -1){
        this._element.style.left = pixel.x - 41 + "px";
        this._element.style.top  = pixel.y - 41 + "px";
    }
    else if(this._element.className.indexOf("amp_house") > -1){
        this._element.style.left = pixel.x - 32 + "px";
        this._element.style.top  = pixel.y - 42 + "px";
    }
    else if(this._element.className.indexOf("amp_icon") > -1){
        this._element.style.left = pixel.x - 13 + "px";
        this._element.style.top  = pixel.y - 10 + "px";
    }
    else if(this._element.className.indexOf("marker_icon") > -1){
        this._element.style.left = pixel.x - 13 + "px";
        this._element.style.top  = pixel.y - 34 + "px";
    }
    else if(this._element.className.indexOf("start_point") > -1){
        this._element.style.left = pixel.x - 15 + "px";
        this._element.style.top  = pixel.y - 42 + "px";
    }
}