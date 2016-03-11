<?php

namespace DrushAudit\Controller;

class TaskController {

  /**
   * Iterate across all known tasks.
   */
  public static function all($options = array()) {
    $config = array(
      'tasks' => static::getTasks(),
      'options' => $options,
    );

    static::iterate($config);
  }

  public static function iterate(array $config = array()) {
    $config = $config + array('tasks' => array(), 'options' => array());
    foreach ($config['tasks'] as $task) {
      $task = new $task();

      $task->setOptions($config['options'])->getData();

      if ($task->canRun()) {
        $task->execute();
      }
    }
  }

  /**
   * Render the results from a given Task.
   *
   * @param $output
   *   Results of all tasks completed.
   * @param $options
   *   Drush input options.
   */
  public static function render($output, $options) {
    foreach ($output as $task => $result) {
      $result = $result + array('title' => 'Task', 'headers' => array(), 'body' => array());

      $title_hyph = str_repeat('-', count($result['title']));
      drush_log("\n> {$result['title']}\n$title_hyph\n", 'ok');

      $table = array($result['headers']);
      $table[] = array_fill(0, count($result['headers']), str_repeat('-', 20));
      drush_print_table($table + $result['body']);
    }
  }

}