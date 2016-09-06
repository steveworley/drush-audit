<?php

/**
 * @file
 * Contains DrushAudit\Task\Modules\Update.
 */

namespace DrushAudit\Task\Modules;

class Update {

  /**
   * {@inheritdoc}
   */
  public function info() {
    return array(
      'name' => 'Update',
      'machine_name' => 'update',
      'status' => FALSE,
    );
  }
}