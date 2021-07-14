<?php
require_once('../lib/db.php');
//$statusID = isset($_GET["statusID"])?$_GET["statusID"]:'';
$courseMasterID = isset($_POST["courseMasterID"])?$_POST["courseMasterID"]:'';
//$courseMasterID=1;

$reply="{ \"data\": [";
if($courseMasterID=="") {
  $reply.="] }";
  echo $reply;
  exit;
} else {
  $sql="select * from coursemaster where courseid=".$courseMasterID;
}
$sql.=" order by courselevel,coursenumber";

$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
      $content=$result[$i]['content'];
      $content=str_replace("\r\n","",$content);
      $reply.='{';
      $reply.='"nameofficial":"'.$result[$i]['nameofficial'].'",';
      $reply.='"namemarketing":"'.$result[$i]['namemarketing'].'",';
      //$reply.='"schedule":"'.$result[$i]['schedule'].'",';
      $reply.='"coursehour":"'.$result[$i]['coursehour'].'",';
      $reply.='"minutetrain":"'.$result[$i]['minutetrain'].'",';
      $reply.='"objective":"'.$result[$i]['objective'].'",';
      $reply.='"content":"'.$content.'",';
      $reply.='"requirement":"'.$result[$i]['requirement'].'",';
      $reply.='"courseremark":"'.$result[$i]['courseremark'].'",';
      $reply.='"approxstudent":"'.$result[$i]['approxstudent'].'",';
      // $reply.='"approxhead":"'.$result[$i]['approxhead'].'",';
      // $reply.='"approxtotal":"'.$result[$i]['approxtotal'].'",';
      $reply.='"trainerid":"'.$result[$i]['trainerid'].'",';
      $reply.='"taglist":"'.$result[$i]['taglist'].'"';
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
