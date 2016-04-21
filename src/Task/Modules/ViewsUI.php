<?php

/**
 * @file
 * Contains DrushAudit\Task\Modules\ViewsUI.
 */

namespace DrushAudit\Task\Modules;

class ViewsUI extends Module {
  public function info() {
    return array(
      'name' => 'Views UI',
      'machine_name' => 'views_ui',
      'status' => FALSE,
    );
  }
}