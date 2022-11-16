<?php

namespace Drupal\ajaxform\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Ajax Form routes.
 */
class AjaxformController extends ControllerBase {

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
