<?php 
require 'eventconfig.php';
require 'eventmania.php';
if(isset($_POST['type']))
{
	if($_POST['type'] == 'login')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		 
		$h = new Eventmania();
	
	 $count = $h->eventlogin($username,$password,'admin');
 if($count != 0)
 {
	 $_SESSION['eventname'] = $username;
	 $returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Login Successfully!","message"=>"welcome admin!!","action"=>"dashboard.php");
	 
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"200","Result"=>"false","title"=>"Please Use Valid Data!!","message"=>"Invalid Data!!","action"=>"index.php");
	}
	
}
else if($_POST['type'] == 'add_code')
{
	$okey = $_POST['status'];
	$title = $event->real_escape_string($_POST['title']);

	
	$table="tbl_code";
  $field_values=array("ccode","status");
  $data_values=array("$title","$okey");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Country Code Add Successfully!!","message"=>"Country Code section!","action"=>"list_code.php");
}
}
else if($_POST['type'] == 'update_status')
{
	$id = $_POST['id'];
	$status = $_POST['status'];
	$coll_type = $_POST['coll_type'];
	
	if($coll_type == 'user')
	{
		$table="tbl_user";
  $field = "status=".$status."";
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData_single($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"User Status Change Successfully!!","message"=>"User section!","action"=>"userlist.php");
}
	}
	
	else 
	{
		$returnArr = array("ResponseCode"=>"200","Result"=>"false","title"=>"Option Not There!!","message"=>"Error!!","action"=>"dashboard.php");
	}
	
}
else if($_POST['type'] == 'add_fcat')
{
	$okey = $_POST['status'];
	$title = $event->real_escape_string($_POST['title']);

	
	$table="faq_cat";
  $field_values=array("title","status");
  $data_values=array("$title","$okey");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"FAQ Category Add Successfully!!","message"=>"FAQ Category section!","action"=>"list_fcat.php");
}
}
else if($_POST['type'] == 'add_category')
{
	$okey = $_POST['status'];
	$title = $event->real_escape_string($_POST['title']);
			$target_dir = dirname( dirname(__FILE__) )."/images/category/";
			$url = "images/category/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

$target_dirs = dirname( dirname(__FILE__) )."/images/category/";
			$urls = "images/category/";
			$temps = explode(".", $_FILES["cover_img"]["name"]);
$newfilenames = uniqid().round(microtime(true)) . '.' . end($temps);
$target_files = $target_dirs . basename($newfilenames);
$urls = $urls . basename($newfilenames);


	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_files);
	$table="tbl_cat";
  $field_values=array("img","status","title","cover_img");
  $data_values=array("$url","$okey","$title","$urls");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Category Add Successfully!!","message"=>"Category section!","action"=>"list_category.php");
}
}
else if($_POST['type'] == 'edit_category')
{
	$okey = $_POST['status'];
	$title = $event->real_escape_string($_POST['title']);
	$id = $_POST['id'];
			$target_dir = dirname( dirname(__FILE__) )."/images/category/";
			$url = "images/category/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

$target_dirs = dirname( dirname(__FILE__) )."/images/category/";
			$urls = "images/category/";
			$temps = explode(".", $_FILES["cover_img"]["name"]);
$newfilenames = uniqid().round(microtime(true)) . '.' . end($temps);
$target_files = $target_dirs . basename($newfilenames);
$urls = $urls . basename($newfilenames);

if($_FILES["cat_img"]["name"] != '' and $_FILES["cover_img"]["name"] == '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_cat";
  $field = array('status'=>$okey,'img'=>$url,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Category Update Successfully!!","message"=>"Category section!","action"=>"list_category.php");
}
}
else if($_FILES["cat_img"]["name"] == '' and $_FILES["cover_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_files);
	$table="tbl_cat";
  $field = array('status'=>$okey,'cover_img'=>$urls,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Category Update Successfully!!","message"=>"Category section!","action"=>"list_category.php");
}
}
else if($_FILES["cat_img"]["name"] != '' and $_FILES["cover_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_files);
	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_cat";
  $field = array('status'=>$okey,'cover_img'=>$urls,'img'=>$url,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Category Update Successfully!!","message"=>"Category section!","action"=>"list_category.php");
}
}
else 
{
	$table="tbl_cat";
  $field = array('status'=>$okey,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Category Update Successfully!!","message"=>"Category section!","action"=>"list_category.php");
}
}
}
else if($_POST['type'] == 'edit_code')
{
	$okey = $_POST['status'];
	$title = $event->real_escape_string($_POST['title']);
	$id = $_POST['id'];
	$table="tbl_code";
  $field = array('status'=>$okey,'ccode'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Country Code Update Successfully!!","message"=>"Country Code section!","action"=>"list_code.php");
}
}

else if($_POST['type'] == 'edit_fcat')
{
	$okey = $_POST['status'];
	$title = $event->real_escape_string($_POST['title']);
	$id = $_POST['id'];
	$table="faq_cat";
  $field = array('status'=>$okey,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"FAQ Category Update Successfully!!","message"=>"FAQ Category section!","action"=>"list_fcat.php");
}
}

else if($_POST['type'] == 'add_coupon')
{
	$ccode = $event->real_escape_string($_POST['ccode']);
							$cdate = $_POST['cdate'];
							$minamt = $_POST['minamt'];
							$ctitle = $event->real_escape_string($_POST['ctitle']);
							$subtitle = $event->real_escape_string($_POST['subtitle']);
							$cstatus = $_POST['cstatus'];
							$cvalue = $_POST['cvalue'];
							$cdesc = $event->real_escape_string($_POST['cdesc']);
			$target_dir = dirname( dirname(__FILE__) )."/images/coupon/";
			$url = "images/coupon/";
			$temp = explode(".", $_FILES["f_up"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

	move_uploaded_file($_FILES["f_up"]["tmp_name"], $target_file);
	$table="tbl_coupon";
  $field_values=array("c_img","c_desc","c_value","c_title","status","cdate","ctitle","min_amt","subtitle");
  $data_values=array("$url","$cdesc","$cvalue","$ccode","$cstatus","$cdate","$ctitle","$minamt","$subtitle");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Coupon Add Successfully!!","message"=>"Coupon section!","action"=>"list_ccode.php");
}
}
else if($_POST['type'] == 'edit_coupon')
{
$ccode = $event->real_escape_string($_POST['ccode']);
$id = $_POST['id'];
							$cdate = $_POST['cdate'];
							$minamt = $_POST['minamt'];
							$ctitle = $event->real_escape_string($_POST['ctitle']);
							$subtitle = $event->real_escape_string($_POST['subtitle']);
							$cstatus = $_POST['cstatus'];
							$cvalue = $_POST['cvalue'];
							$cdesc = $event->real_escape_string($_POST['cdesc']);
							$restid = implode(',',$_POST['restsearch']);
			$target_dir = dirname( dirname(__FILE__) )."/images/coupon/";
			$url = "images/coupon/";
			$temp = explode(".", $_FILES["f_up"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["f_up"]["name"] != '')
{

	move_uploaded_file($_FILES["f_up"]["tmp_name"], $target_file);
	$table="tbl_coupon";
  $field=array('c_img'=>$url,'c_desc'=>$cdesc,'c_value'=>$cvalue,'c_title'=>$ccode,'status'=>$cstatus,'cdate'=>$cdate,'ctitle'=>$ctitle,'min_amt'=>$minamt,'subtitle'=>$subtitle);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Coupon Update Successfully!!","message"=>"Coupon section!","action"=>"list_ccode.php");
}
}
else 
{
	$table="tbl_coupon";
  $field=array('c_desc'=>$cdesc,'c_value'=>$cvalue,'c_title'=>$ccode,'status'=>$cstatus,'cdate'=>$cdate,'ctitle'=>$ctitle,'min_amt'=>$minamt,'subtitle'=>$subtitle);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Coupon Update Successfully!!","message"=>"Coupon section!","action"=>"list_ccode.php");
}
}	
}
else if($_POST['type'] == 'add_page')
{
	$ctitle = $event->real_escape_string($_POST['ctitle']);
							$cstatus = $_POST['cstatus'];
							$cdesc = $event->real_escape_string($_POST['cdesc']);
  $table="tbl_page";
  
  $field_values=array("description","status","title");
  $data_values=array("$cdesc","$cstatus","$ctitle");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Page Add Successfully!!","message"=>"Page section!","action"=>"list_pages.php");
}
}
else if($_POST['type'] == 'edit_page')
{
	$id = $_POST['id'];
	$ctitle = $event->real_escape_string($_POST['ctitle']);
							$cstatus = $_POST['cstatus'];
							$cdesc = $event->real_escape_string($_POST['cdesc']);
	
		$table="tbl_page";
  $field=array('description'=>$cdesc,'status'=>$cstatus,'title'=>$ctitle);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Page Update Successfully!!","message"=>"Page section!","action"=>"list_pages.php");
}
}
else if($_POST['type'] == 'code_delete')
{
$id = $_POST['id'];

$table="tbl_code";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Country Code Delete Successfully!!","message"=>"Country Code section!","action"=>"list_code.php");
}
}
else if($_POST['type'] == 'coupon_delete')
{
$id = $_POST['id'];

$table="tbl_coupon";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Coupon Delete Successfully!!","message"=>"Coupon section!","action"=>"list_ccode.php");
}
}
else if($_POST['type'] == 'page_delete')
{
$id = $_POST['id'];

$table="tbl_page";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Page Delete Successfully!!","message"=>"Page  section!","action"=>"list_pages.php");
}
}
else if($_POST['type'] == 'faqc_delete')
{
$id = $_POST['id'];

$table="faq_cat";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);

$table="tbl_faq";
$where = "where fid=".$id."";
$h = new Eventmania();
 $h->eventDeleteData($where,$table);	
 
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"FAQ Category Delete Successfully!!","message"=>"FAQ Category  section!","action"=>"list_fcat.php");
}
}

else if($_POST['type'] == 'edit_payment')
{
	$dname = mysqli_real_escape_string($event,$_POST['cname']);
			$attributes = mysqli_real_escape_string($event,$_POST['p_attr']);
			$ptitle = mysqli_real_escape_string($event,$_POST['ptitle']);
			$okey = $_POST['status'];
			$id = $_POST['id'];
			$p_show = $_POST['p_show'];
			$target_dir = dirname( dirname(__FILE__) )."/images/payment/";
			$url = "images/payment/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["cat_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_payment_list";
  $field = array('title'=>$dname,'status'=>$okey,'img'=>$url,'attributes'=>$attributes,'subtitle'=>$ptitle,'p_show'=>$p_show);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Payment Gateway Update Successfully!!","message"=>"Payment Gateway section!","action"=>"payment-list.php");
}
}
else 
{
	$table="tbl_payment_list";
  $field = array('title'=>$dname,'status'=>$okey,'attributes'=>$attributes,'subtitle'=>$ptitle,'p_show'=>$p_show);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"payment Update Successfully!!","message"=>"payment section!","action"=>"payment-list.php");
}
}
}
else if($_POST['type'] == 'add_faq')
{
	$question = mysqli_real_escape_string($event,$_POST['question']);
			$answer = mysqli_real_escape_string($event,$_POST['answer']);
			$okey = $_POST['status'];
			$fid = $_POST['fid'];
			
				


  $table="tbl_faq";
  $field_values=array("question","answer","status","fid");
  $data_values=array("$question","$answer","$okey","$fid");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Faq Add Successfully!!","message"=>"Faq section!","action"=>"list_faq.php");
}
}
else if($_POST['type'] == 'edit_faq')
{
	$question = mysqli_real_escape_string($event,$_POST['question']);
			$answer = mysqli_real_escape_string($event,$_POST['answer']);
			$okey = $_POST['status'];
			$fid = $_POST['fid'];
	$id = $_POST['id'];
		
		$table="tbl_faq";
  $field = array('question'=>$question,'status'=>$okey,'answer'=>$answer,'fid'=>$fid);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Faq Update Successfully!!","message"=>"Faq section!","action"=>"list_faq.php");
}
}
else if($_POST['type'] == 'faq_delete')
{
$id = $_POST['id'];

$table="tbl_faq";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Faq Delete Successfully!!","message"=>"Faq section!","action"=>"list_faq.php");
}
}
else if($_POST['type'] == 'edit_setting')
{
$webname = mysqli_real_escape_string($event,$_POST['webname']);
			$timezone = $_POST['timezone'];
			$currency = $_POST['currency'];
			
			$id = $_POST['id'];
			$sms_type = $_POST['sms_type'];
			$auth_key = $_POST['auth_key'];
			$otp_id = $_POST['otp_id'];
			$acc_id = $_POST['acc_id'];
			$auth_token = $_POST['auth_token'];
			$twilio_number = $_POST['twilio_number'];
			$otp_auth = $_POST['otp_auth'];
			$one_key = $_POST['one_key'];
			
			$one_hash = $_POST['one_hash'];
			
			$scredit = $_POST['scredit'];
			$rcredit =$_POST['rcredit'];
			
			
			$target_dir = dirname( dirname(__FILE__) )."/images/website/";
			$url = "images/website/";
			$temp = explode(".", $_FILES["weblogo"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["weblogo"]["name"] != '')
{

	move_uploaded_file($_FILES["weblogo"]["tmp_name"], $target_file);
	$table="tbl_setting";
  $field = array('timezone'=>$timezone,'weblogo'=>$url,'webname'=>$webname,'currency'=>$currency,'one_key'=>$one_key,'one_hash'=>$one_hash,'scredit'=>$scredit,'rcredit'=>$rcredit,
					'otp_auth'=>$otp_auth,
					'twilio_number'=>$twilio_number,
					'auth_token'=>$auth_token,
					'acc_id'=>$acc_id,
					'otp_id'=>$otp_id,
					'auth_key'=>$auth_key,
					'sms_type'=>$sms_type);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Setting Update Successfully!!","message"=>"Setting section!","action"=>"setting.php");
}
}
else 
{
	$table="tbl_setting";
  $field = array('timezone'=>$timezone,'webname'=>$webname,'currency'=>$currency,'one_key'=>$one_key,'one_hash'=>$one_hash,'scredit'=>$scredit,'rcredit'=>$rcredit,
					'otp_auth'=>$otp_auth,
					'twilio_number'=>$twilio_number,
					'auth_token'=>$auth_token,
					'acc_id'=>$acc_id,
					'otp_id'=>$otp_id,
					'auth_key'=>$auth_key,
					'sms_type'=>$sms_type);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Setting Update Successfully!!","message"=>"Offer section!","action"=>"setting.php");
}
}	
}
else if($_POST['type'] == 'edit_profile')
{
	$dname = $_POST['email'];
			$dsname = $_POST['password'];
	$id = $_POST['id'];
	$table="admin";
  $field = array('username'=>$dname,'password'=>$dsname);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Profile Update Successfully!!","message"=>"Profile  section!","action"=>"profile.php");
}
}
else if($_POST['type'] == 'add_events')
{
	$title = $event->real_escape_string($_POST['title']);
	$address = $event->real_escape_string($_POST['address']);
	$description = $event->real_escape_string($_POST['cdesc']);
	$disclaimer = $event->real_escape_string($_POST['disclaimer']);
	$status = $_POST['status'];
	$place_name = $event->real_escape_string($_POST['pname']);
	$sdate = $_POST['sdate'];
	$stime = $_POST['stime'];
	$etime = $_POST['etime'];
	$cid = $_POST['cid'];
	$latitude = $_POST['latitude'];
	$longtitude = $_POST['longtitude'];
	
	$target_dir = dirname( dirname(__FILE__) )."/images/event/";
			$url = "images/event/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

$target_dirs = dirname( dirname(__FILE__) )."/images/event/";
			$urls = "images/event/";
			$temps = explode(".", $_FILES["cover_img"]["name"]);
$newfilenames = uniqid().round(microtime(true)) . '.' . end($temps);
$target_files = $target_dirs . basename($newfilenames);
$urls = $urls . basename($newfilenames);


	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_files);
	$table="tbl_event";
  $field_values=array("cid","title","img","cover_img","sdate","stime","etime","address","status","description","disclaimer","latitude","longtitude","place_name");
  $data_values=array("$cid","$title","$url","$urls","$sdate","$stime","$etime","$address","$status","$description","$disclaimer","$latitude","$longtitude","$place_name");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Event Add Successfully!!","message"=>"Event section!","action"=>"list_events.php");
}	
}

else if($_POST['type'] == 'edit_event')
{
	$title = $event->real_escape_string($_POST['title']);
	$id = $_POST['id'];
	$address = $event->real_escape_string($_POST['address']);
	$description = $event->real_escape_string($_POST['cdesc']);
	$disclaimer = $event->real_escape_string($_POST['disclaimer']);
	$status = $_POST['status'];
	$place_name = $event->real_escape_string($_POST['pname']);
	$sdate = $_POST['sdate'];
	$stime = $_POST['stime'];
	$etime = $_POST['etime'];
	$cid = $_POST['cid'];
	$latitude = $_POST['latitude'];
	$longtitude = $_POST['longtitude'];
	
	$target_dir = dirname( dirname(__FILE__) )."/images/event/";
			$url = "images/event/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

$target_dirs = dirname( dirname(__FILE__) )."/images/event/";
			$urls = "images/event/";
			$temps = explode(".", $_FILES["cover_img"]["name"]);
$newfilenames = uniqid().round(microtime(true)) . '.' . end($temps);
$target_files = $target_dirs . basename($newfilenames);
$urls = $urls . basename($newfilenames);

if($_FILES["cat_img"]["name"] != '' and $_FILES["cover_img"]["name"] == '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_event";
  $field = array('place_name'=>$place_name,'status'=>$status,'img'=>$url,'title'=>$title,'cid'=>$cid,'sdate'=>$sdate,'stime'=>$stime,'etime'=>$etime,'address'=>$address,'description'=>$description,'disclaimer'=>$disclaimer,'latitude'=>$latitude,'longtitude'=>$longtitude);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Event Update Successfully!!","message"=>"Event section!","action"=>"list_events.php");
}
}
else if($_FILES["cat_img"]["name"] == '' and $_FILES["cover_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_files);
	$table="tbl_event";
  $field = array('place_name'=>$place_name,'status'=>$status,'cover_img'=>$urls,'title'=>$title,'cid'=>$cid,'sdate'=>$sdate,'stime'=>$stime,'etime'=>$etime,'address'=>$address,'description'=>$description,'disclaimer'=>$disclaimer,'latitude'=>$latitude,'longtitude'=>$longtitude);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Event Update Successfully!!","message"=>"Event section!","action"=>"list_events.php");
}
}
else if($_FILES["cat_img"]["name"] != '' and $_FILES["cover_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_files);
	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_event";
  $field = array('place_name'=>$place_name,'status'=>$status,'cover_img'=>$urls,'img'=>$url,'title'=>$title,'cid'=>$cid,'sdate'=>$sdate,'stime'=>$stime,'etime'=>$etime,'address'=>$address,'description'=>$description,'disclaimer'=>$disclaimer,'latitude'=>$latitude,'longtitude'=>$longtitude);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Event Update Successfully!!","message"=>"Event section!","action"=>"list_events.php");
}
}
else 
{
	$table="tbl_event";
  $field = array('place_name'=>$place_name,'status'=>$status,'title'=>$title,'cid'=>$cid,'sdate'=>$sdate,'stime'=>$stime,'etime'=>$etime,'address'=>$address,'description'=>$description,'disclaimer'=>$disclaimer,'latitude'=>$latitude,'longtitude'=>$longtitude);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Event Update Successfully!!","message"=>"Event section!","action"=>"list_events.php");
}
}
}

else if($_POST['type'] == 'add_gallery')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
			$target_dir = dirname( dirname(__FILE__) )."/images/gallery/";
			$url = "images/gallery/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_gallery";
  $field_values=array("img","status","eid");
  $data_values=array("$url","$okey","$eid");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Gallery Add Successfully!!","message"=>"Gallery section!","action"=>"list_gallery.php");
}
}

else if($_POST['type'] == 'add_cover')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
			$target_dir = dirname( dirname(__FILE__) )."/images/cover/";
			$url = "images/cover/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_cover";
  $field_values=array("img","status","eid");
  $data_values=array("$url","$okey","$eid");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Cover Image Add Successfully!!","message"=>"Cover Image section!","action"=>"list_cover.php");
}
}

else if($_POST['type'] == 'add_sponsore')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
	$title = $event->real_escape_string($_POST['title']);
			$target_dir = dirname( dirname(__FILE__) )."/images/sponsore/";
			$url = "images/sponsore/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_sponsore";
  $field_values=array("img","status","eid","title");
  $data_values=array("$url","$okey","$eid","$title");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Sponsore Add Successfully!!","message"=>"Sponsore section!","action"=>"list_sponsore.php");
}
}

else if($_POST['type'] == 'add_type')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
	$etype = $event->real_escape_string($_POST['etype']);
	$price = $_POST['price'];
	$tlimit = $_POST['tlimit'];
	
	$table="tbl_type_price";
  $field_values=array("status","eid","type","price","tlimit");
  $data_values=array("$okey","$eid","$etype","$price","$tlimit");
  
$h = new Eventmania();
	  $check = $h->eventinsertdata($field_values,$data_values,$table);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Type & Price Add Successfully!!","message"=>"Type & Price section!","action"=>"list_type.php");
}

}

else if($_POST['type'] == 'edit_type')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
	$id = $_POST['id'];
	$etype = $event->real_escape_string($_POST['etype']);
	$price = $_POST['price'];
	$tlimit = $_POST['tlimit'];
	
	$table="tbl_type_price";
  $field = array('status'=>$okey,'price'=>$price,'eid'=>$eid,'tlimit'=>$tlimit,'type'=>$etype);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Type & Price Edit Successfully!!","message"=>"Type & Price section!","action"=>"list_type.php");
}


}

else if($_POST['type'] == 'edit_gallery')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
	$id = $_POST['id'];
			$target_dir = dirname( dirname(__FILE__) )."/images/gallery/";
			$url = "images/gallery/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["cat_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_gallery";
  $field = array('status'=>$okey,'img'=>$url,'eid'=>$eid);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Gallery Update Successfully!!","message"=>"Gallery section!","action"=>"list_gallery.php");
}
}
else 
{
	$table="tbl_gallery";
  $field = array('status'=>$okey,'eid'=>$eid);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Gallery Update Successfully!!","message"=>"Gallery section!","action"=>"list_gallery.php");
}
}
}

else if($_POST['type'] == 'edit_sponsore')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
	$id = $_POST['id'];
	$title = $event->real_escape_string($_POST['title']);
			$target_dir = dirname( dirname(__FILE__) )."/images/sponsore/";
			$url = "images/sponsore/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["cat_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_sponsore";
  $field = array('status'=>$okey,'img'=>$url,'eid'=>$eid,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Sponsore Update Successfully!!","message"=>"Sponsore section!","action"=>"list_sponsore.php");
}
}
else 
{
	$table="tbl_sponsore";
  $field = array('status'=>$okey,'eid'=>$eid,'title'=>$title);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Sponsore Update Successfully!!","message"=>"Sponsore section!","action"=>"list_sponsore.php");
}
}
}

else if($_POST['type'] == 'edit_cover')
{
	$okey = $_POST['status'];
	$eid = $_POST['eid'];
	$id = $_POST['id'];
			$target_dir = dirname( dirname(__FILE__) )."/images/cover/";
			$url = "images/cover/";
			$temp = explode(".", $_FILES["cat_img"]["name"]);
$newfilename = round(microtime(true)) . '.' . end($temp);
$target_file = $target_dir . basename($newfilename);
$url = $url . basename($newfilename);
if($_FILES["cat_img"]["name"] != '')
{

	move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
	$table="tbl_cover";
  $field = array('status'=>$okey,'img'=>$url,'eid'=>$eid);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
  
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Cover Image Update Successfully!!","message"=>"Cover Image section!","action"=>"list_cover.php");
}
}
else 
{
	$table="tbl_cover";
  $field = array('status'=>$okey,'eid'=>$eid);
  $where = "where id=".$id."";
$h = new Eventmania();
	  $check = $h->eventupdateData($field,$table,$where);
	  if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Cover Image Update Successfully!!","message"=>"Cover Image section!","action"=>"list_cover.php");
}
}
}

else if($_POST['type'] == 'gallery_delete')
{
$id = $_POST['id'];

$table="tbl_gallery";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Gallery Delete Successfully!!","message"=>"Gallery  section!","action"=>"list_gallery.php");
}
}

else if($_POST['type'] == 'cover_delete')
{
$id = $_POST['id'];

$table="tbl_cover";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Cover Image Delete Successfully!!","message"=>"Cover Image  section!","action"=>"list_gallery.php");
}
}

else if($_POST['type'] == 'sponsore_delete')
{
$id = $_POST['id'];

$table="tbl_sponsore";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Sponsore Delete Successfully!!","message"=>"Sponsore  section!","action"=>"list_sponsore.php");
}
}

else if($_POST['type'] == 'type_delete')
{
$id = $_POST['id'];

$table="tbl_type_price";
$where = "where id=".$id."";
$h = new Eventmania();
	$check = $h->eventDeleteData($where,$table);	
	if($check == 1)
{
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","title"=>"Type & Price Delete Successfully!!","message"=>"Type & Price  section!","action"=>"list_type.php");
}
}
	
}
echo json_encode($returnArr);
?>