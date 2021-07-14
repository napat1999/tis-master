<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

//For test
// echo
// '{"1":{"employeeno":"RO0425","thai_name":"Sarun Hardcode","email":"sarun.b@jasmine.com","position":"Manager"}}';
// exit;
$employeeNo = isset($_POST["createby"])?strtoupper($_POST["createby"]):'';
$type = isset($_POST["type"])?$_POST["type"]:'';
//$employeeNo="RO1637";
//$type="open";
//$employeeNo="RO2455";

$token=$_SESSION["access_token"];
//echo "token=".$token."<HR>";
if ($token=="") { //No Login no show
    $error_arr=array('code'=>$httpcode,'message'=>'Please login single sign-on');
    $error_json = json_encode($error_arr);
    echo $error_json;
    exit;
}

$url="";
switch ($type) {
  case 'open':
    $url="https://app.jasmine.com/jpmapi/approval/v1/id/".$employeeNo."/resource/opencourseapp";
    break;
  case 'apply':
    $url="https://app.jasmine.com/jpmapi/approval/v1/id/".$employeeNo."/resource/trainingapp";
    break;
  default:
    // code...
    $url="xx";
    break;
}

$headers = array('token: '.$token.'',
				'Content-Type: application/json; charset=UTF-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

$data=json_decode($response, true);
$cnt=count($data);
if($cnt==0) {
	echo "[{}]";
	exit;
}

$verify=array();

for ($i=0;$i<$cnt;$i++) {
	if($data[$i]['authorize']=="approve") {
		$employeeno=$data[$i]['approver']['id'];
		$thai_name=$data[$i]['approver']['thai_name'];
		$email=$data[$i]['approver']['email'];
		//$email='wasupak.c@jasmine.com';
		$position=$data[$i]['approver']['title'];
		$verify[$i]=array('employeeno' => $employeeno
			,'thai_name' => $thai_name
			,'email' => $email
			,'position' => $position
			);
	}
}
// print_r($verify);
// echo "<HR>";
echo json_encode($verify);
?>
