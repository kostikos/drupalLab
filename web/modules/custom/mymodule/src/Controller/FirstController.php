<?php

namespace Drupal\mymodule\Controller;

class FirstController
{

  public function content()
  {
    return [
      '#markup' => 'Test',
    ];
  }
}
