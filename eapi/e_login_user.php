<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';
require_once dirname(__DIR__) . '/include/awraevent_password.php';

header('Content-Type: application/json; charset=utf-8');

/**
 * Build possible mobile strings stored in tbl_user.mobile (signup often saves national digits only).
 */
function awraevent_login_mobile_candidates(array $data): array {
  $raw = trim((string) ($data['mobile'] ?? ''));
  $out = [];
  if ($raw !== '') {
    $out[] = $raw;
  }
  $digits = preg_replace('/\D+/', '', $raw);
  if ($digits !== '') {
    $out[] = $digits;
    if (str_starts_with($digits, '0')) {
      $out[] = ltrim($digits, '0');
    }
    if (str_starts_with($digits, '251') && strlen($digits) >= 12) {
      $nat = substr($digits, 3);
      $out[] = ltrim($nat, '0');
      $out[] = $nat;
    }
  }
  $ccode = isset($data['ccode']) ? trim((string) $data['ccode']) : '';
  if ($ccode !== '' && $digits !== '') {
    $ccDigits = preg_replace('/\D+/', '', $ccode);
    $national = $digits;
    if ($ccDigits !== '' && str_starts_with($digits, $ccDigits)) {
      $national = substr($digits, strlen($ccDigits));
    }
    $national = ltrim($national, '0');
    if ($national !== '') {
      $out[] = $national;
      $out[] = '+' . $ccDigits . $national;
      $out[] = $ccDigits . $national;
    }
  }
  $out = array_values(array_unique(array_filter($out, static function ($v) {
    return $v !== null && $v !== '';
  })));
  return $out;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || ($data['mobile'] ?? '') === '' || ($data['password'] ?? '') === '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$passwordPlain = (string) $data['password'];
$candidates = awraevent_login_mobile_candidates($data);
if ($candidates === []) {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

$inList = [];
foreach ($candidates as $c) {
  $inList[] = "'" . $event->real_escape_string($c) . "'";
}
$inSql = implode(',', $inList);

$chek = $event->query('SELECT * FROM tbl_user WHERE mobile IN (' . $inSql . ') AND status = 1 LIMIT 1');

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
