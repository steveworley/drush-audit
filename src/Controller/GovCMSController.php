<?php
/**
 * @file
 * Contains DrushAudit\Controller\GovCMSController.
 */

namespace DrushAudit\Controller;

class GovCMSController extends TaskController {

  /**
   * {@inheritdoc}
   */
  public static function getTasks($task = FALSE) {
    $tasks = array(
      'clamav' => 'DrushAudit\\Task\\GovCMS\\ClamAV',
    );
    return $task && isset($tasks[$task]) ? array($tasks[$task]) : $tasks;
  }

}