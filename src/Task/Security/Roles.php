<?php
/**
 * @file
 * DrushAudit\Task\Security\Roles
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Roles implements Task {

  use TaskTrait;

  var $info = array(
    'title' => 'Role permissions',
    'description' => 'Determine if generic user roles have access to edit content',
    'headers' => array('Role', 'Permission', 'Module'),
  );

  /**
   * Get role information for authenticated and unauthenticated roles.
   */
  public function __construct() {
    $authenticated_role = user_role_load_by_name('authenticated user');
    $unauthenticated_role = user_role_load_by_name('anonymous user');

    $roles = array(
      $authenticated_role->rid => $authenticated_role,
      $unauthenticated_role->rid => $unauthenticated_role,
    );

    $this->setData($roles);
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $output = array();
    $permissions = array(
      'edit_any%',
      'delete_any%',
    );

    foreach ($this->getData() as $rid => $role) {
      foreach ($permissions as $permission) {
        $result = db_query('select module, permission from role_permission where rid = :rid and permission like :perm', array(
          ':rid' => $rid,
          ':perm' => $permission,
        ));

        foreach ($result as $row) {
          $output[] = array($role->name, $row->permission, $row->module);
        }
      }
    }

    return $output;
  }

}
