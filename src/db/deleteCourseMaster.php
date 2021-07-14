<?php
require_once('../lib/db.php');
$courseID = isset($_POST["courseID"])?$_POST["courseID"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
//echo $callerFile;
//exit;
if ($callerFile=="courseMaster.php") {
    //Valid
    $sql="delete from coursemaster where courseid=".$courseID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
