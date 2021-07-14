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
$codeID = isset($_POST["codeID"])?$_POST["codeID"]:'';
$courseLevel = isset($_POST["courseLevel"])?$_POST["courseLevel"]:'';
$courseNumber = isset($_POST["courseNumber"])?$_POST["courseNumber"]:'';
$nameOfficial = isset($_POST["nameOfficial"])?$_POST["nameOfficial"]:'';
$nameMarketing = isset($_POST["nameMarketing"])?$_POST["nameMarketing"]:'';
// $schedule = isset($_POST["schedule"])?($_POST["schedule"]):'';
$courseHour = isset($_POST["courseHour"])?$_POST["courseHour"]:'';
$objective = isset($_POST["objective"])?$_POST["objective"]:'';
$content = htmlentities(isset($_POST["course_content"])?$_POST["course_content"]:'');
$requirement = isset($_POST["requirement"])?$_POST["requirement"]:'';
$courseRemark = isset($_POST["courseRemark"])?$_POST["courseRemark"]:'';
$approxstudent = isset($_POST["approxstudent"])?$_POST["approxstudent"]:'';
// $approxhead = isset($_POST["approxhead"])?$_POST["approxhead"]:'';
// $approxtotal = isset($_POST["approxtotal"])?$_POST["approxtotal"]:'';
$trainerArray = isset($_POST["trainerid"])?$_POST["trainerid"]:'';
$trainerid="";
if($trainerArray<>"") {
  foreach ($trainerArray as $value) {
    $trainerid.=$value.",";
  }
  $trainerid=rtrim($trainerid,",");
}

$tagArray = isset($_POST["tagid"])?$_POST["tagid"]:'';
$tagid="";
if($tagArray<>"") {
  foreach ($tagArray as $value) {
    $tagid.=$value.",";
  }
  $tagid=rtrim($tagid,",");
}

if($courseID=="") { //Insert mode
    if($courseNumber=="") { //No number, get latest number +1
      $sql="select COALESCE(max(coursenumber)+1,1) as coursenumber from coursemaster";
      $sql.=" where coursecodeid=".prepareString($codeID);
      $sql.=" and courselevel=".prepareString($courseLevel);
      $result=json_decode(pgQuery($sql),true);
      if($result['code']=="200") {
        //$courseNumber=str_pad($result[0]['coursenumber'],3,'0',STR_PAD_LEFT);
        $courseNumber=$result[0]['coursenumber'];
        $courseSequence='1';
      }
    } else {
      //Have number, get auto sequence
      $sql="select COALESCE(max(coursesequence)+1,1) as coursesequence from coursemaster";
      $sql.=" where coursecodeid=".prepareString($codeID);
      $sql.=" and courselevel=".prepareString($courseLevel);
      $sql.=" and coursenumber=".prepareString($courseNumber);
      $result=json_decode(pgQuery($sql),true);
      if($result['code']=="200") {
        //$courseNumber=str_pad($result[0]['coursenumber'],3,'0',STR_PAD_LEFT);
        $courseSequence=$result[0]['coursesequence'];
      }
    }

    //Check Before inserted
    $sql="select count(*) cnt from course where courseid=".prepareString($courseID);
    $result=json_decode(pgQuery($sql),true);
    if($result[0]['cnt']==0) {
        //Not found insert
        $sql="insert into coursemaster ";
        $sql.="(coursecodeid,courselevel,coursenumber,coursesequence,";
        $sql.="nameOfficial,nameMarketing,courseHour,objective,content,";
        $sql.="requirement,courseRemark,approxstudent,";
        $sql.="trainerid,taglist,createby)";
        $sql.=" values(";
        $sql.=prepareNumber($codeID).",";
        $sql.=prepareNumber($courseLevel).",";
        $sql.=prepareNumber($courseNumber).",";
        $sql.=prepareNumber($courseSequence).",";
        $sql.=prepareString($nameOfficial).",";
        $sql.=prepareString($nameMarketing).",";
        $sql.=prepareNumber($courseHour).",";
        $sql.=prepareString($objective).",";
        $sql.=prepareString($content).",";
        $sql.=prepareString($requirement).",";
        $sql.=prepareString($courseRemark).",";
        $sql.=prepareNumber($approxstudent).",";
        // $sql.=prepareNumber($approxhead).",";
        // $sql.=prepareNumber($approxtotal).",";
        $sql.=prepareString($trainerid).",";
        $sql.=prepareString($tagid).",";
        $sql.=prepareString($_SESSION["employee_id"]).")";

        $result=pgExecute($sql);
        echo $result;

        // $error_arr=array('code'=>'999','message'=>$sql);
        // $response_json = json_encode($error_arr);
        // echo $response_json;
    } else {
        $error_arr=array('code'=>'999','message'=>'ID ชื่อหลักสูตรซ้ำในระบบ');
        $response_json = json_encode($error_arr);
        echo $response_json;
    }

} else { //Update mode
    $sql="update coursemaster set ";
    $sql.="nameOfficial=".prepareString($nameOfficial).",";
    $sql.="nameMarketing=".prepareString($nameMarketing).",";
    $sql.="courseHour=".prepareNumber($courseHour).",";
    $sql.="objective=".prepareString($objective).",";
    $sql.="content=".prepareString($content).",";
    $sql.="requirement=".prepareString($requirement).",";
    $sql.="courseRemark=".prepareString($courseRemark).",";
    $sql.="approxstudent=".prepareNumber($approxstudent).",";
    // $sql.="approxhead=".prepareNumber($approxhead).",";
    // $sql.="approxtotal=".prepareNumber($approxtotal).",";
    $sql.="trainerid=".prepareString($trainerid).",";
    $sql.="taglist=".prepareString($tagid).",";
    $sql.="updateby=".prepareString($_SESSION["employee_id"]).",";
    $sql.="lastupdate=current_timestamp ";
    $sql.="where courseid=".$courseID."";
    $result=pgExecute($sql);
    echo $result;

     // $error_arr=array('code'=>'999','message'=>$sql);
     // $response_json = json_encode($error_arr);
     // echo $response_json;
}
?>
