<?php
/**
 * @file
 * Contains DrushAudiut\Controller\PerformanceController.
 */

namespace DrushAudit\Controller;

class PerformanceController extends TaskController {
  public function getTasks($task = FALSE) {
    $tasks = array(
      'cache' => 'DrushAudit\\Task\\Performance\\Cache',
      'settings' => 'DrushAudit\\Task\\Performance\\Settings',
      'modules' => 'DrushAudit\\Task\\Performance\\Modules',
      'views' => 'DrushAudit\\Task\\Performance\\Views',
    );

    if (isset($tasks[$task])) {
      return array($tasks[$task]);
    }

    return array_values($tasks);
  }
}