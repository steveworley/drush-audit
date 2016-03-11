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

  public $info = array(
    'title' => 'User account checks',
    'headers' => array('UID', 'Issue'),
  );

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $output = array();

    $root_user_check = db_select('users', 'u')
      ->fields('u', array('uid', 'status'))
      ->condition('uid', 1, '=')
      ->execute()
      ->fetchAssoc();

    if ($root_user_check['status'] != 0) {
      $output[] = array(1, 'User with ID is not blocked');
    }

    return $output;
  }
}