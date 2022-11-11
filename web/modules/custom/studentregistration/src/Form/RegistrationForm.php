<?php
/**
 * @file
 * Contains \Drupal\student_registration\Form\RegistrationForm.
 */
namespace Drupal\studentregistration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class RegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId(): string
  {
    return 'student_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array
  {
    $form['#method']='POST';

    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Name:'),
      '#required' => TRUE,
    );
    $form['rollnumner'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Enrollment Number:'),
      '#required' => TRUE,
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => t('Enter Email ID:'),
      '#required' => TRUE,
    );
    $form['phone'] = array (
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
    );
    $form['dob'] = array (
      '#type' => 'date',
      '#title' => t('Enter DOB:'),
      '#required' => TRUE,
    );
    $form['gender'] = array (
      '#type' => 'select',
      '#title' => ('Select Gender:'),
      '#options' => array(
        'Male' => t('Male'),
        'Female' => t('Female'),
        'Other' => t('Other'),
      ),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if(strlen($form_state->getValue('rollnumner')) < 8) {
      $form_state->setErrorByName('rollnumner', $this->t('Please enter a valid Enrollment Number'));
    }
    if(strlen($form_state->getValue('phone')) < 10) {
      $form_state->setErrorByName('phone', $this->t('Please enter a valid Contact Number'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $arResult = $form_state->getValues();
    $query = \Drupal::database()->insert('students');
    $query->fields([
      'name' => $arResult['name'],
      'rollnumner' => $arResult['rollnumner'],
      'email' => $arResult['email'],
      'phone' => $arResult['phone'],
      'dob' => $arResult['dob'],
      'gender' => $arResult['gender'],
    ]);
    $resulrId['result_id']=$query->execute();
    $url = Url::fromRoute('studentregistration.result', $resulrId);
    $form_state->setRedirectUrl($url);
  }

}
