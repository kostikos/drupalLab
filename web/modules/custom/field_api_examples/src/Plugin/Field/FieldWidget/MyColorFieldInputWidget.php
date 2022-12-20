<?php

namespace Drupal\field_api_examples\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Widget for property color.
 *
 * @FieldWidget(
 *   id = "my_color_field_input_widget",
 *   module = "field_api_examples",
 *   label = @Translation("Custom Color Picker "),
 *   field_types = {
 *     "my_color_field"
 *   }
 * )
 */
class MyColorFieldInputWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   *
   * В данном методе мы настраиваем форму в которой наше значение для поля будет
   * вводиться и редактироваться - это то, что видят юзеры в админке при работе
   * с данным полем.
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['red_value'] = [
      '#title' => $this->t('Red colour value.'),
      '#type' => 'textfield',
      '#default_value' => $items[$delta]->red_value ?? NULL,
      '#element_validate' => [
        [$this, 'colorValidation'],
      ],
    ];
    $element['green_value'] = [
      '#title' => $this->t('Green colour value.'),
      '#type' => 'textfield',
      '#default_value' => $items[$delta]->green_value ?? NULL,
      '#element_validate' => [
        [$this, 'colorValidation'],
      ],
    ];
    $element['blue_value'] = [
      '#title' => $this->t('Blue colour value.'),
      '#type' => 'textfield',
      '#default_value' => $items[$delta]->blue_value ?? NULL,
      '#element_validate' => [
        [$this, 'colorValidation'],
      ],
    ];

    return $element;
  }

  /**
   * Check color values.
   *
   * @param $element
   *   Form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   From state.
   */
  public function colorValidation($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if ($value > 255 || $value < 0) {
      $form_state->setError($element, t('Error color format. Enter number between 0 and 255.'));
    }
  }

}
