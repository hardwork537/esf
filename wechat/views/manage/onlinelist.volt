{% for it in arrUnitList %}
	<li  class="warn2" >
	  <div class="col_middle">
		<hgroup>
		<span class="tittle"> {{ it['parkName'] }} </span>
		<span class="green ml3">{% if  it['imgcount'] > 0 %}( {{ it['imgcount'] }}图){% endif %}</span>
        {% if  it['quality'] == 2 %}<span class="sign sign_green green ml5">优质</span>{% endif %}
        {% if  it['quality'] == 3 %}<span class="sign sign_green green ml5">高清</span>{% endif %}
        {% if it['houseType'] == 20 %}<span class="sign sign_green green ml5"> 新房 </span>{% endif %}
        {% if  it['verification'] == -1 %}<span class="sign_orange sign ml5"> 违规 </span>{% endif %}
        </hgroup>

		<p>
		<span class="colwords colw1"> {{ UNIT_BEDROOM[it['bedRoom']] }} {% if it['livingRoom'] is defined and it['livingRoom'] %} {{ UNIT_LIVING_ROOM[it['livingRoom']] }} {% endif %}</span>
		<span class="colwords colw2"> {{ it['bA'] }} 平 </span>
		<span class="colwords colw3">
			{% if  unittype == "rent" %}
            	{{ it['price'] }} 元/月
            {% else %}
            	{{  it['price']|intValue }}万
            {% endif %}
		</span>
		</p>

		{% if showClick %}
		<div class="clicknumwrap"> <p class="clicknum"> 点击（次）：{{ it['day'] }}/{{ it['week'] }}/{{ it['month'] }}（日/周/月）   </p>  </div>
		{% endif %}

		<p class=" gray_666">
		    <span class="colwords colw4"> 发布：{{ it['create'] }} </span>
            <span class="colwords colw5"> {% if it['refreshTime'] %}刷新：{{ it['refreshTime'] }} {% else %}尚未刷新{% endif %} </span>
		</p>
	  </div>

	  <div class="col_left"> <input id="CheckBox_{{ it['id'] }} " type="checkbox" class="CheckBoxClass" value="{{ it['id'] }}"  weigui='0' /> <label class="radio"  for="CheckBox_{{ it['id'] }}" unitid="{{ it['id'] }}"> <i class="icon_ok"> </i> </label> </div>
	  <div class="col_right"> {% if  it['timing'] == 1 %} 正在定时 {% endif %}
	  <p>
		{% if  it['lastflush'] == intTodayZero %}
			<span class="green" id="sp_flush_day_{{ it['id'] }}"> 今日已刷 </span>
		{% else %}
			<span class="orange" id="sp_flush_day_{{ it['id'] }}"> 今日未刷</span>
		{% endif %}
	  </p>

	  </div>
	  <a class="mask"> </a>
	</li>
{% elsefor %}
<li class="noborder">
	<div class="nomsg">
		<p style="text-align:center;"> <img src="{{'wechat/images/no-msg.png'|staticUrl}}"/> </p>
		<p style="text-align:center;"> 暂无房源 </p>
	</div>
</li>
{% endfor %}
<input type="hidden" value="{{reStr}}" name="HideStr" id="HideStr"/>



