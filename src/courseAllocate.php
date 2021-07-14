<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("iscoordinator","index"); //If need permission enable here
include_once("lib/myLib.php");

//$_SESSION["employee_id"]="RO2349";
//$_SESSION["employee_id"]="RO1637";
//Initial form value
$courseID = isset($_GET["courseID"])?$_GET["courseID"]:'';
//$_SESSION["employee_id"]="RO1637";
//RO3055 RO1622
if($courseID<>"") {
  $sql="select * from course where courseid=".$courseID;
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $isFound=1;
    $approxstudent=$result[$i]["approxstudent"];
    $nameOfficial=$result[$i]['nameofficial'];
    $nameMarketing=$result[$i]['namemarketing'];
    $requirement=$result[$i]["requirement"];
  }

  if($isFound==0) { //Wrong access_token
    $url="error.php?code=courseAllocate";
    header("Location: ".$url);
  }

  //Have course info next check right to allocate
  $sql="select * from courseAllocate ";
  $sql.="where courseId=".$courseID;
  $resultRight=json_decode(pgQuery($sql),true);
  $existsQuota=0;
  $hasQuota=0;
  for($i=0;$i<count($resultRight)-1;$i++) {
    $existsQuota=1;
    if($resultRight[$i]["employeeno"]==$_SESSION["employee_id"]) {
      //have right to allocate quota
      $hasQuota=1;
      $allocateQuota=$resultRight[$i]["allocatequota"];
      $allocateAssign=$resultRight[$i]["allocateassign"];
      $allocateLeft=$resultRight[$i]["allocateleft"];
      $allocateUsed=$resultRight[$i]["allocateused"];
      $allocatelevel=$resultRight[$i]["allocatelevel"];
    }
  }
  if($existsQuota) { //Have allocate check permission
    if($hasQuota==0) { //No right to allocate quota
      $url="forbidden.php?permission=allocatable&code=courseAllocate";
      header("Location: ".$url);
    }
  } else {
    //No allocate create begining if is admin
    if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
      $allocateQuota=$approxstudent;
      $allocateAssign=0;
      $allocateLeft=$approxstudent;
      $allocateUsed=0;
      $allocatelevel=1;

      $sql="insert into courseAllocate";
      $sql.="(courseid,thai_name,employeeno,position,email,allocatequota,allocateassign,allocateleft,allocateused,allocatelevel) select ";
      $sql.=prepareString($courseID).",";
      $sql.="thai_name,employeeno,position,email,";
      $sql.=prepareNumber($allocateQuota).",";
      $sql.=prepareNumber($allocateAssign).",";
      $sql.=prepareNumber($allocateLeft).",";
      $sql.=prepareNumber($allocateUsed).",";
      $sql.=prepareNumber($allocatelevel);

      $sql.=" from tisusers where employeeno='".$_SESSION["employee_id"]."'";
      $result=json_decode(pgQuery($sql),true);
    } else {
      $url="forbidden.php?permission=allocatable&code=courseAllocate";
      header("Location: ".$url);
    }
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
    <title>TIS : Course Allocate</title>
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
    .tabulator .tabulator-header .tabulator-headers .tabulator-col{
      background-color:#3C312D !important;
      color:#FFFFFF !important;
    }
    .tabulator-row .tabulator-cell .tabulator-data-tree-branch {
      display: inline-block;
      vertical-align: middle;
      height: 9px;
      width: 7px;
      margin-top: -9px;
      margin-right: 5px;
      border-bottom-left-radius: 1px;
      border-left: 2px solid #3C312D !important;
      border-bottom: 2px solid #3C312D  !important;
    }
    .tabulator-row .tabulator-cell .tabulator-data-tree-control {
      display: -ms-inline-flexbox;
      display: inline-flex;
      -ms-flex-pack: center;
          justify-content: center;
      -ms-flex-align: center;
          align-items: center;
      vertical-align: middle;
      height: 11px;
      width: 11px;
      margin-right: 5px;
      border: 1px solid #3C312D !important;
      border-radius: 2px;
      background: rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
  </style>
  <link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="lib/gallery/hes-gallery-master/hes-gallery.min.css">
  <link rel="stylesheet" type="text/css" href="lib/tabulator-4.2.3/css/tabulator.css">
  <link rel="stylesheet" type="text/css" href="lib/tabulator-4.2.3/css/tabulator_tis.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables.min.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
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
                <h5>มอบสิทธิ์จัดสรรผู้เข้ารับการอบรม</h5>
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
                <form id="formCourseAllocate" name="formCourseAllocate" class="form-horizontal">
                  <div class="row">
                    <input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
                    <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-user-plus"></i> เลือกผู้มีสิทธิ์จัดสรร
                      </div>
                      <div class="panel-body">
                        <div class="form-group row">
                            <div class="col-sm-12">
                              <button type="button" class="btn btn-warning btn-sm pull-right" id="btnInviteStudent"
                                <i class="fas fa-user-plus"></i> เลือกผู้เข้าอบรมด้วยตนเอง
                              </button>
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">ผู้เข้ารับการอบรม</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-9 nopadding"><?php echo $approxstudent;?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-3">การจัดสรร</label>
                          <div class="col-sm-9">
                            <span class="form-control-static col-sm-9 nopadding">
                              สิทธิ์ <span id='allocateQuota' name='allocateQuota'><?php echo $allocateQuota;?></span>
                              เลือกผู้เรียน <span id='allocateUsed' name='allocateUsed'><?php echo $allocateUsed;?></span>
                              จัดสรรแล้ว <span id='allocateAssign' name='allocateAssign'><?php echo $allocateAssign;?></span>
                              รอจัดสรร <span id='allocateLeft' name='allocateLeft'><?php echo $allocateLeft;?></span>
                            </span>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">มอบสิทธิ์จัดสรร</label>
                            <div class="col-sm-9" id="divAssign">
                              <div class="col-sm-11 nopadding">
                                <select id="listAssignEmployee" name="listAssignEmployee" class="form-control" multiple="multiple">
                                </select>
                              </div>
                              <div class="col-sm-1 paddingButton">
                                <button type="button" class="btn btn-info btn-sm" id="btnAssignAllocate">
                                <i class="fas fa-user-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                      </div> <!--End panel body-->
                    </div> <!--End panel Left1-->

                    <!--panel Left2-->
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-person-booth"></i> ผู้จัดสรร

                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <div id="divTable"></div>
                          </div>
                        </div>
                      </div>
                    </div> <!--End panel Left2-->

                    </div> <!--End Left MD-->

                    <div class="col-md-4" id="divEditQuota" style="display:none">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-edit"></i> แก้ไขสิทธิ์
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group nopadding">
                              <label class="control-label col-sm-2">สิทธิ์</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" id="userAllocateQuota" name="userAllocateQuota">
                              </div>
                              <div class="col-sm-2 nopadding">
                                <button type="button" class="btn btn-success btn-sm" id="btUpdateAllocate">
                                <i class="fas fa-save"></i> บันทึก
                                </button>
                              </div>
                              <div class="col-sm-3">
                                <button type="button" class="btn btn-danger btn-sm" id="btCancelAllocate">
                                <i class="fas fa-times-circle"></i> ยกเลิกสิทธิ์
                                </button>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-12">
                                เลือกผู้เรียน
                                <span id="userAllocateused" name="userAllocateused"></span>
                                จัดสรรแล้ว
                                <span id="userAllocateassign" name="userAllocateassign"></span>
                                รอจัดสรร
                                <span id="userAllocateleft" name="userAllocateleft"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> <!--End panel body-->
                    </div> <!--End panel edit-->
                  </div> <!--End col edit-->

                    <div class="col-md-4" id="divStudent" style="display:none">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-user-check"></i> ผู้เรียนที่เลือก
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12">
                            <table id="tbStudent" class='table table-striped'>
                              <thead>
                                <th></th>
                                <th>ข้อมูลพนักงาน</th>
                                <th>สถานะ</th>
                                <th>ผู้เลือก</th>
                              </thead>
                              <tbody>
                                <?php
                                $sql="select * from courseStudent ";
                                $sql.="where courseId=".$courseID;
                                $result=json_decode(pgQuery($sql),true);
                                //$invitedStudent=count($result);
                                $invitedStudent=0;
                                if($result['code']=="200") {
                                  for($i=0;$i<count($result)-1;$i++) {
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
                                        <?php echo $result[$i]['employeeno'];?><br/>
                                        <?php echo $result[$i]['thai_name'];?><br/>
                                      </td>
                                      <td>
                                        <?php echo statusStudentText($result[$i]['status']);?>
                                      </td>
                                      <td>
                                        <?php echo $result[$i]['assignby'];?>
                                      </td>
                                    </tr>
                                    <?php
                                    //$invitedStudent++;
                                  }
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div> <!--End panel body-->
                    </div> <!--End panel Right-->
                  </div> <!--End panel student-->

                  </div> <!--End setting row-->
                </form>
              </div>
            </div><div id="debug">
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
            กรุณายืนยันการเลือกผู้มีอำนาจจัดสรร
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <br/>
          </div>
          <div class="modal-body">
            <form id="formSelected" name="formSelected" class="form-horizontal">
              <input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
              <input type="hidden" id="allocatelevel" name="allocatelevel" value="<?php echo $allocatelevel;?>">
              <div class="row">
              <table id="tbPrepared" class="table table-striped table-sm table-bordered">
                <thead>
                  <tr>
                    <th colspan="3" class="success" style="text-align:center">
                      สิทธิ์ <span id='allocateQuotaDisplay' name='allocateQuotaDisplay'><?php echo $allocateQuota;?></span>
                      เลือกผู้เรียน <span id='allocateUsedDisplay' name='allocateUsedDisplay'><?php echo $allocateUsed;?></span>
                      จัดสรรแล้ว <span id='allocateAssignDisplay' name='allocateAssignDisplay'><?php echo $allocateAssign;?></span>
                      รอจัดสรร <span id='allocateLeftDisplay' name='allocateLeftDisplay'><?php echo $allocateLeft;?></span>
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
    <script src="lib/js/inputFilter.js"></script>
    <script src="lib/select2-4.0.5/js/select2.min.js"></script>
    <script src="lib/select2-4.0.5/js/i18n/th.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="assets/js/formatSelect2Emp.js"></script>
    <script src="lib/gallery/hes-gallery-master/hes-gallery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/tabulator-4.2.3/js/tabulator.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/Responsive-2.2.2/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
      function popup(msg) {
          $('#modalContent').html(msg);
          //$('#modalContent').text(msg);
          $('#popupModal').modal('show');
      }

      function calculateAssign(txt) {
        console.log("Calculated");
        var self='<?php echo $_SESSION["employee_id"];?>';
        <?php
         if ($allocateLeft=="") {
            echo "var max=0;\n";
         } else {
           echo "var max=".$allocateLeft.";\n";
         }
        ?>
        if(txt!=undefined) {
          //calculate when key value
          var inputval=parseInt(txt.value);
          //Validate Max value from all other input
          var totalOther=0;
          $('input[name="allocateQuota[]"]').not(txt).each(function() {
            var employeeno=$(this).data('employeeno');
            if(employeeno!=self) {
              totalOther += parseInt($(this).val());
            }
          });
          var allocateleft=max-totalOther;

          if (inputval>allocateleft) {
            //popup('สิทธิ์คงเหลือ ' + allocateleft + ' ไม่สามารถให้ค่ามากกว่านี้ได้')
            bootbox.alert({
              size:'small',
              message:'สิทธิ์คงเหลือ ' + allocateleft + ' ไม่สามารถมากกว่านี้ได้'
            })
            txt.value=allocateleft;
            txt.focus();
          }
          //Validate Lower value from already allocateed
          var selectUsed=$(txt).data('used');
          if (inputval<selectUsed) {
            bootbox.alert({
              size:'small',
              message:'จัดสรรไปแล้ว ' + selectUsed + ' ไม่สามารถต่ำกว่านี้ได้'
            })
            txt.value=selectUsed;
            txt.focus();
          }
        }
        var totalSelected=0;
        $('input[name="allocateQuota[]"]').each(function() {
          var employeeno=$(this).data('employeeno');
          if(employeeno!=self) {
            totalSelected += parseInt($(this).val());
          }
        });
        var allocateleft=max-totalSelected;
        if (allocateleft==0) {
          disableAdded();
        } else {
          enableAdded();
        }
        console.log('totalSelected='+totalSelected);
        console.log('allocateleft='+allocateleft);
        $('#allocateTotal').text(totalSelected);
        $('#allocateLeft').text(allocateleft);
        $('#allocateTotalDisplay').text(totalSelected);
        $('#allocateLeftDisplay').text(allocateleft);
      }

      function disableAdded() {
        $('#btnAssignAllocate').prop('disabled', true);
      }

      function enableAdded() {
        $('#btnAssignAllocate').removeAttr('disabled');
      }

      //Gen Table Data
      var placeholder = "<span>Loading....</span>";
      var tabledata=
        <?php
        $sql="select * ";
        $sql.=" ,(SELECT MAX(allocatelevel) FROM courseAllocate WHERE courseid=".$courseID." ) AS maxlevel ";
        $sql.=" ,(SELECT allocatelevel FROM courseAllocate WHERE courseid=".$courseID." and employeeno='".$_SESSION['employee_id']."' ) AS curlevel ";
        $sql.=" from courseAllocate";
        $sql.=" where courseid=".$courseID." ";
        $sql.=" AND ((allocatelevel>".$allocatelevel.") OR (allocatelevel=".$allocatelevel." AND employeeno='".$_SESSION['employee_id']."'))";
        $sql.=" order by allocateid";
        $result=json_decode(pgQuery($sql),true);

        if($result['code']=="200") {
          for($i=0;$i<count($result)-1;$i++) {
            $allocateAll[$i]=$result[$i];
          }
          $maxlevel=$result[0]['maxlevel'];
          $curlevel=$result[0]['curlevel'];
          $allocateTemp=$allocateAll;
          $round=0;
          $roundPre=0;
          for ($i=$maxlevel; $i >0 ; $i--) {
            $temp=$maxlevel-$round;
            foreach ($allocateTemp as $key=>$value) {
              if($value['allocatelevel']>=$curlevel){
                if($i==$maxlevel){
                  if($value['allocatelevel']==$maxlevel){
                    $templevel[$round][$value['assignby']][]=$allocateTemp[$key];
                    unset($allocateTemp[$key]);
                  }
                }else{
                  if($i==$temp && $value['allocatelevel']==$i){
                    if($value['allocatelevel']==$curlevel){
                      if($value['employeeno']==$_SESSION['employee_id']){
                        if(isset($templevel[$roundPre][$value['employeeno']])){
                            $allocateTemp[$key]['_children']=$templevel[$roundPre][$value['employeeno']];
                            $templevel[$round][$value['assignby']][]=$allocateTemp[$key];
                        }else{
                              $templevel[$round][$value['assignby']][]=$allocateTemp[$key];
                        }
                      }
                    }else{
                      if(isset($templevel[$roundPre][$value['employeeno']])){
                          $allocateTemp[$key]['_children']=$templevel[$roundPre][$value['employeeno']];
                          $templevel[$round][$value['assignby']][]=$allocateTemp[$key];
                      }else{
                            $templevel[$round][$value['assignby']][]=$allocateTemp[$key];
                      }
                    }

                    unset($allocateTemp[$key]);
                  }
                }
              }

            }
            $roundPre=$round;
            $round++;
          }

          if(isset($templevel[$roundPre])){
            foreach ($templevel[$roundPre] as $key => $value) {
                $allocateTree=$value;
            }
          }elseif(isset($templevel[$round-$curlevel])){
            foreach ($templevel[$round-$curlevel] as $key => $value) {
                $allocateTree=$value;
            }
          }else{
            $allocateTree=$templevel;
          }
          echo  json_encode($allocateTree);

        }else{
          echo "[]";
        }
        ?>
      ;
      //$('#debug').html("<?php echo $sql;?>");

      var table = new Tabulator('#divTable',{
        placeholder:placeholder,
        data:tabledata,
        layout:"fitColumns",
        dataTree:true,
        selectable:1,
        columns:[
         {title:"",width:60,field:"employeeno",formatter:function(cell, formatterParams, onRendered){
              var employeeno=cell.getValue();
              var row = cell.getRow();
              var data = row.getData();
              var imgsrc="https://intranet.jasmine.com/hr/office/Data/"+data.employeeno+".jpg";
              var ahref="<div class='hes-gallery'>";
              var fullName=data.thai_name.split(" ");
              var firstName=fullName[0];
              var imgTag=`<img class='media-object img-thumbnail user-img'
                data-subtext='`+firstName+`'
                data-alt='`+data.thai_name+`'
                src='`+imgsrc+`'
                style='min-height:60px;height:60px;text-align:center;'>`;
              return ahref+imgTag+'</a></div>';
            }
            ,headerSort:false,align:"center",
         },
         {title:"รหัสพนักงาน", field:"employeeno",width:113},
         {title:"ชื่อ/ตำแหน่ง",field:"thai_name",formatter:function(cell, formatterParams, onRendered){
             var row = cell.getRow();
             var data = row.getData();
             return data.thai_name+'<br/>'+data.position;
           },headerSort:false
         },
         {title:"สิทธิ์", field:"allocatequota",width:60,align:"center"},
         {title:"เลือกผู้เรียน", field:"allocateused",width:110,align:"center"},
         {title:"จัดสรร", field:"allocateassign",width:80,align:"center"},
         {title:"รอจัด", field:"allocateleft",width:80,align:"center"},
        ],
        rowFormatter:function(row){
          switch (row.getData().allocatelevel) {
             case 1:
               row.getElement().style.backgroundColor = "#776960";
               break;
             case 2:
                row.getElement().style.backgroundColor = "#897667";
                break;
            case 3:
                row.getElement().style.backgroundColor = "#B1A396";
                break;
            case 4:
                row.getElement().style.backgroundColor = "#CFC6BD";
                break;
            default:
              row.getElement().style.backgroundColor = "#FFFFFF";
           }
        },
        dataTreeRowExpanded:function(row, level){
         //row.getElement().style.backgroundColor = "#5555AA";
        },
        dataTreeRowCollapsed:function(row, level){
          //row.getElement().style.backgroundColor = "#FFFFFF";
        },
        tooltipsHeader:true,
        tooltips:true,
        tooltipGenerationMode:"hover",
        rowSelected:function(row){
          var data = row.getData();
          displayStudent(data.employeeno);
          displayUserAllocate(data.allocatequota,data.allocateused,data.allocateassign,data.allocateleft,data.allocateid,data.employeeno,data.allocatelevel);
        },
      }); //end tabulator

      var tbStudent=$('#tbStudent').DataTable({
        "ordering": false,
        "columnDefs": [
          {
              "targets": 3,
              "visible": false
          },
        ],
        "dom": 't',
        "language": {
          "emptyTable": "ผู้เข้ารับการอบรมยังไม่ถูกเลือก",
          "zeroRecords": "ผู้เข้ารับการอบรมยังไม่ถูกเลือก",
          "infoFiltered":"",
        },
      });


      function displayStudent(assignBy) {
        $('#divStudent').show();
        tbStudent.columns(3).search(assignBy).draw();
      }

      function displayUserAllocate(allocatequota,allocateused,allocateassign,allocateleft,allocateid,employeeno,allocatelevel) {
        $('#divEditQuota').show();
        $('#userAllocateQuota').val(allocatequota);
        $('#userAllocateused').text(allocateused);
        $('#userAllocateassign').text(allocateassign);
        $('#userAllocateleft').text(allocateleft);

        $('#btUpdateAllocate').data('allocateid',allocateid);
        $('#btUpdateAllocate').data('allocatequota',allocatequota);

        $('#btCancelAllocate').data('allocateid',allocateid);
        $('#btCancelAllocate').data('allocatequota',allocatequota);
        $('#btCancelAllocate').data('allocateleft',allocateleft);

        //Enable change only 1st child
        var childlevel=<?php echo $allocatelevel?>+1;
        if(allocatelevel==childlevel) {
          $('#btUpdateAllocate').prop('disabled', false);
          $('#btCancelAllocate').prop('disabled', false);
        } else {
          $('#btUpdateAllocate').prop('disabled', true);
          $('#btCancelAllocate').prop('disabled', true);
        }
      }

      $(document).ready(function() {
        $('input[name="allocateQuota[]"]').inputFilter(function(value) {
            return /^\d*$/.test(value); });
        $('input[name="allocateQuota[]"]').on('change', function(){
          calculateAssign(this);
        });
        calculateAssign();

        prepareListSameOU();
        function prepareListSameOU() {
          var arr_data = [];
          <?php
            $sql="select * from tisUsers ";
            $sql.=" where employeeNo<>'".$_SESSION["employee_id"]."'";
            if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1") {
            } else {
              //show all for Admin & HQ
              $sql.=" and department in (select department from tisusers where employeeNo='".$_SESSION["employee_id"]."')";
            }
            $result=json_decode(pgQuery($sql),true);
            if($result['code']=="200") {
              for($i=0;$i<count($result)-1;$i++) {
              ?>
                var tempObj = {};
                tempObj = {
                  "id":'<?php echo $result[$i]['employeeno'];?>',
                  "text":'<?php echo $result[$i]['employeeno']." ".$result[$i]['thai_name']?>',
                  "thai_name":'<?php echo $result[$i]['thai_name'];?>',
                  "company":'<?php echo $result[$i]['company'];?>',
                  "department":'<?php echo $result[$i]['department'];?>',
                  "section":'<?php echo $result[$i]['section'];?>',
                  "division":'<?php echo $result[$i]['division'];?>',
                  "position":'<?php echo $result[$i]['position'];?>',
                  "email":'<?php echo $result[$i]['email'];?>',
                };
                arr_data.push(tempObj);
              <?php
              }
            }
          ?>
          bindAssignEmployee(arr_data);
        }

        function bindAssignEmployee(arr_data) {
          $('#listAssignEmployee').select2({
            placeholder: 'ป้อนรหัสพนักงาน หรือชื่อ-นามสกุล',
            data: arr_data,
            escapeMarkup: function (markup) { return markup; }, // Allow html
            templateResult: formatEmpRepo,
        	  templateSelection: formatRepoEmpSelection
          }); //end select2
        }

        $('#btnAssignAllocate').click(function(){
          var data = $("#listAssignEmployee").select2("data");
          var isselected=false;
          $('#loading').show();
          $.each( data, function( key, value ) {
            isselected=true;
            var employeeNo=value["id"];
            var thai_name=value["thai_name"];
            var company=value["company"];
            var department=value["department"];
            var section="";
            if(value["section"]!=null) {
              section=value["section"];
            }
            var division=value["division"];
            var position=value["position"];
            var email=value["email"];
            validateEmp(employeeNo,thai_name,company,department,section,division,position,email);
          });
          $('input[name="allocateQuota[]"]').inputFilter(function(value) {
            return /^\d*$/.test(value); });
          $('input[name="allocateQuota[]"]').on('change', function(){
            calculateAssign(this);
          });
          $('#loading').hide();
          if(isselected) {
            displayTable();
            $('#confirmModal').modal('show');
          }
        });

        function validateEmp(employeeNo,thai_name,company,department,section,division,position,email) {
          if (employeeNo=='') {
            return false;
          }
          var isRejected=checkRejected(employeeNo);
          if (isRejected[0]) {
            $('#tbRejected tbody').append(genRowError(employeeNo,thai_name,company,department,section,division,position,email,isRejected[1]));
          } else {
            $('#tbPrepared tbody').append(genRowOK(employeeNo,thai_name,company,department,section,division,position,email));
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
              url: "api/checkAllocated.php",
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
                console.log('err func');
                reply[0]=true;
                reply[1]="Error access checking api";
              }
          });
          // console.log('after');
          // console.log(reply);
          return reply;
        }

        function genRowOK(employeeNo,thai_name,company,department,section,division,position,email) {
          var pic = employeeNo.replace(/\//g, '');
          var divText=`
          <tr>
            <td style="width:80px;text-align:center">
            <input type="hidden" id="EmployeeNo[]" name="EmployeeNo[]" readonly="true" value="`+employeeNo+`">
            <input type="hidden" id="thai_name[]" name="thai_name[]" readonly="true" value="`+thai_name+`">
            <input type="hidden" id="position[]" name="position[]" readonly="true" value="`+position+`">
            <input type="hidden" id="email[]" name="email[]" readonly="true" value="`+email+`">
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
            </td>
            <td style="width:200px">
              <div class="row">
                <label class="control-label col-sm-4">จัดสรร</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="allocateQuota[]" name="allocateQuota[]" value=0 style="width:50px;">
                </div>
              </div>
            </td>
          </tr>
          `;
          return divText;
        }

        function genRowError(employeeNo,thai_name,company,department,section,division,position,email,status) {
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
          $('#confirmSummary').text('เลือกทั้งหมด '+total+' คน');
          if(totalSelected==0) {
            $('#tbPrepared').hide();
            $('#btSave').prop('disabled', true);
          } else {
            $('#tbPrepared').show();
            $('#confirmSummary').append('มอบหมายเพิ่มได้ '+totalSelected+' คน');
            $('#btSave').removeAttr('disabled');
          }
          if(totalRejected==0) {
            $('#tbRejected').hide();
          } else {
            $('#tbRejected').show();
            $('#confirmSummary').append('ติดปัญหา '+totalRejected+' คน');
          }
        }

        $("#btSave").click(function(){
          event.preventDefault();
          //alert($('#formSelected input[name=email]').val());
          var haveZero=false;
          $('input[name="allocateQuota[]"]').each(function() {
            if($(this).val()==0) {
              popup("กรุณาระบุจำนวนจัดสรร");
              haveZero=true;
            }
          });
          if(haveZero) {
            return;
          }
          var nameOfficial="<?php echo $namemarketing;?>";
          var assigner="<?php echo $_SESSION["thai_fullname"];?>";
          var assignerMail="<?php echo $_SESSION["email"];?>";
          var email="";
          $('#formSelected input[name="email[]"]').each(function() {
            email=$(this).val();
            var courseID=<?php echo $courseID;?>;
            //email="wasupak.c@jasmine.com";
            var formData = {
                'sendto': email,
                'ccto': assignerMail,
                'subject': "TIS : คุณได้รับสิทธิ์จัดสรรผู้เข้ารับการอบรม",
                'bodyhtml': `
                <h4>คุณได้รับสิทธิ์จัดสรรผู้เข้ารับการอบรม</h4>
                <br>
                หลักสูตร : `+nameOfficial+`<br/>
                ผู้มอบหมาย : `+assigner+`<br/><br/><br/>
                กรุณา login ระบบ TIS เพื่อตรวจสอบ<br/>
                <a href='https://app.jasmine.com/tis/courseInvite.php?courseID=`+courseID+`'>Login</a>
                <br><br><HR>
                TIS : Training Information System
                `
            };
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
                  } else {
                    popup("Send mail error : "+obj.message);
                    $('#loading').hide();
                  }
                } catch (err) {
                  popup("Send mail error : API return unknow json");
                  $('#loading').hide();
                }
              },
              error: function()
              {
                popup("Cannot call send mail api");
                $('#loading').hide();
              }
            });
          });

          $.ajax({
              type: "POST",
              url: "db/saveCourseAllocate.php",
              data: $("#formSelected").serialize(),
              beforeSend: function()
              {
                $('#loading').show();
              },
              success: function(result){
                try {
                  console.log('saved');
                  var obj = JSON.parse(result);
                  console.log('result='+result);
                  console.log('obj='+obj)
                  if(obj.code=="200") {
                    popup("Completed save.");
                    $('#loading').hide();
                    location.reload();
                  } else {
                    popup(obj.message);
                    if(obj.code=="998") {
                      //location.href='courseAllocate.php?courseID=<?php echo $courseID;?>';
                    }
                    $('#loading').hide();
                  }
                } catch (err) {
                  console.log(result);
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

        $('#btCancelAllocate').click(function(){
          var allocateID = $(this).data('allocateid');
          var originalQuota = $(this).data('allocatequota');
          var allocateLeft = $(this).data('allocateleft');

          if(originalQuota!=allocateLeft) {
            popup("มีการใช้สิทธิ์จัดสรรแล้ว ไม่สามารถยกเลิก");
            return;
          }
          console.log("original:"+originalQuota+" left:"+allocateLeft)

          bootbox.confirm({
              size: "small",
              title: "ยืนยันการลบ",
              message: "ลบ "+name,
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
                  updateAllocate(allocateID,originalQuota,0,"ผู้จัดสรร ยกเลิกสิทธิ์")
                }
              }
          });
        });

        $('#btUpdateAllocate').click(function(){
          var allocateID = $(this).data('allocateid');
          var originalQuota = $(this).data('allocatequota');
          var allocateQuota=$('#userAllocateQuota').val();

          //For validate value
          var allocateused = $('#userAllocateused').text();
          var allocateassign = $('#userAllocateassign').text();
          var allocateleft = $('#userAllocateleft').text();

          var maxAvailiable=<?php echo $allocateLeft;?>+originalQuota;
          if(allocateQuota>maxAvailiable) {
            popup("มอบหมายได้สูงสุด "+maxAvailiable);
            $('#userAllocateQuota').val(maxAvailiable);
            return;
          }

          var minAvailiable=originalQuota-allocateleft;
          if(allocateQuota<minAvailiable) {
            popup("สิทธิ์ถูกใช้งานแล้ว ลดลงได้ต่ำสุด "+minAvailiable);
            $('#userAllocateQuota').val(minAvailiable);
            return;
          }

          console.log('Change from '+originalQuota+' to '+allocateQuota);
          updateAllocate(allocateID,originalQuota,allocateQuota,"ผู้จัดสรร ปรับปรุงสิทธิ์")
        });

        $('#confirmModal').on('hidden.bs.modal', function () {
         $('#tbPrepared tbody').empty();
         $('#tbRejected tbody').empty();
         calculateAssign();
        });

        function updateAllocate(allocateID,originalQuota,allocateQuota,remark) {
          //Update status
          console.log('send original='+originalQuota);
          var formData = {
            'courseID' : <?php echo $courseID;?>,
            'allocateID' : allocateID,
            'originalQuota' : originalQuota,
            'allocateQuota' : allocateQuota,
            'remark' : remark
          };
          $.ajax({
            type: "POST",
            url: "db/updateAllocate.php",
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
                  popup("Allocate updated.");
                  location.reload();
                } else {
                  popup(obj.message);
                  $('#loading').hide();
                }
              } catch (err) {
                console.log('update Allocate error');
                console.log(result);
                popup("Update Allocate Error : API return unknow json");
                $('#loading').hide();
              }
            },
            error: function()
            {
              popup("Cannot call save api");
              $('#loading').hide();
            }
          });
        }

        $('#popupModal').on('hidden.bs.modal', function () {

            var modalContent=$('#modalContent').html();
            if (modalContent.includes("มอบหมายได้สูงสุด")) {
              $('#userAllocateQuota').select();
            }
            if (modalContent.includes("สิทธิ์ถูกใช้งานแล้ว")) {
              $('#userAllocateQuota').select();
            }
            if (modalContent.includes("กรุณาระบุจำนวนจัดสรร")) {
              $('#btnAssignAllocate').click();
            }
        });
        //displayStudent("");

        $("#btnInviteStudent").click(function(){
          location.href='courseInvite.php?courseID=<?php echo $courseID;?>';
        });
      }); //end document.ready
    </script>
  </body>
</html>
