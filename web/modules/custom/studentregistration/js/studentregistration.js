(function ($, Drupal, once) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      console.log('Module studentregistration enabled!');
    }
  };
})(jQuery, Drupal, once);
