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
$approxstudent = isset($_POST["approxstudent"])?$_POST["approxstudent"]:'';
$dateApplyBegin = isset($_POST["dateApplyBegin"])?($_POST["dateApplyBegin"]):'';
$dateApplyEnd = isset($_POST["dateApplyEnd"])?($_POST["dateApplyEnd"]):'';

//$error_arr=array('code'=>'999','message'=>$dateBegin.'-'.$dateEnd);
//$response_json = json_encode($error_arr);
//echo $response_json;
//exit;
if($courseID=="") { //Insert mode
    $error_arr=array('code'=>'999','message'=>'เข้าถึงผิดพลาด');
    $response_json = json_encode($error_arr);
    echo $response_json;
} else { //Update mode
    $sql="update course set ";
    $sql.="dateApplyBegin=".prepareDate($dateApplyBegin,'d/m/Y').",";
    $sql.="dateApplyEnd=".prepareDate($dateApplyEnd,'d/m/Y').",";
    $sql.="updateby=".prepareString($_SESSION["employee_id"]).",";
    $sql.="lastupdate=current_timestamp ";
    $sql.="where courseid=".$courseID."";
    $result=pgExecute($sql);
    echo $result;
}
?>
