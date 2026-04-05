<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require dirname(__DIR__) . '/include/eventmania.php';
require_once dirname(__DIR__) . '/include/awraevent_password.php';

header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went wrong  try again !']);
  exit;
}

$mobile = $data['mobile'] ?? '';
$password = $data['password'] ?? '';
if ($mobile === '' || $password === '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went wrong  try again !']);
  exit;
}

$mobileEsc = $event->real_escape_string(strip_tags((string) $mobile));
$hash = awraevent_password_hash((string) $password);

$counter = $event->query("SELECT 1 FROM tbl_user WHERE mobile='" . $mobileEsc . "' LIMIT 1");

if ($counter->num_rows != 0) {
  $h = new Eventmania();
  $check = $h->eventupdateData_Api(['password' => $hash], 'tbl_user', "WHERE mobile='" . $mobileEsc . "'");
  echo json_encode([
    'ResponseCode' => '200',
    'Result' => 'true',
    'ResponseMsg' => 'Password Changed Successfully!!!!!',
  ]);
} else {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'mobile Not Matched!!!!']);
}
