<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
  $event = new mysqli(
    getenv('DB_HOST') ?: '127.0.0.1',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: 'CHANGE_ME',
    getenv('DB_NAME') ?: 'goevent_db',
    (int) (getenv('DB_PORT') ?: 3306)
  );
  $event->set_charset("utf8mb4");
} catch (Exception $e) {
  die("Database connection failed. Please configure your DB credentials.");
}

$set = $event->query("SELECT * FROM `tbl_setting`")->fetch_assoc();
date_default_timezone_set($set['timezone']);

$validate = $event->query("SELECT * FROM `tbl_validate`")->fetch_assoc();

