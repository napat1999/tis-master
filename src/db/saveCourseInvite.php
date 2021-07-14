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
$EmployeeNoArray = isset($_POST["EmployeeNo"])?$_POST["EmployeeNo"]:'';
$th_initialArray = isset($_POST["th_initial"])?$_POST["th_initial"]:'';
$thai_nameArray = isset($_POST["thai_name"])?$_POST["thai_name"]:'';
$companyArray = isset($_POST["company"])?$_POST["company"]:'';
$departmentArray = isset($_POST["department"])?$_POST["department"]:'';
$sectionArray = isset($_POST["section"])?$_POST["section"]:'';
$divisionArray = isset($_POST["division"])?$_POST["division"]:'';
$positionArray = isset($_POST["position"])?$_POST["position"]:'';
$emailArray = isset($_POST["email"])?$_POST["email"]:'';

function fillArray($invitedEmployee,$array,$key) {
  $cnt=0;
  foreach ($array as $value) {
    $invitedEmployee[$cnt][$key]=$value;
    $cnt++;
  }
  return $invitedEmployee;
}
if ($EmployeeNoArray<>"") {
  $invitedEmployee=array();
  $invitedEmployee=fillArray($invitedEmployee,$EmployeeNoArray,'employeeno');
  $invitedEmployee=fillArray($invitedEmployee,$th_initialArray,'th_initial');
  $invitedEmployee=fillArray($invitedEmployee,$thai_nameArray,'thai_name');
  $invitedEmployee=fillArray($invitedEmployee,$companyArray,'company');
  $invitedEmployee=fillArray($invitedEmployee,$departmentArray,'department');
  $invitedEmployee=fillArray($invitedEmployee,$sectionArray,'section');
  $invitedEmployee=fillArray($invitedEmployee,$divisionArray,'division');
  $invitedEmployee=fillArray($invitedEmployee,$positionArray,'position');
  $invitedEmployee=fillArray($invitedEmployee,$emailArray,'email');
} else {
  $error_arr=array('code'=>'999','message'=>'No selected student');
  $response_json = json_encode($error_arr);
  echo $response_json;
  exit;
}

//Check duplicate before inserted
$insertInvited=0;
//Insert sub assigned

$cntStudent=count($EmployeeNoArray);
for($i=0;$i<$cntStudent;$i++) {
  $sql="insert into coursestudent";
  $sql.="(courseid,employeeno,th_initial,thai_name,company,department,section,division,position,email,assignby)";
  $sql.=" select ";
  $sql.=prepareString($courseID).",";
  $sql.=prepareString($invitedEmployee[$i]['employeeno']).",";
  $sql.=prepareString($invitedEmployee[$i]['th_initial']).",";
  $sql.=prepareString($invitedEmployee[$i]['thai_name']).",";
  $sql.=prepareString($invitedEmployee[$i]['company']).",";
  $sql.=prepareString($invitedEmployee[$i]['department']).",";
  $sql.=prepareString($invitedEmployee[$i]['section']).",";
  $sql.=prepareString($invitedEmployee[$i]['division']).",";
  $sql.=prepareString($invitedEmployee[$i]['position']).",";
  $sql.=prepareString($invitedEmployee[$i]['email']).",";
  $sql.=prepareString($_SESSION["employee_id"]);
  $sql.=" where not exists (select * from coursestudent where ";
  $sql.="courseid=".prepareString($courseID);
  $sql.=" and employeeno=".prepareString($invitedEmployee[$i]['employeeno']).")";

  $resultArray=json_decode(pgExecute($sql),true);
  if($resultArray['code']=="200") {
    $insertInvited++;
    //Inset change status log
    $studentID=$resultArray['message'];
    $sql="insert into studentlog ";
    $sql.="(courseid,studentid,status,remark,updateby)";
    $sql.=" values(";
    $sql.=prepareNumber($courseID).",";
    $sql.=prepareNumber($studentID).",";
    $sql.=prepareNumber($status).",";
    $sql.=prepareString($remark).",";
    $sql.=prepareString($_SESSION["employee_id"]).")";
    $result=pgExecute($sql);
  }
}
//Update allocated
$sql="update courseallocate set allocateused=allocateused+".$insertInvited.",allocateleft=allocateleft-".$insertInvited;
$sql.=" where employeeno=".prepareString($_SESSION["employee_id"]);
$sql.=" and courseid=".prepareString($courseID);
$result=pgExecute($sql);

if($insertInvited==$cntStudent) {
  $response_json=array('code'=>'200','message'=>'Complete Saved '.$cntStudent);
} else {
  $response_json=array('code'=>'998','message'=>'มีบางรายการซ้ำกับในระบบ Save '.$insertInvited.'/'.$cntStudent);
}
//No error return complete

$response_json = json_encode($response_json);
echo $response_json;
?>
