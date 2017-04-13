<?php

namespace Drupal\visitor_tracking;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides a manager for Data plugins on visitor trackers.
 */
class DataPluginManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/VisitorTracker/Data',
      $namespaces,
      $module_handler,
      'Drupal\visitor_tracking\Plugin\VisitorTracker\Data\DataPluginInterface',
      'Drupal\visitor_tracking\Annotation\DataPlugin'
    );

    $this->alterInfo('visitor_tracking_data_plugin_info');
    $this->setCacheBackend($cache_backend, 'visitor_tracking_data_plugin_info_plugins');
    $this->factory = new DefaultFactory($this->getDiscovery());
  }

}
