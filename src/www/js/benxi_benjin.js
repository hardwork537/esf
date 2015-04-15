function exc_zuhe(fmobj,v){
	//var fmobj=document.calc1;
	if (fmobj.name=="calc1"){
		if (v==3){
			document.getElementById('calc1_zuhe').style.display='block';
			document.getElementById('jsff').style.display='none';
			fmobj.jisuan_radio[1].checked = true;
			exc_js(fmobj,2);
		}else{
			document.getElementById('calc1_zuhe').style.display='none';
			document.getElementById('jsff').style.display='block';	
		}
	}else{
		if (v==3){
			document.getElementById('calc2_zuhe').style.display='block';
			document.getElementById('jsff').style.display='none';
			fmobj.jisuan_radio[1].checked = true;
			exc_js(fmobj,2);
		}else{
			document.getElementById('calc2_zuhe').style.display='none';
			document.getElementById('jsff').style.display='block';
		}
	}
}
function exc_js(fmobj,v){
	//var fmobj=document.calc1;
	if (fmobj.name=="calc1"){
		if (v==1){
			document.getElementById('calc1_js_div1').style.display='block';
			document.getElementById('calc1_js_div2').style.display='none';
			document.getElementById('calc1_zuhe').style.display='none';
			fmobj.type.value=1;
		}else{
			document.getElementById('calc1_js_div1').style.display='none';
			document.getElementById('calc1_js_div2').style.display='block';
		}
	}else{
		if (v==1){
			document.getElementById('calc2_js_div1').style.display='block';
			document.getElementById('calc2_js_div2').style.display='none';
			document.getElementById('calc2_zuhe').style.display='none';
			fmobj.type.value=1;
		}else{
			document.getElementById('calc2_js_div1').style.display='none';
			document.getElementById('calc2_js_div2').style.display='block';
		}
	}
}
function formReset(fmobj){
	//var fmobj=document.calc1;
	if (fmobj.name=="calc1"){
		document.getElementById('calc1_js_div1').style.display='block';
		document.getElementById('calc1_js_div2').style.display='none';
		document.getElementById('calc1_zuhe').style.display='none';
		document.getElementById('calc1_benjin').style.display='none';
	}else{
		document.getElementById('calc2_js_div1').style.display='block';
		document.getElementById('calc2_js_div2').style.display='none';
		document.getElementById('calc2_zuhe').style.display='none';
		document.getElementById('calc2_benxi').style.display='none';
	}
}

//显示右边的比较div
function showRightDiv(fmobj){
	if (ext_total(fmobj)==false){return;}
	//alert(document.calc1.month_money2.value);
	var a=window.open('','calc_win','status=yes,scrollbars=yes,resizable=yes,width=550,height=500,left=0,top=0')//790*520
	if (fmobj.name=="calc1"){
		document.calc1.target = "calc_win";
		document.calc1.submit();
	}else{
		document.calc2.target = "calc_win";
		document.calc2.submit();
	}

}


//验证是否为数字
function reg_Num(str){
	if (str.length==0){return false;}
	var Letters = "1234567890.";

	for (i=0;i<str.length;i++){
		var CheckChar = str.charAt(i);
		if (Letters.indexOf(CheckChar) == -1){return false;}
	}
	return true;
}

lilv_array=new Array;          
//2004年之前的旧利率
lilv_array[1]=new Array;
lilv_array[1][1]=new Array;
lilv_array[1][2]=new Array;
lilv_array[1][1][5]=0.0477;//商贷 1～5年 4.77%
lilv_array[1][1][10]=0.0504;//商贷 5-30年 5.04%
lilv_array[1][2][5]=0.0360;//公积金 1～5年 3.60%
lilv_array[1][2][10]=0.0405;//公积金 5-30年 4.05%

//2005年	1月的新利率
lilv_array[2]=new Array;
lilv_array[2][1]=new Array;
lilv_array[2][2]=new Array;
lilv_array[2][1][5]=0.0495;//商贷 1～5年 4.95%
lilv_array[2][1][10]=0.0531;//商贷 5-30年 5.31%
lilv_array[2][2][5]=0.0378;//公积金 1～5年 3.78%
lilv_array[2][2][10]=0.0423;//公积金 5-30年 4.23%

//2006年	1月的新利率下限
lilv_array[3]=new Array;
lilv_array[3][1]=new Array;
lilv_array[3][2]=new Array;
lilv_array[3][1][5]=0.0527;//商贷 1～5年 5.27%
lilv_array[3][1][10]=0.0551;//商贷 5-30年 5.51%
lilv_array[3][2][5]=0.0396;//公积金 1～5年 3.96%
lilv_array[3][2][10]=0.0441;//公积金 5-30年 4.41%
			
//2006年	1月的新利率上限
lilv_array[4]=new Array;
lilv_array[4][1]=new Array;
lilv_array[4][2]=new Array;
lilv_array[4][1][5]=0.0527;//商贷 1～5年 5.27%
lilv_array[4][1][10]=0.0612;//商贷 5-30年 6.12%
lilv_array[4][2][5]= 0.0396;//公积金 1～5年 3.96%
lilv_array[4][2][10]=0.0441;//公积金 5-30年 4.41%

//2006年	4月28日的新利率下限
lilv_array[5]=new Array;
lilv_array[5][1]=new Array;
lilv_array[5][2]=new Array;
lilv_array[5][1][5]=0.0551;//商贷 1～5年 5.51%
lilv_array[5][1][10]=0.0575;//商贷 5-30年 5.75%
lilv_array[5][2][5]= 0.0414;//公积金 1～5年 4.14%
lilv_array[5][2][10]=0.0459;//公积金 5-30年 4.59%

//2006年	4月28日的新利率上限
lilv_array[6]=new Array;
lilv_array[6][1]=new Array;
lilv_array[6][2]=new Array;
lilv_array[6][1][5]=0.0612;//商贷 1～5年 6.12%
lilv_array[6][1][10]=0.0639;//商贷 5-30年 6.39%
lilv_array[6][2][5]= 0.0414;//公积金 1～5年 4.14%
lilv_array[6][2][10]=0.0459;//公积金 5-30年 4.59%

//2006年	8月19日的新利率下限
lilv_array[7]=new Array;
lilv_array[7][1]=new Array;
lilv_array[7][2]=new Array;
lilv_array[7][1][5]=0.0551;//商贷 1～5年 5.51%
lilv_array[7][1][10]=0.0581;//商贷 5-30年 5.81%
lilv_array[7][2][5]= 0.0414;//公积金 1～5年 4.14%
lilv_array[7][2][10]=0.0459;//公积金 5-30年 4.59%

//2006年	8月19日的新利率上限
lilv_array[8]=new Array;
lilv_array[8][1]=new Array;
lilv_array[8][2]=new Array;
lilv_array[8][1][5]=0.0648;//商贷 1～5年 6.48%
lilv_array[8][1][10]=0.0684;//商贷 5-30年 6.84%
lilv_array[8][2][5]= 0.0414;//公积金 1～5年 4.14%
lilv_array[8][2][10]=0.0459;//公积金 5-30年 4.59%


//2007年	3月18日的新利率下限
lilv_array[9]=new Array;
lilv_array[9][1]=new Array;
lilv_array[9][2]=new Array;
lilv_array[9][1][5]=0.0574;//商贷 1～5年 5.74%
lilv_array[9][1][10]=0.0604;//商贷 5-30年 6.04%
lilv_array[9][2][5]=0.0432;//公积金 1～5年 4.32%
lilv_array[9][2][10]=0.0477;//公积金 5-30年 4.77%

//2007年	3月18日的新利率上限
lilv_array[10]=new Array;
lilv_array[10][1]=new Array;
lilv_array[10][2]=new Array;
lilv_array[10][1][5]=0.0675;//商贷 1～5年 6.75%
lilv_array[10][1][10]=0.0711;//商贷 5-30年 7.11%
lilv_array[10][2][5]=0.0432;//公积金 1～5年 4.32%
lilv_array[10][2][10]=0.0477;//公积金 5-30年 4.77%


//2007年	5月19日的新利率下限
lilv_array[11]=new Array;
lilv_array[11][1]=new Array;
lilv_array[11][2]=new Array;
lilv_array[11][1][5]=0.0589;//商贷 1～5年 5.89%
lilv_array[11][1][10]=0.0612;//商贷 5-30年 6.12%
lilv_array[11][2][5]=0.0441;//公积金 1～5年 4.41%%
lilv_array[11][2][10]=0.0486;//公积金 5-30年 4.86%%

//2007年	5月19日的新利率上限
lilv_array[12]=new Array;
lilv_array[12][1]=new Array;
lilv_array[12][2]=new Array;
lilv_array[12][1][5]=0.0693;//商贷 1～5年 6.93%
lilv_array[12][1][10]=0.0720;//商贷 5-30年 7.20%
lilv_array[12][2][5]=0.0441;//公积金 1～5年 4.41%%
lilv_array[12][2][10]=0.0486;//公积金 5-30年 4.86%%

//2007年	7月21日的新利率下限
lilv_array[13]=new Array;
lilv_array[13][1]=new Array;
lilv_array[13][2]=new Array;
lilv_array[13][1][5]=0.0612;//商贷 1～5年 6.12%
lilv_array[13][1][10]=0.06273;//商贷 5-30年 6.273%
lilv_array[13][2][5]=0.0450;//公积金 1～5年 4.50%%
lilv_array[13][2][10]=0.0495;//公积金 5-30年 4.95%%

//2007年	7月21日的新利率上限
lilv_array[14]=new Array;
lilv_array[14][1]=new Array;
lilv_array[14][2]=new Array;
lilv_array[14][1][5]=0.0720;//商贷 1～5年 7.20%
lilv_array[14][1][10]=0.0738;//商贷 5-30年 7.38%
lilv_array[14][2][5]=0.0450;//公积金 1～5年 4.50%%
lilv_array[14][2][10]=0.0495;//公积金 5-30年 4.95%%

//2007年	8月22日的新利率下限
lilv_array[15]=new Array;
lilv_array[15][1]=new Array;
lilv_array[15][2]=new Array;
lilv_array[15][1][5]=0.06273;//商贷 1～5年 6.273%
lilv_array[15][1][10]=0.06426;//商贷 5-30年 6.426%
lilv_array[15][2][5]=0.0459;//公积金 1～5年 4.59%
lilv_array[15][2][10]=0.0504;//公积金 5-30年 5.04%

//2007年	8月22日的新利率上限
lilv_array[16]=new Array;
lilv_array[16][1]=new Array;
lilv_array[16][2]=new Array;
lilv_array[16][1][5]=0.0738;//商贷 1～5年 7.38%
lilv_array[16][1][10]=0.0756;//商贷 5-30年 7.56%
lilv_array[16][2][5]=0.0459;//公积金 1～5年 4.59%
lilv_array[16][2][10]=0.0504;//公积金 5-30年 5.04%

//2007年	9月15日的新利率下限
lilv_array[17]=new Array;
lilv_array[17][1]=new Array;
lilv_array[17][2]=new Array;
lilv_array[17][1][5]=0.06503;//商贷 1～5年 6.503%
lilv_array[17][1][10]=0.06656;//商贷 5-30年 6.656%
lilv_array[17][2][5]=0.0477;//公积金 1～5年 4.77%
lilv_array[17][2][10]=0.0522;//公积金 5-30年 5.22%

//2007年	9月15日的新利率上限
lilv_array[18]=new Array;
lilv_array[18][1]=new Array;
lilv_array[18][2]=new Array;
lilv_array[18][1][5]=0.0765;//商贷 1～5年 7.65%
lilv_array[18][1][10]=0.0783;//商贷 5-30年 7.83%
lilv_array[18][2][5]=0.0477;//公积金 1～5年 4.77%
lilv_array[18][2][10]=0.0522;//公积金 5-30年 5.22%

//2007年	9月15日新利率(第二套房)
lilv_array[19]=new Array;
lilv_array[19][1]=new Array;
lilv_array[19][2]=new Array;
lilv_array[19][1][5]=0.08415;//商贷 1～5年 8.415%
lilv_array[19][1][10]=0.08613;//商贷 5-30年 8.613%
lilv_array[19][2][5]=0.0477;//公积金 1～5年 4.77%
lilv_array[19][2][10]=0.0522;//公积金 5-30年 5.22%


//2007年	12月21日的新利率下限
lilv_array[20]=new Array;
lilv_array[20][1]=new Array;
lilv_array[20][2]=new Array;
lilv_array[20][1][5]=0.06579;//商贷 1～5年 6.579%
lilv_array[20][1][10]=0.06656;//商贷 5-30年 6.656%
lilv_array[20][2][5]=0.0477;//公积金 1～5年 4.77%
lilv_array[20][2][10]=0.0522;//公积金 5-30年 5.22%

//2007年	12月21日的新利率上限
lilv_array[21]=new Array;
lilv_array[21][1]=new Array;
lilv_array[21][2]=new Array;
lilv_array[21][1][5]=0.0774;//商贷 1～5年 7.74%
lilv_array[21][1][10]=0.0783;//商贷 5-30年 7.83%
lilv_array[21][2][5]=0.0477;//公积金 1～5年 4.77%
lilv_array[21][2][10]=0.0522;//公积金 5-30年 5.22%

//2007年	12月21日新利率(第二套房)
lilv_array[22]=new Array;
lilv_array[22][1]=new Array;
lilv_array[22][2]=new Array;
lilv_array[22][1][5]=0.08514;//商贷 1～5年 8.514%
lilv_array[22][1][10]=0.08613;//商贷 5-30年 8.613%
lilv_array[22][2][5]=0.0477;//公积金 1～5年 4.77%
lilv_array[22][2][10]=0.0522;//公积金 5-30年 5.22%

//2008年	9月16日的新利率下限
lilv_array[23]=new Array;
lilv_array[23][1]=new Array;
lilv_array[23][2]=new Array;
lilv_array[23][1][5]=0.06426;//商贷 1～5年 6.426%
lilv_array[23][1][10]=0.06579;//商贷 5-30年 6.579%
lilv_array[23][2][5]=0.0459;//公积金 1～5年 4.59%
lilv_array[23][2][10]=0.0513;//公积金 5-30年 5.13%

//2008年	9月16日的新利率上限
lilv_array[24]=new Array;
lilv_array[24][1]=new Array;
lilv_array[24][2]=new Array;
lilv_array[24][1][5]=0.0756;//商贷 1～5年 7.56%
lilv_array[24][1][10]=0.0774;//商贷 5-30年 7.74%
lilv_array[24][2][5]=0.0459;//公积金 1～5年 4.59%
lilv_array[24][2][10]=0.0513;//公积金 5-30年 5.13%

//2008年	9月16日新利率(第二套房)
lilv_array[25]=new Array;
lilv_array[25][1]=new Array;
lilv_array[25][2]=new Array;
lilv_array[25][1][5]=0.08316;//商贷 1～5年 8.316%
lilv_array[25][1][10]=0.08514;//商贷 5-30年 8.514%
lilv_array[25][2][5]=0.0459;//公积金 1～5年 4.59%
lilv_array[25][2][10]=0.0513;//公积金 5-30年 5.13%

//2008年	10月9日的新利率下限
lilv_array[26]=new Array;
lilv_array[26][1]=new Array;
lilv_array[26][2]=new Array;
lilv_array[26][1][5]=0.061965;//商贷 1～5年 6.1965%
lilv_array[26][1][10]=0.063495;//商贷 5-30年 6.3495%
lilv_array[26][2][5]=0.0432;//公积金 1～5年 4.32%
lilv_array[26][2][10]=0.0486;//公积金 5-30年 4.86%

//2008年	10月9日的新利率上限
lilv_array[27]=new Array;
lilv_array[27][1]=new Array;
lilv_array[27][2]=new Array;
lilv_array[27][1][5]=0.0729;//商贷 1～5年 7.29%
lilv_array[27][1][10]=0.0747;//商贷 5-30年 7.47%
lilv_array[27][2][5]=0.0432;//公积金 1～5年 4.32%
lilv_array[27][2][10]=0.0486;//公积金 5-30年 4.86%

//2008年	10月9日新利率(第二套房)
lilv_array[28]=new Array;
lilv_array[28][1]=new Array;
lilv_array[28][2]=new Array;
lilv_array[28][1][5]=0.08019;//商贷 1～5年 8.019%
lilv_array[28][1][10]=0.08217;//商贷 5-30年 8.217%
lilv_array[28][2][5]=0.0432;//公积金 1～5年 4.32%
lilv_array[28][2][10]=0.0486;//公积金 5-30年 4.86%


//2008年	10月30日基准利率
lilv_array[30]=new Array;
lilv_array[30][1]=new Array;
lilv_array[30][2]=new Array;
lilv_array[30][1][5]=0.0702;//商贷 1～5年 7.02%
lilv_array[30][1][10]=0.072;//商贷 5-30年 7.20%
lilv_array[30][2][5]=0.0405;//公积金 1～5年 4.05%
lilv_array[30][2][10]=0.0459;//公积金 5-30年 4.59%

//2008年	11月27日基准利率
lilv_array[31]=new Array;
lilv_array[31][1]=new Array;
lilv_array[31][2]=new Array;
lilv_array[31][1][5]=0.0594;//商贷 1～5年 5.94%
lilv_array[31][1][10]=0.0612;//商贷 5-30年 6.12%
lilv_array[31][2][5]=0.0351;//公积金 1～5年 3.51%
lilv_array[31][2][10]=0.0405;//公积金 5-30年 4.05%

//2008年	12月23日利率下限(7折)
lilv_array[32]=new Array;
lilv_array[32][1]=new Array;
lilv_array[32][2]=new Array;
lilv_array[32][1][5]=0.0576*0.7;//商贷 1～5年 5.76%    
lilv_array[32][1][10]=0.0594*0.7;//商贷 5-30年 5.94%   
lilv_array[32][2][5]=0.0333;//公积金 1～5年 3.33%  
lilv_array[32][2][10]=0.0387;//公积金 5-30年 3.87% 

//2008年	12月23日利率下限(85折)
lilv_array[33]=new Array;
lilv_array[33][1]=new Array;
lilv_array[33][2]=new Array;
lilv_array[33][1][5]=0.0576*0.85;//商贷 1～5年 5.76%    
lilv_array[33][1][10]=0.0594*0.85;//商贷 5-30年 5.94%   
lilv_array[33][2][5]=0.0333;//公积金 1～5年 3.33%  
lilv_array[33][2][10]=0.0387;//公积金 5-30年 3.87% 

//2008年	12月23日基准利率
lilv_array[34]=new Array;
lilv_array[34][1]=new Array;
lilv_array[34][2]=new Array;
lilv_array[34][1][5]=0.0576;//商贷 1～5年 5.76%
lilv_array[34][1][10]=0.0594;//商贷 5-30年 5.94%
lilv_array[34][2][5]=0.0333;//公积金 1～5年 3.33%
lilv_array[34][2][10]=0.0387;//公积金 5-30年 3.87%

//2008年	12月23日利率上限(1.1倍)
lilv_array[35]=new Array;
lilv_array[35][1]=new Array;
lilv_array[35][2]=new Array;
lilv_array[35][1][5]=0.0576*1.1;//商贷 1～5年 5.76%    
lilv_array[35][1][10]=0.0594*1.1;//商贷 5-30年 5.94%   
lilv_array[35][2][5]=0.0333*1.1;//公积金 1～5年 3.33%  
lilv_array[35][2][10]=0.0387*1.1;//公积金 5-30年 3.87% 


//2010年	10月20日利率下限(7折)
lilv_array[36]=new Array;
lilv_array[36][1]=new Array;
lilv_array[36][2]=new Array;
lilv_array[36][1][1]=0.0556*0.7;
lilv_array[36][1][3]=0.0560*0.7;
lilv_array[36][1][5]=0.0596*0.7;    
lilv_array[36][1][10]=0.0614*0.7;  
lilv_array[36][2][5]=0.0350; 
lilv_array[36][2][10]=0.0405; 

//2010年	10月20日利率下限(85折)
lilv_array[37]=new Array;
lilv_array[37][1]=new Array;
lilv_array[37][2]=new Array;
lilv_array[37][1][1]=0.0556*0.85;
lilv_array[37][1][3]=0.0560*0.85;
lilv_array[37][1][5]=0.0596*0.85; 
lilv_array[37][1][10]=0.0614*0.85;  
lilv_array[37][2][5]=0.0350; 
lilv_array[37][2][10]=0.0405;

//2010年	10月20日基准利率
lilv_array[38]=new Array;
lilv_array[38][1]=new Array;
lilv_array[38][2]=new Array;
lilv_array[38][1][1]=0.0556;//商贷 1年 5.56%
lilv_array[38][1][3]=0.0560;//商贷 1～3年 5.60%
lilv_array[38][1][5]=0.0596;//商贷 3～5年 5.96%
lilv_array[38][1][10]=0.0614;//商贷 5-30年 6.14%
lilv_array[38][2][5]=0.0350;//公积金 1～5年 3.50%
lilv_array[38][2][10]=0.0405;//公积金 5-30年 4.05%

//2010年	10月20日利率上限(1.1倍)
lilv_array[39]=new Array;
lilv_array[39][1]=new Array;
lilv_array[39][2]=new Array;
lilv_array[39][1][1]=0.0556*1.1;
lilv_array[39][1][3]=0.0560*1.1;
lilv_array[39][1][5]=0.0596*1.1; 
lilv_array[39][1][10]=0.0614*1.1; 
lilv_array[39][2][5]=0.0350*1.1;
lilv_array[39][2][10]=0.0405*1.1;

//2010年	12月26日利率下限(7折)
lilv_array[40]=new Array;
lilv_array[40][1]=new Array;
lilv_array[40][2]=new Array;
lilv_array[40][1][1]=0.0581*0.7;
lilv_array[40][1][3]=0.0585*0.7;
lilv_array[40][1][5]=0.0622*0.7;    
lilv_array[40][1][10]=0.0640*0.7;  
lilv_array[40][2][5]=0.0375; 
lilv_array[40][2][10]=0.0430; 

//2010年	12月26日利率下限(85折)
lilv_array[41]=new Array;
lilv_array[41][1]=new Array;
lilv_array[41][2]=new Array;
lilv_array[41][1][1]=0.0581*0.85;
lilv_array[41][1][3]=0.0585*0.85;
lilv_array[41][1][5]=0.0622*0.85; 
lilv_array[41][1][10]=0.0640*0.85;  
lilv_array[41][2][5]=0.0375; 
lilv_array[41][2][10]=0.0430;

//2010年	12月26日基准利率
lilv_array[42]=new Array;
lilv_array[42][1]=new Array;
lilv_array[42][2]=new Array;
lilv_array[42][1][1]=0.0581;
lilv_array[42][1][3]=0.0585;//商贷 1～3年 5.85%
lilv_array[42][1][5]=0.0622;//商贷 3～5年 5.96%
lilv_array[42][1][10]=0.0640;//商贷 5-30年 6.14%
lilv_array[42][2][5]=0.0375;//公积金 1～5年 3.50%
lilv_array[42][2][10]=0.0430;//公积金 5-30年 4.05%

//2010年	12月26日利率上限(1.1倍)
lilv_array[43]=new Array;
lilv_array[43][1]=new Array;
lilv_array[43][2]=new Array;
lilv_array[43][1][1]=0.0581*1.1;
lilv_array[43][1][3]=0.0585*1.1;
lilv_array[43][1][5]=0.0622*1.1; 
lilv_array[43][1][10]=0.0640*1.1; 
lilv_array[43][2][5]=0.0375*1.1;
lilv_array[43][2][10]=0.0430*1.1;

//2010年	12月26日利率上限(1.2倍)
lilv_array[44]=new Array;
lilv_array[44][1]=new Array;
lilv_array[44][2]=new Array;
lilv_array[44][1][1]=0.0581*1.2;
lilv_array[44][1][3]=0.0585*1.2;
lilv_array[44][1][5]=0.0622*1.2; 
lilv_array[44][1][10]=0.0640*1.2; 
lilv_array[44][2][5]=0.0375;
lilv_array[44][2][10]=0.0430;

//2011年	02月09日利率下限(7折)
lilv_array[45]=new Array;
lilv_array[45][1]=new Array;
lilv_array[45][2]=new Array;
lilv_array[45][1][1]=0.0606*0.7;
lilv_array[45][1][3]=0.0610*0.7;
lilv_array[45][1][5]=0.0645*0.7;    
lilv_array[45][1][10]=0.0660*0.7;  
lilv_array[45][2][5]=0.0400; 
lilv_array[45][2][10]=0.0450; 

//2011年	02月09日利率下限(85折)
lilv_array[46]=new Array;
lilv_array[46][1]=new Array;
lilv_array[46][2]=new Array;
lilv_array[46][1][1]=0.0606*0.85;
lilv_array[46][1][3]=0.0610*0.85;
lilv_array[46][1][5]=0.0645*0.85; 
lilv_array[46][1][10]=0.0660*0.85;  
lilv_array[46][2][5]=0.0400; 
lilv_array[46][2][10]=0.0450;

//2011年	02月09日基准利率
lilv_array[47]=new Array;
lilv_array[47][1]=new Array;
lilv_array[47][2]=new Array;
lilv_array[47][1][1]=0.0606;
lilv_array[47][1][3]=0.0610;//商贷 1～3年 5.60%
lilv_array[47][1][5]=0.0645;//商贷 3～5年 5.96%
lilv_array[47][1][10]=0.0660;//商贷 5-30年 6.14%
lilv_array[47][2][5]=0.0400;//公积金 1～5年 3.50%
lilv_array[47][2][10]=0.0450;//公积金 5-30年 4.05%

//2011年	02月09日利率上限(1.1倍)
lilv_array[48]=new Array;
lilv_array[48][1]=new Array;
lilv_array[48][2]=new Array;
lilv_array[48][1][1]=0.0606*1.1;
lilv_array[48][1][3]=0.0610*1.1;
lilv_array[48][1][5]=0.0645*1.1; 
lilv_array[48][1][10]=0.0660*1.1; 
lilv_array[48][2][5]=0.0400*1.1;
lilv_array[48][2][10]=0.0450*1.1;

//2011年	02月09日利率上限(1.2倍)
lilv_array[49]=new Array;
lilv_array[49][1]=new Array;
lilv_array[49][2]=new Array;
lilv_array[49][1][1]=0.0606*1.2;
lilv_array[49][1][3]=0.0610*1.2;
lilv_array[49][1][5]=0.0645*1.2; 
lilv_array[49][1][10]=0.0660*1.2; 
lilv_array[49][2][5]=0.0400;
lilv_array[49][2][10]=0.0450;

//2011年	04月06日利率下限(7折)
lilv_array[50]=new Array;
lilv_array[50][1]=new Array;
lilv_array[50][2]=new Array;
lilv_array[50][1][1]=0.0631*0.7;
lilv_array[50][1][3]=0.0640*0.7;
lilv_array[50][1][5]=0.0665*0.7;    
lilv_array[50][1][10]=0.0680*0.7;  
lilv_array[50][2][5]=0.0420; 
lilv_array[50][2][10]=0.0470; 

//2011年	04月06日利率下限(85折)
lilv_array[51]=new Array;
lilv_array[51][1]=new Array;
lilv_array[51][2]=new Array;
lilv_array[51][1][1]=0.0631*0.85;
lilv_array[51][1][3]=0.0640*0.85;
lilv_array[51][1][5]=0.0665*0.85; 
lilv_array[51][1][10]=0.0680*0.85;  
lilv_array[51][2][5]=0.0420; 
lilv_array[51][2][10]=0.0470;

//2011年	04月06日基准利率
lilv_array[52]=new Array;
lilv_array[52][1]=new Array;
lilv_array[52][2]=new Array;
lilv_array[52][1][1]=0.0631;
lilv_array[52][1][3]=0.0640;//商贷 1～3年 5.60%
lilv_array[52][1][5]=0.0665;//商贷 3～5年 5.96%
lilv_array[52][1][10]=0.0680;//商贷 5-30年 6.14%
lilv_array[52][2][5]=0.0420;//公积金 1～5年 3.50%
lilv_array[52][2][10]=0.0470;//公积金 5-30年 4.05%

//2011年	04月06日利率上限(1.1倍)
lilv_array[53]=new Array;
lilv_array[53][1]=new Array;
lilv_array[53][2]=new Array;
lilv_array[53][1][1]=0.0631*1.1;
lilv_array[53][1][3]=0.0640*1.1;
lilv_array[53][1][5]=0.0665*1.1; 
lilv_array[53][1][10]=0.0680*1.1; 
lilv_array[53][2][5]=0.0420*1.1;
lilv_array[53][2][10]=0.0470*1.1;

//2011年	04月06日利率上限(1.2倍)
lilv_array[54]=new Array;
lilv_array[54][1]=new Array;
lilv_array[54][2]=new Array;
lilv_array[54][1][1]=0.0631*1.2;
lilv_array[54][1][3]=0.0640*1.2;
lilv_array[54][1][5]=0.0665*1.2; 
lilv_array[54][1][10]=0.0680*1.2; 
lilv_array[54][2][5]=0.0420;
lilv_array[54][2][10]=0.0470;


//2011年	07月07日利率下限(7折)
lilv_array[55]=new Array;
lilv_array[55][1]=new Array;
lilv_array[55][2]=new Array;
lilv_array[55][1][1]=0.0656*0.7;
lilv_array[55][1][3]=0.0665*0.7;
lilv_array[55][1][5]=0.0690*0.7;    
lilv_array[55][1][10]=0.0705*0.7;  
lilv_array[55][2][5]=0.0445; 
lilv_array[55][2][10]=0.0490; 

//2011年	07月07日利率下限(85折)
lilv_array[56]=new Array;
lilv_array[56][1]=new Array;
lilv_array[56][2]=new Array;
lilv_array[56][1][1]=0.0656*0.85;
lilv_array[56][1][3]=0.0665*0.85;
lilv_array[56][1][5]=0.0690*0.85; 
lilv_array[56][1][10]=0.0705*0.85;  
lilv_array[56][2][5]=0.0445; 
lilv_array[56][2][10]=0.0490;

//2011年	07月07日利率下限(9折)
lilv_array[57]=new Array;
lilv_array[57][1]=new Array;
lilv_array[57][2]=new Array;
lilv_array[57][1][1]=0.0656*0.9;
lilv_array[57][1][3]=0.0665*0.9;
lilv_array[57][1][5]=0.0690*0.9; 
lilv_array[57][1][10]=0.0705*0.9;  
lilv_array[57][2][5]=0.0445; 
lilv_array[57][2][10]=0.0490;

//2011年	07月07日基准利率
lilv_array[58]=new Array;
lilv_array[58][1]=new Array;
lilv_array[58][2]=new Array;
lilv_array[58][1][1]=0.0656;
lilv_array[58][1][3]=0.0665;//商贷 1～3年 5.60%
lilv_array[58][1][5]=0.0690;//商贷 3～5年 5.96%
lilv_array[58][1][10]=0.0705;//商贷 5-30年 6.14%
lilv_array[58][2][5]=0.0445;//公积金 1～5年 3.50%
lilv_array[58][2][10]=0.0490;//公积金 5-30年 4.05%

//2011年	07月07日利率上限(1.05倍)
lilv_array[59]=new Array;
lilv_array[59][1]=new Array;
lilv_array[59][2]=new Array;
lilv_array[59][1][1]=0.0656*1.05;
lilv_array[59][1][3]=0.0665*1.05;
lilv_array[59][1][5]=0.0690*1.05; 
lilv_array[59][1][10]=0.0705*1.05; 
lilv_array[59][2][5]=0.0445*1.05;
lilv_array[59][2][10]=0.0490*1.05;

//2011年	07月07日利率上限(1.1倍)
lilv_array[60]=new Array;
lilv_array[60][1]=new Array;
lilv_array[60][2]=new Array;
lilv_array[60][1][1]=0.0656*1.1;
lilv_array[60][1][3]=0.0665*1.1;
lilv_array[60][1][5]=0.0690*1.1; 
lilv_array[60][1][10]=0.0705*1.1; 
lilv_array[60][2][5]=0.0445*1.1;
lilv_array[60][2][10]=0.0490*1.1;

//2011年	07月07日利率上限(1.2倍)
lilv_array[61]=new Array;
lilv_array[61][1]=new Array;
lilv_array[61][2]=new Array;
lilv_array[61][1][1]=0.0656*1.2;
lilv_array[61][1][3]=0.0665*1.2;
lilv_array[61][1][5]=0.0690*1.2; 
lilv_array[61][1][10]=0.0705*1.2; 
lilv_array[61][2][5]=0.0445*1.2;
lilv_array[61][2][10]=0.0490*1.2;

//2012年	06月08日利率下限(7折)
lilv_array[62]=new Array;
lilv_array[62][1]=new Array;
lilv_array[62][2]=new Array;
lilv_array[62][1][1]=0.0631*0.7;
lilv_array[62][1][3]=0.0640*0.7;
lilv_array[62][1][5]=0.0665*0.7;    
lilv_array[62][1][10]=0.0680*0.7;  
lilv_array[62][2][5]=0.0420; 
lilv_array[62][2][10]=0.0470; 

//2012年	06月08日利率下限(85折)
lilv_array[63]=new Array;
lilv_array[63][1]=new Array;
lilv_array[63][2]=new Array;
lilv_array[63][1][1]=0.0631*0.85;
lilv_array[63][1][3]=0.0640*0.85;
lilv_array[63][1][5]=0.0665*0.85; 
lilv_array[63][1][10]=0.0680*0.85;  
lilv_array[63][2][5]=0.0420; 
lilv_array[63][2][10]=0.0470;

//2012年	06月08日利率下限(9折)
lilv_array[64]=new Array;
lilv_array[64][1]=new Array;
lilv_array[64][2]=new Array;
lilv_array[64][1][1]=0.0631*0.9;
lilv_array[64][1][3]=0.0640*0.9;
lilv_array[64][1][5]=0.0665*0.9; 
lilv_array[64][1][10]=0.0680*0.9;  
lilv_array[64][2][5]=0.0420; 
lilv_array[64][2][10]=0.0470;

//2012年	06月08日基准利率
lilv_array[65]=new Array;
lilv_array[65][1]=new Array;
lilv_array[65][2]=new Array;
lilv_array[65][1][1]=0.0631;
lilv_array[65][1][3]=0.0640;//商贷 1～3年 5.60%
lilv_array[65][1][5]=0.0665;//商贷 3～5年 5.96%
lilv_array[65][1][10]=0.0680;//商贷 5-30年 6.14%
lilv_array[65][2][5]=0.0420;//公积金 1～5年 3.50%
lilv_array[65][2][10]=0.0470;//公积金 5-30年 4.05%

//2012年	06月08日利率上限(1.05倍)
lilv_array[66]=new Array;
lilv_array[66][1]=new Array;
lilv_array[66][2]=new Array;
lilv_array[66][1][1]=0.0631*1.05;
lilv_array[66][1][3]=0.0640*1.05;
lilv_array[66][1][5]=0.0665*1.05; 
lilv_array[66][1][10]=0.0680*1.05; 
lilv_array[66][2][5]=0.0420*1.05;
lilv_array[66][2][10]=0.0470*1.05;

//2012年	06月08日利率上限(1.1倍)
lilv_array[67]=new Array;
lilv_array[67][1]=new Array;
lilv_array[67][2]=new Array;
lilv_array[67][1][1]=0.0631*1.1;
lilv_array[67][1][3]=0.0640*1.1;
lilv_array[67][1][5]=0.0665*1.1; 
lilv_array[67][1][10]=0.0680*1.1; 
lilv_array[67][2][5]=0.0420*1.1;
lilv_array[67][2][10]=0.0470*1.1;

//2012年	06月08日利率上限(1.2倍)
lilv_array[68]=new Array;
lilv_array[68][1]=new Array;
lilv_array[68][2]=new Array;
lilv_array[68][1][1]=0.0631*1.2;
lilv_array[68][1][3]=0.0640*1.2;
lilv_array[68][1][5]=0.0665*1.2; 
lilv_array[68][1][10]=0.0680*1.2; 
lilv_array[68][2][5]=0.0420*1.2;
lilv_array[68][2][10]=0.0470*1.2;


//2012年	07月06日利率下限(7折)
lilv_array[69]=new Array;
lilv_array[69][1]=new Array;
lilv_array[69][2]=new Array;
lilv_array[69][1][1]=0.0600*0.7;
lilv_array[69][1][3]=0.0615*0.7;
lilv_array[69][1][5]=0.0640*0.7;    
lilv_array[69][1][10]=0.0655*0.7;  
lilv_array[69][2][5]=0.0400; 
lilv_array[69][2][10]=0.0450; 

//2012年	07月06日利率下限(85折)
lilv_array[70]=new Array;
lilv_array[70][1]=new Array;
lilv_array[70][2]=new Array;
lilv_array[70][1][1]=0.0600*0.85;
lilv_array[70][1][3]=0.0615*0.85;
lilv_array[70][1][5]=0.0640*0.85; 
lilv_array[70][1][10]=0.0655*0.85;  
lilv_array[70][2][5]=0.0400; 
lilv_array[70][2][10]=0.0450;

//2012年	07月06日利率下限(9折)
lilv_array[71]=new Array;
lilv_array[71][1]=new Array;
lilv_array[71][2]=new Array;
lilv_array[71][1][1]=0.0600*0.9;
lilv_array[71][1][3]=0.0615*0.9;
lilv_array[71][1][5]=0.0640*0.9; 
lilv_array[71][1][10]=0.0655*0.9;  
lilv_array[71][2][5]=0.0400; 
lilv_array[71][2][10]=0.0450;

//2012年	07月06日基准利率
lilv_array[72]=new Array;
lilv_array[72][1]=new Array;
lilv_array[72][2]=new Array;
lilv_array[72][1][1]=0.0600;
lilv_array[72][1][3]=0.0615;//商贷 1～3年 5.60%
lilv_array[72][1][5]=0.0640;//商贷 3～5年 5.96%
lilv_array[72][1][10]=0.0655;//商贷 5-30年 6.14%
lilv_array[72][2][5]=0.0400;//公积金 1～5年 3.50%
lilv_array[72][2][10]=0.0450;//公积金 5-30年 4.05%

//2012年	07月06日利率上限(1.05倍)
lilv_array[73]=new Array;
lilv_array[73][1]=new Array;
lilv_array[73][2]=new Array;
lilv_array[73][1][1]=0.0600*1.05;
lilv_array[73][1][3]=0.0615*1.05;
lilv_array[73][1][5]=0.0640*1.05; 
lilv_array[73][1][10]=0.0655*1.05; 
lilv_array[73][2][5]=0.0400*1.05;
lilv_array[73][2][10]=0.0450*1.05;

//2012年	07月06日利率上限(1.1倍)
lilv_array[74]=new Array;
lilv_array[74][1]=new Array;
lilv_array[74][2]=new Array;
lilv_array[74][1][1]=0.0600*1.1;
lilv_array[74][1][3]=0.0615*1.1;
lilv_array[74][1][5]=0.0640*1.1; 
lilv_array[74][1][10]=0.0655*1.1; 
lilv_array[74][2][5]=0.0400*1.1;
lilv_array[74][2][10]=0.0450*1.1;

//2012年	07月06日利率上限(1.2倍)
lilv_array[75]=new Array;
lilv_array[75][1]=new Array;
lilv_array[75][2]=new Array;
lilv_array[75][1][1]=0.0600*1.2;
lilv_array[75][1][3]=0.0615*1.2;
lilv_array[75][1][5]=0.0640*1.2; 
lilv_array[75][1][10]=0.0655*1.2; 
lilv_array[75][2][5]=0.0400*1.2;
lilv_array[75][2][10]=0.0450*1.2;


//2014年	11月22日利率下限(7折)
lilv_array[76]=new Array;
lilv_array[76][1]=new Array;
lilv_array[76][2]=new Array;
lilv_array[76][1][1]=0.0560*0.7;
lilv_array[76][1][3]=0.0600*0.7;
lilv_array[76][1][5]=0.0600*0.7;    
lilv_array[76][1][10]=0.0615*0.7;  
lilv_array[76][2][5]=0.0375; 
lilv_array[76][2][10]=0.0425; 

//2014年	11月22日利率下限(85折)
lilv_array[77]=new Array;
lilv_array[77][1]=new Array;
lilv_array[77][2]=new Array;
lilv_array[77][1][1]=0.0560*0.85;
lilv_array[77][1][3]=0.0600*0.85;
lilv_array[77][1][5]=0.0600*0.85; 
lilv_array[77][1][10]=0.0615*0.85;  
lilv_array[77][2][5]=0.0375; 
lilv_array[77][2][10]=0.0425;

//2014年	11月22日利率下限(9折)
lilv_array[78]=new Array;
lilv_array[78][1]=new Array;
lilv_array[78][2]=new Array;
lilv_array[78][1][1]=0.0560*0.9;
lilv_array[78][1][3]=0.0600*0.9;
lilv_array[78][1][5]=0.0600*0.9;    
lilv_array[78][1][10]=0.0615*0.9;  
lilv_array[78][2][5]=0.0375; 
lilv_array[78][2][10]=0.0425; 

//2014年	11月22日基准利率
lilv_array[79]=new Array;
lilv_array[79][1]=new Array;
lilv_array[79][2]=new Array;
lilv_array[79][1][1]=0.0560;
lilv_array[79][1][3]=0.0600;//商贷 1～3年 5.60%
lilv_array[79][1][5]=0.0600;//商贷 3～5年 5.96%
lilv_array[79][1][10]=0.0615;//商贷 5-30年 6.14%
lilv_array[79][2][5]=0.0375;//公积金 1～5年 3.50%
lilv_array[79][2][10]=0.0425;//公积金 5-30年 4.05%

//2014年	11月22日利率上限(1.05倍)
lilv_array[80]=new Array;
lilv_array[80][1]=new Array;
lilv_array[80][2]=new Array;
lilv_array[80][1][1]=0.0560*1.05;
lilv_array[80][1][3]=0.0600*1.05;
lilv_array[80][1][5]=0.0600*1.05; 
lilv_array[80][1][10]=0.0615*1.05; 
lilv_array[80][2][5]=0.0375*1.05;
lilv_array[80][2][10]=0.0425*1.05;

//2014年	11月22日利率上限(1.1倍)
lilv_array[81]=new Array;
lilv_array[81][1]=new Array;
lilv_array[81][2]=new Array;
lilv_array[81][1][1]=0.0560*1.1;
lilv_array[81][1][3]=0.0600*1.1;
lilv_array[81][1][5]=0.0600*1.1; 
lilv_array[81][1][10]=0.0615*1.1; 
lilv_array[81][2][5]=0.0375*1.1;
lilv_array[81][2][10]=0.0425*1.1;

//2014年	11月22日利率上限(1.2倍)
lilv_array[82]=new Array;
lilv_array[82][1]=new Array;
lilv_array[82][2]=new Array;
lilv_array[82][1][1]=0.0560*1.2;
lilv_array[82][1][3]=0.0600*1.2;
lilv_array[82][1][5]=0.0600*1.2; 
lilv_array[82][1][10]=0.0615*1.2; 
lilv_array[82][2][5]=0.0375*1.2;
lilv_array[82][2][10]=0.0425*1.2;

//得到利率
function getlilv(lilv_class,type,years){
	var lilv_class = parseInt(lilv_class);
    if (years<=5){
		 return lilv_array[lilv_class][type][5];
	}else{
		return lilv_array[lilv_class][type][10];
	}
}

//本金还款的月还款额(参数: 年利率 / 贷款总额 / 贷款总月份 / 贷款当前月0～length-1)
function getMonthMoney2(lilv,total,month,cur_month){
	var lilv_month = lilv / 12;//月利率
	//return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
	var benjin_money = total/month;
	return (total - benjin_money * cur_month) * lilv_month + benjin_money;

}


//本息还款的月还款额(参数: 年利率/贷款总额/贷款总月份)
function getMonthMoney1(lilv,total,month){
	var lilv_month = lilv / 12;//月利率
	return total * lilv_month * Math.pow(1 + lilv_month, month) / ( Math.pow(1 + lilv_month, month) -1 );
}

function ext_total(fmobj){
	//var fmobj=document.calc1;
	//先清空月还款数下拉框
	while ((k=fmobj.month_money2.length-1)>=0){
		fmobj.month_money2.options.remove(k);
	}
	var years = fmobj.years.value;
	var month = fmobj.years.value * 12;

	fmobj.month1.value = month+"(月)";
	fmobj.month2.value = month+"(月)";
	if (fmobj.type.value == 3 ){
		//--  组合型贷款(组合型贷款的计算，只和商业贷款额、和公积金贷款额有关，和按贷款总额计算无关)
			if (!reg_Num(fmobj.total_sy.value)){alert("混合型贷款请填写商贷比例");fmobj.total_sy.focus();return false;}
			if (!reg_Num(fmobj.total_gjj.value)){alert("混合型贷款请填写公积金比例");fmobj.total_gjj.focus();return false;}
			if (fmobj.total_sy.value==null){fmobj.total_sy.value=0;}
			if (fmobj.total_gjj.value==null){fmobj.total_gjj.value=0;}
			var total_sy = fmobj.total_sy.value*10000;
			var total_gjj = fmobj.total_gjj.value*10000;
			fmobj.fangkuan_total1.value = "略";//房款总额
			fmobj.fangkuan_total2.value = "略";//房款总额
			fmobj.money_first1.value = 0;//首期付款
			fmobj.money_first2.value = 0;//首期付款

			//贷款总额
			var total_sy = parseInt(fmobj.total_sy.value*10000);
			var total_gjj = parseInt(fmobj.total_gjj.value*10000);
			var daikuan_total = total_sy + total_gjj;
			fmobj.daikuan_total1.value = Math.round(daikuan_total);
			fmobj.daikuan_total2.value = Math.round(daikuan_total);

			//月还款
			var lilv_sd = getlilv(fmobj.lilv.value,1, years);//得到商贷利率
			var lilv_gjj = getlilv(fmobj.lilv.value,2, years);//得到公积金利率

			//1.本金还款
				//月还款
				var all_total2 = 0;
				var month_money2 = "";
				for(j=0;j<month;j++) {
					//调用函数计算: 本金月还款额
					huankuan = getMonthMoney2(lilv_sd,total_sy,month,j) + getMonthMoney2(lilv_gjj,total_gjj,month,j);
					all_total2 += huankuan;
					huankuan = Math.round(huankuan*100)/100;
					//fmobj.month_money2.options[j] = new Option( (j+1) +"月," + huankuan + "(元)", huankuan);
					month_money2 += (j+1) +"月," + huankuan + "(元)\n";
				}
				fmobj.month_money2.value = month_money2;
				//还款总额
				fmobj.all_total2.value = Math.round(all_total2*100)/100;
				//支付利息款
				fmobj.accrual2.value = Math.round( (all_total2 - daikuan_total) *100)/100;


			//2.本息还款
				//月均还款
				var month_money1 = getMonthMoney1(lilv_sd,total_sy,month) + getMonthMoney1(lilv_gjj,total_gjj,month);//调用函数计算
				fmobj.month_money1.value = Math.round(month_money1*100)/100 + "(元)";
				//还款总额
				var all_total1 = month_money1 * month;
				fmobj.all_total1.value = Math.round(all_total1*100)/100;
				//支付利息款
				fmobj.accrual1.value = Math.round( (all_total1 - daikuan_total) *100)/100;

	}else{
		//--  商业贷款、公积金贷款
			var lilv = getlilv(fmobj.lilv.value,fmobj.type.value, fmobj.years.value);//得到利率
			if (fmobj.jisuan_radio[0].checked == true){
				//------------ 根据单价面积计算
				if (!reg_Num(fmobj.price.value)){alert("请填写单价");fmobj.price.focus();return false;}
				if (!reg_Num(fmobj.sqm.value)){alert("请填写面积");fmobj.sqm.focus();return false;}

				//房款总额
				var fangkuan_total = fmobj.price.value * fmobj.sqm.value;
				fmobj.fangkuan_total1.value = fangkuan_total;
				fmobj.fangkuan_total2.value = fangkuan_total;
				//贷款总额
				var daikuan_total = (fmobj.price.value * fmobj.sqm.value) * (fmobj.anjie.value/10);
				fmobj.daikuan_total1.value = Math.round(daikuan_total);
				fmobj.daikuan_total2.value = Math.round(daikuan_total);
				//首期付款
				var money_first = fangkuan_total - daikuan_total;
				fmobj.money_first1.value = Math.round(money_first);
				fmobj.money_first2.value = Math.round(money_first);
			}else{
				//------------ 根据贷款总额计算
				if (!reg_Num(fmobj.daikuan_total000.value)){alert("请填写贷款总额");fmobj.daikuan_total000.focus();return false;}

				//房款总额
				fmobj.fangkuan_total1.value = "略";
				fmobj.fangkuan_total2.value = "略";
				//贷款总额
				//var daikuan_total = fmobj.daikuan_total000.value;
				var daikuan_total = fmobj.daikuan_total000.value*10000;
				fmobj.daikuan_total1.value = Math.round(daikuan_total);
				fmobj.daikuan_total2.value = Math.round(daikuan_total);
				//首期付款
				fmobj.money_first1.value = 0;
				fmobj.money_first2.value = 0;
			}
			//1.本金还款
				//月还款
				var all_total2 = 0;
				var month_money2 = "";
				for(j=0;j<month;j++) {
					//调用函数计算: 本金月还款额
					huankuan = getMonthMoney2(lilv,daikuan_total,month,j);
					all_total2 += huankuan;
					huankuan = Math.round(huankuan*100)/100;
					//fmobj.month_money2.options[j] = new Option( (j+1) +"月," + huankuan + "(元)", huankuan);
					month_money2 += (j+1) +"月," + huankuan + "(元)\n";
				}
				fmobj.month_money2.value = month_money2;
				//还款总额
				fmobj.all_total2.value = Math.round(all_total2*100)/100;
				//支付利息款
				fmobj.accrual2.value = Math.round( (all_total2 - daikuan_total) *100)/100;


			//2.本息还款
				//月均还款
				var month_money1 = getMonthMoney1(lilv,daikuan_total,month);//调用函数计算
				fmobj.month_money1.value = Math.round(month_money1*100)/100 + "(元)";
				//还款总额
				var all_total1 = month_money1 * month;
				fmobj.all_total1.value = Math.round(all_total1*100)/100;
				//支付利息款
				fmobj.accrual1.value = Math.round( (all_total1 - daikuan_total) *100)/100;

	}
}



function load_year_map(fmobj,dktype){
    if(fmobj.daikuan_total1.value!="" && fmobj.all_total1.value!=""){
		if(fmobj.type.value!=3){
			var lilv = getlilv(fmobj.lilv.value, fmobj.type.value, fmobj.years.value);
			if (fmobj.jisuan_radio[0].checked == true){
				var daikuan_total = (fmobj.price.value * fmobj.sqm.value) * (fmobj.anjie.value/10);
			}else{
				var daikuan_total = fmobj.daikuan_total000.value*10000;
			}
			var month = fmobj.years.value*12;
			var imgHtml = "<img src=/common/modules/housemarket/daikuan_year_map.php?lilv="+lilv+"&total="+daikuan_total+"&month="+month+"&type="+dktype+">";
		}else{
			var lilv_sy = getlilv(fmobj.lilv.value, 1, fmobj.years.value);
			var lilv_gjj = getlilv(fmobj.lilv.value, 2, fmobj.years.value);
			var dk_total_sy = fmobj.total_sy.value*10000;
			var dk_total_gjj = fmobj.total_gjj.value*10000;
			var month = fmobj.years.value*12;
			dktype += '_mix';
			var imgHtml = "<img src=/common/modules/housemarket/daikuan_year_map.php?lilv_sy="+lilv_sy+"&lilv_gjj="+lilv_gjj+"&total_sy="+dk_total_sy+"&total_gjj="+dk_total_gjj+"&month="+month+"&type="+dktype+">";
		}
        if ( document.getElementById("year_map_"+dktype) != null	){
			document.getElementById("year_map_"+dktype).innerHTML = imgHtml;
		}else{
			document.getElementById("year_map").innerHTML = imgHtml;
		}
    }else{
		if ( document.getElementById("year_map_"+dktype) != null	){
			document.getElementById("year_map_"+dktype).innerHTML = imgHtml;
		}else{
			document.getElementById("year_map").innerHTML = imgHtml;
		}
	}
}