(function ($) {
  Drupal.behaviors.document_outline_click_states = {
    attach:function(context) {
      $('#the_75th_anniversary_banner.noscript', context).removeClass('noscript').each(function() {
        var banner = $(this);

        $(this).find('a.close_window').each(function(index) {
          $(this).click(function(event) {
            $(banner).remove();
          });
        });
      });
    }
  }
})(jQuery);
