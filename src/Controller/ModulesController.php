<?php

/**
 * @file
 * Contains DrushAudit\Controller\ModuleController.
 */

namespace DrushAudit\Controller;

class ModulesController extends TaskControllerInterface {

  public static function getTasks($task = FALSE) {
    $tasks = array(
      'admin' => 'DrushAudit\\Task\\Modules\\Admin',
      'crondebug' => 'DrushAudit\\Task\\Modules\\CronDebug',
      'dblog' => 'DrushAudit\\Task\\Modules\\DbLog',
      'devel' => 'DrushAudit\\Task\\Modules\\Devel',
      'fieldui' => 'DrushAudit\\Task\\Modules\\FieldUI',
      'loginsecurity' => 'DrushAudit\\Task\\Modules\\LoginSecurity',
      'memcache' => 'DrushAudit\\Task\\Modules\\Memcache',
      'php' => 'DrushAudit\\Task\\Modules\\PHP',
      'syslog' => 'DrushAudit\\Task\\Modules\\Syslog',
      'viewsphp' => 'DrushAudit\\Task\\Modules\\ViewsPHP',
      'viewsui' => 'DrushAudit\\Task\\Modules\\ViewsUI',
    );

    if (isset($tasks[$task])) {
      return array($tasks[$task]);
    }

    return array_values($tasks);
  }

  public function iterate(array $config = array()) {
    $config = $config + array('tasks' => array(), 'options' => array());
    $output[] = array(
      'title' => 'Modules',
      'headers' => ['Name', 'Status', 'Desired Status', 'Configuration'],
      'body' => [],
    );

    foreach ($config['tasks'] as $task) {
      $module = new $task($config['options']);
      extract($module->getStatus());

      $output[0]['body'][] = [
        $module->getInfo('name'),
        $actual,
        $expected,
        $module->getConfig(),
      ];
    }

    $this->render($output, $config['options']);
  }
}
