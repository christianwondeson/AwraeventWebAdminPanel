<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require dirname(__DIR__) . '/include/eventmania.php';
require_once dirname(__DIR__) . '/include/brand.php';
require_once dirname(__DIR__) . '/include/awraevent_password.php';
require_once dirname(__DIR__) . '/include/awraevent_mobile_eapi.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

function awraevent_generate_user_code(): int {
  global $event;
  $six_digit_random_number = mt_rand(100000, 999999);
  $c_refer = $event->query('SELECT 1 FROM tbl_user WHERE code=' . (int) $six_digit_random_number . ' LIMIT 1')->num_rows;
  if ($c_refer != 0) {
    return awraevent_generate_user_code();
  }
  return $six_digit_random_number;
}

if ($data['name'] == '' || $data['mobile'] == '' || $data['password'] == '' || $data['ccode'] == '' || $data['email'] == '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$name = strip_tags((string) $data['name']);
$email = strip_tags((string) $data['email']);
$ccode = strip_tags((string) $data['ccode']);
$passwordHash = awraevent_password_hash(trim((string) $data['password']));
$refercode = isset($data['refercode']) ? strip_tags((string) $data['refercode']) : '';

$mobile = awraevent_eapi_mobile_canonical_storage($data);
$candidates = awraevent_eapi_mobile_candidates($data);
if ($mobile === '' || $candidates === []) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}
$inList = [];
foreach ($candidates as $c) {
  $inList[] = "'" . $event->real_escape_string($c) . "'";
}
$inSql = implode(',', $inList);

$checkmob = $event->query('SELECT 1 FROM tbl_user WHERE mobile IN (' . $inSql . ') LIMIT 1');

if ($checkmob->num_rows != 0) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Mobile Number Already Used!']);
  exit;
}

$h = new Eventmania();

if ($refercode != '') {
  $c_refer = $event->query('SELECT 1 FROM tbl_user WHERE code=' . (int) $refercode . ' LIMIT 1')->num_rows;
  if ($c_refer == 0) {
    echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Refer Code Not Found Please Try Again!!']);
    exit;
  }

  $timestamp = date('Y-m-d H:i:s');
  $prentcode = awraevent_generate_user_code();
  $wallet = $event->query('SELECT * FROM tbl_setting LIMIT 1')->fetch_assoc();
  $fin = (int) ($wallet['scredit'] ?? 0);

  $field_values = ['name', 'email', 'mobile', 'rdate', 'password', 'ccode', 'refercode', 'wallet', 'code', 'status'];
  $data_values = [$name, $email, $mobile, $timestamp, $passwordHash, $ccode, (string) (int) $refercode, (string) $fin, (string) $prentcode, '1'];

  $newId = $h->eventinsertdata_Api_Id($field_values, $data_values, 'tbl_user');

  $h->eventinsertdata_Api(
    ['uid', 'message', 'status', 'amt', 'tdate'],
    [(string) $newId, 'Sign up Credit Added!!', 'Credit', (string) $fin, $timestamp],
    'wallet_report'
  );

  $c = $event->query('SELECT * FROM tbl_user WHERE id=' . (int) $newId . ' LIMIT 1')->fetch_assoc();
  echo json_encode([
    'UserLogin' => awraevent_user_for_api(is_array($c) ? $c : []),
    'ResponseCode' => '200',
    'Result' => 'true',
    'ResponseMsg' => 'Sign Up Done Successfully!',
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

$timestamp = date('Y-m-d H:i:s');
$prentcode = awraevent_generate_user_code();
$field_values = ['name', 'email', 'mobile', 'rdate', 'password', 'ccode', 'code', 'status'];
$data_values = [$name, $email, $mobile, $timestamp, $passwordHash, $ccode, (string) $prentcode, '1'];

$newId = $h->eventinsertdata_Api_Id($field_values, $data_values, 'tbl_user');
$c = $event->query('SELECT * FROM tbl_user WHERE id=' . (int) $newId . ' LIMIT 1')->fetch_assoc();

echo json_encode([
  'UserLogin' => awraevent_user_for_api(is_array($c) ? $c : []),
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => 'Sign Up Done Successfully!',
], JSON_UNESCAPED_UNICODE);
