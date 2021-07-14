<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');

if($_SESSION["employee_id"]=="") {
  $response_json=array('code'=>'997','message'=>'Session expired: กรุณา reload');
  $response_json = json_encode($response_json);
  echo $response_json;
  exit;
}

$userID = isset($_POST["userID"])?$_POST["userID"]:'';
$th_initial = isset($_POST["th_initial"])?$_POST["th_initial"]:'';
$thai_name = isset($_POST["thai_name"])?$_POST["thai_name"]:'';
$employeeNo = isset($_POST["employeeNo"])?strtoupper($_POST["employeeNo"]):'';
$position = isset($_POST["position"])?$_POST["position"]:'';
$department = isset($_POST["department"])?$_POST["department"]:'';
$company = isset($_POST["company"])?$_POST["company"]:'';
$section = isset($_POST["section"])?$_POST["section"]:'';
$division = isset($_POST["division"])?$_POST["division"]:'';
$workplace = isset($_POST["workplace"])?$_POST["workplace"]:'';
$userro = isset($_POST["userro"])?$_POST["userro"]:'';
$telephone = isset($_POST["telephone"])?$_POST["telephone"]:'';
$email = isset($_POST["email"])?strtolower($_POST["email"]):'';
$userremark = isset($_POST["userremark"])?strtolower($_POST["userremark"]):'';
$isAdmin = isset($_POST["isAdmin"])?strtolower($_POST["isAdmin"]):'0';
$isTrainingHQ = isset($_POST["isTrainingHQ"])?strtolower($_POST["isTrainingHQ"]):'0';
$isTrainingRO = isset($_POST["isTrainingRO"])?strtolower($_POST["isTrainingRO"]):'0';
$isCoordinator = isset($_POST["isCoordinator"])?strtolower($_POST["isCoordinator"]):'0';

if($userID=="") { //Insert mode
    //Check Before inserted
    $sql="select count(*) cnt from tisusers where employeeno=".prepareString($employeeNo);
    $result=json_decode(pgQuery($sql),true);
    if($result[0]['cnt']==0) {
        //Not found insert
        $sql="insert into tisusers ";
        $sql.="(th_initial,thai_name,employeeNo,position,department,company,";
        $sql.="section,division,workplace,userro,telephone,email,userremark,";
        $sql.="isadmin,istraininghq,istrainingro,iscoordinator,createby)";
        $sql.=" values(";
        $sql.=prepareString($th_initial).",";
        $sql.=prepareString($thai_name).",";
        $sql.=prepareString($employeeNo).",";
        $sql.=prepareString($position).",";
        $sql.=prepareString($department).",";
        $sql.=prepareString($company).",";
        $sql.=prepareString($section).",";
        $sql.=prepareString($division).",";
        $sql.=prepareString($workplace).",";
        $sql.=prepareString($userro).",";
        $sql.=prepareString($telephone).",";
        $sql.=prepareString($email).",";
        $sql.=prepareString($userremark).",";
        $sql.=prepareString($isAdmin).",";
        $sql.=prepareString($isTrainingHQ).",";
        $sql.=prepareString($isTrainingRO).",";
        $sql.=prepareString($isCoordinator).",";
        $sql.=prepareString($_SESSION["employee_id"]).")";

        $result=pgExecute($sql);
        echo $result;

        // $error_arr=array('code'=>'999','message'=>$sql);
        // $response_json = json_encode($error_arr);
        // echo $response_json;
    } else {
        $error_arr=array('code'=>'999','message'=>'รหัสพนักงานซ้ำในระบบ');
        $response_json = json_encode($error_arr);
        echo $response_json;
    }

} else { //Update mode
    $sql="update tisusers set ";
    $sql.="th_initial=".prepareString($th_initial).",";
    $sql.="thai_name=".prepareString($thai_name).",";
    $sql.="employeeNo=".prepareString($employeeNo).",";
    $sql.="position=".prepareString($position).",";
    $sql.="department=".prepareString($department).",";
    $sql.="company=".prepareString($company).",";
    $sql.="section=".prepareString($section).",";
    $sql.="division=".prepareString($division).",";
    $sql.="workplace=".prepareString($workplace).",";
    $sql.="userro=".prepareString($userro).",";
    $sql.="telephone=".prepareString($telephone).",";
    $sql.="email=".prepareString($email).",";
    $sql.="userremark=".prepareString($userremark).",";
    $sql.="isadmin=".prepareString($isAdmin).",";
    $sql.="istraininghq=".prepareString($isTrainingHQ).",";
    $sql.="istrainingro=".prepareString($isTrainingRO).",";
    $sql.="iscoordinator=".prepareString($isCoordinator).",";
    $sql.="updateby=".prepareString($_SESSION["employee_id"]).",";
    $sql.="lastupdate=current_timestamp ";
    $sql.="where userid=".$userID."";
    $result=pgExecute($sql);
    echo $result;

    // $error_arr=array('code'=>'999','message'=>$sql);
    // $response_json = json_encode($error_arr);
    // echo $response_json;
}
?>
