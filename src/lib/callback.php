<?php
include_once("myOAuth.php");

//session_start();

$code=$_GET['code'];
$accessToken = check_login($code);

if($_SESSION["CurURL"]!="") {
	header("Location: ".$_SESSION["CurURL"]);
}
?>
