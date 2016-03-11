<?php

/**
 * @file
 * Drush wrapper for site audit tasks.
 */

/**
 * Implements hook_drush_help().
 */
function drush_audit_drush_help($section) {
  switch($section) {
    case 'meta:drush_audit:title':
      return dt('Drupal Site Audit');
  }
}

/**
 * Implements hook_drush_command().
 */
function drush_audit_drush_command() {

  $items['drush-audit'] = array(
    'callback' => 'drush_audit',
    'description' => dt('Perform all audits'),
    'aliases' => array('audit'),
    'arguments' => array(
      'tasks' => dt('Audit tasks'),
    ),
    'examples' => array(
      'drush audit' => dt('Perform all audit tasks against the code base'),
      'drush audit Security,Cache,VersionCheck' => dt('Perform specific audit tasks against the code base'),
    )
  );

  $items['drush-audit-performance'] = array(
    'callback' => 'drush_audit_performance',
    'description' => dt('Check performance settings'),
    'aliases' => array('audit-performance'),
    'examples' => array(
      'drush audit-performance' => dt('Check performance settings'),
    ),
  );

  $items['drush-audit-theme'] = array(
    'callback' => 'drush_audit_theme',
    'description' => dt('Audit themes'),
    'aliases' => array('audit-themes'),
    'arguments' => array(
      'theme' => dt('A path to a theme'),
    ),
    'examples' => array(
      'drush audit-themes' => dt('Audit all themes in the code base'),
      'drush audit-themes sites/all/themes/custom' => dt('Audit a specific theme'),
    )
  );

  $items['drush-audit-security'] = array(
    'callback' => 'drush_audit_security',
    'description' => dt('Audit security'),
    'aliases' => array('audit-security'),
    'examples' => array(
      'drush audit-security' => dt('Audit seucirty configurations for the site'),
    ),
  );

  $items['drush-audit-modules'] = array(
    'callback' => 'drush_audit_modules',
    'description' => dt('Audit modules'),
    'aliases' => array('audit-modules'),
    'examples' => array(
      'drush audit-modules' => dt('Audit modules configurations for the site'),
    ),
  );

  return $items;
}

/**
 * Perform all audit tasks against the site.
 */
function drush_audit($tasks = array()) {
  require_once __DIR__ . '/vendor/autoload.php';

  $task_list = array();
  $task_list = array_merge($task_list, \DrushAudit\Controller\SecurityController::getTasks());
  $task_list = array_merge($task_list, \DrushAudit\Controller\ModulesController::getTasks());

  $config = array(
    'options' => array(
      'all' => TRUE,
      'up-to-date' => FALSE,
      'text_format' => FALSE,
    ),
    'tasks' => $task_list,
  );

  \DrushAudit\Controller\TaskController::iterate($config);
}

/**
 * Perform an audit on the theme.
 */
function drush_audit_theme() {
  require_once __DIR__ . '/vendor/autoload.php';
}

function drush_audit_performance() {
  require_once __DIR__ . '/vendor/autoload.php';
  DrushAudit\Controller\PerformanceController::all();
}

/**
 * Perform an audit on the security.
 */
function drush_audit_security() {
  require_once __DIR__ . '/vendor/autoload.php';
  DrushAudit\Controller\SecurityController::all();
}

/**
 * Perform an audit against any modules.
 */
function drush_audit_modules() {
  require_once __DIR__ . '/vendor/autoload.php';
  \DrushAudit\Controller\ModulesController::all();
}
