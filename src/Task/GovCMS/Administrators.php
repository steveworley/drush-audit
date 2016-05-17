<?php
/**
 * Created by PhpStorm.
 * User: steven.worley
 * Date: 6/04/2016
 * Time: 12:29 PM
 */

namespace DrushAudit\Task\GovCMS;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Administrators implements Task {

  use TaskTrait;

  public $info = array(
    'title' => 'Administrator Users',
    'headers' => array('UID', 'Name', 'Status'),
  );

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // Ensure that no users are assigned the administrator role.
    $query = db_select('users', 'u')->fields('u', array('uid', 'name', 'status'));
    $query->innerJoin('users_roles', 'ur', 'u.uid = ur.uid');
    $query->innerJoin('role', 'r', 'ur.rid = r.rid');
    $query->condition('r.name', 'administrator');

    $data = $query->execute()->fetchAll();
    foreach ($data as &$row) {
      $row = (array) $row;
    }

    return $data;
  }
}