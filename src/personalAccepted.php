<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("isadmin","index"); //If need permission enable here
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
    $url="error.php?code=personalAccepted";
    header("Location: ".$url);
  }
  $sql="select * from coursestudent where courseid=".$courseID;
  $sql.=" and employeeno='".$_SESSION["employee_id"]."'";
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $studentID=$result[$i]['studentid'];
    $studentStatus=$result[$i]['status'];
    $studentRemark=$result[$i]['studentremark'];
  }
} else {
  //Prevent warning in_array()
  $trainerArray=array();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Course accept</title>
	<?php include_once("basicHeader.php");?>

  <link rel="stylesheet" href="lib/TisCheckbox/CustomCheckBox.css">
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


	</style>
  </head>
  <body>
    <div id="wrap">
			<?php $menu="personalCourse" ?>
			<?php $submenu="" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>

                <ol class="breadcrumb">

                                  <li>
                                      <a href="personalCourse.php">
                                       <i class="fas fa-fw fa-history"></i> ข้อมูลการฝึกอบรม
                                      </a>
                                  </li>
                                  <li class="active">

                                            <i class="fas fa-fw fa-book"></i> ข้อมูลหลักสูตรอบรม

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
                <div class="row">
                  <div class="col-md-9">
                    <form id="formCourseBasic" name="formCourseBasic" class="form-horizontal">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <i class="fab fa-accusoft"></i> ข้อมูลเบื้องต้น
                      </div>
                      <div class="panel-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">ชื่อหลักสูตร (ทางการ)</label>
                            <div class="col-sm-9">
                                <span class="form-control-static col-sm-12"><?php echo $nameOfficial;?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ชื่อหลักสูตร (ประชาสัมพันธ์)</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-12"><?php echo $nameMarketing;?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">กำหนดการ</label>
                          <div class="col-sm-9">
                              <span class="form-control-static col-sm-9">
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
                          <label class="control-label col-sm-3">ชั่วโมงฝึกอบรม</label>
                          <div class="col-sm-9" id="divMinuteTrain">
                            <span class="form-control-static col-sm-12">
                            <?php echo minToText($minuteTrain);?>
                            </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">วัตถุประสงค์</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-12"><?php echo $objective;?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">เนื้อหา</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-12"><?php echo $content;?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">คุณสมบัติผู้เข้าอบรม</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-12"><?php echo $requirement;?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">สถานที่จัด</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-12">
                            <?php
                            $sql="select siteid,sitero,siteprovince || ':' || sitename as sitename from trainingsite where siteid=".$siteid;
                            $result=json_decode(pgQuery($sql),true);
                            for($i=0;$i<count($result)-1;$i++) {
                              echo $result[0]['sitename']." ";
                            }
                            ?>
                            </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">วิทยากร</label>
                          <div class="col-sm-9">
                              <span class="form-control-static col-sm-12">
                              <?php
                              $sql="select trainerid,th_initial || ' ' || thai_name as trainerName,coalesce(department,'วิทยากรภายนอก') as department";
                              $sql.=" from trainer order by department";
                              $result=json_decode(pgQuery($sql),true);
                              for($i=0;$i<count($result)-1;$i++) {
                                if(in_array($result[$i]['trainerid'],$trainerArray)) {
                                  echo $result[$i]['trainername']." ";
                                }
                              }
                              ?>
                              </span>
                            </div>
                          </div>
                        </div> <!--End panel body-->
                      </div> <!--End panel Left-->
                      <?php if($studentStatus=="0") {?>

                        <div class="col-sm-12">
                          <div class="form-group row">
                            <div class="panel panel-yellow">
                            <div class="panel-heading">
                                เงื่อนไขและข้อตกลงในการใช้ข้อมูลเลขบัตรประจำตัวประชาชน
                            </div>
                            <div class="panel-body">
                                <p>เมื่อท่านได้รับการฝึกอบรมครบตามหลักสูตรและผ่านการวัดผลแล้ว บริษัทจะต้องดำเนินการนำส่งข้อมูลของท่านแจ้งต่อกรมแรงงาน โดยข้อมูลดังกล่าวมีการระบุถึงข้อมูลเลขบัตรประจำตัวประชาชนของท่าน</p>
                            </div>
                            <div class="panel-footer">
                              <div class="form-check checkbox checkbox-primary">

                                <input id="chkAccepted" class="form-check-input" type="checkbox" value="1">
                                <label class="form-check-label" for="chkAccepted">
                                  ข้าพเจ้า รับทราบและยินยอมให้ใช้ข้อมูลนำส่งกรมแรงงาน
                                </label>
                              </div>
                            </div>
                            </div>
                          </div>
                        </div>
                      <?php } ?>

                      <div class="form-group row" align="center">
                        <div class="form-group col-md-2">
                          <a href="personalCourse.php"  class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-circle-left"></i> กลับ
                          </a>
                          </div>
                        <?php
                         $btnAccepted='
                         <button type="button" class="btn btn-success btn-sm" id="btnAccepted" disabled >
                           <i class="fas fa-check"></i> รับทราบการเข้ารับการอบรม
                         </button>';
                         $btnReject='
                         <button type="button" class="btn btn-danger btn-sm" id="btnReject">
                           <i class="fas fa-exclamation"></i> แจ้งไม่เข้าร่วมอบรม
                         </button>';
                         switch ($studentStatus) {
                           case '0':
                             echo "$btnAccepted";
                             echo "$btnReject";
                             break;
                           case '1':
                             echo '<span style="color:#5cb85c;"><i class="fas fa-check-double"></i> รับทราบการเข้ารับการอบรมแล้ว</span>';
                             echo "$btnReject";
                             break;
                           case '2':
                             echo '<span style="color:#d9534f;"><i class="fas fa-user-slash"></i> แจ้งไม่เข้าร่วมอบรมแล้ว </span>';
                             echo $studentRemark." ";
                             echo '<label class="tooltip-info">
                                 <i class="fas fa-info-circle" style="cursor:pointer;color: #31708f;" data-toggle="tooltip" data-placement="top" title="" data-original-title="เมื่อท่านได้ทำการแจ้งไม่เข้าร่วมอบรมแล้ว หากท่านต้องการกลับเข้าสู่การอบรมอีกครัั้ง ต้องดำเนินการแจ้งหัวหน้างานผู้จัดสรร เพื่อปลดล็อค"> </i>
                                 </label>';
                             break;
                           case '3':
                             echo '<span style="color:#d9534f;"><i class="fas fa-user-slash"></i> รับทราบการไม่เข้าร่วมอบรมแล้ว </span>';
                             echo '<label class="tooltip-info">
                                 <i class="fas fa-info-circle" style="cursor:pointer;color: #31708f;" data-toggle="tooltip" data-placement="top" title="" data-original-title="เมื่อท่านได้ทำการแจ้งไม่เข้าร่วมอบรมแล้ว หากท่านต้องการกลับเข้าสู่การอบรมอีกครัั้ง ต้องดำเนินการแจ้งหัวหน้างานผู้จัดสรร เพื่อปลดล็อค"> </i>
                                 </label>';
                             break;
                           default:
                             // code...
                             break;
                         }
                        ?>
                      </div>
                    </form>
                  </div> <!--End Left MD-->

                  <!--Right MD-->
                  <div class="col-md-3">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <i class="fas fa-user-check"></i> Log
                    </div>
                    <div class="panel-body" style="padding-top: 2px;padding-bottom: 2px;">
                      <div class="row dropdown-log">
                        <?php
                          $sql="select std.*,tis.thai_name from studentlog std left join tisusers tis";
                          $sql.=" on std.updateby=tis.employeeno";
                          $sql.=" where std.studentid in ('".$studentID."',0)";
                          $sql.=" order by std.logid";

                          $result=json_decode(pgQuery($sql),true);
                          if($result['code']=="200") {


                            for($i=0;$i<count($result)-1;$i++) {
                              if($i==0) {
                                echo '<ul class="log" style="overflow: hidden; width: auto;  max-height: 500px;height:auto;">';
                              }
                            ?>
                                <li>
                                  <a href="javascript:void(0);" class=" waves-effect waves-block">
                                    <img width="50px;" height="100px;" class="icon-circle img-circle img-responsive user-img" alt="User Picture" style="min-width:50px;width:50px;" src="https://intranet.jasmine.com/hr/office/Data/<?php echo $result[$i]['updateby'];?>.jpg">
                                <div class="log-info">
                                <?php
                                  $studentStatus=$result[$i]['status'];
                                  $logby=strstr($result[$i]['thai_name']," ",true);
                                  switch ($studentStatus) {
                                    case '0':
                                      if($result[$i]['remark']=="") {
                                        echo "<h4>จัดสรร (".$logby.")</h4>";
                                      } else {
                                        echo "<h4>นำกลับ (".$logby.")</h4>";
                                      }
                                      break;
                                    case '1':
                                      echo "<h4>".statusStudentText(1)."</h4>";
                                      break;
                                    case '2':
                                      echo "<h4>".statusStudentText(2)." (".$logby.")</h4>";
                                      break;
                                    case '3':
                                      echo "<h4>".statusStudentText(3)." (".$logby.")</h4>";
                                      break;
                                    case '4':
                                      echo "<h4>".statusStudentText(4)." (".$logby.")</h4>";
                                      break;
                                    default:
                                      // code...
                                      break;
                                  }
                                ?>
                                <p>
                                    <i class="far fa-clock"></i> <?php echo date('d/m/Y H:i:s', strtotime($result[$i]['logupdate']));?>
                                </p>
                                </div>
                                    </a>
                                  </li>
                              <!-- echo statusStudentText($result[$i]['status']); -->
                            <?php
                              if($i==count($result)-2) {
                                echo "</ul>";
                              }
                            }
                          }
                        ?>

                      </div> <!--End row-->
                    </div> <!--End panel body-->
                  </div> <!--End panel Right-->
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
    <div id="confirmModal" class="modal fade" data-backdrop="static" role="dialog" tabindex="-1">
      <div class="modal-dialog">
        <!-- Modal content-->
          <form id="formConfirm" name="formConfirm" class="form-horizontal">
        <div class="modal-content">
          <div class="modal-header MyModalHeader">
            <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
            <h5 class="modal-title"><i class="fas fa-check-double"></i> กรุณาตรวจสอบความถูกต้อง</h5>



          </div>
          <div class="modal-body">

              <input type="hidden" id="personal_id" name="personal_id">
              <div class="row">
                <div class="col-sm-12"  >
                  <div class="form-group row">
                    <label class="control-label col-sm-4">ชื่อ-นามสกุล</label>
                    <div class="col-sm-7">
                      <input type="text" id="thai_name" disabled name="thai_name" style="border:0px;cursor: auto;color:#2C3E50;" class="form-control"/>
                      <!--<span class="form-control-static col-sm-8" style="border: 1px solid #000;" ></span>-->
                    </div>

                  </div>
                  <div class="form-group row">
                    <label class="control-label col-sm-4">หน่วยงาน</label>
                    <div class="col-sm-7">
                        <input type="text" id="division" name="division" disabled style="border:0px;cursor: auto;color:#2C3E50;" class="form-control"/>
                    <!--  <span class="form-control-static col-sm-8" id="position" name="position"></span>-->
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="control-label col-sm-4">ตำแหน่ง</label>
                    <div class="col-sm-7">
                        <input type="text" id="position" name="position" disabled style="border:0px;cursor: auto;color:#2C3E50;" class="form-control"/>
                    <!--  <span class="form-control-static col-sm-8" id="position" name="position"></span>-->
                    </div>
                  </div>

                </div>
                  <div class="col-sm-12"  >
                  <div class="form-group row">
                  <label class="control-label col-sm-4">หมายเลขประจำตัวประชาชน</label>
                <div class="col-sm-7"  >
                  <input type="text" id="personal_id_label" name="personal_id_label" disabled style="border:0px;cursor: auto;color:#2C3E50;" class="form-control"/>
                <!--  <span class="form-control-static col-sm-8" id="personal_id_label" name="personal_id_label"></span>-->
                </div>
                </div>
                </div>
              </div>



          </div> <!--End panel body-->
          <div class="modal-footer" style="margin-top: 0px;">
            <div class="row" style="margin-bottom: 10px;">
              <div class="col-sm-12 text-center">

              <span class="" >เข้าอบรมหลักสูตร <?php echo $nameMarketing;?>  </span>
              </div>
            </div>
            <div class="row">

              <div class="col-sm-12  text-center">

                <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" id="btSave">
                  <i class="fas fa-thumbs-up"></i> ยืนยัน</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                  <i class="fas fa-times-circle"></i> ยกเลิก</button>
              </div>
            </div>
          </div>
        </div> <!--End panel content-->
          </form>
      </div> <!--End panel dialog-->
    </div> <!--End Modal-->

    <div id="rejectModal" class="modal fade" data-backdrop="static" role="dialog" tabindex="-1">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header MyModalHeader">
              <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>

            <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> แจ้งไม่เข้าร่วมอบรม</h5>

          </div>
          <div class="modal-body">
            <form id="formReject" name="formReject" class="form-horizontal">
              <div class="row">
                <div class="col-sm-12">
                <div class="form-group row">
                  <label class="control-label col-sm-3">กรุณาระบุเหตุผล</label>
                  <div class="col-sm-8">
                    <input type="text" placeholder="Ex. เหตุผล" required class="form-control col-sm-8" id="rejectReason" name="rejectReason">
                  </div>
                </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-right">
                  <button type="submit" class="btn btn-success btn-sm" id="btConfirmReject">
                    <i class="fas fa-thumbs-up"></i> ยืนยัน</button>
                  <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i> ยกเลิก</button>
                </div>
              </div>
            </form>
          </div> <!--End panel body-->
        </div> <!--End panel content-->
      </div> <!--End panel dialog-->
    </div> <!--End Modal-->


    <?php include_once("notification.php");?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="assets/js/tisApp.js"></script>
    <script>
    // tooltip demo
    $('.tooltip-info').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>
    <script type="text/javascript">
    function popup(msg) {
      $('#modalContent').html(msg);
      $('#popupModal').modal('show');
    }

    function getJPM() {
        $.ajax({
          type: "POST",
          url: "api/getEmployeeInfoFull.php",
          beforeSend: function()
          {
            $('#loading').show();
          },
          success: function(result){
            try {
              console.log(result);
              var obj = JSON.parse(result);
              $('#thai_name').val(obj[0].first_name+' '+obj[0].last_name);
              $('#position').val(obj[0].title);
              $('#division').val(obj[0].division);


              $('#personal_id').val(obj[0].personal_id);
                $('#personal_id_label').val(obj[0].personal_id);
              $('#btSave').removeAttr('disabled');
            } catch(error) {

              tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Problem read API','error','small','',false);
            /*  bootbox.alert({
                size:'small',
                message:'Problem read API'
              })*/
              console.log(error);
            }
            $('#loading').hide();
          },
          error: function()
          {
            tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Problem read API','error','small','',false);
          /*  bootbox.alert({
              size:'small',
              message:'Cannot call API'
            })*/


            $('#loading').hide();
          }
        });
      }

      $(document).ready(function() {

        $('#chkAccepted').change(function () {
          if($('#chkAccepted').is(':checked')) {
            $('#btnAccepted').removeAttr('disabled');
          } else {
            $('#btnAccepted').prop('disabled', true);
          }
        });

        $('#btnAccepted').click(function(){
          $('#confirmModal').modal('show');
          $('#btSave').prop('disabled', true);
          getJPM();
        });

        $('#btnReject').click(function(){
          $('#rejectModal').modal('show');
        });

        $("#btSave").click(function(){
          event.preventDefault();
          updateStudentStatus(1);
        });

        $("#btConfirmReject").click(function(){
          event.preventDefault();
          if($('#rejectReason').val()!=''){
              $('#rejectModal').modal('hide');
            updateStudentStatus(2);
          }else{
            $('#rejectReason').focus();
          }

        });

        function updateStudentStatus(status) {
          //Update status
          var personal_id="";
          var remark="";
          if(status==1) {
            personal_id=$('#personal_id').val();
          } else {
            remark=$('#rejectReason').val();
          }
          var formData = {
            'courseID' : <?php echo $courseID;?>,
            'studentID' : <?php echo $studentID;?>,
            'personal_id' : personal_id,
            'status' : status,
            'remark':remark
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
              console.log('update status success');
              console.log(result);
              try {
                var obj = JSON.parse(result);
                if(obj.code=="200") {
                  $('#loading').hide();
                  tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','small','personalCourse.php',true);
              //  popup("Completed save.");
              //    popup("Status updated.");
                  //$('#loading').hide();
                //  location.reload();
                } else {
                  tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>'+obj.message+'<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','personalCourse.php',false);
                //  popup(obj.message);
                  //$('#debug').html(obj.message);
                  $('#loading').hide();
                }
              } catch (err) {
                console.log('update status error');
                console.log(result);
                tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>API return unknow json<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','personalCourse.php',false);
              //  popup("Update status Error : API return unknow json");
                $('#loading').hide();
              }
              //location.reload();
            },
            error: function()
            {
              tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Cannot call save api<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','',false);
            //popup("Cannot call save api");
              $('#loading').hide();
            }
          });
        }
      });
    </script>
  </body>
</html>
