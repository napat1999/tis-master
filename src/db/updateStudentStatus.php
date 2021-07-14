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
$studentID = isset($_POST["studentID"])?$_POST["studentID"]:'';
$personal_id = isset($_POST["personal_id"])?$_POST["personal_id"]:'';
$status = isset($_POST["status"])?$_POST["status"]:'';
$remark = isset($_POST["remark"])?$_POST["remark"]:'';
//Protect from invalid call
$callerFile=strstr(basename($_SERVER['HTTP_REFERER']),"?",true);
if ($callerFile=="courseInvite.php" || $callerFile=="personalAccepted.php" || $callerFile=="courseStudent.php") {
    //Inset change status log
    $sql="insert into studentlog ";
    $sql.="(courseid,studentid,status,remark,updateby)";
    $sql.=" values(";
    $sql.=prepareNumber($courseID).",";
    $sql.=prepareNumber($studentID).",";
    $sql.=prepareNumber($status).",";
    $sql.=prepareString($remark).",";
    $sql.=prepareString($_SESSION["employee_id"]).")";
    $result=pgExecute($sql);
    //Valid
    $sqlremark="";
    if($remark!="") {
      $sqlremark=",studentremark='".$remark."'";
    }
    $sqlpersonalid="";
    if($personal_id!="") {
      $sqlpersonalid=",personal_id='".$personal_id."'";
    }

    //Update student status
    $sql="update coursestudent set status=".$status.$sqlremark.$sqlpersonalid;
    if($studentID=="0") {//delete all
      $sql.=" where assignby='".$_SESSION["employee_id"]."'";
      $sql.=" and courseid=".$courseID;
    } else {
      $sql.=" where studentid=".$studentID;
    }
    $result=pgExecute($sql);

    //Update allocate data
    if($status==3 or $status==4) {
      //increase left, decrease used
      if($studentID=="0") {
        //delete all
        $sql="update courseallocate set allocateused=0,allocateleft=allocatequota-allocateassign";
        $sql.=" where employeeno='".$_SESSION["employee_id"]."'";
        $sql.=" and courseid=".$courseID;
      } else {
        $sql="update courseallocate set allocateused=allocateused-1,allocateleft=allocateleft+1";
        $sql.=" where employeeno='".$_SESSION["employee_id"]."'";
        $sql.=" and courseid=".$courseID;
      }
    } else {
      //decrease left, increase used
      $sql="update courseallocate set allocateused=allocateused+1,allocateleft=allocateleft-1";
      $sql.=" where employeeno='".$_SESSION["employee_id"]."'";
      $sql.=" and courseid=".$courseID;
    }
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
