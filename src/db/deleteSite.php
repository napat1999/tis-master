<?php
require_once('../lib/db.php');
$siteID = isset($_POST["siteID"])?$_POST["siteID"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
if ($callerFile=="trainingSite.php") {
    //Valid
    $sql="delete from trainingsite where siteid=".$siteID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
