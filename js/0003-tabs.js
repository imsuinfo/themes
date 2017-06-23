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
