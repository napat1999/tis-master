<?php
require_once('../lib/db.php');
//$statusID = isset($_GET["statusID"])?$_GET["statusID"]:'';
$codeID = isset($_POST["codeID"])?$_POST["codeID"]:'';
$reply="{ \"data\": [";
if($codeID=="") {
  $sql="select * from paramcode";
} else {
  $sql="select * from paramcode where codeid=".$codeID;
}
$sql.=" order by codename";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
      $reply.='{';
      $reply.='"codeid":"'.$result[$i]['codeid'].'",';
      $reply.='"codename":"'.$result[$i]['codename'].'",';
      $reply.='"codedescription":"'.$result[$i]['codedescription'].'"';
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
