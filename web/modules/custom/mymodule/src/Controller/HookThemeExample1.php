<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for My first module routes.
 */
class HookThemeExample1 extends ControllerBase
{

  /**
   * Builds the response.
   */
  public function build()
  {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    $build[] = [
      '#theme' => 'mymodule_example_theme',
      '#title' => t('Hello my first custom theme!'),
      '#description' => t('Some error from console to example:Could not connect to debugging client. Tried: host.docker.internal:9003;'),
      '#date' => '05_11_2022',
      '#link' => 'https://gooogle.com',
      '#tags' => 'sport',
    ];

    $build[] = [
      '#theme' => 'mymodule_example_theme',
      '#title' => t('Hello my first custom theme!1111'),
      '#description' => t('Some error from console to example:Could not connect to debugging client. Tried: host.docker.internal:9003;'),
      '#link' => 'https://gooogle.com',
      '#tags' => 'sport',
    ];
    $build[] = [
      '#theme' => 'mymodule_example_theme',
      '#title' => t('Hello my first custom theme!2222'),
      '#description' => t('Some error from console to example:Could not connect to debugging client. Tried: host.docker.internal:9003;'),
      '#date' => '05_11_2022',
    ];
    return $build;
  }

}
