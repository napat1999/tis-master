<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');
include_once('../lib/ftp_upload.php');
//require_once('../lib/myIMGLib.php');
//include_once("../lib/myS3.php");

if ($_SESSION["employee_id"] == "") {
  $response_json = array('code' => '997', 'message' => 'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}
$trainer_type = isset($_POST["trainer_type"]) ? $_POST["trainer_type"] : '';
$trainerID = isset($_POST["trainerID"]) ? $_POST["trainerID"] : '';
$th_initial = isset($_POST["th_initial"]) ? $_POST["th_initial"] : '';
$thai_name = isset($_POST["thai_fname"]) . " " . isset($_POST["thai_lname"]) ? $_POST["thai_fname"] . " " . $_POST["thai_lname"] : '';
$name_en = isset($_POST["name_en"]) ? $_POST["name_en"] : '';
$lastname_en = isset($_POST["lastname_en"]) ? $_POST["lastname_en"] : '';
$employeeNo = isset($_POST["employeeNo"]) ? strtoupper($_POST["employeeNo"]) : '';
$position = isset($_POST["position"]) ? $_POST["position"] : '';
$department = isset($_POST["department"]) ? $_POST["department"] : '';
$company = isset($_POST["company"]) ? $_POST["company"] : '';
$section = isset($_POST["section"]) ? $_POST["section"] : '';
$division = isset($_POST["division"]) ? $_POST["division"] : '';
$workplace = isset($_POST["workplace"]) ? $_POST["workplace"] : '';
$telephone = isset($_POST["telephone"]) ? $_POST["telephone"] : '';
$email = isset($_POST["email"]) ? strtolower($_POST["email"]) : '';
$trainerremark = isset($_POST["trainerRemark"]) ? strtolower($_POST["trainerRemark"]) : '';
$studyinfo = isset($_POST["studyinfo"]) ? $_POST["studyinfo"] : '';
$workinfo = isset($_POST["workinfo"]) ? $_POST["workinfo"] : '';
$lastupdate = isset($_POST["lastupdate"]) ? $_POST["lastupdate"] : '';
$imagepath = isset($_POST["imagepath"]) ? $_POST["imagepath"] : '';
$contact_p = isset($_POST["contact_p"]) ? $_POST["contact_p"] : '';
$contact_tel = isset($_POST["contact_tel"]) ? $_POST["contact_tel"] : '';
$contact_email = isset($_POST["contact_email"]) ? $_POST["contact_email"] : '';
$expends = isset($_POST["expends"]) ? $_POST["expends"] : '';
$spe_courses = isset($_POST["spe_courses"]) ? $_POST["spe_courses"] : '';
$course_em = isset($_POST["course_em"]) ? $_POST["course_em"] : '';
$gen = isset($_POST["gen"]) ? $_POST["gen"] : '';
$year_train = isset($_POST["year_train"]) ? $_POST["year_train"] : '';
$name = $_FILES["avatar"]["name"]; //original name
//Data part
if ($trainerID == "") { //Insert mode
  if ($name == "") { //No image, just DB
    //echo $resultDB; //show result from db only
    //exit;
    $name = $_FILES["avatar"]["name"]; //original name
    $type = pathinfo(basename($_FILES["avatar"]["name"]), PATHINFO_EXTENSION); //["type"]; //image/png
    $temp = $_FILES["avatar"]["tmp_name"]; // /tmp/xxxx
    $error = $_FILES["avatar"]["error"]; //0
    $sql = "select count(*) cnt from trainer where thai_name=" . prepareString($thai_name);
    $result = json_decode(pgQuery($sql), true);
    //Not found insert
    $sql = "insert into trainer ";
    $sql .= "(th_initial,thai_name,name_en,lastname_en,employeeNo,position,department,company,";
    $sql .= "section,division,workplace,telephone,email,studyinfo,workinfo,trainerRemark,imagepath,expends,spe_courses,course_em,gen,year_train,contact_p,contact_tel,contact_email,createby)";
    $sql .= " values(";
    $sql .= prepareString($th_initial) . ",";
    $sql .= prepareString($thai_name) . ",";
    $sql .= prepareString($name_en) . ",";
    $sql .= prepareString($lastname_en) . ",";
    $sql .= prepareString($employeeNo) . ",";
    $sql .= prepareString($position) . ",";
    $sql .= prepareString($department) . ",";
    $sql .= prepareString($company) . ",";
    $sql .= prepareString($section) . ",";
    $sql .= prepareString($division) . ",";
    $sql .= prepareString($workplace) . ",";
    $sql .= prepareString($telephone) . ",";
    $sql .= prepareString($email) . ",";
    $sql .= prepareString($studyinfo) . ",";
    $sql .= prepareString($workinfo) . ",";
    $sql .= prepareString($trainerremark) . ",";
    //$imagepath = $upload_path ;
    $sql .= prepareString($imagepath) . ",";
    $sql .= prepareString($expends) . ",";
    $sql .= prepareString($spe_courses) . ",";
    $sql .= prepareString($course_em) . ",";
    $sql .= prepareString($gen) . ",";
    $sql .= prepareString($year_train) . ",";
    $sql .= prepareString($contact_p) . ",";
    $sql .= prepareString($contact_tel) . ",";
    $sql .= prepareString($contact_email) . ",";
    $sql .= prepareString($_SESSION["employee_id"]) . ")";
    //$sql.=")";
    $result = pgExecute($sql);
    echo  $result;
    //$resultArray = json_decode($resultDB, true);
    // if ($resultArray['code'] == '200') {
    //   $trainerID = $resultArray['message'];
    // }
    //for($i=0;$i<count($result)-1;$i++) {
    //$isFound=1;
    //echo $resultDB;
    //exit;
  } else {
    $name = $_FILES["avatar"]["name"]; //original name
    $type = pathinfo(basename($_FILES["avatar"]["name"]), PATHINFO_EXTENSION); //["type"]; //image/png
    $temp = $_FILES["avatar"]["tmp_name"]; // /tmp/xxxx
    $error = $_FILES["avatar"]["error"]; //0

    if ($error > 0) {
      $error_arr = array('code' => '999', 'message' => 'image error code' . $error);
      $response_json = json_encode($error_arr);
      echo $response_json;
      exit;
    } else {
      try {

        if ($trainerID == "") {  //Insert mode
          //Check Before inserted
          $sql = "select count(*) cnt from trainer where thai_name=" . prepareString($thai_name);
          $result = json_decode(pgQuery($sql), true);

          //Not found insert
          $sql = "insert into trainer ";
          $sql .= "(th_initial,thai_name,name_en,lastname_en,employeeNo,position,department,company,";
          $sql .= "section,division,workplace,telephone,email,studyinfo,workinfo,trainerRemark,imagepath,expends,spe_courses,course_em,gen,year_train,contact_p,contact_tel,contact_email,createby)";
          $sql .= " values(";
          $sql .= prepareString($th_initial) . ",";
          $sql .= prepareString($thai_name) . ",";
          $sql .= prepareString($name_en) . ",";
          $sql .= prepareString($lastname_en) . ",";
          $sql .= prepareString($employeeNo) . ",";
          $sql .= prepareString($position) . ",";
          $sql .= prepareString($department) . ",";
          $sql .= prepareString($company) . ",";
          $sql .= prepareString($section) . ",";
          $sql .= prepareString($division) . ",";
          $sql .= prepareString($workplace) . ",";
          $sql .= prepareString($telephone) . ",";
          $sql .= prepareString($email) . ",";
          $sql .= prepareString($studyinfo) . ",";
          $sql .= prepareString($workinfo) . ",";
          $sql .= prepareString($trainerremark) . ",";
          //$imagepath = $upload_path ;
          $sql .= prepareString($imagepath) . ",";
          $sql .= prepareString($expends) . ",";
          $sql .= prepareString($spe_courses) . ",";
          $sql .= prepareString($course_em) . ",";
          $sql .= prepareString($gen) . ",";
          $sql .= prepareString($year_train) . ",";
          $sql .= prepareString($contact_p) . ",";
          $sql .= prepareString($contact_tel) . ",";
          $sql .= prepareString($contact_email) . ",";
          $sql .= prepareString($_SESSION["employee_id"]) . ")";
          //$sql.=")";
          $result = pgExecute($sql);
          echo  $result;
          //$resultArray = json_decode($resultDB, true);
          // if ($resultArray['code'] == '200') {
          //   $trainerID = $resultArray['message'];
          // }
          //for($i=0;$i<count($result)-1;$i++) {
          //$isFound=1;
          //echo $resultDB;
          //exit;

        }
        // $sql1 = "select trainerID from trainer where thai_name=" . prepareString($thai_name);
        // $ret = json_decode(pgQuery($sql1), true);
        // $x =  $ret[0]['trainerID'];
        $sql1 = "select MAX(trainerID) AS maxid from trainer";
        $ret = json_decode(pgQuery($sql1), true);
        $x =  $ret[0]['maxid'];
        //echo $x;
        $lastupload = date("Y-m-d_H-i-s");

        /*****S3 Upload Image*****/
        $filename = $x . "_" . $name_en . "_" . $lastname_en . "_" . $lastupload . "_";
        $filenameup = $filename . "." . $type;
        $prefix = "../assets/img/trainer/";
        $resp = uploadimg($prefix, $filename, $temp, $type);
        //echo $resp;
        if ($resp == "") {
          $error_arr = array('code' => '999', 'message' => 'Simple Storage Service Error Code: ' . $resp);
          $response_json = json_encode($error_arr);
          echo $response_json;
          exit;
        } else {
          $sql = "update trainer set ";
          $sql .= "imagepath=" . prepareString($resp) . ","; //** */
          $sql .= "trainer_pict=" . prepareString($filenameup) . ""; //** */
          $sql .= "where trainerid=" . $x . "";
          $resultDB = pgExecute($sql);
          // echo $resultDB;
        }
      } catch (RuntimeException $e) {
        $error_arr = array('code' => '999', 'message' => $e->getMessage());
        $response_json = json_encode($error_arr);
        echo $response_json;
        exit;
      }
    }
  }
  //Check Before inserted

} else { //Update mode
  $sql = "update trainer set ";
  $sql .= "th_initial=" . prepareString($th_initial) . ",";
  $sql .= "thai_name=" . prepareString($thai_name) . ",";
  $sql .= "name_en=" . prepareString($name_en) . ",";
  $sql .= "lastname_en=" . prepareString($lastname_en) . ",";
  $sql .= "employeeNo=" . prepareString($employeeNo) . ",";
  $sql .= "position=" . prepareString($position) . ",";
  $sql .= "department=" . prepareString($department) . ",";
  $sql .= "company=" . prepareString($company) . ",";
  $sql .= "section=" . prepareString($section) . ",";
  $sql .= "division=" . prepareString($division) . ",";
  $sql .= "workplace=" . prepareString($workplace) . ",";
  $sql .= "telephone=" . prepareString($telephone) . ",";
  $sql .= "email=" . prepareString($email) . ",";
  $sql .= "studyinfo=" . prepareString($studyinfo) . ",";
  $sql .= "workinfo=" . prepareString($workinfo) . ",";
  $sql .= "trainerRemark=" . prepareString($trainerremark) . ",";
  $sql .= "expends=" . prepareString($expends) . ",";
  $sql .= "spe_courses=" . prepareString($spe_courses) . ",";
  $sql .= "course_em=" . prepareString($course_em) . ",";
  $sql .= "gen=" . prepareString($gen) . ",";
  $sql .= "year_train=" . prepareString($year_train) . ",";
  $sql .= "contact_p=" . prepareString($contact_p) . ",";
  $sql .= "contact_tel=" . prepareString($contact_tel) . ",";
  $sql .= "contact_email=" . prepareString($contact_email) . ",";
  $sql .= "updateby=" . prepareString($_SESSION["employee_id"]) . ",";
  $sql .= "lastupdate=current_timestamp ";
  $sql .= "where trainerID=" . $trainerID . "";
  $result = pgExecute($sql);
  echo $result;
  //echo $sql;
}

//Image part
//print_r($_FILES["avatar"]);
$name = $_FILES["avatar"]["name"]; //original name
$type = pathinfo(basename($_FILES["avatar"]["name"]), PATHINFO_EXTENSION); //["type"]; //image/png
$temp = $_FILES["avatar"]["tmp_name"]; // /tmp/xxxx
$error = $_FILES["avatar"]["error"]; //0

if ($name == "") { //No image, just DB
  //echo $resultDB; //show result from db only
  //exit;
} else {
  if ($error > 0) {
    $error_arr = array('code' => '999', 'message' => 'image error code' . $error);
    $response_json = json_encode($error_arr);
    echo $response_json;
    exit;
  } else {
    try {

      $lastupload = date("Y-m-d_H-i-s");

      /*****S3 Upload Image*****/
      $filename = $trainerID . "_" . $name_en . "_" . $lastname_en . "_" . $lastupload . "_";
      $filenameup = $filename . "." . $type;
      $prefix = "../assets/img/trainer/";
      $resp = uploadimg($prefix, $filename, $temp, $type);
      //echo $resp;
      if ($resp == "") {
        $error_arr = array('code' => '999', 'message' => 'Simple Storage Service Error Code: ' . $resp);
        $response_json = json_encode($error_arr);
        echo $response_json;
        exit;
      } else {
        $sql = "update trainer set ";
        $sql .= "imagepath=" . prepareString($resp) . ","; //** */
        $sql .= "trainer_pict=" . prepareString($filenameup) . ""; //** */
        $sql .= "where trainerid=" . $trainerID . "";
        $resultDB = pgExecute($sql);
        //echo $resultDB;
        //echo "<script>" . "$('#loading').hide(); " . "</script>";


      }
    } catch (RuntimeException $e) {
      $error_arr = array('code' => '999', 'message' => $e->getMessage());
      $response_json = json_encode($error_arr);
      echo $response_json;
      exit;
    }
  }
}

// Document part
// if ($_FILES["fileDocument"]["name"][0] == "") {
//   echo  $resultDB; //show result from db only
//   exit;
// } else {
//   $countFile = count($_FILES["fileDocument"]["name"]);
//   for ($i = 0; $i < $countFile; $i++) {
//     $name = $_FILES["fileDocument"]["name"][$i];
//     $type = $_FILES["fileDocument"]["type"][$i];
//     $temp = $_FILES["fileDocument"]["tmp_name"][$i];
//     $error = $_FILES["fileDocument"]["error"][$i];
//     if ($error > 0) {
//       $error_arr = array('code' => '999', 'message' => 'doucment error code' . $error);
//       $response_json = json_encode($error_arr);
//       echo $response_json;
//       exit;
//     } else {
//       try {
//         /*****S3 Upload Image*****/
//         $filename = date('Ymd-His') . "-" . $i;
//         $prefix = "TIS/Trainer/Document/";
//         $resp = UploadObjectsToBucket($prefix, $filename, $temp, $type);
//         $ext = pathinfo($name, PATHINFO_EXTENSION);
//         if ($resp != 200) {
//           $error_arr = array('code' => '999', 'message' => 'Simple Storage Service Error Code: ' . $resp);
//           $response_json = json_encode($error_arr);
//           echo $response_json;
//           exit;
//         } else {
//           $sql = "insert into trainerDocument ";
//           $sql .= "(filepath,filetype,oldname,trainerid)";
//           $sql .= " values(";
//           $sql .= prepareString($prefix . $filename) . ",";
//           $sql .= prepareString($ext) . ",";
//           $sql .= prepareString($name) . ",";
//           $sql .= $trainerID . ")";
//           $resultDB = pgExecute($sql);
//           //$resultArray=json_decode($resultDB,true)
//         }
//         $msgshow = "Saved";
//         $error_arr = array('code' => '200', 'message' => $msgshow);
//         $response_json = json_encode($error_arr);
//         //exit;
//       } catch (RuntimeException $e) {
//         $error_arr = array('code' => '999', 'message' => $e->getMessage());
//         $response_json = json_encode($error_arr);
//         echo $response_json;
//         exit;
//       }
//     }
//   } //end forloop
//   echo $response_json;
// } //end else
// print_r($_FILES["fileDocument"]);
// $name= $_FILES["fileDocument"]["name"][0];
// $type= $_FILES["fileDocument"]["type"][0];
// $temp= $_FILES["fileDocument"]["tmp_name"][0];
// $error= $_FILES["fileDocument"]["error"][0];

// if($name=="") { //No image, just DB
//   echo $resultDB; //show result from db only
//   exit;
// } else {
//   if ($error > 0) {
//     $error_arr=array('code'=>'999','message'=>'doucment error code'.$error);
//     $response_json = json_encode($error_arr);
//     echo $response_json;
//     exit;
//   } else {
//     try {

//       /*****S3 Upload Image*****/
//       $filename="Trainer-".$trainerID;
//       $prefix="TIS/Trainer/Document/";

//       $resp=UploadObjectsToBucket($prefix,$filename,$temp,$type);
//       if($resp!=200){
//         echo $response_json;
//         exit;
//       }else{
//          $sql="update trainer set ";
//         $sql.="documentpath=".prepareString($prefix.$filename)."";
//         $sql.="where trainerid=".$trainerID."";
//         $resultDB=pgExecute($sql);
//       }
//       $msgshow="Saved";
//       $error_arr=array('code'=>'200','message'=>$msgshow);
//       $response_json = json_encode($error_arr);
//       echo $response_json;
//       //exit;
//     } catch (RuntimeException $e) {
//       $error_arr=array('code'=>'999','message'=>$e->getMessage());
//       $response_json = json_encode($error_arr);
//       echo $response_json;
//       exit;
//     }
//   }
// }
/****Remark*********/
/*
* Warning: POST Content-Length of 90612004 bytes exceeds the limit of 8388608 bytes in Unknown on line 0
* set php.ini
* - post_max_size=100M
* - upload_max_filesize=100M
*/
