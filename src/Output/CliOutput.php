<?php

/**
 * @file
 * Contains DrushAudit\Output\CliOutput.
 */

namespace DrushAudit\Output;

class CliOutput implements OutputInterface {

  /**
   * {@inheritdoc}
   */
  public function render(array $results = array(), array $options = array()) {
    foreach ($results as $task => $result) {
      $result = $result + array('title' => FALSE, 'headers' => array(), 'body' => array());

      if (!empty($result['title'])) {
        drush_log("\n> {$result['title']}\n", 'ok');
      }

      if (count($result['body']) < 1) {
        drush_log("No results for {$result['title']}", 'warn');
        continue;
      }

      $table = array($result['headers']);
      $table[] = array_fill(0, count($result['headers']), str_repeat('-', 20));
      $result['body'] = is_array($result['body']) ? $result['body'] : array($result['body']);

      foreach ($result['body'] as &$row) {
        foreach ($row as &$item) {
          if (is_array($item)) {
            foreach ($item as &$sub_item) {
              $sub_item = implode(', ', $sub_item);
            }
            $item = is_array($item) ? implode("\n", $item) : $item;
          }
        }
      }

      drush_print_table(array_merge($table, $result['body']));
    }

    return TRUE;
  }

}
