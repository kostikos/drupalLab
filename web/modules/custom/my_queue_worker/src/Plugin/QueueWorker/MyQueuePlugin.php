<?php

namespace Drupal\my_queue_worker\Plugin\QueueWorker;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines 'my_queue_plugin' queue worker.
 *
 * @QueueWorker(
 *   id = "my_queue_plugin",
 *   title = @Translation("Examples of queue worker."),
 *   cron = {"time" = 60}
 * )
 */
class MyQueuePlugin extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   */
  protected $logger;

  /**
   * Constructs a new class instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   Entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $loggerFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $loggerFactory->get('my_queue_worker');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    \Drupal::logger('my_queue_worker')->notice('test message from plugin');
    $this->logger->notice(
      '@user (uid: @uid) performed @op on @entity at @timestamp',
      [
        '@user' => $data['user'],
        '@uid' => $data['uid'],
        '@op' => $data['op'],
        '@entity' => $data['entity'],
        '@timestamp' => $data['timestamp'],
      ],
    );
  }

}

