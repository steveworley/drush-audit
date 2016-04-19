<?php

/**
 * @file
 * Contains DrushAudit\Controller\ModuleController.
 */

namespace DrushAudit\Controller;

class ModulesController extends TaskController {

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

  public static function iterate(array $config = array()) {
    $config = $config + array('tasks' => array(), 'options' => array());

    drush_print("\n> Performing module checks\n");

    foreach ($config['tasks'] as $task) {
      $module = new $task($config['options']);
      $row = array('name' => $module->getInfo('name'));
      $printed = FALSE;

      if (!$module->validateStatus()) {
        drush_print(dt(' !module should be !status', array(
          '!module' => $module->getInfo('name'),
          '!status' => $module->getInfo('status') ? 'enabled' : 'disabled',
        )));
        $printed = TRUE;
      }

      if ($module->getInfo('configuration', FALSE)) {
        if (!$printed) {
          drush_print(dt(" !module", array(
            '!module' => $module->getInfo('name'),
          )));
          $printed = TRUE;
        }

        drush_print(" Checking configuration\n ---");
        $config = $module->validateConfig();
        $table = array(
          'title' => FALSE,
          'headers' => array('Configuration', 'Expected', 'Actual'),
          'body' => array(),
        );

        foreach ($config as $key => $values) {
          $table['body'][] = array('configuration' => $key) + $values;
        }

        if (!empty($table['body'])) {
          drush_print_table($table['body']);
        }
      }

      if ($printed) {
        drush_print("------");
      }
    }
  }
}