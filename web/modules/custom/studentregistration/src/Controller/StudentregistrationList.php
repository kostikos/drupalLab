<?php

namespace Drupal\studentregistration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for studentregistration routes.
 */
class StudentregistrationList extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build()
  {
    $database = \Drupal::database();
    $query = $database->select('students')
      ->fields('students', ['uid', 'name', 'rollnumner', 'average_mark', 'email', 'phone', 'dob', 'gender']);
    $result = $query->execute()->fetchAllAssoc('uid');

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
