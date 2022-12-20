<?php

namespace Drupal\field_api_examples\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin generate new formatter for color property like text.
 *
 * @FieldFormatter(
 *   id = "my_color_field_default_formatter",
 *   label = @Translation("HEX color"),
 *   field_types = {
 *     "my_color_field"
 *   }
 * )
 */
class MyColorFieldDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {

      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => "rgb($item->red_value, $item->green_value ,  $item->blue_value)",
      ];
    }

    return $elements;
  }

}
