<?php
/**
 * Created by PhpStorm.
 * User: steven.worley
 * Date: 16/03/2016
 * Time: 1:03 PM
 */

namespace DrushAudit\Task\Performance;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Modules implements Task {
  use TaskTrait;

  var $info = array(
    'title' => 'Underperforming modules',
    'headers' => array('Module', 'Info'),
  );

  public function __construct() {
    $this->setData(module_list());
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $output = array();
    $exclude = array(
      'dblog' => 'Syslog should be used instead of dblog'
    );

    foreach ($this->getData() as $module) {
      if (in_array($module, array_keys($exclude))) {
        $output = array($module, $exclude[$module]);
      }
    }

    return $output;
  }
}