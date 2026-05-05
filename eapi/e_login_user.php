<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';
require_once dirname(__DIR__) . '/include/awraevent_password.php';
require_once dirname(__DIR__) . '/include/awraevent_mobile_eapi.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || trim((string) ($data['mobile'] ?? '')) === '' || ($data['password'] ?? '') === '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$passwordPlain = trim((string) $data['password']);
$candidates = awraevent_eapi_mobile_candidates($data);
if ($candidates === []) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$inList = [];
foreach ($candidates as $c) {
  $inList[] = "'" . $event->real_escape_string($c) . "'";
}
$inSql = implode(',', $inList);

$chek = $event->query('SELECT * FROM tbl_user WHERE mobile IN (' . $inSql . ') LIMIT 1');

if ($chek->num_rows == 0) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Invalid mobile number or password.']);
  exit;
}

$c = $chek->fetch_assoc();
$stored = (string) ($c['password'] ?? '');

if (!awraevent_password_verify($passwordPlain, $stored)) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Invalid mobile number or password.']);
  exit;
}

if ((int) ($c['status'] ?? 0) !== 1) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Your account has been deactivated.']);
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
