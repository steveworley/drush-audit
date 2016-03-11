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

  $items['drush-audit-module-version-check'] = array(
    'callback' => 'drush_audit_module_version_check',
    'description' => dt('Check enabled module versions'),
    'aliases' => array('audit-version'),
    'arguments' => array(
      'module' => dt('Version check a single module.'),
    ),
    'options' => array(
      'all' => dt('Version check all installed modules.'),
      'up-to-date' => dt('Display modules that are up-to-date.'),
    ),
    'examples' => array(
      'drush audit-version --all' => dt('Check all installed module versions'),
    ),
  );

  $items['drush-audit-cache'] = array(
    'callback' => 'drush_audit_cache',
    'description' => dt('Check cache settings'),
    'aliases' => array('audit-cache'),
    'examples' => array(
      'drush audit-cache' => dt('Check caching settings'),
    ),
  );

  $items['drush-audit-text-formats'] = array(
    'callback' => 'drush_audit_text_formats',
    'description' => dt('Audit any defined text formats'),
    'aliases' => array('audit-text-formats'),
    'arguments' => array(
      'format' => 'A known text format to audit',
    ),
    'examples' => array(
      'drush audit-text-formats text_plain' => dt('Audit the text_plain text format'),
      'drush audit-text-formats' => dt('Audit all text formats'),
    ),
  );

  $items['drush-audit-custom-module-check'] = array(
    'callback' => 'drush_audit_custom_modules',
    'description' => dt('Audit custom modules'),
    'aliases' => array('audit-custom-modules'),
    'examples' => array(
      'drush audit-modules' => dt('Audit all custom modules in the code base'),
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

  $items['drush-audit-incompatible'] = array(
    'callback' => 'drush_audit_incompatible',
    'description' => dt('Audit incompatible modules'),
    'aliases' => array('audit-incompatible'),
    'examples' => array(
      'drush audit-incompatible' => dt('Audit incompatible modules'),
    ),
  );

  return $items;
}

/**
 * Perform all audit tasks against the site.
 */
function drush_audit($tasks = array()) {
  require_once __DIR__ . '/vendor/autoload.php';

  $task_list = array(
    'DrushAudit\\Task\\Cache',
    'DrushAudit\\Task\\VersionCheck',
    'DrushAudit\\Task\\TextFormat',
    'DrushAudit\\Task\\CustomModule',
    'DrushAudit\\Task\\Security',
    'DrushAudit\\Task\\Modules',
  );

  if (!empty($tasks)) {
    $tasks = explode(',', $tasks);
    $task_list = array();

    foreach ($tasks as $task) {
      if (class_exists("DrushAudit\\Task\\$task")) {
        $task_list[] = "DrushAudit\\Task\\$task";
      }
    }
  }

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
 * Check a module version.
 */
function drush_audit_module_version_check($module = FALSE) {
  require_once __DIR__ . '/vendor/autoload.php';

  $config = array(
    'options' => array(
      'all' => drush_get_option('all', FALSE),
      'module' => $module,
      'up-to-date' => drush_get_option('up-to-date', FALSE),
    ),
    'tasks' => array(
      'DrushAudit\\Task\\VersionCheck',
    ),
  );

  \DrushAudit\Controller\TaskController::iterate($config);
}

/**
 * Check site cache settings.
 */
function drush_audit_cache() {
  require_once __DIR__ . '/vendor/autoload.php';

  $config = array(
    'tasks' => array('DrushAudit\Task\Cache'),
  );

  \DrushAudit\Controller\TaskController::iterate($config);
}

/**
 * Audit text formats.
 */
function drush_audit_text_formats($format = FALSE) {
  require_once __DIR__ . '/vendor/autoload.php';

  $config = array(
    'options' => array(
      'text_format' => $format,
    ),
    'tasks' => array(
      'DrushAudit\\Task\\TextFormat',
    ),
  );

  \DrushAudit\Controller\TaskController::iterate($config);
}

/**
 * Perform an audit against all custom modules in the code base.
 */
function drush_audit_custom_modules() {
  require_once __DIR__ . '/vendor/autoload.php';
  $config = array(
    'task' => array('DrushAudit\\Task\\CustomModule'),
  );
  \DrushAudit\Controller\TaskController::iterate($config);
}

/**
 * Perform an audit on the theme.
 */
function drush_audit_theme() {
  require_once __DIR__ . '/vendor/autoload.php';
  $config = array();
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
  $config = array('tasks' => array('DrushAudit\\Task\\Modules'));
  \DrushAudit\Controller\TaskController::iterate($config);
}

/**
 * Perform an incompatible module check.
 */
function drush_audit_incompatible() {
  require_once __DIR__ . '/vendor/autoload.php';
  $config = array('tasks' => array('DrushAudit\\Task\\IncompatibleModules'));
  \DrushAudit\Controller\TaskController::iterate($config);
}