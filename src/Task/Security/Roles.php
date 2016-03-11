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

    $this->setData(array(
      'roles' => $roles,
      'permissions' => user_permission_get_modules(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // TODO: Implement execute() method.
  }

}