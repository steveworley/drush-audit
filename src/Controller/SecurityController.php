<?php
/**
 * Created by PhpStorm.
 * User: steven.worley
 * Date: 11/03/2016
 * Time: 10:01 AM
 */

namespace DrushAudit\Controller;


class SecurityController extends TaskController {

  private static function getTasks($task = FALSE) {
    $tasks = array(
      'htaccess' => 'DrushAudit\\Task\\Security\\Htaccess',
      'roles' => 'DrushAudit\\Task\\Security\\Roles',
      'settings' => 'DrushAudit\\Task\\Security\\Settings',
      'textformats' => 'DrushAudit\\Task\\Security\\TextFormats',
      'user' => 'DrushAudit\\Task\\Security\\User',
      'views' => 'DrushAudit\\Task\\Security\\Views',
    );

    if (isset($tasks[$task])) {
      return array($tasks[$task]);
    }

    return array_values($tasks);
  }

  public static function iterate(array $config = array()) {
    $output = array();
    $config = $config + array('tasks' => array(), 'options' => array());

    foreach ($config['tasks'] as $task) {
      if (!$task = self::getTasks($task)) {
        continue;
      }

      $task = new $task($config['options']);
      $output[$task] = array(
        'title' => $task->getInfo('title'),
        'headers' => $task->getInfo('headers'),
        'body' => $task->execute(),
      );
    }

    self::render($output, $config['options']);
  }

}