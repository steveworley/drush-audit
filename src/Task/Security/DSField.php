<?php
/**
 * @file
 * Contains DrushAudit\Task\Security\DSField.
 */

namespace DrushAudit\Task\Security;


use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class DSField implements Task {
  use TaskTrait;

  public $info = array(
    'title' => 'Display Suite Fields',
    'headers' => array('NID', 'Display Format'),
  );

  public function __construct($options) {
    $this->setData(array('field_data_body'));
  }

  /**
   * {@inheritcdoc}
   */
  public function execute() {
    // @TODO: This list should be provided by an option.
    $allowed_text_formats = array('filtered_html', 'full_html', 'plain_text');

    foreach ($this->getData() as $field) {
      $result = db_query("select entity_id, body_format from {$field} where body_format not in (:af)", array(
        ':af' => implode(',', $allowed_text_formats),
      ));
      foreach ($result as $row) {
        $output[] = array($row->entity_id, $row->body_format);
      }
    }
    return $output;
  }
}