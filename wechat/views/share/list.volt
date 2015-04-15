{% for it in arrUnitList %}
<li>
  <div class="col_middle"> 
	<hgroup>
	<span class="tittle"> {{ it['parkName'] }} </span>
	<span class="green ml3">{% if  it['imgcount'] > 0 %}( {{ it['imgcount'] }}图){% endif %}</span>
    {% if  it['quality'] == 2 %}<span class="sign sign_green green ml5">优质</span>{% endif %}
	{% if  it['quality'] == 3 %}<span class="sign sign_green green ml5">高清</span>{% endif %}
    {% if  it['houseType'] == 20 %}<span class="sign sign_green green ml5"> 新房 </span>{% endif %}
	</hgroup>

	<p>
	<span class="colwords colw1"> {{ UNIT_BEDROOM[it['bedRoom']] }}  {% if it['livingRoom'] is defined and it['livingRoom'] %} {{ UNIT_LIVING_ROOM[it['livingRoom']] }} {% endif %}</span>
	<span class="colwords colw2"> {{ it['bA'] }}平 </span>
	<span class="colwords colw3">
		{% if  unittype == "Rent" %}
			{{ it['price'] }} 元/月
		{% else %}
			{{  it['price']|intValue }}万
		{% endif %}
	</span>
	</p>
	<div class="clicknumwrap">
	    <p class="clicknum">点击（次）：{{ it['day'] }}/{{ it['week'] }}/{{ it['month'] }} （日/周/月）</p> <!--  统计 -->
	</div>
	<p class=" gray_666">
	<span class="colwords colw4"> 发布：{{ it['create'] }} </span>
	<span class="colwords colw5"> {% if it['refreshTime'] %}刷新：{{ it['refreshTime'] }} {% else %}尚未刷新{% endif %} </span>
	</p>
  </div>

  <div class="col_right">  
	<a class="round_share"> </a> 
  </div>
  {% if  unittype == 'Sale' %}
	<a class="mask" onclick="houseDetail('http://{{ city_pinyin_abbr }}.esf.focus.cn/view/{{ it['id'] }}.html?c=n'); return false;" hl="http://{{ city_pinyin_abbr}}.esf.focus.cn/view/{{ it['id'] }}.html?c=n" href="javascript:void(0);"> </a>
  {% else %}
	<a class="mask" onclick="houseDetail('http://{{ city_pinyin_abbr }}.zu.focus.cn/view/{{ it['id'] }}.html?c=n'); return false;" hl="http://{{ city_pinyin_abbr }}.esf.focus.cn/view/{{ it['id'] }}.html?c=n" href="javascript:void(0);"> </a>
  {% endif %}

</li>
{% elsefor %}
<li>
	<div class="nomsg"> 
		<p style="text-align:center;"> <img src="{{'wechat/images/no-msg.png'|staticUrl}}"/> </p>
		<p style="text-align:center;"> 暂无房源 </p>
	</div>
</li>
{% endfor %}


