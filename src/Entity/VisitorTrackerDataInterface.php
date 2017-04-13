<?php

namespace Drupal\visitor_tracking;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\visitor_tracking\Entity\VisitorTrackerInterface;

/**
 * Provides an interface defining a comment entity.
 */
interface VisitorTrackerDataInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Returns the visitor tracker entity which generated this data.
   *
   * @return \Drupal\visitor_tracking\Entity\VisitorTrackerInterface
   *   The visitor tracker configuration entity.
   */
  public function getVisitorTracker();

  /**
   * Returns the type of the visitor tracker entity which generated this data.
   *
   * @return string
   *   The visitor tracker configuration entity machine name.
   */
  public function getVisitorTrackerId();

  /**
   * Sets the visitor tracker for this data.
   *
   * @param VisitorTrackerInterface $visitorTracker
   *   The visitor tracker.
   *
   * @return $this
   *   The class instance that this method is called on.
   */
  public function setVisitorTracker(VisitorTrackerInterface $visitorTracker);

  /**
   * Sets the visitor tracker for this data by its ID.
   *
   * @param string $visitorTrackerId
   *   The visitor tracker ID.
   *
   * @return $this
   *   The class instance that this method is called on.
   */
  public function setVisitorTrackerId($visitorTrackerId);

  /**
   * Returns the time that the data was created.
   *
   * @return int
   *   The timestamp of when the data was created.
   */
  public function getCreatedTime();

  /**
   * Sets the creation date of the data.
   *
   * @param int $created
   *   The timestamp of when the data was created.
   *
   * @return $this
   *   The class instance that this method is called on.
   */
  public function setCreatedTime($created);

  /**
   * Returns the tracking data array.
   *
   * @return array
   *   The tracking data.
   */
  public function getTrackingData();

  /**
   * Sets the tracking data array
   *
   * @param array $trackingData
   *   The tracking data array.
   *
   * @return $this
   *   The class instance that this method is called on.
   */
  public function setTrackingData(array $trackingData);

}
