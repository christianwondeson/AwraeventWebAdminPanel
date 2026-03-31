<?php 
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';

header('Content-type: text/json');
$sel = $event->query("select * from tbl_code");
$myarray = array();
while($row = $sel->fetch_assoc())
{
			$myarray[] = $row;
}
$returnArr = array("CountryCode"=>$myarray,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Country Code List Founded!");
echo json_encode($returnArr);
?>