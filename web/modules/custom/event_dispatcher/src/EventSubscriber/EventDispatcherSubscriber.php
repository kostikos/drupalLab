<?php

namespace Drupal\event_dispatcher\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Url;

/**
 * Event dispatcher event subscriber.
 */
class EventDispatcherSubscriber implements EventSubscriberInterface {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected ConfigFactory $configFactory;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   Config Factory service.
   */
  public function __construct(MessengerInterface $messenger, ConfigFactory $configFactory) {
    $this->messenger = $messenger;
    $this->configFactory = $configFactory;
  }

  /**
   * Create method.
   *
   * @param \Drupal\Component\DependencyInjection\ContainerInterface $container
   *   Dependency injection.
   *
   * @return static
   */
  public static function create(ContainerInterface $container): static {
    return new static($container->get('config.factory'));
  }

  /**
   * Check url for parameter from module setting. Added if needed.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The RequestEvent to process.
   */
  public function checkParameters(RequestEvent $event) {
    $request = $event->getRequest();
    $option = [
      'query' => $request->query->all(),
    ];
    $config = $this->configFactory->get('event_dispatcher.settings');
    $queryName = $config->get('query_name');
    $queryValue = $config->get('query_value');

    if (!isset($option['query'][$queryName]) ||  $option['query'][$queryName] != $queryValue) {
      $option['query'][$queryName] = $queryValue;
      $base_url = $request->getUriForPath($request->getPathInfo());
      $new_url = Url::fromUri($base_url, $option);
      $response = new RedirectResponse($new_url->toString(), 302);
      $event->setResponse($response);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['checkParameters'],
    ];
  }

}
