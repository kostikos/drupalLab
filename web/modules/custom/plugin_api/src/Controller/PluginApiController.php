<?php

namespace Drupal\plugin_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Plugin API routes.
 */
class PluginApiController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {


    $block = \Drupal\block_content\Entity\BlockContent::load('plugin_api_example');
    $render = \Drupal::entityTypeManager()->
    getViewBuilder('block_content')->view($block);
    return $render;
//
//    $build['content'] = [
//      '#type' => 'item',
//      '#markup' => $this->t('It works!'),
//    ];
//
//    return $build;
  }

}
