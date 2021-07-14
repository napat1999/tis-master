<?php
require_once('../lib/db.php');
$codeID = isset($_POST["codeID"])?$_POST["codeID"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
if ($callerFile=="courseCode.php") {
    //Valid
    $sql="delete from paramcode where codeid=".$codeID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
