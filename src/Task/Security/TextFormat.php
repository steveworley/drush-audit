<?php
/**
 * @file
 * Contains DrushAudit\Task\Security\TextFormat.
 */

namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class TextFormat implements Task {

  use TaskTrait;

  var $info = array(
    'title' => 'Text format permissions',
  );

  /**
   * {@inheritDoc}
   */
  public function __construct() {
    $text_formats = filter_formats();
    $filter_info = filter_get_filters();

    foreach ($text_formats as $id => &$format) {
      $format->roles = filter_get_roles_by_format($format);
      $format->filters = !empty($format->format) ? filter_list_format($format->format) : array();

      // Create an empty filter object for new/unconfigured filters.
      foreach ($filter_info as $name => $filter) {
        if (!isset($format->filters[$name])) {
          $format->filters[$name] = new \stdClass();
          $format->filters[$name]->format = $format->format;
          $format->filters[$name]->module = $filter['module'];
          $format->filters[$name]->name = $name;
          $format->filters[$name]->status = 0;
          $format->filters[$name]->weight = $filter['weight'];
          $format->filters[$name]->settings = array();
        }
      }
    }

    $this->setData($text_formats);
  }

  /**
   * {@inheritDoc}
   */
  public function execute() {

    $rows = array();

    foreach ($this->data as $id => $format) {
      if (count($format->roles) == 1 && in_array('administrator', $format->roles)) {
        continue;
      }

      $filters_html = (bool) $format->filters['filter_html']->status;

      if (!$filters_html) {
        $rows[] = array($id, implode(', ', array_values($format->roles)));
      }
    }

    return $rows;
  }
}