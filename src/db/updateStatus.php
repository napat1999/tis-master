<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$courseID = isset($_POST["courseID"])?$_POST["courseID"]:'';
$status = isset($_POST["status"])?$_POST["status"]:'';
$remark = isset($_POST["remark"])?$_POST["remark"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
//Remove get parameter
$callerArray=explode("?",$callerFile);
$callerFile=$callerArray[0];
if ($callerFile=="courseGeneralEdit.php") {
    //Inset change status log
    $sql="insert into courselog ";
    $sql.="(courseid,status,remark,updateby)";
    $sql.=" values(";
    $sql.=prepareNumber($courseID).",";
    $sql.=prepareNumber($status).",";
    $sql.=prepareString($remark).",";
    $sql.=prepareString($_SESSION["employee_id"]).")";
    $result=pgExecute($sql);

    //Update status
    $sql="update course set status=".$status." where courseid=".$courseID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
