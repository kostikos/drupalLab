<?php

namespace Drupal\student\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Render Arrays routes.
 */
class StudentLatestNodesController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build()
  {

    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $nodeStorage->getQuery()
      ->exists('field_preview_picture')
      ->sort('created')
      ->pager(4)
      ->execute();

    $articles = $nodeStorage->loadMultiple($ids);
    $rez = [];

    foreach ($articles as $key => $article) {
      $uri = $article->get('field_preview_picture')->entity->uri->value;
      $rez[$key]['url'] = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
      $rez[$key]['title'] = $article->get('title')->getValue('value');
    }

    $build[] = [
      '#theme' => 'student_latest_node_theme',
      '#items' => $rez,
      '#otherr' => 5,
    ];

    return $build;
  }

}
