/**
 * JS lib
 *
 * @author mole <mole.chen@foxmail.com>
 * @version $Id: libesf.js 19222 2012-11-26 10:26:47Z sunhailong $
 */
(function(window) {
	var document = window.document,
		alert = window.alert,
		confirm = window.confirm,
		$ = window.jQuery;
	var ESF = {
		Config: {},
		Api: {},
		Widget: {}
	};

	ESF.autoCompleteDefault = {
		url: "/ajax/wj31_get_suggest_house.php?is_showb=0",
		dataType: "json",
		matchSubset: true,
		highlight: false,
		scroll: false,
		parse: function (data) {
			var rows = [], i;
			if ($.isArray(data)) {
				for (i = 0; i < data.length; i++) {
					rows.push({
						data: data[i],
						value: data[i].house_name,
						result: data[i].house_name
					});
				}
			}
			return rows;
		},
		formatItem: function(data, i, max, value, term) {
			return value;
		}
	};
	
	ESF.Widget = {
		// 加入收藏�?
		addFavorite: function(url, title) {
			url = url || window.location.href;
			title = title || document.title;
			try {
				window.external.addFavorite(url, title);
			} catch (e) {
				try {
					window.sidebar.addPanel(title, url, "");
				} catch (ee) {
					// 加入收藏失败，请使用Ctrl+D进行添加
					alert("\u52a0\u5165\u6536\u85cf\u5931\u8d25\uff0c\u8bf7\u4f7f\u7528Ctrl+D\u8fdb\u884c\u6dfb\u52a0");
				}
			}
		},
		
		// 输入小区�? ,查小区房�?
		rateSearch: function(s, options, autoOptions) {
			autoOptions = $.extend(ESF.autoCompleteDefault, autoOptions);

			$(s).each(function() {
				var $text = $("input:text", this),
					$submit = $("input:submit", this),
					placeholder = $text.val();

				// Text input bind event
				$text.focus(function(e) {
					if ($.trim($text.val()) == placeholder) {
						$text.val('');
					}
				}).blur(function(e) {
					if ($.trim($text.val()) == '') {
						$text.val(placeholder);
					}
				})
				.autocomplete(null, autoOptions)
				.result(function(e, data, value) {
					$(this).data("ESF.Widget.rateSearch.data", {data: data, value: value});
					if (data) {
						window.open("/xiaoqu/" + data.house_id + "/jiage/");
					}
				}).blur();

				$submit.click(function(e) {
					var data = $text.data("ESF.Widget.rateSearch.data"),
						value = $.trim($text.val());

					if (data && value && value == data.value) {
						$text.trigger("result", [data.data, data.value]);
					} else if (value) {
						if (value == placeholder) {
							window.location = "/xiaoqu/";
						} else {
							window.location = "/xiaoqu/?k=" + value;
						}
						
						return true;
					}
					return false;
				});
			});
		},

		// 输入小区名，与本小区房价对比
		tendCompare: function(s, options, autoOptions) {
			autoOptions = $.extend(ESF.autoCompleteDefault, autoOptions, {clickFire: true});
			options = $.extend({
				parkId: 0,
				relateId: 0,
				length: 12
			}, options);
			$(s).each(function() {
				var $text = $("input:text", this),
					$submit = $("input:submit", this),
					placeholder = $text.val();
				
				// Text input bind event
				$text.focus(function(e) {
					if ($.trim($text.val()) == placeholder) {
						$text.val('');
					}
				}).blur(function(e) {
					if ($.trim($text.val()) == '') {
						$text.val(placeholder);
					}
				})
				.autocomplete(null, autoOptions)
				.result(function(e, data, value) {
					if (!data) {
						$text.val(placeholder);
						return;
					}
					options.relateId = data.park_id;
					$submit.trigger('compare');
				});
					
				// 对比按钮，用于加载数�?
				$submit.bind('compare', function(e, refresh) {
					var $this = $(this),
						id = $this.data('id') || 0;
					if (id && id == options.relateId && !refresh) {
						return false;
					}
					$this.data('id', options.relateId);
					$(options.placeholder).load(options.url, {
						park_id: options.parkId,
						relate_id: options.relateId,
						length: options.length
					});

					return false;
				}).click(function(e) {
					$text.focus().trigger('click');
					return false;
				}).trigger('compare');

				// 浏览过小�?
				$(options.trigger).click(function(e) {
					options.relateId = $(this).data('id');
					$submit.trigger('compare');
				});
				$(options.change).click(function(e) {
					var $this = $(this);
					options.length = $this.children("a[title='click']").attr('value');
					$submit.trigger('compare', [true]);
				});
			});
		},
		
		// 则边工具栏随窗口滚动
		toolbar: function(s, ready) {
			function _init() {
				if (!($.browser.msie && $.browser.version == "6.0")) {
					return;
				}
				$(window).scroll(function(e) {
					var sTop = $(document).scrollTop(),
						wh = $(window).height();
					$(s).each(function() {
						var $this = $(this), 
							h = $this.height();
						$this.css('top', wh - h + sTop);
					});
				});
				
				// first run
				$(window).scroll();
			}

			if (ready) {
				$(_init);
			} else {
				_init();
			}
			
		}
	};

	window.ESF = ESF;
}(window));