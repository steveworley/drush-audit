<?php
/**
 * @file
 * Contains DrushAudit\Task\Security\PhpFilter.
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class PhpFilter implements Task {

  use TaskTrait;

  public $info = array(
    'title' => 'PHP Filter',
    'description' => 'Security checks around fields',
    'headers' => array('Table', 'Field', 'Filter', 'Field count'),
  );

  public function __construct() {
    global $databases;
    $columns = array();

    // Get database names first.
    foreach ($databases as $name => $tables) {
      foreach ($tables as $id => $info) {
        $list = db_query("select COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME LIKE \"%format%\" AND TABLE_SCHEMA = '{$info['database']}'");
        $columns[$info['database']] = $list->fetchAssoc();
      }
    }

    $this->setData($columns);
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $output = array();
    $input_filter = 'php';

    foreach ($this->getData() as $table => $columns) {
      foreach ($columns as $column) {
        $field_count = db_query("select count(*) as count from {$table} where {column} like '%{$input_filter}%'");
        $count = $field_count->fetchColumn();
        if ($count > 0) {
          $output[] = array($table, $column, $input_filter, $count);
        }
      }
    }

    return $output;
  }

}