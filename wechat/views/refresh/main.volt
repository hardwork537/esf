<ul class="toptab3 clearfix"> 
  <li> <p class=" green"> {{ completeRef }}</p> <p> 已刷新 </p> <i class="liner"> </i> </li>
  <li> <p class=" green">{{ timeRef }} </p> <p> 正在定时 </p> <i class="liner"> </i> </li>
  <li> <p class=" green">{{ maxRef }}</p> <p> 还可刷新 </p>  </li>
</ul>
<div class="tabshow"> 
  <!--已发布  =============start -->
  <ul class="houselist houselist2 clearfix">
	{% include "refresh/list.volt" %}
  </ul>
  <!--已发布  =============end -->
</div>
<script src="{{'wechat/js/refresh.js?20140914'|staticUrl}}" type="text/javascript"></script>
<script type="text/javascript">
var imgurl = "{{'wechat/images/loading.gif'|staticUrl}}";
</script>
