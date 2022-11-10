<?php

namespace Drupal\studentregistration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\redirect\Entity\Redirect;
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
    if ($request->query->has('result_id')) {
      $studentId = $request->query->get('result_id');
      $database = \Drupal::database();
      $query = $database->select('students')
        ->condition('students.uid', $studentId)
        ->fields('students', ['uid', 'name', 'rollnumner', 'email', 'phone', 'dob', 'gender'])
        ->range(0, 1);
      $result = $query->execute()->fetchAssoc();
      $schema = \Drupal::service('update.update_hook_registry')->getAvailableUpdates('studentregistration');
      //$schema = drupal_get_schema_versions('');

      $database = \Drupal::database();
      $query = $database->select('students')
        ->condition('average_mark', 0)
        ->fields('students', ['average_mark', 'uid']);
      $result = $query->execute()->fetchAssoc();
      if ($result) {
        foreach ($result as $item) {
          $avg_updated = \Drupal\Core\Database\Database::getConnection()->update('students')
            ->fields([
              'average_mark' => rand(1, 12),
            ])
            ->condition('uid', 5)->execute();

        }
      }

      $build[] = [
        '#theme' => 'studentregistration_result',
        '#name' => $result['name'],
        //'#age' => $result['age'],
        '#gender' => $result['gender'],
        '#email' => $result['email'],
        '#phone' => $result['phone'],
        '#dob' => $result['dob'],
        '#rollnumner' => $result['rollnumner'],
        '#id' => $schema,
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
