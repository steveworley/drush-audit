<?php

/**
 * @file
 * Check the site has configured cache settings.
 */

namespace DrushAudit\Task\Performance;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Cache implements Task {

  use TaskTrait;

  var $info = array(
    'title' => 'Cache settings',
    'headers' => array('Cache Setting', 'Issue'),
  );

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $rows = array();

    if (!variable_get('cache')) {
      $rows[] = array('Page', 'Cache is disabled');
    }

    if (!variable_get('block_cache')) {
      $rows[] = array('Block', 'Cache is disabled.');
    }

    if (variable_get('cache_lifetime') != 0) {
      $rows[] = array('Cache lifetime', 'Invalid cache lifetime this should be 0 to allow reverse proxy caching');
    }

    if (variable_get('page_cache_maximum_age') >= 3600) {
      $rows[] = array('Cache max age', 'Maximum page cache lifetime is greater than one hour consider revising');
    }

    return $rows;
  }

}