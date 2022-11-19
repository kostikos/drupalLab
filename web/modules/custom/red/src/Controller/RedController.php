<?php

namespace Drupal\red\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Render Arrays routes.
 */
class RedController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
