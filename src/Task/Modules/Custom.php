<?php

/**
 * @file
 */

namespace DrushAudit\Task\Modules;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Custom implements Task {

  use TaskTrait;

  public $info = array(
    'title' => 'Custom module sniffs',
    'headers' => array(),
  );

  public function __construct() {
    $sites = array();

    foreach (glob(DRUPAL_ROOT  . '/sites/*' , GLOB_ONLYDIR) as $site_path) {
      $site = explode('/', $site_path);
      $site = end($site);

      $sites[$site] = array();

      foreach (glob($site_path . '/modules/custom/*', GLOB_ONLYDIR) as $module_path) {
        $module_name = explode('/', $module_path);
        $module_name = end($module_name);
        $sites[$site][$module_name] = $module_path;
      }
    }

    $this->setData($sites);
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $phpcs = FALSE;
    exec('which phpcs', $phpcs);

    if (!$phpcs) {
      return array('Please install PHPCS to run sniffs on custom modules');
    }

    foreach ($this->data as $site => $module_list) {
      foreach ($module_list as $module => $path) {
        exec("phpcs --standard=Drupal $path");
      }
    }

    return array();
  }
}