
<?php
include_once("../lib/myOAuth.php");
require_once('../lib/db.php');


if ($_SESSION["employee_id"] == "") {
    $response_json = array('code' => '997', 'message' => 'Session expired: กรุณา reload');
    $response_json = json_encode($response_json);
    echo $response_json;
    exit;
}
$trainer_type = isset($_POST["trainer_type"]) ? $_POST["trainer_type"] : '';
$trainerID = isset($_POST["trainerID"]) ? $_POST["trainerID"] : '';
$th_initial = isset($_POST["th_initial"]) ? $_POST["th_initial"] : '';
$thai_name = isset($_POST["thai_name"]) ? $_POST["thai_name"] : '';
$employeeNo = isset($_POST["employeeNo"]) ? strtoupper($_POST["employeeNo"]) : '';
$position = isset($_POST["position"]) ? $_POST["position"] : '';
$department = isset($_POST["department"]) ? $_POST["department"] : '';
$company = isset($_POST["company"]) ? $_POST["company"] : '';
$section = isset($_POST["section"]) ? $_POST["section"] : '';
$division = isset($_POST["division"]) ? $_POST["division"] : '';
$workplace = isset($_POST["workplace"]) ? $_POST["workplace"] : '';
$telephone = isset($_POST["telephone"]) ? $_POST["telephone"] : '';
$email = isset($_POST["email"]) ? strtolower($_POST["email"]) : '';
$trainerremark = isset($_POST["trainerRemark"]) ? strtolower($_POST["trainerRemark"]) : '';
$studyinfo = isset($_POST["studyinfo"]) ? $_POST["studyinfo"] : '';
$workinfo = isset($_POST["workinfo"]) ? $_POST["workinfo"] : '';
$traininfo = isset($_POST["traininfo"]) ? $_POST["traininfo"] : '';
$lastupdate = isset($_POST["lastupdate"]) ? $_POST["lastupdate"] : '';
$imagepath = isset($_POST["imagepath"]) ? $_POST["imagepath"] : '';
$expends = isset($_POST["expends"]) ? $_POST["expends"] : '';
$spe_courses = isset($_POST["spe_courses"]) ? $_POST["spe_courses"] : '';
$course_em = isset($_POST["course_em"]) ? $_POST["course_em"] : '';
$gen = isset($_POST["gen"]) ? $_POST["gen"] : '';
$year_train = isset($_POST["year_train"]) ? $_POST["year_train"] : '';

//echo "$a";

if ($trainerID == "") { //Insert mode
    //Check Before inserted
    $sql = "select count(*) cnt from trainer where employeeNo=" . prepareString($employeeNo);
    $result = json_decode(pgQuery($sql), true);
    $sql1 = "select MAX(trainerID) AS maxid from trainer";
    $ret = json_decode(pgQuery($sql1), true);
    $x =  $ret[0]['maxid'] + 1;

    if ($result[0]['cnt'] == 0) {
        //Not found insert
        $sql = "insert into trainer ";
        $sql .= "(th_initial,thai_name,employeeNo,position,department,company,";
        $sql .= "section,division,workplace,telephone,email,studyinfo,workinfo,traininfo,trainerRemark,expends,spe_courses,course_em,gen,year_train,createby)";
        $sql .= " values(";
        $sql .= prepareString($th_initial) . ",";
        $sql .= prepareString($thai_name) . ",";
        $sql .= prepareString($employeeNo) . ",";
        $sql .= prepareString($position) . ",";
        $sql .= prepareString($department) . ",";
        $sql .= prepareString($company) . ",";
        $sql .= prepareString($section) . ",";
        $sql .= prepareString($division) . ",";
        $sql .= prepareString($workplace) . ",";
        $sql .= prepareString($telephone) . ",";
        $sql .= prepareString($email) . ",";
        $sql .= prepareString($studyinfo) . ",";
        $sql .= prepareString($workinfo) . ",";
        $sql .= prepareString($traininfo) . ",";
        $sql .= prepareString($trainerremark) . ",";
        $sql .= prepareString($expends) . ",";
        $sql .= prepareString($spe_courses) . ",";
        $sql .= prepareString($course_em) . ",";
        $sql .= prepareString($gen) . ",";
        $sql .= prepareString($year_train) . ",";
        $sql .= prepareString($_SESSION["employee_id"]) . ")";
        //$sql.=")";
        $result = pgExecute($sql);
        echo $result;
    } else {
        $error_arr = array('code' => '999', 'message' => 'รหัสพนักงานซ้ำในระบบ');
        $response_json = json_encode($error_arr);
        echo $response_json;
    }
} else { //Update mode

    $sql = "update trainer set ";
    $sql .= "th_initial=" . prepareString($th_initial) . ",";
    $sql .= "thai_name=" . prepareString($thai_name) . ",";
    $sql .= "employeeNo=" . prepareString($employeeNo) . ",";
    $sql .= "position=" . prepareString($position) . ",";
    $sql .= "department=" . prepareString($department) . ",";
    $sql .= "company=" . prepareString($company) . ",";
    $sql .= "section=" . prepareString($section) . ",";
    $sql .= "division=" . prepareString($division) . ",";
    $sql .= "workplace=" . prepareString($workplace) . ",";
    $sql .= "telephone=" . prepareString($telephone) . ",";
    $sql .= "email=" . prepareString($email) . ",";
    $sql .= "trainerRemark=" . prepareString($trainerremark) . ",";
    $sql .=  "studyinfo=" . prepareString($studyinfo) . ",";
    $sql .=  "workinfo=" . prepareString($workinfo) . ",";
    $sql .=  "traininfo=" . prepareString($traininfo) . ",";
    $sql .=  "expends=" . prepareString($expends) . ",";
    $sql .=  "spe_courses=" . prepareString($spe_courses) . ",";
    $sql .=  "course_em=" . prepareString($course_em) . ",";
    $sql .=  "gen=" . prepareString($gen) . ",";
    $sql .=  "year_train=" . prepareString($year_train) . ",";
    $sql .= "updateby=" . prepareString($_SESSION["employee_id"]) . ",";
    $sql .= "lastupdate=current_timestamp ";
    $sql .= "where trainerID=" . $trainerID . "";
    $result = pgExecute($sql);
    echo $result;
    //echo $sql;
}
?>
