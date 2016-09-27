<?php

/**
 * @file
 * Drush wrapper for site audit tasks.
 */

require_once __DIR__ . '/vendor/autoload.php';

// Controllers.
use DrushAudit\Controller\GovCMSController;
use DrushAudit\Controller\ModulesController;
use DrushAudit\Controller\PerformanceController;
use DrushAudit\Controller\SecurityController;
use DrushAudit\Controller\TaskController;

// Output.
use DrushAudit\Output\CliOutput;
use DrushAudit\Output\HtmlOutput;

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

  // Option to be included in all commands.
  $options = array(
    'options' => array(
      'output' => dt('The output format for the audit results.'),
      'o' => dt('The output format for the audit results.'),
      'output_dir' => dt('The output location for the audit results.'),
      'dir' => dt('The output location for the audit results.'),
      'file' => dt('The output file name.'),
      'f' => dt('The output file name.'),
    ),
  );

  $items['drush-audit'] = $options + array(
    'callback' => 'drush_audit',
    'description' => dt('Perform all audits'),
    'aliases' => array('audit'),
    'arguments' => array(
      'tasks' => dt('Audit tasks'),
    ),
    'options' => array(
      'output' => dt('The output format for the audit results.'),
    ),
    'examples' => array(
      'drush audit' => dt('Perform all audit tasks against the code base'),
      'drush audit Security,Cache,VersionCheck' => dt('Perform specific audit tasks against the code base'),
      'drush audit --output=html' => dt('Perform all audits and output as HTML'),
    )
  );

  $items['drush-audit-performance'] = $options + array(
    'callback' => 'drush_audit_performance',
    'description' => dt('Check performance settings'),
    'aliases' => array('audit-performance'),
    'examples' => array(
      'drush audit-performance' => dt('Check performance settings'),
      'drush audit-performance --output=html' => dt('Check performance settings and output as HTML'),

    ),
  );

  $items['drush-audit-theme'] = $options + array(
    'callback' => 'drush_audit_theme',
    'description' => dt('Audit themes'),
    'aliases' => array('audit-themes'),
    'arguments' => array(
      'theme' => dt('A path to a theme'),
    ),
    'examples' => array(
      'drush audit-themes' => dt('Audit all themes in the code base'),
      'drush audit-themes sites/all/themes/custom' => dt('Audit a specific theme'),
      'drush audit-themes --output=html' => dt('Audit all themes in the code base and output as HTML'),
    )
  );

  $items['drush-audit-security'] = $options + array(
    'callback' => 'drush_audit_security',
    'description' => dt('Audit security'),
    'aliases' => array('audit-security'),
    'examples' => array(
      'drush audit-security' => dt('Audit seucirty configurations for the site'),
      'drush audit-security --output=html' => dt('Audit security configurations and output as HTML'),
    ),
  );

  $items['drush-audit-modules'] = $options + array(
    'callback' => 'drush_audit_modules',
    'description' => dt('Audit modules'),
    'aliases' => array('audit-modules'),
    'examples' => array(
      'drush audit-modules' => dt('Audit modules configurations for the site'),
      'drush audit-modules --output=html' => dt('Audit modules configurations and output as HTML'),
    ),
  );

  $items['drush-audit-govcms'] = $options + array(
    'callback' => 'drush_audit_govcms',
    'description' => dt('Audit govCMS project'),
    'aliases' => array('audit-govcms'),
    'examples' => array(
      'drush audit-govcms' => dt('Perform tasks that should be completed prior to a govCMS site launch'),
      'drush audit-govcms --output=html' => dt('Perform tasks that should be completed prior to a govCMS site launch and output as HTML'),
    ),
  );

  return $items;
}

/**
 * [drush_audit_get_display description]
 * @param  [type] $output [description]
 * @return [type]         [description]
 */
function drush_audit_get_display() {
  $plugins = array(
    'cli' => '\DrushAudit\Output\CliOutput',
    'html' => '\DrushAudit\Output\HtmlOutput',
  );

  $display = drush_get_option(array('output', 'o'), 'cli');
  $plugin = isset($plugins[$display]) ? $plugins[$display] : $plugins['cli'];

  return new $plugin;
}

/**
 * [drush_audit_get_options description]
 * @return [type] [description]
 */
function drush_audit_get_options() {
  return array(
    'all' => TRUE,
    'up-to-date' => FALSE,
    'text_format' => FALSE,
    'output_dir' => drush_get_option(array('output_dir', 'dir')),
    'file' => drush_get_option(array('file', 'f')),
  );
}

/**
 * Perform all audit tasks against the site.
 */
function drush_audit($tasks = array()) {
  $task_list = array();
  $task_list = array_merge($task_list, SecurityController::getTasks());
  $task_list = array_merge($task_list, ModulesController::getTasks());

  $config = array('tasks' => $task_list);

  $controller = new TaskController(drush_audit_get_options(), drush_audit_get_display());
  $controller->iterate($config);
}

/**
 * Perform an audit on the theme.
 */
function drush_audit_theme() {}

function drush_audit_performance() {
  $controller = new PerformanceController(drush_audit_get_options(), drush_audit_get_display());
  $controller->all();
}

/**
 * Perform an audit on the security.
 */
function drush_audit_security() {
  $controller = new SecurityController(drush_audit_get_options(), drush_audit_get_display());
  $controller->all();
}

/**
 * Perform an audit against any modules.
 */
function drush_audit_modules() {
  $controller = new ModulesController(drush_audit_get_options(), drush_audit_get_display());
  $controller->all();
}

/**
 * Perform a govCMS prelaunch
 */
function drush_audit_govcms() {
  $controller = new GovCMSController(drush_audit_get_options(), drush_audit_get_display());
  $controller->all();
}
