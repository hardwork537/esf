function checkWord(theObj){
     var intLen = 140;
     var theVal = theObj.value;
	 if( theVal==theObj.defaultValue ) return ;
     var theLen = theVal.length;
     var theLimitWord = intLen-parseInt(theLen);
     document.getElementById('limitWord').innerHTML = theLimitWord;
     if( theLen>intLen ){
        theVal = theVal.substring(0, intLen);
        theObj.value = theVal;
     }
}

function checkLast(){
	var obj = document.getElementById('theContent');
    if( obj.value=='' || obj.value==obj.defaultValue ){
        alert("请输入反馈内容！");
        return false;
    }
}

function feedbackSuccess(){
	$("#theContent").val("");
    alert("反馈成功");
}