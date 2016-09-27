<?php

/**
 * @file
 * Contains \DrushAudit\Output\OutputInterface.
 */

namespace DrushAudit\Output;

interface OutputInterface {

  /**
   * Render the result of an audit task.
   */
  public function render(array $result = array(), array $options = array());

}
