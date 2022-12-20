<?php

namespace Drupal\random_color_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\CacheTagsInvalidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default configure random_color_blocks settings for this site make by drush.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Cache invalidator.
   *
   * @var Drupal\Core\Cache\CacheTagsInvalidator
   */
  protected $cacheInvalidator;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidator $cacheInvalidator
   *   Cache invalidator.
   */
  public function __construct(CacheTagsInvalidator $cacheInvalidator) {
    $this->cacheInvalidator = $cacheInvalidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'random_color_blocks_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['random_color_blocks.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['example'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Example'),
      '#default_value' => $this->config('random_color_blocks.settings')
        ->get('example'),
    ];
    $form['block_count'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node count'),
      '#default_value' => $this->config('random_color_blocks.settings')
        ->get('block_count'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('block_count') > 7) {
      $form_state->setErrorByName('block_count', $this->t('The value is not correct.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('random_color_blocks.settings')
      ->set('block_count', $form_state->getValue('block_count'))
      ->save();
    $this->cacheInvalidator->invalidateTags(['config:random_color_blocks']);

    parent::submitForm($form, $form_state);
  }

}
