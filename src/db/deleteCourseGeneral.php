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
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
if ($callerFile=="courseGeneral.php") {
    //Valid
    $sql="delete from courseschedule where courseid=".$courseID;
    $result=pgExecute($sql);
    $sql="delete from courseallocate where courseid=".$courseID;
    $result=pgExecute($sql);
    $sql="delete from coursestudent where courseid=".$courseID;
    $result=pgExecute($sql);

    //Inset change status log
    $sql="insert into courselog ";
    $sql.="(courseid,status,remark,updateby)";
    $sql.=" values(";
    $sql.=prepareNumber($courseID).",-1,'Deleted',";
    $sql.=prepareString($_SESSION["employee_id"]).")";
    $result=pgExecute($sql);

    $sql="delete from course where courseid=".$courseID;
    $result=pgExecute($sql);
    //Ignore result, focus only main table
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
