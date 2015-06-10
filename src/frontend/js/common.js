/**
 * 模仿PHP的$_GET
 * @param {type} attr
 * @returns {String}
 */
base_url = "/";
souce_url = "http://src.esf.focus.cn/upload/crm/";
function _GET() {
    var _v = [];
    try {
        var $url = location.href;
    } catch (e) {
        var $url = window.top.location.href;
    }
    if($url.indexOf('?')<=0){
        return false;
    }
    $start = $url.indexOf('?') + 1;
    $end = $url.length;
    $Query_String = $url.substring($start, $end);
    
    $Get = $Query_String.split('&');
    for (var i in $Get) {
        $tmp = $Get[i].split('=');
        _v[$tmp[0]] = $tmp[1]; 
        //_v = decodeURIComponent($tmp[1]);
        
    }
    return _v;
}

/**
 * 生成Excel导出Url参数
 * @returns {undefined}
 */
function _excelQueryString(){
    var _vstr = [];
    var _v = _GET();
    for(var i in _v){
        if(i=="page"){
            continue;
        }
        _vstr.push(i+"="+_v[i]);
    }    
    _vstr.push("action=export");
    return _vstr.join("&");
}


function caculateh() {
    var height = $(window).height() - 203;
    $(".sidebar").css("min-height", String(height) + "px");
    $(".main").css("min-height", String(height) + "px");
}

$(function () {
   
   //图片放大效果
    $(".hoverimg").hover(function () { $(this).find(".imgbig").show(); }, function () { $(this).find(".imgbig").hide(); })

    //左右栏最小高度设置
    caculateh();

    //=================sidebar slide ===================
    $(".sidebar li span").click(function () {
        if ($(this).parent().hasClass("open")) {
            $(this).parent().removeClass("open")
        } else {
            $(this).parent().addClass("open")
        }

    })

})

//删除弹出层
$(document).ready(function () {
    $('.td_delete').bind('click', function (e) {
        if (!$(".arrow_tips").is(":visible")) {
            $(this).siblings(".arrow_tips").fadeIn("fast") //显示效果
        }

    })
    $(".close_mylabel").click(function () {
        $(this).parent().parent().fadeOut();
    })
    $(".operate .arrow_tips .btn").click(function () {
        $(this).parent().parent().parent().parent().fadeOut();
    })
});


//返回顶部
function gotoTop(min_height) {
    $("#toTop").click(
        function () {
            $('html,body').animate({ scrollTop: 0 }, 200);
        })
        min_height ? min_height = min_height : min_height = 0;
        $(window).scroll(function () {
            var s = $(window).scrollTop();
            if (s > min_height) {
                $("#toTop").fadeIn(100);
            } else {
                $("#toTop").fadeOut(200);
            };
        });
};
$(function () {
    cacutetotopgap();
    gotoTop();
    $(window).resize(function () {
        cacutetotopgap();
        caculateh();
    });
})

function cacutetotopgap() {
    var winwidth = $(window).width();
    var itemswidth = $(".items").width();
    var totopgap = 0;
    if (winwidth >= 1548) {
        totopgap = itemswidth / 2 + 10;
       
    } else {
        totopgap = winwidth / 2 - 64;
     
    } 
    $("#toTop").css("margin-left", String(totopgap) + "px")
}


//table hover上去底色改变

 /*
      * jQuery placeholder, fix for IE6,7,8,9
      * @author JENA
      * @since 20131115.1504
      * @website ishere.cn
      */
      var JPlaceHolder = {
          //检测
          _check: function () {
              return 'placeholder' in document.createElement('input');
          },
          //初始化
          init: function () {
              if (!this._check()) {
                  this.fix();
              }
          },
          //修复
          fix: function () {
              jQuery(':input[placeholder]').each(function (index, element) {
                  var self = $(this), txt = self.attr('placeholder');
                  self.wrap($('<div></div>').css({ position: 'relative', zoom: '1', border: 'none', background: 'none', padding: 'none', margin: 'none' }));
                  var pos = self.position(), h = self.outerHeight(true), paddingleft = self.css('padding-left');
                  var holder = $('<span></span>').text(txt).addClass("placeholder").css({ position: 'absolute', left: pos.left, top: pos.top, height: h, paddingLeft: paddingleft, color: '#aaa'}).appendTo(self.parent());
                  self.focusin(function (e) {
                      holder.hide();
                  }).focusout(function (e) {
                      if (!self.val()) {
                          holder.show();
                      }
                  });
                  holder.click(function (e) {
                      holder.hide();
                      self.focus();
                  });
              });
          }
      };
      //执行
      jQuery(function () {
          JPlaceHolder.init();
      });


