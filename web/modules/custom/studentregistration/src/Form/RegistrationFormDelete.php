<?php
/**
 * @file
 * Contains \Drupal\student_registration\Form\RegistrationForm.
 */

namespace Drupal\studentregistration\Form;


use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\ConfirmFormBase;


class RegistrationFormDelete extends ConfirmFormBase
{
  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL)
  {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $connection = \Drupal::database();
    $num_deleted = $connection->delete('students')
      ->condition('uid', $this->id)
      ->execute();

    if ($num_deleted > 0) {
      \Drupal::messenger()->addMessage('TEst 123');
      $url = Url::fromRoute('studentregistration.result.list');
      $form_state->setRedirectUrl($url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string
  {
    return "confirm_delete_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl()
  {
    return new Url('studentregistration.result.list');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion()
  {
    return $this->t('Do you want to delete student with id = %id?', ['%id' => $this->id]);
  }

}
