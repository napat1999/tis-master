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
$allocateID = isset($_POST["allocateID"])?$_POST["allocateID"]:'';
$originalQuota = isset($_POST["originalQuota"])?$_POST["originalQuota"]:'';
$allocateQuota = isset($_POST["allocateQuota"])?$_POST["allocateQuota"]:'';
$remark = isset($_POST["remark"])?$_POST["remark"]:'';

//Protect from invalid call
$callerFile=strstr(basename($_SERVER['HTTP_REFERER']),"?",true);
if ($callerFile=="courseAllocate.php") {
    //left quota
    $diffQuota=prepareNumber($originalQuota-$allocateQuota);
    //Update Assigned user
    if($allocateQuota==0) {
      $sql="delete from courseallocate where allocateid=".$allocateID;
    } else {
      $sql="update courseallocate set allocatequota=".$allocateQuota;
      $sql.=",allocateleft=".$allocateQuota."-allocateused-allocateassign";
      $sql.=" where allocateid=".$allocateID;
    }

    // $error_arr=array('code'=>'403','message'=>$sql);
    // $response_json = json_encode($error_arr);
    // echo $response_json;
    // exit;
    $result=pgExecute($sql);
    //Update Assingner
    $sql="update courseallocate set ";
    if($diffQuota>=0) {
      //Decrease user quota, assigner increase
      $sql.="allocateleft=allocateleft+".$diffQuota;
      $sql.=",allocateassign=allocateassign-".$diffQuota;
    } else {
      $sql.="allocateleft=allocateleft".$diffQuota;
      $sql.=",allocateassign=allocateassign+".abs($diffQuota);
    }

    $sql.=" where employeeno='".$_SESSION["employee_id"]."' and courseid=".$courseID;
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
