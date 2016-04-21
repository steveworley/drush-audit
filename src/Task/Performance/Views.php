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
    'headers' => array(),
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

    foreach ($this->getData() as $id => $view) {
      $indent = "  ";
      drush_print("$indent>> Checking view: $id");

      foreach ($view->display as $display_id => $display) {
        $indent = "    ";
        drush_print("$indent>>> $display_id");
        $indent = "$indent   ";

        if ($display->display_options['cache']['type'] == 'none') {
          drush_print("$indent- view is not being cached");
        }

        if ($display->display_options['pager']['options']['items_per_page'] > $num) {
          drush_print("$indent- view is displaying more than $num items.");
        }

      }

      drush_print();
    }

    return array();
  }

}