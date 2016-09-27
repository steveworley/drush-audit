<?php

/**
 * @file
 * Contains DrushAudit\Controller\TaskControllerInterface.
 */

namespace DrushAudit\Controller;

use DrushAudit\Output\OutputInterface;

abstract class TaskControllerInterface {

  /**
   * The OutputInterface for this audit.
   *
   * @var OutputInterface
   */
  protected $output;

  /**
   * Drush options given at run-time.
   *
   * @var array
   */
  protected $options;

  /**
   * Build a Task Controller.
   *
   * @param  OutputInterface $output
   *   The output plugin for the audit.
   */
  public function __construct(array $options = array(), OutputInterface $output) {
    $this->output = $output;
    $this->options = $options;
  }

  /**
   * Return a list of tasks for the controller.
   *
   * @return array
   */
  abstract static function getTasks($task = NULL);

  /**
   * Iterate across all known tasks.
   */
  public function all() {
    $config = array(
      'tasks' => static::getTasks(),
      'options' => $this->options,
    );

    $this->iterate($config);
  }

  /**
   * Iterate over a given configuration.
   *
   * @param array $config
   */
  public function iterate(array $config = array()) {
    $output = array();
    $config = $config + array(
      'tasks' => array(),
      'options' => $this->options,
    );

    foreach ($config['tasks'] as $task) {
      $task = new $task($config['options']);
      drush_print('> Collecting information for ' . $task->getInfo('title'));

      $output[] = array(
        'title' => $task->getInfo('title'),
        'headers' => $task->getInfo('headers'),
        'body' => $task->execute(),
      );
    }

    return $this->render($output);
  }

  /**
   * Render the results from a given Task.
   *
   * @param $output
   *   Results of all tasks completed.
   */
  public function render($output) {
    return $this->output->render($output, $this->options);
  }

}
