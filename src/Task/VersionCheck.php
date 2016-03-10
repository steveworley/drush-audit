<?php

/**
 * @file
 * Check installed version of a module against
 */

namespace DrushAudit\Task;

class VersionCheck implements Task {

  use TaskTrait;

  /**
   * {@inheritdoc}
   */
  public function getData() {
    $module_list = module_list();

    if ($this->getOption('all')) {
      $this->setData($module_list);
      return $this;
    }

    $module = $this->getOption('module');
    if (!isset($module_list[$module])) {
      $this->outputError("Invalid module: $module is not enabled");
      return $this;
    }

    $this->setData(array($module));
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $available = update_get_available();

    if (!$available) {
      $this->outputError('Could not get module update status');
    }

    $updates = update_calculate_project_data($available);

    $requires_update = $okay = array();

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
      } else {
        $okay[] = $row;
      }
    }

    $headers = array('Module Name', 'Installed', 'Recommended');

    $this->outputHeader('Modules that require updates');
    $this->outputInfo($requires_update, $headers);

    if ($this->getOption('up-to-date')) {
      $this->outputHeader('Modules that do not require updates');
      $this->outputInfo($okay, $headers);
    }
  }
}