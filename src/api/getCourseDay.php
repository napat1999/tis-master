<?php
require_once('../lib/db.php');
//$statusID = isset($_GET["statusID"])?$_GET["statusID"]:'';
$statusID = isset($_POST["statusID"])?$_POST["statusID"]:'';
//$statusID = 40;
$reply="[";
$sql="select course.*,trainingsite.sitero,";
$sql.="date(courseschedule.datebegin) as datebegin,date(courseschedule.dateend) as dateend from course ";
$sql.=" left join trainingsite on course.siteid=trainingsite.siteid";
$sql.=" left join courseschedule on course.courseid=courseschedule.courseid";
$sql.=" where status=".$statusID;
$result=json_decode(pgQuery($sql),true);
if($result['code']=="200") {
  for($i=0;$i<count($result)-1;$i++) {
      $title="RO".$result[$i]['sitero'].": ".$result[$i]['nameofficial'];
      $description=$result[$i]['namemarketing'];
      $reply.='{';
      $reply.='"title":"'.$title.'",';
      $reply.='"start":"'.$result[$i]['datebegin'].'",';
      $reply.='"end":"'.$result[$i]['dateend'].'",';
      $reply.='"description":"'.$description.'",';
      $reply.='"url":"courseGeneralEdit.php?courseID='.$result[$i]['courseid'].'"';
      $reply.='},';
  }
  //Trim end$reply=
  $reply = rtrim($reply, ",");

}
$reply.="]";
echo $reply;
?>
