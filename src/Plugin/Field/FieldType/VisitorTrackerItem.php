<?php

namespace Drupal\visitor_tracking\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\entity_slug\Plugin\Slugifier\SlugifierInterface;
use Drupal\entity_slug\SlugifierManager;

/**
 * Abstract base class SlugItemBase.
 *
 * @package Drupal\visitor_tracking\Plugin\Field\FieldType
 */
class VisitorTrackerItem extends FieldItemBase implements VisitorTrackerItemInterface {

  public static function defaultStorageSettings() {
    return [
      'visitor_tracker_id'
      ] + parent::defaultStorageSettings();
  }

  public static function defaultFieldSettings() {
    return [
      // @todo Insert default settings here
      ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    $properties['visitor_tracker_data_id'] = DataDefinition::create('integer')
      ->setLabel(t('Visitor tracker data ID'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'visitor_tracker_data_id' => [
          'type' => 'int',
          'default' => 0,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('visitor_tracker_data_id')->getValue();

    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    $settings = $this->getSettings();

    // @todo Insert settings here

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'visitor_tracker_data_id';
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];

    /** @var \Drupal\visitor_tracking\VisitorTrackerStorageInterface $storage */
    $storage = \Drupal::entityTypeManager()->getStorage('visitor_tracker');
    $options = [];
    foreach ($storage->loadByProperties(['enabled' => TRUE]) as $visitor_tracker) {
      $options[$visitor_tracker->id()] = $visitor_tracker->label();
    }

    $element['visitor_tracker_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Visitor tracker'),
      '#options' => $options,
      '#required' => TRUE,
      '#description' => $this->t('Select the visitor tracker to use for this field.'),
      '#default_value' => $this->getSetting('visitor_tracker_id'),
      '#disabled' => $has_data,
    ];

    return $element;
  }

}
