<?php
/**
 * @file
 * Contains DrushAudit\Task\Security\Menu.
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Menu implements Task {

  use TaskTrait;

  public $info = array(
    'title' => 'Menu',
    'description' => 'Runs all menu entries',
    'headers' => array('URL', 'Code'),
  );

  public function __construct() {
    $substitutions = array();
    $paths = array();

    // Collect substitution information.
    $node_types = drush_db_select('node_type', array('type'));

    foreach ($node_types as $node_type) {
      $substitutions[] = db_query_range("SELECT nid FROM {node} WHERE type = :type and status = :status",
        0, 1, array(":type" => $node_type->type, ":status" => 1))->fetchField();
      $substitutions[] = db_query_range(
        "SELECT nid FROM {node} WHERE type = :type and status = :status",
        0, 1, array(":type" => $node_type->type, ":status" => 0))->fetchField();
    }

    // Add taxonomies.
    if (module_exists('taxonomy')) {
      foreach (taxonomy_get_vocabularies() as $vocab) {
        $terms = taxonomy_get_tree($vocab);
        $substitutions[] = array_pop($terms)->tid;
      }
    }

    array_filter($substitutions);

    // Collect paths.
    $results = drush_db_select('menu_router', array('path'));
    $paths = drush_db_fetch_object($results);

    $this->setData(array(
      'substitutions' => $substitutions,
      'paths' => $paths,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    global $base_url;
    $data = $this->getData();
    $output = array();

    if (!$base_url) {
      drush_log('$base_url must be set');
      return array();
    }

    foreach ($data['paths'] as $result) {
      $urls = array();
      $path = $result->path;

      // We know the logout link is avaiable, but it will also kill the
      // active session, so skip it
      // This may need to be updated to support cookies provided by
      // JanRain and other SSOs
      if ($path == 'user/logout') {
        continue;
      }

      // replace wild cards with real values if exist
      if(strpos($path, '%')) {
        foreach($data['substitutions'] as $sub) {
          $urls[] = $base_url . '/' . str_replace('%', $sub, $path);
        }
      }
      else {
        $urls[] = $base_url . '/' . $path;
      }

      foreach ($urls as $url) {
        $request = drupal_http_request($url, array('type' => 'HEAD'));
        if ($request['code'] != 200) {
          $output[] = array($url, $request['code']);
        }
      }
    }

    return $output;
  }

}