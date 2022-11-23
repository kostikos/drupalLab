<?php

namespace Drupal\plugin_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "plugin_api_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Plugin API")
 * )
 */
class ExampleBlock extends BlockBase {





  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#markup' => $this->t('It works!222'),
    ];
    return $build;
  }

}
