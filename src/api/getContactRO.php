<?php
require_once('../lib/db.php');
//$statusID = isset($_GET["statusID"])?$_GET["statusID"]:'';
$contactid = isset($_POST["contactID"])?$_POST["contactID"]:'';
$reply="{ \"data\": [";
if($contactid=="") {
  $sql="select * from contactro";
} else {
  $sql="select * from contactro where contactid=".$contactid;
}
$sql.=" order by contactro,employeeno";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
      $reply.='{';
      $reply.='"contactid":"'.$result[$i]['contactid'].'",';
      $reply.='"th_initial":"'.$result[$i]['th_initial'].'",';
      $reply.='"thai_name":"'.$result[$i]['thai_name'].'",';
      $reply.='"employeeno":"'.$result[$i]['employeeno'].'",';
      $reply.='"position":"'.$result[$i]['position'].'",';
      $reply.='"workplace":"'.$result[$i]['workplace'].'",';
      $reply.='"contactro":"'.$result[$i]['contactro'].'",';
      $reply.='"telephone":"'.$result[$i]['telephone'].'",';
      $reply.='"email":"'.$result[$i]['email'].'",';
      $reply.='"contactRemark":"'.$result[$i]['contactremark'].'"';
      $reply.='},';
  }
  //Trim end$reply=
  $reply = rtrim($reply, ",");
} else {
  $reply.="error";
}
$reply.="] }";
echo $reply;
?>
