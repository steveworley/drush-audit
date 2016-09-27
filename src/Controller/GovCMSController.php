<?php
/**
 * @file
 * Contains DrushAudit\Controller\GovCMSController.
 */

namespace DrushAudit\Controller;

class GovCMSController extends TaskControllerInterface {

  /**
   * {@inheritdoc}
   */
  public static function getTasks($task = FALSE) {
    $tasks = array(
      'administrators' => 'DrushAudit\\Task\\GovCMS\\Administrators',
    );

    return $task && isset($tasks[$task]) ? array($tasks[$task]) : $tasks;
  }

}
