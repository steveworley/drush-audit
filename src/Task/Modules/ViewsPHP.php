<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\ViewsPHP
 */
namespace DrushAudit\Task\Modules;

class ViewsPHP extends Module {
  public function info() {
    return array(
      'name' => 'Views PHP',
      'machine_name' => 'views_php',
      'status' => FALSE,
    );
  }
}