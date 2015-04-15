<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta content="yes" name="apple-mobile-web-app-capable"/><!-- 隐藏safari导航栏以及工具栏 -->
<meta content="yes" name="apple-touch-fullscreen"/>
<meta content="telephone=no" name="format-detection"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>切换账号</title>
<link href="{{'wechat/css/common.css?20140717'|staticUrl}}" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]> 
<script src="js/css3-mediaqueries.js" type="text/javascript"></script>
<![endif]--> 
</head>
<body>

<style>
  body{ background-color:#f0f0f0;}
  .rows{ padding:10px 10px 10px 15px; margin:12px 0; border-top:solid 1px #e2e2e2; border-bottom:solid 1px #e2e2e2; line-height:2em; background-color:#fff;}
  .left{ float:left;}
  .right{ float:right;}
  .font_red{ color:#e85b17;}
  .font15{ font-size:15px;}
  .font17{ font-size:17px;}
  .greenbtn{
    border-radius:5px;
    -moz-border-radius: 5px; 
    -webkit-border-radius: 5px; 
	background: #50c25f; color:#fff; display:block; text-align:center; height:40px; line-height:40px; font-size:17px; border-bottom:solid 1px #349641; cursor:pointer;
  }
  .btnwrap{ padding:15px;}
</style>

<div class="rows clearfix font17"> 
   <p class="left"> 当前账号 </p>
   <p class=" right "> {{accountName}} </p>
</div>

<div class="rows">
  <p> <span class=" font15"> {{ realName }} </span> {{ realTel }} </p>
  <p> {{ companyName }}&nbsp;{% if realType==1 %} {{ agentName }} {% elseif realType==2 %} {{ agentInfo }} {% endif %} </p>
   {% if maxPort is defined and  maxPort %}
  <p> 正在使用<span class="font_red">  {{maxPort}} </span>个{% if portType == '10' %}出售{% elseif portType == '01' %} 出租 {% endif %}端口{% if  is_expired >= 0 %}，剩余 <span class=" font_red"> {{ is_expired }} </span> 天{% if stoptime > 0 %}（ {{ stoptime }} ）{% endif %} {% endif %}</p>
   {% else %}
   <p>还未启用端口</p>
   {% endif %}
</div>

<div class="btnwrap"> 
   <a class="greenbtn" style="text-decoration:none" href="{{ loginUrl }}"> 切换账号 </a>
</div>

</body>
</html>
