<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here
date_default_timezone_set('Asia/Bangkok');

$token=$_SESSION["access_token"];
//echo "token=".$token."<HR>";
if ($token=="") { //No Login no show
	echo "[{}}";
	exit;
}

$url="https://app.jasmine.com/jpmapi/v1/timesheets/employeeid/RO1637/year/2019/month/3";
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
	echo "[{}}";
	exit;
}
$timesheet=array();
for ($i=0;$i<$cnt;$i++) {
	//echo "Workdate : ".$data[$i]['work_date']."<BR>";
	//echo "time in : ".$data[$i]['time_in']."<BR>";
	//echo "time_leave : ".$data[$i]['time_leave']."<BR>";
	$time_in="_";
	if($data[$i]['time_in']!="") {
		$time_in=date("H:i",$data[$i]['time_in']/1000);
	}
	$time_out="_";
	if($data[$i]['time_leave']!="") {
		$time_out=date("H:i",$data[$i]['time_leave']/1000);
	}
	$title=$time_in." - ".$time_out;
	$timesheet[$i]=array('title' => $title,'start' => $data[$i]['work_date']);
}
//print_r($timesheet);
echo json_encode($timesheet);
?>
