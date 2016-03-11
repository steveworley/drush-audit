<?php
/**
 * @file
 * Contains DrushAudit\Task\Security\Views.
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Views implements Task {

  use TaskTrait;

  var $info = array(
    'title' => 'Views security task',
    'description' => '',
    'headers' => array('View', 'Display', 'Issue'),
  );

  public function __construct() {
    $this->setData(views_get_all_views());
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $unsafe_views = array();

    foreach ($this->getData() as $view_name => $view_info) {
      if (empty($view_info->display)) {
        // Weird edge case- a view can't be created without a display.
        continue;
      }

      foreach ($view_info->display as $display_name => $display_info) {
        if (!empty($display_info->display_options['access']['type']) && $display_info->display_options['access']['type'] == 'none') {
          $unsafe_views[] = array($view_name, $display_name, 'No access control found');
        }
      }
    }

    return $unsafe_views;
  }

}