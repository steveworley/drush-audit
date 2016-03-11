<?php
/**
 * @file
 * Includes DrushAudit\Task\Security\Settings.
 */
namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Settings implements Task {

  use TaskTrait;

  var $info = array(
    'title' => 'Settings security checks',
    'headers' => array('Setting', 'Issue'),
  );

  /**
   * {@inheritdoc}
   */
  public function execute() {
    global $conf;

    $results = array();

    if (variable_get('error_level', 0) != 0) {
      $results[] = array('Error reporting', 'Error reporting is displayed to users');
    }

    if (empty($conf['base_url'])) {
      $results[] = array('Configuration', '$conf[\'base_url\'] is not defined in settings');
    }

    return $results;
  }


}