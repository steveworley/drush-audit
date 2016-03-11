<?php

/**
 * @file
 */

namespace DrushAudit\Task;

class CustomModule implements Task {

  use TaskTrait;

  /**
   * {@inheritdoc}
   */
  public function execute() {
    foreach ($this->data as $site => $module_list) {
      foreach ($module_list as $module => $path) {
        exec("phpcs --standard=Drupal $path");
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
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
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function canRun() {
    return exec('which phpcs');
  }
}