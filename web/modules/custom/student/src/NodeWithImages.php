<?php

namespace Drupal\student;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileUrlGenerator;

/**
 * Class for examples work with nodes.
 */
class NodeWithImages {

  /**
   * Active entity.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Generator url service.
   *
   * @var \Drupal\Core\File\FileUrlGenerator
   */
  protected FileUrlGenerator $generator;

  /**
   * Construct form service.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity Type Manager service.
   * @param \Drupal\Core\File\FileUrlGenerator $generator
   *   File Url Generator service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FileUrlGenerator $generator) {
    $this->entityTypeManager = $entity_type_manager;
    $this->generator = $generator;
  }

  /**
   * Get nodes with not empty property field_preview_picture.
   *
   * @return array
   *   array where keys are ids of nodes containing:
   *   - url: A url to node preview picture.
   *   - title: Nodes title.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getNodesFields(): array {

    $nodeStorage = $this->entityTypeManager->getStorage('node');

    $nodesIds = $nodeStorage->getQuery()
      ->exists('field_preview_picture')
      // ->condition('type', 'article') // type = bundle id (machine name)
    // sorted by time of creation
      ->sort('created', 'ASC')
    // Limit 4 items.
      ->pager(4)
      // ->range(0, 10)
      ->execute();

    $articles = $nodeStorage->loadMultiple($nodesIds);
    $result = [];

    foreach ($articles as $key => $article) {
      $uri = $article->get('field_preview_picture')->entity->uri->value;
      $result[$key]['url'] = $this->generator->generateAbsoluteString($uri);
      $result[$key]['title'] = $article->get('title')->getValue('value');
    }

    return $result;
  }

}
