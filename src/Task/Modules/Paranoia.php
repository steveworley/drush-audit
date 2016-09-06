<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\Paranoia.
 */

namespace DrushAudit\Task\Modules;

class Paranoia extends Module {

  /**
   * {@inheritdoc}
   */
  public function info() {
    return array(
      'name' => 'Paranoia',
      'machine_name' => 'paranoia',
      'status' => TRUE,
    );
  }
}