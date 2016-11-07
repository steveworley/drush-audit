<?php
/**
 * @file
 * Contains DrushAudit\Task\Performance\Views.
 */

namespace DrushAudit\Task\Performance;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Views implements Task {

  use TaskTrait;

  var $info = array(
    'title' => 'Views',
    'headers' => ['View Id', 'Data'],
//    'headers' => array('View Name', 'Display ID', 'Caching', 'Pagination'),
  );

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->setData(views_get_all_views());
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $num = 30; // @TODO: Make this configurable.
    $output = [];

    foreach ($this->getData() as $id => $view) {
      $rows = [];

      foreach ($view->display as $display_id => $display) {
        $row = [];

        if ($display->display_options['cache']['type'] == 'none') {
          $row['cache'] = $display->display_options['cache']['type'];
        }

        if ($display->display_options['pager']['options']['items_per_page'] > $num) {
          $row['pagination'] = $display->display_options['pager']['options']['items_per_page'];
        }

        if (count($row) > 1) {
          $rows[] = array_merge(['display_id' => $display_id], $row);
        }
      }

      if (!empty($rows)) {
        $output[] = [$id, $rows];
      }
    }

    return $output;
  }

}
