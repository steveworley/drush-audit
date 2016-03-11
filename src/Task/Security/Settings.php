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
    'title' => 'Roles security checks',
  );

  public function execute() {
    global $conf;

    $results = array();

    if (variable_get('error_level', 0) !== 0) {
      $results[] = 'Error reporting is being displayed to users';
    }

    if (empty($conf['base_url'])) {
      $results[] = 'Base url is not defined in settings.php';
    }
  }


}