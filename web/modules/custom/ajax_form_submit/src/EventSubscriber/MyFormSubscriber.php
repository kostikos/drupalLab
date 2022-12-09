<?php

namespace Drupal\ajax_form_submit\EventSubscriber;

use Drupal\ajax_form_submit\Event\AjaxFormSubmitEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Dummy event subscriber.
 */
class MyFormSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      AjaxFormSubmitEvent::EVENT_STARTED => ['exampleEventListener', 100],
    ];
  }

  /**
   * Example for AjaxFormSubmitEvent::EVENT_STARTED.
   */
  public function exampleEventListener(AjaxFormSubmitEvent $event) {
    /** @var \Drupal\Core\Messenger\MessengerInterface $messenger */
    $messenger = \Drupal::service('messenger');
    $variables = $event->getFormvalues();
    $messenger->addMessage("Event for ajax.form.action  called. Result = $variables");
    $event->stopPropagation();
  }

}
