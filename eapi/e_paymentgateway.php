<?php
require dirname(__DIR__) . '/include/eventconfig.php';
require_once dirname(__DIR__) . '/include/brand.php';

header('Content-Type: application/json; charset=utf-8');

$sql = 'SELECT * FROM tbl_payment_list WHERE status = 1';
$allow = function_exists('awraevent_payment_gateway_id_allowlist')
  ? awraevent_payment_gateway_id_allowlist()
  : null;
if (is_array($allow) && count($allow) > 0) {
  $ids = array_map('intval', $allow);
  $ids = array_filter($ids, static function ($id) {
    return $id > 0;
  });
  if (count($ids) > 0) {
    $sql .= ' AND id IN (' . implode(',', $ids) . ')';
  }
}

$sel = $event->query($sql);
$myarray = [];
while ($row = $sel->fetch_assoc()) {
  if (isset($row['img'])) {
    $row['img'] = awraevent_media_url((string) $row['img']);
  }
  $myarray[] = $row;
}

$returnArr = [
  'paymentdata' => $myarray,
  'ResponseCode' => '200',
  'Result' => 'true',
  'ResponseMsg' => 'Payment Gateway List Founded!',
];
echo json_encode($returnArr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
