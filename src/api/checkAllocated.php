<?php
require_once('../lib/db.php');
$courseID = isset($_POST["courseID"])?$_POST["courseID"]:'';
$employeeNo = isset($_POST["employeeNo"])?$_POST["employeeNo"]:'';
$sql="select ca.*,tu.thai_name from courseallocate ca";
$sql.=" left join tisusers tu on ca.assignby=tu.employeeno";
$sql.=" where ca.courseid=".$courseID." and ca.employeeno='".$employeeNo."'";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  $employeeNo=$result[0]['employeeno'];
  if($employeeNo=="") { //Not found mean OK
    $response_json=array('code'=>"200",'message'=>"OK");
    $response_json = json_encode($response_json);
    echo $response_json;
  } else {
    $thai_name=strstr($result[0]['thai_name']," ",true);
    if ($thai_name=="") {
      $message="ผู้จัดสรรหลัก";
    } else {
      $message="เลือกแล้วโดย ".$thai_name;
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
