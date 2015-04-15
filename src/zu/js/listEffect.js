//选中效果 这里将在循环中的if判断的压力转移到了客户端
$(document).ready(function($) {
	//异步加载图片
	$(".asyn_loading").scrollLoading();
	//下拉跳转
	$(".item-drop-list span a").click(function(){
	    window.location.href = $(this).attr('href');
	});
});

function selectBlue(obj){
    obj.children('a').each(function (){
        var thisHtml = $(this).html();
        if( String(thisHtml) == String(obj.attr('sel')) ){
            var thisClass = String(thisHtml)=='不限'? 'select': 'curr';
            $(this).addClass(thisClass);
        }
    });
}

$('#type').ready(function() {				selectBlue( $('#type')				 ); });
$('#selectWalkTime').ready(function() {		selectBlue( $('#selectWalkTime')	 ); });
$('#selectSchoolType').ready(function() {	selectBlue( $('#selectSchoolType')	 ); });
$('#selectSchoolSearch').ready(function() {	selectBlue( $('#selectSchoolSearch') ); });

function selectBlue1(obj){
    obj.children('a').each(function (){
        var thisHtml = $(this).html();
        if( String(thisHtml) == String(obj.attr('sel')) ){
            var thisClass = 'select';
            $(this).addClass(thisClass);
        }
    });
}

$('#selectHotArea').ready(function() { selectBlue1( $('#selectHotArea') ); });
$('#selectPrice').ready(function() {   selectBlue1( $('#selectPrice')   ); });
$('#selectBedroom').ready(function() { selectBlue1( $('#selectBedroom') ); });

function selectBlue2(obj){
    var tabValue = obj.attr('sel');
    obj.children('li').each(function (){
        if( String(tabValue)==String($(this).attr('data')) ) $(this).addClass('curr');
    });
}

$('#tab').ready(function()	{	selectBlue2( $('#tab') ); });
$('#tab1').ready(function() {	selectBlue2( $('#tab1')); });
