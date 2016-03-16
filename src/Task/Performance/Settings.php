<?php
/**
 * Created by PhpStorm.
 * User: steven.worley
 * Date: 16/03/2016
 * Time: 12:54 PM
 */

namespace DrushAudit\Task\Performance;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Settings implements Task {
  use TaskTrait;

  var $info = array(
    'title' => 'Settings performance checks',
    'headers' => array('Setting', 'Issue'),
  );

  /**
   * {@inheritdoc}
   */
  public function execute() {
    global $conf;

    $results = array();

    if (!empty($conf['theme_debug'])) {
      $results[] = array('theme_debug', 'Theme debug should not be enabled');
    }

    if (variable_get('preprocess_css') != 1) {
      $results = array('preprocess_css', 'CSS is not aggregated');
    }

    if (variable_get('preprocess_js') != 1) {
      $results = array('preprocess_js', 'JS is not aggregated');
    }

    return $results;
  }
}