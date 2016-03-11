<?php
/**
 * @file
 * Contains DrushAudit/Task/Securiry/User.
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class User implements Task {

  use TaskTrait;

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $root_user_check = db_select('users', 'u')
      ->fields('u', array('uid', 'status'))
      ->condition('uid', 1, '=')
      ->execute()
      ->fetchAssoc();

    if ($root_user_check['status'] != 0) {
      return array(dt('User with ID1 is not blocked'));
    }
  }
}