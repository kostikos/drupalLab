<?php

namespace Drupal\random_color_blocks\Controller;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Returns responses for Random colors blocks. routes.
 */
class RandomColorBlocksController extends ControllerBase {

  /**
   * Request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  public $request;

  /**
   * The cache backend service.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * Constructs a new HomeController object.
   */
  public function __construct(CacheBackendInterface $cache_backend, RequestStack $request) {
    $this->cacheBackend = $cache_backend;
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('random_color_blocks.my_cache'),
      $container->get('request_stack')
    );
  }

  /**
   * Builds the two blocks with the opposite cache.
   */
  public function build() {

    $color = $this->request->getCurrentRequest()->get('color');

    if ($color) {
      $cid = 'my_color_cache:' . $color;
    }
    else {
      $cid = 'my_color_cache: default';
    }

    $data_cached = $this->cacheBackend->get($cid);

    if (!$data_cached) {
      $data = $color ?: 'red';
      $this->cacheBackend->set($cid, $data, CacheBackendInterface::CACHE_PERMANENT, [$cid]);
    }
    else {
      $data = $data_cached->data;
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works! Color =') . $data,
      '#cache' => [
        'contexts' => ['url.query_args:color'],
        // 'max-age' => 0,
      ],
    ];

    $block_manager = \Drupal::service('plugin.manager.block');
    $block_plugin = $block_manager->createInstance('random_color_blocks_example', []);
    $build['my_block'] = $block_plugin->build();

    // Add the cache tags/contexts.
    \Drupal::service('renderer')
      ->addCacheableDependency($build['my_block'], $block_plugin);

    return $build;
  }

}
