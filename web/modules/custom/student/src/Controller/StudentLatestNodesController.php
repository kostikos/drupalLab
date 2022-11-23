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
class StudentLatestNodesController extends ControllerBase implements ContainerInjectionInterface
{

  /**
   * Active database connection.
   *
   * @var Connection
   */
  protected $database;
  protected $nodeWithImage;


  /**
   * Constructs object.
   *
   * @param Connection $database
   *   The database connection to be used.
   */
  public function __construct(Connection $database, NodeWithImages $nodeWithImages)
  {
    $this->database = $database;
    $this->nodeWithImage = $nodeWithImages;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database'),
      $container->get('student.service1'),
    );
  }


  /**
   * Builds the response.
   */
  public function build()
  {
    $build[] = [
      '#theme' => 'student_latest_node_theme',
      '#items' => $this->nodeWithImage->getNodesFields(),
      '#otherr' => 5,
    ];

    return $build;
  }

}
