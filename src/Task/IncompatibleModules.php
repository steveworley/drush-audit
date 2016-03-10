<?php

/**
 * @file
 * Contains DrushAudit\Task\IncompatibleMdoules;
 */

namespace DrushAudit\Task;

class IncompatibleModules implements Task {
  use TaskTrait;

  public function getData() {
    $this->setData(module_list());
    return $this;
  }

  public function isIncompatible($module = '') {
    $unsupported = array(
      'apc',
      'autoslave',
      'apachesolr_file',
      'backup_migrate',
      'bean',
      'boost',
      'civicrm',
      'configuration',
      'context_show_regions',
      'db_maintenance',
      'ds',
      'fbconnect',
      'filecache',
      'fivestar',
      'global_filter',
      'hierarchical_select',
      'imagefield_crop',
      'ip_geoloc',
      'linkchecker',
      'oauth',
      'optimizedb',
      'purge',
      'quicktabs',
      'recaptcha',
      'role_memory_limit',
      'session_api',
      'session_cache',
      'serial',
      'shib_auth',
      'smart_ip',
      'textsize',
      'varnish',
      'filter_harmonizer',
    );

    return in_array($module, $unsupported);
  }

  /**
   * The entry method into a Task.
   * @return mixed
   */
  public function execute() {
    $rows = array();
    foreach ($this->data as $module) {
      if ($this->isIncompatible($module)) {
        $rows[] = array($module);
      }
    }

    $this->outputHeader('Incompatible modules installed');
    $this->outputInfo($rows, array('Module name'));
  }
}