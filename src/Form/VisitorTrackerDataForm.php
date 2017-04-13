<?php

namespace Drupal\visitor_tracking\Form;

use Drupal\comment\Plugin\Field\FieldType\CommentItemInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityConstraintViolationListInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base handler for comment forms.
 */
class VisitorTrackerDataForm extends ContentEntityForm {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('current_user'),
      $container->get('renderer'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time')
    );
  }

  /**
   * Constructs a new CommentForm.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(EntityManagerInterface $entity_manager, AccountInterface $current_user, RendererInterface $renderer, EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL, TimeInterface $time = NULL) {
    parent::__construct($entity_manager, $entity_type_bundle_info, $time);
    $this->currentUser = $current_user;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\visitor_tracking\VisitorTrackerDataInterface $visitor_tracker_data */
    $visitor_tracker_data = $this->entity;

    // The uid field is only displayed when a user with the permission
    // 'administer comments' is editing an existing comment from an
    // authenticated user.
    $owner = $visitor_tracker_data->getOwner();
    $form['uid'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'user',
      '#default_value' => $owner,
      '#selection_settings' => ['include_anonymous' => TRUE],
      '#title' => $this->t('User'),
    ];

    // Add administrative comment publishing options.
    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Created on'),
      '#default_value' => DrupalDateTime::createFromTimestamp($visitor_tracker_data->getCreatedTime()),
      '#size' => 20,
    ];

    return parent::form($form, $form_state, $visitor_tracker_data);
  }

  /**
   * {@inheritdoc}
   */
  public function buildEntity(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\visitor_tracking\VisitorTrackerDataInterface $visitor_tracking_data */
    $visitor_tracking_data = parent::buildEntity($form, $form_state);

    $created = (!$form_state->isValueEmpty('date') && $form_state->getValue('date') instanceof DrupalDateTime)
      ? $visitor_tracking_data->setCreatedTime($form_state->getValue('date')->getTimestamp())
      : REQUEST_TIME;
    $visitor_tracking_data->setCreatedTime($created);

    // Empty author ID should revert to anonymous.
    $author_id = $form_state->getValue('uid');
    if (is_null($author_id)) {
      $author_id = $this->currentUser->id();
    }
    $visitor_tracking_data->setOwnerId($author_id);

    return $visitor_tracking_data;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditedFieldNames(FormStateInterface $form_state) {
    return array_merge(['created'], parent::getEditedFieldNames($form_state));
  }

  /**
   * {@inheritdoc}
   */
  protected function flagViolations(EntityConstraintViolationListInterface $violations, array $form, FormStateInterface $form_state) {
    // Manually flag violations of fields not handled by the form display.
    foreach ($violations->getByField('created') as $violation) {
      $form_state->setErrorByName('date', $violation->getMessage());
    }
    parent::flagViolations($violations, $form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\visitor_tracking\VisitorTrackerDataInterface $visitor_tracking_data */
    $visitor_tracking_data = $this->entity;
    $logger = $this->logger('visitor_tracking');

    $visitor_tracking_data->save();
    $form_state->setValue('visitor_tracking_data_id', $visitor_tracking_data->id());

    // Add a log entry.
    $logger->notice('Visitor tracking data logged: %label.', [
      '%label' => $visitor_tracking_data->label(),
      'link' => $this->l(t('View'), $visitor_tracking_data->toUrl('canonical'))
    ]);

    drupal_set_message($this->t('Visitor tracking data has been updated..'));

    // Redirect to the newly posted comment.
    $form_state->setRedirectUrl(Url::fromRoute('entity.visitor_tracker.collection'));
  }

}
