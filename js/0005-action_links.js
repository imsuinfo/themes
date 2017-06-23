


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
