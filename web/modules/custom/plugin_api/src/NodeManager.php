<?php

namespace Drupal\plugin_api;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * A service that retrieves a node with a picture.
 */
class NodeManager {

  /**
   * Entity typeManager interface service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entity;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected ConfigFactory $configFactory;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity
   *   Entity Type Manager Interface service.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   Config Factory service.
   */
  public function __construct(EntityTypeManagerInterface $entity, ConfigFactory $configFactory) {
    $this->entity = $entity;
    $this->configFactory = $configFactory;

  }

  /**
   * Create method.
   *
   * @param \Drupal\Component\DependencyInjection\ContainerInterface $container
   *   Dependency injection.
   *
   * @return static
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
    );
  }

  /**
   * Get the latest nodes with not empty fields field_preview_picture.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getNodesFields(): array {
    $result = [];
    $config = $this->configFactory->get('plugin_api.settings');
    $nodeStorage = $this->entity->getStorage('node');
    $nodeType = $config->get('node_type');
    $nodeCount = $config->get('node_count');

    $query = $nodeStorage->getQuery()
      ->exists('field_preview_picture');

    if ($nodeType) {
      $query->condition('type', $nodeType);
    }

    if ($nodeCount) {
      $query->pager($nodeCount);
    }

    $nodesIds = $query->sort('created', 'ASC')->execute();
    $articles = $nodeStorage->loadMultiple($nodesIds);

    foreach ($articles as $key => $article) {
      $uri = $article->get('field_preview_picture')->entity->uri->value;
      $result[$key]['url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
      $result[$key]['title'] = $article->get('title')->getValue('value');
    }

    return $result;
  }

}
