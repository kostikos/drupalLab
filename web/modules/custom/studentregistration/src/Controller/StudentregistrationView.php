<?php

namespace Drupal\studentregistration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for studentregistration routes.
 */
class StudentregistrationView extends ControllerBase
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

  public function build($id)
  {

    if ($id) {
      $query = $this->database->select('students')
        ->condition('students.uid', $id)
        ->fields('students', ['uid', 'name', 'rollnumner', 'average_mark', 'email', 'phone', 'dob', 'gender'])
        ->range(0, 1);
      $result = $query->execute()->fetchAssoc();
      //drupal_get_installed_schema_version('studentregistration');
      //drupal_set_installed_schema_version('studentregistration');


      $build[] = [
        '#theme' => 'studentregistration_result',
        '#name' => $result['name'],
        //'#age' => $result['age'],
        '#gender' => $result['gender'],
        '#email' => $result['email'],
        '#phone' => $result['phone'],
        '#dob' => $result['dob'],
        '#rollnumner' => $result['rollnumner'],
        '#average_mark' => $result['average_mark'],
        '#id' => $id,
        '#cache' => ['contexts' => ['url.query_args:result_id']],
      ];
    } else {
      $build['content'] = [
        '#type' => 'item',
        '#markup' => $this->t('An error occurred while executing the script, please try filling out the form again! <a href="/registration">Try</a>'),
      ];
    }

    return $build;
  }
}
