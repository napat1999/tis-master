<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraining","courseGeneral"); //If need permission enable here
include_once("lib/myLib.php");

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
    $schedule=$result[$i]['schedule'];
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
    $createby = $result[$i]["createby"];
    $createdate = $result[$i]['createdate'];
    $lastupdate = $result[$i]["lastupdate"];
  }
  if($isFound==0) { //Wrong access_token
    $url="error.php?code=courseStudent";
    header("Location: ".$url);
  }
} else {
  $url="error.php?code=courseStudent";
  header("Location: ".$url);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Student</title>
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
		#modal-warning {
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
  <link rel="stylesheet" type="text/css" href="lib/summernote-0.8.11/summernote.css">
  <link rel="stylesheet" type="text/css" href="lib/select2-4.0.5/css/select2.min.css">

  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.css"/>
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-responsive/dataTables.responsive.css"/>
  <link rel="stylesheet" href="lib/fonts/material-design/css/material-design-iconic-font.min.css">


  <link rel="stylesheet" type="text/css" href="lib/Responsive-2.2.2/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="lib/gallery/hes-gallery-master/hes-gallery.min.css">
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
                  <li>
                      <a href="courseGeneral.php">
                      <i class="fa fa-fw fa-tasks"></i>  หลักสูตรทั่วไป
                      </a>

                  </li>
                  <li class="active">

                      <i class="fas fa-users-cog"></i>  จัดการผู้เข้ารับการอบรม

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
                <?php
                $sql="select * from coursestudent where courseid=".$courseID;
                $resultStudent=json_decode(pgQuery($sql),true);
                $studentValid=0; //0+1+2
                $studentWait=0; //0
                $studentAccepted=0; //1
                $studentRequestReject=0; //2
                $studentReject=0; //3
                $studentSuspend=0; //4
                if($resultStudent['code']=="200") {
                  //echo "Check student";
                  for($i=0;$i<count($resultStudent)-1;$i++) {
                    switch ($resultStudent[$i]['status']) {
                      case '0':
                        $studentValid++;
                        $studentWait++;
                        break;
                      case '1':
                        $studentValid++;
                        $studentAccepted++;
                        break;
                      case '2':
                        $studentValid++;
                        $studentRequestReject++;
                        break;
                      case '3':
                        $studentReject++;
                        break;
                      case '4':
                        $studentSuspend++;
                        break;
                      default:
                        break;
                    }
                  }
                }
                ?>
                <!--Course info-->
                <div class="row">
                  <div class="col-md-12">
                    <form method="post" target="_blank" id="frmOpenPDF" action="genForm1.php" style="display:none">
                			<input type="hidden" name="courseID" value="<?php echo $courseID?>" />
                      <input type="hidden" name="fillUser" id="fillUser"/>
                		</form>
                    <form id="formCourseBasic" name="formCourseBasic" class="form-horizontal">
                    <div class="panel panel-green">
                      <div class="panel-heading btn-outline" data-toggle="collapse" href="#courseInfo" style="cursor:pointer;">
                        <i class="fas fa-user-friends"></i> ข้อมูลหลักสูตร
                        <i id="toggleInfo" class="fas fa-chevron-down pull-right"></i>
                      </div>
                      <div id="courseInfo" class="panel-collapse collapse">
                        <div class="panel-body" style="background-color:#e6e4e2;">
                          <div class="row">
                            <!--Course info panel left-->
                            <div class="col-sm-8">
                              <div class="panel panel-default" style="min-height:284px;">
                                  <div class="panel-body" >
                              <div class="form-group row">
                                  <label class="control-label col-sm-4">ชื่อหลักสูตร (ทางการ)</label>
                                  <div class="col-sm-8">
                                      <span class="form-control-static col-sm-12"><?php echo $nameOfficial;?></span>
                                  </div>
                              </div>
                              <div class="form-group row">
                                <label class="control-label col-sm-4">ชื่อหลักสูตร (ประชาสัมพันธ์)</label>
                                <div class="col-sm-8">
                                  <span class="form-control-static col-sm-12"><?php echo $nameMarketing;?></span>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="control-label col-sm-4">คุณสมบัติผู้เข้าอบรม</label>
                                <div class="col-sm-8">
                                  <span class="form-control-static col-sm-12"><?php echo $requirement;?></span>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="control-label col-sm-4">กำหนดการ</label>
                                <div class="col-sm-8">
                                    <span class="form-control-static col-sm-12">
                                      <?php
                                      $sql="select * from courseschedule where courseId=".$courseID;
                                      $result=json_decode(pgQuery($sql),true);
                                      if($result['code']=="200") {
                                        for($i=0;$i<count($result)-1;$i++) {
                                          if($i==0) {
                                            echo '
                                            <table class="table table-striped table-bordered" style="font-size:10pt;margin-bottom:0px">
                                            <tr>
                                              <th>เริ่มต้น</th>
                                              <th>สิ้นสุด</th>
                                              <th>เวลา</th>
                                            </tr>
                                            ';
                                          }
                                          echo '
                                          <tr>
                                          <td>
                                            <span>'.DisplayDateTime($result[$i]['datebegin'],'Y-m-d').'</span>
                                          </td>
                                          <td>
                                            <span>'.DisplayDateTime($result[$i]['dateend'],'Y-m-d').'</span>
                                          </td>
                                          <td>
                                            <span>'.minToText($result[$i]['roundmins']).'</span>
                                          </td>
                                          </tr>
                                          ';
                                        }
                                        echo "</table>";
                                      }
                                      ?>
                                    </span>
                                </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-sm-12">
                                  <span class="pull-right">
                                    <a href="courseGeneralEdit.php?courseID=<?php echo $courseID?>">ดูข้อมูลหลักสูตรโดยละเอียด</a>
                                  </span>
                                </div>
                              </div>
                              </div>
                            </div>
                            </div>

                            <!--Course info panel right-->
                            <div class="col-sm-4">
                              <div class="panel panel-default">
                                  <div class="panel-body">
                                    <div class="form-group row">

                                        <label class="control-label col-sm-8">จำนวนรับผู้เข้าอบรม</label>
                                        <div class="col-sm-4">
                                            <span class="form-control-static col-sm-12"><?php echo $approxstudent;?></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-sm-8"><i class='fas fa-fw fa-edit'></i> รับทราบ</label>
                                        <div class="col-sm-4">
                                            <span class="form-control-static col-sm-12"><?php echo $studentAccepted;?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-sm-8"><i class='fas fa-fw fa-hourglass-start'></i> รอตอบรับ</label>
                                        <div class="col-sm-4">
                                            <span class="form-control-static col-sm-12"><?php echo $studentWait;?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-sm-8"><i class='fas fa-fw fa-question'></i> ขอไม่เข้าร่วม</label>
                                        <div class="col-sm-4">
                                            <span class="form-control-static col-sm-12"><?php echo $studentRequestReject;?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-sm-8"><i class='fas fa-fw fa-user-slash'></i> ไม่เข้าร่วม</label>
                                        <div class="col-sm-4">
                                            <span class="form-control-static col-sm-12"><?php echo $studentReject;?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="control-label col-sm-8"><i class='fas fa-fw fa-user-slash'></i> ระงับ</label>
                                        <div class="col-sm-4">
                                            <span class="form-control-static col-sm-12"><?php echo $studentSuspend;?></span>
                                        </div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                          <!--Button panel-->
                          <?php
                          if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1" or $studentValid>0) {
                          ?>
                          <div class="row">
                            <div class="col-sm-12">
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#mailModal">
                                <i class="fas fa-envelope"></i> Mail แจ้งผู้เข้ารับการอบรม</a>
                              </button>
                              แบบ ฝยฝป.3
                              <button type="button" class="btn btn-primary btn-sm" id="btnGenPDFAccepted">
                                <i class="fas fa-file-pdf"></i> รายชื่อ-รับทราบ</a>
                              </button>
                              <button type="button" class="btn btn-primary btn-sm" id="btnGenPDFAll">
                                <i class="fas fa-file-pdf"></i> รายชื่อ-ทั้งหมด</a>
                              </button>
                              <button type="button" class="btn btn-primary btn-sm" id="btnGenPDFBlank">
                                <i class="fas fa-file-pdf"></i> ไม่ระบุรายชื่อ</a>
                              </button>
                            </div>
                            <div class="col-sm-12">
                              Issue: ไม่รองรับครึ่งวัน ปนวัน
                            </div>
                          </div>
                          <?php } ?>
                        </div> <!--End panel body-->
                      </div>
                    </div> <!--End panel Top-->
                    </form>
                  </div> <!--End Left MD-->

                </div>
                <!-- List of student-->
                <div class="row">
                    <div class="col-sm-12">
                      <div class="panel panel-primary">
                        <div class="panel-body">
                          <table id="studentTable" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                            <tr>
                								<td style="padding:5px;">&nbsp;</td>
                								<td style="padding:5px;">รหัสพนักงาน</td>
                								<td style="padding:5px;">ชื่อ-นามสกุล</td>
                                <td style="padding:5px;">ตำแหน่ง</td>
                                <td style="padding:5px;">สังกัด</td>
                                <td style="padding:5px;">เลือกโดย</td>
                                <td style="padding:5px;">สถานะ</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                              $sql="select std.*,tis.thai_name tisthai_name from coursestudent std,tisusers tis";
                              $sql.=" where std.courseid=".$courseID." and std.assignby=tis.employeeno";
                              $result=json_decode(pgQuery($sql),true);
                							if($result['code']=="200") {
                              	for($i=0;$i<count($result)-1;$i++) {
                                  echo "<tr>\n";
                                  $urlImage=intranetIMGNoCheck($result[$i]['employeeno']);
                                  echo "<td style='width:100px'>";
                									echo '<div class="hes-gallery" >';
                                  echo "<img class='media-object img-thumbnail user-img'  style='height:90px;width:90px;' ";
                                  echo "data-subtext='".strstr($result[$i]['thai_name'],' ',true)."' ";
                                  echo "data-alt='".$result[$i]['thai_name']."' src='".$urlImage."'>";
                									echo "</div>";
                                  echo "</td>";
                									echo "<td>";
                									echo $result[$i]['employeeno'];
                                  echo "</td>";
                                  echo "<td>".$result[$i]['thai_name']."</td>";
                                  echo "<td>".$result[$i]['position']."</td>";
                                  echo "<td>";
                                  if ($result[$i]['section']<>"") {
                                    echo $result[$i]['section']."<br/>";
                                  }
                                  if ($result[$i]['division']<>"") {
                                    echo $result[$i]['division']."<br/>";
                                  }
                                  echo $result[$i]['department'];
                                  //echo "<br/>".$result[$i]['company'];
                                  echo "</td>";
                                  echo "<td>".strstr($result[$i]['tisthai_name'],' ',true)."</td>";
                                  echo "<td>";
                                  $studentStatus=$result[$i]['status'];
                                  $suspend="<a title='ระงับการอบรม' style='color:red;' href='javascript:updateStudentStatus(\"".$result[$i]['studentid']."\",\"".$result[$i]['thai_name']."\",4)'>
                                    <i class='fas fa-user-slash'></i> ระงับ</a>";
                                  $recover="<a title='มอบหมายอีกครั้ง' href='javascript:updateStudentStatus(\"".$result[$i]['studentid']."\",\"".$result[$i]['thai_name']."\",0)'>
                                    <i class='fas fa-undo'></i> มอบหมาย</a>";
                                  switch ($studentStatus) {
                                    case '0':
                                      echo "<i class='fas fa-fw fa-hourglass-start'></i>".statusStudentText($studentStatus);
                                      if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
                    	                  echo "<br/>";
                                        echo $suspend;
                    									}
                                      break;
                                    case '1':
                                      echo "<i class='fas fa-fw fa-edit'></i>".statusStudentText($studentStatus);
                                      if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
                    	                  echo "<br/>";
                                        echo $suspend;
                    									}
                                      break;
                                    case '2':
                                      echo "<span title='".$result[$i]['studentremark']."'>";
                                      echo "<i class='fas fa-fw fa-question'></i>".statusStudentText($studentStatus);
                                      echo "</span>";
                                      if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
                    	                  echo "<br/>";
                                        echo $recover;
                    									}
                                      break;
                                    case '3':
                                      echo "<span title='".$result[$i]['studentremark']."'>";
                                      echo "<i class='fas fa-fw fa-edit'></i>".statusStudentText($studentStatus);
                                      echo "</span>";
                                      if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
                    	                  echo "<br/>";
                                        echo $recover;
                    									}
                                      break;
                                    case '4':
                                      echo "<span title='".$result[$i]['studentremark']."'>";
                                      echo "<i class='fas fa-fw fa-user-slash'></i>".statusStudentText($studentStatus);
                                      echo "</span>";
                                      if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
                    	                  echo "<br/>";
                                        echo $recover;
                    									}
                                      break;
                                    default:
                                      echo "&nbsp";
                                      break;
                                  }
                                  echo "</td>";
                                  echo "</tr>\n";
                	              }
                							} else {
                								 echo "<tr><td colspan='5'>\n";
                								 echo "Error : ".$result[code]."<!--".$result[message]."--!>";
                								 echo "</td></tr>";
                							}
                            ?>
                            </tbody>
                          </table>
                          </div>

                      </div>

                    </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                    Issue : <br/>
                    - ไม่รองรับการย้ายรุ่น<br/>
                  </div>
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

    <div id="mailModal" class="modal fade" role="dialog" data-backdrop="static">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header MyModalHeader">
            <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
            <h5 class="modal-title">แบบฟอร์มการส่ง E-mail</h5>

					</div>
					<div class="modal-body" style="max-height:450px">
						<div class="row" style="margin:10px">
							<div id='modal-warning'>xxxxx</div>
							<form id="formMail" name="formMail">
                <div class="form-group row">
                    <?php
                    $sendtoUnAccepted="";
                    $sql="select * from coursestudent where courseid=".$courseID." and status=0";
                    $result=json_decode(pgQuery($sql),true);
                    if($result['code']=="200") {
                      for($i=0;$i<count($result)-1;$i++) {
                        $sendtoUnAccepted=$sendtoUnAccepted.$result[$i]['email'].",";
                      }
                    }
                    $sendtoUnAccepted=rtrim($sendtoUnAccepted,",");
                    $sendtoAll="";
                    $sql="select * from coursestudent where courseid=".$courseID." and status<3";
                    $result=json_decode(pgQuery($sql),true);
                    if($result['code']=="200") {
                      for($i=0;$i<count($result)-1;$i++) {
                        $sendtoAll=$sendtoAll.$result[$i]['email'].",";
                      }
                    }
                    $sendtoAll=rtrim($sendtoAll,",");
                    ?>
                    <input type="hidden" id="sendtoUnAccepted" name="sendtoUnAccepted" value="<?php echo $sendtoUnAccepted;?>">
                    <input type="hidden" id="sendtoAll" name="sendtoAll" value="<?php echo $sendtoAll;?>">
                    <input type="hidden" id="sendto" name="sendto">
										<label class="control-label col-sm-2">To</label>
                    <div class="col-sm-10">
                      <label class="radio-inline">
                        <input type="radio" name="optSendto" value="sendtoUnAccepted" <?php if($studentAccepted==0) { echo "disabled"; }?>>
                        ผู้รับการอบรมที่ยังไม่ตอบรับ</label>
                      <label class="radio-inline">
                        <input type="radio" name="optSendto" value="sendtoAll">
                        ผู้รับการอบรมทุกคน</label>
                    </div>
								</div>
								<div class="form-group row">
										<label class="control-label col-sm-2" for="subject">Subject</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Subject" id="subject" name="subject">
                    </div>
								</div>
                <div class="form-group row">
                    <label class="control-label col-sm-2" for="bodyhtml">Message</label>
                    <div class="col-sm-10">
                      <textarea id="bodyhtml" name="bodyhtml"></textarea>
                    </div>
								</div>
                <div class="form-group row">
                    <label class="control-label col-sm-2" for="selSubject">Template</label>
                    <div class="col-sm-10">
                      <select class="form-control" id="selSubject" name="selSubject" style="width:50%">
                        <option></option>
                        <option value="แจ้งเข้ารับการอบรม" data-body="
                          เรียนทุกท่าน<br/><br/>
                          ท่านมีรายชื่ออยู่ในการอบรม <?php echo $nameOfficial?><br/>
                          กรุณา login ระบบ TIS เพื่อตรวจสอบและรับทราบการอบรม<br/>
                          <a href='https://app.jasmine.com/tis/personalAccepted.php?courseID='<?php echo $courseID?>'>Login</a><br/>
                          ">แจ้งเข้ารับการอบรม
                        </option>
                        <?php
                        $schedule="";
                        $sql="select * from courseschedule where courseId=".$courseID;
                        $result=json_decode(pgQuery($sql),true);
                        if($result['code']=="200") {
                          for($i=0;$i<count($result)-1;$i++) {
                            $schedule.="
                            <div class='col-sm-12'>
                              <span>".DisplayDateTime($result[$i]['datebegin'],'Y-m-d')."</span>
                              -
                              <span>".DisplayDateTime($result[$i]['dateend'],'Y-m-d')."</span>
                            </div>
                            ";
                          }
                        }
                        ?>
                        <option value="กำหนดการอบรม" data-body="
                          เรียนทุกท่าน<br/><br/>
                          กำหนดการอบรม <?php echo $nameOfficial?><br/>
                          <?php echo $schedule;?>
                          ">กำหนดการอบรม
                        </option>
                      </select>
                    </div>
                  </div>
              </form>
            </div>
					</div>
					<div class="modal-footer" style="margin-top: 0px;padding-top: 10px;padding-bottom: 15px;">
            <button type="button" class="btn btn-success btn-sm" id="btnSendMail" disabled>
              <i class="fas fa-paper-plane"></i> ส่ง Mail
            </button>
						<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">ยกเลิก</button>
					</div>
				</div>

			</div>
		</div>
		<!-- End Modal -->

    <?php include_once("notification.php");?>
		<script src="assets/lib/jquery.min.js"></script>
    <script src="lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>

    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-responsive/dataTables.responsive.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-tabletools/dataTables.tableTools.js"></script>


    <script src="lib/summernote-0.8.11/summernote.js"></script>
    <script src="lib/summernote-0.8.11/lang/summernote-th-TH.js"></script>
    <script src="lib/select2-4.0.5/js/select2.min.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="lib/gallery/hes-gallery-master/hes-gallery.min.js"></script>
		<script type="text/javascript">
      function userDelete(id) {
        if(confirm("Want to delete?")) {
          return;
          $.ajax({
            type: "POST",
            url: "db/deleteUser.php",
            data: "userID="+id,
            beforeSend: function()
            {
              $('#loading').show();
            },
            success: function(result){
              var obj = JSON.parse(result);
              if(obj.code=="200") {
                //alert('OK');
                location.reload();
              } else {
                alert(obj.message);
                //$('#debug').html(obj.message);
                $('#loading').hide();
              }
            },
            error: function()
            {
              alert("Cannot call delete api");
              $('#loading').hide();
            }
          });
        }
      }

      function popup(msg) {
        $('#modalContent').html(msg);
        //$('#modalContent').text(msg);
        $('#popupModal').modal('show');
      }

      function updateStudentStatus(studentID,textContant,status) {
        var title='';
        var classname='';
        if(status==4) {
          title='กรุณายืนยัน การระงับการอบรม ?';
          classname="confirmDelete bootbox-confirm";
        } else {
          title='กรุณายืนยัน การมอบหมายใหม่ ?';
          classname="bootbox-confirm";
        }
        bootbox.confirm({
          closeButton: false,
          title:title,
            message: textContant,
            size: 'small',
            animate: true,
            centerVertical:true,
            className:classname,
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
            callback: function (result) {
              if(result){
                var formData = {
                  'courseID' : <?php echo $courseID;?>,
                  'studentID' : studentID,
                  'status' : status,
                  'remark' : 'ระงับโดย Training',
                };
                $.ajax({
                  type: "POST",
                  url: "db/updateStudentStatus.php",
                  data: formData,
                  beforeSend: function()
                  {
                    $('#loading').show();
                  },
                  success: function(result){
                    try {
                      var obj = JSON.parse(result);
                      if(obj.code=="200") {
                        popup("Status updated.");
                        //$('#loading').hide();
                        location.reload();
                      } else {
                        popup(obj.message);
                        //$('#debug').html(obj.message);
                        $('#loading').hide();
                      }
                    } catch (err) {
                      console.log('update status error');
                      console.log(result);
                      popup("Update status Error : API return unknow json");
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
      }

      $(document).ready(function() {
        var table = $('#studentTable').DataTable({
          "pageLength": 10,
          responsive: true,
          "order": [],
          "columnDefs": [{
            "targets": 0,
            "orderable": false,
            "searchable": false
            },
          ],
          "dom": 'frtip'
        });

        //Script for modal part
        $('input[type=radio][name=optSendto]').change(function() {
            $('#btnSendMail').removeAttr('disabled');
            switch (this.value) {
              case 'sendtoUnAccepted':
                $('#sendto').val($('#sendtoUnAccepted').val());
                break;
              case 'sendtoAll':
                $('#sendto').val($('#sendtoAll').val());
                break;
              default:
            }
        });

        $('#selSubject').select2({
          placeholder: 'เลือกข้อความอัตโนมัติ',
          minimumResultsForSearch: -1
        }).on('select2:select', function (e) {
          var opt = e.params.data.element;
          $('#subject').val(opt.value);
          var body=$(opt).data("body");
          $('#bodyhtml').summernote('code',body)
        }); //end select2

        $('#bodyhtml').summernote({
          lang: 'th-TH',
          height:150,
          minHeight:80,
          followingToolbar: false,
          disableResizeEditor: true,
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

        $("#btnSendMail").click(function(){
          var formData = {
              'sendto': $('#sendto').val(),
              'subject': $('#subject').val(),
              'bodyhtml': $('#bodyhtml').val()
          };
          console.log("sendto: "+formData.sendto);
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
                  $('#loading').hide();
                  $('#mailModal').modal('hide');
                } else {
                  popup(obj.message);
                  $('#loading').hide();
                }
              } catch (err) {
                popup("Send mail error");
                $('#loading').hide();
              }
              //location.reload();
            },
            error: function()
            {
              popup("Cannot call send mail api");
              $('#loading').hide();
            }
          });
        });
        //End script for modal part

        $('#courseInfo').on('shown.bs.collapse', function () {
		       $("#toggleInfo").removeClass("fa-chevron-down").addClass("fa-chevron-up");
		    });
				$('#courseInfo').on('hidden.bs.collapse', function () {
		       $("#toggleInfo").removeClass("fa-chevron-up").addClass("fa-chevron-down");
		    });

        $("#btnGenPDFAccepted").click(function(){
            $('#frmOpenPDF input[name=fillUser]').val("1");
    				$('#frmOpenPDF').submit();
            frmOpenPDF
        });

        $("#btnGenPDFAll").click(function(){
            $('#frmOpenPDF input[name=fillUser]').val("2");
    				$('#frmOpenPDF').submit();
            frmOpenPDF
        });

        $("#btnGenPDFBlank").click(function(){
            $('#frmOpenPDF input[name=fillUser]').val("0");
    				$('#frmOpenPDF').submit();
            frmOpenPDF
        });
      }); //end document.ready
    </script>
  </body>
</html>
