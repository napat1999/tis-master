<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');
$courseID = isset($_POST["courseID"])?$_POST["courseID"]:'';
$status = isset($_POST["status"])?$_POST["status"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
//Remove get parameter
$callerArray=explode("?",$callerFile);
$callerFile=$callerArray[0];
if ($callerFile=="courseEdit.php") {
    //Valid
    $sql="update coursemaster set status=".$status." where courseid=".$courseID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
