<?php

namespace Drupal\random_color_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "random_color_blocks_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Random colors blocks.")
 * )
 */
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected ConfigFactory $configFactory;

  /**
   * Account service.
   *
   * @var account\Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * Constructor.
   *
   * @param array $configuration
   *   Config.
   * @param string $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   Account service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, AccountProxyInterface $account) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->account = $account;
  }

  /**
   * Create method.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interface.
   * @param array $configuration
   *   Configurations.
   * @param string $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $colors = [];
    $config = $this->configFactory->get('random_color_blocks.settings');
    $blockCount = $config->get('block_count');

    for ($i = 0; $i < $blockCount; $i++) {
      $colors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    // date('H') >= 22 || date('H') <= 07) {.
    if (rand(0, 1)) {

      $backgroundColor = 'pink';
    }
    else {
      $backgroundColor = 'yellow';
    }
    $build['content2'] = [
      '#theme' => 'random_color_blocks_theme',
      '#user' => [
        'role' => $this->account->getRoles()[0],
        'name' => $this->account->getAccountName(),
      ],
      '#colors' => $colors,
      '#backgroundColor' => $backgroundColor,
      '#cache' => [
        'tags' => ['user:1', 'config:random_color_blocks'],
        'contexts' => ['url.query_args:colors'],
        // 'max-age' => 20,
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(
      parent::getCacheContexts(),
      ['custom_request']
    );
  }

}
