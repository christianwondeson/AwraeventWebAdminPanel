<?php 
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';
header('Content-type: text/json');

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
$myarray = array();
while($row = $sel->fetch_assoc())
{
	$myarray[] = $row;
}
$returnArr = array("paymentdata"=>$myarray,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Payment Gateway List Founded!");
echo json_encode($returnArr);
?> 