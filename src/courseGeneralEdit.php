<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraining","courseGeneral"); //If need permission enable here
include_once("lib/myLib.php");

//Initial form value
$courseID = isset($_GET["courseID"])?$_GET["courseID"]:'';
$status=-1;
if($courseID<>"") {
  $sql="select * from course where courseid=".$courseID;
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $isFound=1;
    $coursemasterid=$result[$i]['coursemasterid'];
    $courseGen=$result[$i]['coursegen'];
    $nameOfficial=$result[$i]['nameofficial'];
    $nameMarketing=$result[$i]['namemarketing'];
    //$schedule=$result[$i]['schedule'];
    $courseHour = $result[$i]["coursehour"];
    $dateApplyBegin = DisplayDate($result[$i]['dateapplybegin'],'Y-m-d');
    $dateApplyEnd = DisplayDate($result[$i]['dateapplyend'],'Y-m-d');
    $minuteTrain = $result[$i]["minutetrain"];
    $objective = $result[$i]["objective"];
    $content = html_entity_decode($result[$i]["content"]);
    $requirement = $result[$i]["requirement"];
    $courseRemark = $result[$i]["courseremark"];
    $status = $result[$i]["status"];
    $approxstudent = $result[$i]["approxstudent"];
    $approxhead = number_format($result[$i]["approxhead"]);
    $approxtotal = number_format($result[$i]["approxtotal"]);
    $budget = $result[$i]["budget"];
    $siteid = $result[$i]["siteid"];
    $trainerid = $result[$i]["trainerid"];
    $trainerArray=explode(",",$trainerid);
    $tagid = $result[$i]["taglist"];
    $tagArray=explode(",",$tagid);
    $createby = $result[$i]["createby"];
    $createdate = $result[$i]['createdate'];
    $lastupdate = $result[$i]["lastupdate"];
  }
  if($isFound==0) { //Wrong access_token
    $url="error.php?code=course";
    header("Location: ".$url);
  }
} else {
  //Prevent warning in_array()
  $trainerArray=array();
  $tagArray=array();
}

//Find create by
if($createby<>"") {
  $sql="select * from tisusers where employeeno='".$createby."'";
  $result=json_decode(pgQuery($sql),true);
  for($i=0;$i<count($result)-1;$i++) {
    $createbyname=$result[$i]['thai_name'];
  }
}

$btnSaveDisable="disabled"; //Default no save
//Enable save only when inset, before approve
switch ($status) {
  case '-1':
    $btnSaveDisable="";
    break;
  case '0':
    $btnSaveDisable="";
    break;
  case '1':
    //Enable change only for HQ
    if ($_SESSION["istraininghq"]=="1") {
      $btnSaveDisable="";
    }
    break;
  default:
    // code...
    break;
}
// if($status=="-1" or $status=="0") { //Insert mode or prepare
//   $btnSaveDisable="";
// }
//admin always can change
if ($_SESSION["isadmin"]=="1") {
  $btnSaveDisable="";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Course</title>
	<?php include_once("basicHeader.php");?>
	<style>
		#script-warning {
			display: none;
			background: #eee;
			border-bottom: 1px solid #ddd;
			padding: 0 10px;
			line-height: 40px;
			text-align: center;
			font-weight: bold;
			font-size: 12px;
			color: red;
		}

		/* Safari */
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
        }
    .date-picker-wrapper {
     z-index: 1100 !important;
    }
    .nopaddingRight {
		   padding-right: 0px !important;
		   margin-right: 0px !important;
		}
		.nopadding {
		   padding-left: 0px !important;
		   padding-right: 0px !important;
		}
		.paddingButton{
		   padding-left: 3px !important;
		}
    input:required {
      border-color: #f28d68;
    }
    .slrequire span.select2-selection {
      border-color: #f28d68  !important;
    }

    .select2-container {
      height: 34px !important;;
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
    }

.select2-container--default .select2-selection--single .select2-selection__rendered {



      width: 100%;
      height: 34px !important;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143 !important;
      vertical-align: bottom !important;


}
.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 34px !important;
    user-select: none;
    -webkit-user-select: none;


}
    </style>
    <link href="lib/summernote-0.8.11/summernote.css" rel="stylesheet">
    <!-- <link href="lib/simple-time-input/timingfield.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="lib/jquery-date-range-picker-0.20.0/css/daterangepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="lib/jquery-timepicker-1.11.14/jquery.timepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="lib/bootstrap-datepicker-1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/timeline.css" rel="stylesheet" />
  </head>
  <body>
    <div id="wrap">
			<?php $menu="course" ?>
			<?php $submenu="courseGeneral.php" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>

                <ol class="breadcrumb">
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fa fa-fw fa-book"></i> หลักสูตร
                    </a>
                  </li>
                  <li >
                    <a href="courseGeneral.php">
                      <i class="fa fa-fw fa-tasks"></i>  หลักสูตรทั่วไป
                    </a>
                  </li>
                  <li class="active">

                    <i class="fab fa-audible"></i> หลักสูตรอบรม

                  </li>
                </ol>
              </header>
              <div class="body">
							<div id='script-warning'></div>
              <div id='loading'>
                <div class="loading-backdrop">
                </div>
                <div class="loading-img">
                    <img src="assets/img/Loading-tis.gif" width="400px"/>
                </div>
              </div>
              	<div class="row" style="">
                  <form id="formCourseBasic" name="formCourseBasic" class="form-horizontal">
                    <input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
                    <input type="hidden" id="courseGen" name="courseGen" value="<?php echo $courseGen;?>">
                    <div class="col-md-8">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <i class="fab fa-accusoft"></i> ข้อมูลหลักสูตร เบื้องต้น
                      </div>
                      <div class="panel-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">ID ชื่อหลักสูตร</label>
                            <div class="col-sm-9">
                              <select class="form-control" id="courseMasterID" name="courseMasterID" style="margin-left:20px;">
                                <option></option>
                                <?php
                                if($coursemasterid==0) {
                                  echo "<option selected";
                                } else {
                                  echo "<option";
                                }
                                echo " value='0'>ไม่ตรงกับหลักสูตรที่มีอยู่</option>";
                                $sql="select cm.*,pc.codename,pc.codedescription from coursemaster cm,paramcode pc where cm.coursecodeid=pc.codeid";
                                $sql.=" order by courseid,courselevel,coursenumber";
                                $result=json_decode(pgQuery($sql),true);
                                $activeCode="";
                                for($i=0;$i<count($result)-1;$i++) {
                                  $currentCode=$result[$i]['codename'];
                                  $fullCode=str_pad($result[$i]['courselevel'],2,'0',STR_PAD_LEFT)."-".str_pad($result[$i]['coursenumber'],3,'0',STR_PAD_LEFT)."-".str_pad($result[$i]['coursesequence'],1,'0',STR_PAD_LEFT);
                                  if($currentCode<>$activeCode) { //Start group
                                    if($activeCode<>"") { //Close if not begining
                                      echo "</optgroup>\n";
                                    }
                                    echo "<optgroup label='$currentCode'>\n";
                                    $activeCode=$currentCode;
                                  }
                                  if($result[$i]['courseid']==$coursemasterid) {
                                    echo "<option selected";
                                  } else {
                                    echo "<option";
                                  }
                                  echo " value='".$result[$i]['courseid']."'>";
                                  echo $currentCode.$fullCode." ".$result[$i]['nameofficial']."</option>\n";
                                }
                                echo "</optgroup>\n";
                                ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">ชื่อหลักสูตร<br><small class="text-info">(ทางการ)</small></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="ชื่อหลักสูตร (ทางการ)"
                                id="nameOfficial" name="nameOfficial" value="<?php echo $nameOfficial;?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ชื่อหลักสูตร<br><small class="text-info">(ประชาสัมพันธ์)</small></label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ชื่อหลักสูตร (ประชาสัมพันธ์)"
                            id="nameMarketing" name="nameMarketing" value="<?php echo $nameMarketing;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">กำหนดการโดยประมาณ</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="กำหนดการโดยประมาณ"
                            id="schedule" name="schedule" value="<?php echo $schedule;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">กำหนดการ</label>
                          <div class="col-sm-9">
                              <div class="col-sm-12 nopadding">
                                <button type="button" class="btn btn-info btn-sm" id="btnAddCourseDateTime" data-toggle="modal" data-target="#CourseDateTimeModal" title="เพิ่มกำหนดการ">
                                <i class="fas fa-calendar-plus"></i> เพิ่มกำหนดการ
                                </button>
                              </div>
                              <div class="col-sm-12 nopadding" id="divCourseDateTime" style="padding-top:10px;">
                                <?php
                                $sql="select * from courseschedule where courseId=".$courseID;
                                $result=json_decode(pgQuery($sql),true);
                                if($result['code']=="200") {
                                  for($i=0;$i<count($result)-1;$i++) {
                                    if($i==0) {
                                      echo '
                                      <div class="col-sm-12 nopadding" style="border-bottom: 2px solid #ddd;">
                                      <div class="col-sm-4 nopadding">
                                        <span class="text-success">เริ่มต้น</span>
                                      </div>
                                      <div class="col-sm-4 nopaddingRight">
                                        <span class="text-success">สิ้นสุด</span>
                                      </div>
                                      <div class="col-sm-4 paddingButton text-success">
                                        เวลา
                                      </div>
                                      </div>
                                      ';
                                    }
                                    echo '
                                    <div class="col-sm-12 nopadding" style="padding-top: 5px;">
                                    <div class="col-sm-4 nopadding btn-xs" style="font-size:10pt;">
                                      <span>'.DisplayDateTime($result[$i]['datebegin'],'Y-m-d').'</span>
                                    </div>
                                    <div class="col-sm-4 nopaddingRight btn-xs" style="font-size:10pt;">
                                      <span>'.DisplayDateTime($result[$i]['dateend'],'Y-m-d').'</span>
                                    </div>
                                    <div class="col-sm-3 paddingButton btn-xs" style="font-size:10pt;">
                                      <span>'.minToText($result[$i]['roundmins']).'</span>
                                    </div>
                                    <div class="col-sm-1 paddingButton" style="font-size:10pt;">
                                      <input name="dateCourseBegin[]" id="dateCourseBegin[]" type="hidden" value="'.DisplayDateTime($result[$i]['datebegin'],'Y-m-d').'">
                                      <input name="dateCourseEnd[]" id="dateCourseEnd[]" type="hidden" value="'.DisplayDateTime($result[$i]['dateend'],'Y-m-d').'">
                                      <input name="diffMin[]" id="diffMin[]" type="hidden" value="'.$result[$i]['roundmins'].'">
                                      <button type="button" class="remove_button btn btn-danger btn-xs" id="btnRemoveBeginEnd">
                                      <i class="fas fa-trash-alt fa-xs"></i>
                                      </button>
                                    </div>
                                    </div>
                                    ';
                                  }
                                }
                                ?>

                              </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ชั่วโมงฝึกอบรมทีแนะนำ</label>
                          <div class="col-sm-9">
                            <span class="col-sm-8" id="coursehour" name="coursehour" style="padding-top:10px;">
                            </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ชั่วโมงฝึกอบรมที่เลือก</label>
                          <div class="col-sm-9 " id="divMinuteTrain" style="
                            padding-top: 7px;
                            font-weight: 200;
                            color: slateblue;
                            ">
                            <span id="minuteTrainDisplay" name="minuteTrainDisplay">
                            <?php echo minToText($minuteTrain);?>
                            </span>
                            <input type="hidden" id="minuteTrain" name="minuteTrain" value="<?php echo $minuteTrain;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">วัตถุประสงค์</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="วัตถุประสงค์"
                            id="objective" name="objective" value="<?php echo $objective;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">เนื้อหา</label>
                          <div class="col-sm-9">
                            <textarea id="course_content" name="course_content"><?php echo $content;?></textarea>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">หมายเหตุหลักสูตร</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="หมายเหตุหลักสูตร"
                            id="courseRemark" name="courseRemark" value="<?php echo $courseRemark;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">คุณสมบัติผู้เข้าอบรม</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="คุณสมบัติผู้เข้าอบรม"
                            id="requirement" name="requirement" value="<?php echo $requirement;?>">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="control-label col-sm-3">ผู้เข้าอบรม/รุ่น</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ผู้เข้าอบรมต่อรุ่น"
                            id="approxstudent" name="approxstudent" value="<?php echo $approxstudent;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ค่าใช้จ่าย/คน</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ค่าใช้จ่าย/คน"
                            id="approxhead" name="approxhead" value="<?php echo $approxhead;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ประมาณการค่าใช้จ่าย</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="ประมาณการค่าใช้จ่าย"
                            id="approxtotal" name="approxtotal" value="<?php echo $approxtotal;?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">งบประมาณ</label>
                          <div class="col-sm-9">
                            <label class="radio-inline"><input type="radio" name="budget" value="1" <?php if($budget=="1" or $budget=="") echo "checked";?>>ต้นสังกัด</label>
                            <label class="radio-inline"><input type="radio" name="budget" value="2" <?php if($budget=="2") echo "checked";?>>ส่วนฝึกอบรม</label>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">สถานที่จัดอบรม</label>
                          <div class="col-sm-9 slrequire">
                            <select id="siteid" name="siteid" class="form-control" required>
                              <option></option>
                              <?php
                              $sql="select siteid,sitero,siteprovince || ':' || sitename as sitename from trainingsite order by sitero,sitename";
                              $result=json_decode(pgQuery($sql),true);
                              $activeRO="";
                              for($i=0;$i<count($result)-1;$i++) {
                                $currentRO=$result[$i]['sitero'];
                                if($currentRO<>$activeRO) { //Start group
                                  if($activeRO<>"") { //Close if not begining
                                    echo "</optgroup>\n";
                                  }
                                  echo "<optgroup label='RO $currentRO'>\n";
                                  $activeRO=$currentRO;
                                }
                                if($result[$i]['siteid']==$siteid) {
                                  echo "<option selected";
                                } else {
                                  echo "<option";
                                }
                                echo " value='".$result[$i]['siteid']."'>";
                                echo $result[$i]['sitename']."</option>\n";
                              }
                              echo "</optgroup>\n";
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">วิทยากร</label>
                          <div class="col-sm-9">
                            <select id="trainerid" name="trainerid[]" class="form-control" multiple="multiple">
                              <?php
                              $sql="select trainerid,th_initial || ' ' || thai_name as trainerName,coalesce(department,'วิทยากรภายนอก') as department";
                              $sql.=" from trainer order by department";
                              $result=json_decode(pgQuery($sql),true);
                              $activeRO="";
                              for($i=0;$i<count($result)-1;$i++) {
                                $currentRO=$result[$i]['department'];
                                if($currentRO<>$activeRO) { //Start group
                                  if($activeRO<>"") { //Close if not begining
                                    echo "</optgroup>\n";
                                  }
                                  echo "<optgroup label='$currentRO'>\n";
                                    $activeRO=$currentRO;
                                  }
                                  if(in_array($result[$i]['trainerid'],$trainerArray)) {
                                    echo "<option selected";
                                  } else {
                                    echo "<option";
                                  }
                                  echo " value='".$result[$i]['trainerid']."'>";
                                  echo $result[$i]['trainername']."</option>\n";
                                }
                                echo "</optgroup>\n";
                                ?>
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="control-label col-sm-3">Tag</label>
                            <div class="col-sm-9">
                              <select id="tagid" name="tagid[]" class="form-control" multiple="multiple">
                                <?php
                                $sql="select tagid,tagname,tagdescription";
                                $sql.=" from paramtag order by tagname";
                                $result=json_decode(pgQuery($sql),true);
                                for($i=0;$i<count($result)-1;$i++) {
                                  if(in_array($result[$i]['tagid'],$tagArray)) {
                                    echo "<option selected";
                                  } else {
                                    echo "<option";
                                  }
                                  echo " value='".$result[$i]['tagid']."' title='".$result[$i]['tagdescription']."'>";
                                  echo $result[$i]['tagname']."</option>\n";
                                }
                                ?>
                                </select>
                              </div>
                          </div>
                          <?php
                          if($courseID<>"") {
                          ?>
                          <div class="form-group row">
                            <label class="control-label col-sm-3">ปรับปรุงข้อมูลล่าสุด</label>
                            <div class="col-sm-9" style="padding-top: 7px;">
                              <span class="col-sm-8"><?php echo date('d/m/Y H:i:s', strtotime($lastupdate));?></span>
                            </div>
                          </div>
                          <?php
                          }
                          ?>
                        </div> <!--End panel body-->
                      </div> <!--End panel Left-->
                      <div class="form-group col-md-2">
                        <a href="courseGeneral.php"  class="btn btn-default btn-sm">
                          <i class="fas fa-arrow-circle-left"></i> กลับ
                        </a>
                        </div>
                      <div class="form-group" align="center">
                        <button type="submit" class="btn btn-success btn-sm" id="btnSave" <?php echo $btnSaveDisable;?>>
                          <i class="fas fa-save"></i> บันทึกข้อมูล
                        </button>
                        <button id="btnCancel" type="reset" class="btn btn-danger btn-sm">
                          <i class="fas fa-times-circle"></i> ยกเลิก
                        </button>
                      </div>
                    </div> <!--End Left MD-->
                  </form>
                  <!--Start Right MD-->
                  <div class="col-md-4">
                    <!--Verify part-->
                    <?php
                    if($status!="-1") { //Update mode
                    ?>
                    <form id="formCourseRequest" name="formCourseRequest">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-sitemap"></i> การขออนุมัติ
                      </div>
                      <div class="panel-body" style="padding-left: 30px;">
                        <div class="form-group">
                          <label>สถานะ</label> : <?php echo statusText($status)?>
                        </div>
                        <div class="timeline">
                          <div class="line"></div>
                          <?php
                          $panelcolor="panel-success";
                          ?>
                          <!-- Training RO -->
                          <article class="panel <?php echo $panelcolor;?>">
                              <div class="panel-heading icon">
                                  <i class="fas fa-plus"></i>
                              </div>
                              <div class="panel-body">
                                <label>สร้างหลักสูตร</label><br/>
                                <?php
                                  echo "<span class='timelineMemo'>".$createbyname."</span><br/>";
                                ?>
                                <span class="timelineMemo"><?php echo date('d/m/Y H:i:s', strtotime($createdate));?></span>
                              </div>
                          </article>
                          <?php
                            $panelcolor="";
                            if($status=="0") {
                              $panelcolor="panel-info";
                            } else {
                              $panelcolor="panel-success";
                            }
                          ?>
                          <article class="panel <?php echo $panelcolor;?>">
                          <div class="panel-heading icon">
                            <i class="fas fa-expand"></i>
                          </div>
                          <div class="panel-body">
                              <label>ขอตรวจสอบหลักสูตร</label><br/>
                              <?php
                              if($status=="0") {//Waiting for request approve
                                  //Get all trainerhq email
                                  $sendto="";
                                  $sql="select * from tisusers where istraininghq=true";
                                  $result=json_decode(pgQuery($sql),true);
                                  if($result['code']=="200") {
                                    for($i=0;$i<count($result)-1;$i++) {
                                      $sendto=$sendto.$result[$i]['email'].",";
                                    }
                                  }
                                  $sendto=rtrim($sendto,",");
                                  $ccto = "";
                                  $subject = "TIS : ขอตรวจสอบหลักสูตร ".$nameOfficial;
                                  $bodyhtml="
                                  <h4>เรียน เจ้าหน้าที่ที่เกี่ยวข้อง</h4>
                                  <br>
                                  มีการขอตรวจสอบหลักสูตร : ".$nameOfficial."<br/><br/><br/>
                                  กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>
                                  <a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>Login</a>
                                  <br><br><HR>
                                  TIS : Training Information System
                                  ";
                              ?>
                                  <button type="button" class="btn btn-primary btn-sm" id="btnRequestVerify">
                                    <i class="fas fa-share"></i> ขอตรวจสอบหลักสูตร
                                  </button>
                              <?php
                                //End case enableVerify
                                } else {
                                  //Show course log
                                  if($status>"0") {
                                    $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                    $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=1";
                                    $result=json_decode(pgQuery($sql),true);
                                    if($result['code']=="200") {
                                      $thai_name=$result[0]['thai_name'];
                                      $logupdate=$result[0]['logupdate'];
                                      echo "<span class='timelineMemo'>".$thai_name."</span><br/>";
                                      echo "<span class='timelineMemo'>".date('d/m/Y H:i:s', strtotime($logupdate))."</span>";
                                    }
                                  }
                                }
                              ?>
                            </div>
                            </article>
                          <?php
                            $panelcolor="";
                            if($status=="1") {
                              $panelcolor="panel-info";
                            } else {
                              if($status<"1") {
                                $panelcolor="panel-default";
                              } else {
                                $panelcolor="panel-success";
                              }
                            }
                          ?>
                          <div class="separator"></div>
                          <!-- Training HQ-->
                          <article class="panel <?php echo $panelcolor;?>">
                              <div class="panel-heading icon">
                                  <i class="fas fa-check-double"></i>
                              </div>
                              <div class="panel-body">
                                <label>ตรวจสอบขั้นต้น</label><br/>
                                <?php
                                if($status=="1") {//Waiting for request approve
                                    $ccto = "";
                                    //cc back to owner
                                    $sql="select * from tisusers where employeeno='".$createby."'";
                                    $result=json_decode(pgQuery($sql),true);
                                    if($result['code']=="200") {
                                      $userro=$result[0]['userro'];
                                      for($i=0;$i<count($result)-1;$i++) {
                                        $ccto=$ccto.$result[$i]['email'].",";
                                      }
                                    }
                                    //cc back to all in ro
                                    $sql="select * from tisusers where employeeno<>'".$createby."' and userro='".$userro."' and istrainingro='1'";
                                    $result=json_decode(pgQuery($sql),true);
                                    if($result['code']=="200") {
                                      for($i=0;$i<count($result)-1;$i++) {
                                        $ccto=$ccto.$result[$i]['email'].",";
                                      }
                                    }
                                    $ccto=rtrim($ccto,",");
                                    $subject = "TIS : ขออนุมัติหลักสูตร ".$nameOfficial;
                                    $bodyhtml="
                                    <br>
                                    &nbsp;&nbsp;ขออนุมัติการอบรม : ".$nameOfficial."<br/><br/><br/>
                                    กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>
                                    <a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>Login</a>
                                    <br><br><HR>
                                    TIS : Training Information System
                                    ";
                                    if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1") {
                                ?>
                                    <button type="button" class="btn btn-primary btn-sm" id="btnVerify">
                                      <i class="fas fa-envelope"></i> ยืนยัน/ ขออนุมัติ
                                    </button>
                                <?php
                                    }  else { //Else not admin&hq
                                      echo "รอ Training HQ ยืนยันหลักสูตร";
                                    }
                                  //End case enableVerify
                                  } else {
                                    //Get course log
                                    if($status>"1") {
                                      switch ($budget) {
                                        case '1':
                                          $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                          $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=2";
                                          break;
                                        case '2':
                                          $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                          $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=3";
                                          break;
                                        default:
                                      }

                                      $result=json_decode(pgQuery($sql),true);
                                      if($result['code']=="200") {
                                        $thai_name=$result[0]['thai_name'];
                                        $logupdate=$result[0]['logupdate'];
                                        echo "<span class='timelineMemo'>".$thai_name."</span><br/>";
                                        echo "<span class='timelineMemo'>".date('d/m/Y H:i:s', strtotime($logupdate))."</span>";
                                      }
                                    } else {
                                      echo "Training HQ";
                                    }
                                  }
                                ?>
                              </div>
                          </article>
                          <?php
                            //Always show not depend on status
                            $DirectorIcon="";
                            $DirectorLabel="";
                            switch ($budget) {
                              case '1':
                                $DirectorIcon="<i class='fas fa-thumbs-up'></i>";
                                $DirectorLabel="<label>Approve</label>";
                                break;
                              case '2':
                                $DirectorIcon="<i class='fas fa-check-double'></i>";
                                $DirectorLabel="<label>Verify2</label>";
                                break;
                              default:
                                // code...
                                break;
                            }

                            //Show depend on status
                            $panelcolor="";
                            if($status=="2" or $status=="3") {
                              $panelcolor="panel-info";
                              $subject = "TIS : ผลการอนุมัติหลักสูตร ".$nameOfficial;

                              $rejecthtml="
                              <h4>ผลการอนุมัติหลักสูตร</h4>
                              <br>
                              หลักสูตร : ".$nameOfficial." ที่คุณทำการขออนุมัติผ่านระบบ TIS<br/>
                              ผลการพิจารณา : <span style='color:red'>ไม่ผ่านการอนุมัติ</span><br/><br/>
                              กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>
                              <a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>Login</a>
                              <br><br><HR>
                              TIS : Training Information System
                              ";

                              switch ($budget) {
                                case '1':
                                  $sendto = "";
                                  //send back to owner
                                  $sql="select * from tisusers where employeeno='".$createby."'";
                                  $result=json_decode(pgQuery($sql),true);
                                  if($result['code']=="200") {
                                    $userro=$result[0]['userro'];
                                    for($i=0;$i<count($result)-1;$i++) {
                                      $sendto=$sendto.$result[$i]['email'].",";
                                    }
                                  }
                                  $sendto=rtrim($sendto,",");
                                  //cc back to all in ro
                                  $sql="select * from tisusers where employeeno<>'".$createby."' and userro=".$userro."' and istrainingro='1'";
                                  $result=json_decode(pgQuery($sql),true);
                                  if($result['code']=="200") {
                                    for($i=0;$i<count($result)-1;$i++) {
                                      $ccto=$ccto.$result[$i]['email'].",";
                                    }
                                  }
                                  $ccto=rtrim($ccto,",");
                                  $approvehtml="
                                  <h4>ผลการอนุมัติหลักสูตร</h4>
                                  <br>
                                  หลักสูตร : ".$nameOfficial." ที่คุณทำการขออนุมัติผ่านระบบ TIS<br/>
                                  ผลการพิจารณา : <span style='color:green'>ผ่านการอนุมัติ</span><br/><br/>
                                  กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>
                                  <a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>Login</a>
                                  <br><br><HR>
                                  TIS : Training Information System
                                  ";
                                  break;
                                case '2':
                                  $sendto = "phatnaree@jasmine.com";
                                  $ccto = "";
                                  //send back to owner
                                  $sql="select * from tisusers where employeeno='".$createby."'";
                                  $result=json_decode(pgQuery($sql),true);
                                  if($result['code']=="200") {
                                    $userro=$result[0]['userro'];
                                    for($i=0;$i<count($result)-1;$i++) {
                                      $ccto=$ccto.$result[$i]['email'].",";
                                    }
                                  }
                                  $sendto=rtrim($sendto,",");
                                  //cc back to all in ro
                                  $sql="select * from tisusers where employeeno<>'".$createby."' and userro=".$userro."' and istrainingro='1'";
                                  $result=json_decode(pgQuery($sql),true);
                                  if($result['code']=="200") {
                                    for($i=0;$i<count($result)-1;$i++) {
                                      $ccto=$ccto.$result[$i]['email'].",";
                                    }
                                  }
                                  $ccto=rtrim($ccto,",");

                                  $approvehtml="
                                  <h4>ผลการอนุมัติหลักสูตร</h4>
                                  <br>
                                  หลักสูตร : ".$nameOfficial." ที่คุณทำการขออนุมัติผ่านระบบ TIS<br/>
                                  ผลการพิจารณา : <span style='color:green'>ผ่านการอนุมัติ</span><br/><br/>
                                  กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>
                                  <a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>Login</a>
                                  <br><br><HR>
                                  TIS : Training Information System
                                  ";
                                  break;
                                default:
                                  // code...
                                  break;
                              }
                            } else {
                              if($status<"2") {
                                $panelcolor="panel-default";
                              } else {
                                $panelcolor="panel-success";
                              }
                            }


                          ?>
                          <div class="separator"></div>
                          <!-- Director -->
                          <article class="panel <?php echo $panelcolor;?>">
                              <div class="panel-heading icon">
                                <?php echo $DirectorIcon;?>
                              </div>
                              <div class="panel-body">
                                <?php echo $DirectorLabel?><br/>
                                <?php
                                if(($budget=="1" and $status<"21") or ($budget=="2" and $status<"31"))  { //try get arprove name from api
                                ?>
                                <span id="approvero-position" name="approvero-position" class='timelineMemo'>
                                  loading...
                                </span><br/>
                                <span id="approvero-name" name="approvero-name" class='timelineMemo'>
                                  loading...
                                </span><br/>
                                <?php
                                }//end show approvero api
                                ?>
                                <?php
                                //Check status to show button
                                if ($status=="2" or $status=="3") {
                                  //Check right to enable click
                                  if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1") {
                                ?>
                                  <button type="button" class="btn btn-primary btn-sm" id="btnDirectorApprove" style="width:100%;text-align:center;margin-bottom: 7px;margin-top: 5px;">
                                    <i class="fas fa-thumbs-up"></i> อนุมัติหลักสูตร
                                  </button>
                                  <button type="button" class="btn btn-outline btn-danger btn-sm" id="btnDirectorReject" style="width:100%;text-align:center">
                                    <i class="fas fa-thumbs-down"></i> ไม่อนุมัติหลักสูตร
                                  </button>

                                <?php
                                  } else { //Else check right
                                    echo "รออนุมัติหลักสูตร";
                                  }
                                } else { //Else check status
                                  //Get course log
                                  if(($budget=="1" and $status>="21") or ($budget=="2" and $status>="31")) {
                                    if($status=="22" or $status=="32") {
                                      echo "<span style='color:red'>ไม่อนุมัติ</span><br/>";
                                      switch ($budget) {
                                        case '1':
                                          $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                          $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=22";
                                          break;
                                        case '2':
                                        $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                        $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=32";
                                          break;
                                        default:
                                      }
                                    } else {
                                      switch ($budget) {
                                        case '1':
                                          $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                          $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=21";
                                          break;
                                        case '2':
                                        $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                        $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=31";
                                          break;
                                        default:
                                      }
                                    }//end log description

                                    $result=json_decode(pgQuery($sql),true);
                                    if($result['code']=="200") {
                                      $thai_name=$result[0]['thai_name'];
                                      $logupdate=$result[0]['logupdate'];
                                      echo "<span class='timelineMemo'>".$thai_name."</span><br/>";
                                      echo "<span class='timelineMemo'>".date('d/m/Y H:i:s', strtotime($logupdate))."</span>";
                                    }
                                  } //end log description
                                }
                                ?>
                              </div>
                          </article>

                          <?php
                          if($budget=="2") {
                            //Show depend on status
                            $panelcolor="";
                            if($status=="31") {
                              $panelcolor="panel-info";
                              $subject = "TIS : ผลการอนุมัติหลักสูตร ".$nameOfficial;
                              $rejecthtml="<h3>ผลการอนุมัติหลักสูตร</h3>";
                              $rejecthtml.="<div>\n";
                              $rejecthtml.="หลักสูตร ".$nameOfficial." ที่คุณทำการขออนุมัติผ่านระบบ TIS<br/>";
                              $rejecthtml.="ผลการพิจารณา : <span style='color:red'>ไม่ผ่านการอนุมัติ</span><br/>";
                              $rejecthtml.="กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>";
                              $rejecthtml.="<a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>";
                              $rejecthtml.="Login";
                              $rejecthtml.="</a>";
                              $rejecthtml.="</div>";
                              $sendto = "phatnaree@jasmine.com";
                              $ccto = "";
                              //send back to owner
                              $sql="select * from tisusers where employeeno='".$createby."'";
                              $result=json_decode(pgQuery($sql),true);
                              if($result['code']=="200") {
                                $userro=$result[0]['userro'];
                                for($i=0;$i<count($result)-1;$i++) {
                                  $ccto=$ccto.$result[$i]['email'].",";
                                }
                              }
                              //cc back to all in ro
                              $sql="select * from tisusers where employeeno<>'".$createby."' and userro=".$userro."' and istrainingro='1'";
                              $result=json_decode(pgQuery($sql),true);
                              if($result['code']=="200") {
                                for($i=0;$i<count($result)-1;$i++) {
                                  $ccto=$ccto.$result[$i]['email'].",";
                                }
                              }
                              $ccto=rtrim($ccto,",");

                              $approvehtml="<h3>ขออนุมัติหลักสูตร</h3>";
                              $approvehtml.="<div>\n";
                              $approvehtml.="หลักสูตร ".$nameOfficial." ผ่านการอนุมัติขั้นต้นแล้ว จากระบบ TIS<br/>";
                              $approvehtml.="กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>";
                              $approvehtml.="<a href='https://app.jasmine.com/tis/courseGeneralEdit.php?courseID=".$courseID."'>";
                              $approvehtml.="Login";
                              $approvehtml.="</a>";
                              $approvehtml.="</div>";

                            } else {
                              if($status<"31") {
                                $panelcolor="panel-default";
                              } else {
                                $panelcolor="panel-success";
                              }
                            }
                          ?>
                          <div class="separator"></div>
                          <!-- Final -->
                          <article class="panel <?php echo $panelcolor;?>">
                              <div class="panel-heading icon">
                                <i class='fas fa-thumbs-up'></i>
                              </div>
                              <div class="panel-body">
                                <label>Approve</label><br/>
                                <?php
                                //Check status to show button
                                if ($status=="31") {
                                  //Check right to enable click
                                  if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1") {
                                ?>
                                  <button type="button" class="btn btn-primary btn-sm" id="btnFinalApprove" style="width:120px;text-align:left">
                                    <i class="fas fa-thumbs-up"></i> อนุมัติหลักสูตร
                                  </button>
                                  <button type="button" class="btn btn-danger btn-sm" id="btnFinalReject" style="width:120px;text-align:left">
                                    <i class="fas fa-thumbs-down"></i> ไม่อนุมัติหลักสูตร
                                  </button>
                                <?php
                                  } else { //Else check right
                                    echo "รออนุมัติหลักสูตร";
                                  }
                                } else { //Else check status
                                  //Get course log

                                  if($status>"32") {
                                    if($status=="34") {
                                      echo "<span style='color:red'>ไม่อนุมัติ</span><br/>";
                                      $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                      $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=34";
                                    } else {
                                      $sql="select log.*,users.thai_name from courselog log left join tisusers users";
                                      $sql.=" on log.updateby=users.employeeno where courseid=".$courseID." and status=33";
                                    }

                                    $result=json_decode(pgQuery($sql),true);
                                    if($result['code']=="200") {
                                      $thai_name=$result[0]['thai_name'];
                                      $logupdate=$result[0]['logupdate'];
                                      echo "<span class='timelineMemo'>".$thai_name."</span><br/>";
                                      echo "<span class='timelineMemo'>".date('d/m/Y H:i:s', strtotime($logupdate))."</span>";
                                    }
                                  } //end log description
                                }
                                ?>
                              </div>
                          </article>
                        <?php } //end Final?>
                        </div> <!--end timeline-->
                      </div> <!--End panel body-->
                    </div> <!--End panel Left-->
                    <input type="hidden" id="sendto" name="sendto" value="<?php echo $sendto;?>">
                    <input type="hidden" id="ccto" name="ccto" value="<?php echo $ccto;?>">
                    <input type="hidden" id="subject" name="subject" value="<?php echo $subject;?>">
                    <input type="hidden" id="bodyhtml" name="bodyhtml" value="<?php echo $bodyhtml;?>">
                    <input type="hidden" id="approvehtml" name="approvehtml" value="<?php echo $approvehtml;?>">
                    <input type="hidden" id="rejecthtml" name="rejecthtml" value="<?php echo $rejecthtml;?>">
                    </form> <!-- End formCourseRequest-->
                    <?php
                      }// End insert mode checked
                    ?>

                    <!--Start Apply part-->
                    <?php
                    if($status=="21" or $status=="33" or $status=="40") { //Apply state
                    ?>
                      <form id="formCourseApply" name="formCourseApply">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <i class="fas fa-edit"></i> เปิดรับสมัคร/จัดสรร
                          </div>
                          <div class="panel-body" style="padding-bottom: 0px;">
                            <div class="form-group row">
                              <input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
                              <div class="col-sm-12">
                                <span id="dateApply" class="input-group">
                                  <input name="dateApplyBegin" id="dateApplyBegin" class="form-control" readonly="true" value="<?php echo $dateApplyBegin;?>">
                                  <span class="input-group-addon" style="border:0px;background-color: #fff;"> to </span>
                                  <input name="dateApplyEnd" id="dateApplyEnd" class="form-control" readonly="true" value="<?php echo $dateApplyEnd;?>">
                                </span>
                              </div>
                            </div>
                            <div class="form-group" align="center">
                              <button type="submit" class="btn btn-success btn-sm" id="btnStartApply">
                                <i class="fas fa-save"></i> บันทึกวันรับสมัคร
                              </button>
                            </div>

                          </div> <!--End panel body-->


                        </div> <!--End panel Left-->

                      </form>
                      <div class="panel panel-info">

                        <div class="panel-body" style="padding-bottom: 0px;">
                          <div class="form-group" align="center">
                            <button type="button" class="btn btn-primary btn-sm" id="btnStartAllocate"
                              <?php if ($dateApplyBegin=="") { echo "style='display:none'";}?>>
                              <i class="fas fa-person-booth"></i> มอบหมายผู้จัดสรร
                            </button>
                            <?php
                            //Have course info next check right to allocate
                            $sql="select * from courseAllocate ";
                            $sql.="where employeeNo='".$_SESSION["employee_id"]."' and courseId=".$courseID;
                            $resultRight=json_decode(pgQuery($sql),true);
                            $hasQuota=0;
                            for($i=0;$i<count($resultRight)-1;$i++) {
                              $hasQuota=1;
                            }
                            ?>
                            <button type="button" class="btn btn-warning btn-sm" id="btnInviteStudent"
                              <?php if ($hasQuota=="0") { echo "disabled";}?>
                              <?php if ($dateApplyBegin=="") { echo "style='display:none'";}?>>
                              <i class="fas fa-user-plus"></i> เลือกผู้เข้าอบรม
                            </button>
                          </div>
                        </div>
                      </div> <!--End panel body-->




                    <?php
                      }//End case Apply State
                    ?>

                    <?php if($status!="-1") {?>
                    <div id="debugmail" style="background-color:#EEEEEE;font-size:10px">
                      Debug (Delete when published) status=<?php echo $status?><br/>
                    </div>
                  <?php }?>
                              </div> <!--End Right MD-->
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- end .inner -->
                    </div>

                    <!-- end .outer -->
                  </div>

                  <!-- end #content -->
                </div><!-- /#wrap -->
                <div id="footer">
                  <?php include_once("footer.php");?>
                </div>
                <!-- Modal -->
                <div id="popupModal" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <br/>
                      </div>
                      <div class="modal-body">
                        <div id="modalContent"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="CourseDateTimeModal" class="modal fade" role="dialog" data-backdrop="static">
                  <div class="modal-dialog" style="width:400px;">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header MyModalHeader">
                        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
          						<h5 class="modal-title" id="dataModalTitle"><i class="fas fa-calendar-plus"></i> เพิ่มกำหนดการ</h5>
                      </div>
                      <div class="modal-body form-horizontal">
                        <div class="form-group row ">
                          <label class="control-label col-sm-3">เริ่มต้น</label>
                          <div class="col-sm-9">
                            <div class="col-sm-12 nopadding">
                              <span class="input-group">
                                <input name="dateBeginPicker" id="dateBeginPicker" class="form-control date start" style="cursor:pointer;" readonly="true">
                                <span class="input-group-addon" style="border:0px;"><i class="fas fa-clock" title="ระบุเวลา"></i></span>
                                <input name="timeBeginPicker" id="timeBeginPicker" class="form-control time start" />
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">สิ้นสุด</label>
                          <div class="col-sm-9">
                            <div class="col-sm-12 nopadding">
                              <span class="input-group">
                                <input name="dateEndPicker" id="dateEndPicker" class="form-control date end" readonly="true" style="cursor:pointer;">
                                <span class="input-group-addon" style="border:0px;"><i class="fas fa-clock" title="ระบุเวลา"></i></span>
                                <input name="timeEndPicker" id="timeEndPicker" type="text" class="form-control time end" />
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:0px;">
                          <div class="col-sm-12" align="right">
                            <button type="button" class="btn btn-success btn-sm" id="btnAddDateTime" data-dismiss="modal" disabled="true">
                              <i class="fas fa-calendar-plus"></i> เพิ่มกำหนดการ
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                            <input name="diffMinPicker" id="diffMinPicker" type="hidden"/>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- End Modal -->

                <?php include_once("notification.php");?>
                <script src="assets/lib/jquery.min.js"></script>
                <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
                <script src="assets/lib/screenfull/screenfull.js"></script>
                <script src="assets/js/main.min.js"></script>
                <script src="lib/summernote-0.8.11/summernote.js"></script>
                <script src="lib/summernote-0.8.11/lang/summernote-th-TH.js"></script>
                <!-- <script src="lib/simple-time-input/timingfield.js"></script> -->
                <script src="lib/select2-4.0.5/js/select2.min.js"></script>
                <script src="lib/Moment 2.24.0/moment.min.js"></script>
                <script src="lib/jquery-date-range-picker-0.20.0/js/jquery.daterangepicker.min.js"></script>
                <script src="lib/datepair-0.4.16/datepair.min.js"></script>
                <script src="lib/datepair-0.4.16/jquery.datepair.min.js"></script>
                <script src="lib/jquery-timepicker-1.11.14/jquery.timepicker.min.js"></script>
                <script src="lib/js/inputFilter.js"></script>
                <script src="lib/bootstrap-datepicker-1.8.0/js/bootstrap-datepicker.min.js"></script>
                <script src="lib/bootstrap-datepicker-1.8.0/locales/bootstrap-datepicker.th.min.js"></script>
                <script src="lib/bootbox-5.1.3/bootbox.js"></script>
                <script src="assets/js/tisApp.js"></script>
                <script src="lib/js/decodeEntities.js"></script>
                <script type="text/javascript">

                $( "#btnCancel" ).click(function( event ) {
            			event.preventDefault();
            			bootbox.confirm({
            				title: "กรุณายืนยัน ยกเลิกการบันทึกข้อมูล ?",
            				backdrop: true,closeButton: false,
            				message: "คุณต้องการยกเลิกการบันทึกข้อมูล</br>และกลับสู่เมนูก่อนหน้า",
            				size: 'small',
            				animate: true,
            				centerVertical:true,
            				className:"confirmDelete bootbox-confirm",
            				buttons: {
            					confirm: {
            							label: '<i class="fa fa-check "></i> ยืนยัน',
            							className:'btn btn-success btn-sm'
            					},
            						cancel: {
            								label: '<i class="fa fa-times"></i> ยกเลิก',
            								className:'btn btn-danger btn-sm'
            						}

            				},
            				callback: function (result) {
            					if(result){
            					window.location.href = 'courseGeneral.php';
            					}

            				}
            			});
            		});
                  function minToText(min) {
                    var hours=Math.floor(min / 60);
                    if(hours>0) {
                      var hoursText=hours+" ชม.";
                    }
                    var minutes=min%60;
                    var minutesText='';
                    if(minutes>0) {
                      minutesText=minutes+" นาที";
                    }
                    return hoursText+" "+minutesText;
                  }

                  function calculateHour() {
                    var total=0;
                    $('input[name="diffMin[]"]').each(function() {
                      if($(this).val()!="") {
                        total += parseInt($(this).val());
                      }
                    });

                    if(total>0) {
                      $('#minuteTrainDisplay').text(minToText(total));
                      var miniuteInfo=`
                      <ul>
                      <li>หากต่อเนื่องเกิน 4 ชม. จะพัก 1 ชม.</li>
                      <li>หากมีการอบรมข้ามวัน เวลาเริ่มต้นจะเป็น 08:00 สิ้นสุด 17:00</li>
                      </ul>
                      `;
                      $('#minuteTrainDisplay').append(miniuteInfo);
                    } else {
                      $('#divCourseDateTime').empty();
                      $('#minuteTrainDisplay').empty();
                    }
                    $('#minuteTrain').val(total);
                  }

                  function popup(msg) {
                    $('#modalContent').html(msg);
                    //$('#modalContent').text(msg);
                    $('#popupModal').modal('show');
                  }
                  <?php
                  if ($courseID<>"") {
                    ?>
                    function updateStatus(status,remark) {
                      //Update status
                      var formData = {
                        'courseID' : <?php echo $courseID;?>,
                        'status' : status,
                        'remark' : remark
                      };
                      $.ajax({
                        type: "POST",
                        url: "db/updateStatus.php",
                        data: formData,
                        beforeSend: function()
                        {
                          $('#loading').show();
                        },
                        success: function(result){
                          try {
                            var obj = JSON.parse(result);
                            if(obj.code=="200") {
                              $('#loading').hide();
                              tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','small','courseGeneralEdit.php',false);
                            //  popup("Status updated.");

                            } else {
                              $('#loading').hide();
                              tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Error :'+obj.message,'error','small','',false);
                          //    popup(obj.message);
                              //$('#debug').html(obj.message);
                            //  $('#loading').hide();
                            }

                          } catch (err) {
                            $('#loading').hide();
                            tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Update status Error : API return unknow json','error','small','',false);
                          //  popup("Update status Error : API return unknow json");
                          //  alert('update status error'+result);

                          }
                          //location.reload();
                        },
                        error: function()
                        {
                          tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Cannot call save api','error','small','',false);
                        //  popup("Cannot call save api");
                          $('#loading').hide();
                        }
                      });
                    }
                    <?php
                  } //End status 0
                  ?>
                  function formatNumber(num) {
                    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
                  }

                  function Master2General() {
                    var courseMasterID=$('#courseMasterID').val();
                    $.ajax({
                      type: "POST",
                      url: "api/getCourseMaster.php",
                      data: {
                        courseMasterID: courseMasterID,
                      },
                      beforeSend: function()
                      {
                        $('#loading').show();
                      },
                      success: function(result){
                        //console.log(result);
                        var obj = JSON.parse(result, function (key, value) {
                          switch(key) {
                            case 'nameofficial':
                              $('#nameOfficial').val(value);
                              break;
                            case 'namemarketing':
                              if(value!="") {
                                $('#nameMarketing').val(value);
                              } else {
                                $('#nameMarketing').val($('#nameOfficial').val());
                              }
                              break;
                            //case 'schedule': $('#schedule').val(value); break;
                            case 'coursehour': $('#coursehour').text(value); break;
                            case 'objective': $('#objective').val(value); break;
                            case 'content':
                              //console.log(value);
                              //console.log(decodeEntities(value));
                              value=decodeEntities(value);
                              $('#course_content').summernote('code',value);
                              break;
                            case 'requirement': $('#requirement').val(value); break;
                            case 'courseremark': $('#courseRemark').val(value); break;
                            case 'approxstudent': $('#approxstudent').val(value); break;
                            // case 'approxhead': $('#approxhead').val(formatNumber(value)); break;
                            // case 'approxtotal': $('#approxtotal').val(formatNumber(value)); break;
                            case 'trainerid':
                              $.each(value.split(","), function(i,e){
                                  $("#trainerid option[value='" + e + "']").prop("selected", true);
                              });
                              $('#trainerid').select2({
          					            placeholder: 'เลือกวิทยากรฝึกอบรม'
          					          });
                              break;
                            case 'taglist':
                              $.each(value.split(","), function(i,e){
                                  $("#tagid option[value='" + e + "']").prop("selected", true);
                              });
                              $('#tagid').select2({
                                placeholder: 'เลือก tag สำหรับค้นหา'
                              });
                              break;
                            default:
                          }
                        });
                        $('#loading').hide();
                      },
                      error: function()
                      {
                        popup("Cannot call API");
                        $('#loading').hide();
                      }
                    });
                  }

                  function checkTimeDiff(){
                    var datediff=0;
                    if ($('#dateBeginPicker').val() && $('#dateEndPicker').val() && $('#timeBeginPicker').val() && $('#timeEndPicker').val()) {
                      //All data complete enable button
                      $('#btnAddDateTime').prop('disabled', false);

                      var startD = $("#dateBeginPicker").datepicker('getDate');
                      var startPick = new Date(startD);
                      var endD = $("#dateEndPicker").datepicker('getDate');
                      var endPick = new Date(endD);
                      var diffD = parseInt((endPick.getTime()-startPick.getTime())/(24*3600*1000));

                      if (diffD==0) {
                        //Same date time diff = direct value
                        var diffMS = $('#CourseDateTimeModal').datepair('getTimeDiff');
                        var diffMins=diffMS/60000;
                        //Minus break hours
                        if (diffMins>240) {
                          diffMins=diffMins-60;
                        }
                        $("#diffMinPicker").val(diffMins);
                      } else {
                        var diffMins=0;
                        for (i = 0; i <= diffD; i++) {
                          var startD = $("#dateBeginPicker").datepicker('getDate');
                          startD.setDate(startD.getDate() + i);
                          if(i==0) {
                            var startT=$('#timeBeginPicker').val();
                          } else {
                            var startT="08:00";
                          }
                          //var endD = $("#dateEndPicker").datepicker('getDate');
                          if(i==diffD) {
                            var endT=$('#timeEndPicker').val();
                          } else {
                            var endT="17:00";
                          }
                          var startD = new Date(Date.prototype.setHours.apply(new Date(startD), startT.split(':')));
                          var endD = new Date(Date.prototype.setHours.apply(new Date(startD), endT.split(':')));
                          var diffMinsDay = parseInt((endD.getTime()-startD.getTime())/(60*1000));
                          //Minus break hours
                          if (diffMinsDay>240) {
                            diffMinsDay=diffMinsDay-60;
                          }
                          diffMins += diffMinsDay;
                        }
                        $("#diffMinPicker").val(diffMins);
                      }

                    }

                  }

                  function showDebug() {
                    $("#debugmail").append('to :<span style="background-color:#E0E0FF">'+$("#sendto").val()+'</span><br/>');
                    $("#debugmail").append('cc :<span style="background-color:#E0E0FF">'+$("#ccto").val()+'</span><br/>');
                    $("#debugmail").append('subject :<span style="background-color:#E0E0FF">'+$("#subject").val()+'</span><br/>');
                    $("#debugmail").append('<div style="background-color:#E0E0FF">'+$("#bodyhtml").val()+'</div><br/>');
                  }

                  $(document).ready(function() {
                    calculateHour();

                    $('#formCourseBasic').on('reset', function(e)
                    {
                      //Clear schedule
                      $('#divCourseDateTime').empty();
                      $('#minuteTrainDisplay').empty();
                    });

                    $('#CourseDateTimeModal .date').datepicker({
                       format: 'dd/mm/yyyy',
                       language: "th",
                       autoclose: true,
                       todayHighlight: true,
                       maxViewMode: 2,
                    });

                    $('#CourseDateTimeModal .time').timepicker({
                        'timeFormat': 'H:i',
                        'showDuration': false,
                        'disableTextInput':true,
                        'scrollDefault': '08:00',
                    });

                    $('#CourseDateTimeModal').on('rangeSelected', function(){
                        checkTimeDiff();
                    });

                    // initialize datepair
                    var msInHour=3600000;
                    var timeDelta=msInHour*9;
                    $('#CourseDateTimeModal').datepair({
                      'defaultTimeDelta': timeDelta,
                    });

                    $('#course_content').summernote({
                      lang: 'th-TH',
                      height:150,
                      minHeight:80,
                      followingToolbar: false,
                      toolbar: [
                      // [groupName, [list of button]]
                      ['misc',['undo','redo']],
                      ['style', ['bold', 'italic', 'underline', 'clear']],
                      ['fontsize', ['fontsize','color']],
                      ['para', ['ul', 'ol', 'paragraph']],
                      ['height', ['height']],
                      ['insert',['hr','table']],
                      ['misc',['fullscreen']]
                      ]
                    });
                    $('#courseMasterID').select2({
					            placeholder: 'เลือกหลักสูตร'
					          });

                    <?php
                      //Content เปลี่ยนตาม ID เฉพาะตอนสร้าง
                      if($status=="-1") {
                    ?>
                    $('#courseMasterID').on('change', function() {
                      if ($('#courseMasterID').val()==0) {
                        $("#formCourseBasic").trigger('reset');
                        $('#nameOfficial').prop('readonly', false);
                      } else {
                        $('#nameOfficial').prop('readonly', true);
                        Master2General();
                      }
					          });
                    <?php
                      }
                    ?>
                    $('#siteid').select2({
                      placeholder: "เลือกสถานที่จัดอบรม"
                    });
                    $('#trainerid').select2({
                      placeholder: 'เลือกวิทยากรฝึกอบรม'
                    });
                    $('#tagid').select2({
                      placeholder: 'เลือก tag สำหรับค้นหา'
                    });
                    //Save
                    $("#formCourseBasic").submit(function(e) {
                      event.preventDefault();
                      var minuteTrain=$('#minuteTrain').val();
                      if(minuteTrain=="0" || minuteTrain=="") {
                        popup("ยังไม่เลือกกำหนดการอบรม");
                        $('#btnAddCourseDateTime').focus();
                        return;
                      }
                      $.ajax({
                        type: "POST",
                        url: "db/saveCourseGeneral.php",
                        data: $("#formCourseBasic").serialize(),
                        beforeSend: function()
                        {
                          $('#loading').show();
                        },
                        success: function(result){
                          try {
                            var obj = JSON.parse(result);
                            if(obj.code=="200") {
                            //  popup("Completed save.");
                              $('#loading').hide();
                              <?php
                              if($status=="-1") {
                                //Redirect to list if inserted
                              //  echo "location.href='courseGeneral.php';";
                                  echo "var IsRedirect=true;";
                              } else {
                                //Reload if updated
                              //  echo "location.reload();";
                                    echo "var IsRedirect=false;";
                              }
                              ?>
	                             tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','small','courseGeneral.php',IsRedirect);
                            } else {
                                tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>'+obj.message,'error','small','',false);
                            //  popup(obj.message);
                              //$('#debug').html(obj.message);
                              $('#loading').hide();
                            }
                          } catch (err) {
                            //alert('form submit'+result);
                            tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Save Error : API return unknow json','error','small','',false);
                        //    popup("Save Error : API return unknow json");
                            $('#loading').hide();
                          }
                          //location.reload();
                        },
                        error: function()
                        {
                          tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Save Error : Cannot call api','error','small','',false);
                        //  popup("Save Error : Cannot call api");
                          $('#loading').hide();
                        }
                      });
                    });

                    $("#btnAddDateTime").click(function(){
                      var dateBeginPicker=$("#dateBeginPicker").val();
                      var dateEndPicker=$("#dateEndPicker").val();
                      var timeBeginPicker=$("#timeBeginPicker").val();
                      var timeEndPicker=$("#timeEndPicker").val();
                      var diffMinPicker=$("#diffMinPicker").val();
                      var addBeginEnd=`
                      <div class="col-sm-12 nopadding" style="padding-top: 5px;">
                      <div class="col-sm-4 nopadding btn-sm" style="font-size:10pt;">
                        <span>`+dateBeginPicker+` `+timeBeginPicker+`</span>
                      </div>
                      <div class="col-sm-4 nopaddingRight btn-sm" style="font-size:10pt;">
                        <span>`+dateEndPicker+` `+timeEndPicker+`</span>
                      </div>
                      <div class="col-sm-3 paddingButton btn-sm" style="font-size:10pt;">
                        <span>`+minToText(diffMinPicker)+`</span>
                      </div>
                      <div class="col-sm-1 paddingButton" style="font-size:10pt;">
                        <input name="dateCourseBegin[]" id="dateCourseBegin[]" type="hidden" value="`+dateBeginPicker+` `+timeBeginPicker+`">
                        <input name="dateCourseEnd[]" id="dateCourseEnd[]" type="hidden" value="`+dateEndPicker+` `+timeEndPicker+`">
                        <input name="diffMin[]" id="diffMin[]" type="hidden" value="`+diffMinPicker+`">
                        <button type="button" class="remove_button btn btn-danger btn-xs" id="btnRemoveBeginEnd">
                        <i class="fas fa-trash-alt fa-xs"></i>
                        </button>
                      </div>
                      </div>
                      `;
                      if ($("#divCourseDateTime").text().trim()=="") {
                        //Add header if begin
                        var addHeader=`
                        <div class="col-sm-12 nopadding" style="border-bottom: 2px solid #ddd;">
                        <div class="col-sm-4 nopadding">
                          <span class="text-success">เริ่มต้น</span>
                        </div>
                        <div class="col-sm-4 nopaddingRight">
                          <span class="text-success">สิ้นสุด</span>
                        </div>
                        <div class="col-sm-4 paddingButton text-success">
                          เวลา
                        </div>
                        </div>
                        `;
                        $("#divCourseDateTime").append(addHeader);
                      }
                      $("#divCourseDateTime").append(addBeginEnd);
                      calculateHour();

                    });
                    $('#divCourseDateTime').on('click', '.remove_button', function(e){
                      e.preventDefault();
                      $(this).parent('div').parent('div').remove(); //Remove field html
                      calculateHour();
                    });

                    <?php
                    if ($status=="0") {
                    ?>
                      $("#btnRequestVerify").click(function(){
                        bootbox.confirm({
                          closeButton: false,
                          title:"กรุณายืนยัน การขอตรวจสอบหลักสูตร ?",
                            message: "หลักสูตร "+$('#nameMarketing').val(),
                            size: 'small',
                            animate: true,
                            centerVertical:true,
                            className:"alertInfo bootbox-confirm",
                            buttons: {
                                confirm: {
                                    label: '<i class="fas fa-check"></i> ยืนยัน',
                                    className: 'btn-success btn-sm'
                                },
                                cancel: {
                                    label: '<i class="fas fa-times"></i> ยกเลิก',
                                    className: 'btn-danger btn-sm'
                                }
                            },
                            callback: function (confirmresult) {
                              if(confirmresult){
                        var formData = {
                            'sendto': $('#formCourseRequest input[name=sendto]').val(),
                            'ccto': $('#formCourseRequest input[name=ccto]').val(),
                            'subject': $('#formCourseRequest input[name=subject]').val(),
                            'bodyhtml': $('#formCourseRequest input[name=bodyhtml]').val()
                        };
                        //Disable resend
                        $('#btnRequestVerify').prop('disabled', true);
                        //Disable change if request Approve
                        $('#btnSave').prop('disabled', true);
                        $.ajax({
                          type: "POST",
                          url: "api/sendMail.php",
                          data: formData,
                          beforeSend: function()
                          {
                            $('#loading').show();
                          },
                          success: function(result){
                            try {
                              var obj = JSON.parse(result);
                              if(obj.code=="200") {
                                //popup("Request sended.");
                                updateStatus(1,"");
                                //$('#loading').hide();
                              //  location.reload();
                              } else {

                                tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Error :'+obj.message,'error','small','',false);
                              //  popup(obj.message);
                                //$('#debug').html(obj.message);
                                $('#loading').hide();
                              }
                            } catch (err) {
                              tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Request approve Error : API return unknow json','error','small','',false);
                            //  popup("Request approve Error : API return unknow json");
                              $('#loading').hide();
                            }
                            //location.reload();
                          },
                          error: function()
                          {
                            tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Cannot call save api','error','small','',false);
                            //popup("Cannot call save api");
                            $('#loading').hide();
                          }
                        });

                        }
                        }
                      });
                      });
                    <?php
                      } // end status0
                    ?>
                    <?php
                    if ($status=="1") {
                    ?>
                      $("#btnVerify").click(function(){

                        bootbox.confirm({
                          closeButton: false,
                          title:"กรุณายืนยัน การตรวจสอบหลักสูตร ?",
                            message: "หลักสูตร "+$('#nameMarketing').val(),
                            size: 'small',
                            animate: true,
                            centerVertical:true,
                            className:"alertInfo bootbox-confirm",
                            buttons: {
                                confirm: {
                                    label: '<i class="fas fa-check"></i> ยืนยัน',
                                    className: 'btn-success btn-sm'
                                },
                                cancel: {
                                    label: '<i class="fas fa-times"></i> ยกเลิก',
                                    className: 'btn-danger btn-sm'
                                }
                            },
                            callback: function (confirmresult) {
                              if(confirmresult){
                        //Must complete course code ID before Verify
                        var courseMasterID=<?php echo $coursemasterid;?>;
                        if(courseMasterID==0) {
                          popup("ID ชื่อหลักสูตรยังไม่ถูกกำหนด<br/>หรือกำหนดแล้วยังไม่กดบันทึก");
                          $('#courseMasterID').focus();
                          return;
                        }
                        var approveroname=$('#approvero-name').text();
                        var bodyTarget="<h4>เรียน คุณ"+approveroname+"</h4>";
                        var formData = {
                            'sendto': $('#formCourseRequest input[name=sendto]').val(),
                            'ccto': $('#formCourseRequest input[name=ccto]').val(),
                            'subject': $('#formCourseRequest input[name=subject]').val(),
                            'bodyhtml': bodyTarget+$('#formCourseRequest input[name=bodyhtml]').val()
                        };
                        //Disable resend
                        $('#btnVerify').prop('disabled', true);

                        $.ajax({
                          type: "POST",
                          url: "api/sendMail.php",
                          data: formData,
                          beforeSend: function()
                          {
                            $('#loading').show();
                          },
                          success: function(result){
                            try {
                              var obj = JSON.parse(result);
                              if(obj.code=="200") {
                                var budget=<?php echo $budget?>;
                                if(budget==1) {
                                  updateStatus(2,"");
                                } else {
                                  updateStatus(3,"");
                                }
                                //location.reload();
                              } else {
                                popup(obj.message);
                                $('#loading').hide();
                              }
                            } catch (err) {
                              popup("Request approve Error : API return unknow json");
                              $('#loading').hide();
                            }
                            //location.reload();
                          },
                          error: function()
                          {
                            popup("Cannot call save api");
                            $('#loading').hide();
                          }
                        });

}
}
});
                      });
                    <?php
                      } // end status 1
                    ?>
                  <?php //Director state
                  if ($status=="2" or $status=="3") {
                  ?>
                    $("#btnDirectorApprove").click(function(){
                      //Using approvehtml
                      $('#formCourseRequest input[name=bodyhtml]').val($('#formCourseRequest input[name=approvehtml]').val())
                      //Disable resend
                      $('#btnDirectorApprove').prop('disabled', true);
                      $('#btnDirectorReject').prop('disabled', true);
                      $.ajax({
                        type: "POST",
                        url: "api/sendMail.php",
                        data: $("#formCourseRequest").serialize(),
                        beforeSend: function()
                        {
                          $('#loading').show();
                        },
                        success: function(result){
                          try {
                            var obj = JSON.parse(result);
                            if(obj.code=="200") {
                              var budget=<?php echo $budget?>;
                              if(budget==1) {
                                updateStatus(21,"");
                              } else {
                                updateStatus(31,"");
                              }
                            //  location.reload();
                            } else {
                              popup(obj.message);
                              //$('#debug').html(obj.message);
                              $('#loading').hide();
                            }
                          } catch (err) {
                            popup("Approve Error : API return unknow json");
                            $('#loading').hide();
                          }
                          //location.reload();
                        },
                        error: function()
                        {
                          popup("Cannot call save api");
                          $('#loading').hide();
                        }
                      });
                    });
                    $("#btnDirectorReject").click(function(){
                      //Using rejecthtml
                      $('#formCourseRequest input[name=bodyhtml]').val($('#formCourseRequest input[name=rejecthtml]').val())
                      //Disable resend
                      $('#btnDirectorApprove').prop('disabled', true);
                      $('#btnDirectorReject').prop('disabled', true);
                      $.ajax({
                        type: "POST",
                        url: "api/sendMail.php",
                        data: $("#formCourseRequest").serialize(),
                        beforeSend: function()
                        {
                          $('#loading').show();
                        },
                        success: function(result){
                          try {
                            var obj = JSON.parse(result);
                            if(obj.code=="200") {
                              var budget=<?php echo $budget?>;
                              if(budget==1) {
                                updateStatus(22,"");
                              } else {
                                updateStatus(32,"");
                              }
                            //  location.reload();
                            } else {
                              popup(obj.message);
                              //$('#debug').html(obj.message);
                              $('#loading').hide();
                            }
                          } catch (err) {
                            popup("Reject Error : API return unknow json");
                            $('#loading').hide();
                          }
                          //location.reload();
                        },
                        error: function()
                        {
                          popup("Cannot call save api");
                          $('#loading').hide();
                        }
                      });
                    });
                  <?php
                    } // end Director state
                  ?>

                  <?php //Final state
                  if ($status=="31") {
                  ?>
                    $("#btnFinalApprove").click(function(){
                      //Using approvehtml
                      $('#formCourseRequest input[name=bodyhtml]').val($('#formCourseRequest input[name=approvehtml]').val())
                      //Disable resend
                      $('#btnFinalApprove').prop('disabled', true);
                      $('#btnFinalReject').prop('disabled', true);
                      $.ajax({
                        type: "POST",
                        url: "api/sendMail.php",
                        data: $("#formCourseRequest").serialize(),
                        beforeSend: function()
                        {
                          $('#loading').show();
                        },
                        success: function(result){
                          try {
                            var obj = JSON.parse(result);
                            if(obj.code=="200") {
                              updateStatus(33,"");
                            //  location.reload();
                            } else {
                              popup(obj.message);
                              //$('#debug').html(obj.message);
                              $('#loading').hide();
                            }
                          } catch (err) {
                            popup("Final Approve Error : API return unknow json");
                            $('#loading').hide();
                          }
                          //location.reload();
                        },
                        error: function()
                        {
                          popup("Cannot call save api");
                          $('#loading').hide();
                        }
                      });
                    });
                    $("#btnFinalReject").click(function(){
                      //Using rejecthtml
                      $('#formCourseRequest input[name=bodyhtml]').val($('#formCourseRequest input[name=rejecthtml]').val())
                      //Disable resend
                      $('#btnFinalApprove').prop('disabled', true);
                      $('#btnFinalReject').prop('disabled', true);
                      $.ajax({
                        type: "POST",
                        url: "api/sendMail.php",
                        data: $("#formCourseRequest").serialize(),
                        beforeSend: function()
                        {
                          $('#loading').show();
                        },
                        success: function(result){
                          try {
                            var obj = JSON.parse(result);
                            if(obj.code=="200") {
                              updateStatus(34,"");
                              //location.reload();
                            } else {
                              popup(obj.message);
                              //$('#debug').html(obj.message);
                              $('#loading').hide();
                            }
                          } catch (err) {
                            popup("Final Reject Error : API return unknow json");
                            $('#loading').hide();
                          }
                          //location.reload();
                        },
                        error: function()
                        {
                          popup("Cannot call save api");
                          $('#loading').hide();
                        }
                      });
                    });
                  <?php
                    } // end Final state
                  ?>

                  <?php //Apply state
                  if($status=="21" or $status=="33" or $status=="40") {
                  ?>
                    $('#dateApply').dateRangePicker({
                      format: 'DD/MM/YYYY',
                      monthSelect: true,
                      autoClose: true,
                      separator: ' to ',
                  		getValue: function()
                  		{
                  			if ($('#dateApplyBegin').val() && $('#dateApplyEnd').val() )
                  				return $('#dateApplyBegin').val() + ' to ' + $('#dateApplyEnd').val();
                  			else
                  				return '';
                  		},
                  		setValue: function(s,s1,s2)
                  		{
                  			$('#dateApplyBegin').val(s1);
                  			$('#dateApplyEnd').val(s2);
                  		}
                    });

                    $("#formCourseApply").submit(function(e) {
                      event.preventDefault();
                      //return;
                      $.ajax({
                          type: "POST",
                          url: "db/saveCourseApply.php",
                          data: $("#formCourseApply").serialize(),
                          beforeSend: function()
                          {
                            $('#loading').show();
                          },
                          success: function(result){
                            try {
                              var obj = JSON.parse(result);
                              if(obj.code=="200") {
                                updateStatus(40,"");
                              //  location.reload();
                              } else {
                                popup(obj.message);
                                //$('#debug').html(obj.message);
                                $('#loading').hide();
                              }
                            } catch (err) {
                              alert('save apply error : '+result);
                              popup("Save apply Error : API return unknow json");
                              $('#loading').hide();
                            }
                            //location.reload();
                          },
                          error: function()
                          {
                            popup("Save Error : Cannot call api");
                            $('#loading').hide();
                          }
                      });
                    });
                  <?php
                    if($dateApplyBegin!="") { //check button allocate available
                  ?>
                    $("#btnStartAllocate").click(function(){
                      location.href='courseAllocate.php?courseID=<?php echo $courseID;?>';
                    });
                    $("#btnInviteStudent").click(function(){
                      location.href='courseInvite.php?courseID=<?php echo $courseID;?>';
                    });
                  <?php
                    } //end check button allocate available
                  } // end  Apply State
                  ?>
                  $("#approxstudent").inputFilter(function(value) {
                    return /^\d*$/.test(value); });
                  $("#approxhead").inputFilter(function(value) {
                    return /^-?\d*[,]?\d*$/.test(value); });
                  $("#approxtotal").inputFilter(function(value) {
                    return /^-?\d*[,]?\d*$/.test(value); });

                  //For debug
                  <?php if($status!="-1") {?>
                  showDebug();
                  <?php }?>
                }); //end document.ready

                <?php
                if(($budget=="1" and $status<"21") or ($budget=="2" and $status<"31")) {
                ?>
                //Get ApproveRO
                $(function() {
                  var createby='<?php echo $createby;?>';
                  $.ajax({
                    type: "POST",
                    url: "api/getApprovalRO.php",
                    data: {
                      createby: createby,
                      type:'open'
                    },
                    beforeSend: function()
                    {
                      $('#loading').show();
                    },
                    success: function(result){
                      try {
                        var obj = JSON.parse(result, function (key, value) {
                          switch(key) {
                            case 'employeeno':
                              //$('#approvero-employeeno').text(value);
                            //Enable for approver only (except admin)
                            <?php if ($_SESSION["isadmin"]!="1") {?>
                              if(value=="<?php echo $_SESSION["employee_id"];?>") {
                                //Approver logined
                                $('#btnDirectorApprove').prop('disabled', false);
                                $('#btnDirectorReject').prop('disabled', false);
                              } else {
                                $('#btnDirectorApprove').prop('disabled', true);
                                $('#btnDirectorReject').prop('disabled', true);
                              }
                              <?php } ?>
                              break;
                            case 'thai_name': $('#approvero-name').text(value); break;
                            case 'email':
                              <?php
                              if($status=="1") {
                              ?>
                              $('#formCourseRequest input[name=sendto]').val(value);
                              showDebug();
                              <?php }?>
                              break;
                            case 'position': $('#approvero-position').text(value); break;
                            default:
                          }
                        });
                        $('#loading').hide();
                      } catch (err) {
                        popup("Get approval error : API return unknow json");
                        $('#btnRequestApprove').prop('disabled', true);
                        $('#loading').hide();
                      }
                      //location.reload();
                    },
                    error: function()
                    {
                      popup("Save Error : Cannot call api");
                      $('#loading').hide();
                    }
                  });
                });
                <?php
                }
                ?>

                $(function() {
                  $("input[required]").each(function() {
                    if ($(this).val().length > 0 ) {
                      $(this).css('border-color', '#ccc');
                    } else {
                      $(this).css('border-color', '#f28d68');
                    }

                    $(this).on('change',function(){
                        if ($(this).val().length > 0 ) {
                          $(this).css('border-color', '#ccc');
                        } else {
                          $(this).css('border-color', '#f28d68');
                        }
                    });
                  }) //end each

                  $("select[required]").each(function() {
                    if ($(this).prop('selectedIndex') > 0 ) {
                      $('.slrequire span.select2-selection').attr('style', 'border-color: #ccc !important');
                    } else {
                      $('.slrequire span.select2-selection').attr('style', 'border-color: #f28d68 !important');
                    }

                    $(this).on('change',function(){
                        if ($(this).prop('selectedIndex') > 0 ) {
                          $('.slrequire span.select2-selection').attr('style', 'border-color: #ccc !important');
                        } else {
                          $('.slrequire span.select2-selection').attr('style', 'border-color: #f28d68 !important');
                        }
                    });
                  }) //end each
                }); //end function
          </script>
  </body>
</html>
