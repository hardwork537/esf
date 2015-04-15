/** refresh.js
 * authors : Hqyun
 * date : 2014-08-26 add
 */
$(document).ready(function(){
    function bindEvent(){
        var plan = $('#addNormalPlan'),
            obj = plan.find('em'),
            btn_save = $('#btnSave'),
            del = $('#tableList .a_del'),
            panel = $('#contentOuter'),
            icon_del = $('#iconDel'),
            btn_sure = $('#btnSure'),
            btn_cancel = $('#btnCancel');

        /*save item*/
        obj.click(function(){
            var tit = $('#alreadyTit'),
                con = $('#alreadyCon'),
                bar = $('#pBlank');
            $(this).toggleClass('hover');

            tit.show();
            bar.show();
            con.show();

            var zIndex = 'z-index:100;';

            function showList(obj){
                var sel = '<select style="'+ zIndex +'" class="td_0_minute" name="td_0_refresh_minute[]">';
                sel += '<option value=""></option><option value="00">00分</option><option value="05">05分</option><option value="10">10分</option><option value="15">15分</option><option value="20">20分</option><option value="25">25分</option>';
                sel += '<option value="30">30分</option><option value="35">35分</option><option value="40">40分</option><option value="45">45分</option><option value="50">50分</option><option value="55">55分</option>'
                sel += '</select>';

                var str = '<li class="clearfix">';
                str += '    <em class="fl em_0 em_1">5点</em>';
                str += '    <div class="fl div_0 clearfix"><span class="sp_0"><input type="checkbox" /><label>'+ sel + '</label></span><span class="sp_0"><input type="checkbox" /><label>'+ sel + '</label></span></div>';
                str += '    <em class="fl em_0 em_3">展开</em>';
                str += '</li>';

                if(obj.hasClass('hover')){
                    con.append(str);
                }else{
                    var lst = $("#alreadyCon li:last-child");
                    lst.remove();
                }
            }
            showList($(this));
        });

        /*save item*/
        btn_save.click(function(){
            var curr = plan.find('.hover'),
                data = [];
            curr.each(function(){
                data.push($(this).attr('ids'));
            });
            //console.log('data:',data);
            alert('data:'+data);
        });

        /*delete item*/
        del.click(function(){
            var set = $(this).offset(),
                top = set.top + 18,
                left = set.left - 240,
                tr = $(this).parent().parent(),
                ids = tr.attr('ids');
            panel.show();
            panel.css('top', top + 'px');
            panel.css('left', left + 'px');

            icon_del.click(function(){
                panelHide();
                sendData(tr,ids);
            });
            btn_sure.click(function(){
                panelHide();
                sendData(tr,ids);
            });
            btn_cancel.click(function(){
                panelHide();
            });
        });

        function panelHide(){
            panel.hide();
        }

        function sendData(obj,id){
            $.ajax({
                type: 'get',
                url: '', //php提供
                data: {
                    id: id
                },
                success: function Success(data) {
                    //console.log('data:',data);
                    obj.remove();
                }
            });
        }
    }
    bindEvent();


    function bindEvent2(){
        /*settime*/
        var li_items = $('#setTime li'),
            bg = $('#bgBlue'),
            curr_idx = $('#setTime .selected').index();

        bg.css('width', curr_idx * 90 + 'px');

        li_items.click(function(){
            var idx = $(this).index(),
                wid = idx * 90;

            li_items.removeClass('selected');
            $(this).addClass('selected');
            bg.css('width', wid + 'px');
        });
    }
    bindEvent2();




    /**
     * 下拉菜单保存值到隐藏域
     * @param {object} selObj select对象，hiddenObj 隐藏域对象
     */
    function test(selObj,hiddenObj){
        var v = selObj.find("option:selected").val();
        hiddenObj.val(v);  //取到初始化的值

        selObj && selObj.change(function(){
            hiddenObj.val($(this).val());
        });
    }



    function panel(){

    }

});