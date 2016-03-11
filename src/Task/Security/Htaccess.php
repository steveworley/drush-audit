<?php
/**
 * Created by PhpStorm.
 * User: steven.worley
 * Date: 11/03/2016
 * Time: 9:25 AM
 */

namespace DrushAudit\Task\Security;


use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Htaccess implements  Task{
  use TaskTrait;

  public $info = array(
    'title' => 'Htaccess checks',
    'headers' => array('File', 'Issue'),
  );

  /**
   * Register paths to htaccess files in docroot.
   */
  public function __construct() {
    // Use FS over PHP for this as it may need to search deep.
    $htaccess = array();
    exec('locate .htaccess', $htaccess);

    $htaccess = array_filter($htaccess, function($item) {
      return strpos($item, DRUPAL_ROOT) !== FALSE;
    });

    $this->setData($htaccess);
  }

  public function execute() {
    // TODO: Implement execute() method.
    return array();
  }
}