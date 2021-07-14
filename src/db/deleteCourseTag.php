<?php
require_once('../lib/db.php');
$tagID = isset($_POST["tagID"])?$_POST["tagID"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
if ($callerFile=="courseTag.php") {
    //Valid
    $sql="delete from paramtag where tagid=".$tagID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
