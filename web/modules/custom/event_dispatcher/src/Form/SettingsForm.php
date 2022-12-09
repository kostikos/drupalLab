<?php

namespace Drupal\event_dispatcher\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Default configure Plugin API settings for this site make by drush.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_dispatcher_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['event_dispatcher.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['query_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of param what you want to add'),
      '#default_value' => $this->config('event_dispatcher.settings')
        ->get('query_name'),
    ];
    $form['query_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Value of param what you want to add.'),
      '#default_value' => $this->config('event_dispatcher.settings')
        ->get('query_value'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @todo Make some validation.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('event_dispatcher.settings')
      ->set('query_name', $form_state->getValue('query_name'))
      ->set('query_value', $form_state->getValue('query_value'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
