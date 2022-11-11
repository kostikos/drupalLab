<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for My first module routes.
 */
class FormResaultPageParamsInUrl extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build( $name, $age, $email  ): array
  {

    $build[] = [
      '#theme' => 'mymodule_form_result_long_url',
      '#name' => $this->t($name),
      '#age' => $this->t($age),
      '#gender' => $this->t(' default gender'),
      '#email' =>$this->t( $email),
      //'#cache' => ['contexts' => ['url.query_args:name']],
    ];

    return $build;
  }

}
