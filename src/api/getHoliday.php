<?php
date_default_timezone_set('Asia/Bangkok');
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

$year = isset($_GET["year"])?$_GET["year"]:'2019';

$url="https://app.jasmine.com/jpmapi/v1/holiday/year/".$year;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

$data=json_decode($response, true);
$cnt=count($data);
if($cnt==0) {
	//echo '[{"title":"'.$year.'","start":"2019-03-18"}]';
	echo '[{}]';
	exit;
}
$timesheet=array();
for ($i=0;$i<$cnt;$i++) {
	$name=$data[$i]['name'];
	$holiday=date("Y-m-d",$data[$i]['date']/1000);
	$description=$data[$i]['description'];
	$timesheet[$i]=array('title' => $name,'description' => $description,'start' => $holiday);
}
echo json_encode($timesheet);
?>
