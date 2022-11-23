<?php

namespace Drupal\student;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use \Drupal\Core\Entity\EntityTypeManagerInterface;

class NodeWithImages {

  /**
   * Active entity.
   *
   * @var Connection
   */
  protected $entity;


  /**
   * Constructs a NodeWithImages object.
   *
   * @param Connection $database
   *   The database connection to be used.
   */
  public function __construct(EntityTypeManagerInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  public function getNodesFields(): array
  {

    $nodeStorage = $this->entity->getStorage('node');

    $nodesIds = $nodeStorage->getQuery()
      ->exists('field_preview_picture')
      // ->condition('type', 'article') // type = bundle id (machine name)
      ->sort('created', 'ASC') // sorted by time of creation
      ->pager(4) // limit 4 items
      //->range(0, 10)
      ->execute();

    $articles = $nodeStorage->loadMultiple($nodesIds);
    $rez=[];

    foreach ($articles as $key => $article) {
      $uri = $article->get('field_preview_picture')->entity->uri->value;
      $rez[$key]['url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
      $rez[$key]['title'] = $article->get('title')->getValue('value');
    }


    return $rez;
  }


}
