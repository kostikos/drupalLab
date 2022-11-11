(function ($, Drupal, once) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      let slick = $('.js-slider', context).slick({
        dots: true,
        infinite: true,
        speed: 700,
        fade: true,
        cssEase: 'linear'
      });
      console.log(slick);
    }
  };
})(jQuery, Drupal, once);
