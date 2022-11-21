<?php

namespace Drupal\studentregistration\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for studentregistration routes.
 */
class StudentregistrationList extends ControllerBase implements ContainerInjectionInterface
{

  /**
   * Active database connection.
   *
   * @var Connection
   */
  protected $database;


  /**
   * Constructs object.
   *
   * @param Connection $database
   *   The database connection to be used.
   */
  public function __construct(Connection $database)
  {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('database'),
    );
  }


  /**
   * Builds the response.
   */
  public function build()
  {
    $result = $this->database->select('students')
      ->fields('students', ['uid', 'name', 'rollnumner', 'average_mark', 'email', 'phone', 'dob', 'gender'])
      ->execute()
      ->fetchAllAssoc('uid');

    if ($result) {
      $build[] = [
        '#theme' => 'studentregistration_list',
        '#students' => $result,
        // '#cache' => ['contexts' => ['students_list']],
        '#attached' => [
          'library' => [
            'studentregistration/studentregistration',
          ],
        ],
      ];
    } else {
      $build['content'] = [
        '#type' => 'item',
        '#markup' => $this->t('Database table students is empty. Go to  <a href="/registration">registry page</a> and register!'),
      ];
    }

    return $build;
  }
}
