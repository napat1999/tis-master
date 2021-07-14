<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

$ouid = isset($_POST["ouid"])?strtoupper($_POST["ouid"]):'';
if($ouid=="") { //Same OU
  $apiURL="https://app.jasmine.com/jpmapi/v1/ou/employees";
} else {
  //$apiURL="https://app.jasmine.com/contactapi/api/employee/v1/ouid/".$ouid."/all";
  $apiURL="https://app.jasmine.com/jpmapi/employee/v1/ouid/".$ouid;
}

$token=$_SESSION["access_token"];
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
        $response_json=json_decode($response,true);
        $response_json=array_merge(array("code"=>"200"),$response_json);
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
