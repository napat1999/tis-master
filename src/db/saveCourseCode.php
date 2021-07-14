<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$codeID = isset($_POST["codeID"])?$_POST["codeID"]:'';
$codeName = isset($_POST["codeName"])?strtoupper($_POST["codeName"]):'';
$codeDescription = isset($_POST["codeDescription"])?$_POST["codeDescription"]:'';

if($codeID=="") { //Insert mode
    //Check Before inserted
    $sql="select count(*) cnt from paramcode where codename=".prepareString($codeName );
    $result=json_decode(pgQuery($sql),true);
    if($result[0]['cnt']==0) {
        //Not found insert
        $sql="insert into paramcode ";
        $sql.="(codename,codedescription)";
        $sql.=" values(";
        $sql.=prepareString($codeName).",";
        $sql.=prepareString($codeDescription).")";
        $result=pgExecute($sql);
        echo $result;

         // $error_arr=array('code'=>'999','message'=>$sql);
         // $response_json = json_encode($error_arr);
         // echo $response_json;
    } else {
        $error_arr=array('code'=>'999','message'=>'การบันทึกผิดพลาด อาจมี code ซ้ำในระบบ');
        $response_json = json_encode($error_arr);
        echo $response_json;
    }

} else { //Update mode
    $sql="update paramcode set ";
    $sql.="codename=".prepareString($codeName).",";
    $sql.="codedescription=".prepareString($codeDescription)." ";
    $sql.="where codeid=".$codeID."";
    $result=pgExecute($sql);
    echo $result;
     // $error_arr=array('code'=>'999','message'=>$sql);
     // $response_json = json_encode($error_arr);
     // echo $response_json;
}
?>
