<?php
$host="localhost";
//$host="192.168.1.7";
$db="training";
$userDB="root";
$paswordDB="";
$timeout=array(
        PDO::ATTR_TIMEOUT => "5",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
function pgQuery($sql) {
    //Returning a result set as a PDOStatement object
    global $host;
    global $db;
    global $userDB;
    global $paswordDB;
    global $timeout;

    try {
        $myPDO = new PDO("mysql:host=$host;dbname=$db",$userDB,$paswordDB);
        $result=$myPDO->query($sql);
        if ($result == false) {
            //$response_json='The SQL query failed with error '.$myPDO->errorCode;
            $error_arr=array('code'=>'403','message'=>'Query Exception:');
            $response_json = json_encode($error_arr);
        } else {
            $response_json=array_merge(array("code"=>"200"),$result->fetchAll(PDO::FETCH_ASSOC));
            $response_json=json_encode($response_json);
        }
        $myPDO=null;
        return $response_json;
    } catch (PDOException $e) {
        //echo "Error : " . $e->getMessage() . "<br/>";
        $error_arr=array('code'=>'403','message'=>'PDO Exception:'.$e->getMessage());
        $response_json = json_encode($error_arr);
        return $response_json;
    }
}

function pgExecute($sql) {
    //Return the number of affected rows, false on error
    //echo"pgExecute()";
    global $host;
    global $db;
    global $userDB;
    global $paswordDB;
    global $timeout;

    try {
        $lastId="";
        $myPDO = new PDO("mysql:host=$host;dbname=$db", $userDB,$paswordDB,$timeout);
        $result = $myPDO->exec($sql);
        
        if (substr($sql,0,6)=="insert") { //Get insert id if inserted
          $lastId = $myPDO->lastInsertId();
        }

        if ($result == false) {
            //echo 'The SQL query failed with error '.$myPDO->errorCode;
            $error_arr=array('code'=>'403','message'=>'Query Exception');
            $response_json = json_encode($error_arr);
        } else {
            if($result>0) {
                $response_json=array('code'=>'200','message'=>$lastId);
                $response_json=json_encode($response_json);
            } else {
                $error_arr=array('code'=>'403','message'=>'Qurey OK : 0 row effected');
                $response_json = json_encode($error_arr);
            }
        }

        $myPDO=null;
        //echo"$response_json";

        return $response_json;
    } catch (PDOException $e) {
        //echo "Error : " . $e->getMessage() . "<br/>";
        $error_arr=array('code'=>'403','message'=>'PDO Exception:'.$e->getMessage());
        $response_json = json_encode($error_arr);
    }
}

function prepareString($str) {
    if($str=="") {
        return "NULL";
    }
    return "'".$str."'";
}

function prepareNumber($value) {
  //remove comma
  $value=str_replace(",","",$value);
	$value=trim($value);
	if (is_numeric($value)) {
		return $value;
	} else {
		return 0;
	}
}

function prepareDate($d,$format) {
	if(!$d) {
		return 'NULL';
	}
	//Replace Short Thai month
	$ThaiMon= array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$EngMon= array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
	$d=str_replace($ThaiMon,$EngMon,$d);
	$date=date_create_from_format($format,$d);
	if ($date) {
		return "'".date_format($date,'Y-m-d')."'";
	} else {
		return 'NULL';
	}
}

function prepareDateTime($dt,$format) {
	if(!$dt) {
		return 'NULL';
	}
  list($d, $t) = explode(' ', $dt);
	//Replace Short Thai month
	$ThaiMon= array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$EngMon= array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
	$d=str_replace($ThaiMon,$EngMon,$d);
	$date=date_create_from_format($format,$d);
	if ($date) {
		return "'".date_format($date,'Y-m-d')." ".$t."'";
	} else {
		return 'NULL';
	}
}

function DisplayDate($d,$format) {
	if(!$d) {
		return "";
	}
	//Replace Short Thai month
	$ThaiMon= array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$EngMon= array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
	$d=str_replace($ThaiMon,$EngMon,$d);
	$date=date_create_from_format($format,$d);
	if ($date) {
		return date_format($date,'d/m/Y');
	} else {
		return "";
	}
}

function DisplayDateTime($dt,$format) {
	if(!$dt) {
		return "";
	}
  list($d, $t) = explode(' ', $dt);
  //remove seconds
  $t=substr($t,0,5);
	//Replace Short Thai month
	$ThaiMon= array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$EngMon= array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
	$d=str_replace($ThaiMon,$EngMon,$d);
	$date=date_create_from_format($format,$d);
	if ($date) {
		return date_format($date,'d/m/Y')." ".$t;
	} else {
		return "";
	}
}

function DisplayDateRejectTime($dt,$format) {
	if(!$dt) {
		return "";
	}
  list($d, $t) = explode(' ', $dt);
  //remove seconds
  $t=substr($t,0,5);
	//Replace Short Thai month
	$ThaiMon= array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	$EngMon= array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
	$d=str_replace($ThaiMon,$EngMon,$d);
	$date=date_create_from_format($format,$d);
	if ($date) {
		return date_format($date,'d/m/Y');
	} else {
		return "";
	}
}
?>
