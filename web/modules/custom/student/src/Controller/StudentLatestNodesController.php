<?php

namespace Drupal\student\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\student\NodeWithImages;

/**
 * Returns responses for Render Arrays routes.
 */
class StudentLatestNodesController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Active database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * Custom nodes data manager.
   *
   * @var \Drupal\student\NodeWithImages
   */
  protected NodeWithImages $nodeWithImage;

  /**
   * Constructs object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection to be used.
   */

  /**
   * Constructs object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   Database connection.
   * @param \Drupal\student\NodeWithImages $nodeWithImages
   *   Custom nodes data-manager.
   */
  public function __construct(Connection $database, NodeWithImages $nodeWithImages) {
    $this->database = $database;
    $this->nodeWithImage = $nodeWithImages;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('student.service1'),
    );
  }

  /**
   * Builds the response.
   */
  public function build() {
    $build[] = [
      '#theme' => 'student_latest_node_theme',
      '#items' => $this->nodeWithImage->getNodesFields(),
      '#otherr' => 5,
    ];

    return $build;
  }

}
