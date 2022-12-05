<?php

namespace Drupal\plugin_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\plugin_api\NodeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "plugin_api_example",
 *   admin_label = @Translation("Example plugin"),
 *   category = @Translation("Plugin API")
 * )
 */
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Node manager object.
   *
   * @var \Drupal\plugin_api\NodeManager
   */
  protected NodeManager $nodeWithImage;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, NodeManager $nodeWithImages) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeWithImage = $nodeWithImages;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin_api.latest_node'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $node = $this->nodeWithImage->getNodesFields();

    $build[] = [
      '#theme' => 'plugin_api_theme',
      '#node' => $node,
    ];

    return $build;
  }

}
