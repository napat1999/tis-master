<?php
include_once("lib/myOAuth.php");
$_SESSION["CurURL"]="../index.php";
if ($_SESSION["access_token"]!="") {
    //Back to caller if already login
   header("Location: ".$_SERVER['HTTP_REFERER']);
} else {
     $accessToken=check_login($code);
}
include_once("lib/chkLogin.php"); //If need login enable here
$host=$_SERVER['HTTP_HOST'];
$homeURL="";
switch ($host) {
  case 'app.jasmine.com':
    $homeURL="https://$_SERVER[HTTP_HOST]/tis";
    break;
  default:
    $homeURL="http://$_SERVER[HTTP_HOST]";
    break;
}
header("Location: ".$homeURL);
?>
