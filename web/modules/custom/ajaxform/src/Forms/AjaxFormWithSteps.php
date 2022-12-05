<?php

namespace Drupal\ajaxForm\Forms;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Our example form class.
 */
class AjaxFormWithSteps extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_submit_demo';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;

    switch ($step) {
      case 1:
        $form['title'] = [
          '#type' => 'item',
          '#title' => $this->t('Step 1 from 3: MultistepForm - Personal data'),
        ];
        $form['name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Name'),
          '#default_value' => $form_state->getValue('name') ?? $form_state
            ->get([
              'data',
              'name',
            ]),
        ];
        $form['surname'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Surname'),
          '#default_value' => $form_state->getValue('surname') ?? $form_state
            ->get([
              'data',
              'surname',
            ]),
        ];
        $form['actions']['next'] = [
          '#type' => 'submit',
          '#value' => $this->t('Next'),
          '#submit' => ['::nextSubmit'],
          '#ajax' => [
            'callback' => '::myAjaxCallback',
            'wrapper' => 'myform-ajax-wrapper',
          ],
        ];
        break;

      case 2:
        $form['title'] = [
          '#type' => 'item',
          '#title' => $this->t('Step 2 from 3: MultistepForm - Parameters'),
        ];
        $form['age'] = [
          '#type' => 'select',
          '#options' => [
            '1-18' => $this->t('1-18'),
            '18-30' => $this->t('18-30'),
            '30-60' => $this->t('30-60'),
            '60-110' => $this->t('60-110'),
          ],
          '#title' => $this->t('Age'),
          '#default_value' => $form_state->getValue('age') ?? $form_state
            ->get([
              'data',
              'age',
            ]),
        ];
        $form['gender'] = [
          '#type' => 'radios',
          '#options' => [
            'male' => $this->t('Male'),
            'female' => $this->t('Female'),
          ],
          '#title' => $this->t('Gender'),
          '#default_value' => $form_state->getValue('gender') ?? $form_state
            ->get([
              'data',
              'gender',
            ]),
        ];
        $form['actions']['prev'] = [
          '#type' => 'submit',
          '#value' => $this->t('Prev'),
          '#submit' => ['::prevSubmit'],
          '#limit_validation_errors' => [],
          '#ajax' => [
            'callback' => '::myAjaxCallback',
            'wrapper' => 'myform-ajax-wrapper',
          ],
        ];
        $form['actions']['next'] = [
          '#type' => 'submit',
          '#value' => $this->t('Next'),
          '#submit' => ['::nextSubmit'],
          '#ajax' => [
            'callback' => '::myAjaxCallback',
            'wrapper' => 'myform-ajax-wrapper',
          ],
        ];
        break;

      case 3:
        $form['hobby'] = [
          '#type' => 'checkboxes',
          '#title' => $this->t('Interests'),
          '#options' => [
            'chess' => $this->t('Chess'),
            'football' => $this->t('Football'),
            'politics' => $this->t('Politics'),
            'gardening' => $this->t('Gardening'),
          ],
          '#default_value' => $form_state->getValue('hobby') ?? $form_state
            ->get([
              'data',
              'hobby',
            ]),
        ];
        $form['bio'] = [
          '#type' => 'textarea',
          '#rows' => 10,
          '#title' => $this->t('Dreams'),
          '#default_value' => $form_state->getValue('bio') ?? $form_state
            ->get([
              'data',
              'bio',
            ]),
        ];
        $form['title'] = [
          '#type' => 'item',
          '#title' => $this->t('Step 3 from 3: MultistepForm - Survey'),
        ];

        $form['actions']['prev'] = [
          '#type' => 'submit',
          '#value' => $this->t('Prev'),
          '#submit' => ['::prevSubmit'],
          '#limit_validation_errors' => [],
          '#ajax' => [
            'callback' => '::myAjaxCallback',
            'wrapper' => 'myform-ajax-wrapper',
            'progress' => [
              'type' => 'throbber',
              'message' => $this->t('Waiting results ...'),
            ],
          ],
        ];
        $form['actions']['save'] = [
          '#type' => 'submit',
          '#value' => $this->t('Save'),
          '#ajax' => [
            // don't forget :: when calling a class method.
            'callback' => '::myAjaxCallback',
            // This element is updated with this AJAX callback.
            'wrapper' => 'myform-ajax-wrapper',
          ],
        ];
        break;
    }

    $form['#prefix'] = '<div id="myform-ajax-wrapper">';
    $form['#suffix'] = '</div>';

    return $form;
  }

  /**
   * Ajax submit form.
   *
   * @param array $form
   *   From fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   From state.
   *
   * @return array
   *   Returns form fields.
   */
  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus('ajax done !');

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $data = $form_state->get('data') ?? [];
    $data = array_merge($data, $values);
    unset($data['title']);
    unset($data['form_build_id']);
    unset($data['form_id']);
    unset($data['prev']);
    unset($data['ajax_page_state']);
    unset($data['next']);
    unset($data['save']);
    unset($data['op']);

    foreach ($data as $key => $value) {
      $this->messenger()->addStatus($this->t(':key => :value', [
        ':key' => $key,
        ':value' => is_array($value) ? implode(', ', array_filter($value)) : $value,
      ]));
    }
  }

  /**
   * Form nex step realisation.
   *
   * @param array $form
   *   Form fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   From state.
   */
  public function nextSubmit(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus('submit  done !');

    $step = $form_state->get('step') ?? 1;

    // Save step data.
    $values = $form_state->getValues();
    $data = $form_state->get('data') ?? [];
    $form_state->set('data', array_merge($data, $values));

    // Prepare new step.
    $form_state->set('step', ++$step);
    $form_state->setRebuild(TRUE);
  }

  /**
   * Form prev step realisation.
   *
   * @param array $form
   *   Form fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   From state.
   */
  public function prevSubmit(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;

    // Save step data.
    $values = $form_state->getUserInput();
    $data = $form_state->get('data') ?? [];
    $form_state->set('data', array_merge($data, $values));

    // Restore step data.
    $form_state->setValues($data);

    // Prepare new step.
    $form_state->set('step', --$step);
    $form_state->setRebuild(TRUE);
  }

}
