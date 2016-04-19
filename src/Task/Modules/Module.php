<?php
/**
 * @file
 * contains DrushAudit\Task\Modules\Module.
 */
namespace DrushAudit\Task\Modules;

abstract class Module {
  /**
   * Meta information about the module.
   */
  abstract public function info();

  /**
   * Access the modules metadata.
   *
   * @param string $name
   * @param string $default
   *
   * @return string|null
   */
  public final function getInfo($name, $default = NULL) {
    $info = $this->info();
    return isset($info[$name]) ? $info[$name] : $default;
  }

  /**
   * Returns the expected status of the module.
   * @return boolean
   */
  public function status() {
    return $this->getInfo('status', TRUE);
  }

  /**
   * Get the current status of the module.
   * @return bool
   */
  public function validateStatus() {
    return $this->status() === module_exists($this->getInfo('machine_name'));
  }

  /**
   * Get the expected module configuration.
   * @return array
   */
  public function config() {
    return $this->getInfo('configuration', array());
  }

  /**
   * Validate the configuration against what is set in the DB.
   * @return array
   */
  public function validateConfig() {
    $config = $this->config();
    $return = array();

    foreach ($config as $key => $value) {
      $stored_config = variable_get($key);
      if ($value !== $stored_config) {
        $return[$key] = array(
          'expected' => $value,
          'actual' => $stored_config,
        );
      }
    }

    return $return;
  }
}