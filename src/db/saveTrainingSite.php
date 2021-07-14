<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$siteid = isset($_POST["siteid"])?$_POST["siteid"]:'';
$sitename = isset($_POST["sitename"])?$_POST["sitename"]:'';
$siteroom = isset($_POST["siteroom"])?$_POST["siteroom"]:'';
$sitefloor = isset($_POST["sitefloor"])?$_POST["sitefloor"]:'';
$siteprovince = isset($_POST["siteprovince"])?$_POST["siteprovince"]:'';
$sitero = isset($_POST["sitero"])?$_POST["sitero"]:'';
$contactname = isset($_POST["contactname"])?strtoupper($_POST["contactname"]):'';
$contactposition = isset($_POST["contactposition"])?$_POST["contactposition"]:'';
$contacttelephone = isset($_POST["contacttelephone"])?$_POST["contacttelephone"]:'';
$contactemail = isset($_POST["contactemail"])?$_POST["contactemail"]:'';
$siteurl = isset($_POST["siteurl"])?$_POST["siteurl"]:'';
$siteremark = isset($_POST["siteremark"])?$_POST["siteremark"]:'';
$sitelat = isset($_POST["sitelat"])?$_POST["sitelat"]:'';
$sitelong = isset($_POST["sitelong"])?$_POST["sitelong"]:'';

if($siteid=="") { //Insert mode
    //Check Before inserted
    $sql="select count(*) cnt from trainingsite ";
    $sql.=" where sitename=".prepareString($sitename);
    $sql.=" and siteroom=".prepareString($siteroom);
    $sql.=" and sitefloor=".prepareString($sitefloor);
    $result=json_decode(pgQuery($sql),true);
    if($result[0]['cnt']==0) {
        //Not found insert
        $sql="insert into trainingsite ";
        $sql.="(sitename,siteroom,sitefloor,siteprovince,sitero,contactname,contactposition,contacttelephone,contactemail,";
        $sql.="siteurl,siteremark,sitelat,sitelong,createby)";
        $sql.=" values(";
        $sql.=prepareString($sitename).",";
        $sql.=prepareString($siteroom).",";
        $sql.=prepareString($sitefloor).",";
        $sql.=prepareString($siteprovince).",";
        $sql.=prepareNumber($sitero).",";
        $sql.=prepareString($contactname).",";
        $sql.=prepareString($contactposition).",";
        $sql.=prepareString($contacttelephone).",";
        $sql.=prepareString($contactemail).",";
        $sql.=prepareString($siteurl).",";
        $sql.=prepareString($siteremark).",";
        $sql.=prepareString($sitelat).",";
        $sql.=prepareString($sitelong).",";
        $sql.=prepareString($_SESSION["employee_id"]).")";
        $result=pgExecute($sql);
        echo $result;

        // $error_arr=array('code'=>'999','message'=>$sql);
        // $response_json = json_encode($error_arr);
        // echo $response_json;
    } else {
        $error_arr=array('code'=>'999','message'=>'การบันทึกผิดพลาด อาจมีชื่อสถานที่ซ้ำในระบบ');
        $response_json = json_encode($error_arr);
        echo $response_json;
    }

} else { //Update mode
    $sql="update trainingsite set ";
    $sql.="sitename=".prepareString($sitename).",";
    $sql.="siteroom=".prepareString($siteroom).",";
    $sql.="sitefloor=".prepareString($sitefloor).",";
    $sql.="siteprovince=".prepareString($siteprovince).",";
    $sql.="sitero=".prepareString($sitero).",";
    $sql.="contactname=".prepareString($contactname).",";
    $sql.="contactposition=".prepareString($contactposition).",";
    $sql.="contacttelephone=".prepareString($contacttelephone).",";
    $sql.="contactemail=".prepareString($contactemail).",";
    $sql.="siteurl=".prepareString($siteurl).",";
    $sql.="siteremark=".prepareString($siteremark).",";
    $sql.="sitelat=".prepareNumber($sitelat).",";
    $sql.="sitelong=".prepareNumber($sitelong).",";
    $sql.="updateby=".prepareString($_SESSION["employee_id"]).",";
    $sql.="lastupdate=current_timestamp ";
    $sql.="where siteid=".$siteid."";
    $result=pgExecute($sql);
    echo $result;
    //echo $sql;
}
?>
