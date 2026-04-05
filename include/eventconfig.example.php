<?php
/**
 * Copy to eventconfig.php and adjust credentials (eventconfig.php is gitignored).
 */
$__awraevent_runtime = __DIR__ . '/awraevent_runtime.php';
if (is_readable($__awraevent_runtime)) {
  require $__awraevent_runtime;
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!extension_loaded('mysqli')) {
  die(
    'PHP mysqli extension is not enabled. On Ubuntu with PHP 8.2: ' .
    'sudo apt install php8.2-mysql -y && sudo systemctl restart php8.2-fpm'
  );
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
  $event = new mysqli(
    getenv('DB_HOST') ?: '127.0.0.1',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: 'root',
    getenv('DB_NAME') ?: 'goevent_db',
    (int) (getenv('DB_PORT') ?: 3308)
  );
  $event->set_charset("utf8mb4");
} catch (Exception $e) {
  die("Database connection failed. Please configure your DB credentials.");
}

require_once __DIR__ . '/awraevent_defaults.php';
require_once __DIR__ . '/brand.php';
require_once __DIR__ . '/awraevent_password.php';

$set = $event->query("SELECT * FROM `tbl_setting` LIMIT 1")->fetch_assoc();
if (!is_array($set)) {
  die('Database misconfigured: tbl_setting must have at least one row.');
}
awraevent_apply_setting_defaults($set);
$set['timezone'] = awraevent_sanitize_timezone((string) $set['timezone']);
if (!@date_default_timezone_set($set['timezone'])) {
  date_default_timezone_set(awraevent_default_timezone());
}

$validate = $event->query("SELECT * FROM `tbl_validate`")->fetch_assoc();
