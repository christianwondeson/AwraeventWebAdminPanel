<?php
/**
 * User profile refresh (GoEvent / Awra Event Flutter).
 * Returns JSON so the app can parse wallet / referral fields; avoids nginx 404 HTML.
 */
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';

header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
$data = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
if (!is_array($data)) {
  $data = [];
}

$uid = $data['uid'] ?? null;
if ($uid === null || $uid === '' || $uid === 'null') {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Something Went Wrong!',
  ]);
  exit;
}

$uid = (int) $uid;
if ($uid < 1) {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Something Went Wrong!',
  ]);
  exit;
}

$stmt = $event->query('SELECT * FROM tbl_user WHERE id=' . $uid . ' LIMIT 1');
if (!$stmt || $stmt->num_rows === 0) {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'User not found!',
  ]);
  exit;
}

$row = $stmt->fetch_assoc();
if (!is_array($row) || (isset($row['status']) && (int) $row['status'] !== 1)) {
  echo json_encode([
    'ResponseCode' => '401',
    'Result' => 'false',
    'ResponseMsg' => 'Your Status Deactivate!!!',
  ]);
  exit;
}

echo json_encode([
  'UserLogin' => awraevent_user_for_api($row),
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => 'Data Get Successfully!',
], JSON_UNESCAPED_UNICODE);
