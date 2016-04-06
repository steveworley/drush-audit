<?php

/**
 * @file
 * Traits for tasks.
 */

namespace DrushAudit\Task;

trait TaskTrait {

  private $data = array();

  private $opts = array();

  /**
   * Determine if this Task can run.
   *
   * @return bool
   */
  public function canRun() {
    return TRUE;
  }

  /**
   * Set any command line options.
   * @param array $options
   */
  public function setOptions($options = array()) {
    $this->opts = $options;
    return $this;
  }

  /**
   * Get the options that were set.
   * @return array
   */
  public function getOption($name = FALSE) {
    return isset($this->opts[$name]) ? $this->opts[$name] : FALSE;
  }

  /**
   * Mutator for $this->data.
   */
  public function setData($data = array()) {
    $this->data = is_array($data) ? $data : array($data);
  }

  /**
   * Accessor for $this->data.
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Verify that this task has enough information to run.
   *
   * This can be used to verify that a task can execute. If this returns
   * FALSE the task will not execute and an error will be printed.
   *
   * @return bool
   *   If the task has enough information to execute.
   */
  public function verify() {
    return TRUE;
  }

  /**
   * Get meta information about the task.
   *
   * @param $info
   *   A key for the info.
   * @param $default
   *   A default message to display.
   *
   * @return bool|string
   */
  public function getInfo($info, $default = FALSE) {
    return isset($this->info[$info]) ? $this->info[$info] : $default;
  }

  /**
   * Output an error message via drush_log().
   *
   * This will not only output to the user but also capture all errors into a
   * variable (global $errors) which can be utilised later.
   *
   * @param string $message
   *   The error message to be shown to the user.
   */
  public function outputError($message) {
    drush_log("     $message", 'error');
  }

  /**
   * Output a message to the user via drush_log().
   *
   * @param string $message
   *   The message to display to the user.
   */
  public function outputInfo(array $message, $headers, $fallback = '') {
    $table = array($headers);
    $table[] = array_fill(0, count($headers), str_repeat('-', 20));

    if (empty($message)) {
      drush_log("        $fallback", 'ok');
    }

    drush_print_table(array_merge($table, $message));
  }

  /**
   * Output a header line in the drush line.
   *
   * @param string $message
   *   The message to output as a header.
   */
  public function outputHeader($message) {
    drush_log("\n> $message \n", 'ok');
  }

}