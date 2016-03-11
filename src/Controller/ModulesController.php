<?php

/**
 * @file
 * Contains DrushAudit\Controller\ModuleController.
 */

namespace DrushAudit\Controller;

class ModulesController extends TaskController {

  public static function getTasks($task = FALSE) {
    $tasks = array(
      'status' => 'DrushAudit\\Task\\Modules\\Status',
      // 'custom' => 'DrushAudit\\Task\\Modules\\Custom',
      'incompatible' => 'DrushAudit\\Task\\Modules\\Incompatible',
      'version-check' => 'DrushAudit\\Task\\Modules\\VersionCheck',
    );

    if (isset($tasks[$task])) {
      return array($tasks[$task]);
    }

    return array_values($tasks);
  }
}