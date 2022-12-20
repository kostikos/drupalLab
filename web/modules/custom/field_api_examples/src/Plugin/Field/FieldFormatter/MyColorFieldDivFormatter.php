<?php

namespace Drupal\field_api_examples\Plugin\Field\FieldFormatter;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin generate new formatter for color property like color block.
 *
 * @FieldFormatter(
 *   id = "my_color_field_div_formatter",
 *   label = @Translation("Div element with background color"),
 *   field_types = {
 *     "my_color_field"
 *   }
 * )
 */
class MyColorFieldDivFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   *
   * Default settings for our formFormatter.
   */
  public static function defaultSettings() {
    return [
      'width' => '80',
      'height' => '80',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   *
   * Form formatter.
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['width'] = [
      '#type' => 'number',
      '#title' => t('Width'),
      '#field_suffix' => 'px.',
      '#default_value' => $this->getSetting('width'),
      '#min' => 1,
    ];

    $elements['height'] = [
      '#type' => 'number',
      '#title' => t('Height'),
      '#field_suffix' => 'px.',
      '#default_value' => $this->getSetting('height'),
      '#min' => 1,
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   *
   * Display small description about format settings.
   */
  public function settingsSummary() {
    $summary = [];
    $settings = $this->getSettings();

    $summary[] = t('Width @width px.', ['@width' => $settings['width']]);
    $summary[] = t('Height @height px.', ['@height' => $settings['height']]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $settings = $this->getSettings();

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = [
        '#type' => 'markup',
        '#markup' => new FormattableMarkup(
          '<div style="width: @width; height: @height; background-color: @color;"></div>',
          [
            '@width' => $settings['width'] . 'px',
            '@height' => $settings['height'] . 'px',
            '@color' => 'rgb(' . $item->red_value . ',' . $item->green_value . ',' . $item->blue_value . ')',
          ]
        ),
      ];
    }

    return $element;
  }

}
