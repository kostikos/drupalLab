<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Example simple controller.
 */
class FirstController extends ControllerBase {

  /**
   * Simple controller realisation.
   *
   * @return string[]
   *   test markup.
   */
  public function content() {
    return [
      '#markup' => 'Test',
    ];
  }

}
