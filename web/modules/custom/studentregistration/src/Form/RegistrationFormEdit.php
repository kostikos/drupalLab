<?php

namespace Drupal\studentregistration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Class for edit students fields from admin panel.
 */
class RegistrationFormEdit extends FormBase {

  /**
   * Current database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * Current route service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected CurrentRouteMatch $route;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->database = $container->get('database');
    $instance->route = $container->get('current_route_match');

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
    $studentId = $this->route->getParameter('id');
    $query = $this->database->select('students')
      ->condition('students.uid', $studentId)
      ->fields('students', [
        'uid',
        'name',
        'rollnumner',
        'average_mark',
        'email',
        'phone',
        'dob',
        'gender',
      ])
      ->range(0, 1);
    $result = $query->execute()->fetchAssoc();

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Name:'),
      '#required' => TRUE,
      '#default_value' => $result['name'],
    ];
    $form['rollnumner'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Enrollment Number:'),
      '#required' => TRUE,
      '#default_value' => $result['rollnumner'],
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Enter Email ID:'),
      '#required' => TRUE,
      '#default_value' => $result['email'],
    ];
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
      '#default_value' => $result['phone'],
    ];
    $form['dob'] = [
      '#type' => 'date',
      '#title' => t('Enter DOB:'),
      '#required' => TRUE,
      '#default_value' => $result['dob'],
    ];
    $form['gender'] = [
      '#type' => 'select',
      '#title' => ('Select Gender:'),
      '#options' => [
        'Male' => t('Male'),
        'Female' => t('Female'),
        'Other' => t('Other'),
      ],
      '#default_value' => $result['gender'],
    ];
    $form['average_mark'] = [
      '#type' => 'textfield',
      '#title' => t('Average mark:'),
      '#required' => TRUE,
      '#default_value' => $result['average_mark'],
    ];
    $form['uid'] = [
      '#type' => 'hidden',
      '#default_value' => $result['uid'],
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
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
    $query = $this->database->update('students');
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
