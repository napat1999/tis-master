<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$userID = isset($_POST["userID"])?$_POST["userID"]:'';
$col = isset($_POST["col"])?$_POST["col"]:'';
$status = isset($_POST["status"])?$_POST["status"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
//Remove get parameter
$callerArray=explode("?",$callerFile);
$callerFile=$callerArray[0];
if ($callerFile=="users.php") {
    //Valid
    $sql="update tisusers set ".$col."='".$status."' where userid=".$userID;
    $result=pgExecute($sql);
    echo $result;

    // $error_arr=array('code'=>'403','message'=>$sql);
    // $response_json = json_encode($error_arr);
    // echo $response_json;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
