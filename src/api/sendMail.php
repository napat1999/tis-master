<?php
//Demo for test
// $success_arr=array('code'=>'200','message'=>'Send Success');
// $response_json = json_encode($success_arr);
// echo $response_json;
// exit;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/PHPMailer-6.0.6/Exception.php';
require '../lib/PHPMailer-6.0.6/PHPMailer.php';
require '../lib/PHPMailer-6.0.6/SMTP.php';

$sendfrom="tis-noreply@jasmine.com";
$sendname="TIS Admin";
$sendto = isset($_POST["sendto"])?$_POST["sendto"]:'';
$ccto = isset($_POST["ccto"])?$_POST["ccto"]:'';
$subject = isset($_POST["subject"])?$_POST["subject"]:'';
$bodyhtml = isset($_POST["bodyhtml"])?$_POST["bodyhtml"]:'';

//Hard code remove on production
$sendto=str_replace("narongrit.v@jasmine.com","wasupak.c@jasmine.com",$sendto);
//$sendto=str_replace("phatnaree@jasmine.com","wasupak.c@jasmine.com",$sendto);
$ccto=str_replace("narongrit.v@jasmine.com","wasupak.c@jasmine.com",$ccto);
//$ccto=str_replace("phatnaree@jasmine.com","wasupak.c@jasmine.com",$ccto);

$sendtoArray = explode(",",$sendto);
if($ccto!="") {
  $cctoArray = explode(",",$ccto);
}

try {
  $mail = new PHPMailer(true);
  $mail->CharSet = "utf-8";
  $mail->isSMTP();
  $mail->Host = "10.2.0.2"; // smtp.jasmine.com
  $mail->Port = 25;

  $mail->setFrom($sendfrom,$sendname);
	for($cnt=0; $cnt<sizeof($sendtoArray); $cnt++){
    $mail->addAddress($sendtoArray[$cnt]);     // Add a recipient
	}
	if($ccto!=''){
		for($cnt=0; $cnt<sizeof($cctoArray); $cnt++){
			$mail->addCC($cctoArray[$cnt]);
		}
	}

	$mail->Subject = $subject;
  $mail->Body    = $bodyhtml;
  $mail->AltBody = 'This message is HTML. Please enable html to read.';

	$mail->IsHTML(true);
	$mail->Send();

  $success_arr=array('code'=>'200','message'=>'Send Success');
  $response_json = json_encode($success_arr);
  echo $response_json;

} catch (Exception $e) {
	$send_status = $mail->ErrorInfo;
  $error_arr=array('code'=>'403','message'=>$send_status);
  $response_json = json_encode($error_arr);
  echo $response_json;
}
?>
