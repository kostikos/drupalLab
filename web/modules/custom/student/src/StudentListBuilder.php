<?php

namespace Drupal\student;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for the student entity type.
 */
class StudentListBuilder extends EntityListBuilder {

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new StudentListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter) {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['table'] = parent::render();

    $total = $this->getStorage()
      ->getQuery()
      ->accessCheck(FALSE)
      ->count()
      ->execute();

    $build['summary']['#markup'] = $this->t('Total students: @total', ['@total' => $total]);
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['surname'] = $this->t('Surname');
    $header['email'] = $this->t('Email');
    $header['phone'] = $this->t('Phone');
    $header['age'] = $this->t('Age');
    $header['dob'] = $this->t('Date of birthday');
    $header['gender'] = $this->t('Gender');
    $header['group_id'] = $this->t('Group id');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   *
   * TODO Check the correct setting of the variable  (->getValue()[0]['value'] ) ????
   *
   *
   */
  public function buildRow(EntityInterface $entity) {

    $row['id'] = $entity->id();
    $row['name'] = $entity->get('surname')->getValue()[0]['value'];
    $row['surname'] = $entity->get('surname')->getValue()[0]['value'];
    $row['email'] =$entity->get('email')->getValue()[0]['value'];
    $row['phone'] = $entity->get('phone')->getValue()[0]['value'];
    $row['age'] = $entity->get('age')->getValue()[0]['value'];
    $row['dob'] = $entity->get('dob')->getValue()[0]['value'];
    $row['gender'] = $entity->get('gender')->getValue()[0]['value'];
    $row['group_id'] = $entity->get('group_id')->getValue()[0]['value'];
    return $row + parent::buildRow($entity);
  }

}
