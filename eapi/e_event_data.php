<?php 
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';
require_once dirname( dirname(__FILE__) ).'/include/brand.php';
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
	$g = array();
	$s = array();
	
	$eventlist = $event->query("select * from tbl_event where status=1 and id=".$eid."");
$nav = array();
while($ev = $eventlist->fetch_assoc())
{
	$cover_img = array();
	$nav['event_id'] = $ev['id'];
	$nav['event_title'] = $ev['title'];
	$nav['event_img'] = awraevent_media_url($ev['img']);
	$cover_img[] = $ev['cover_img'];
	$check = $event->query("select * from tbl_cover where eid=".$eid." and status=1");
	while($co = $check->fetch_assoc())
	{
		array_push($cover_img,$co['img']);
	}
	
	$nav['event_cover_img'] = awraevent_media_urls_list($cover_img);
	$date=date_create($ev['sdate']);
	$nav['event_sdate'] = date_format($date,"d F, Y");
	$nav['event_time_day'] = date_format($date,"l").','.date("g:i A", strtotime($ev['stime'])).' TO '.date("g:i A", strtotime($ev['etime']));
	$nav['event_address_title'] = $ev['place_name'];
	$nav['event_address'] = $ev['address'];
	$nav['event_latitude'] = $ev['latitude'];
	$nav['event_longtitude'] = $ev['longtitude'];
	$nav['event_about'] = $ev['description'];
	$getprice = $event->query("select * from tbl_type_price where eid=".$eid." order by price limit 1")->fetch_assoc();
	$nav['ticket_price'] = $getprice['price'];
	$nav['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$ev['id']."")->num_rows;
	$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$ev['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if($getpic['pro_pic'] == '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$nav['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$ev['id']."")->fetch_assoc();
$nav['total_member_list'] = $ticket['books'];
	$v[] = $nav;
}

$gal = $event->query("select * from tbl_gallery where eid=".$eid." and status=1");
while($row = $gal->fetch_assoc())
{
	$g[] = awraevent_media_url($row['img']);
}

$spon = $event->query("select * from tbl_sponsore where eid=".$eid." and status=1");
$sponsore = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}



$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Event Data Get Successfully!","EventData"=>$v,"Event_gallery"=>$g,"Event_sponsore"=>$s);
}
echo json_encode($returnArr);