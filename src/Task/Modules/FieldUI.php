<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\FieldUI
 */

namespace DrushAudit\Task\Modules;

class FieldUI extends Module {
  public function info() {
    return array(
      'name' => 'Field UI',
      'machine_name' => 'field_ui',
      'status' => FALSE,
    );
  }
}