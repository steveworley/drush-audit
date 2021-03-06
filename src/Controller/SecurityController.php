<?php

/**
 * @file
 * Contains DrushAudit\Controller\SecurityController.
 */

namespace DrushAudit\Controller;

use DrushAudit\Controller\TaskControllerInterface;

class SecurityController extends TaskControllerInterface {

  /**
   * {@inheritdoc}
   */
  public static function getTasks($task = FALSE) {
    $tasks = array(
      'htaccess' => 'DrushAudit\\Task\\Security\\Htaccess',
      'roles' => 'DrushAudit\\Task\\Security\\Roles',
      'settings' => 'DrushAudit\\Task\\Security\\Settings',
      'textformats' => 'DrushAudit\\Task\\Security\\TextFormat',
      'user' => 'DrushAudit\\Task\\Security\\User',
      'views' => 'DrushAudit\\Task\\Security\\Views',
      'headers' => 'DrushAudit\\Task\\Security\\Headers',
      'version' => 'DrushAudit\\Task\\Security\\VersionCheck',
    );

    if (isset($tasks[$task])) {
      return array($tasks[$task]);
    }

    return array_values($tasks);
  }

}
