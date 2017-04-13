<?php

namespace Drupal\visitor_tracking\Form;

use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\visitor_tracking\Entity\VisitorTrackerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form handler for visitor tracker add and edit forms.
 */
class VisitorTrackerForm extends EntityForm {

  /** @var \Drupal\Core\Entity\Query\QueryFactory */
  protected $entityQuery;

  /** @var  EntityFieldManager */
  protected $fieldManager;

  /**
   * Constructs a VisitorTrackerForm object.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   *
   * @param \Drupal\Core\Entity\EntityFieldManager $field_manager
   *   The entity field manager.
   */
  public function __construct(QueryFactory $entity_query, EntityFieldManager $field_manager) {
    $this->entityQuery = $entity_query;
    $this->fieldManager = $field_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var QueryFactory $entity_query */
    $entity_query = $container->get('entity.query');

    /** @var EntityFieldManager $field_manager */
    $field_manager = $container->get('entity_field.manager');

    return new static(
      $entity_query,
      $field_manager
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var VisitorTrackerInterface $formTracker */
    $formTracker = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $formTracker->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $formTracker->id(),
      '#machine_name' => [
        'exists' => [$this, 'exists'],
      ],
      '#disabled' => !$formTracker->isNew(),
    ];

    // @todo Add entity fields

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#description' => $this->t('When enabled, tracking information will be collected automatically and displayed where configured.'),
      '#default_value' => $formTracker->isEnabled(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    /** @var VisitorTrackerInterface $formTracker */
    $formTracker = $this->entity;

    $status = $formTracker->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label visitor tracker.', [
        '%label' => $formTracker->label(),
      ]));
    }
    else {
      drupal_set_message($this->t('The %label visitor tracker was not saved.', [
        '%label' => $formTracker->label(),
      ]));
    }

    $form_state->setRedirect('entity.visitor_tracker.collection');
  }

  /**
   * Helper function to check whether a visitor tracker configuration entity exists.
   */
  public function exists($id) {
    $entity = $this->entityQuery->get('visitor_tracker')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
