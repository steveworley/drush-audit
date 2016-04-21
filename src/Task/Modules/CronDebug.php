<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\CronDebug.
 */

namespace DrushAudit\Task\Modules;

class CronDebug extends Module {

  /**
   * {@inheritdoc}
   */
  public function info() {
    return array(
      'name' => 'Cron debug',
      'machine_name' => 'cron_debug',
      'status' => FALSE,
    );
  }
}