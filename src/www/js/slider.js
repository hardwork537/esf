/**
 * @author [niekai]
 * @date [2013-08-13]
 * @description [
 * 
 * ]
 */
(function($){

    window.Slider = function(){};
    
    Slider.prototype.config = {
        pageSize: 7,
        dataType: '',
        dataSelquence: 0//需要添加该init时 的字段，否则从详情页点击某张图片到相册页的相应图片会失效
    }


    Slider.prototype.reset = function(dataItems, dataType) {

        Slider.dataType = dataType;
        Slider.el_slider.empty();
        Slider.el_slider.css({left: '0px'});
        $('.bd_nk').find('img').remove();
        var tmpl = '';
        for (var i=0; i<dataItems.length; i++) {
            dataItems[i].loaded = false;
            dataItems[i].large_loaded = false;
            tmpl += '<div class="item" data-index="' + i + '">'+
                    '</div>';
        }
        Slider.el_slider.append(tmpl);

        Slider.dataItems = dataItems;
        Slider.el_items = $('.items',  Slider.el_context).children();
        Slider.amount = Slider.el_items.length;
        Slider.pageCount = Math.floor((Slider.amount-1)/this.config.pageSize + 1);
        Slider.index = 0;
        var instance = this;

        instance.find_thumbs(7);
        instance.find_fullSize();
        //Slider.el_items.eq(0).css('border-color', '#1369C0');
        Slider.el_items.eq(0).addClass('item_curr');
        $('.cur_num_nk').text(Slider.index - 0 + 1);
        $('.total_nk').text(Slider.amount);
        $("#image_des").html(Slider.dataItems[Slider.index]['desc']);	
		
		/* ice add */		
		$('.scroll_prev').removeClass('scroll_prev_active');
        $('.fs_prev').hide();
		if(Slider.dataItems.length > 1){
			$('.scroll_next').addClass('scroll_next_active');
            $('.fs_next').show();
		}
    }


    Slider.prototype.init = function(init_config) {
        $.extend(this.config, init_config);
        var tmpl = '';
        for (var i=0; i<this.config.dataItems.length; i++) {
            tmpl += '<div class="item" data-index="' + i + '">'+
                    '</div>';
        }
        $('.items').append(tmpl);

        Slider.dataItems = this.config.dataItems;
        Slider.el_context = $('.scrollable');
        Slider.el_slider = $('.items');
        Slider.el_items = $('.items',  Slider.el_context).children();
        Slider.amount = Slider.el_items.length;
        Slider.pageCount = Math.floor((Slider.amount-1)/this.config.pageSize + 1);
        //Slider.index = 0;
        Slider.index = this.config.dataSelquence;//初始化显示的大图为那个TAB的某个顺序的图片 modify by Eric xuminwan@sohu-inc.com
        $("#image_des").html(Slider.dataItems[Slider.index]['desc']);
        $('.cur_num_nk').text(Slider.index - 0 + 1);
        $('.total_nk').text(Slider.amount);
		
        var instance = this;		
		$('.scroll_next').removeClass('scroll_next_active');
		if(Slider.dataItems.length > 1){
			$('.scroll_next').addClass('scroll_next_active');
		}
		
        $('.scroll_prev').click(function(event){
            doLoad(Slider.index - 1);
        });
        $('.scroll_next').click(function(event){
			if($(this).hasClass('scroll_next_active')){
				doLoad(Slider.index + 1);
			}
        });
        $('.fs_next', $('.full_size_wapper_nk')).click(function(){
			if(Slider.index<Slider.dataItems.length-1){
				doLoad(Slider.index + 1);
			}
        });
        $('.fs_prev', $('.full_size_wapper_nk')).click(function(){
            doLoad(Slider.index - 1);
        });

        $('.nav_wapper_nk').delegate('span[type]', 'click', function(){
            $('.nav_wapper_nk span').removeClass('current');
            $(this).addClass('current');
        });

        function doLoad(index) {
            instance.jumpTo(index, 300, function(){
                instance.find_thumbs(7);
                instance.find_fullSize();
            });
        };


        Slider.el_slider.delegate('.item', 'click', function(event){
            var _el_index = $(this).data('index');
            instance.jumpTo(_el_index, 300, function(){
                instance.find_thumbs(7);
                instance.find_fullSize();
            });
        });

        instance.find_thumbs(7);
        instance.find_fullSize();
        //Slider.el_items.eq(0).css('border-color', '#1369C0');
        //Slider.el_items.eq(0).addClass('item_curr');
        Slider.el_items.eq(this.config.dataSelquence).addClass('item_curr');//设定小图高亮选中的图片顺序 modify by Eric xuminwan@sohu-inc.com
        $('.fs_prev').css('display','none');
		
    }



    Slider.prototype.jumpTo = function(targe_index, timer, callBack) {
        if (targe_index < 0 || targe_index == Slider.amount) return false;
        if (targe_index != undefined) {
            Slider.index = targe_index;
        }
        var _left_pix = 0;
		
		if(Slider.index>0){
			$('.scroll_prev').addClass('scroll_prev_active');
			$('.fs_prev').css('display','block');
		}else{
			$('.scroll_prev').removeClass('scroll_prev_active');
			$('.fs_prev').css('display','none');
		}
		if(Slider.dataItems.length >1){
			if(Slider.index < Slider.dataItems.length-1){
				$('.scroll_next').addClass('scroll_next_active');
				$('.fs_next').css('display','block');
			}else{
				$('.scroll_next').removeClass('scroll_next_active');
				$('.fs_next').css('display','none');
			}
		}else{
			$('.scroll_next').removeClass('scroll_next_active');
		}

        /*$('.item').css('border-color', '#eeeeee');
        Slider.el_items.eq(targe_index).css('border-color', '#1369C0');*/
        $('.item').removeClass('item_curr');
        Slider.el_items.eq(targe_index).addClass('item_curr');
        if(Slider.amount > 7){
            if (targe_index >= 4) {
                _left_pix =  -Slider.el_items.eq(targe_index - 2).position().left;
            }
            if (targe_index > Slider.amount -7) {
                var _lp = Slider.el_items.eq(Slider.amount).position();
                if (_lp) {
                    _left_pix = -_lp.left;
                }
            }
        }

        $('.cur_num_nk').text(Slider.index - 0 + 1);
        $("#image_des").html(Slider.dataItems[Slider.index]['desc']);

        Slider.el_slider.animate({left: _left_pix}, timer||200, 'swing', callBack && callBack.call());
    }



    Slider.prototype.find_thumbs = function(how_many) {
        var _count = 1;
        var _currentType = Slider.dataType;/*����첽���ص�ͼƬ��δ���ʱ������dataType���л����򲻽���append*/
        /*������7��δ���ص�Сͼ���м���*/
        for (var i= 0; i<Slider.dataItems.length; i++ ) {
            if (!Slider.dataItems[i].loaded && _count<=how_many) {
                _count ++;
                Slider.dataItems[i].loaded = true;
                imgReady(Slider.dataItems[i].s_url, (function(i){
                    return function(image) {
                        if (_currentType == Slider.dataType && Slider.el_items.eq(i).find('img').length ==0) {
                            Slider.el_items.eq(i).append(image);
                        }
                    }
                })(i), (function(i){
                    return function() {
                        if (_currentType == Slider.dataType) {
                            if (Slider.dataItems[i]) Slider.dataItems[i].loaded = true;
                        }
                    }
                })(i));
            }
        }
    }

    Slider.prototype.find_fullSize = function(how_many) {
        var _currentType = Slider.dataType;
        var _index = Slider.index;
        var large = Slider.dataItems[Slider.index]|| {};
        var large_next = Slider.dataItems[Slider.index + 1]|| {};
        $('.bd_nk').css({'background-position':'50% 50%'});
        if (!large.large_loaded) {
            /*���ص�ǰ��ͼ*/
            imgReady((Slider.dataItems[Slider.index]|| {}).l_url, function(image){
                if (_currentType == Slider.dataType && Slider.index == _index) {
                    $('.bd_nk').empty().append(image);
                    $(image).css({
                        'margin-top': (450- $(image).height())/2 + 'px'
                    });                    
                    $('.bd_nk').css({'background-position':'-100px -100px'});
                }
            }, function(){
            });
        }

        if (!large_next.large_loaded) {
            /*������һ�Ŵ�ͼ*/
            imgReady(large_next.l_url, function(){
            }, function(){
                large_next.large_loaded = true;
            });
        }
    };

    /**
     * [��ʱ���ж϶����е�image�Ƿ�complete]
     * @return {[type]} [�հ�غ���]
     */
    var imgReady = (function () {
        var list = [], intervalId = null,

        // ����ִ�ж���
        tick = function () {
            var i = 0;
            for (; i < list.length; i++) {
                list[i].end ? list.splice(i--, 1) : list[i]();
            };
            !list.length && stop();
        },

        // ֹͣ���ж�ʱ������
        stop = function () {
            clearInterval(intervalId);
            intervalId = null;
        };

        return function (url, ready, error) {
            var onready,
                img = new Image();
            /*�����ϸ���֤*/
            if (!url) return;
     
            img.src = url;
          
            // ���ͼƬ�����棬��ֱ�ӷ��ػ������
            if (img.complete) {
                ready.call(img, img);
                return;
            };
            
            // ���ش������¼�
            img.onerror = function () {
                error && error.call(img);
                onready.end = true;
                img = img.onload = img.onerror = null;
            };
            
            // ͼƬ������
            onready = function () {
                if (img.complete) {
                    onready.end = true;
                    ready.call(img, img);
                };
            };
            onready();
            
            // ��������ж���ִ��
            if (!onready.end) {
                list.push(onready);
                // ���ۺ�ʱֻ�������һ����ʱ��������������������
                if (intervalId === null) intervalId = setInterval(tick, 40);
            };
        };
    })();

})(jQuery);
