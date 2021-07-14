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
$courseMasterID = isset($_POST["courseMasterID"])?$_POST["courseMasterID"]:'';
$courseGen = isset($_POST["courseGen"])?$_POST["courseGen"]:'';
$nameOfficial = isset($_POST["nameOfficial"])?$_POST["nameOfficial"]:'';
$nameMarketing = isset($_POST["nameMarketing"])?$_POST["nameMarketing"]:'';
$schedule = isset($_POST["schedule"])?($_POST["schedule"]):'';
$dateBegin = isset($_POST["dateBegin"])?($_POST["dateBegin"]):'';
$dateEnd = isset($_POST["dateEnd"])?($_POST["dateEnd"]):'';
$minuteTrain = isset($_POST["minuteTrain"])?$_POST["minuteTrain"]:'';
//Convert seconds to minutes
//$minuteTrain=$minuteTrain/60;
$objective = isset($_POST["objective"])?$_POST["objective"]:'';
$content = htmlentities(isset($_POST["course_content"])?$_POST["course_content"]:'');
$requirement = isset($_POST["requirement"])?$_POST["requirement"]:'';
$courseRemark = isset($_POST["courseRemark"])?$_POST["courseRemark"]:'';
$approxstudent = isset($_POST["approxstudent"])?$_POST["approxstudent"]:'';
$approxhead = isset($_POST["approxhead"])?$_POST["approxhead"]:'';
$approxtotal = isset($_POST["approxtotal"])?$_POST["approxtotal"]:'';
$budget = isset($_POST["budget"])?$_POST["budget"]:'';
$siteid = isset($_POST["siteid"])?$_POST["siteid"]:'';
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

$isInserted=0;
if($courseID=="") { //Insert mode
    $isInserted=1;
    if($courseMasterID==0) {
      $nextgen=0; //Ignore gen if no coursecode
    } else {
      //Count course in the past
      $sql="select COALESCE(max(coursegen),0) as maxgen from course where coursemasterid=".prepareString($courseMasterID);
      $result=json_decode(pgQuery($sql),true);
      $nextgen=$result[0]['maxgen']+1;
    }

    $sql="insert into course ";
    $sql.="(coursemasterid,coursegen,nameOfficial,nameMarketing,schedule,minuteTrain,objective,content,";
    $sql.="requirement,courseRemark,approxstudent,approxhead,approxtotal,budget,";
    $sql.="siteid,trainerid,taglist,createby)";
    $sql.=" values(";
    $sql.=prepareNumber($courseMasterID).",";
    $sql.=prepareNumber($nextgen).",";
    $sql.=prepareString($nameOfficial).",";
    $sql.=prepareString($nameMarketing).",";
    $sql.=prepareString($schedule).",";
    $sql.=prepareNumber($minuteTrain).",";
    $sql.=prepareString($objective).",";
    $sql.=prepareString($content).",";
    $sql.=prepareString($requirement).",";
    $sql.=prepareString($courseRemark).",";
    $sql.=prepareNumber($approxstudent).",";
    $sql.=prepareNumber($approxhead).",";
    $sql.=prepareNumber($approxtotal).",";
    $sql.=prepareNumber($budget).",";
    $sql.=prepareString($siteid).",";
    $sql.=prepareString($trainerid).",";
    $sql.=prepareString($tagid).",";
    $sql.=prepareString($_SESSION["employee_id"]).")";

    $resultDB=pgExecute($sql);
    $resultArray=json_decode($resultDB,true);
    if($resultArray['code']=='200') {
      $courseID=$resultArray['message'];
    }

    // $error_arr=array('code'=>'999','message'=>$sql);
    // $response_json = json_encode($error_arr);
    // echo $response_json;
    // exit;

} else { //Update mode
    $sql="select * from course where courseid=".$courseID;
    $result=json_decode(pgQuery($sql),true);
    $OldCourseMasterID=$result[0]['coursemasterid'];
    $OldCourseGen=$result[0]['coursegen'];

    if($courseMasterID==$OldCourseMasterID) {
      $nextgen=$OldCourseGen; //Ignore change if same code id
    } else {
      if($courseMasterID==0) {
        $nextgen=0; //Ignore gen if no coursecode
      } else {
        //Count course in the past
        $sql="select COALESCE(max(coursegen),0) as maxgen from course where coursemasterid=".prepareString($courseMasterID);
        $result=json_decode(pgQuery($sql),true);
        $nextgen=$result[0]['maxgen']+1;
      }
    }



    $sql="update course set ";
    $sql.="coursemasterid=".prepareNumber($courseMasterID).",";
    $sql.="coursegen=".prepareNumber($nextgen).",";
    $sql.="nameOfficial=".prepareString($nameOfficial).",";
    $sql.="nameMarketing=".prepareString($nameMarketing).",";
    $sql.="schedule=".prepareString($schedule).",";
    $sql.="minuteTrain=".prepareNumber($minuteTrain).",";
    $sql.="objective=".prepareString($objective).",";
    $sql.="content=".prepareString($content).",";
    $sql.="requirement=".prepareString($requirement).",";
    $sql.="courseRemark=".prepareString($courseRemark).",";
    $sql.="approxstudent=".prepareNumber($approxstudent).",";
    $sql.="approxhead=".prepareNumber($approxhead).",";
    $sql.="approxtotal=".prepareNumber($approxtotal).",";
    $sql.="budget=".prepareNumber($budget).",";
    $sql.="siteid=".prepareString($siteid).",";
    $sql.="trainerid=".prepareString($trainerid).",";
    $sql.="taglist=".prepareString($tagid).",";
    $sql.="updateby=".prepareString($_SESSION["employee_id"]).",";
    $sql.="lastupdate=current_timestamp ";
    $sql.="where courseid=".$courseID."";
    $result=pgExecute($sql);
    //echo $result;

     // $error_arr=array('code'=>'999','message'=>$sql);
     // $response_json = json_encode($error_arr);
     // echo $response_json;
     // exit;
}

//Schedule part
if($courseID!="") {
  $dateCourseBeginArray = isset($_POST["dateCourseBegin"])?$_POST["dateCourseBegin"]:'';
  $dateCourseEndArray = isset($_POST["dateCourseEnd"])?$_POST["dateCourseEnd"]:'';
  $diffMinArray = isset($_POST["diffMin"])?$_POST["diffMin"]:'';

  $cntArray=0;
  foreach ($dateCourseBeginArray as $value) {
    $dateCourseList[$cntArray]['dateCourseBegin']=$value;
    $cntArray++;
  }
  $cntArray=0;
  foreach ($dateCourseEndArray as $value) {
    $dateCourseList[$cntArray]['dateCourseEnd']=$value;
    $cntArray++;
  }
  $cntArray=0;
  foreach ($diffMinArray as $value) {
    $dateCourseList[$cntArray]['diffMin']=$value;
    $cntArray++;
  }
} else {
  $error_arr=array('code'=>'999','message'=>'Problem insert course');
  $response_json = json_encode($error_arr);
  echo $response_json;
}

//Clear old schedule
if($isInserted==0) {
  $sql="delete from courseschedule where courseid=".$courseID;
  $result=json_decode(pgExecute($sql),true);
  if($result['code']<>"200") {
    $error_arr=array('code'=>'999','message'=>'Problem when delete schedule');
    $response_json = json_encode($error_arr);
    echo $response_json;
    exit;
  }
}

//Insert schedule
for($i=0;$i<$cntArray;$i++) {
  $sql="insert into courseschedule(courseid,datebegin,dateend,roundmins) values(";
  $sql.=prepareString($courseID).",";
  $sql.=prepareDateTime($dateCourseList[$i]['dateCourseBegin'],'d/m/Y').",";
  $sql.=prepareDateTime($dateCourseList[$i]['dateCourseEnd'],'d/m/Y').",";
  $sql.=prepareNumber($dateCourseList[$i]['diffMin']).")";
  $result=json_decode(pgExecute($sql),true);
  if($result['code']<>"200") {
    $error_arr=array('code'=>'999','message'=>'Problem when insert schedule');
    $response_json = json_encode($error_arr);
    echo $response_json;
    exit;
  }
}

//No error return complete
$response_json=array('code'=>'200','message'=>'Complete schedule');
$response_json = json_encode($response_json);
echo $response_json;
?>
