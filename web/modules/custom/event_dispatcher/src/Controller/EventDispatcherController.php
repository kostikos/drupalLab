<?php

namespace Drupal\event_dispatcher\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Event dispatcher routes.
 */
class EventDispatcherController extends ControllerBase {

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
