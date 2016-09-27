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

  public static function iterate(array $config = array()) {
    $config = $config + array('tasks' => array(), 'options' => array());
    $output = array();

    foreach ($config['tasks'] as $task) {
      $module = new $task($config['options']);
      $module_name = $module->getInfo('name');

      $output[$module_name] = array();
      $output[$module_name]['status'] = array($module->getStatus());

      if ($module->getInfo('configuration', FALSE)) {
        $output[$module_name]['config'] = $module->getConfig();
      }
    }

    static::render($output, $config['options']);
  }

  public static function render($output, $options) {
    foreach ($output as $module => $info) {
      drush_print("> $module");

      $headers = array(array('Actual', 'Expected'));
      drush_print("\n Module status:");
      drush_print_table(array_merge($headers, $info['status']));

      if (!empty($info['config'])) {
        drush_print("\n Module configuration: ");
        $headers = array(array('Setting', 'Expected', 'Actual'));
        drush_print_table(array_merge($headers, $info['config']));
      }

      drush_print();
    }
  }
}
