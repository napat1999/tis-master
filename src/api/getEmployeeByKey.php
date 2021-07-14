<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

$queryKey = isset($_GET["q"])?strtoupper($_GET["q"]):'';
$apiURL="https://app.jasmine.com/jpmapi/employee/v1/search/".$queryKey;
//echo $apiURL."<br/>";
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
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode==200) {
      $responseArray=json_decode($response,true);
      $activeResponse=array();
      foreach ($responseArray as $key=>$value) {
        if($value[active]) {
          array_push($activeResponse,$value);
        }
      }
      $response_json=json_encode($activeResponse);
      //Format result for select2
      $response_json='{"pagination":{"more":false},"results":'.$response_json.'}';
      echo $response_json;
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
