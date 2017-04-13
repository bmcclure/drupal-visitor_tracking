<?php

namespace Drupal\visitor_tracking\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines the interface for visitor tracker config entities.
 */
interface VisitorTrackerInterface extends ConfigEntityInterface {

  /**
   * Gets the visitor tracker machine name.
   *
   * @return string
   *   The machine name.
   */
  public function getId();

  /**
   * Sets the visitor tracker machine name.
   *
   * @param string $id
   *   The machine name.
   *
   * @return $this
   */
  public function setId($id);

  /**
   * Gets the visitor tracker label.
   *
   * @return string
   *   The label.
   */
  public function getLabel();

  /**
   * Sets the visitor tracker label.
   *
   * @param string $label
   *   The label.
   *
   * @return $this
   */
  public function setLabel($label);

  /**
   * Get whether the visitor tracker is enabled.
   *
   * @return bool
   *   TRUE if the visitor tracker is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets whether the visitor tracker is enabled.
   *
   * @param bool $enabled
   *   Whether the visitor tracker is enabled.
   *
   * @return $this
   */
  public function setEnabled($enabled);

}
