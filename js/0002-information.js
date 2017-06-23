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
