<?php

namespace Drupal\visitor_tracking\Entity;

use Drupal\Core\Annotation\PluralTranslation;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;
use Drupal\visitor_tracking\VisitorTrackerDataInterface;

/**
 * Defines the Visitor Tracker Data entity class.
 *
 * @ContentEntityType(
 *   id = "visitor_tracker_data",
 *   label = @Translation("Visitor tracker data"),
 *   label_singular = @Translation("visitor tracker data"),
 *   label_plural = @Translation("visitor tracker data"),
 *   label_count = @PluralTranslation(
 *     singular = "@count visitor tracker data entity",
 *     plural = "@count visitor tracker data entities",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "form" = {
 *       "default" = "Drupal\visitor_tracking\Form\VisitorTrackerDataForm",
 *       "delete" = "Drupal\visitor_tracking\Form\VisitorTrackerDataDeleteForm"
 *     },
 *   },
 *   base_table = "visitor_tracker_data",
 *   data_table = "visitor_tracker_data_field_data",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" => "label",
 *     "uuid" => "uuid"
 *   }
 *   links = {
 *     "canonical" = "/visitor-tracker-data/{visitor_tracker_data}",
 *     "delete-form" = "/visitor-tracker-data/{visitor_tracker_data}/delete",
 *     "edit-form" = "/visitor-tracker-data/{visitor_tracker_data}/edit
 *   }
 * )
 */
class VisitorTrackerData extends ContentEntityBase implements VisitorTrackerDataInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    /** @var UserInterface $user */
    $user = $this->get('uid')->entity;
    if (!$user) {
      $user = User::getAnonymousUser();
    }
    return $user;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVisitorTracker() {
    return $this->get('visitor_tracker')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getVisitorTrackerId() {
    return $this->get('visitor_tracker')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setVisitorTracker(VisitorTrackerInterface $visitorTracker) {
    $this->set('visitor_tracker', $visitorTracker->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setVisitorTrackerId($visitorTrackerId) {
    $this->set('visitor_tracker', $visitorTrackerId);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTrackingData() {
    return $this->get('tracking_data')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTrackingData(array $trackingData) {
    $this->set('tracking_data', $trackingData);
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return (isset($this->get('created')->value))
      ? $this->get('created')->value
      : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($created) {
    $this->set('created', $created);
    return $this;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id']
      ->setLabel(t('Visitor tracker data ID'));

    $fields['visitor_tracker'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Visitor tracker'))
      ->setDescription(t('The ID of the visitor tracker.'))
      ->setSetting('target_type', 'visitor_tracker')
      ->setRequired(TRUE);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'));

    $fields['tracking_data'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Tacking data'));

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User ID'))
      ->setDescription(t('The user ID the data is associated with.'))
      ->setSetting('target_type', 'user')
      ->setDefaultValue(0);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the data was created.'))
      ->setTranslatable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the data was last edited.'))
      ->setTranslatable(TRUE);

    return $fields;
  }

}
