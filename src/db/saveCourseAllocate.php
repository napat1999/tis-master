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
$allocatelevel = isset($_POST["allocatelevel"])?$_POST["allocatelevel"]:'';
$allocatelevel+=1;
$EmployeeNoArray = isset($_POST["EmployeeNo"])?$_POST["EmployeeNo"]:'';
$thai_nameArray = isset($_POST["thai_name"])?$_POST["thai_name"]:'';
$positionArray = isset($_POST["position"])?$_POST["position"]:'';
$emailArray = isset($_POST["email"])?$_POST["email"]:'';
$assignQuotaArray = isset($_POST["allocateQuota"])?$_POST["allocateQuota"]:'';
//$assignLeft = isset($_POST["assignLeftHidden"])?$_POST["assignLeftHidden"]:'';

function fillArray($AssignerEmployeeNo,$array,$key) {
  $cnt=0;
  foreach ($array as $value) {
    $AssignerEmployeeNo[$cnt][$key]=$value;
    $cnt++;
  }
  return $AssignerEmployeeNo;
}
if ($EmployeeNoArray<>"") {
  $AssignerEmployeeNo=array();
  $AssignerEmployeeNo=fillArray($AssignerEmployeeNo,$EmployeeNoArray,'employeeno');
  $AssignerEmployeeNo=fillArray($AssignerEmployeeNo,$thai_nameArray,'thai_name');
  $AssignerEmployeeNo=fillArray($AssignerEmployeeNo,$positionArray,'position');
  $AssignerEmployeeNo=fillArray($AssignerEmployeeNo,$emailArray,'email');
  $AssignerEmployeeNo=fillArray($AssignerEmployeeNo,$assignQuotaArray,'quota');
} else {
  $error_arr=array('code'=>'999','message'=>'No selected Assigner');
  $response_json = json_encode($error_arr);
  echo $response_json;
  exit;
}

//Insert sub assigned quota
$insertAssigner=0;
$sumAssingerQuota=0;
$cntStudent=count($EmployeeNoArray);
for($i=0;$i<$cntStudent;$i++) {
  $sql="insert into courseallocate";
  $sql.="(courseid,thai_name,employeeno,position,email,allocatequota,allocateassign,allocateleft,allocateused,assignby,allocatelevel)";
  $sql.=" select ";
  $sql.=prepareString($courseID).",";
  $sql.=prepareString($AssignerEmployeeNo[$i]['thai_name']).",";
  $sql.=prepareString($AssignerEmployeeNo[$i]['employeeno']).",";
  $sql.=prepareString($AssignerEmployeeNo[$i]['position']).",";
  $sql.=prepareString($AssignerEmployeeNo[$i]['email']).",";
  $sql.=prepareNumber($AssignerEmployeeNo[$i]['quota']).",";
  $sql.="0,";
  $sql.=prepareNumber($AssignerEmployeeNo[$i]['quota']).",";
  $sql.="0,";
  $sql.=prepareString($_SESSION["employee_id"]).",";
  $sql.=prepareNumber($allocatelevel);
  $sql.=" where not exists (select * from courseallocate where ";
  $sql.="courseid=".prepareString($courseID);
  $sql.=" and employeeno=".prepareString($AssignerEmployeeNo[$i]['employeeno']).")";

  $resultArray=json_decode(pgExecute($sql),true);
  if($resultArray['code']=="200") {
    $insertAssigner++;
    $sumAssingerQuota=$sumAssingerQuota+prepareNumber($AssignerEmployeeNo[$i]['quota']);
  }
}

//Update left quota
$sql="update courseallocate set allocateleft=allocateleft-".$sumAssingerQuota.",allocateassign=allocateassign+".$sumAssingerQuota;
$sql.=" where employeeno='".$_SESSION["employee_id"]."' and courseid=".$courseID;
$result=json_decode(pgExecute($sql),true);

if($insertAssigner==$cntStudent) {
  $response_json=array('code'=>'200','message'=>'Complete Saved '.$sql);
  //$response_json=array('code'=>'200','message'=>'Complete Saved '.$cntStudent);
} else {
  //$response_json=array('code'=>'998','message'=>$sql);
  $response_json=array('code'=>'998','message'=>'มีบางรายการซ้ำกับในระบบ Save '.$insertAssigner.'/'.$cntStudent);
}
//No error return complete

$response_json = json_encode($response_json);
echo $response_json;
?>
