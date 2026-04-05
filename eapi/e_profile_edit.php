<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require dirname(__DIR__) . '/include/eventmania.php';
require_once dirname(__DIR__) . '/include/brand.php';
require_once dirname(__DIR__) . '/include/awraevent_password.php';

header('Content-Type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || $data['name'] == '' || $data['password'] == '' || $data['uid'] == '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$fname = strip_tags((string) $data['name']);
$email = strip_tags((string) $data['email']);
$passwordHash = awraevent_password_hash((string) $data['password']);
$uid = (int) $data['uid'];

$checkimei = $event->query('SELECT 1 FROM tbl_user WHERE id=' . $uid . ' LIMIT 1')->num_rows;
if ($checkimei == 0) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'User Not Exist!!!!']);
  exit;
}

$h = new Eventmania();
$h->eventupdateData_Api(
  ['name' => $fname, 'password' => $passwordHash, 'email' => $email],
  'tbl_user',
  'WHERE id=' . $uid
);

$c = $event->query('SELECT * FROM tbl_user WHERE id=' . $uid . ' LIMIT 1')->fetch_assoc();
echo json_encode([
  'UserLogin' => awraevent_user_for_api(is_array($c) ? $c : []),
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => 'Profile Update successfully!',
], JSON_UNESCAPED_UNICODE);