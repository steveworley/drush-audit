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
  public function getStatus() {
    return array(
      'actual' => module_exists($this->getInfo('machine_name')) ? 'enabled' : 'disabled',
      'expected' => $this->getInfo('status') ? 'enabled' : 'disabled',
    );
  }

  /**
   * Validate the configuration against what is set in the DB.
   * @return array
   */
  public function getConfig() {
    $config = $this->getInfo('configuration', array());
    $return = array();

    foreach ($config as $key => $value) {
      $stored_config = variable_get($key);
      if ($value !== $stored_config) {
        $return[] = array(
          'setting' => $key,
          'expected' => $value,
          'actual' => $stored_config,
        );
      }
    }

    return $return;
  }
}