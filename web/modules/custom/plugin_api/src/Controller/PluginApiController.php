<?php

namespace Drupal\plugin_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Plugin API routes.
 */
class PluginApiController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build()
  {
    $block_manager = \Drupal::service('plugin.manager.block');
    $config = ['node_count' => '2'];
    $block_plugin = $block_manager->createInstance('plugin_api_example', $config);
    $render = $block_plugin->build();

    //Add the cache tags/contexts.
    \Drupal::service('renderer')->addCacheableDependency($render, $block_plugin);

    return $render;
  }
}
