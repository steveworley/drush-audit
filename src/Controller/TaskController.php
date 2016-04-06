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

  /**
   * Iterate over a given configuration.
   *
   * @param array $config
   */
  public static function iterate(array $config = array()) {
    $output = array();
    $config = $config + array('tasks' => array(), 'options' => array());

    foreach ($config['tasks'] as $task) {
      $task = new $task($config['options']);
      drush_print('> Collecting information for ' . $task->getInfo('title'));

      if (!$task->verify()) {
        drush_print('  ' . $task->getInfo('invalid', 'Unable to gather information for ' . $task->getInfo('title')));
        continue;
      }

      $output[] = array(
        'title' => $task->getInfo('title'),
        'headers' => $task->getInfo('headers'),
        'body' => $task->execute(),
      );
    }

    static::render($output, $config['options']);
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

      $title_hyph = str_repeat('-', strlen($result['title']));
      drush_log("\n> {$result['title']}\n", 'ok');

      if (count($result['body']) < 1) {
        drush_log("No results for {$result['title']}", 'warn');
        continue;
      }

      $table = array($result['headers']);
      $table[] = array_fill(0, count($result['headers']), str_repeat('-', 20));
      $result['body'] = is_array($result['body']) ? $result['body'] : array($result['body']);

      drush_print_table(array_merge($table, $result['body']));
    }
  }

}