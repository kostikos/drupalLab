<?php

namespace Drupal\mymodule\Forms;

use Drupal\Core\Form\FormBase,
  Drupal\Core\Form\FormStateInterface,
  Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;

class FirstForm extends FormBase
{

  public function getFormId()
  {
    return 'mymodule_firstform';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array
  {

    $form['#method'] = 'POST';

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Yor name'),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Yor email'),
    ];

    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Yor age'),
    ];

    $form['user_id'] = [
      '#type' => 'hidden',
      '#value' => \Drupal::currentUser()->id(),
    ];


    $form['gender'] = [
      '#type' => 'select',
      '#title' => t('Gender'),
      '#description' => t('Select gender.'),
      '#options' => array(
        'Male' => t('Male'),
        'Female' => t('Female'),
        'other' => t('other'),
      ),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];
//    $form['reset2'] = array(
//      '#type' => 'submit',
//      '#value' => t('Reset'),
//      '#submit' => array('::resetForm'),
//    );

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // TODO: Implement submitForm() method.
    /* $form_object = $this->getFormObject($form_state);
     $form_object->submitForm($form, $form_state);
 */

    //ksm($form_state->getValues());
    $arResult = $form_state->getValues();


    if ($arResult['op'] == 'submit') {
      unset($arResult['submit']);
      unset($arResult['form_build_id']);
      unset($arResult['form_id']);
      unset($arResult['form_token']);
      unset($arResult['op']);
      $url = Url::fromRoute('mymodule.forms.result', $arResult);
      $form_state->setRedirectUrl($url);
    } else {

      $form_state->setRebuild(false);
    }

  }

}

