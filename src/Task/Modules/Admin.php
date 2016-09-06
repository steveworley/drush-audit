<?php

/**
 * @file
 * Contains DrushAudit\Task\Modules\Admin.
 */

namespace DrushAudit\Task\Modules;

class Admin extends Module {

  /**
   * {@inheritdoc}}
   */
  public function info() {
    return array(
      'name' => 'Administration tools',
      'machine_name' => 'admin',
      'status' => FALSE,
    );
  }
}