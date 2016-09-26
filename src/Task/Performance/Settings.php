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
   * Attempt to determine if the site needs to run updates.
   */
  public function getUpdateStatus() {
    require_once DRUPAL_ROOT . '/includes/update.inc';
    require_once DRUPAL_ROOT . '/includes/install.inc';
    return update_get_update_list();
  }

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
      $results[] = array('preprocess_css', 'CSS is not aggregated');
    }

    if (variable_get('preprocess_js') != 1) {
      $results[] = array('preprocess_js', 'JS is not aggregated');
    }

    if (!empty($this->getUpdateStatus())) {
      $results[] = array('updates', 'Updates need to be run');
    }

    if (variable_get('error_level') != 0) {
      $results[] = ['error_level', 'Errors are being displayed'];
    }

    return $results;
  }
}
