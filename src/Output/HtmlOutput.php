<?php

/**
 * @file
 * Contains DrushAudit\Output\HtmlOutput.
 */

namespace DrushAudit\Output;

use Twig\Twig;

class HtmlOutput implements OutputInterface {

  /**
   * {@inheritdoc}
   */
  public function render(array $results = array(), array $options = array()) {
    $output_dir = isset($options['output_dir']) ? $options['output_dir'] : dirname(dirname(__DIR__));
    $file = isset($options['file']) ? $options['file'] : 'drush-audit.html';

    $loader = new \Twig_Loader_Filesystem(dirname(__FILE__) . '/Templates');
    $twig = new \Twig_Environment($loader, array(
      'cache' => FALSE,
    ));

    $markup = $twig->render('output.twig', array('results' => $results));
    drush_log("\n> Writing results to {$output_dir}/drush-audit.html...\n", 'ok');
    file_put_contents("$output_dir/$file", $markup);
  }

}
