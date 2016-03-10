<?php

namespace DrushAudit\Controller;

class TaskController {

  public static function iterate(array $config = array()) {
    $config = $config + array('tasks' => array(), 'options' => array());

    foreach ($config['tasks'] as $task) {
      $task = new $task();

      $task->setOptions($config['options'])
        ->getData()
        ->execute();
    }
  }

}