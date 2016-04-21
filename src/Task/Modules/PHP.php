<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules.
 */

namespace DrushAudit\Task\Modules;

class PHP extends Module {
  public function info() {
    return array(
      'name' => 'PHP Filter',
      'machine_name' => 'php',
      'status' => FALSE,
    );
  }
}