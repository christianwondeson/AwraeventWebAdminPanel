<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/awraevent_mobile_eapi.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || trim((string) ($data['mobile'] ?? '')) === '') {
  echo json_encode(['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Something Went Wrong!']);
  exit;
}

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

$chek = $event->query('SELECT 1 FROM tbl_user WHERE mobile IN (' . $inSql . ') LIMIT 1')->num_rows;

if ($chek != 0) {
  $returnArr = ['ResponseCode' => '401', 'Result' => 'false', 'ResponseMsg' => 'Already Exist Mobile Number!'];
} else {
  $returnArr = ['ResponseCode' => '200', 'Result' => 'true', 'ResponseMsg' => 'New Number!'];
}

echo json_encode($returnArr, JSON_UNESCAPED_UNICODE);
