$(document).ready(function() {

	//搜索关键词
	$('#searchKeyBegin').click(function (){ 
		var obj = document.getElementById('searchKey');
		if( trim(obj.value)!=trim(obj.defaultValue) ) setKey('search');
	});

	//设置价格
	$('#priceBtn').click(function (){ setPrice(); });
	//设置面积
	$('#areaBtn').click(function (){ setArea(); });

});


//经常被调用的函数
function trim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

function getNowUrl(){
	if(document.getElementById('url')!=null&&document.getElementById('url')!=undefined){
		return document.getElementById('url').value;
	}
	return window.location.href;
}
//param clear:清除,search:搜索
function setKey(action){
	var new_url = getNowUrl();
	var theKey = $('#searchKey').attr('theKey')? $('#searchKey').attr('theKey'): 'k';
	var kValue = trim(document.getElementById('searchKey').value);
	new_url = String(action)=='clear'? changeParam(new_url, theKey, ''): changeParam(new_url, theKey, kValue);
	window.location.href = new_url;
}

function changeParam(newUrl, key, val){
	var val = trim(val);
	if( newUrl.indexOf(key+'=')<0 ){
		if( val ) var newUrl = newUrl.indexOf('=')<0? newUrl+'?'+key+'='+val: newUrl+'&'+key+'='+val;
	}else{
	    var arrParam1 = newUrl.split("?");
	    var newUrl = arrParam1[0];
	    if( arrParam1[1]!=undefined ){
	        var arrParam2 = arrParam1[1].split("&");
	        var arrParam3 = new Array();
	        newUrl += '?';
	        var intArrLen = arrParam2.length;
	        for(var i=0; i<intArrLen; i++){
	            arrParam3 = arrParam2[i].split("=");
	            if( arrParam3[0]!=key ) newUrl += arrParam2[i]+'&';
	        }
	        if(val) newUrl += key+'='+val;
	        newUrl = newUrl.replace(/[&\?]?$/g, "");
	    }
	}
	return newUrl;
}

function setPrice(){
	var min = document.getElementById('priceMin').value;
	var max = document.getElementById('priceMax').value;
	if(isNumber(min)){ alert('请输入正确的价格开始范围！'); return false; }
	if(isNumber(max)){ alert('请输入正确的价格结束范围！'); return false; }
	if( parseFloat(min)>parseFloat(max) ){ alert('价格范围应由小到大！'); return false; }
	var priceVal = min+'-'+max;
	var new_url = getNowUrl();
	if( /^(.+)(j[0-9]+(-[0-9]+){0,1})(.*)$/i.test(new_url) ){
		new_url = new_url.replace(/^(.+)(j[0-9]+(-[0-9]+){0,1})(.*)$/i, '$1j'+priceVal+'$4');
	}else{
		new_url = new_url.replace(/^([^?]+)(\??[^?]*)$/i, '$1j'+priceVal+'$2');
	}
	window.location.href = new_url;
}
function setArea(){
	var min = document.getElementById('areaMin').value;
	var max = document.getElementById('areaMax').value;
	if(isNumber(min)){ alert('请输入正确的面积开始范围！'); return false; }
	if(isNumber(max)){ alert('请输入正确的面积结束范围！'); return false; }
	if( parseFloat(min)>parseFloat(max) ){ alert('面积范围应由小到大！'); return false; }
	var areaVal = min+'-'+max;
	var new_url = getNowUrl();
	if( /^(.+)(m[0-9]+(-[0-9]+){0,1})(.*)$/i.test(new_url) ){
		new_url = new_url.replace(/^(.+)(m[0-9]+(-[0-9]+){0,1})(.*)$/i, '$1m'+areaVal+'$4');
	}else{
		new_url = new_url.replace(/^([^?]+)(\??[^?]*)$/i, '$1m'+areaVal+'$2');
	}
	window.location.href = new_url;
}
