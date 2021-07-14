<?php
require_once('../lib/db.php');
$userID = isset($_POST["userID"])?$_POST["userID"]:'';
//Protect from invalid call
$callerFile=basename($_SERVER['HTTP_REFERER']);
if ($callerFile=="users.php") {
    //Valid
    $sql="delete from tisusers where userid=".$userID;
    $result=pgExecute($sql);
    echo $result;
} else {
    $error_arr=array('code'=>'403','message'=>'Invalid call');
    $response_json = json_encode($error_arr);
    echo $response_json;
}
?>
