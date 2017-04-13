<?php

namespace Drupal\visitor_tracking\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines a visitor tracker entity.
 *
 * @ConfigEntityType(
 *   id = "visitor_tracker",
 *   label = @Translation("Visitor tracker"),
 *   handlers = {
 *     "list_builder" = "Drupal\visitor_tracking\VisitorTrackerListBuilder",
 *     "storage" = "Drupal\visitor_tracking\VisitorTrackerStorage",
 *     "form" = {
 *       "add" = "Drupal\visitor_tracking\Form\VisitorTrackerForm",
 *       "edit" = "Drupal\visitor_tracking\Form\VisitorTrackerForm",
 *       "delete" = "Drupal\visitor_tracking\Form\VisitorTrackerDeleteForm"
 *     }
 *   },
 *   config_prefix = "visitor_tracker",
 *   admin_permission = "administer visitor tracking",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "collection" = "/admin/config/people/visitor-trackers",
 *     "edit-form" = "/admin/config/people/visitor-trackers/{visitor_tracker}",
 *     "delete-form" = "/admin/config/people/visitor-trackers/{visitor_tracker}/delete"
 *   }
 * )
 */
class VisitorTracker extends ConfigEntityBase implements VisitorTrackerInterface {

  /**
   * The visitor tracker machine name.
   *
   * @var string
   */
  public $id;

  /**
   * The visitor tracker label.
   *
   * @var string
   */
  public $label;

  /**
   * Whether or not this visitor tracker is enabled.
   *
   * @var bool
   */
  public $enabled;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function setId($id) {
    $this->id = $id;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function setLabel($label) {
    $this->label = $label;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    return $this->enabled;
  }

  /**
   * {@inheritdoc}
   */
  public function setEnabled($enabled) {
    $this->enabled = $enabled;

    return $this;
  }

}
