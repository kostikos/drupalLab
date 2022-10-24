(function ($, Drupal, once) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      once('myCustomBehavior', 'input.myCustomBehavior', context).forEach(function (element) {
        $('.grid').masonry({
          // options
          itemSelector: '.grid-item',
          columnWidth: 600,
          columnHeight: 600
        });
      });
    }
  };
})(jQuery, Drupal, once);
