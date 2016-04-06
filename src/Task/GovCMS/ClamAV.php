<?php
/**
 * Created by PhpStorm.
 * User: steven.worley
 * Date: 6/04/2016
 * Time: 1:16 PM
 */

namespace DrushAudit\Task\GovCMS;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class ClamAV implements Task {

  use TaskTrait;

  public $info = array(
    'title' => 'ClamAV configuration',
    'invalid' => 'Module is not enabled',
    'headers' => array('Option', 'Expected'),
  );

  /**
   * {@inheritdoc}
   */
  public function verify() {
    return module_exists('clamav');
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // @TODO: Get additional data around what settings we need to validate.
    return array();
  }
}