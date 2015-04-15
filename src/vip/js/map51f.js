function map51f(o){
	this.o = o;
};
map51f.prototype.initialize = function() {
	var mapOptions = new MMapOptions();//构建地图辅助类
	mapOptions.zoom = 13;//要加载的地图的缩放级别
	mapOptions.center = new MLngLat(116.397428,39.90923);//要加载的地图的中心点经纬度坐标
	mapOptions.toolbar = DEFAULT;//设置地图初始化工具条
	mapOptions.toolbarPos = new MPoint(5, 5); //设置工具条在地图上的显示位置
	mapOptions.overviewMap = SHOW; //设置鹰眼地图的状态，SHOW:显示，HIDE:隐藏（默认）
	mapOptions.scale = SHOW; //设置地图初始化比例尺状态，SHOW:显示（默认），HIDE:隐藏。
	mapOptions.returnCoordType = COORD_TYPE_OFFSET;//返回数字坐标
	mapOptions.zoomBox = true;//鼠标滚轮缩放和双击放大时是否有红框动画效果。
	mapObj = new MMap(this.o, mapOptions); //地图初始化
	mapObj.setKeyboardEnabled(false);
};
map51f.prototype.setKeyboardEnabled = function(o) {
	mapObj.setKeyboardEnabled(o);
};
map51f.prototype.setCenter = function(x, y) {
	mapObj.setCenter(new MLngLat(x, y));
};
map51f.prototype.setCenterByCity = function(city) {
	var c = (city == "" || city == undefined) ? "010" : city;
	var maptools = new MMapTools(mapObj);
	maptools.setCenterByCity(c); 
};
map51f.prototype.getCenter = function() {
	return mapObj.getCenter();
};
map51f.prototype.getZoomLevel = function() {
	mapObj.getZoomLevel();
};
map51f.prototype.setZoomLevel = function(a) {
	var z = (a == "" || a == undefined) ? 13 : a;
	mapObj.setZoomLevel(z);//设置地图zoom级别为17
};
map51f.prototype.getSearch = function(data) {
	var condition = (data == "" || data == undefined) ? {} : data;
	var keyword = (condition.keyword == "" || condition.keyword == undefined) ? "" : condition.keyword;
	var city = (condition.city == "" || condition.city == undefined) ? "北京" : condition.city;
	var type = (condition.type == "" || condition.type == undefined) ? "" : condition.type;
	var page = (condition.page == "" || condition.page == undefined) ? 1 : condition.page;
	var callBack = (condition.callBack == "" || condition.callBack == undefined) ? callback : condition.callBack;
	var maxPerPage = (condition.maxPerPage == "" || condition.maxPerPage == undefined) ? 10 : condition.maxPerPage;
	var MSearch = new MPoiSearch(); 
	var opt = new MPoiSearchOptions();
	opt.recordsPerPage = maxPerPage;//每页返回数据量，默认为10 
	opt.pageNum = page;//当前页数。 
	opt.dataType = "";//数据类别，该处为分词查询，只需要相关行业关键字即可 
	opt.dataSources = DS_BASE_ENPOI;//数据源，基础+企业地标数据库（默认）   
	MSearch.setCallbackFunction(callBack);
	MSearch.poiSearchByKeywords(keyword, city, opt);
};
map51f.prototype.getSearchAround = function(condition) {
	var x = (condition.x == "" || condition.x == undefined) ? "" : condition.x;
	var y = (condition.y == "" || condition.y == undefined) ? "" : condition.y;
	var keyword = (condition.keyword == "" || condition.keyword == undefined) ? "" : condition.keyword;
	var city = (condition.city == "" || condition.city == undefined) ? "北京" : condition.city;
	var type = (condition.type == "" || condition.type == undefined) ? "" : condition.type;
	var callBack = (condition.callBack == "" || condition.callBack == undefined) ? callback : condition.callBack;
	var page = (condition.page == "" || condition.page == undefined) ? 1 : condition.page;
	var maxPerPage = (condition.maxPerPage == "" || condition.maxPerPage == undefined) ? 10 : condition.maxPerPage;
	var range = (condition.range == "" || condition.range == undefined) ? 1000 : condition.range;
	var poiXY = new MLngLat(x, y);//中心点坐标
	var MSearch = new MPoiSearch();
    var opt = new MPoiSearchOptions();
    opt.recordsPerPage = maxPerPage;//每页返回数据量，默认为10
    opt.pageNum = page;//当前页数
    opt.dataType = type;//数据类别，该处为分词查询，只需要相关行业关键字即可 
    opt.dataSources = DS_BASE_ENPOI;//数据源，基础+企业地标数据库（默认） 
    opt.range = range;//查询范围，单位为米，默认值为3000 
    opt.naviFlag = 0;//周边查询返回结果是否按导航距离排序,0，不按导航距离排序（默认）1，按导航距离排序。 
    MSearch.setCallbackFunction(callBack); 
    MSearch.poiSearchByCenPoi(poiXY, keyword, city, opt);
};
map51f.prototype.getBounds = function() {
	return mapObj.getLngLatBounds();//获取地图矩形视野西南东北角经纬度坐标
};
map51f.prototype.setRegion2String = function(data) {
	return data.northEast.lngX + "," + data.northEast.latY + "," + data.southWest.lngX + "," + data.southWest.latY;
};
map51f.prototype.getSearchRegion = function(condition) {
	var keyword = (condition.keyword == "" || condition.keyword == undefined) ? "" : condition.keyword;
	//MOverlay.TYPE_POLYGON，多边形；MOverlay.TYPE_CIRCLE，圆形；MOverlay.TYPE_RECTANGLE，矩形
	var type = (condition.type == "" || condition.type == undefined) ? MOverlay.TYPE_RECTANGLE : condition.type;
	var maxPerPage = (condition.maxPerPage == "" || condition.maxPerPage == undefined) ? 99999 : condition.maxPerPage;
	var page = (condition.page == "" || condition.page == undefined) ? 1 : condition.page;
	var region = (condition.region == "" || condition.region == undefined) ? this.setRegion2String(this.getBounds()) : condition.region;
	var callBack = (condition.callBack == "" || condition.callBack == undefined) ? callback : condition.callBack;
	var arr1 = region.split(","); 
    var regionArr = new Array(); 
    for(var i = 0 ;i<=arr1.length-2;){ 
        var mll =new MLngLat(arr1[i],arr1[i+1]); 
        regionArr.push(mll); 
        i=i+2; 
    } 
	var mlls = new MLngLats(regionArr);
	var MSearch = new MPoiSearch();
	var opt= new MPoiSearchOptions();
	opt.recordsPerPage = maxPerPage;//每页返回数据量，默认为10 
    opt.pageNum = page;//当前页数。 
    opt.dataType = "";//数据类别，该处为分词查询，只需要相关行业关键字即可 
    opt.dataSources = DS_BASE_ENPOI;//数据源，基础+企业地标数据库（默认）
	MSearch.setCallbackFunction(callBack);
	MSearch.poiSearchByRegion(type, mlls, keyword, opt);
};
map51f.prototype.setPoint = function(x, y, z) {
	var marker = new MMarker(new MLngLat(x, y));
	marker.id = "marker"+z;
	mapObj.addOverlay(marker);
};
map51f.prototype.setHtmlPoint = function(x, y, id, html, isCenter) {
	var id = (id == "" || id == undefined) ? 0 : id;
	var html = (html == "" || html == undefined) ? "" : html;
	var isCenter = (isCenter == "" || isCenter == undefined) ? false : isCenter;
	var opt = new MBoxOptions();   
	opt.content = html; 
	opt.boxAlign = BOTTOM_CENTER; 
	var box = new MBox(new MLngLat(x, y), opt); 
	box.id = "box"+id; 
	mapObj.addOverlay(box, isCenter);
};
map51f.prototype.addEventListener = function(action, clickfunction) {
	//点击:MOUSE_CLICK 
    mapObj.addEventListener(mapObj, action, clickfunction);//鼠标事件监听
};
map51f.prototype.setCtrlPanelState = function(a, b) {
	//鹰眼:OVERVIEW_CTRL(SHOW,HIDE,MINIMIZE) 工具条:TOOLBAR_CTRL(SHOW,HIDE) 比例尺:SCALE_CTRL(SHOW,HIDE)
	mapObj.setCtrlPanelState(a, b);
};
map51f.prototype.setCtrlPanel = function(a) {
	var toolbarOpt = {};//构建工具条对象
    toolbarOpt.toolbarPos = new MPoint(5,5);//工具条位置 
    toolbarOpt.toolbar = a;//默认:DEFAULT 小型:SMALL 迷你:MINI
    toolbarOpt.toolbarUrl = "";//工具条URL，可以自定义工具条 
    mapObj.loadCtrlPanel(TOOLBAR_CTRL,toolbarOpt);//加载鱼骨条
};
map51f.prototype.setZoomIn = function() {
	mapObj.zoomIn();
};
map51f.prototype.setZoomOut = function() {
	mapObj.zoomOut();
};
map51f.prototype.clearMap = function() {
	mapObj.clearMap();
};
map51f.prototype.panTo = function(data) {
	var d = (data == "" || data == undefined) ? {} : data;
	var x = (d.x == "" || d.x == undefined) ? "116.397428" : d.x;
	var y = (d.y == "" || d.y == undefined) ? "39.90923" : d.y;
	mapObj.panTo(new MLngLat(x, y));
};
map51f.prototype.getBusSearch = function(data) {
	var d = (data == "" || data ==undefined) ? {} : data;
	if ( d.start_x == "" || d.start_x == undefined || d.start_y == "" || d.start_y == undefined ) { alert("起点坐标不能为空"); return;}
	if ( d.end_x == "" || d.end_x == undefined || d.end_y == "" || d.end_y == undefined ) { alert("终点坐标不能为空"); return;}
	var city = (d.city == "" || d.city == undefined) ? "北京" : d.city;
	var callBack = (d.callBack == "" || d.callBack == undefined) ? callback : d.callBack;
	var perpage = (d.maxPerPage == "" || d.maxPerPage == undefined) ? 1 : d.maxPerPage;
	var page = (d.page == "" || d.page == undefined) ? 1 : d.page;
	var startXY= new MLngLat(d.start_x, d.start_y); 
	var endXY = new MLngLat(d.end_x, d.end_y); 
	var MSearch = new MBusSearch(); 
	var opt = new MBusSearchOptions(); 
	//var MSearch = new MRouteSearch();
    //var opt =new MRouteSearchOptions();
	opt.per = 150;//抽吸函数，表示在地图上画公交路径的关键点的个数，默认为150 
	opt.routeType = 0;//路径计算规则，0，最快捷模式，尽可能乘坐轨道交通和快速公交线路 
	MSearch.setCallbackFunction(callBack); 
	MSearch.busSearchByTwoPoi(startXY, endXY, city, opt);
	//MSearch.routeSearchByTwoPoi(startXY, endXY, opt); 
};
map51f.prototype.getDriveSearch = function(data) {
	var d = (data == "" || data ==undefined) ? {} : data;
	if ( d.start_x == "" || d.start_x == undefined || d.start_y == "" || d.start_y == undefined ) { alert("起点坐标不能为空"); return;}
	if ( d.end_x == "" || d.end_x == undefined || d.end_y == "" || d.end_y == undefined ) { alert("终点坐标不能为空"); return;}
	var city = (d.city == "" || d.city == undefined) ? "北京" : d.city;
	var callBack = (d.callBack == "" || d.callBack == undefined) ? callback : d.callBack;
	var perpage = (d.maxPerPage == "" || d.maxPerPage == undefined) ? 1 : d.maxPerPage;
	var page = (d.page == "" || d.page == undefined) ? 1 : d.page;
	var startXY= new MLngLat(d.start_x, d.start_y); 
	var endXY = new MLngLat(d.end_x, d.end_y);
	var MSearch = new MRouteSearch();
    var opt =new MRouteSearchOptions();
	opt.per = 150;//抽吸函数，表示在地图上画公交路径的关键点的个数，默认为150 
	opt.routeType = 0;//路径计算规则，0，最快捷模式，尽可能乘坐轨道交通和快速公交线路 
	MSearch.setCallbackFunction(callBack); 
	MSearch.routeSearchByTwoPoi(startXY, endXY, opt); 
};
//JS按照中文长度截取字符串，临时放置于此，有需求再做调整
String.prototype.truncate = function (n) {
	var cut = 0;
	var str = '';
	if ( this.length < n ) return this;
	for ( var i=0; i<this.length; i++ ) {
		if ( this.charCodeAt(i) > 255 ) {
			str += this.substr(i,1);
			cut += 2;
		} else {
			str += this.substr(i,1);
			cut += 1;
		}
		if (cut >= n*2) {
			return str+'...';
		}
	}
	return str;
}