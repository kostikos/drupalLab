<?php

namespace Drupal\plugin_api;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class NodeManager
{

  /**
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entity;
  /**
   * @var ConfigFactory
   */
  protected ConfigFactory $configFactory;


  /**
   * @param EntityTypeManagerInterface $entity
   * @param ConfigFactory $configFactory
   */
  public function __construct(EntityTypeManagerInterface $entity, ConfigFactory $configFactory)
  {
    $this->entity = $entity;
    $this->configFactory = $configFactory;

  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container): static
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
    );
  }


  /**
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function getNodesFields(): array
  {
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
