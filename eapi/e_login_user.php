<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';
require_once dirname(__DIR__) . '/include/awraevent_password.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || $data['mobile'] == '' || $data['password'] == '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$mobile = strip_tags((string) $data['mobile']);
$passwordPlain = (string) $data['password'];
$mobileEsc = $event->real_escape_string($mobile);

$chek = $event->query("SELECT * FROM tbl_user WHERE mobile='" . $mobileEsc . "' AND status = 1 LIMIT 1");
$status = $event->query('SELECT 1 FROM tbl_user WHERE status = 1 LIMIT 1');

if ($status->num_rows == 0) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Your Status Deactivate!!!']);
  exit;
}

if ($chek->num_rows == 0) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Invalid Email/Mobile No or Password!!!']);
  exit;
}

$c = $chek->fetch_assoc();
$stored = (string) ($c['password'] ?? '');

if (!awraevent_password_verify($passwordPlain, $stored)) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Invalid Email/Mobile No or Password!!!']);
  exit;
}

if (!awraevent_password_is_modern_hash($stored)) {
  $nh = awraevent_password_hash($passwordPlain);
  $event->query("UPDATE tbl_user SET password='" . $event->real_escape_string($nh) . "' WHERE id=" . (int) $c['id']);
}

$c = $event->query('SELECT * FROM tbl_user WHERE id=' . (int) $c['id'] . ' LIMIT 1')->fetch_assoc();

echo json_encode([
  'UserLogin' => awraevent_user_for_api(is_array($c) ? $c : []),
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => 'Login successfully!',
], JSON_UNESCAPED_UNICODE);
