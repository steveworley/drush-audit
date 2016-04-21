<?php

/**
 * @file
 * Contains DrushAudit\Task\Modules\Devel.
 */

namespace DrushAudit\Task\Modules;

class Devel extends Module {
  public function info() {
    return array(
      'name' => 'Devel',
      'machine_name' => 'devel',
      'status' => FALSE,
    );
  }
}