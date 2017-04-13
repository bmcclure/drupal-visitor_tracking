<?php

namespace Drupal\visitor_tracking\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a visitor tracker data plugin annotation object.
 *
 * Plugin Namespace: Plugin\VisitorTracker\Data
 *
 * @Annotation
 */
class DataPlugin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $name;

  /**
   * The plugin weight.
   *
   * @var integer
   */
  public $weight;
}
