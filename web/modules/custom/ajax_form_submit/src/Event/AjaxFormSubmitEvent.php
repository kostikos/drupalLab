<?php

namespace Drupal\ajax_form_submit\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Provides custom event object.
 */
class AjaxFormSubmitEvent extends Event {

  /**
   * Some event started.
   */
  const EVENT_STARTED = 'ajax.form.action';

  /**
   * The formValues value.
   *
   * @var string
   */
  protected string $formValues;

  /**
   * Constructs a formValuesEvent object.
   *
   * @param string $formValues
   *   The formValues value.
   */
  public function __construct(string $formValues) {
    $this->formValues = $formValues;
  }

  /**
   * Gets formValues value.
   *
   * @return string
   *   The formValues value.
   */
  public function getFormvalues() {
    return $this->formValues;
  }

}
