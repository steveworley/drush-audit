<?php

/**
 * @file
 * Check installed version of a module against
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class VersionCheck implements Task {

  use TaskTrait;

  const UPDATE_DEFAULT_URL = 'http://updates.drupal.org/release-history';

  public $info = array(
    'title' => 'Version check for installed modules',
    'headers' => array('Module name', 'Installed', 'Recommended', 'Type'),
  );

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->setData($this->getProjects());
    return $this;
  }

  /**
   * Get all projects for the current Drupal site.
   *
   * @return array
   *   A list of projects with latest version to compare.
   */
  private function getProjects() {
    $module_data = array_filter(system_rebuild_module_data(), function($file) {
      return $file->status != 0;
    });

    foreach ($module_data as $project) {
      // @TODO: this is {x} HTTP requests and can be slow will need to optimise.
      $project->latest = $this->fetchProjectLatestRelease($project);
    }

    return $module_data;
  }

  /**
   * Fetch the project status from Drupal.org
   *
   * @param array $project
   *   An module list array.
   *
   * @return object
   */
  private function fetchProjectLatestRelease($project) {
    // @TODO: Look for project specific url.
    // @see _update_get_fetch_url_base.
    $url = self::UPDATE_DEFAULT_URL;
    $url .= "/{$project->name}/" . DRUPAL_CORE_COMPATIBILITY;
    $xml = drupal_http_request($url);

    if (isset($xml->error)) {
      return FALSE;
    }

    $xml = simplexml_load_string($xml->data);
    $data = json_decode(json_encode($xml), TRUE);

    return isset($data['releases']['release']) ? reset($data['releases']['release']) : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $projects = self::getProjects();
    $return = array();

    foreach ($this->data as $module => $project) {
      if (empty($project->latest['version']) || version_compare($project->info['version'], $project->latest['version']) >= 0) {
        // The version is at least the latest so we can skip this project or the
        // version is not present this is generally a custom module one that
        // doesn't have release history on DO so we can skip.
        continue;
      }

      $terms = [];

      if (isset($project->latest['terms'])) {
        if (isset($project->latest['terms']['term']['value'])) {
          $terms[] = $project->latest['terms']['term']['value'];
        } else {
          foreach ($project->latest['terms']['term'] as $term) {
            $terms[] = $term['value'];
          }
        }
      }

      $return[] = array(
        $module,
        $project->info['version'],
        $project->latest['version'],
        implode(', ', $terms),
      );
    }

    return $return;
  }
}