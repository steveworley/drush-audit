<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\Syslog.
 */
namespace DrushAudit\Task\Modules;

class Syslog extends Module {
  public function info() {
    return array(
      'name' => 'System logging',
      'machine_name' => 'syslog',
      'status' => TRUE,
    );
  }
}