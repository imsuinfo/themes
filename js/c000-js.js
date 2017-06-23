/*!
	Autosize v1.17.8 - 2013-09-07
	Automatically adjust textarea height based on user input.
	(c) 2013 Jack Moore - http://www.jacklmoore.com/autosize
	license: http://www.opensource.org/licenses/mit-license.php
*/
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals: jQuery or jQuery-like library, such as Zepto
		factory(window.jQuery || window.$);
	}
}(function ($) {
	var
	defaults = {
		className: 'autosizejs',
		append: '',
		callback: false,
		resizeDelay: 10
	},

	// border:0 is unnecessary, but avoids a bug in FireFox on OSX
	copy = '<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; padding: 0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>',

	// line-height is conditionally included because IE7/IE8/old Opera do not return the correct value.
	typographyStyles = [
		'fontFamily',
		'fontSize',
		'fontWeight',
		'fontStyle',
		'letterSpacing',
		'textTransform',
		'wordSpacing',
		'textIndent'
	],

	// to keep track which textarea is being mirrored when adjust() is called.
	mirrored,

	// the mirror element, which is used to calculate what size the mirrored element should be.
	mirror = $(copy).data('autosize', true)[0];

	// test that line-height can be accurately copied.
	mirror.style.lineHeight = '99px';
	if ($(mirror).css('lineHeight') === '99px') {
		typographyStyles.push('lineHeight');
	}
	mirror.style.lineHeight = '';

	$.fn.autosize = function (options) {
		if (!this.length) {
			return this;
		}

		options = $.extend({}, defaults, options || {});

		if (mirror.parentNode !== document.body) {
			$(document.body).append(mirror);
		}

		return this.each(function () {
			var
			ta = this,
			$ta = $(ta),
			maxHeight,
			minHeight,
			boxOffset = 0,
			callback = $.isFunction(options.callback),
			originalStyles = {
				height: ta.style.height,
				overflow: ta.style.overflow,
				overflowY: ta.style.overflowY,
				wordWrap: ta.style.wordWrap,
				resize: ta.style.resize
			},
			timeout,
			width = $ta.width();

			if ($ta.data('autosize')) {
				// exit if autosize has already been applied, or if the textarea is the mirror element.
				return;
			}
			$ta.data('autosize', true);

			if ($ta.css('box-sizing') === 'border-box' || $ta.css('-moz-box-sizing') === 'border-box' || $ta.css('-webkit-box-sizing') === 'border-box'){
				boxOffset = $ta.outerHeight() - $ta.height();
			}

			// IE8 and lower return 'auto', which parses to NaN, if no min-height is set.
			minHeight = Math.max(parseInt($ta.css('minHeight'), 10) - boxOffset || 0, $ta.height());

			$ta.css({
				overflow: 'hidden',
				overflowY: 'hidden',
				wordWrap: 'break-word', // horizontal overflow is hidden, so break-word is necessary for handling words longer than the textarea width
				resize: ($ta.css('resize') === 'none' || $ta.css('resize') === 'vertical') ? 'none' : 'horizontal'
			});

			// The mirror width must exactly match the textarea width, so using getBoundingClientRect because it doesn't round the sub-pixel value.
			function setWidth() {
				var style, width;
				
				if ('getComputedStyle' in window) {
					style = window.getComputedStyle(ta);
					width = ta.getBoundingClientRect().width;

					$.each(['paddingLeft', 'paddingRight', 'borderLeftWidth', 'borderRightWidth'], function(i,val){
						width -= parseInt(style[val],10);
					});

					mirror.style.width = width + 'px';
				}
				else {
					// window.getComputedStyle, getBoundingClientRect returning a width are unsupported and unneeded in IE8 and lower.
					mirror.style.width = Math.max($ta.width(), 0) + 'px';
				}
			}

			function initMirror() {
				var styles = {};

				mirrored = ta;
				mirror.className = options.className;
				maxHeight = parseInt($ta.css('maxHeight'), 10);

				// mirror is a duplicate textarea located off-screen that
				// is automatically updated to contain the same text as the
				// original textarea.  mirror always has a height of 0.
				// This gives a cross-browser supported way getting the actual
				// height of the text, through the scrollTop property.
				$.each(typographyStyles, function(i,val){
					styles[val] = $ta.css(val);
				});
				$(mirror).css(styles);

				setWidth();

				// Chrome-specific fix:
				// When the textarea y-overflow is hidden, Chrome doesn't reflow the text to account for the space
				// made available by removing the scrollbar. This workaround triggers the reflow for Chrome.
				if (window.chrome) {
					var width = ta.style.width;
					ta.style.width = '0px';
					var ignore = ta.offsetWidth;
					ta.style.width = width;
				}
			}

			// Using mainly bare JS in this function because it is going
			// to fire very often while typing, and needs to very efficient.
			function adjust() {
				var height, original;

				if (mirrored !== ta) {
					initMirror();
				} else {
					setWidth();
				}

				mirror.value = ta.value + options.append;
				mirror.style.overflowY = ta.style.overflowY;
				original = parseInt(ta.style.height,10);

				// Setting scrollTop to zero is needed in IE8 and lower for the next step to be accurately applied
				mirror.scrollTop = 0;

				mirror.scrollTop = 9e4;

				// Using scrollTop rather than scrollHeight because scrollHeight is non-standard and includes padding.
				height = mirror.scrollTop;

				if (maxHeight && height > maxHeight) {
					ta.style.overflowY = 'scroll';
					height = maxHeight;
				} else {
					ta.style.overflowY = 'hidden';
					if (height < minHeight) {
						height = minHeight;
					}
				}

				height += boxOffset;

				if (original !== height) {
					ta.style.height = height + 'px';
					if (callback) {
						options.callback.call(ta,ta);
					}
				}
			}

			function resize () {
				clearTimeout(timeout);
				timeout = setTimeout(function(){
					var newWidth = $ta.width();

					if (newWidth !== width) {
						width = newWidth;
						adjust();
					}
				}, parseInt(options.resizeDelay,10));
			}

			if ('onpropertychange' in ta) {
				if ('oninput' in ta) {
					// Detects IE9.  IE9 does not fire onpropertychange or oninput for deletions,
					// so binding to onkeyup to catch most of those occasions.  There is no way that I
					// know of to detect something like 'cut' in IE9.
					$ta.on('input.autosize keyup.autosize', adjust);
				} else {
					// IE7 / IE8
					$ta.on('propertychange.autosize', function(){
						if(event.propertyName === 'value'){
							adjust();
						}
					});
				}
			} else {
				// Modern Browsers
				$ta.on('input.autosize', adjust);
			}

			// Set options.resizeDelay to false if using fixed-width textarea elements.
			// Uses a timeout and width check to reduce the amount of times adjust needs to be called after window resize.

			if (options.resizeDelay !== false) {
				$(window).on('resize.autosize', resize);
			}

			// Event for manual triggering if needed.
			// Should only be needed when the value of the textarea is changed through JavaScript rather than user input.
			$ta.on('autosize.resize', adjust);

			// Event for manual triggering that also forces the styles to update as well.
			// Should only be needed if one of typography styles of the textarea change, and the textarea is already the target of the adjust method.
			$ta.on('autosize.resizeIncludeStyle', function() {
				mirrored = null;
				adjust();
			});

			$ta.on('autosize.destroy', function(){
				mirrored = null;
				clearTimeout(timeout);
				$(window).off('resize', resize);
				$ta
					.off('autosize')
					.off('.autosize')
					.css(originalStyles)
					.removeData('autosize');
			});

			// Call adjust in case the textarea already contains text.
			adjust();
		});
	};
}));
(function ($) {
  Drupal.behaviors.messages_click_states = {
    attach:function(context) {
      $('#mcneese-messages.noscript', context).removeClass('noscript').each(function() {
        $(this).click(function(event) {
          if ($(this).hasClass('expanded')) {
            $(this).removeClass('expanded');
            $(this).addClass('collapsed');
          } else {
            $(this).removeClass('collapsed');
            $(this).addClass('expanded');
          }
        });
      });

      $('#mcneese-help.noscript', context).removeClass('noscript').each(function() {
        $(this).click(function(event) {
          if ($(this).hasClass('expanded')) {
            $(this).removeClass('expanded');
            $(this).addClass('collapsed');
          } else {
            $(this).removeClass('collapsed');
            $(this).addClass('expanded');
          }
        });
      });
    }
  }
})(jQuery);
(function ($) {
  Drupal.behaviors.information_click_states = {
    attach:function(context) {
      $('#mcneese-information.noscript', context).removeClass('noscript').each(function() {
        $(this).click(function(event) {
          if ($(this).hasClass('expanded')) {
            $(this).removeClass('expanded');
            $(this).addClass('collapsed');
          } else {
            $(this).removeClass('collapsed');
            $(this).addClass('expanded');
          }
        });
      });
    }
  }
})(jQuery);
(function ($) {
  Drupal.behaviors.tabs_click_states = {
    attach:function(context) {
      $('#mcneese-tabs.noscript', context).removeClass('noscript').each(function() {
        if ($(this).hasClass('fixed')) {
          $(this).click(function(event) {
            if ($(this).hasClass('expanded')) {
              $(this).removeClass('expanded');
              $(this).addClass('collapsed');
            } else {
              $(this).removeClass('collapsed');
              $(this).addClass('expanded');
            }
          });
        }
        else {
          var tabs = $(this);

          $(tabs).children('.navigation_list').children('.tab').each(function() {
            if ($(this).hasClass('tab-command-1')) {
              $(this).children('a').each(function() {
                $(this).click(function() {
                  if ($(tabs).hasClass('expanded')) {
                    $(this).attr('title', 'Expand Menu Tabs');
                    $(tabs).removeClass('expanded');
                    $(tabs).addClass('collapsed');
                  } else {
                    $(this).attr('title', 'Collapse Menu Tabs');
                    $(tabs).removeClass('collapsed');
                    $(tabs).addClass('expanded');
                  }
                });
              });
            }
          });
        }
      });
    }
  }
})(jQuery);



/*** Breadcrumbs ***/
(function ($) {
  Drupal.behaviors.breadcrumb_click_states = {
    attach:function(context) {
      $('#mcneese-breadcrumb.noscript', context).removeClass('noscript').each(function() {
        if ($(this).hasClass('fixed')) {
          $(this).click(function(event) {
            if ($(this).hasClass('expanded')) {
              $(this).removeClass('expanded');
              $(this).addClass('collapsed');
            } else {
              $(this).removeClass('collapsed');
              $(this).addClass('expanded');
            }
          });
        }
      });
    }
  }
})(jQuery);



/*** Action Links ***/
(function ($) {
  Drupal.behaviors.action_links_click_states = {
    attach:function(context) {
      $('#mcneese-action_links.noscript', context).removeClass('noscript').each(function() {
        if ($(this).hasClass('fixed')) {
          $(this).click(function(event) {
            if ($(this).hasClass('expanded')) {
              $(this).removeClass('expanded');
              $(this).addClass('collapsed');
            } else {
              $(this).removeClass('collapsed');
              $(this).addClass('expanded');
            }
          });
        }
      });
    }
  }
})(jQuery);



/*** Toolbar ***/
(function ($) {
  Drupal.behaviors.toolbar_click_states = {
    attach:function(context) {
      $('#mcneese-toolbar.noscript', context).removeClass('noscript').each(function() {
        var toolbar = $(this);
        var menus = $(toolbar).children('.mcneese-toolbar-menu.noscript').removeClass('noscript');
        var shortcuts = $(toolbar).children('.mcneese-toolbar-shortcuts.noscript').removeClass('noscript');

        $(toolbar).focus(function () {
          if ($(toolbar).hasClass('autohide') && $(toolbar).hasClass('collapsed')) {
            $(toolbar).removeClass('collapsed');
            $(toolbar).addClass('expanded');
            $('body.mcneese').removeClass('is-toolbar-collapsed');
            $('body.mcneese').addClass('is-toolbar-expanded');
          }
        });

        $(toolbar).blur(function () {
          if ($(toolbar).hasClass('autohide') && $(toolbar).hasClass('expanded')) {
            $(toolbar).removeClass('expanded');
            $(toolbar).addClass('collapsed');
            $('body.mcneese').removeClass('is-toolbar-expanded');
            $('body.mcneese').addClass('is-toolbar-collapsed');
          }
        });

        $(toolbar).hover(function () {
          if ($(toolbar).hasClass('autohide') && $(toolbar).hasClass('collapsed')) {
            $(toolbar).removeClass('collapsed');
            $(toolbar).addClass('expanded');
            $('body.mcneese').removeClass('is-toolbar-collapsed');
            $('body.mcneese').addClass('is-toolbar-expanded');
          }
        },
        function () {
          if ($(toolbar).hasClass('autohide') && $(toolbar).hasClass('expanded')) {
            $(toolbar).removeClass('expanded');
            $(toolbar).addClass('collapsed');
            $('body.mcneese').removeClass('is-toolbar-expanded');
            $('body.mcneese').addClass('is-toolbar-collapsed');
          }
        });

        $(menus).each(function() {
          var menu = $(this);

          $(menu).children('.navigation_list').children('.item').each(function() {
            var item = $(this);

            $(item).children('.link.noscript').removeClass('noscript').each(function() {
              var a = $(this);

              $(a).focus(function () {
                $(toolbar).focus();
              });

              if ($(item).hasClass('mcneese-toolbar-toggle')) {
                $(a).removeAttr('href');

                $(a).click(function() {
                  $(shortcuts).each(function() {
                    if ($(this).hasClass('expanded')) {
                      $(this).removeClass('expanded');
                      $(this).addClass('collapsed');
                      $('body.mcneese').removeClass('is-toolbar-shortcuts-expanded');
                      $('body.mcneese').addClass('is-toolbar-shortcuts-collapsed');
                    } else {
                      $(this).removeClass('collapsed');
                      $(this).addClass('expanded');
                      $('body.mcneese').removeClass('is-toolbar-shortcuts-collapsed');
                      $('body.mcneese').addClass('is-toolbar-shortcuts-expanded');
                    }
                  });
                });
              }
            });
          });
        });

        $(shortcuts).each(function() {
          var shortcut = $(this);

          $(shortcut).children('.navigation_list').children('.mcneese-toolbar-sticky').each(function() {
            var sticky = $(this);

            $(sticky).children('.link.noscript').removeClass('noscript').each(function() {
              var a = $(this);

              $(a).focus(function () {
                $(toolbar).focus();
              });

              $(a).click(function() {
                $(toolbar).each(function() {
                  if ($(this).hasClass('relative')) {
                    $(this).removeClass('relative');
                    $(this).addClass('fixed');
                    $('body.mcneese').removeClass('is-toolbar-relative');
                    $('body.mcneese').addClass('is-toolbar-fixed');
                  } else if ($(this).hasClass('fixed')) {
                    $(this).removeClass('fixed');
                    $(this).addClass('relative');
                    $('body.mcneese').removeClass('is-toolbar-fixed');
                    $('body.mcneese').addClass('is-toolbar-relative');
                  }
                });
              });
            });
          });
        });
      });
    }
  }
})(jQuery);



/*** Side ***/
(function ($) {
  Drupal.behaviors.side_click_states = {
    attach:function(context) {
      $('#mcneese-page-side.noscript', context).removeClass('noscript').each(function() {
        if ($(this).hasClass('fixed')) {
          $(this).click(function(event) {
            if ($(this).hasClass('expanded')) {
              $(this).removeClass('expanded');
              $(this).addClass('collapsed');
            } else {
              $(this).removeClass('collapsed');
              $(this).addClass('expanded');
            }
          });
        }
      });
    }
  }
})(jQuery);



/*** Work Area Menu ***/
(function ($) {
  Drupal.behaviors.work_area_menu_click_states = {
    attach:function(context) {
      $('#mcneese-work_area_menu.noscript', context).removeClass('noscript').each(function() {
        $(this).each(function() {
          $(this).children('.html_tag-list').children('.html_tag-list_item').children('a').each(function() {
            var a = $(this);

            if ($(a).attr('id') == 'mcneese-work_area_menu-page_width') {
              $(a).click(function(event) {
                if ($(a).hasClass('work_area-state-on')) {
                  $(a).removeClass('work_area-state-on');
                  $(a).addClass('work_area-state-off');
                  $('body.mcneese').removeClass('is-flex_width');
                  $('body.mcneese').addClass('is-fixed_width');
                } else {
                  $(a).removeClass('work_area-state-off');
                  $(a).addClass('work_area-state-on');
                  $('body.mcneese').removeClass('is-fixed_width');
                  $('body.mcneese').addClass('is-flex_width');
                }
              });
            }
          });
        });
      });
    }
  }
})(jQuery);



/*** Print ***/
(function ($) {
  Drupal.behaviors.prepare_for_printing = {
    attach:function(context) {
      if (window.matchMedia) {
        window.matchMedia('print').addListener(function(media) {
          if (media.matches) {
            $('textarea').autosize();
          }
        });
      }
    }
  }
})(jQuery);
