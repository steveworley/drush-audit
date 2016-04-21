<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\Memcache.
 */

namespace DrushAudit\Task\Modules;

class Memcache extends Module {

  /**
   * {@inheritdoc}
   */
  public function info() {
    return array(
      'name' => 'Memcache',
      'machine_name' => 'memcache',
      'status' => TRUE,
    );
  }

}