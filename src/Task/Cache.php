<?php

/**
 * @file
 * Check the site has configured cache settings.
 */

namespace DrushAudit\Task;

class Cache implements Task {

  use TaskTrait;

  /**
   * {@inheritdoc}
   */
  public function execute() {

    $rows = array();

    if (variable_get('cache')) {
      $rows[] = array('Page Cache', 'Enabled');
    }
    else {
      $rows[] = array('Page Cache', 'Disabled');
    }

    if (variable_get('block_cache')) {
      $rows[] = array('Block Cache', 'Enabled');
    }
    else {
      $rows[] = array('Block Cache', 'Disabled');
    }

    if (variable_get('cache_lifetime') == 0) {
      $rows[] = array('Cache lifetime', 'Valid');
    }
    else {
      $rows[] = array('Cache lifetime', 'Invalid');
    }

    if (variable_get('page_cache_maximum_age') <= 3600) {
      $rows[] = array('External page caching', 'Enabled');
    }
    else {
      $rows[] = array('External page caching', 'Invalid');
    }

    $this->outputHeader('Checking cache settings');
    $this->outputInfo($rows, array('Cache', 'Status'));
  }

}