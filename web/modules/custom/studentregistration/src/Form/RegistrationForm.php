<?php

namespace Drupal\studentregistration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

/**
 * Student registration form.
 */
class RegistrationForm extends FormBase {

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->database = $container->get('database');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'student_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#method'] = 'POST';

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Name:'),
      '#required' => TRUE,
    ];
    $form['rollnumner'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Enrollment Number:'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Enter Email ID:'),
      '#required' => TRUE,
    ];
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
    ];
    $form['dob'] = [
      '#type' => 'date',
      '#title' => t('Enter DOB:'),
      '#required' => TRUE,
    ];
    $form['gender'] = [
      '#type' => 'select',
      '#title' => ('Select Gender:'),
      '#options' => [
        'Male' => t('Male'),
        'Female' => t('Female'),
        'Other' => t('Other'),
      ],
    ];
    $form['average_mark'] = [
      '#type' => 'textfield',
      '#title' => t('Average mark:'),
      '#required' => TRUE,
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('rollnumner')) < 8) {
      $form_state->setErrorByName('rollnumner', $this->t('Please enter a valid Enrollment Number'));
    }
    if (strlen($form_state->getValue('phone')) < 10) {
      $form_state->setErrorByName('phone', $this->t('Please enter a valid Contact Number'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $arResult = $form_state->getValues();
    $query = $this->database->insert('students');
    $query->fields([
      'name' => $arResult['name'],
      'rollnumner' => $arResult['rollnumner'],
      'email' => $arResult['email'],
      'phone' => $arResult['phone'],
      'dob' => $arResult['dob'],
      'average_mark' => $arResult['average_mark'],
      'gender' => $arResult['gender'],
    ]);
    $resulrId['id'] = $query->execute();
    $url = Url::fromRoute('studentregistration.result', $resulrId);
    $form_state->setRedirectUrl($url);
  }

}
