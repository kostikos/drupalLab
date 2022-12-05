<?php

namespace Drupal\plugin_api\Form;

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
    return 'plugin_api_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['plugin_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['example'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Example'),
      '#default_value' => $this->config('plugin_api.settings')->get('example'),
    ];
    $form['node_count'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node count'),
      '#default_value' => $this->config('plugin_api.settings')->get('node_count'),
    ];
    $form['node_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node type'),
      '#default_value' => $this->config('plugin_api.settings')->get('node_type'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('node_count') > 5) {
      $form_state->setErrorByName('node_count', $this->t('The value is not correct.'));
    }
    if (!in_array($form_state->getValue('node_type'), ['blog', 'article'])) {
      $form_state->setErrorByName('node_type', $this->t('The value is not correct.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('plugin_api.settings')
      ->set('node_count', $form_state->getValue('node_count'))
      ->set('node_type', $form_state->getValue('node_type'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
