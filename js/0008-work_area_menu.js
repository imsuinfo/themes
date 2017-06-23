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
