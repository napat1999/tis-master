<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$tagID = isset($_POST["tagID"])?$_POST["tagID"]:'';
$tagName = isset($_POST["tagName"])?$_POST["tagName"]:'';
$tagDescription = isset($_POST["tagDescription"])?$_POST["tagDescription"]:'';

if($tagID=="") { //Insert mode
    //Check Before inserted
    $sql="select count(*) cnt from paramtag where tagname=".prepareString($tagName );
    $result=json_decode(pgQuery($sql),true);
    if($result[0]['cnt']==0) {
        //Not found insert
        $sql="insert into paramtag ";
        $sql.="(tagname,tagdescription)";
        $sql.=" values(";
        $sql.=prepareString($tagName).",";
        $sql.=prepareString($tagDescription).")";
        $result=pgExecute($sql);
        echo $result;

         // $error_arr=array('code'=>'999','message'=>$sql);
         // $response_json = json_encode($error_arr);
         // echo $response_json;
    } else {
        $error_arr=array('code'=>'999','message'=>'การบันทึกผิดพลาด อาจมี tag ซ้ำในระบบ');
        $response_json = json_encode($error_arr);
        echo $response_json;
    }

} else { //Update mode
    $sql="update paramtag set ";
    $sql.="tagname=".prepareString($tagName).",";
    $sql.="tagdescription=".prepareString($tagDescription)." ";
    $sql.="where tagid=".$tagID."";
    $result=pgExecute($sql);
    echo $result;
     // $error_arr=array('code'=>'999','message'=>$sql);
     // $response_json = json_encode($error_arr);
     // echo $response_json;
}
?>
