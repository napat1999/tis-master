<?php
require_once('../lib/db.php');
//$statusID = isset($_GET["statusID"])?$_GET["statusID"]:'';
$tagID = isset($_POST["tagID"])?$_POST["tagID"]:'';
$reply="{ \"data\": [";
if($tagID=="") {
  $sql="select * from paramtag";
} else {
  $sql="select * from paramtag where tagid=".$tagID;
}
$sql.=" order by tagname";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
      $reply.='{';
      $reply.='"tagid":"'.$result[$i]['tagid'].'",';
      $reply.='"tagname":"'.$result[$i]['tagname'].'",';
      $reply.='"tagdescription":"'.$result[$i]['tagdescription'].'"';
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
