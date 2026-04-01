<?php 
require 'eventconfig.php';
require_once __DIR__ . '/brand.php';
awraevent_normalize_webname($set);
$GLOBALS['event'] = $event;
class Eventmania {
 

	function eventlogin($username,$password,$tblname) {
		
		$q = "select * from ".$tblname." where username='".$username."' and password='".$password."'";
	return $GLOBALS['event']->query($q)->num_rows;
	}
	
	function eventinsertdata($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['event']->query($sql);
  return $result;
  }
  
  

  
  
  
  function eventinsertdata_id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['event']->query($sql);
  return $GLOBALS['event']->insert_id;
  }
  
  function eventinsertdata_Api($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['event']->query($sql);
  return $result;
  }
  
  function eventinsertdata_Api_Id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$GLOBALS['event']->query($sql);
  return $GLOBALS['event']->insert_id;
  }
  
  function eventupdateData($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
			
           $cols[] = "$key = '$val'"; 
			
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$GLOBALS['event']->query($sql);
    return $result;
  }
  
  
 
  
   function eventupdateData_Api($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$GLOBALS['event']->query($sql);
    return $result;
  }
  
  
  
  
  function eventupdateData_single($field,$table,$where){
$query = "UPDATE $table SET $field";

$sql =  $query.' '.$where;
$result=$GLOBALS['event']->query($sql);
  return $result;
  }
  
  function eventDeleteData($where,$table){

    $sql = "Delete From $table $where";
    $result=$GLOBALS['event']->query($sql);
  return $result;
  }
  
  function eventDeleteData_Api($where,$table){

    $sql = "Delete From $table $where";
    $result=$GLOBALS['event']->query($sql);
  return $result;
  }
 
}
?>