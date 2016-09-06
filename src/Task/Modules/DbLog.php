<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\DbLog
 */

namespace DrushAudit\Task\Modules;

class DbLog extends Module {
  public function info() {
    return array(
      'name' => 'Database Logging',
      'machine_name' => 'dblog',
      'status' => FALSE,
    );
  }
}