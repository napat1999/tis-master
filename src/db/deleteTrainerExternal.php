<?php
require_once('../lib/db.php');
$trainerID = isset($_POST["trainerID"])?$_POST["trainerID"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
if ($callerFile=="trainerExternal.php") {
    //Valid
    $sql="delete from trainer where trainerid=".$trainerID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
