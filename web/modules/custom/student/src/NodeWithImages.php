<?php

namespace Drupal\student;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use \Drupal\Core\File\FileUrlGenerator;

class NodeWithImages
{

  /**
   * Active entity.
   *
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Generator url service
   * @var FileUrlGenerator
   */
  protected FileUrlGenerator $generator;

  /**
   * Construct form service
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   *   Entity Type Manager service
   * @param \Drupal\Core\File\FileUrlGenerator $generator
   *   File Url Generator service
   *
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager,  FileUrlGenerator $generator)
  {
    $this->entityTypeManager = $entity_type_manager;
    $this->generator = $generator;
  }

  /**
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getNodesFields(): array
  {

    $nodeStorage = $this->entityTypeManager->getStorage('node');

    $nodesIds = $nodeStorage->getQuery()
      ->exists('field_preview_picture')
      // ->condition('type', 'article') // type = bundle id (machine name)
      ->sort('created', 'ASC') // sorted by time of creation
      ->pager(4) // limit 4 items
      //->range(0, 10)
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
