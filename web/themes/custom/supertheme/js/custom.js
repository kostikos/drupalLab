(function ($, Drupal, once) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      once('myCustomBehavior', 'input.myCustomBehavior', context).forEach(function (element) {
        let $grid = $('.grid', context).masonry({
          // options
          itemSelector: '.grid-item',
          columnWidth: 600,
          columnHeight: 600
        });
        // change size of item by toggling gigante class
        $grid.on('click', '.grid-item', function () {
          $(this).toggleClass('gigante');
          // trigger layout after item size changes
          $grid.masonry('layout');
        });

      });
    }
  };
})(jQuery, Drupal, once);
