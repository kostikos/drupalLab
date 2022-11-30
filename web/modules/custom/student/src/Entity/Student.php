<?php

namespace Drupal\student\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\student\StudentInterface;

/**
 * Defines the student entity class.
 *
 * @ContentEntityType(
 *   id = "student",
 *   label = @Translation("Student"),
 *   label_collection = @Translation("Students"),
 *   label_singular = @Translation("student"),
 *   label_plural = @Translation("students"),
 *   label_count = @PluralTranslation(
 *     singular = "@count students",
 *     plural = "@count students",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\student\StudentListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\student\StudentAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\student\Form\StudentForm",
 *       "edit" = "Drupal\student\Form\StudentForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "student",
 *   admin_permission = "administer student",
 *   entity_keys = {
 *     "id" = "id",
 *     "name" = "name",
 *   },
 *   links = {
 *     "collection" = "/admin/content/student",
 *     "add-form" = "/student/add",
 *     "canonical" = "/student/{student}",
 *     "edit-form" = "/student/{student}/edit",
 *     "delete-form" = "/student/{student}/delete",
 *   },
 * )
 */
class Student extends ContentEntityBase implements StudentInterface
{

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Student name'))
      ->setDescription(t('Student`s name.'))
      ->setRequired(TRUE)
      ->setSettings(array(
        'max_length' => 255,
      ))
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 9,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 9,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['surname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Student surname'))
      ->setDescription(t('Student`s surname.'))
      ->setRequired(FALSE)
      ->setSettings(array(
        'max_length' => 255,
      ))
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Student email'))
      ->setDescription(t('Student`s email.'))
      ->setRequired(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 11,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 11,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Student telephone'))
      ->setDescription(t('Student`s telephone.'))
      ->setRequired(FALSE)
      ->setSettings(array(
        'max_length' => 16,
      ))
      ->setDisplayOptions('form', [
        'type' => 'telephone',
        'weight' => 12,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 12,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['dob'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Student date of birthday'))
      ->setDescription(t('Student`s date of birthday.'))
      ->setRequired(FALSE)
      ->setSettings(array(
        'max_length' => 16,
      ))
      ->setDisplayOptions('form', [
        'type' => 'datetime',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['age'] = BaseFieldDefinition::create('list_string')
      ->setSettings([
        'allowed_values' => [
          '1-18' => '1-18',
          '18-30' => '18-30',
          '30-60' => '30-60',
          '60-110' => '60-110',
        ],
      ])
      ->setLabel(t('Student age'))
      ->setDescription(t('Student`s age.'))
      ->setCardinality(1)   //count of selected elements
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 13,
      ])
      ->setDisplayOptions('form', [
        'type' => 'list_default',
        'weight' => 13,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setSettings([
        'allowed_values' => [
          'male' => 'male',
          'female' => 'female',
          'other' => 'other',
        ],
      ])
      ->setCardinality(1)   //count of selected elements
      ->setLabel(t('Student gender'))
      ->setDescription(t('Student`s gender type.'))
      ->setRequired(FALSE)
      ->setSettings(array(
        'max_length' => 8,
      ))
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 14,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 14,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['group_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Student group id'))
      ->setDescription(t('Student group.'))
      ->setRequired(FALSE)
      ->setSettings(array(
        'max_length' => 8,
      ))
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_textfield',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
