/**
 *  data.js
 *  authors : Hqyun
 *  date : 2014-06-12
 */
(function(){
    var data1,data2,data3;

    var chart1 = document.getElementById('myChart1'),
        chart2 = document.getElementById('myChart2'),
        chart3 = document.getElementById('myChart3');

    data1 = eval('('+ chart1.getAttribute('jsdata') +')');
    data2 = eval('('+ chart2.getAttribute('jsdata') +')');
    data3 = eval('('+ chart3.getAttribute('jsdata') +')');

    doughnut(chart1,data1);
    bar(chart2,data2);
    bar(chart3,data3);

    if(window.addEventListener){
        window.addEventListener('resize', function(){
            doughnut(chart1,data1);
            bar(chart2,data2);
            bar(chart3,data3);
        });
    }else if(window.attachEvent){
        window.attachEvent('onresize', function(){
            doughnut(chart1,data1);
            bar(chart2,data2);
            bar(chart3,data3);
        });
    }


    /**
     * 环形图
     * @param obj canavs对象
     * @param data 填充的数据
     */
    function doughnut(obj,data){
        updateWH(obj,'doughnut');

        var options = {
            percentageInnerCutout : 60,
            animation : true,
            animationSteps : 100,
            animationEasing : "easeInOutSine",
            animateScale : false
        };

        var ctx = obj.getContext("2d");
        var myNewChart = new Chart(ctx).Doughnut(data,options);
    }

    /**
     * 柱状图
     * @param obj canavs对象
     * @param data 填充的数据
     */
    function bar(obj,data){
        updateWH(obj,'bar');

        var options = {
            /*scaleFontSize : 13,*/
            barValueSpacing : 5,
            barStrokeWidth : 0,
            barDatasetSpacing : 0,
            animation : true,
            animationSteps : 60,                //动画的平滑度
            animationEasing : "easeInOutSine",  //动画显示效果的种类
            animateScale : false                //是否使用放大效果
        };

        var ctx2 = obj.getContext('2d');
        var myNewChart = new Chart(ctx2).Bar(data,options);
    }

    function updateWH(obj,type){
        var w = document.documentElement.clientWidth -20, h;
        w = type === 'bar' ? w * 0.99 : w * 0.48;
        h = type === 'bar' ? w * 0.5 : w;

        obj.setAttribute("width", w + "px");
        obj.setAttribute("height", h + "px");
    }
})();