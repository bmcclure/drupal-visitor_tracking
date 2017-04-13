<?php

namespace Drupal\visitor_tracking\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Url;

/**
 * Provides the comment delete confirmation form.
 */
class VisitorTrackerDataDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    // Point to the entity of which this comment is a reply.
    return Url::fromRoute('entity.visitor_tracking_data.collection');
  }

  /**
   * {@inheritdoc}
   */
  protected function getRedirectUrl() {
    return $this->getCancelUrl();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('The associated tracking data will be lost. This action cannot be undone.');
  }

  /**
   * {@inheritdoc}
   */
  protected function getDeletionMessage() {
    return $this->t('The visitor tracking data has been deleted.');
  }

  /**
   * {@inheritdoc}
   */
  public function logDeletionMessage() {
    $this->logger('visitor_tracking')->notice('Deleted visitor tracking data @id.', ['@id' => $this->entity->id()]);
  }

}
