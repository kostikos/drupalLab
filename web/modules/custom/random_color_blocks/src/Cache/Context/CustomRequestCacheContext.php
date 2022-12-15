<?php

namespace Drupal\random_color_blocks\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CalculatedCacheContextInterface;
use Drupal\Core\Cache\Context\RequestStackCacheContextBase;

/**
 * Defines the ExampleCacheContext service.
 *
 * Cache context ID: 'custom_request'.
 *
 * @DCG
 * Check out the core/lib/Drupal/Core/Cache/Context directory for examples of
 * cache contexts provided by Drupal core.
 */
class CustomRequestCacheContext extends RequestStackCacheContextBase implements CalculatedCacheContextInterface {

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Custom request');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext($parameter = NULL) {
    // Change cache depending on the time of day.
    // date('H') >= 22 || date('H') <= 07) {.
    if (rand(0, 1)) {

      $context = 'some_string_value_day';
    }
    else {
      $context = 'some_string_value_night';
    }

    return $context;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($parameter = NULL) {
    return new CacheableMetadata();
  }

}
