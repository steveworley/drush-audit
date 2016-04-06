<?php

/**
 * @file
 * Task interface.
 */

namespace DrushAudit\Task;

interface Task {

  /**
   * The entry method into a Task.
   * @return mixed
   */
  public function execute();


}