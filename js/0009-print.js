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
