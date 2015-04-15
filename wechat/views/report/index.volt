<!doctype html>
<html>
<head>
        <meta charset="UTF-8">
        <meta name="Description" content="焦点通-房产经纪人专业助手，专为搜狐焦点经纪人会员精心打造的房产信息管理软件">
        <meta name="Keywords" content="焦点通,焦点通微信版,焦点通手机版，焦点通手机客户端，焦点通APP，焦点通Android版下载，焦点通IOS版下载">
        <title id="til">高清房源效果</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">

        <link href="{{'wechat/css/base.css?20140626'|staticUrl}}" rel="stylesheet" type="text/css" />
        <link href="{{'wechat/css/data.css?20140630'|staticUrl}}" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="{{'wechat/js/Chart.min.js?20140625'|staticUrl}}"></script>
        <!--<script type="text/javascript" src="js/zepto-1.1.3.min.js"></script>-->
		<script type="text/javascript">
			function onBridgeReady(){
			 WeixinJSBridge.call('hideOptionMenu');
			}
			if(typeof WeixinJSBridge == "undefined"){
				if( document.addEventListener ){
					document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
				}else if (document.attachEvent){
					document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
					document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
				}
			}else{
				onBridgeReady();
			}
		</script>
    </head>
    <body>
        <div class="content">
		{% if nopower %}
			<p style="padding:5px;text-align:center;">嗷嗷嗷，此功能暂时只对出售端口类型的经纪人开放哦</p>
		{% else %}
            <b class="tit">高清房源占比</b>
			{% if showRefreshPie == 1 %}
            <div class="charts charts1">
                <canvas id="myChart1" class="charts_l" width="30%" height="30%" jsdata='[{value: {{ unitInfo.good }},color:"#50c25f"},{value : {{ unitInfo.nogood }},color : "#fdd400"}]'></canvas>
                <div class="charts_r">
                    <p class="p0 p1"><i class="i0 i1"></i> {{ unitInfo.good }}% 高清房源</p>
                    <p class="p0 p2"><i class="i0 i2"></i> {{ unitInfo.nogood }}% 非高清房源</p>
                </div>
            </div>
            <div class="description">
                <b>{{ unitInfo.msg }}</b>
            </div>
			{% else %}
				<p style="padding:5px;text-align:center;font-size:12px;">没有房源信息</p>
			{% endif %}
            <div class="blank"></div>

            <b class="tit">房源平均点击量</b>
			{% if showRefreshPie == 1 %}
            <div class="charts charts2">
                <p class="charts_p">
                    <span class="sp0 sp1"><i class="i0 i1"></i> 高清房源</span>
                    <span class="sp0 sp2"><i class="i0 i2"></i> 非高清房源</span>
                </p>
                <canvas id="myChart2" class="myChart2" width="95%" height="60%" jsdata='{labels : [{{ unitClickInfo.day }}], datasets : [{fillColor : "#50c25f",data : [{{ unitClickInfo.good }}]},{fillColor : "#fdd400",data : [{{ unitClickInfo.nogood }}]}]}'></canvas>
            </div>
            <div class="description">
                <b>{{ unitClickInfo.msg }}</b>
            </div>
			{% else %}
				<p style="padding:5px;text-align:center;font-size:12px;">没有房源信息</p>
			{% endif %}
            <div class="blank"></div>

            <b class="tit">房源刷新量</b>
			{% if showRefreshPie == 1 %}
            <div class="charts charts2">
                <p class="charts_p">
                    <span class="sp0 sp1"><i class="i0 i1"></i> 高清房源</span>
                    <span class="sp0 sp2"><i class="i0 i2"></i> 非高清房源</span>
                </p>
                <canvas id="myChart3" class="myChart2" width="95%" height="60%" jsdata='{labels : [{{ unitRefreshInfo.day }}], datasets : [{fillColor : "#50c25f",data : [{{ unitRefreshInfo.good }}]},{fillColor : "#fdd400",data : [{{ unitRefreshInfo.nogood }}]}]}'></canvas>
            </div>
            <div class="description">
                <b>{{ unitRefreshInfo.msg }}</b>
            </div>
			{% else %}
				<p style="padding:5px;text-align:center;font-size:12px;">没有房源信息</p>
			{% endif %}
		{% endif %}
        </div>
        {% if showRefreshPie == 1 %}<script type="text/javascript" src="{{'wechat/js/data.js?20140630'|staticUrl}}"></script>{% endif %}
    </body>
</html>