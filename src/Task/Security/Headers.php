<?php
/**
 * @file
 * Contains DrushAudit\Task\Security\Headers.
 */
namespace DrushAudit\Task\Security;

use DrushAudit\Task\Task;
use DrushAudit\Task\TaskTrait;

class Headers implements Task {
  use TaskTrait;

  var $info = array(
    'title' => 'Headers security check',
    'headers' => array('Header', 'Recommendation'),
  );

  /**
   * {@inheritdoc}
   */
  public function execute() {
    global $base_url;
    $home = url('<front>');
    $request = drupal_http_request("$base_url/$home", array('method' => 'HEAD'));

    if (!in_array('x-content-options', $request['headers'])) {
      $output[] = array('X-Content-Options', 'Should be set to nosniff to prevent clickjacking');
    }

    if (!in_array('x-frame-options', $request['headers'])) {
      $output[] = array('X-Frame-Options', 'Should be set to SAMEORIGIN to prevent clickjacking');
    }

    if (!in_array('x-xss-protection', $request['headers'])) {
      $output[] = array('X-XSS-Protection', 'Should be set to prevent clickjacking');
    }

    if (!in_array('content-security-policy', $request['headers'])) {
      $output[] = array('Content-Security-Policy', 'There is no content security policy in place');
    }

    return $output;
  }
}