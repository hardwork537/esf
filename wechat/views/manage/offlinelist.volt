{% for it in arrUnitList %}
<li {% if  it['verification'] == -1  or it['imgcount'] <= 0 %} class="warn" {% endif %} >
  <div class="col_middle"> 
	<hgroup>
	    <span class="tittle"> {{ it['parkName'] }} </span>
    	<span class="green ml3">{% if  it['imgcount'] > 0 %}( {{ it['imgcount'] }}图){% endif %}</span>
        {% if  it['quality'] == 2 %}<span class="sign sign_green green ml5">优质</span>{% endif %}
        {% if  it['quality'] == 3 %}<span class="sign sign_green green ml5">高清</span>{% endif %}
        {% if it['houseType'] == 20 %}<span class="sign sign_green green ml5"> 新房 </span>{% endif %}
	    {% if it['verification'] == -1 %}<span class="sign_orange sign ml5"> 违规 </span>{% endif %}
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
	<p class=" gray_666">
	 <span class="colwords colw4"> 发布：{{ it['create'] }} </span>
	 <span class="colwords colw5"> {% if it['xiajia'] %} 下架：{{ it['xiajia'] }}{% endif %} </span> </p>
  </div>
  <div class="col_left"> <input id="CheckBox_{{ it['id'] }}" type="checkbox" class="CheckBoxClass" value="{{ it['id'] }}"/> <label class="radio"  unitid="{{ it['id'] }}" for="CheckBox_{{ it['id'] }}"> <i class="icon_ok"> </i> </label> </div>
  <div class="col_right">{% if it['xiajia'] %}还剩<span class="orange"> {{ it['xiajiatime'] }}</span> 天 {% endif %}</div>
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



