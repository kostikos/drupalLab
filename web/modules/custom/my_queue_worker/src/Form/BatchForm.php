<?php

namespace Drupal\my_queue_worker\Form;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure my_queue_worker settings for this site.
 */
class BatchForm extends FormBase {


  /**
   * Batch Builder.
   *
   * @var \Drupal\Core\Batch\BatchBuilder
   */
  protected $batchBuilder;

  /**
   * Node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * BatchForm constructor.
   */
  public function __construct(NodeStorageInterface $node_storage) {


    $this->nodeStorage = $node_storage;
    $this->batchBuilder = new BatchBuilder();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('node')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_queue_worker_batch';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['help'] = [
      '#markup' => $this->t('This form add and remove some values from nodes titles.'),
    ];
    $form['delete_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Value to delete from title.'),
      '#required' => TRUE,
    ];
    $form['add_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Value to add into title.'),
      '#required' => TRUE,
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['run'] = [
      '#type' => 'submit',
      '#value' => $this->t('Run batch'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nodes = $this->getNodes();
    $textToDelete = $form_state->getValue('delete_text');
    $textToAdd = $form_state->getValue('add_text');

    $this->batchBuilder
      ->setTitle($this->t('Processing'))
      ->setInitMessage($this->t('Initializing.'))
      ->setProgressMessage($this->t('Completed @current of @total.'))
      ->setErrorMessage($this->t('An error has occurred.'));

    $this->batchBuilder->setFile(drupal_get_path('module', 'my_queue_worker') . '/src/Form/BatchForm.php');
    $this->batchBuilder->addOperation([$this, 'processItems'], [
      $nodes,
      $textToDelete,
      $textToAdd,
    ]);
    $this->batchBuilder->setFinishCallback([$this, 'finished']);

    batch_set($this->batchBuilder->toArray());
  }

  /**
   * Processor for batch operations.
   */
  public function processItems($items, $textToDelete, $textToAdd, array &$context) {
    // Elements per operation.
    $limit = 50;

    // Set default progress values.
    if (empty($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = count($items);
    }

    // Save items to array which will be changed during processing.
    if (empty($context['sandbox']['items'])) {
      $context['sandbox']['items'] = $items;
    }

    $counter = 0;
    if (!empty($context['sandbox']['items'])) {
      // Remove already processed items.
      if ($context['sandbox']['progress'] != 0) {
        array_splice($context['sandbox']['items'], 0, $limit);
      }

      foreach ($context['sandbox']['items'] as $item) {
        if ($counter != $limit) {
          $this->processItem($item, $textToDelete, $textToAdd);

          $counter++;
          $context['sandbox']['progress']++;

          $context['message'] = $this->t('Now processing node :progress of :count', [
            ':progress' => $context['sandbox']['progress'],
            ':count' => $context['sandbox']['max'],
          ]);

          $context['results']['processed'] = $context['sandbox']['progress'];
        }
      }
    }

    // If not finished all tasks, we count percentage of process. 1 = 100%.
    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }
  }

  /**
   * Process single item.
   *
   * @param int|string $nid
   *   An id of Node.
   * @param string $textToDelete
   *   Text to delete from title.
   * @param string $textToAdd
   *   Text to add into title.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function processItem($nid, $textToDelete, $textToAdd) {
    $node = $this->nodeStorage->load($nid);
    $nodeTitle = $node->title->getString();
    $nodeTitle = str_replace($textToDelete, '', $nodeTitle) . ' ' . $textToAdd;
    $node->setTitle($nodeTitle);
    $node->save();
  }

  /**
   * Finished callback for batch.
   */
  public function finished($success, $results, $operations) {
    $message = $this->t('Number of nodes affected by batch: @count', [
      '@count' => $results['processed'],
    ]);

    $this->messenger()
      ->addStatus($message);
  }

  /**
   * Load all nids.
   *
   * @return array
   *   An array with nids.
   */
  public function getNodes() {
    return $this->nodeStorage->getQuery()
      //->condition('status', NodeInterface::PUBLISHED)
      ->condition('type', 'article')
      ->execute();
  }

}

