<?php

namespace Drupal\studentregistration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for studentregistration routes.
 */
class StudentregistrationController extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build(Request $request)
  {
    var_dump($current_path = \Drupal::service('path.current')->getPath());
    if ($request->query->has('result_id')) {
      $studentId = $request->query->get('result_id');
      $database = \Drupal::database();
      $query = $database->select('students')
        ->condition('students.uid', $studentId)
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
        '#id' => $studentId,
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
