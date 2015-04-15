;(function($) {
    $.dialog = function(content, options) {
        if($('.dialog').length) {
            $('.dialog').remove();
        }    
        var defaults = {
            overlay: true,
            initFun: null,
            callback: null,
            draggable: true,
            fade: true,
            dural: 300,
            offsetX: 0,
            offsetY: 0,
            closeButClass: 'close',
            confirmButClass: 'confirm',
            cancelButClass: 'cancel',
            closeFun: null,            
            confirmFun: null,
            cancelFun: null
        };
        var op = $.extend(defaults, options);
        var $dialog = $('<div class="dialog"></div>');
        $overlay = $('#dialogOverlay');
        $dialog.diaRemove = diaRemove;
        init();

        function init() {
            $dialog.html(content);
            if($overlay.length == 0) {
                $overlay = $('<div id="dialogOverlay"></div>');
                $overlay.css({
                    'left': 0,
                    'top': 0,
                    'width': '100%',               
                    'position': 'fixed',               
                    'height': $(document).height(),
                    'z-index': 998,
                    'background': '#000', 
                    'opacity': 0.6,
                    'filter': 'alpha(opacity=80)'
                });
                $('body').append($overlay);
                $(window).resize(function() {
                    $overlay.css('height', $(document).height());
                    setPos();
                });
            }
            $dialog.css({
                'z-index': 999,
                'position': 'fixed',
                'visibility': 'hidden'
            });
            $('body').append($dialog);
            if(typeof(op.initFun) == 'function') {
                op.initFun($dialog);
            }
            if(typeof(op.confirmFun) == 'function') {
                $dialog.find('.' + op.confirmButClass).bind('click', function() {
                    op.confirmFun($dialog)               
                });
            } else {
                $dialog.find('.' + op.confirmButClass).bind('click', diaRemove);
            }
            if(typeof(op.closeFun) == 'function') {
                $dialog.find('.' + op.closeButClass).bind('click', function() {
                    op.closeFun($dialog)               
                });
            } else {
                $dialog.find('.' + op.closeButClass).bind('click', diaRemove);
            }
            if(typeof(op.cancelFun) == 'function') {
                $dialog.find('.' + op.cancelButClass).bind('click', function() {
                    op.cancelFun($dialog)               
                });
            } else {
                $dialog.find('.' + op.cancelButClass).bind('click', diaRemove);
            }
            $dialog.css('visibility', 'visible').hide();     
            
            if(op.draggable) {
                dragger();
            }
            
            show();
        }
        
        function setPos() {
            var top = ($(window).height() - $dialog.height()) / 2 + op.offsetX;    
            var left = ($(window).width() - $dialog.width()) / 2 + op.offsetY;
            if(navigator.userAgent.indexOf("MSIE 6.0")>0) {   
                $overlay.css('position','absolute');
                $dialog.css('position','absolute');
                $dialog.css({
                    'position': 'absolute',
                    'top': top + $(document).scrollTop(),
                    'left': left + $(document).scrollLeft()
                });
                $(window).scroll(function(){
                    $dialog.css({
                        'top': $(document).scrollTop() + top,
                        'left': $(document).scrollLeft() + left
                    });
                    $overlay.css({
                        'top': $(document).scrollTop()
                    });
                });
            } else {
                $dialog.css({
                    'top': top,
                    'left': left
                });   
            }
        }
        function dragger() {
            var mouse={x:0,y:0};
            var moveDialog = function(event)  {
                var e = window.event || event;
                var top = parseInt($dialog.css('top')) + (e.clientY - mouse.y);  
                var left = parseInt($dialog.css('left')) + (e.clientX - mouse.x);  
                $dialog.css({
                    top:top,
                    left:left
                });  
                mouse.x = e.clientX;  
                mouse.y = e.clientY;          
            };  
            $dialog.mousedown(function(event) {
                var e = window.event || event;         
                var target = e.srcElement || e.target;
                if(target == $dialog[0]) {
                    mouse.x = e.clientX;  
                    mouse.y = e.clientY;  
                    $(document).bind('mousemove', moveDialog);
                }
            });  
            $(document).mouseup(function(event) {  
                $(document).unbind('mousemove', moveDialog);  
            });
        }    
        function show() {  
            setPos();
            $overlay.show();
            if(op.fade) {
                $dialog.fadeIn(op.dural);
            } else {
                $dialog.show();
            }
        }
        function diaRemove(now) {
            $dialog.find('.' + op.confirmButClass).unbind('click');
            $dialog.find('.' + op.closeButClass).unbind('click');
            $dialog.find('.' + op.cancelButClass).unbind('click');
            if(op.fade && !now) {
                $dialog.fadeOut(op.dural, function() {
                    $overlay.hide();
                    $dialog.remove();
                });
            } else {
                $dialog.remove();
                $overlay.hide();
            }
            if(typeof(op.callback) == 'function') {
                op.callback();
            }
        }
    }
})(jQuery);
