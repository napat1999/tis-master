<?php
require_once('../lib/db.php');
include_once("../lib/myLib.php");

$courseID = isset($_POST["courseID"])?$_POST["courseID"]:'';
$employeeNo = isset($_POST["employeeNo"])?$_POST["employeeNo"]:'';
$sql="select ct.*,tu.thai_name from coursestudent ct";
$sql.=" left join tisusers tu on ct.assignby=tu.employeeno";
$sql.=" where ct.courseid=".$courseID." and ct.employeeno='".$employeeNo."'";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  $employeeNo=$result[0]['employeeno'];
  if($employeeNo=="") { //Not found mean OK
    $response_json=array('code'=>"200",'message'=>"OK");
    $response_json = json_encode($response_json);
    echo $response_json;
  } else {
    $thai_name=strstr($result[0]['thai_name']," ",true);
    $status=$result[0]['status'];
    $studentremark=$result[0]['studentremark'];
    switch ($status) {
      case '0':
        $message="เลือกแล้วโดย ".$thai_name;
        break;
      case '1':
        $message="เลือกแล้วโดย ".$thai_name;
        break;
      case '2':
        $message=statusStudentText($status)." ".$studentremark;
        break;
      case '3':
        $message=statusStudentText($status)." ".$studentremark;
        break;
      default:
        // code...
        break;
    }
    $response_json=array('code'=>"200",'message'=>$message);
    $response_json = json_encode($response_json);
    echo $response_json;
  }
} else {
  $error_arr=array('200'=>"999",'message'=>'error');
  $error_json = json_encode($error_arr);
  echo $error_json;
}
?>
