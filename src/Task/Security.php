<?php

/**
 * @file
 * Contains DrushAudit\Task\Security.
 */

namespace DrushAudit\Task;

class Security implements Task {

  use TaskTrait;

  /**
   * Ensure that the error reporting level is set correctly.
   *
   * @param array $rows
   *   Output array $rows.
   */
  public function checkUser(&$rows) {
    $row = array('Root user access check');

    $root_user_check = db_select('users', 'u')
      ->fields('u', array('uid', 'status'))
      ->condition('uid', 1, '=')
      ->execute()
      ->fetchAssoc();

    if ($root_user_check['status'] == 0) {
      $row[] = 'User ID 1 is disabled.';
    }
    else {
      $row[] = 'User ID 1 should be blocked from access.';
    }

    $rows[] = $row;
  }

  /**
   * Ensure that the error reporting level is set correctly.
   *
   * @param array $rows
   *   Output array $rows.
   */
  public function checkErrorReporting(&$rows) {
    $row = array('Error reporting');

    if (variable_get('error_level') == 0) {
      $row[] = 'Error reporting is not being shown to end users.';
    }
    else {
      $row[] = 'Error reporting is being shown to end users.';
    }

    $rows[] = $row;
  }

  /**
   * Ensure that user defined views have permissions.
   *
   * @param array $rows
   *   Output array $rows.
   */
  public function checkViewsPermissions(&$rows) {
    $unsafe_views = array();
    $views = views_get_all_views();

    $row = array('Views permissions check');

    foreach ($views as $view_name => $view_info) {
      if (empty($view_info->display)) {
        continue;
      }

      foreach ($view_info->display as $display_name => $display_info) {
        if (!empty($display_info->display_options['access']['type']) && $display_info->display_options['access']['type'] == 'none') {
          $unsafe_views[] = "{$view_name} ({$display_name})";
        }
      }
    }

    if (empty($unsafe_views)) {
      $row[] = 'All views have permissions assigned.';
    }
    else {
      $row[] = 'The following views are missing permissions: ' . implode(', ', $unsafe_views);
    }

    $rows[] = $row;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $rows = array();

    // Perform the security checks.
    $this->checkUser($rows);
    $this->checkErrorReporting($rows);
    $this->checkViewsPermissions($rows);

    $this->outputHeader('Security check');
    $this->outputInfo($rows, array('Task', 'Status'));
  }
}