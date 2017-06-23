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
