/** refresh.js
 * authors : Hqyun
 * date : 2014-08-26 add
 */
$(document).ready(function(){
    //最大刷新数
    var intMaxFlush = $("#tomorrow_surplus").text();
    intMaxFlush = parseInt(intMaxFlush, 10);
    if(isNaN(intMaxFlush)) intMaxFlush = 0;

    var isIE = !!window.ActiveXObject,
        isIE6 = isIE && !window.XMLHttpRequest,
        isIE8 = isIE && !!document.documentMode,
        isIE7 = isIE && !isIE6 && !isIE8;

    $(".bot_total .count").text($(".f_simsun .count").text());
    function bindEvent(){
        var plan = $('#addNormalPlan'),
            obj = plan.find('em'),
            del = $('#tableList .a_del'),
            panel = $('#contentOuter'),
            icon_del = $('#iconDel'),
            btn_sure = $('#btnSure'),
            btn_save = $('#btnSave'),
            btn_cancel = $('#btnCancel');

        /*save item*/
        obj.click(function(){
            var tit = $('#alreadyTit'),
                con = $('#alreadyCon'),
                bar = $('#pBlank'),
                item = con.find('.li_items'),
                idx = $(this).index();
            $(this).toggleClass('hover');

            tit.show();
            bar.show();
            con.show();

            item.eq(idx).find(".div_0").empty();
            var content = getSelectTimeHtml($(this),20);
            item.eq(idx).find(".div_0").append(content);
            item.eq(idx).toggle();
            item.eq(idx).toggleClass("open");
            var times = 0;
            $.each(item,function(){
                if($(this).hasClass("open")){
                    times += $(this).find("input:checked").length;
                }
            });
            var houseCount = parseInt($(".bot_total .count").text());
            $(".bot_total .time").text(houseCount * times);
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
            
            var delID = $(this).data("delid");
            var houseID = $(this).data("houseid");
            var houseType = $(this).data("housetype");

            icon_del.click(function(){
                panelHide();
            });
            btn_sure.click(function(){
                panelHide();
                sendData(tr,delID,houseID,houseType);
            });
            btn_cancel.click(function(){
                panelHide();
            });
        });
        
        function panelHide(){
            panel.hide();
        }

        function sendData(obj,delID,houseID,houseType){
            $.ajax({
                type: 'get',
                url: '/refreshscheme/delete', //php提供
                data:"id="+delID+"&ids="+houseID+"&house_type="+houseType,
                dataType:"json",
                success: function Success(data) {
                	if(data.success == 1) obj.remove();
                }
            });
        }
    

        $(".type0_ul").on("click",".em_3",function(){
            switchShow($(this));
        });
        $(".tables").on("click",".em_3",function(){
            switchShow($(this));
        });
        //默认平均分配刷新次数
        getAverageTime();
        getDetailTime();
        //输入框：只能填写数字，最多可输入3位字符
        $("#typeTime2 input").keyup(function(){
            var objInput = $(this);
            var strVal = objInput.val();
            strVal = strVal.replace(/[^\d]/g,'');
            strVal = strVal.replace(/^(\d{1,3})\d*/,'$1');
            $(this).val(strVal);

            //计算明日剩余定时数
            if(getTomorrowTimes() < 0){
                objInput.val('0');
                getTomorrowTimes();
                alert('您设置的刷新次数已超限，请重新输入');
            }
        });
        var lastTimes = "";
        $("#typeTime2 input").focus(function(){
            lastTimes = $(this).val();
        });
        $("#typeTime2 input").keyup(function(){
            var currentTimes = $(this).val();
            var ids = $(this).parent().attr("ids");
            if(lastTimes == currentTimes){
                return;
            }
            if(currentTimes == 0 || currentTimes == ""){
                $(".type2_ul").find("li[ids="+ids+"]").removeClass("open");
                $(".type2_ul").find("li[ids="+ids+"]").hide();
            }
            else{
                $(".type2_ul").find("li[ids="+ids+"]").addClass("open");
                $(".type2_ul").find("li[ids="+ids+"]").show();
                randomTime(ids, currentTimes);
            }
        });
        $(".type2_ul").on("click",".em_4",function(){
            var ids = $(this).parent().attr("ids");
            var count = $("#typeTime2").find(".sp_0[ids="+ids+"]").find("input").val();
            randomTime(ids, count);
        });
        $("#typeTime2 .sp_lst").click(function(){
            emptyRefreshTimes();
        });
        $('#clickInfo').on("mouseenter",function(){
            detailUpdateState();
            $('#detailInfo').show();
        });
        $("#clickInfo").on("mouseleave",function(){
            $('#detailInfo').hide();
        });

        $('.type0_div').on("mouseenter","em",function(){
            $(this).addClass("hovering");
        });
        $(".type0_div").on("mouseleave","em",function(){
            $(this).removeClass("hovering");
        });
        $('.type2_div').on("focus",".em_1",function(){
            $(this).addClass("focus");
        });
        $('.type2_div').on("blur",".em_1",function(){
            var thiz = $(this);
            setTimeout(function(){
                thiz.removeClass("focus");
            },250)
        });
    }
    bindEvent();
    //控制展开收起
    function switchShow(element){
        var thiz = element.parent().find("div"),
            str,
            cls;
        thiz.toggleClass('open');
        thiz.animate({scrollTop:0},0);
        str = thiz.hasClass('open') ? '收起'+'<i class="up"></i>' : '展开'+'<i class="down"></i>';
        element.html(str);
        element.toggleClass('em_3_open');
    }
    //平均分配刷新次数
    function getAverageTime(){
    	 var houseCount = parseInt($(".f_simsun .count").text());
         $("#typeTime2").find(".sp_0").find(".em_0").val('');
        if(houseCount > 0){
            if(intMaxFlush/houseCount < 13){
                $("#typeTime2").find(".sp_0[ids='9']").find(".em_0").val(Math.floor(intMaxFlush/houseCount));
            }
            else if(intMaxFlush/houseCount >= 13){
                var averageTime = Math.floor(intMaxFlush/houseCount/13);
                for(var i = 9;i <= 21;i++){
                    $("#typeTime2").find(".sp_0[ids='"+i+"']").find(".em_0").val(averageTime);
                }
            }
        }
         getTomorrowTimes();
    }

    //选择时间详情的html的生成、count个数，time时间
    function getSelectTimeHtml(container,count, currentTime){
        var content = "",
            ids = container.attr("ids"),
            m = 1;
        for(var j = 0;j < count;j++){
            var minute = m / 10 >= 1 ? m:"0" + m;
            var time = currentTime ? currentTime : ids + ":" + minute;
            var selectContent = "<select ids="+ ids +"><option>"+time+"</option>";
            for(var i = 0; i < 60;i++){
                selectContent += "<option>"+i+"</option>";
            }
            selectContent += "</select>";
            content += "<span class='sp_0 clearfix'><input type='checkbox' name='timing[]' value='"+time+"'/><label>"+time+"</label>"+selectContent+"</span>";
            m += 3;
        }
        return content;
    }
    
    //选择时间详情的html的生成、count个数，time时间(选中的）
    function getCheckedTimeHtml(container,count, currentTime){
        var content = "",
            ids = container.attr("ids"),
            m = 1;
        for(var j = 0;j < count;j++){
            var minute = m / 10 >= 1 ? m:"0" + m;
            var time = currentTime ? currentTime : ids + ":" + minute;
            var selectContent = "<select ids="+ ids +"><option>"+time+"</option>";
            for(var i = 0; i < 60;i++){
                selectContent += "<option>"+i+"</option>";
            }
            selectContent += "</select>";
            content += "<span class='sp_0 clearfix'><input type='checkbox' name='timing[]' value='"+time+"' checked/><label>"+time+"</label>"+selectContent+"</span>";
            m += 3;
        }
        return content;
    }
    
    //选中的房源数统计 - 已选择 xx 条出售房源
    function unitNumStat(){
        //$("#selected_unit_num").html($("input[name='unit_ids[]']:checked").length);
    }

    //清空
    function emptyRefreshTimes(){
        $("#typeTime2 .em_0").each(function(){
            $(this).val("0");
        });
        $(".type2_ul").find("li").removeClass("open");
        $(".type2_ul").find("li").hide();
        $("#tomorrow_surplus").html(intMaxFlush);
    }
    //获得具体的时间
    function getDetailTime(){
        var ul = $(".type2_ul");
        $("#typeTime2 input").each(function(){
            var val = $(this).val();
            val = parseInt($(this).val(), 10);
            if(isNaN(val)){
                val = 0;
            }
            if(val > 0){
                var ids = $(this).parent().attr("ids");
                randomTime(ids, val);
                ul.find("li[ids="+ids+"]").addClass("open");
                ul.find("li[ids="+ids+"]").show();
            }
        });
    }
    //随机生成多个时间，count生成的个数
    function randomTime(ids, count){
        var container = $(".type2_ul").find("li[ids="+ids+"]").find(".div_0"),
            content = "",
            allRandomTimeArr = [];
        container.empty();
        if(count <= 0){
            return;
        }
        for(var i = 0; i < count; i++){
            var minute = Math.ceil(Math.random() * 59);
            var seconds = Math.ceil(Math.random() * 59);
            var time = {"hour":ids, "minute":minute, "seconds":seconds};
            allRandomTimeArr.push(time);
        }
        allRandomTimeArr.sort(function(obj1,obj2){
            if(obj1.minute > obj2.minute){
                return 1;
            }
            else if(obj1.minute == obj2.minute){
                if(obj1.seconds > obj2.seconds){
                    return 1;
                }
                else{
                    return -1;
                }
            }
            else{
                return -1;
            }
        });
        $.each(allRandomTimeArr,function(index, randomTime){
            var minute = randomTime.minute < 10 ? "0" + randomTime.minute:randomTime.minute;
            var seconds = randomTime.seconds < 10 ? "0" + randomTime.seconds:randomTime.seconds;
            var time = randomTime.hour +":"+ minute +":"+ seconds;
            content += "<em class='em_0 em_2'>"+time+"</em>";
        });
        container.append(content);
        if(allRandomTimeArr.length <= 8){
            container.parent().find(".em_3").hide();
        }
        else{
            container.parent().find(".em_3").show();
        }
    }
    //计算明日剩余定时数
    function getTomorrowTimes(){
        var intMaxFlushTmp = intMaxFlush;
        var currntTimeCount = 0;
        $("#typeTime2 input").each(function(){
            var val = $(this).val();
            val = parseInt($(this).val(), 10);
            if(isNaN(val)){
                val = 0;
            }
            var houseCount = parseInt($(".f_simsun .count").text())
            intMaxFlushTmp -= val * houseCount;
            currntTimeCount += val * houseCount;
        });
        $("#tomorrow_surplus").html(intMaxFlushTmp);
        $(".bot_total .time").html(currntTimeCount);
        return intMaxFlushTmp;
    }

    function bindEvent2(){
        /*settime*/
        var li_items = $('#setTime li'),
            bg = $('#bgBlue'),
            curr_idx = $('#setTime .selected').index(),
            cb = $(".cb_forever"),
            lastIndex = curr_idx,
            lastLi = li_items.parent().find('>li:last'),
            lastLiTime = lastLi.find("em").html();

        bg.css('width', curr_idx * 90 + 'px');

        li_items.click(function(){
            if(cb.find("input").prop("checked")){
                lastLi.find("em").html(lastLiTime);
                cb.find("input").prop("checked",false);
            }
            var idx = $(this).index(),
                wid = idx * 90;

            li_items.removeClass('selected');
            $(this).addClass('selected');
            bg.css('width', wid + 'px');
            lastIndex = idx;
        });

        cb.on("click",function(){
           var flag = $(this).find("input")[0].checked;
           if(flag){
               li_items.removeClass('selected');
               lastLi.addClass('selected');
               lastLi.find("em").html("一个月");
               bg.css('width', (li_items.length-1) * 90 + 'px');
           }else{
               li_items.removeClass('selected');
               li_items.parent().find('li').eq(lastIndex).addClass('selected');
               lastLi.find("em").html(lastLiTime);
               bg.css('width', lastIndex * 90 + 'px');
           }
        });
    }
    bindEvent2();
//保存和选择定时方案的事件
    function bindEvent3(){
        var overlay1 = $("#overlay1"),
            overlay2 = $("#overlay2"),
            cover = $(".cover");
        $(".div_0").on("click",".sp_0",function(){
            var status = $(this).find("input[type='checkbox']").prop("checked")
            $(this).find("input[type='checkbox']").prop("checked",!status);
            changeCount($(this).find("input"));
        });
        $(".div_0").on("click","input",function(e){
            e.stopPropagation();
            changeCount($(this));
        });
        $(".div_0").on("click","select",function(e){
            e.stopPropagation();
            if($(this).find("option:selected").index() == 0){
                return;
            }
            var value = $(this).find("option:selected").val();
            if(value){
                var hour = $(this).attr("ids");
                var time = value.length == 1 ? "0" + value:value;
                var seleteTime = hour +":"+time;
                $(this).parent().find("label").html(seleteTime);
                $(this).parent().find("input").val(seleteTime);
                $(this).find("option").eq(0).text(seleteTime);
                $(this).val(seleteTime);
            }
        });
        $(".p_select").on("click",function(){
            $("body").animate({scrollTop:0},0);
            cover.show();
            overlay1.show();
            if(isIE6){
                $("#alreadyCon").hide();
            }
        });
        overlay1.on("click",".btn_greens",function(){
            var alreadyCon = $("#alreadyCon"),
                addNormalPlan = $("#addNormalPlan");
            alreadyCon.find("li").find(".div_0").empty();
            alreadyCon.find("li").hide();
            addNormalPlan.find("em[class = 'hover']").removeClass("hover");
            var radio = overlay1.find('input:radio:checked'),
                timeArr;
            if(radio.length > 0){
                var times = overlay1.find('input:radio:checked').parent().parent().find(".mid i");
                var timeLength = times.length;
                $.each(times, function(){
                    var time = $(this).text(),
                    hour = time.split(":")[0],
                    thiz = addNormalPlan.find("em[ids = "+hour+"]"),
                    detailTime = alreadyCon.find("li[ids = "+ hour +"]").find(".div_0");

                    $('#alreadyTit').show();
                    $("#pBlank").show();
                    
                    thiz.addClass("hover");
                    var content = getCheckedTimeHtml(detailTime.parent(), 1, time);
                    detailTime.append(content);
                    alreadyCon.show();
                    alreadyCon.find("li[ids = "+ hour +"]").show();
                    alreadyCon.find("li[ids = "+ hour +"]").addClass("open");
                });
                var lis = $("#alreadyCon").find("li");
                $.each(lis,function(){
                    if($(this).hasClass("open")){
                        var len = $(this).find(".div_0 span").length;
                        if(len < 20){
                            var content = getSelectTimeHtml($(this), (20-len));
                            $(this).find(".div_0").append(content);
                        }
                    }
                });
            }
            var houseCount = parseInt($(".bot_total .count").text());
            $(".bot_total .time").text(houseCount * timeLength);
            cover.hide();
            overlay1.hide();
            $('#alreadyCon').show();
        });
        overlay1.on("click",".btn_gray, .close",function(){
            cover.hide();
            overlay1.hide();
            $('#alreadyCon').show();
        });
        $(".save").on("click",function(){
            var timeArr = getTimePoint(),
            length = timeArr.length,
            tbody = overlay2.find("tbody"),
            ids = overlay1.find("tbody tr").length + 1,
            title = "刷新方案" + ids;
            if(length == 0){
            	alert("请选择时间点!");
                return;
            }
            tbody.empty();
            var content = "<tr ids='"+ids+"'><td class='tIndent'><input type='radio' name='type' id='type"+ids+"' /><label for='type"+ids+"'>"+title+"</label></td><td>"+length+"</td><td class='mid'><div class='container'>";
            $.each(timeArr,function(index,time){
                content += "<i>"+ time +"</i>";
            });
            content += "</div>";
            if(length > 50){
                content += "<em class = 'em_3'>展开<i class='down'></i></em>";
            }
            content += "</td></tr>";
            tbody.append(content);

            $("body").animate({scrollTop:0},0);
            cover.show();
            overlay2.show();
            if(isIE6){
                $("#alreadyCon").hide();
            }
        });
        overlay2.on("click",".btn_greens",function(){
            var content = overlay2.find("tbody").html();
            overlay1.find("tbody").append(content);
            cover.hide();
            overlay2.hide();
            $('#alreadyCon').show();
        });
        overlay2.on("click",".btn_gray, .close",function(){
            cover.hide();
            overlay2.hide();
            $('#alreadyCon').show();
        });

        $('#btnSave').on("click",function(){
           var timeArr = getTimePoint();
            //console.log(timeArr);
        });
    }
    bindEvent3();
    function changeCount($this){
        var houseCount = parseInt($(".bot_total .count").text());
        var totalCount = parseInt($(".bot_total .time").text());
        if($this[0].checked){
            $(".bot_total .time").text(totalCount + houseCount * 1);
        }
        else{
            $(".bot_total .time").text(totalCount - houseCount * 1);
        }
    }
//点击修改房源的事件
    function bindEvent4(){
        var overlay3 = $("#overlay3"),
            cover = $(".cover");
        $(".editHouse").on("click",function(){
            $("body").animate({scrollTop:0},0);
            cover.show();
            overlay3.show();
            if(isIE6){
                $("#alreadyCon").hide();
            }
        });
        overlay3.on("click","#checkAll",function(){
            overlay3.find("tbody input").prop("checked",this.checked);
        });
        overlay3.on("click",".btn_greens",function(){
            var checkboxs = overlay3.find("tbody input:checkbox:checked"),
                idsArr = [];
            $.each(checkboxs,function(){
                idsArr.push($(this).parent().parent().attr("ids"));
            });
            //$("#houseIdArr").val(idsArr);
            var length = checkboxs.length;

            if(length == 0){
                alert("嗷，亲还没选择房源!");
                return;
            }
            $(".f_simsun .count").text(length);
            var singleCount = parseInt($(".bot_total .time").text())/parseInt($(".bot_total .count").text());
            $(".bot_total .count").text(length);
            $(".bot_total .time").text(singleCount * length);

            if($("#secNav").find(".sp_nav").eq(1).hasClass("selected")){
                getAverageTime();
                getDetailTime();
            }
            cover.hide();
            overlay3.hide();
            $('#alreadyCon').show();
            
            var checkboxs = overlay3.find("tbody input:checkbox:checked"),
	            idsArr = [];
	        $.each(checkboxs,function(){
	            idsArr.push($(this).val());
	        });
	        $("#ids").val(idsArr);
        });
        overlay3.on("click",".btn_gray, .close",function(){
            cover.hide();
            overlay3.hide();
            $('#alreadyCon').show();
        });
    }
    bindEvent4();

    function getTimePoint(){
        var timeArr = [],
            lis = $("#alreadyCon").find("li");
        $.each(lis,function(){
            if($(this).hasClass("open")){
                var times = $(this).find("input");
                $.each(times,function(){
                    if($(this)[0].checked){
                        timeArr.push($(this).val());
                    }
                });
            }
        });
        return timeArr;
    }

    function detailUpdateState(){
        var content = "";
            lis = $("#alreadyCon").find("li"),
            houseCount = parseInt($(".bot_total .count").text());
        $("#detailInfo .container").empty();

        if($(".bot_total .time").text() == 0){
            $("#detailInfo .container").html("<span>还未选择定时<span>");
            return;
        }
        var isFirst = true;
        $.each(lis,function(){
            var count = 0;
            if($(this).hasClass("open")){
                var times = $(this).find("input");
                $.each(times,function(){
                    if($(this)[0].checked){
                        count++;
                    }
                });
                if(count > 0){
                    var ids = parseInt($(this).attr("ids"));
                    if(isFirst){
                        content += "<p class='first'><em>"+ids+"-"+(ids + 1)+"点</em><i><b class='red'>"+ (houseCount * count) +"</b>次</i></p>";
                        isFirst = false;
                    }
                    else{
                        content += "<p><em>"+ids+"-"+(ids + 1)+"点</em><i><b class='red'>"+ (houseCount * count) +"</b>次</i></p>";
                    }
                }
            }
        });
        $("#detailInfo .container").append(content);
    }
});
