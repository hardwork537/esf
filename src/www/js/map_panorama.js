$(function(){
    var panorama = new BMap.Panorama('panorama');

    var panoramaService = new BMap.PanoramaService();
    var point = new BMap.Point(lng, lat);
    panoramaService.getPanoramaByLocation(point, function(data){
        if(data == null){
            $("#panorama").html("抱歉！暂无街景，请关闭页面。")
        }
        else{
            panorama.setPosition(point);
        }
    });
});