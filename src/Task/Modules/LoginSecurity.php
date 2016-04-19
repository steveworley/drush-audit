<?php
/**
 * @file
 * Contains DrushAudit\Task\Modules\LoginSecurity.
 */
namespace DrushAudit\Task\Modules;

class LoginSecurity extends Module {
  /**
   * {@inheritdoc}
   */
  public function info() {
    return array(
      'name' => 'Login Security',
      'machine_name' => 'login_security',
      'status' => TRUE,
      'configuration' => array(
        'login_security_track_time' => 24,
        'login_security_user_wrong_count' => 5,
        'login_security_host_wrong_count' => 0,
        'login_security_host_wrong_count_hard' => 0,
        'login_security_disable_core_login_error' => TRUE,
        'login_security_notice_attempts_available' => FALSE,
        'login_security_last_login_timestamp' => TRUE,
        'login_security_last_access_timestamp' => FALSE,
      )
    );
  }
}