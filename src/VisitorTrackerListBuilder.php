<?php

namespace Drupal\visitor_tracking;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\visitor_tracking\Entity\VisitorTrackerInterface;

/**
 * The list builder for Form Tracker entities.
 */
class VisitorTrackerListBuilder extends ConfigEntityListBuilder {

  public function buildHeader() {
    $header = [
      'label' => $this->t('Label'),
      'id' => $this->t('Machine name'),
      'data_plugins' => $this->t('Data plugins'),
      'enabled' => $this->t('Enabled'),
    ];

    return $header + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity) {
    /** @var VisitorTrackerInterface $entity */

    $row = [
      'label' => $entity->label(),
      'id' => $entity->id(),
      'data_plugins' => $this->getDataPlugins($entity),
      'enabled' => $entity->isEnabled() ? $this->t('Yes') : $this->t('No'),
    ];

    return $row + parent::buildRow($entity);
  }

  protected function getDataPlugins(VisitorTrackerInterface $entity) {
    // @todo Fix
    $plugins = $entity->getDataPlugins();

    $items = [];

    foreach ($plugins as $plugin) {
      $items[] = $plugin->label();
    }

    return \Drupal::theme()->render('item_list', ['items' => $items]);
  }
}
