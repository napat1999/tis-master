<?php
try {
require_once('../lib/db.php');
include_once("../lib/myS3.php");
$trainerDocID = isset($_POST["trainerDocID"])?$_POST["trainerDocID"]:'';
$filePath = isset($_POST["filePath"])?$_POST["filePath"]:'';
$callerFileArr=pathinfo($_SERVER['HTTP_REFERER']);
$callerFile=$callerFileArr['filename'].".php";
if ($callerFile=="trainerExternalEdit.php") {

     $resp=DeleteObjectOnBucket("",$filePath);
    if($resp!="200"){
      $error_arr=array('code'=>'999','message'=>'Simple Storage Service Error Code: '.$resp);
      $response_json = json_encode($error_arr);
      echo $response_json;
      exit;
    }
    $sql="delete from trainerDocument where trainerdocid=".$trainerDocID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
}catch (Exception $e) {
  $error_arr=array('code'=>$e->getCode(),'message'=>$e->getMessage());
  $response_json = json_encode($error_arr);
  echo $response_json;
}
?>
