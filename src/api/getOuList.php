<?php
include_once("../lib/myOAuth.php");
include_once("../lib/chkLogin.php"); //If need login enable here

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$apiURL="https://app.jasmine.com/jpmapi/ou/details/all";
$token=$_SESSION["access_token"];
$department=$_SESSION["department"];
$company=$_SESSION["company"];

$response=$json;
$NewOu=array();
$keyOu=0;

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
      $responseArray=json_decode($response,true);
      $ouListArray=array();
      foreach ($responseArray as $key=>$value) {
          $NewOu[$keyOu]['code']  =$value['code'];
          $NewOu[$keyOu]['name'] = $value['name'];
          $NewOu[$keyOu]['head']  =$value['head_of_onit'];
          if($value['org_unit_type']['name']!='Section'){
            $NewOu[$keyOu]['Section'] = "";
            if($value['org_unit_type']['name']!='Division'){
                $NewOu[$keyOu]['Division'] = "";
            }
          }
          $NewOu[$keyOu][$value['org_unit_type']['name']] = $value['name'];
          recursive($value);
          $keyOu++;
      }
      //echo "Total found:".count($NewOu)."<br/>";
      $OUSameDept=array();
      foreach ($NewOu as $key => $value) {
        if($value['Company']==$company and $value['Department']==$department) {
          if($value['name']!=$value['Department']) {
            array_push($OUSameDept,$value);
          }
        }
        // code...
      }
      //echo json_encode($NewOu);
      echo json_encode($OUSameDept);
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


function recursive($val){
global $NewOu;
global $keyOu;
    if(isset($val['parent'])){
        if($val['parent']['org_unit_type']['name']=="Company") {
          $NewOu[$keyOu][$val['parent']['org_unit_type']['name']] = $val['parent']['short_name'];
        } else {
          $NewOu[$keyOu][$val['parent']['org_unit_type']['name']] = $val['parent']['name'];
        }

        return recursive($val['parent']);
    }
}
?>
