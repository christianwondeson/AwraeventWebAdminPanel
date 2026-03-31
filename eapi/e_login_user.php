<?php 
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';

$data = json_decode(file_get_contents('php://input'), true);
if($data['mobile'] == ''  or $data['password'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    $mobile = strip_tags(mysqli_real_escape_string($event,$data['mobile']));
    $password = strip_tags(mysqli_real_escape_string($event,$data['password']));
    
$chek = $event->query("select * from tbl_user where mobile='".$mobile."' and status = 1 and password='".$password."'");
$status = $event->query("select * from tbl_user where status = 1");
if($status->num_rows !=0)
{
if($chek->num_rows != 0)
{
    $c = $event->query("select * from tbl_user where mobile='".$mobile."'  and status = 1 and password='".$password."'");
    $c = $c->fetch_assoc();
	
    $returnArr = array("UserLogin"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Login successfully!");
}
else
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Invalid Email/Mobile No or Password!!!");
}
}
else  
{
	 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Your Status Deactivate!!!");
}
}

echo json_encode($returnArr);