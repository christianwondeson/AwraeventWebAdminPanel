<?php 
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'];
$eid = $data['eid'];
if($uid == '' or $eid == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{
	$v = array();
	$eventlist = $event->query("select * from tbl_event where status=1 and id=".$eid."");
$nav = array();
while($ev = $eventlist->fetch_assoc())
{
	$cover_img = array();
	$nav['event_id'] = $ev['id'];
	$nav['event_title'] = $ev['title'];
	$nav['event_img'] = $ev['img'];
	$tick = array();
	$ticket = array();
	$getprice = $event->query("select * from tbl_type_price where eid=".$eid."");
	while($row = $getprice->fetch_assoc())
	{
		$tick['typeid'] = $row['id'];
		$tick['ticket_type'] = $row['type'];
		$tick['ticket_price'] = $row['price'];
		
		$tick['ticket_limit'] = $row['tlimit'] - $row['ticket_book'];
		
			
		
		$ticket[] = $tick;
	}
	$nav['ticketlist'] = $ticket;
	$nav['event_disclaimer'] = $ev['disclaimer'];
	$nav['event_tax'] = $set['tax'];
	$v[] = $nav;
}




$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Event Data Get Successfully!","EventData"=>$v);
}
echo json_encode($returnArr);