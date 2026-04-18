<?php
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';
require_once dirname( dirname(__FILE__) ).'/include/brand.php';
header('Content-type: text/json; charset=utf-8');

/** Empty sponsor row when an event has no tbl_sponsore rows (avoids PHP 8+ undefined $s[0] breaking JSON for Flutter). */
function awraevent_eapi_empty_sponsore(): array {
	return array('sponsore_id' => '', 'sponsore_img' => '', 'sponsore_title' => '');
}

$data = json_decode(file_get_contents('php://input'), true);
$uid = is_array($data) && array_key_exists('uid', $data) ? (int) $data['uid'] : 0;
$lats = is_array($data) && isset($data['lats']) ? trim((string) $data['lats']) : '';
$longs = is_array($data) && isset($data['longs']) ? trim((string) $data['longs']) : '';
// Do not treat uid 0 as "empty" (0 == '' is true in PHP); only lat/long are required for listings.
if (!is_array($data) || $lats === '' || $longs === '') {
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{
	$v = array();
	$cp = array(); 
	$d = array();
	$pop = array();
	$sec = array();
	
	$cato = $event->query("select * from tbl_cat where status=1");
$cat = array();
while($row = $cato->fetch_assoc())
{
	$cat['id'] = $row['id'];
	$cat['title'] = $row['title'];
	$cat['cat_img'] = awraevent_media_url($row['img']);
	$cat['cover_img'] = awraevent_media_url($row['cover_img']);
    $cp[] = $cat;
}
$timestamp = date("Y-m-d");
$chtime = date("Y-m-d H:i:s");
$eventlist = $event->query("select * from tbl_event where status=1 and event_status='Pending' order by sdate");
$nav = array();
while($ev = $eventlist->fetch_assoc())
{
	if($ev['sdate'] <= $timestamp)
	{
if($ev['sdate'].' '.$ev['etime'] <= $chtime)
	{
		$event->query("update tbl_event set event_status='Completed' where id=".$ev['id']."");
		$event->query("update tbl_ticket set ticket_type='Completed' where eid=".$ev['id']." and ticket_type='Booked'");
	}
	else 
	{
		$event->query("update tbl_event set is_booked=1 where id=".$ev['id']."");
		$nav['event_id'] = $ev['id'];
	$nav['event_title'] = $ev['title'];
	$nav['event_img'] = awraevent_media_url($ev['cover_img']);
	$nav['is_booked'] = $ev['is_booked'];
	$date=date_create($ev['sdate']);
	$nav['event_sdate'] = date_format($date,"d F");
	$nav['event_address'] = $ev['address'];
	$nav['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$ev['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$ev['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$nav['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();
$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$ev['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$nav['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$ev['id']."")->fetch_assoc();
$nav['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
$v[] = $nav;
	}
	}
	else 
	{
		
	$nav['event_id'] = $ev['id'];
	$nav['event_title'] = $ev['title'];
	$nav['event_img'] = awraevent_media_url($ev['cover_img']);
	$nav['is_booked'] = $ev['is_booked'];
	$date=date_create($ev['sdate']);
	$nav['event_sdate'] = date_format($date,"d F");
	$nav['event_address'] = $ev['address'];
	$nav['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$ev['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$ev['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$nav['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();
$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$ev['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$nav['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$ev['id']."")->fetch_assoc();
$nav['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
$v[] = $nav;
	}
}


$eventlistc = $event->query("select * from tbl_event where status=1 order by id desc limit 5");
$navc = array();
while($evc = $eventlistc->fetch_assoc())
{
	if($evc['sdate'] <= $timestamp)
	{
		if($evc['sdate'].' '.$evc['etime'] <= $chtime)
	{
		$event->query("update tbl_event set event_status='Completed' where id=".$evc['id']."");
		$event->query("update tbl_ticket set ticket_type='Completed' where eid=".$evc['id']." and ticket_type='Booked'");
	}
	else 
	{
		$event->query("update tbl_event set is_booked=1 where id=".$evc['id']."");
		$navc['event_id'] = $evc['id'];
	$navc['event_title'] = $evc['title'];
	$navc['is_booked'] = $evc['is_booked'];
	$navc['event_img'] = awraevent_media_url($evc['cover_img']);
	$date=date_create($evc['sdate']);
	$navc['event_sdate'] = date_format($date,"d F");
	$navc['event_address'] = $evc['address'];
	$navc['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$evc['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$evc['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$navc['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();

$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$evc['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$navc['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$evc['id']."")->fetch_assoc();
$navc['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
	$sec[] = $navc;
	}
	}
	else 
	{
	$navc['event_id'] = $evc['id'];
	$navc['event_title'] = $evc['title'];
	$navc['is_booked'] = $evc['is_booked'];
	$navc['event_img'] = awraevent_media_url($evc['cover_img']);
	$date=date_create($evc['sdate']);
	$navc['event_sdate'] = date_format($date,"d F");
	$navc['event_address'] = $evc['address'];
	$navc['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$evc['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$evc['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$navc['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();

$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$evc['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$navc['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$evc['id']."")->fetch_assoc();
$navc['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
	$sec[] = $navc;
	}
}


$eventlists = $event->query("SELECT (((acos(sin((".$lats."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$lats."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longs."-`longtitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance,id,title,img,address,sdate,stime,is_booked,etime FROM tbl_event where status=1 order by distance");
$navs = array();
while($evs = $eventlists->fetch_assoc())
{
	if($evs['sdate'] <= $timestamp)
	{
		if($evs['sdate'].' '.$evs['etime'] <= $chtime)
	{
		$event->query("update tbl_event set event_status='Completed' where id=".$evs['id']."");
		$event->query("update tbl_ticket set ticket_type='Completed' where eid=".$evs['id']." and ticket_type='Booked'");
	}
	else 
	{
		$event->query("update tbl_event set is_booked=1 where id=".$evs['id']."");
		$navs['event_id'] = $evs['id'];
	$navs['event_title'] = $evs['title'];
	$navs['is_booked'] = $evs['is_booked'];
	$navs['event_img'] = awraevent_media_url($evs['img']);
	$date=date_create($evs['sdate']);
	$navs['event_sdate'] = strtoupper(date_format($date,"dS M - D - ").date("g:i A", strtotime($evs['stime'])));
	$navs['event_address'] = $evs['address'];
	$navs['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$evs['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$evs['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$navs['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();

$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$evs['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$navs['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$evs['id']."")->fetch_assoc();
$navs['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
	$d[] = $navs;
	}
	}
	else 
	{
		
	$navs['event_id'] = $evs['id'];
	$navs['event_title'] = $evs['title'];
	$navs['is_booked'] = $evs['is_booked'];
	$navs['event_img'] = awraevent_media_url($evs['img']);
	$date=date_create($evs['sdate']);
	$navs['event_sdate'] = strtoupper(date_format($date,"dS M - D - ").date("g:i A", strtotime($evs['stime'])));
	$navs['event_address'] = $evs['address'];
	$navs['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$evs['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$evs['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$navs['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();

$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$evs['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$navs['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$evs['id']."")->fetch_assoc();
$navs['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
	$d[] = $navs;
	}
}
$month = date("m");
$eve = $event->query("select * from tbl_event where status=1 and MONTH(sdate) = ".$month."");
$na = array();
while($e = $eve->fetch_assoc())
{
	if($e['sdate'] <= $timestamp)
	{
		if($e['sdate'].' '.$e['etime'] <= $chtime)
	{
		$event->query("update tbl_event set event_status='Completed' where id=".$e['id']."");
		$event->query("update tbl_ticket set ticket_type='Completed' where eid=".$e['id']." and ticket_type='Booked'");
	}
	else 
	{
		$event->query("update tbl_event set is_booked=1 where id=".$e['id']."");
		$na['event_id'] = $e['id'];
	$na['event_title'] = $e['title'];
	$na['is_booked'] = $e['is_booked'];
	$na['event_img'] = awraevent_media_url($e['img']);
	$date=date_create($e['sdate']);
	$na['event_sdate'] = date_format($date,"d F");
	$na['event_address'] = $e['address'];
	$na['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$e['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$e['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$na['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();

$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$e['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$na['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$e['id']."")->fetch_assoc();
$na['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
	$pop[] = $na;
	}
	}
	else 
	{
	$na['event_id'] = $e['id'];
	$na['event_title'] = $e['title'];
	$na['is_booked'] = $e['is_booked'];
	$na['event_img'] = awraevent_media_url($e['img']);
	$date=date_create($e['sdate']);
	$na['event_sdate'] = date_format($date,"d F");
	$na['event_address'] = $e['address'];
	$na['IS_BOOKMARK'] = $event->query("select * from tbl_fav where uid=".$uid." and eid=".$e['id']."")->num_rows;
	$spon = $event->query("select * from tbl_sponsore where eid=".$e['id']." and status=1");
$sponsore = array();
$s = array();
while($row = $spon->fetch_assoc())
{
	$sponsore['sponsore_id'] = $row['id'];
	$sponsore['sponsore_img'] = awraevent_media_url($row['img']);
	$sponsore['sponsore_title'] = $row['title'];
	$s[] = $sponsore;
}
$na['sponsore_list'] = isset($s[0]) ? $s[0] : awraevent_eapi_empty_sponsore();

$ulist = $event->query("SELECT uid,eid FROM `tbl_ticket` WHERE `eid` = ".$e['id']." GROUP BY uid");
$member = array();
while($rp = $ulist->fetch_assoc())
{
	$getpic = $event->query("select * from tbl_user where id=".$rp['uid']."")->fetch_assoc();
	if (!is_array($getpic) || ($getpic['pro_pic'] ?? '') === '')
	{
	}
	else 
	{
	$member[] = awraevent_media_url($getpic['pro_pic']);
	}
}
$na['member_list'] = $member;
$ticket = $event->query("SELECT sum(`ticket_book`) as books FROM `tbl_type_price` WHERE eid=".$e['id']."")->fetch_assoc();
$na['total_member_list'] = (int) (is_array($ticket) ? ($ticket['books'] ?? 0) : 0);
	$pop[] = $na;
	}
}

$tbwallet = $event->query("select * from tbl_user where id=".$uid."")->fetch_assoc();
if ($uid === 0 || !is_array($tbwallet)) {
	$wallet = 0;
} else {
	$wallet = (int) ($tbwallet['wallet'] ?? 0);
}

$pols = array();
	$main_data = $event->query("select * from tbl_setting")->fetch_assoc();
	$pols['id'] = $main_data['id'];
	$pols['currency'] = $main_data['currency'];
    $pols['one_key'] = $main_data['one_key'];
	$pols['one_hash'] = $main_data['one_hash'];
	$pols['scredit'] = $main_data['scredit'];
	$pols['rcredit'] = $main_data['rcredit'];
	
$kp = array('Catlist'=>$cp,"Main_Data"=>$pols,"trending_event"=>$sec,"wallet"=>$wallet,"upcoming_event"=>$v,"nearby_event"=>$d,'this_month_event'=>$pop);
	
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Home Data Get Successfully!","HomeData"=>$kp);


}
echo json_encode($returnArr);