<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("isuser","courseInvite"); //If need permission enable here
include_once("lib/myLib.php");

//Initial form value
$courseID = isset($_GET["courseID"])?$_GET["courseID"]:'';

if($courseID<>"") {
  $sql="select * from course where courseid=".$courseID;
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $isFound=1;
    $nameOfficial=$result[$i]['nameofficial'];
    $nameMarketing=$result[$i]['namemarketing'];
    $requirement=$result[$i]["requirement"];
  }
  if($isFound==0) { //Wrong access_token
    $url="error.php?code=courseInvite";
    header("Location: ".$url);
  }

  //Have course info next check right to allocate
  $sql="select * from courseAllocate ";
  $sql.="where employeeNo='".$_SESSION["employee_id"]."' and courseId=".$courseID;
  $resultRight=json_decode(pgQuery($sql),true);
  $hasQuota=0;
  for($i=0;$i<count($resultRight)-1;$i++) {
    $hasQuota=1;
  }
  if($hasQuota==0) { //No Quota to do
    $url="forbidden.php?permission=invitable&code=courseInvite";
    header("Location: ".$url);
  }
} else {
  //Unsupport no courseID
  $url="error.php?code=courseAllocate";
  header("Location: ".$url);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Course Invite</title>
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
    .modal-footer button {
      float:right;
      margin-left: 10px;
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
  <link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="lib/gallery/hes-gallery-master/hes-gallery.min.css">
  </head>
  <body>
    <div id="wrap">
      <?php $menu="course" ?>
			<?php $submenu="course.php" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>
                <h5>เลือกผู้เข้ารับการอบรม</h5>
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
              <!--Course Info-->
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-green">
                    <div class="panel-heading btn-outline" data-toggle="collapse" href="#courseInfo" style="cursor:pointer;">
                      <i class="fas fa-user-friends"></i> ข้อมูลหลักสูตร
                      <i id="toggleInfo" class="fas fa-chevron-down pull-right"></i>
                    </div>
                    <div id="courseInfo" class="panel-collapse collapse">
                      <div class="panel-body" style="background-color:#e6e4e2;">
                        <div class="row">
                          <!--Course info panel left-->
                          <div class="col-sm-12">
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
                        </div>
                      </div> <!--End panel body-->
                    </div>
                  </div> <!--End panel Top-->
                </div> <!--End Left MD-->
                </div> <!--End Course Info-->
                <form id="formCourseInvite" name="formCourseInvite" class="form-horizontal">
                  <div class="row">
                    <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-user-plus"></i> เลือกผู้เข้ารับการอบรม
                      </div>
                      <div class="panel-body">
                        <div class="form-group row">
                          <div class="col-sm-12 alert alert-info" align="center" id="noticeInvite" style="display:none">
                            คุณใช้สิทธิ์เลือกครบแล้ว
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">คุณสมบัติผู้เข้าอบรม</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-9 nopadding"><?php echo $requirement;?></span>
                          </div>
                        </div>
                        <?php
                        $sql="select * from courseAllocate ";
                        $sql.="where employeeNo='".$_SESSION["employee_id"]."' and courseId=".$courseID;
                        $result=json_decode(pgQuery($sql),true);
                        if($result['code']=="200") {
                          $assignQuota=$resultRight[0]["allocateleft"]+$resultRight[0]["allocateused"];
                          $assignUsed=$resultRight[0]["allocateused"];
                        }
                        ?>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">การจัดสรร</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-9 nopadding">
                              สิทธิ์เลือก <span id='assignQuota' name='assignQuota'><?php echo $assignQuota;?></span> :
                              เลือกผู้เรียน <span id='assignUsed' name='assignUsed'><?php echo $assignUsed;?></span>,
                              รอจัดสรร <span id='assignLeft' name='assignLeft'></span>
                            </span>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">ระบุรหัสพนักงาน</label>
                            <div class="col-sm-9">
                              <div class="col-sm-10 nopadding">
                                <div class="col-sm-5 nopadding">
                                  <div class="input-group">
                                    <input type="text" class="form-control" placeholder="รหัสพนักงาน"
                                      id="assignEmployeeNo" name="assignEmployeeNo">
                                    <input type="hidden" class="form-control" id="assignEmployeeNo" name="assignEmployeeNo">
                                    <span class="input-group-btn">
                                      <button type="button" class="btn btn-default" id="getJPM">
                                        <i class="fas fa-sync"></i>
                                      </button>
                                    </span>
                                  </div>
                                </div>
                                <div class="col-sm-7 nopadding">
                                <span>
                                  <input type="text" class="form-control" placeholder=""
                                    id="assignEmployeeNoDisplay" name="assignEmployeeNoDisplay" readonly>
                                </span>
                                </div>
                              </div>
                              <div class="col-sm-2 paddingButton">
                                <button type="button" class="btn btn-info btn-sm" id="btnAssign">
                                <i class="fas fa-user-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">ภายในหน่วยงานตนเอง</label>
                            <div class="col-sm-9">
                              <div class="col-sm-10 nopadding">
                                <select id="listEmpSameOU" name="listEmpSameOU" class="form-control" multiple="multiple">
                                </select>
                              </div>
                              <div class="col-sm-2 paddingButton">
                                <button type="button" class="btn btn-info btn-sm" id="btnAssignSameOU">
                                <i class="fas fa-user-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:5px">
                            <label class="control-label col-sm-3">ระบุหน่วยงาน</label>
                            <div class="col-sm-9">
                              <div class="col-sm-11 nopadding">
                                <select id="listOU" name="listOU" class="form-control">
                                  <option></option>
                                </select>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3"></label>
                            <div class="col-sm-9">
                              <div class="col-sm-10 nopadding">
                                <select id="listEmpAllOU" name="listEmpAllOU" class="form-control" multiple="multiple" disabled>
                                </select>
                              </div>
                              <div class="col-sm-2 paddingButton">
                                <button type="button" class="btn btn-info btn-sm" id="btnAssignAllOU">
                                <i class="fas fa-user-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">ระบุรหัสพนักงาน/ชื่อ</label>
                            <div class="col-sm-9">
                              <div class="col-sm-10 nopadding">
                                <select id="listAll" name="listAll" class="form-control" multiple="multiple">
        	                      </select>
                              </div>
                              <div class="col-sm-2 paddingButton">
                                <button type="button" class="btn btn-info btn-sm" id="btnAssignAll">
                                <i class="fas fa-user-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:5px">
                            <label class="control-label col-sm-3">ระบุอายุงาน</label>
                            <div class="col-sm-9">
                              <div class="col-sm-10">
                                <div class="row">
                                  <div class="col-sm-2 nopadding">
                                    <input type="number" class="form-control" min="0" placeholder="ปี" name="beginExpYear" id="beginExpYear" value="0">
                                  </div>
                                  <div class="col-sm-1 nopadding" style="width:20px;">
                                    <span class="form-control-static col-sm-1" style="padding-left:5px !important;">ปี</span>
                                  </div>
                                  <div class="col-sm-2 nopadding">
                                    <input type="number" class="form-control" min="0" max="11" placeholder="เดือน" name="beginExpMonth" id="beginExpMonth" value="0">
                                  </div>
                                  <div class="col-sm-1 nopadding" style="width:50px">
                                    <span class="form-control-static col-sm-2" style="padding-left:1px !important;padding-right:1px !important;white-space:nowrap">เดือน -</span>
                                  </div>
                                  <div class="col-sm-2 nopadding">
                                    <input type="number" class="form-control" min="0" placeholder="ปี" name="endExpYear" id="endExpYear" value="0">
                                  </div>
                                  <div class="col-sm-1 nopadding" style="width:20px;">
                                    <span class="form-control-static col-sm-1" style="padding-left:5px !important;">ปี</span>
                                  </div>
                                  <div class="col-sm-2 nopadding">
                                    <input type="number" class="form-control" min="0" max="11" placeholder="เดือน" name="endExpMonth" id="endExpMonth" value="1">
                                  </div>
                                  <div class="col-sm-1 nopadding">
                                    <span class="form-control-static col-sm-2" style="padding-left:5px !important;">เดือน</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 paddingButton">
                                <button type="button" class="btn btn-sm btn-info" id="btnListEXP">
                                <i class="fas fa-search"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-top:0px;">
                            <label class="control-label col-sm-3"></label>
                            <div class="col-sm-9">
                              <div class="col-sm-10 nopadding">
                                <select id="listEmpEXP" name="listEmpEXP" class="form-control" multiple="multiple" disabled>
                                </select>
                              </div>
                              <div class="col-sm-2 paddingButton">
                                <button type="button" class="btn btn-info btn-sm" id="btnAssignEXP" disabled>
                                <i class="fas fa-user-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                      </div> <!--End panel body-->
                    </div> <!--End panel Left-->
                    Discuss :<BR>
                    <ul>
                      <li>ลบแล้วลบเลย หรือคืนกลับได้</li>
                      <li>ต้องการดึงพนักงานในเงื่อนไขอื่นๆ อีกหรือไม่</li>
                    </ul>
                    Know issue :<BR>
                    <ul>
                      <li>ลบแล้ว คืนกลับ ยังไม่ได้เช็คว่าเกินสิทธิ์หรือไม่</li>
                      <li>select2 เลือกหลายคน ชนขอบอันอื่น</li>
                      <li>select2 เหลือสิทธิ์ 1 คน เลือก 2 คนได้</li>
                    </ul>
                    To do :<BR>
                    <ul>
                      <li>เงื่อนไขเลือกนักเรียน ผ่านการอบรมหลักสูตร...</li>
                    </ul>
                    </div> <!--End Left MD-->

                    <div class="col-md-4">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-user-check"></i> ผู้เข้ารับการอบรมที่เลือก
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12" id="divSelected">
                          <?php
                          $sql="select * from courseStudent ";
                          $sql.="where assignBy='".$_SESSION["employee_id"]."' and courseId=".$courseID;
                          $result=json_decode(pgQuery($sql),true);
                          //$invitedStudent=count($result);
                          $invitedStudent=0;
                          if($result['code']=="200") {
                            for($i=0;$i<count($result)-1;$i++) {

                              if($i==0) {
                                echo "<table class='table table-striped'>\n";
                              }
                              ?>
                              <tr>
                                <td align="center">
                                  <div class="hes-gallery" data-wrap="true">
                                  <img class='media-object img-thumbnail user-img' alt='User Picture'
                                    src='<?php echo intranetIMGCheck($result[$i]['employeeno']);?>'
                                    data-subtext='<?php echo strstr($result[$i]['thai_name'],' ',true)?>'
                                    data-alt='<?php echo $result[$i]['thai_name']?>'
                                    style='min-height:60px;height:60px;text-align:center'>
                                  </div>
                                </td>
                                <td>
                                  <span>
                                    <?php echo $result[$i]['employeeno'];?><br/>
                                    <?php echo $result[$i]['thai_name'];?><br/>
                                    <?php echo statusStudentText($result[$i]['status']);?><br/>
                                  </span>
                                </td>
                                <td>
                                  <?php
                                  $btnRemove='<button type="button" class="remove_button btn btn-danger btn-sm"
                                    data-id="'.$result[$i]['studentid'].'"
                                    data-name="'.$result[$i]['thai_name'].'"
                                    data-toggle="tooltip" data-placement="top" title="ยกเลิกการมอบหมาย"
                                    id="btnRemove">
                                  <i class="fas fa-fw fa-user-minus"></i>
                                  </button>';
                                  $btnAcceptReject='<button type="button" class="accept_button btn btn-danger btn-sm"
                                    data-id="'.$result[$i]['studentid'].'"
                                    data-name="'.$result[$i]['thai_name'].'"
                                    data-toggle="tooltip" data-placement="top" title="ยอมรับการไม่เข้าร่วม"
                                    id="btnAcceptReject">
                                  <i class="fas fa-fw fa-user-slash"></i>
                                  </button>';
                                  $btnReturn='<button type="button" class="return_button btn btn-primary btn-sm"
                                    data-id="'.$result[$i]['studentid'].'"
                                    data-name="'.$result[$i]['thai_name'].'"
                                    data-toggle="tooltip" data-placement="top" title="มอบหมายอีกครั้ง"
                                    id="btnReturn">
                                  <i class="fas fa-fw fa-undo"></i>
                                  </button>';
                                  switch ($result[$i]['status']) {
                                    case '0':
                                      echo $btnRemove;
                                      $invitedStudent++;
                                      break;
                                    case '1':
                                      echo $btnRemove;
                                      $invitedStudent++;
                                      break;
                                    case '2':
                                      echo $btnAcceptReject;
                                      echo "<br/>";
                                      echo $btnReturn;
                                      $invitedStudent++;
                                      break;
                                    case '3':
                                      echo $btnReturn;
                                      break;
                                    case '4':
                                      echo $btnReturn;
                                      break;
                                    default:
                                      // code...
                                      break;
                                  }
                                  ?>
                                </td>
                              </tr>
                              <?php
                              //$invitedStudent++;
                              if($i==count($result)-2) {
                                echo "</table>\n";
                              }
                            }
                          }
                          ?>
                          </div>
                        </div>
                        <div class="form-group row" id="divSave" align="center" <?php if($invitedStudent==0) { echo "style='display: none;'";} ?>>
                          <button type="button" class="btn btn-danger btn-sm"
                            data-id='0' data-name='ทั้งหมด' id="btnRemoveAll">
                            <i class="fas fa-user-times"></i> ยกเลิกทั้งหมด
                          </button>
                        </div>
                      </div> <!--End panel body-->
                    </div> <!--End panel Right-->
                    </div> <!--End Right MD-->
                    <!--For debug-->
                  </div> <!--End setting row-->
                </form>
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
    <div id="confirmModal" class="modal fade" role="dialog" tabindex="-1">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            กรุณายืนยันการเลือกผู้เข้ารับการอบรม
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <br/>
          </div>
          <div class="modal-body">
            <form id="formSelected" name="formSelected" class="form-horizontal">
              <input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
              <div class="row">
              <table id="tbPrepared" class="table table-striped table-sm table-bordered">
                <thead>
                  <tr>
                    <th colspan="3" class="success" style="text-align:center">
                      Available
                    </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <table id="tbRejected" class="table table-striped table-sm table-bordered">
                <thead>
                  <tr>
                    <th colspan="3" class="danger" style="text-align:center">
                      Reject
                    </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <span id="confirmSummary"></span>
                </div>
                <div class="col-sm-4 text-right">
                  <button type="button" class="btn btn-success" data-dismiss="modal" id="btSave">
                    <i class="fa fa-check"></i> ยืนยัน</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> ยกเลิก</button>
                </div>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
    <!--end Modal-->

    <?php include_once("notification.php");?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script src="lib/Moment 2.24.0/moment.min.js"></script>
    <script src="lib/js/inputFilter.js"></script>
    <script src="lib/select2-4.0.5/js/select2.min.js"></script>
    <script src="lib/select2-4.0.5/js/i18n/th.js"></script>
    <script src="lib/gallery/hes-gallery-master/hes-gallery.min.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="assets/js/formatSelect2Emp.js"></script>
    <script type="text/javascript">
    function displayTable() {
      var totalSelected=0;
      $('#tbPrepared input[name="EmployeeNo[]"]').each(function() {
             totalSelected += 1;
      });

      var totalRejected=0;
      $('#tbRejected input[name="RejectEmployeeNo[]"]').each(function() {
             totalRejected += 1;
      });

      var total=totalSelected+totalRejected;
      $('#confirmSummary').text('เลือกทั้งหมด '+total+' คน : ');
      if(totalSelected==0) {
        $('#tbPrepared').hide();
        $('#btSave').prop('disabled', true);
      } else {
        $('#tbPrepared').show();
        $('#confirmSummary').append(' จัดสรรเพิ่มได้ '+totalSelected+' คน');
        $('#btSave').removeAttr('disabled');
      }
      if(totalRejected==0) {
        $('#tbRejected').hide();
      } else {
        $('#tbRejected').show();
        $('#confirmSummary').append(' ติดปัญหา '+totalRejected+' คน');
      }
    }
    function calculateAssign() {
      <?php
       if ($assignQuota=="") {
         echo "var max=0;\n";
       } else {
         echo "var max=".$assignQuota.";\n";
       }
       if ($assignUsed=="") {
         echo "var totalSelected=0;\n";
       } else {
         echo "var totalSelected=".$assignUsed.";\n";
       }
      ?>
      var assignleft=max-totalSelected;
      if (assignleft==0) {
        disableAdded();
      } else {
        enableAdded();
      }
      $('#assignLeft').text(assignleft);
    }

    function validateEmp(employeeNo,th_initial,thai_name,company,department,section,division,position,email) {
      if (employeeNo=='') {
        return false;
      }
      var isRejected=checkRejected(employeeNo);
      if (isRejected[0]) {
        $('#tbRejected tbody').append(genRowError(employeeNo,th_initial,thai_name,company,department,section,division,position,email,isRejected[1]));
      } else {
        $('#tbPrepared tbody').append(genRowOK(employeeNo,th_initial,thai_name,company,department,section,division,position,email));
      }
      return true;
    }

    function checkRejected(employeeNo) {
      var reply=[];
      reply[0]=false;
      reply[1]="";
      $.ajax({
          type: "POST",
          async: false,
          url: "api/checkInvitedStudent.php",
          data: {
            employeeNo: employeeNo,
            courseID:<?php echo $courseID;?>
          },
          success: function(result){
            try {
              var obj = JSON.parse(result);
              if(obj.code=="200") {
                if(obj.message!="OK") {
                  reply[0]=true;
                  reply[1]=obj.message;
                }
              } else {
                reply[0]=true;
                reply[1]=obj.message;
              }
            } catch (err) {
              console.log('catch err result='+result);
              reply[0]=true;
              reply[1]="Error reply checking api";
            }
            //location.reload();
          },
          error: function()
          {
            console.log('err func check reject');
            reply[0]=true;
            reply[1]="Error access checking api";
          }
      });
      return reply;
    }

    function genRowOK(employeeNo,th_initial,thai_name,company,department,section,division,position,email) {
      var pic = employeeNo.replace(/\//g, '');
      var divText=`
      <tr>
        <td style="width:80px;text-align:center">
        <input type="hidden" id="EmployeeNo[]" name="EmployeeNo[]" readonly="true" value="`+employeeNo+`">
        <input type="hidden" id="th_initial[]" name="th_initial[]" readonly="true" value="`+th_initial+`">
        <input type="hidden" id="thai_name[]" name="thai_name[]" readonly="true" value="`+thai_name+`">
        <input type="hidden" id="company[]" name="company[]" readonly="true" value="`+company+`">
        <input type="hidden" id="department[]" name="department[]" readonly="true" value="`+department+`">
        <input type="hidden" id="section[]" name="section[]" readonly="true" value="`+section+`">
        <input type="hidden" id="division[]" name="division[]" readonly="true" value="`+division+`">
        <input type="hidden" id="position[]" name="position[]" readonly="true" value="`+position+`">
        <input type="hidden" id="email[]" name="email[]" readonly="true" value="`+email+`">
          <img class='media-object img-thumbnail user-img' alt='User Picture'
            src='https://intranet.jasmine.com/hr/office/Data/` + pic + `.jpg'
            style='min-height:60px;height:60px;text-align:center'>
        </td>
        <td>
          <span>
            `+employeeNo+`<br/>
            `+th_initial+` `+thai_name+`<br/>
            `+position+`
          </span>
        </td>
        <td style="width:200px;">
          <i class="fas fa-check-circle" style="color:green"></i> OK
        </td>
      </tr>
      `;
      return divText;
    }

    function genRowError(employeeNo,th_initial,thai_name,company,department,section,division,position,email,status) {
      var pic = employeeNo.replace(/\//g, '');
      var divText=`
      <tr>
        <td style="width:80px;text-align:center">
        <input type="hidden" id="RejectEmployeeNo[]" name="RejectEmployeeNo[]" readonly="true" value="`+employeeNo+`">
          <img class='media-object img-thumbnail user-img' alt='User Picture'
            src='https://intranet.jasmine.com/hr/office/Data/` + pic + `.jpg'
            style='min-height:60px;height:60px;text-align:center'>
        </td>
        <td>
          <span>
            `+employeeNo+`<br/>
            `+thai_name+`<br/>
            `+position+`
          </span>
        <td style="width:200px;">
          <i class="fas fa-times-circle" style="color:red"></i> Unavailiable<br/>`+status+`
        </td>
      </tr>
      `;
      return divText;
    }

    function disableAdded() {
      $('#btnAssign').prop('disabled', true);
      $('#btnAssignSameOU').prop('disabled', true);
      $('#btnAssignAllOU').prop('disabled', true);
      $('#btnAssignAll').prop('disabled', true);
      $('#btnAssignEXP').prop('disabled', true);
      $('#noticeInvite').show();
    }

    function enableAdded() {
      $('#btnAssign').removeAttr('disabled');
      $('#btnAssignSameOU').removeAttr('disabled');
      $('#btnAssignAllOU').removeAttr('disabled');
      $('#btnAssignAll').removeAttr('disabled');
      $('#btnAssignEXP').removeAttr('disabled');
      $('#noticeInvite').hide();
    }

    function popup(msg) {
        $('#modalContent').html(msg);
        //$('#modalContent').text(msg);
        $('#popupModal').modal('show');
    }

    function getJPM() {
      var employeeNo = $('#assignEmployeeNo').val().toUpperCase();
      if(employeeNo=="") {
          return;
      }
      $('#assignEmployeeNo').val(employeeNo); //in case uppercase
      $.ajax({
        type: "POST",
        url: "api/getEmployeeInfo.php",
        data: {
          employeeNo: employeeNo,
        },
        beforeSend: function()
        {
          $('#loading').show();
        },
        success: function(result){
          var obj = JSON.parse(result);
          if(obj.code=="200") {
            $('#assignEmployeeNoDisplay').val(obj.id+" "+obj.thai_name);
            $('#assignEmployeeNoDisplay').data("id",obj.id);
            $('#assignEmployeeNoDisplay').data("th_initial",obj.th_initial);
            $('#assignEmployeeNoDisplay').data("thai_name",obj.thai_name);
            $('#assignEmployeeNoDisplay').data("company",obj.company);
            $('#assignEmployeeNoDisplay').data("department",obj.department);
            var section="";
            if(obj.section!=null) {
              section=obj.section;
            }
            var division="";
            if(obj.division!=null) {
              division=obj.division;
            }
            $('#assignEmployeeNoDisplay').data("section",section);
            $('#assignEmployeeNoDisplay').data("division",division);
            $('#assignEmployeeNoDisplay').data("position",obj.title);
            $('#assignEmployeeNoDisplay').data("email",obj.email);
          } else {
            popup("ผิดพลาดในการดึง API รหัสพนักงาน");
          }
          $('#loading').hide();
        },
        error: function()
        {
          popup("Cannot call API");
          $('#loading').hide();
        }
      });
    }

        $(document).ready(function() {
          calculateAssign();

          $("#getJPM").click(function(){
            getJPM();
          });
          $('#assignEmployeeNo').on('keypress', function (e) {
            if(e.which === 13){
              event.preventDefault();
              getJPM();
            }
          });

          $("#btSave").click(function(){
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "db/saveCourseInvite.php",
                data: $("#formSelected").serialize(),
                beforeSend: function()
                {
                  $('#loading').show();
                },
                success: function(result){
                  try {
                    var obj = JSON.parse(result);
                    if(obj.code=="200") {
                      popup("Completed save.");
                      //$('#loading').hide();
                      location.reload();
                    } else {
                      popup(obj.message);
                      if(obj.code=="998") {
                        //location.href='courseInvite.php?courseID=<?php echo $courseID;?>';
                      }
                      //$('#debug').html(obj.message);
                      $('#loading').hide();
                    }
                  } catch (err) {

                    popup("Save Error : API return unknow json");
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
          $('#popupModal').on('hidden.bs.modal', function () {
              if ($('#modalContent').html()=="Completed save.") {
                //location.href='course.php';
              }
          });


          $('#btnAssign').click(function(){
            var assignEmployeeNoDisplay=$('#assignEmployeeNoDisplay').val().split(' ');
            var employeeNo=assignEmployeeNoDisplay[0];
            if(employeeNo=="") {
              return;
            }
            var data = $("#assignEmployeeNoDisplay").data();
            var employeeNo=data.id;
            var th_initial=data.th_initial;
            var thai_name=data.thai_name;
            var company=data.company;
            var department=data.department;
            var section=data.section;
            var division=data.division;
            var position=data.position;
            var email=data.email;
            validateEmp(employeeNo,th_initial,thai_name,company,department,section,division,position,email);
            $('#assignEmployeeNo').val('');
            $('#assignEmployeeNoDisplay').val('');
            displayTable();
            $('#confirmModal').modal('show');
            //calculateAssign();

          });

          $('#btnAssignSameOU').click(function(){
            var data = $("#listEmpSameOU").select2("data");
            assignFromSelect(data);
          });

          $('#btnAssignAllOU').click(function(){
            var data = $("#listEmpAllOU").select2("data");
            assignFromSelect(data);
          });

          $('#btnAssignAll').click(function(){
            var data = $("#listAll").select2("data");
            assignFromSelect(data);
          });

          $('#btnAssignEXP').click(function(){
            var data = $("#listEmpEXP").select2("data");
            assignFromSelect(data);
          });

          function assignFromSelect(data) {
            var isselected=false;
            $('#loading').show();
            $.each( data, function( key, value ) {
              isselected=true;
              var employeeNo=value["id"];
              var th_initial=value["tinitial"];
              var thai_name=value["thai_name"];
              var company=value["company"];
              var department=value["department"];
              var section="";
              if(value["section"]!=null) {
                section=value["section"];
              }
              var division="";
              if(value["division"]!=null) {
                division=value["division"];
              }
              var position="";
              if(value["position"]!=null) {
                position=value["position"];
              } else {
                position=value["title"];
              }
              var email=value["email"];
              validateEmp(employeeNo,th_initial,thai_name,company,department,section,division,position,email);
            });
            $('#loading').hide();
            if(isselected) {
              displayTable();
              $('#confirmModal').modal('show');
            }
          }
          preparelistFromOU();
          function preparelistFromOU(ouid) {
            var arr_data = [];
            var apiURL="";
            if (ouid === undefined) {
              ouid="";
            }
            $.ajax({
              type: "POST",
              url: 'api/getEmployeeOU.php',
              //url: 'test/getEmployeeSameOU.php',
              data: {
                ouid: ouid,
              },
              dataType: 'json',
              beforeSend: function()
              {
                $('#loading').show();
              },
              success: function(result){
                if(result.code=="200") {
                  $.each(result, function (key, value) {
                    switch(key) {
                      case 'code': break; //Ignore
                      default:
                        //var tempObj = '';
                        var tempObj = {};
                        tempObj = {
                          "id":value.id,
                          "text":value.id+' '+value.thai_name,
                          "tinitial":value.tinitial,
                          "thai_name":value.thai_name,
                          "company":value.company,
                          "department":value.department,
                          "section":value.section,
                          "division":value.division,
                          "position":value.title,
                          "email":value.email
                        };
                        arr_data.push(tempObj);
                    }
                  });
                  $('#loading').hide();
                  bindlistEmpSameOU(arr_data,ouid);
                } else {
                  popup("API พนง ในหน่วยงาน "+result.message);
                  bindlistEmpSameOU(arr_data,ouid);
                  $('#loading').hide();
                }
              }
            });
          }
          function bindlistEmpSameOU(arr_data,ouid) {
            if (ouid =="") {
              $('#listEmpSameOU').select2({
                placeholder: 'เลือกจากภายในหน่วยงาน',
                data: arr_data,
                escapeMarkup: function (markup) { return markup; }, // Allow html
                templateResult: formatEmpRepo,
            	  templateSelection: formatRepoEmpSelection
              }); //end select2
              $('#listEmpAllOU').select2();
            } else {
              $('#listEmpAllOU').select2({
                placeholder: 'เลือกจากหน่วยงานที่ระบุ',
                data: arr_data,
                escapeMarkup: function (markup) { return markup; }, // Allow html
                templateResult: formatEmpRepo,
            	  templateSelection: formatRepoEmpSelection
              }); //end select2
              $('#listEmpAllOU').select2('open');
            }
          }

          preparelistOU();
          function preparelistOU() {
            var arr_data = [];
            $.ajax({
              url: 'api/getOuList.php',
              dataType: 'json',
              beforeSend: function()
              {
                $('#loading').show();
              },
              success: function(result){
                $.each(result, function (key, value) {
                  var tempObj = {};
                  var txt="";
                  if(value.Section) {
                    txt=value.Division+':'+value.Section;
                  } else {
                    txt=value.Division;
                  }
                  tempObj = {
                    "id":value.code,
                    "text":txt
                  };
                  arr_data.push(tempObj);
                });
                $('#loading').hide();
                bindlistOU(arr_data);
              }
            });
          }

          function bindlistOU(arr_data) {
            $('#listOU').select2({
              placeholder: 'เลือกหน่วยงาน',
              data: arr_data
            }); //end select2
          }

          $('#listOU').on('change',function(){
            $('#listEmpAllOU').empty();
            $('#listEmpAllOU').prop('disabled', false);
            $('#btnAssignAllOU').prop('disabled', false);
            var ouid=$('#listOU').val();
            preparelistFromOU(ouid);
          });

          $('#listAll').select2({
            placeholder: 'ป้อนรหัสพนักงาน หรือชื่อ',
            //language: "th",
            ajax: {
              //url: 'https://app.jasmine.com/jpm/select2/employee_active.json', //full jpm
              //url: 'test/employee_active.php', //Test full jpm
              url: 'api/getEmployeeByKey.php', //Real production
              //url: 'test/getEmployeeByKey.php', //Test production
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  q: params.term, // search term
                  page: params.page
                };
              },
              cache: true
            },
            escapeMarkup: function (markup) { return markup }, // ยอมอักระเช่น &
            minimumInputLength: 4,
            templateResult: formatEmpRepo,
            templateSelection: formatRepoEmpSelection
          });

          $('#listEmpEXP').select2();

          $('#divSelected').on('click', '.remove_button', function(e){
            e.preventDefault();
            var id = $(this).data('id')
            var name = $(this).data('name')
            bootbox.confirm({
                size: "small",
                title: "ยืนยันการยกเลิก",
                message: "ยกเลิก "+name,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> ยกเลิก',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> ยืนยัน',
                        className: 'btn-success'
                    }
                },
                callback: function (result) {
                  if(result) {
                    updateStudentStatus(id,4,"ผู้จัดสรรยกเลิก");
                  }
                }
            });
          });

          $('#divSelected').on('click', '.return_button', function(e){
            e.preventDefault();
            var id = $(this).data('id')
            var name = $(this).data('name')
            bootbox.confirm({
                size: "small",
                title: "ยืนยันการนำกลับ",
                message: "นำกลับ "+name,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> ยกเลิก',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> ยืนยัน',
                        className: 'btn-success'
                    }
                },
                callback: function (result) {
                  if(result) {
                    updateStudentStatus(id,0,"ผู้จัดสรรนำกลับ");
                  }
                }
            });
          });

          $('#divSelected').on('click', '.accept_button', function(e){
            e.preventDefault();
            var id = $(this).data('id')
            var name = $(this).data('name')
            bootbox.confirm({
                size: "small",
                title: "ยอมรับการไม่เข้าร่วม",
                message: "ไม่เข้าร่วมการอบรม "+name,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> ยกเลิก',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> ยืนยัน',
                        className: 'btn-success'
                    }
                },
                callback: function (result) {
                  if(result) {
                    updateStudentStatus(id,3,"");
                  }
                }
            });
          });

          $('#btnRemoveAll').on('click', function(e){
            e.preventDefault();
            var id = $(this).data('id')
            var name = $(this).data('name')
            bootbox.confirm({
                size: "small",
                title: "ยืนยันการยกเลิก",
                message: "ยกเลิก "+name,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> ยกเลิก',
                        className: 'btn-danger'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> ยืนยัน',
                        className: 'btn-success'
                    }
                },
                callback: function (result) {
                    if(result) {
                      updateStudentStatus(id,4,"ผู้จัดสรรยกเลิกทั้งหมด");
                    }
                }
            });
          });

          $('#confirmModal').on('hidden.bs.modal', function () {
           $('#tbPrepared tbody').empty();
           $('#tbRejected tbody').empty();
          });

          function updateStudentStatus(studentID,status,remark) {
            //Update status
            var formData = {
              'courseID' : <?php echo $courseID;?>,
              'studentID' : studentID,
              'status' : status,
              'remark' : remark
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

          $("#btnListEXP").click(function(){
            var beginExpYear=0;
            var beginExpMonth=0;
            var endExpYear=0;
            var endExpMonth=0;
            //Init zero if blank;
            if($("#beginExpYear").val()!="") {
              beginExpYear=Number($("#beginExpYear").val());
            } else {
              $("#beginExpYear").val(beginExpYear);
            }
            if($("#beginExpMonth").val()!="") {
              beginExpMonth=Number($("#beginExpMonth").val());
            } else {
              $("#beginExpMonth").val(beginExpMonth);
            }
            if($("#endExpYear").val()!="") {
              endExpYear=Number($("#endExpYear").val());
            } else {
              $("#endExpYear").val(endExpYear);
            }
            if($("#endExpMonth").val()!="") {
              endExpMonth=Number($("#endExpMonth").val());
            } else {
              $("#endExpMonth").val(endExpMonth);
            }
            var minMonth=(beginExpYear*12)+beginExpMonth;
            var maxMonth=(endExpYear*12)+endExpMonth;
            //Validate min,max,equal
            if(minMonth>maxMonth) {
              bootbox.alert({
                size:'small',
                message:'อายุงานเริ่มต้นมากกว่าสิ้นสุด<br/>กรุณาเลือกใหม่'
              });
              return;
            }
            if(minMonth==maxMonth) {
              bootbox.alert({
                size:'small',
                message:'อายุงานเริ่มต้นเท่ากับสิ้นสุด<br/>กรุณาเลือกใหม่'
              });
              return;
            }
            $('#listEmpEXP').empty();
            $('#listEmpEXP').prop('disabled', false);
            $('#btnAssignEXP').prop('disabled', false);
            prepareListEXP(minMonth,maxMonth);
          });

          $('#beginExpMonth').on('input', function() {
              validateMonth(this);
          });

          $('#beginExpYear').on('input', function() {
              validateYear(this);
          });

          $('#endExpMonth').on('input', function() {
              validateMonth(this);
          });

          $('#endExpYear').on('input', function() {
              validateYear(this);
          });

          function validateMonth(txt) {
            var min=$(txt).attr('min');
            var max=$(txt).attr('max');
            var value=$(txt).val();
            if($.isNumeric(value)) {
              value=parseInt(value);
              if(value>max) {
                console.log('auto change over max');
                $(txt).val(max);
              }
              if(value<min) {
                console.log('auto change lower min');
                $(txt).val(min);
              }
            } else {
              console.log('auto change not numeric');
              $(txt).val(min);
            }
          }

          function validateYear(txt) {
            var min=$(txt).attr('min');
            var value=$(txt).val();
            if($.isNumeric(value)) {
              value=parseInt(value);
              if(value<min) {
                console.log('auto change lower min');
                $(txt).val(min);
              }
            } else {
              console.log('auto change not numeric');
              $(txt).val(min);
            }
          }

          function prepareListEXP(minMonth,maxMonth) {
            var arr_data = [];
            var apiURL="";
            $.ajax({
              type: "POST",
              url: 'api/getEmployeeExp.php',
              data: {
                min: minMonth,
                max: maxMonth
              },
              dataType: 'json',
              beforeSend: function()
              {
                $('#loading').show();
              },
              success: function(result){
                if(result.code=="200") {
                  console.log(result);
                  $.each(result, function (key, value) {
                    switch(key) {
                      case 'code': break; //Ignore
                      default:
                        //var tempObj = '';
                        var tempObj = {};
                        tempObj = {
                          "id":value.id,
                          "text":value.id+' '+value.thai_name,
                          "tinitial":value.tinitial,
                          "thai_name":value.thai_name,
                          "company":value.company,
                          "department":value.department,
                          "section":value.section,
                          "division":value.division,
                          "position":value.position,
                          "email":value.email,
                          "workdate":value.workdate
                        };
                        arr_data.push(tempObj);
                    }
                  });
                  $('#loading').hide();
                  bindlistEmpEXP(arr_data);
                } else {
                  popup("API EXP "+result.message);
                  bindlistEmpEXP(arr_data);
                  $('#loading').hide();
                }
              }
            });
          }
          function bindlistEmpEXP(arr_data) {
            $('#listEmpEXP').select2({
              placeholder: 'เลือกจากอายุการทำงาน',
              data: arr_data,
              escapeMarkup: function (markup) { return markup; }, // Allow html
              templateResult: formatExpEmpRepo,
              templateSelection: formatRepoEmpSelection
            }); //end select2
            $('#listEmpEXP').select2('open');
          }

        }); //end document.ready
    </script>
  </body>
</html>
