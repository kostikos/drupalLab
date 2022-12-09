<?php

namespace Drupal\studentregistration\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\ConfirmFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\Messenger;

/**
 * Form for delete user.
 */
class RegistrationFormDelete extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected $id;

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   *   database connection service.
   */
  protected Connection $database;

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\Messenger
   *   Data manager service.
   */
  protected Messenger $messanger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->database = $container->get('database');
    $instance->messanger = $container->get('messenger');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $num_deleted = $this->database->delete('students')
      ->condition('uid', $this->id)
      ->execute();

    if ($num_deleted > 0) {
      $this->messanger->addMessage('User deleted');
      $url = Url::fromRoute('studentregistration.result.list');
      $form_state->setRedirectUrl($url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return "confirm_delete_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('studentregistration.result.list');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete student with id = %id?', ['%id' => $this->id]);
  }

}
