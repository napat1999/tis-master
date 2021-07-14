<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

$employeeNo = isset($_POST["employeeNo"])?strtoupper($_POST["employeeNo"]):'';
$employeeNo = str_replace('/','',$employeeNo); //Fix Employee Code /
$apiURL="https://app.jasmine.com/contactapi/api/employee/v2/id/".$employeeNo;
$_SESSION["access_token"]="0e646560-525d-47d9-922e-978d1e160553";
$token=$_SESSION["access_token"];
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
        $response_json=json_decode($response,true);
        $response_json=array_merge(array("code"=>"200"),$response_json);
        $response=json_encode($response_json);
        echo $response;
    } else {
      $error_arr=array('code'=>$httpcode,'message'=>'Cannot connected');
      $error_json = json_encode($error_arr);
      echo $error_json;
      //echo $response;
    }
} catch (Exception $e) {
    $error_arr=array('code'=>$httpcode,'message'=>'Curl Exception:'.$e->getMessage());
    $error_json = json_encode($error_arr);
    echo $error_json;
}
?>
