<?php
date_default_timezone_set('Asia/Bangkok');
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

$min = isset($_POST["min"])?strtoupper($_POST["min"]):'';
$max = isset($_POST["max"])?strtoupper($_POST["max"]):'';
//$min="0";
//$max="1";
if($min=="" or $max=="") {
  $error_arr=array('code'=>"998",'message'=>'API parameter incorrect');
  $error_json = json_encode($error_arr);
  echo $error_json;
  exit;
}
$apiURL="https://app.jasmine.com/jpmapi/employee/v1/search/experience/min/".$min."/max/".$max;
//echo $apiURL;
//exit;
$token=$_SESSION["access_token"];
$department=$_SESSION["department"];
$company=$_SESSION["company"];
//echo "token=".$token."<HR>";
if ($token=="") { //No Login no show
    $error_arr=array('code'=>$httpcode,'message'=>'Please login single sign-on');
    $error_json = json_encode($error_arr);
    echo $error_json;
    exit;
}
$headers = array('token: '.$token.'',
            'Content-Type: application/json; charset=UTF-8');
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $apiURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);


    if($httpcode==200) {
        //insert code to reply json
        $responseArray=json_decode($response,true);
        $empArray=array();
        $empCount=0;
        foreach ($responseArray as $key=>$value) {
            if($value['active']!=1) {
              //Ignore unactive employee
              continue;
            };
            if($value['company']!=$company or $value['department']!=$department) {
              //Ignore different department
              //echo "Skip ".$value['company']." with ".$company." ".$value['department']." with ".$department;
              //echo "<br/>";
              continue;
            }
            //echo "Accept".$value['company']." with ".$company." ".$value['department']." with ".$department;
            $empArray[$empCount]['id']=$value['id'];
            $empArray[$empCount]['thai_name']=$value['thai_name'];
            $empArray[$empCount]['tinitial']=$value['tinitial'];
            $empArray[$empCount]['company']=$value['company'];
            $empArray[$empCount]['department']=$value['department'];
            $empArray[$empCount]['section']=$value['section'];
            $empArray[$empCount]['division']=$value['division'];
            $empArray[$empCount]['position']=$value['title'];
            $empArray[$empCount]['ouid']=$value['ouid'];
            $empArray[$empCount]['email']=$value['email'];
            $date_join=date("Y-m-d",$value['date_join']/1000);
            $empArray[$empCount]['date_join']=$date_join;
            $empArray[$empCount]['workdate']=date_diff(date_create($date_join),new DateTime("now"))->days;
            $empCount++;
        }
        //$response_json=json_decode($response,true);
        //$response_json=array_merge(array("code"=>"200"),$response_json);
        //$response=json_encode($response_json);
        //echo $response;
        $response_json=array_merge(array("code"=>"200"),$empArray);
        $response=json_encode($response_json);
        echo $response;
    } else {
      $error_arr=array('code'=>$httpcode,'message'=>'Cannot connected');
      $error_json = json_encode($error_arr);
      echo $error_json;
    }
} catch (Exception $e) {
    $error_arr=array('code'=>$httpcode,'message'=>'Curl Exception:'.$e->getMessage());
    $error_json = json_encode($error_arr);
    echo $error_json;
}
?>
