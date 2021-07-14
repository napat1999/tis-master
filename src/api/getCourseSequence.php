<?php
require_once('../lib/db.php');
//$statusID = isset($_GET["statusID"])?$_GET["statusID"]:'';
$codeID = isset($_POST["codeID"])?$_POST["codeID"]:'';
$reply="{ \"data\": [";
if($codeID=="") {
  $reply.="] }";
  echo $reply;
  exit;
} else {
  $sql="select courselevel,coursenumber,coursesequence,nameofficial from coursemaster where coursecodeid=".$codeID;
}
$sql.=" order by courselevel,coursenumber";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
      $reply.='{';
      $reply.='"courselevel":"'.str_pad($result[$i]['courselevel'],2,'0',STR_PAD_LEFT).'",';
      $reply.='"coursenumber":"'.str_pad($result[$i]['coursenumber'],3,'0',STR_PAD_LEFT).'",';
      $reply.='"coursesequence":"'.str_pad($result[$i]['coursesequence'],1,'0',STR_PAD_LEFT).'",';
      $reply.='"nameofficial":"'.$result[$i]['nameofficial'].'"';
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
