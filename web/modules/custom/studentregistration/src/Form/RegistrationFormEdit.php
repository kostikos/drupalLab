<?php
/**
 * @file
 * Contains \Drupal\student_registration\Form\RegistrationForm.
 */

namespace Drupal\studentregistration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class RegistrationFormEdit extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId(): string
  {
    return 'student_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array
  {
    //$request = $this->getRequest()->;

    $form['#method'] = 'POST';
    $studentId = \Drupal::routeMatch()->getParameter('id');
    $database = \Drupal::database();
    $query = $database->select('students')
      ->condition('students.uid', $studentId)
      ->fields('students', ['uid', 'name', 'rollnumner', 'average_mark', 'email', 'phone', 'dob', 'gender'])
      ->range(0, 1);
    $result = $query->execute()->fetchAssoc();


    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Name:'),
      '#required' => TRUE,
      '#default_value' => $result['name'],
    );
    $form['rollnumner'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Enrollment Number:'),
      '#required' => TRUE,
      '#default_value' => $result['rollnumner'],
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => t('Enter Email ID:'),
      '#required' => TRUE,
      '#default_value' => $result['email'],
    );
    $form['phone'] = array(
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
      '#default_value' => $result['phone'],
    );
    $form['dob'] = array(
      '#type' => 'date',
      '#title' => t('Enter DOB:'),
      '#required' => TRUE,
      '#default_value' => $result['dob'],
    );
    $form['gender'] = array(
      '#type' => 'select',
      '#title' => ('Select Gender:'),
      '#options' => array(
        'Male' => t('Male'),
        'Female' => t('Female'),
        'Other' => t('Other'),
      ),
      '#default_value' => $result['gender'],
    );
    $form['average_mark'] = array(
      '#type' => 'textfield',
      '#title' => t('Average mark:'),
      '#required' => TRUE,
      '#default_value' => $result['average_mark'],
    );
    $form['uid'] = array(
      '#type' => 'hidden',
      '#default_value' => $result['uid'],
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    if (strlen($form_state->getValue('rollnumner')) < 8) {
      $form_state->setErrorByName('rollnumner', $this->t('Please enter a valid Enrollment Number'));
    }
    if (strlen($form_state->getValue('phone')) < 10) {
      $form_state->setErrorByName('phone', $this->t('Please enter a valid Contact Number'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $arResult = $form_state->getValues();
    $query = \Drupal::database()->update('students');
    $query->fields([
      'name' => $arResult['name'],
      'rollnumner' => $arResult['rollnumner'],
      'email' => $arResult['email'],
      'phone' => $arResult['phone'],
      'dob' => $arResult['dob'],
      'average_mark' => $arResult['average_mark'],
      'gender' => $arResult['gender'],
    ])
      ->condition('uid', $arResult['uid'])
      ->execute();
    $url = Url::fromRoute('studentregistration.result', ['id' => $arResult['uid']]);
    $form_state->setRedirectUrl($url);
  }

}
