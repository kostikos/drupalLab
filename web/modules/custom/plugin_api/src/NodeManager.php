<?php

namespace Drupal\plugin_api;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class NodeManager {

  /**
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entity;


  /**
   * @param EntityTypeManagerInterface $entity
   */
  public function __construct(EntityTypeManagerInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container): static
  {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
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
