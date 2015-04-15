
  //下拉事件
  $(document).ready(function(){
    $('#headDistrict').children('a').click(function (){ $('#Second').val( $(this).attr('href') ); });
    $('#headSubwayLine').children('a').click(function (){ $('#Second').val( $(this).attr('href') ); });
    $('#headPrice').children('a').click(function (){ $('#J').val( $(this).attr('href') ); });

    $('#headZhuZhaiType').children('a').click(function (){ $('#H').val( $(this).attr('href') ); });
    $('#headBedroom').children('a').click(function (){ $('#H').val( $(this).attr('href') ); });
  });

  //价格事件
  function isNumber(nu){ return (isNaN(nu)||parseFloat(nu)<0||nu=='')? true: false; }
  $('#head_price_button').click(function (){
    var min = document.getElementById('head_price_min').value;
    var max = document.getElementById('head_price_max').value;
    if(isNumber(min)){ alert('请输入正确的价格开始范围！'); return false; }
    if(isNumber(max)){ alert('请输入正确的价格结束范围！'); return false; }
    if( parseFloat(min)>parseFloat(max) ){ alert('价格范围应由小到大！'); return false; }
    var priceVal = min+'-'+max;
    $('#headPrice').parent().siblings('span').html(priceVal+'元').removeClass('active').siblings('div').hide();
    $('#J').val(priceVal);
  });

  //搜索事件
  function top_search(){
    var location = $('#strHeadCityDomain').val()? $('#strHeadCityDomain').val(): '';
    var strHeadData = $('#strHeadData').val();
    var strSecond = $("#Second").val();
    var strJ = $("#J").val();
    var strH = $("#H").val();
    switch(strHeadData){
      case 'rent':
        location += "/rent/"+(strSecond==0||strSecond=='sub0'? '': strSecond+'/')+(strJ==0? '': 'j'+strJ)+(strH==0? '' : 'h'+strH);
        break;
      case 'house':
        location += "/xiaoqu/"+(strH==0? 'all': strH)+"/"+(strSecond==0||strSecond=='sub0'? '': strSecond+'/')+(strJ==0? '': 'j'+strJ);
        break;
      case 'sale':
        location += "/sale/"+(strSecond==0||strSecond=='sub0'? '': strSecond+'/')+(strJ==0? '': 'j'+strJ)+(strH==0? '' : 'h'+strH);
        break;
    }
    if( $('#house_id_head').val()!=0 ){
      location += 'x'+$('#house_id_head').val();
    }else if( $('#head_house_name').val()!='请输入小区名或房源特征' ){
      location += '?k='+$('#head_house_name').val();
    }
    window.location.href = location;
  }