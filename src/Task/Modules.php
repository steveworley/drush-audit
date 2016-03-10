<?php

/**
 * @file
 * Contains DrushAudit\Task\Modules.
 */

namespace DrushAudit\Task;

class Modules implements Task {

  use TaskTrait;

  /**
   * {@inheritdoc}
   */
  public function getData() {
    $modules = array(
      array(
        'name' => 'views_ui',
        'friendly_name' => 'Views UI',
        'desired_status' => FALSE,
      ),
      array(
        'name' => 'views_php',
        'friendly_name' => 'Views PHP',
        'desired_status' => FALSE,
      ),
      array(
        'name' => 'dblog',
        'friendly_name' => 'Database logging',
        'desired_status' => FALSE,
      ),
      array(
        'name' => 'syslog',
        'friendly_name' => 'System logging',
        'desired_status' => TRUE,
      ),
      array(
        'name' => 'memcache',
        'friendly_name' => 'Memcache',
        'desired_status' => TRUE,
      ),
      array(
        'name' => 'admin',
        'friendly_name' => 'Administration tools',
        'desired_status' => FALSE,
      ),
      array(
        'name' => 'cron_debug',
        'friendly_name' => 'Cron debug',
        'desired_status' => FALSE,
      ),
      array(
        'name' => 'devel',
        'friendly_name' => 'Devel',
        'desired_status' => FALSE,
      ),
      array(
        'name' => 'php',
        'friendly_name' => 'PHP filter',
        'desired_status' => FALSE,
      ),
    );

    $this->setData($modules);

    return $this;
  }

  /**
   * The entry method into a Task.
   * @return mixed
   */
  public function execute() {
    $rows = array();

    foreach ($this->data as $module) {
      $desired_status = ($module['desired_status']) ? 'enabled' : 'disabled';
      $status = module_exists($module['name']) ? 'enabled' : 'disabled';

      if ($desired_status != $status) {
        $rows[] = array($module['friendly_name'], $status, $desired_status);
      }
    }

    $this->outputHeader('Module checks');
    $this->outputInfo($rows, array('Module name', 'Status', 'Desired status'), 'All modules are in the desired status.');
  }
}