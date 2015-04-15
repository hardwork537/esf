// JavaScript Document
function isnumeric(p)
{
 if (p == "")
  return false;
 var l = p.length;
 var count=0;
 for(var i=0; i<l; i++)
 {
  var digit = p.charAt(i);
  if(digit == "." )
 {
    ++count;
    if(count>1) return false;
   }
  else if(digit < "0" || digit > "9")
  return false;
 }
 return true;
}

function isInt (theStr) {
    var flag = true;

    if (isEmpty(theStr)) { flag=false; }
    else{ 
        for (var i=0; i<theStr.length; i++) {
            if (theStr.substring(i,i+1) ==".") {
                flag = false; break;
            }
        }
    }
    return(flag);
}


function isEmpty (str) {
    if ((str==null)||(str.length==0)) return true;
    else return(false);
}

function adv_format(value,num)   //四舍五入
    {
    var a_str = formatnumber(value,num);
    var a_int = parseFloat(a_str);
    if (value.toString().length>a_str.length)
        {
        var b_str = value.toString().substring(a_str.length,a_str.length+1)
        var b_int = parseFloat(b_str);
        if (b_int<5)
            {
            return a_str
            }
        else
            {
            var bonus_str,bonus_int;
            if (num==0)
                {
                bonus_int = 1;
                }
            else
                {
                bonus_str = "0."
                for (var i=1; i<num; i++)
                    bonus_str+="0";
                bonus_str+="1";
                bonus_int = parseFloat(bonus_str);
                }
            a_str = formatnumber(a_int + bonus_int, num)
            }
        }
        return a_str
    }


function formatnumber(value,num)    //直接去尾
{
    var a,b,c,i
    a = value.toString();
    b = a.indexOf('.');
    c = a.length;
    if (num==0)
        {
        if (b!=-1)
            a = a.substring(0,b);
        }
    else
        {
        if (b==-1)
            {
            a = a + ".";
            for (i=1;i<=num;i++)
                a = a + "0";
            }
        else
            {
            a = a.substring(0,b+num+1);
            for (i=c;i<=b+num;i++)
                a = a + "0";
            }
        }
    return a
}

lilv_array = new Array;
//2004年之前的旧利率
lilv_array[1] = new Array;
lilv_array[1][1] = new Array;
lilv_array[1][2] = new Array;
lilv_array[1][1][5] = 0.0477; //商贷 1～5年 4.77%
lilv_array[1][1][10] = 0.0504; //商贷 5-30年 5.04%
lilv_array[1][2][5] = 0.0360; //公积金 1～5年 3.60%
lilv_array[1][2][10] = 0.0405; //公积金 5-30年 4.05%

//2005年	1月的新利率
lilv_array[2] = new Array;
lilv_array[2][1] = new Array;
lilv_array[2][2] = new Array;
lilv_array[2][1][5] = 0.0495; //商贷 1～5年 4.95%
lilv_array[2][1][10] = 0.0531; //商贷 5-30年 5.31%
lilv_array[2][2][5] = 0.0378; //公积金 1～5年 3.78%
lilv_array[2][2][10] = 0.0423; //公积金 5-30年 4.23%

//2006年	1月的新利率下限
lilv_array[3] = new Array;
lilv_array[3][1] = new Array;
lilv_array[3][2] = new Array;
lilv_array[3][1][5] = 0.0527; //商贷 1～5年 5.27%
lilv_array[3][1][10] = 0.0551; //商贷 5-30年 5.51%
lilv_array[3][2][5] = 0.0396; //公积金 1～5年 3.96%
lilv_array[3][2][10] = 0.0441; //公积金 5-30年 4.41%

//2006年	1月的新利率上限
lilv_array[4] = new Array;
lilv_array[4][1] = new Array;
lilv_array[4][2] = new Array;
lilv_array[4][1][5] = 0.0527; //商贷 1～5年 5.27%
lilv_array[4][1][10] = 0.0612; //商贷 5-30年 6.12%
lilv_array[4][2][5] = 0.0396; //公积金 1～5年 3.96%
lilv_array[4][2][10] = 0.0441; //公积金 5-30年 4.41%

//2006年	4月28日的新利率下限
lilv_array[5] = new Array;
lilv_array[5][1] = new Array;
lilv_array[5][2] = new Array;
lilv_array[5][1][5] = 0.0551; //商贷 1～5年 5.51%
lilv_array[5][1][10] = 0.0575; //商贷 5-30年 5.75%
lilv_array[5][2][5] = 0.0414; //公积金 1～5年 4.14%
lilv_array[5][2][10] = 0.0459; //公积金 5-30年 4.59%

//2006年	4月28日的新利率上限
lilv_array[6] = new Array;
lilv_array[6][1] = new Array;
lilv_array[6][2] = new Array;
lilv_array[6][1][5] = 0.0612; //商贷 1～5年 6.12%
lilv_array[6][1][10] = 0.0639; //商贷 5-30年 6.39%
lilv_array[6][2][5] = 0.0414; //公积金 1～5年 4.14%
lilv_array[6][2][10] = 0.0459; //公积金 5-30年 4.59%

//2006年	8月19日的新利率下限
lilv_array[7] = new Array;
lilv_array[7][1] = new Array;
lilv_array[7][2] = new Array;
lilv_array[7][1][5] = 0.0551; //商贷 1～5年 5.51%
lilv_array[7][1][10] = 0.0581; //商贷 5-30年 5.81%
lilv_array[7][2][5] = 0.0414; //公积金 1～5年 4.14%
lilv_array[7][2][10] = 0.0459; //公积金 5-30年 4.59%

//2006年	8月19日的新利率上限
lilv_array[8] = new Array;
lilv_array[8][1] = new Array;
lilv_array[8][2] = new Array;
lilv_array[8][1][5] = 0.0648; //商贷 1～5年 6.48%
lilv_array[8][1][10] = 0.0684; //商贷 5-30年 6.84%
lilv_array[8][2][5] = 0.0414; //公积金 1～5年 4.14%
lilv_array[8][2][10] = 0.0459; //公积金 5-30年 4.59%


//2007年	3月18日的新利率下限
lilv_array[9] = new Array;
lilv_array[9][1] = new Array;
lilv_array[9][2] = new Array;
lilv_array[9][1][5] = 0.0574; //商贷 1～5年 5.74%
lilv_array[9][1][10] = 0.0604; //商贷 5-30年 6.04%
lilv_array[9][2][5] = 0.0432; //公积金 1～5年 4.32%
lilv_array[9][2][10] = 0.0477; //公积金 5-30年 4.77%

//2007年	3月18日的新利率上限
lilv_array[10] = new Array;
lilv_array[10][1] = new Array;
lilv_array[10][2] = new Array;
lilv_array[10][1][5] = 0.0675; //商贷 1～5年 6.75%
lilv_array[10][1][10] = 0.0711; //商贷 5-30年 7.11%
lilv_array[10][2][5] = 0.0432; //公积金 1～5年 4.32%
lilv_array[10][2][10] = 0.0477; //公积金 5-30年 4.77%


//2007年	5月19日的新利率下限
lilv_array[11] = new Array;
lilv_array[11][1] = new Array;
lilv_array[11][2] = new Array;
lilv_array[11][1][5] = 0.0589; //商贷 1～5年 5.89%
lilv_array[11][1][10] = 0.0612; //商贷 5-30年 6.12%
lilv_array[11][2][5] = 0.0441; //公积金 1～5年 4.41%%
lilv_array[11][2][10] = 0.0486; //公积金 5-30年 4.86%%

//2007年	5月19日的新利率上限
lilv_array[12] = new Array;
lilv_array[12][1] = new Array;
lilv_array[12][2] = new Array;
lilv_array[12][1][5] = 0.0693; //商贷 1～5年 6.93%
lilv_array[12][1][10] = 0.0720; //商贷 5-30年 7.20%
lilv_array[12][2][5] = 0.0441; //公积金 1～5年 4.41%%
lilv_array[12][2][10] = 0.0486; //公积金 5-30年 4.86%%

//2007年	7月21日的新利率下限
lilv_array[13] = new Array;
lilv_array[13][1] = new Array;
lilv_array[13][2] = new Array;
lilv_array[13][1][5] = 0.0612; //商贷 1～5年 6.12%
lilv_array[13][1][10] = 0.06273; //商贷 5-30年 6.273%
lilv_array[13][2][5] = 0.0450; //公积金 1～5年 4.50%%
lilv_array[13][2][10] = 0.0495; //公积金 5-30年 4.95%%

//2007年	7月21日的新利率上限
lilv_array[14] = new Array;
lilv_array[14][1] = new Array;
lilv_array[14][2] = new Array;
lilv_array[14][1][5] = 0.0720; //商贷 1～5年 7.20%
lilv_array[14][1][10] = 0.0738; //商贷 5-30年 7.38%
lilv_array[14][2][5] = 0.0450; //公积金 1～5年 4.50%%
lilv_array[14][2][10] = 0.0495; //公积金 5-30年 4.95%%

//2007年	8月22日的新利率下限
lilv_array[15] = new Array;
lilv_array[15][1] = new Array;
lilv_array[15][2] = new Array;
lilv_array[15][1][5] = 0.06273; //商贷 1～5年 6.273%
lilv_array[15][1][10] = 0.06426; //商贷 5-30年 6.426%
lilv_array[15][2][5] = 0.0459; //公积金 1～5年 4.59%
lilv_array[15][2][10] = 0.0504; //公积金 5-30年 5.04%

//2007年	8月22日的新利率上限
lilv_array[16] = new Array;
lilv_array[16][1] = new Array;
lilv_array[16][2] = new Array;
lilv_array[16][1][5] = 0.0738; //商贷 1～5年 7.38%
lilv_array[16][1][10] = 0.0756; //商贷 5-30年 7.56%
lilv_array[16][2][5] = 0.0459; //公积金 1～5年 4.59%
lilv_array[16][2][10] = 0.0504; //公积金 5-30年 5.04%

//2007年	9月15日的新利率下限
lilv_array[17] = new Array;
lilv_array[17][1] = new Array;
lilv_array[17][2] = new Array;
lilv_array[17][1][5] = 0.06503; //商贷 1～5年 6.503%
lilv_array[17][1][10] = 0.06656; //商贷 5-30年 6.656%
lilv_array[17][2][5] = 0.0477; //公积金 1～5年 4.77%
lilv_array[17][2][10] = 0.0522; //公积金 5-30年 5.22%

//2007年	9月15日的新利率上限
lilv_array[18] = new Array;
lilv_array[18][1] = new Array;
lilv_array[18][2] = new Array;
lilv_array[18][1][5] = 0.0765; //商贷 1～5年 7.65%
lilv_array[18][1][10] = 0.0783; //商贷 5-30年 7.83%
lilv_array[18][2][5] = 0.0477; //公积金 1～5年 4.77%
lilv_array[18][2][10] = 0.0522; //公积金 5-30年 5.22%

//2007年	9月15日新利率(第二套房)
lilv_array[19] = new Array;
lilv_array[19][1] = new Array;
lilv_array[19][2] = new Array;
lilv_array[19][1][5] = 0.08415; //商贷 1～5年 8.415%
lilv_array[19][1][10] = 0.08613; //商贷 5-30年 8.613%
lilv_array[19][2][5] = 0.0477; //公积金 1～5年 4.77%
lilv_array[19][2][10] = 0.0522; //公积金 5-30年 5.22%


//2007年	12月21日的新利率下限
lilv_array[20] = new Array;
lilv_array[20][1] = new Array;
lilv_array[20][2] = new Array;
lilv_array[20][1][5] = 0.06579; //商贷 1～5年 6.579%
lilv_array[20][1][10] = 0.06656; //商贷 5-30年 6.656%
lilv_array[20][2][5] = 0.0477; //公积金 1～5年 4.77%
lilv_array[20][2][10] = 0.0522; //公积金 5-30年 5.22%

//2007年	12月21日的新利率上限
lilv_array[21] = new Array;
lilv_array[21][1] = new Array;
lilv_array[21][2] = new Array;
lilv_array[21][1][5] = 0.0774; //商贷 1～5年 7.74%
lilv_array[21][1][10] = 0.0783; //商贷 5-30年 7.83%
lilv_array[21][2][5] = 0.0477; //公积金 1～5年 4.77%
lilv_array[21][2][10] = 0.0522; //公积金 5-30年 5.22%

//2007年	12月21日新利率(第二套房)
lilv_array[22] = new Array;
lilv_array[22][1] = new Array;
lilv_array[22][2] = new Array;
lilv_array[22][1][5] = 0.08514; //商贷 1～5年 8.514%
lilv_array[22][1][10] = 0.08613; //商贷 5-30年 8.613%
lilv_array[22][2][5] = 0.0477; //公积金 1～5年 4.77%
lilv_array[22][2][10] = 0.0522; //公积金 5-30年 5.22%

//2008年	9月16日的新利率下限
lilv_array[23] = new Array;
lilv_array[23][1] = new Array;
lilv_array[23][2] = new Array;
lilv_array[23][1][5] = 0.06426; //商贷 1～5年 6.426%
lilv_array[23][1][10] = 0.06579; //商贷 5-30年 6.579%
lilv_array[23][2][5] = 0.0459; //公积金 1～5年 4.59%
lilv_array[23][2][10] = 0.0513; //公积金 5-30年 5.13%

//2008年	9月16日的新利率上限
lilv_array[24] = new Array;
lilv_array[24][1] = new Array;
lilv_array[24][2] = new Array;
lilv_array[24][1][5] = 0.0756; //商贷 1～5年 7.56%
lilv_array[24][1][10] = 0.0774; //商贷 5-30年 7.74%
lilv_array[24][2][5] = 0.0459; //公积金 1～5年 4.59%
lilv_array[24][2][10] = 0.0513; //公积金 5-30年 5.13%

//2008年	9月16日新利率(第二套房)
lilv_array[25] = new Array;
lilv_array[25][1] = new Array;
lilv_array[25][2] = new Array;
lilv_array[25][1][5] = 0.08316; //商贷 1～5年 8.316%
lilv_array[25][1][10] = 0.08514; //商贷 5-30年 8.514%
lilv_array[25][2][5] = 0.0459; //公积金 1～5年 4.59%
lilv_array[25][2][10] = 0.0513; //公积金 5-30年 5.13%

//2008年	10月9日的新利率下限
lilv_array[26] = new Array;
lilv_array[26][1] = new Array;
lilv_array[26][2] = new Array;
lilv_array[26][1][5] = 0.061965; //商贷 1～5年 6.1965%
lilv_array[26][1][10] = 0.063495; //商贷 5-30年 6.3495%
lilv_array[26][2][5] = 0.0432; //公积金 1～5年 4.32%
lilv_array[26][2][10] = 0.0486; //公积金 5-30年 4.86%

//2008年	10月9日的新利率上限
lilv_array[27] = new Array;
lilv_array[27][1] = new Array;
lilv_array[27][2] = new Array;
lilv_array[27][1][5] = 0.0729; //商贷 1～5年 7.29%
lilv_array[27][1][10] = 0.0747; //商贷 5-30年 7.47%
lilv_array[27][2][5] = 0.0432; //公积金 1～5年 4.32%
lilv_array[27][2][10] = 0.0486; //公积金 5-30年 4.86%

//2008年	10月9日新利率(第二套房)
lilv_array[28] = new Array;
lilv_array[28][1] = new Array;
lilv_array[28][2] = new Array;
lilv_array[28][1][5] = 0.08019; //商贷 1～5年 8.019%
lilv_array[28][1][10] = 0.08217; //商贷 5-30年 8.217%
lilv_array[28][2][5] = 0.0432; //公积金 1～5年 4.32%
lilv_array[28][2][10] = 0.0486; //公积金 5-30年 4.86%


//2008年	10月30日基准利率
lilv_array[30] = new Array;
lilv_array[30][1] = new Array;
lilv_array[30][2] = new Array;
lilv_array[30][1][5] = 0.0702; //商贷 1～5年 7.02%
lilv_array[30][1][10] = 0.072; //商贷 5-30年 7.20%
lilv_array[30][2][5] = 0.0405; //公积金 1～5年 4.05%
lilv_array[30][2][10] = 0.0459; //公积金 5-30年 4.59%

//2008年	11月27日基准利率
lilv_array[31] = new Array;
lilv_array[31][1] = new Array;
lilv_array[31][2] = new Array;
lilv_array[31][1][5] = 0.0594; //商贷 1～5年 5.94%
lilv_array[31][1][10] = 0.0612; //商贷 5-30年 6.12%
lilv_array[31][2][5] = 0.0351; //公积金 1～5年 3.51%
lilv_array[31][2][10] = 0.0405; //公积金 5-30年 4.05%

//2008年	12月23日利率下限(7折)
lilv_array[32] = new Array;
lilv_array[32][1] = new Array;
lilv_array[32][2] = new Array;
lilv_array[32][1][5] = 0.0576 * 0.7; //商贷 1～5年 5.76%    
lilv_array[32][1][10] = 0.0594 * 0.7; //商贷 5-30年 5.94%   
lilv_array[32][2][5] = 0.0333; //公积金 1～5年 3.33%  
lilv_array[32][2][10] = 0.0387; //公积金 5-30年 3.87% 

//2008年	12月23日利率下限(85折)
lilv_array[33] = new Array;
lilv_array[33][1] = new Array;
lilv_array[33][2] = new Array;
lilv_array[33][1][5] = 0.0576 * 0.85; //商贷 1～5年 5.76%    
lilv_array[33][1][10] = 0.0594 * 0.85; //商贷 5-30年 5.94%   
lilv_array[33][2][5] = 0.0333; //公积金 1～5年 3.33%  
lilv_array[33][2][10] = 0.0387; //公积金 5-30年 3.87% 

//2008年	12月23日基准利率
lilv_array[34] = new Array;
lilv_array[34][1] = new Array;
lilv_array[34][2] = new Array;
lilv_array[34][1][5] = 0.0576; //商贷 1～5年 5.76%
lilv_array[34][1][10] = 0.0594; //商贷 5-30年 5.94%
lilv_array[34][2][5] = 0.0333; //公积金 1～5年 3.33%
lilv_array[34][2][10] = 0.0387; //公积金 5-30年 3.87%

//2008年	12月23日利率上限(1.1倍)
lilv_array[35] = new Array;
lilv_array[35][1] = new Array;
lilv_array[35][2] = new Array;
lilv_array[35][1][5] = 0.0576 * 1.1; //商贷 1～5年 5.76%    
lilv_array[35][1][10] = 0.0594 * 1.1; //商贷 5-30年 5.94%   
lilv_array[35][2][5] = 0.0333 * 1.1; //公积金 1～5年 3.33%  
lilv_array[35][2][10] = 0.0387 * 1.1; //公积金 5-30年 3.87% 


//2010年	10月20日利率下限(7折)
lilv_array[36] = new Array;
lilv_array[36][1] = new Array;
lilv_array[36][2] = new Array;
lilv_array[36][1][1] = 0.0556 * 0.7;
lilv_array[36][1][3] = 0.0560 * 0.7;
lilv_array[36][1][5] = 0.0596 * 0.7;
lilv_array[36][1][10] = 0.0614 * 0.7;
lilv_array[36][2][5] = 0.0350;
lilv_array[36][2][10] = 0.0405;

//2010年	10月20日利率下限(85折)
lilv_array[37] = new Array;
lilv_array[37][1] = new Array;
lilv_array[37][2] = new Array;
lilv_array[37][1][1] = 0.0556 * 0.85;
lilv_array[37][1][3] = 0.0560 * 0.85;
lilv_array[37][1][5] = 0.0596 * 0.85;
lilv_array[37][1][10] = 0.0614 * 0.85;
lilv_array[37][2][5] = 0.0350;
lilv_array[37][2][10] = 0.0405;

//2010年	10月20日基准利率
lilv_array[38] = new Array;
lilv_array[38][1] = new Array;
lilv_array[38][2] = new Array;
lilv_array[38][1][1] = 0.0556; //商贷 1年 5.56%
lilv_array[38][1][3] = 0.0560; //商贷 1～3年 5.60%
lilv_array[38][1][5] = 0.0596; //商贷 3～5年 5.96%
lilv_array[38][1][10] = 0.0614; //商贷 5-30年 6.14%
lilv_array[38][2][5] = 0.0350; //公积金 1～5年 3.50%
lilv_array[38][2][10] = 0.0405; //公积金 5-30年 4.05%

//2010年	10月20日利率上限(1.1倍)
lilv_array[39] = new Array;
lilv_array[39][1] = new Array;
lilv_array[39][2] = new Array;
lilv_array[39][1][1] = 0.0556 * 1.1;
lilv_array[39][1][3] = 0.0560 * 1.1;
lilv_array[39][1][5] = 0.0596 * 1.1;
lilv_array[39][1][10] = 0.0614 * 1.1;
lilv_array[39][2][5] = 0.0350 * 1.1;
lilv_array[39][2][10] = 0.0405 * 1.1;

//2010年	12月26日利率下限(7折)
lilv_array[40] = new Array;
lilv_array[40][1] = new Array;
lilv_array[40][2] = new Array;
lilv_array[40][1][1] = 0.0581 * 0.7;
lilv_array[40][1][3] = 0.0585 * 0.7;
lilv_array[40][1][5] = 0.0622 * 0.7;
lilv_array[40][1][10] = 0.0640 * 0.7;
lilv_array[40][2][5] = 0.0375;
lilv_array[40][2][10] = 0.0430;

//2010年	12月26日利率下限(85折)
lilv_array[41] = new Array;
lilv_array[41][1] = new Array;
lilv_array[41][2] = new Array;
lilv_array[41][1][1] = 0.0581 * 0.85;
lilv_array[41][1][3] = 0.0585 * 0.85;
lilv_array[41][1][5] = 0.0622 * 0.85;
lilv_array[41][1][10] = 0.0640 * 0.85;
lilv_array[41][2][5] = 0.0375;
lilv_array[41][2][10] = 0.0430;

//2010年	12月26日基准利率
lilv_array[42] = new Array;
lilv_array[42][1] = new Array;
lilv_array[42][2] = new Array;
lilv_array[42][1][1] = 0.0581;
lilv_array[42][1][3] = 0.0585; //商贷 1～3年 5.85%
lilv_array[42][1][5] = 0.0622; //商贷 3～5年 5.96%
lilv_array[42][1][10] = 0.0640; //商贷 5-30年 6.14%
lilv_array[42][2][5] = 0.0375; //公积金 1～5年 3.50%
lilv_array[42][2][10] = 0.0430; //公积金 5-30年 4.05%

//2010年	12月26日利率上限(1.1倍)
lilv_array[43] = new Array;
lilv_array[43][1] = new Array;
lilv_array[43][2] = new Array;
lilv_array[43][1][1] = 0.0581 * 1.1;
lilv_array[43][1][3] = 0.0585 * 1.1;
lilv_array[43][1][5] = 0.0622 * 1.1;
lilv_array[43][1][10] = 0.0640 * 1.1;
lilv_array[43][2][5] = 0.0375 * 1.1;
lilv_array[43][2][10] = 0.0430 * 1.1;

//2010年	12月26日利率上限(1.2倍)
lilv_array[44] = new Array;
lilv_array[44][1] = new Array;
lilv_array[44][2] = new Array;
lilv_array[44][1][1] = 0.0581 * 1.2;
lilv_array[44][1][3] = 0.0585 * 1.2;
lilv_array[44][1][5] = 0.0622 * 1.2;
lilv_array[44][1][10] = 0.0640 * 1.2;
lilv_array[44][2][5] = 0.0375;
lilv_array[44][2][10] = 0.0430;

//2011年	02月09日利率下限(7折)
lilv_array[45] = new Array;
lilv_array[45][1] = new Array;
lilv_array[45][2] = new Array;
lilv_array[45][1][1] = 0.0606 * 0.7;
lilv_array[45][1][3] = 0.0610 * 0.7;
lilv_array[45][1][5] = 0.0645 * 0.7;
lilv_array[45][1][10] = 0.0660 * 0.7;
lilv_array[45][2][5] = 0.0400;
lilv_array[45][2][10] = 0.0450;

//2011年	02月09日利率下限(85折)
lilv_array[46] = new Array;
lilv_array[46][1] = new Array;
lilv_array[46][2] = new Array;
lilv_array[46][1][1] = 0.0606 * 0.85;
lilv_array[46][1][3] = 0.0610 * 0.85;
lilv_array[46][1][5] = 0.0645 * 0.85;
lilv_array[46][1][10] = 0.0660 * 0.85;
lilv_array[46][2][5] = 0.0400;
lilv_array[46][2][10] = 0.0450;

//2011年	02月09日基准利率
lilv_array[47] = new Array;
lilv_array[47][1] = new Array;
lilv_array[47][2] = new Array;
lilv_array[47][1][1] = 0.0606;
lilv_array[47][1][3] = 0.0610; //商贷 1～3年 5.60%
lilv_array[47][1][5] = 0.0645; //商贷 3～5年 5.96%
lilv_array[47][1][10] = 0.0660; //商贷 5-30年 6.14%
lilv_array[47][2][5] = 0.0400; //公积金 1～5年 3.50%
lilv_array[47][2][10] = 0.0450; //公积金 5-30年 4.05%

//2011年	02月09日利率上限(1.1倍)
lilv_array[48] = new Array;
lilv_array[48][1] = new Array;
lilv_array[48][2] = new Array;
lilv_array[48][1][1] = 0.0606 * 1.1;
lilv_array[48][1][3] = 0.0610 * 1.1;
lilv_array[48][1][5] = 0.0645 * 1.1;
lilv_array[48][1][10] = 0.0660 * 1.1;
lilv_array[48][2][5] = 0.0400 * 1.1;
lilv_array[48][2][10] = 0.0450 * 1.1;

//2011年	02月09日利率上限(1.2倍)
lilv_array[49] = new Array;
lilv_array[49][1] = new Array;
lilv_array[49][2] = new Array;
lilv_array[49][1][1] = 0.0606 * 1.2;
lilv_array[49][1][3] = 0.0610 * 1.2;
lilv_array[49][1][5] = 0.0645 * 1.2;
lilv_array[49][1][10] = 0.0660 * 1.2;
lilv_array[49][2][5] = 0.0400;
lilv_array[49][2][10] = 0.0450;

//2011年	04月06日利率下限(7折)
lilv_array[50] = new Array;
lilv_array[50][1] = new Array;
lilv_array[50][2] = new Array;
lilv_array[50][1][1] = 0.0631 * 0.7;
lilv_array[50][1][3] = 0.0640 * 0.7;
lilv_array[50][1][5] = 0.0665 * 0.7;
lilv_array[50][1][10] = 0.0680 * 0.7;
lilv_array[50][2][5] = 0.0420;
lilv_array[50][2][10] = 0.0470;

//2011年	04月06日利率下限(85折)
lilv_array[51] = new Array;
lilv_array[51][1] = new Array;
lilv_array[51][2] = new Array;
lilv_array[51][1][1] = 0.0631 * 0.85;
lilv_array[51][1][3] = 0.0640 * 0.85;
lilv_array[51][1][5] = 0.0665 * 0.85;
lilv_array[51][1][10] = 0.0680 * 0.85;
lilv_array[51][2][5] = 0.0420;
lilv_array[51][2][10] = 0.0470;

//2011年	04月06日基准利率
lilv_array[52] = new Array;
lilv_array[52][1] = new Array;
lilv_array[52][2] = new Array;
lilv_array[52][1][1] = 0.0631;
lilv_array[52][1][3] = 0.0640; //商贷 1～3年 5.60%
lilv_array[52][1][5] = 0.0665; //商贷 3～5年 5.96%
lilv_array[52][1][10] = 0.0680; //商贷 5-30年 6.14%
lilv_array[52][2][5] = 0.0420; //公积金 1～5年 3.50%
lilv_array[52][2][10] = 0.0470; //公积金 5-30年 4.05%

//2011年	04月06日利率上限(1.1倍)
lilv_array[53] = new Array;
lilv_array[53][1] = new Array;
lilv_array[53][2] = new Array;
lilv_array[53][1][1] = 0.0631 * 1.1;
lilv_array[53][1][3] = 0.0640 * 1.1;
lilv_array[53][1][5] = 0.0665 * 1.1;
lilv_array[53][1][10] = 0.0680 * 1.1;
lilv_array[53][2][5] = 0.0420 * 1.1;
lilv_array[53][2][10] = 0.0470 * 1.1;

//2011年	04月06日利率上限(1.2倍)
lilv_array[54] = new Array;
lilv_array[54][1] = new Array;
lilv_array[54][2] = new Array;
lilv_array[54][1][1] = 0.0631 * 1.2;
lilv_array[54][1][3] = 0.0640 * 1.2;
lilv_array[54][1][5] = 0.0665 * 1.2;
lilv_array[54][1][10] = 0.0680 * 1.2;
lilv_array[54][2][5] = 0.0420;
lilv_array[54][2][10] = 0.0470;


//2011年	07月07日利率下限(7折)
lilv_array[55] = new Array;
lilv_array[55][1] = new Array;
lilv_array[55][2] = new Array;
lilv_array[55][1][1] = 0.0656 * 0.7;
lilv_array[55][1][3] = 0.0665 * 0.7;
lilv_array[55][1][5] = 0.0690 * 0.7;
lilv_array[55][1][10] = 0.0705 * 0.7;
lilv_array[55][2][5] = 0.0445;
lilv_array[55][2][10] = 0.0490;

//2011年	07月07日利率下限(85折)
lilv_array[56] = new Array;
lilv_array[56][1] = new Array;
lilv_array[56][2] = new Array;
lilv_array[56][1][1] = 0.0656 * 0.85;
lilv_array[56][1][3] = 0.0665 * 0.85;
lilv_array[56][1][5] = 0.0690 * 0.85;
lilv_array[56][1][10] = 0.0705 * 0.85;
lilv_array[56][2][5] = 0.0445;
lilv_array[56][2][10] = 0.0490;

//2011年	07月07日利率下限(9折)
lilv_array[57] = new Array;
lilv_array[57][1] = new Array;
lilv_array[57][2] = new Array;
lilv_array[57][1][1] = 0.0656 * 0.9;
lilv_array[57][1][3] = 0.0665 * 0.9;
lilv_array[57][1][5] = 0.0690 * 0.9;
lilv_array[57][1][10] = 0.0705 * 0.9;
lilv_array[57][2][5] = 0.0445;
lilv_array[57][2][10] = 0.0490;

//2011年	07月07日基准利率
lilv_array[58] = new Array;
lilv_array[58][1] = new Array;
lilv_array[58][2] = new Array;
lilv_array[58][1][1] = 0.0656;
lilv_array[58][1][3] = 0.0665; //商贷 1～3年 5.60%
lilv_array[58][1][5] = 0.0690; //商贷 3～5年 5.96%
lilv_array[58][1][10] = 0.0705; //商贷 5-30年 6.14%
lilv_array[58][2][5] = 0.0445; //公积金 1～5年 3.50%
lilv_array[58][2][10] = 0.0490; //公积金 5-30年 4.05%

//2011年	07月07日利率上限(1.05倍)
lilv_array[59] = new Array;
lilv_array[59][1] = new Array;
lilv_array[59][2] = new Array;
lilv_array[59][1][1] = 0.0656 * 1.05;
lilv_array[59][1][3] = 0.0665 * 1.05;
lilv_array[59][1][5] = 0.0690 * 1.05;
lilv_array[59][1][10] = 0.0705 * 1.05;
lilv_array[59][2][5] = 0.0445 * 1.05;
lilv_array[59][2][10] = 0.0490 * 1.05;

//2011年	07月07日利率上限(1.1倍)
lilv_array[60] = new Array;
lilv_array[60][1] = new Array;
lilv_array[60][2] = new Array;
lilv_array[60][1][1] = 0.0656 * 1.1;
lilv_array[60][1][3] = 0.0665 * 1.1;
lilv_array[60][1][5] = 0.0690 * 1.1;
lilv_array[60][1][10] = 0.0705 * 1.1;
lilv_array[60][2][5] = 0.0445 * 1.1;
lilv_array[60][2][10] = 0.0490 * 1.1;

//2011年	07月07日利率上限(1.2倍)
lilv_array[61] = new Array;
lilv_array[61][1] = new Array;
lilv_array[61][2] = new Array;
lilv_array[61][1][1] = 0.0656 * 1.2;
lilv_array[61][1][3] = 0.0665 * 1.2;
lilv_array[61][1][5] = 0.0690 * 1.2;
lilv_array[61][1][10] = 0.0705 * 1.2;
lilv_array[61][2][5] = 0.0445 * 1.2;
lilv_array[61][2][10] = 0.0490 * 1.2;

//2012年	06月08日利率下限(7折)
lilv_array[62] = new Array;
lilv_array[62][1] = new Array;
lilv_array[62][2] = new Array;
lilv_array[62][1][1] = 0.0631 * 0.7;
lilv_array[62][1][3] = 0.0640 * 0.7;
lilv_array[62][1][5] = 0.0665 * 0.7;
lilv_array[62][1][10] = 0.0680 * 0.7;
lilv_array[62][2][5] = 0.0420;
lilv_array[62][2][10] = 0.0470;

//2012年	06月08日利率下限(85折)
lilv_array[63] = new Array;
lilv_array[63][1] = new Array;
lilv_array[63][2] = new Array;
lilv_array[63][1][1] = 0.0631 * 0.85;
lilv_array[63][1][3] = 0.0640 * 0.85;
lilv_array[63][1][5] = 0.0665 * 0.85;
lilv_array[63][1][10] = 0.0680 * 0.85;
lilv_array[63][2][5] = 0.0420;
lilv_array[63][2][10] = 0.0470;

//2012年	06月08日利率下限(9折)
lilv_array[64] = new Array;
lilv_array[64][1] = new Array;
lilv_array[64][2] = new Array;
lilv_array[64][1][1] = 0.0631 * 0.9;
lilv_array[64][1][3] = 0.0640 * 0.9;
lilv_array[64][1][5] = 0.0665 * 0.9;
lilv_array[64][1][10] = 0.0680 * 0.9;
lilv_array[64][2][5] = 0.0420;
lilv_array[64][2][10] = 0.0470;

//2012年	06月08日基准利率
lilv_array[65] = new Array;
lilv_array[65][1] = new Array;
lilv_array[65][2] = new Array;
lilv_array[65][1][1] = 0.0631;
lilv_array[65][1][3] = 0.0640; //商贷 1～3年 5.60%
lilv_array[65][1][5] = 0.0665; //商贷 3～5年 5.96%
lilv_array[65][1][10] = 0.0680; //商贷 5-30年 6.14%
lilv_array[65][2][5] = 0.0420; //公积金 1～5年 3.50%
lilv_array[65][2][10] = 0.0470; //公积金 5-30年 4.05%

//2012年	06月08日利率上限(1.05倍)
lilv_array[66] = new Array;
lilv_array[66][1] = new Array;
lilv_array[66][2] = new Array;
lilv_array[66][1][1] = 0.0631 * 1.05;
lilv_array[66][1][3] = 0.0640 * 1.05;
lilv_array[66][1][5] = 0.0665 * 1.05;
lilv_array[66][1][10] = 0.0680 * 1.05;
lilv_array[66][2][5] = 0.0420 * 1.05;
lilv_array[66][2][10] = 0.0470 * 1.05;

//2012年	06月08日利率上限(1.1倍)
lilv_array[67] = new Array;
lilv_array[67][1] = new Array;
lilv_array[67][2] = new Array;
lilv_array[67][1][1] = 0.0631 * 1.1;
lilv_array[67][1][3] = 0.0640 * 1.1;
lilv_array[67][1][5] = 0.0665 * 1.1;
lilv_array[67][1][10] = 0.0680 * 1.1;
lilv_array[67][2][5] = 0.0420 * 1.1;
lilv_array[67][2][10] = 0.0470 * 1.1;

//2012年	06月08日利率上限(1.2倍)
lilv_array[68] = new Array;
lilv_array[68][1] = new Array;
lilv_array[68][2] = new Array;
lilv_array[68][1][1] = 0.0631 * 1.2;
lilv_array[68][1][3] = 0.0640 * 1.2;
lilv_array[68][1][5] = 0.0665 * 1.2;
lilv_array[68][1][10] = 0.0680 * 1.2;
lilv_array[68][2][5] = 0.0420 * 1.2;
lilv_array[68][2][10] = 0.0470 * 1.2;


//2012年	07月06日利率下限(7折)
lilv_array[69] = new Array;
lilv_array[69][1] = new Array;
lilv_array[69][2] = new Array;
lilv_array[69][1][1] = 0.0600 * 0.7;
lilv_array[69][1][3] = 0.0615 * 0.7;
lilv_array[69][1][5] = 0.0640 * 0.7;
lilv_array[69][1][10] = 0.0655 * 0.7;
lilv_array[69][2][5] = 0.0400;
lilv_array[69][2][10] = 0.0450;

//2012年	07月06日利率下限(85折)
lilv_array[70] = new Array;
lilv_array[70][1] = new Array;
lilv_array[70][2] = new Array;
lilv_array[70][1][1] = 0.0600 * 0.85;
lilv_array[70][1][3] = 0.0615 * 0.85;
lilv_array[70][1][5] = 0.0640 * 0.85;
lilv_array[70][1][10] = 0.0655 * 0.85;
lilv_array[70][2][5] = 0.0400;
lilv_array[70][2][10] = 0.0450;

//2012年	07月06日利率下限(9折)
lilv_array[71] = new Array;
lilv_array[71][1] = new Array;
lilv_array[71][2] = new Array;
lilv_array[71][1][1] = 0.0600 * 0.9;
lilv_array[71][1][3] = 0.0615 * 0.9;
lilv_array[71][1][5] = 0.0640 * 0.9;
lilv_array[71][1][10] = 0.0655 * 0.9;
lilv_array[71][2][5] = 0.0400;
lilv_array[71][2][10] = 0.0450;

//2012年	07月06日基准利率
lilv_array[72] = new Array;
lilv_array[72][1] = new Array;
lilv_array[72][2] = new Array;
lilv_array[72][1][1] = 0.0600;
lilv_array[72][1][3] = 0.0615; //商贷 1～3年 5.60%
lilv_array[72][1][5] = 0.0640; //商贷 3～5年 5.96%
lilv_array[72][1][10] = 0.0655; //商贷 5-30年 6.14%
lilv_array[72][2][5] = 0.0400; //公积金 1～5年 3.50%
lilv_array[72][2][10] = 0.0450; //公积金 5-30年 4.05%

//2012年	07月06日利率上限(1.05倍)
lilv_array[73] = new Array;
lilv_array[73][1] = new Array;
lilv_array[73][2] = new Array;
lilv_array[73][1][1] = 0.0600 * 1.05;
lilv_array[73][1][3] = 0.0615 * 1.05;
lilv_array[73][1][5] = 0.0640 * 1.05;
lilv_array[73][1][10] = 0.0655 * 1.05;
lilv_array[73][2][5] = 0.0400 * 1.05;
lilv_array[73][2][10] = 0.0450 * 1.05;

//2012年	07月06日利率上限(1.1倍)
lilv_array[74] = new Array;
lilv_array[74][1] = new Array;
lilv_array[74][2] = new Array;
lilv_array[74][1][1] = 0.0600 * 1.1;
lilv_array[74][1][3] = 0.0615 * 1.1;
lilv_array[74][1][5] = 0.0640 * 1.1;
lilv_array[74][1][10] = 0.0655 * 1.1;
lilv_array[74][2][5] = 0.0400 * 1.1;
lilv_array[74][2][10] = 0.0450 * 1.1;

//2012年	07月06日利率上限(1.2倍)
lilv_array[75] = new Array;
lilv_array[75][1] = new Array;
lilv_array[75][2] = new Array;
lilv_array[75][1][1] = 0.0600 * 1.2;
lilv_array[75][1][3] = 0.0615 * 1.2;
lilv_array[75][1][5] = 0.0640 * 1.2;
lilv_array[75][1][10] = 0.0655 * 1.2;
lilv_array[75][2][5] = 0.0400 * 1.2;
lilv_array[75][2][10] = 0.0450 * 1.2;


//2014年	11月22日利率下限(7折)
lilv_array[76] = new Array;
lilv_array[76][1] = new Array;
lilv_array[76][2] = new Array;
lilv_array[76][1][1] = 0.0560 * 0.7;
lilv_array[76][1][3] = 0.0600 * 0.7;
lilv_array[76][1][5] = 0.0600 * 0.7;
lilv_array[76][1][10] = 0.0615 * 0.7;
lilv_array[76][2][5] = 0.0375;
lilv_array[76][2][10] = 0.0425;

//2014年	11月22日利率下限(85折)
lilv_array[77] = new Array;
lilv_array[77][1] = new Array;
lilv_array[77][2] = new Array;
lilv_array[77][1][1] = 0.0560 * 0.85;
lilv_array[77][1][3] = 0.0600 * 0.85;
lilv_array[77][1][5] = 0.0600 * 0.85;
lilv_array[77][1][10] = 0.0615 * 0.85;
lilv_array[77][2][5] = 0.0375;
lilv_array[77][2][10] = 0.0425;

//2014年	11月22日利率下限(9折)
lilv_array[78] = new Array;
lilv_array[78][1] = new Array;
lilv_array[78][2] = new Array;
lilv_array[78][1][1] = 0.0560 * 0.9;
lilv_array[78][1][3] = 0.0600 * 0.9;
lilv_array[78][1][5] = 0.0600 * 0.9;
lilv_array[78][1][10] = 0.0615 * 0.9;
lilv_array[78][2][5] = 0.0375;
lilv_array[78][2][10] = 0.0425;

//2014年	11月22日基准利率
lilv_array[79] = new Array;
lilv_array[79][1] = new Array;
lilv_array[79][2] = new Array;
lilv_array[79][1][1] = 0.0560;
lilv_array[79][1][3] = 0.0600; //商贷 1～3年 5.60%
lilv_array[79][1][5] = 0.0600; //商贷 3～5年 5.96%
lilv_array[79][1][10] = 0.0615; //商贷 5-30年 6.14%
lilv_array[79][2][5] = 0.0375; //公积金 1～5年 3.50%
lilv_array[79][2][10] = 0.0425; //公积金 5-30年 4.05%

//2014年	11月22日利率上限(1.05倍)
lilv_array[80] = new Array;
lilv_array[80][1] = new Array;
lilv_array[80][2] = new Array;
lilv_array[80][1][1] = 0.0560 * 1.05;
lilv_array[80][1][3] = 0.0600 * 1.05;
lilv_array[80][1][5] = 0.0600 * 1.05;
lilv_array[80][1][10] = 0.0615 * 1.05;
lilv_array[80][2][5] = 0.0375 * 1.05;
lilv_array[80][2][10] = 0.0425 * 1.05;

//2014年	11月22日利率上限(1.1倍)
lilv_array[81] = new Array;
lilv_array[81][1] = new Array;
lilv_array[81][2] = new Array;
lilv_array[81][1][1] = 0.0560 * 1.1;
lilv_array[81][1][3] = 0.0600 * 1.1;
lilv_array[81][1][5] = 0.0600 * 1.1;
lilv_array[81][1][10] = 0.0615 * 1.1;
lilv_array[81][2][5] = 0.0375 * 1.1;
lilv_array[81][2][10] = 0.0425 * 1.1;

//2014年	11月22日利率上限(1.2倍)
lilv_array[82] = new Array;
lilv_array[82][1] = new Array;
lilv_array[82][2] = new Array;
lilv_array[82][1][1] = 0.0560 * 1.2;
lilv_array[82][1][3] = 0.0600 * 1.2;
lilv_array[82][1][5] = 0.0600 * 1.2;
lilv_array[82][1][10] = 0.0615 * 1.2;
lilv_array[82][2][5] = 0.0375 * 1.2;
lilv_array[82][2][10] = 0.0425 * 1.2;

var l6_30 = lilv_array[72][2][10];
var l1_5 = lilv_array[72][2][5];

function gjjloan1(obj)
{
  var gryjce;//住房公积金个人月缴存额
  var poyjce;//配偶住房公积金个人月缴存额
  var grjcbl;//缴存比例
  var pojcbl;//配偶缴存比例
  var xy;//个人信用
  var fwzj;//房屋总价
  var fwxz;//房屋性质
  var dknx;//贷款申请年限
  var syhk;//首月还款额

  var dked;//需要贷款额度
  var hkfs;//还款方式
  var bxhj;//本息合计
  var bxhj2;//本息合计等本金

//中间变量
 var gbl;
 var jtysr;//家庭月收入
 var r;//月还款
 var gjjdka;//住房公积金贷款额度a
 var gjjdkb;//住房公积金贷款额度b
 var gjjdkc;//住房公积金贷款额度c

 gryjce=obj.mount2.value;
 if(gryjce<=0){
    alert('住房公积金个人月缴存额不能为空,请输入');
    obj.mount2.value='';
    obj.mount2.focus();
    return;
 }

 poyjce=obj.mount3.value;
 if(poyjce.length>0 && !isnumeric(poyjce))
{alert("配偶月缴存额录入不正确");return;}

if (obj.jcbl.value=="" || !isnumeric(obj.jcbl.value) || Number(obj.jcbl.value)<=0 ||Number(obj.jcbl.value)>=100)
{
	alert("缴存比例不正确");return;
}
if (poyjce.length>0 &&(obj.p_bl.value=="" || !isnumeric(obj.p_bl.value) || Number(obj.p_bl.value)<=0||Number(obj.p_bl.value)>=100) )
{
	alert("配偶缴存比例不正确");return;
}
grjcbl=obj.jcbl.value/100;
pojcbl=obj.p_bl.value/100;
/*
if (obj.xz[0].checked==true){fwxz=0.9;}
else {fwxz=0.95;}
*/
if (obj.xz[0].checked==true){fwxz=0.9;}
else {fwxz=0.8;}

if (obj.xy[0].checked==true){xy=1.3;}
else if(obj.xy[1].checked==true){xy=1.15;}
else {xy=1;}

fwzj=obj.mount.value*10000;
if(!isnumeric(fwzj)){
    alert('＂房屋评估价值或实际购房款＂不能为空,请输入');
    obj.mount.value='';
    return;
}

dknx=Math.round(obj.mount10.value);

if(!isnumeric(dknx)){
    alert('贷款申请年限不能为空,请输入');
    obj.mount10.value='';
    return;
}

if(dknx>30){
    alert('贷款申请年限不能大于30年,请重新输入');
    obj.mount10.value='';
    return;
}

var bcv=0;
if (dknx>5){
  bcv=Math.round( 1000000 * l6_30/12 ) / 1000000;
}else{
  bcv=Math.round( 1000000 * l1_5/12 ) / 1000000;
}
r=adv_format((10000*bcv*Math.pow(1+bcv,12*dknx))/(Math.pow(1+bcv,12*dknx)-1),2);
//r=Math.round((10000*bcv*Math.pow(1+bcv,12*dknx))/(Math.pow(1+bcv,12*dknx)-1));


if(poyjce.length>0)
{
	jtysr=Math.ceil((gryjce/grjcbl+poyjce/pojcbl)*10)/10;
}
else
{
	jtysr=Math.ceil((gryjce/grjcbl)*10)/10;
}

if(jtysr<=400){
    alert('家庭月收入低于400，不符合贷款条件');
    return;
}

gjjdka=Math.min(Math.round((jtysr-400)/r*10000*10)/10,600000);
gjjdkb=Math.round(gjjdka*xy*10)/10;
gjjdkc=Math.round(fwzj*fwxz*10)/10;
//obj.ze2.value=gjjdka; //jtysr;
obj.ze2.value=Math.floor(Math.min(gjjdkb,gjjdkc)/10000*10)/10;
zgdk=obj.ze2.value; //最高贷款额度




}



function gjjloan2(obj)
{

  var gryjce;//住房公积金个人月缴存额
  var poyjce;//配偶住房公积金个人月缴存额
  var grjcbl;//缴存比例
  var pojcbl;//配偶缴存比例
  var xy; //个人信用
  var fwzj;//房屋总价
  var fwxz;//房屋性质
  var dknx;//贷款申请年限
  var syhk; //首月还款额

  var dked;//需要贷款额度
  var hkfs;//还款方式
  var bxhj; //本息合计
  var bxhj2; //本息合计等本金

//中间变量
 var gbl;
 var jtysr; //家庭月收入
 var r;//月还款
 var rb;
 var gjjdka;//住房公积金贷款额度a
 var gjjdkb;//住房公积金贷款额度b
 var gjjdkc;//住房公积金贷款额度c


 gryjce=obj.mount2.value;
if(gryjce<=0){
	alert('住房公积金个人月缴存额不能为空,请输入.');
         obj.mount2.value='';
         obj.mount.focus();
         return;
}

 poyjce=obj.mount3.value;
/*if (obj.jcbl[0].checked==true)
{grjcbl=0.08;}
else {grjcbl=0.1;}

if (obj.p_bl[0].checked==true){pojcbl=0.08;}
else {pojcbl=0.1;}
*/
grjcbl=obj.jcbl.value/100;
pojcbl=obj.p_bl.value/100;
if (obj.xz[0].checked==true){fwxz=0.9;}
else {fwxz=0.8;}

if (obj.xy[0].checked==true){xy=1.15;}
else if(obj.xy[1].checked==true){xy=1.3;}
else {xy=1;}



 fwzj=obj.mount.value;

if(fwzj<=0){alert('房屋总价不能为空,请输入');
                     obj.mount.value='';return;}

 dknx=Math.round(obj.mount10.value);
//alert(dknx);
if(dknx<=0){alert('贷款申请年限不能为空,请输入');
                     obj.mount10.value='';return;}





var bcv=0;
if (dknx>5)
{
  bcv=Math.round( 1000000 * l6_30/12 ) / 1000000;
}else{
     bcv=Math.round( 1000000 * l1_5/12 ) / 1000000;
}
r=adv_format((10000*bcv*Math.pow(1+bcv,12*dknx))/(Math.pow(1+bcv,12*dknx)-1),2);

jtysr=Math.ceil((gryjce/grjcbl+poyjce/pojcbl)*10)/10;
gjjdka=Math.min(Math.round((jtysr-400)/r*10000*10)/10,600000);
gjjdkb=Math.round(gjjdka*xy*10)/10;
gjjdkc=Math.round(fwzj*fwxz*10)/10;
//obj.ze2.value=gjjdka; //jtysr;
//obj.ze2.value=Math.floor(Math.min(gjjdkb,gjjdkc)/10000*10)/10;

//计算2
zgdk=obj.ze2.value; //最高贷款额度

dked=Math.round(obj.need.value*10)/10;

obj.need.value = dked ;

if(dked==0){alert('需要的贷款额度不能为空,请输入');
                     obj.need.value='';return;}
if(dked<0){alert('输入的贷款额度不符合要求,请输入');
                     obj.need.value='';return;}


if(dked>zgdk){alert('不能高于最高贷款额度,请重新输入');
                     obj.need.value='';return;}


hkfs=obj.select.value;

if(hkfs==1){
//obj.ze22.value=Math.ceil(dked*r*100)/100;
    //==============================modify by xujianfei

var ylv_new ;

if(dknx>=1&&dknx<=5)
ylv_new = l1_5/12;
else
ylv_new = l6_30/12;


var ncm  = parseFloat(ylv_new) + 1;//n次幂

var dknx_new = dknx*12;



total_ncm = Math.pow(ncm, dknx_new)

obj.ze22.value = Math.round(((dked * 10000 * ylv_new * total_ncm) / (total_ncm - 1)) * 100) / 100 + " （元）";
var pp = Math.round(((dked*10000*ylv_new*total_ncm)/(total_ncm-1))*100)/100;

//=========================================================
gbl=Math.round(Math.ceil(dked*r*100)/100/jtysr*1000)/10;
obj.yj2.value = gbl + " （元）";
//bxhj=Math.round(r*dked*dknx*12*100)/100;
bxhj = Math.round(pp*dknx*12*100)/100;
obj.lx2.value = bxhj + " （元）";
$(".result_blocks").hide();
$(".result_block1").show();

}
if (hkfs==2)
{
if (dknx>5)
{
    rb=l6_30*100;
}else{
    rb=l1_5*100;
}

syhk=Math.round((dked*10000/(dknx*12)+dked*10000*rb/(100*12))*100)/100;
obj.sfk2.value = syhk + " （元）";
var yhke; //月还款额
var bxhj; //本息合计
var dkys; //贷款月数
var sydkze;//当前剩余贷款总额
var yhkbj; //月还款本金
dkys=dknx*12;
yhkbj=dked*10000/dkys;

yhke=syhk;
sydkze=dked*10000-yhkbj;
bxhj=syhk;
for (var count=2;count<=dkys; ++count)
	{
       		//yhke=Math.round((dked*10000/(dknx*12)+sydkze*rb/(100*12))*100)/100;
		yhke=dked*10000/dkys+sydkze*rb/1200;
		sydkze-=yhkbj;
		bxhj+=yhke;
	}
obj.lx3.value = Math.round(bxhj * 100) / 100 + " （元）";

$(".result_blocks").hide();
$(".result_block2").show();

}

if (hkfs==3)
{

switch(dknx){//自由还款还款方式月最低还款额参照表,调整利率不修改
	case 1 :  rb=83.04/100; break;
	case 2 :  rb=81.08/100; break;
	case 3 :  rb=79.12/100; break;
	case 4 :  rb=77.16/100; break;
	case 5 :  rb=75.20/100; break;
	case 6 :  rb=73.24/100; break;
	case 7 :  rb=71.28/100; break;
	case 8 :  rb=69.32/100; break;
	case 9 :  rb=67.36/100; break;
	case 10 :  rb=65.40/100; break;
	case 11 :  rb=63.44/100; break;
	case 12 :  rb=61.48/100; break;

	case 13 :  rb=59.52/100; break;
	case 14 :  rb=57.56/100; break;
	case 15 :  rb=55.60/100; break;
	case 16 :  rb=53.64/100; break;
	case 17 :  rb=51.68/100; break;
	case 18 :  rb=49.72/100; break;
	case 19 :  rb=47.76/100; break;
	case 20 :  rb=45.80/100; break;
	case 21 :  rb=43.84/100; break;
	case 22 :  rb=41.88/100; break;
	case 23 :  rb=39.92/100; break;
	case 24 :  rb=37.96/100; break;
	case 25 :  rb=36.00/100; break;
	case 26 :  rb=34.04/100; break;
	case 27 :  rb=32.08/100; break;
	case 28 :  rb=30.12/100; break;
	case 29 :  rb=28.16/100; break;
	case 30 :  rb=26.20/100; break;
}
var yhke; //月还款额
var ll;//利率
var zhbj;//最后一期本金
zhbj = Math.round(dked*10000*rb*100)/100;
if (dknx<=5)
{
	ll=l1_5/12;
	zdhkll=0.0378/12;
	var total_gjj = Math.pow(zdhkll + 1, dknx*12);
	syhk=Math.ceil(dked*10000*zdhkll*total_gjj/(total_gjj-1));
}
else
{
	ll=l6_30/12;
	zdhkll=0.0423/12;
	var total_gjj = Math.pow(zdhkll + 1, dknx*12-1);
	syhk=Math.ceil((dked*10000-zhbj)*zdhkll*total_gjj/(total_gjj-1)+zhbj*zdhkll);
}
obj.sfk3.value = syhk + " （元）";       //最低还款额
	var zhyqbj=dked*10000;
	var zchlx=0;//总偿还利息
	for (i=1;i<dknx*12 ;i++ )
	{
		zchlx+=Math.round(zhyqbj*ll*100)/100;
		zhyqbj=Math.round((zhyqbj-(syhk-Math.round(zhyqbj*ll*100)/100))*100)/100;
	}
	var sydkze=dked*10000-syhk;
	obj.lx4.value = zhyqbj + " （元）";    //最后期本金
	obj.lx5.value = Math.round(zhyqbj * ll * 100) / 100 + " （元）";
	
	zchlx+=Math.round(zhyqbj*ll*100)/100;
	obj.lx6.value = Math.round(zchlx * 100) / 100 + " （元）";

	$(".result_blocks").hide();
	$(".result_block3").show();

}



}