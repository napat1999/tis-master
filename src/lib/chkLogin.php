<?php
require_once('db.php');
$_SESSION["CurURL"]=getCurURL();
//$accessToken=check_login(""); //Bypass Login
$accessToken = "0e646560-525d-47d9-922e-978d1e160553" ;
$_SESSION["employee_id"]="1883";
if ($_SESSION["employee_id"]!="") {
  //Check permission
  $employee_id = strtoupper($_SESSION["employee_id"]);
  //$employeeNo = 'RO1234'; //TEST

  unset($_SESSION["isDBConnected"]);
  unset($_SESSION["isadmin"]);
  unset($_SESSION["istraininghq"]);
  unset($_SESSION["istrainingro"]);
  unset($_SESSION["iscoordinator"]);

  //Fix admin for developer
  if($employee_id=="1883") {
    $_SESSION["isadmin"]="1";
  }
  //get right
  $sql="select * from tisusers where employeeNo='".$employee_id."'";
  $result=json_decode(pgQuery($sql),true);
  if($result['code']=="200") {
    $_SESSION["isDBConnected"]=1;
    $isFound=0;
    for($i=0;$i<count($result)-1;$i++) {
      $isFound=1;
      $_SESSION["isuser"]=1;
      $_SESSION["isadmin"]=$result[$i]['isadmin'];
      $_SESSION["istraininghq"]=$result[$i]['istraininghq'];
      $_SESSION["istrainingro"]=$result[$i]['istrainingro'];
      $_SESSION["iscoordinator"]=$result[$i]['iscoordinator'];
    }
  }
}

function restrict($permission,$code) {
  //Admin pass all rules
  if ($_SESSION["isadmin"]=="1") {
    return;
  }
  switch ($permission) {
    case 'istraining':
      if ($_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
        return;
      }
      break;
    case 'iscoordinator':
      if ($_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1" or $_SESSION["iscoordinator"]=="1") {
        return;
      }
      break;
    case 'isuser':
      if ($_SESSION["isuser"]=="1") {
        return;
      }
      break;
    default:
      break;
  }
  if ($_SESSION["isDBConnected"]=="1") {
    $url="forbidden.php?permission=".$permission."&code=".$code;
    header("Location: ".$url);
  } else {
    $url="errorDB.php";
    header("Location: ".$url);
  }
}
?>
