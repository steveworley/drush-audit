<?php

/**
 * @file
 * Check installed version of a module against
 */

namespace DrushAudit\Task\Modules;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class VersionCheck implements Task {

  use TaskTrait;

  public $info = array(
    'title' => 'Version check for installed modules',
    'headers' => array('Module name', 'Installed', 'Recommended'),
  );

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $module_list = module_list();
    $this->setData($module_list);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $available = update_get_available();
    $updates = update_calculate_project_data($available);

    $requires_update = array();

    foreach ($this->data as $module) {
      if (empty($updates[$module])) {
        continue;
      }

      $row = array(
        $module,
        $updates[$module]['existing_version'],
        $updates[$module]['recommended'],
      );

      if (version_compare($updates[$module]['existing_version'], $updates[$module]['recommended'])) {
        $requires_update[] = $row;
      }
    }

    return $requires_update;
  }
}