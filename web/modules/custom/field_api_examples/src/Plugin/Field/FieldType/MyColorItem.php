<?php

namespace Drupal\field_api_examples\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'my_color' field type.
 *
 * @FieldType(
 *   id = "my_color_field",
 *   label = @Translation("Field API color example"),
 *   category = @Translation("Base"),
 *   default_widget = "my_color_field_input_widget",
 *   default_formatter = "my_color_field_default_formatter"
 * )
 */
class MyColorItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    $empty = TRUE;
    foreach (['red_value', 'green_value', 'blue_value'] as $column) {
      $empty &= $this->get($column)->getValue() === NULL;
    }
    return (bool) $empty;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['red_value'] = DataDefinition::create('integer')
      ->setLabel(t('Integer value between 0-255'))
      ->setRequired(TRUE);
    $properties['green_value'] = DataDefinition::create('integer')
      ->setLabel(t('Integer value between 0-255'))
      ->setRequired(TRUE);
    $properties['blue_value'] = DataDefinition::create('integer')
      ->setLabel(t('Integer value between 0-255'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'red_value' => [
        'type' => 'int',
        'not null' => TRUE,
        'description' => 'Red color value.',
      ],
      'green_value' => [
        'type' => 'int',
        'not null' => TRUE,
        'description' => 'Green color value.',
      ],
      'blue_value' => [
        'type' => 'int',
        'not null' => TRUE,
        'description' => 'Blue color value.',
      ],
    ];

    return [
      'columns' => $columns,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $values['red_value'] = $random->word(mt_rand(0, 255));
    $values['green_value'] = $random->word(mt_rand(0, 255));
    $values['blue_value'] = $random->word(mt_rand(0, 255));
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    // If this is an unsigned integer, add a validation constraint for the
    // integer to be positive.
    if ($this->getSetting('unsigned')) {
      $constraint_manager = \Drupal::typedDataManager()
        ->getValidationConstraintManager();
      $constraints[] = $constraint_manager->create('ComplexData', [
        'value' => [
          'Range' => [
            'min' => 0,
            'minMessage' => $this->t('%name: The integer must be larger or equal to %min.', [
              '%name' => $this->getFieldDefinition()->getLabel(),
              '%min' => 0,
            ]),
            'max' => 0,
            'maxMessage' => $this->t('%name: The integer must be larger or equal to %max.', [
              '%name' => $this->getFieldDefinition()->getLabel(),
              '%max' => 255,
            ]),
          ],
        ],
      ]);
    }

    return $constraints;
  }

}
