<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for My first module routes.
 */
class FormResaultPage extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build(Request $request): array
  {

    $build[] = [
      '#theme' => 'mymodule_form_result',
      '#name' => $request->query->get('name'),
      '#age' => $request->query->get('age'),
      '#gender' => $request->query->get('gender'),
      '#email' => $request->query->get('email'),
      '#cache' => ['contexts' => ['url.query_args:name']],
    ];

    return $build;
  }

}
