<?php
include_once("lib/myOAuth.php");
require_once("lib/chkLogin.php"); //If need login enable here
//restrict("istraining","trainerInternal"); //If need permission enable here

//Initial form value
$trainerID = isset($_GET["trainerID"]) ? $_GET["trainerID"] : '';
$empPic = "assets/img/guest.png";

if ($trainerID <> "") {
  $sql = "select * from trainer where trainerID=" . $trainerID;
  $result = json_decode(pgQuery($sql), true);
  $isFound = 0;
  for ($i = 0; $i < count($result) - 1; $i++) {
    $isFound = 1;
    $trainer_type = $result[$i]['trainer_type'];
    $employeeNo = $result[$i]['employeeNo'];
    $empPic = "https://intranet.jasmine.com/hr/office/Data/" . $employeeNo . ".jpg";
    $th_initial = $result[$i]['th_initial'];
    $thai_name = $result[$i]["thai_name"];
    $position = $result[$i]["position"];
    $department = $result[$i]["department"];
    $company = $result[$i]["company"];
    $section = $result[$i]["section"];
    $division = $result[$i]["division"];
    $workplace = $result[$i]["workplace"];
    $telephone = $result[$i]["telephone"];
    $email = $result[$i]["email"];
    $expends = $result[$i]["expends"];
    $trainerRemark = $result[$i]["trainerRemark"];
    $lastupdate = $result[$i]["lastupdate"];
    $spe_courses = $result[$i]["spe_courses"];
    $course_em = $result[$i]["course_em"];
    $gen = $result[$i]["gen"];
    $year_train = $result[$i]["year_train"];
    $studyinfo =$result[$i]["studyinfo"];
    $workinfo =$result[$i]["workinfo"];
    $traininfo =$result[$i]["traininfo"];
  }
  if ($isFound == 0) { //Wrong access_token
    $url = "error.php?code=trainerinternal";
    header("Location: " . $url);
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>TIS : วิทยากรภายใน</title>
  <?php include_once("basicHeader.php"); ?>
  <style>
    /* Safari */
    @-webkit-keyframes spin {
      0% {
        -webkit-transform: rotate(0deg);
      }

      100% {
        -webkit-transform: rotate(360deg);
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .form-horizontal .control-label {
      /* text-align:right; */
      text-align: left;
    }

    input:required {
      border-color: #f28d68;
    }

    .control-label {
      margin-left: 150px;
      text-align: left !important;
    }
    .control-label1 {
      margin-top: 7px !important;
      text-align: left !important;
    }
    .select2-container {
      height: 34px !important;
      ;
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
  <link href="lib/bootstrap-fileinput-v4.5.2-0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body>
  <div id="wrap">
    <?php $menu = "trainer" ?>
    <?php $submenu = "trainerInternal.php" ?>
    <?php include_once("top.php"); ?>
    <?php include_once("left.php"); ?>
    <div id="content">
      <div class="outer">
        <div class="inner">
          <div class="box">
            <header>

              <ol class="breadcrumb">
                <li>
                  <a href="">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i> วิทยากร</a>
                </li>
                <li>
                  <a href="trainerInternal.php">
                    <i class="fas fa-fw fa-address-book"></i> วิทยากรภายใน
                  </a>
                </li>
                <li class="active">
                  <i class="fas fa-user"></i> ดูข้อมูลวิทยากรภายใน
                </li>
              </ol>
            </header>
            <div class="body">
              <div id='loading'>
                <div class="loading-backdrop">
                </div>
                <div class="loading-img">
                  <img src="assets/img/Loading-tis.gif" width="400px" />
                </div>
              </div>
              <div class="row">
                <!--Right Panel-->
                <form id="formJPM" name="formJPM" class="form-horizontal">
                  <input type="hidden" id="trainerID" name="trainerID" value="<?php echo $trainerID; ?>">
                  <input type="hidden" id="trainer_type" name="trainer_type" value="<?php echo $trainer_type; ?>">
                  <div class="col-md-8">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-user"></i>
                        Basic Information (JPM)
                      </div>
                      <div class="panel-body">
                        <div class="form-group row">
                          <label class="control-label col-sm-2">รหัสพนักงาน</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $employeeNo = isset($employeeNo) ? $employeeNo : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-2" >ชื่อ-สกุล</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $th_initial = isset($th_initial) ? $th_initial : ''; ?>
                            <?php echo $thai_name = isset($thai_name) ? $thai_name : '-'; ?>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="control-label col-sm-2" >ตำแหน่ง</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $position = isset($position) ? $position : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >department</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $department = isset($department) ? $department : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-2" >section</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $section = isset($section) ? $section : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-2" >division</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $division = isset($division) ? $division : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >company</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $company = isset($company) ? $company : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-2" >Telephone</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $telephone = isset($telephone) ? $telephone : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >email</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $email = isset($email) ? $email : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >สถานที่ทำงาน</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $workplace = isset($workplace) ? $workplace : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >ค่าแรงวิทยากร</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $expends = isset($expends) ? $expends : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >หลักสูตรที่เชี่ยวชาญ</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $spe_courses = isset($spe_courses) ? $spe_courses : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >หลักสูตรที่อบรมให้พนักงาน</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $course_em = isset($course_em) ? $course_em : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >จำนวนรุ่น</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $gen = isset($gen) ? $gen : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-2" >ปีที่อบรม</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $year_train = isset($year_train) ? $year_train : '-'; ?>
                          </div>
                        </div>
                        <div class="form-group row" <?php if ($trainerID == "") {
                                                      echo " style='display:none'";
                                                    } ?>>
                          <label class="control-label col-sm-2" >ปรับปรุงข้อมูลล่าสุด</label>
                          <div class="control-label1 col-sm-8">
                            <span class="control-label1" style="color:green;"><?php echo date('d/m/Y H:i:s', strtotime($lastupdate)); ?></span>
                          </div>
                        </div>
                      </div>
                      <!--End panel body-->
                    </div>
                    <!--End panel Left-->
                  </div>
                  <!--End Left MD-->
                  <div class="col-md-4 text-center" style="padding-top:10px;margin-bottom:30px;">
                    <div class="kv-avatar" style="display: inline-block;">
                      <div class="file-input file-input-ajax-new" style="display: table-cell;width: 213px;">
                        <div class="file-preview ">
                          <div class=" file-drop-zone">
                            <div class="file-preview-thumbnails">
                              <div class="file-default-preview clickable" tabindex="-1">
                                <img src="<?php echo $empPic; ?>" id="picEmp" alt="Your Avatar" width="200">
                              </div>
                            </div>
                          </div>
                          <span>Auto Display HR</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-8">
                    <!--Begin Right MD-->
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-user-plus"></i>
                        Extra Information (TIS)
                      </div>
                      
                      <div class="panel-body">
                      <div class="form-group row">
                          <label class="control-label col-sm-2">ประวัติการศึกษา</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $studyinfo = isset($studyinfo) ? $studyinfo : '-'; ?> </textarea>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-2">ประสบการณ์ทำงาน</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $workinfo = isset($workinfo) ? $workinfo : '-'; ?> </textarea>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-sm-2">ประวัติการฝึกอบรม</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $traininfo = isset($traininfo) ? $traininfo : '-'; ?> </textarea>
                          </div>
                        </div>
                      <div class="form-group row">
                          <label class="control-label col-sm-2">หมายเหตุ</label>
                          <div class="control-label1 col-sm-8">
                            <?php echo $trainerRemark = isset($trainerRemark) ? $trainerRemark : '-'; ?> </textarea>
                          </div>
                        </div>
                      </div>
                      <!--End panel body-->
                    </div>
                    <!--End panel Right-->
                  </div>
                  <!--End Right MD-->
                  <br />
                  
                  <div class=" form-group col-md-8" align="center">
                    <div class="form-group  col-md-2">
                      <a href="trainerInternal.php" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-circle-left"></i> กลับ
                      </a>
                    </div>
                  </div>
                </form>
              </div>
              <!--End Row-->
              <div id='debug'></div>
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
    <?php include_once("footer.php"); ?>
  </div>
  <!-- Modal -->
  <div id="popupModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <br />
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

  <?php include_once("notification.php"); ?>
  <script src="assets/lib/jquery.min.js"></script>
  <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
  <script src="assets/lib/screenfull/screenfull.js"></script>
  <script src="assets/js/main.min.js"></script>
  <script src="lib/select2-4.0.5/js/select2.min.js"></script>
  <script src="lib/bootbox-5.1.3/bootbox.js"></script>
  <script src="assets/js/tisApp.js"></script>
  <script type="text/javascript">
    $("#btnCancel").click(function(event) {
      event.preventDefault();
      bootbox.confirm({
        title: "กรุณายืนยัน ยกเลิกการบันทึกข้อมูล ?",
        backdrop: true,
        closeButton: false,
        message: "คุณต้องการยกเลิกการบันทึกข้อมูล</br>และกลับสู่เมนูก่อนหน้า",
        size: 'small',
        animate: true,
        centerVertical: true,
        className: "confirmDelete bootbox-confirm",
        buttons: {
          confirm: {
            label: '<i class="fa fa-check "></i> ยืนยัน',
            className: 'btn btn-success btn-sm'
          },
          cancel: {
            label: '<i class="fa fa-times"></i> ยกเลิก',
            className: 'btn btn-danger btn-sm'
          }

        },
        callback: function(result) {
          if (result) {
            window.location.href = 'trainerInternal.php';
          }

        }
      });
    });

    function popup(msg) {
      $('#modalContent').html(msg);
      $('#popupModal').modal('show');
    }

    function saveEmployee() {
      console.log($("#formJPM").serialize());
      var employeeNo = $('#employeeNo').val();
      if (employeeNo == "") {
        return;

      }
      $.ajax({
        type: "POST",
        url: "db/saveTrainerInternal.php",
        data: $("#formJPM").serialize(),
        beforeSend: function() {

          $('#loading').show();
        },
        success: function(result) {
          var obj = JSON.parse(result);
          //console.log(result)
          <?php
          if ($trainerID <> "") {
            echo "var IsRedirect=false;";
          } else {

            echo "var IsRedirect=true;";
          }

          ?>


          if (obj.code == "200") {
            //  popup("Completed save.");
            $('#loading').hide();
            tisAlertMessage('ผลการดำเนินการ', '<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น', 'completed', 'small', 'trainerInternal.php', IsRedirect);
          } else {
            $('#loading').hide();
            tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้ <br>' + obj.message, 'error', 'small', 'trainerInternalEdit.php', false);
            //  popup(obj.message);
            //$('#debug').html(obj.message);

          }
          //location.reload();
        },
        error: function() {
          $('#loading').hide();
          tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้ <br>Cannot call save api<br>กรุณาติดต่อผู้ดูแลระบบ', 'error', 'small', 'trainerInternalEdit.php', false);
          //popup("Cannot call save api");

        }
      });
    }

    function getJPM() {
      var employeeNo = $('#employeeNo').val();
      if (employeeNo == "") {
        return;
      }
      $.ajax({
        type: "POST",
        url: "api/getEmployeeInfo.php",
        data: {
          employeeNo: employeeNo,
        },
        beforeSend: function() {
          $('#loading').show();
        },
        success: function(result) {
          var code = '';
          var message = '';
          var obj = JSON.parse(result, function(key, value) {
            switch (key) {
              case 'code':
                code = value;
                break;
              case 'message':
                message = value;
                break;
              case 'th_initial':
                $('#th_initial').val(value);
                break;
              case 'thai_name':
                $('#thai_name').val(value);
                break;
              case 'title':
                $('#position').val(value);
                break;
              case 'department':
                $('#department').val(value);
                break;
              case 'company':
                $('#company').val(value);
                break;
              case 'section':
                $('#section').val(value);
                break;
              case 'division':
                $('#division').val(value);
                break;
              case 'workplace':
                $('#workplace').val(value);
                $('#workplace').select2({
                  tags: true
                });
                break;
              case 'work_telephone':
                $('#telephone').val(value);
                break;
              case 'email':
                $('#email').val(value);
                break;
              default:
            }
            console.log(result);
          });
          if (code == "200") {
            $("input[required]").each(function() {
              $(this).change();
            });

            $("#picEmp").attr("src", "https://intranet.jasmine.com/hr/office/Data/" + $('#employeeNo').val() + ".jpg");

          } else {

            tisAlertMessage('ข้อความ', message, 'info', 'small', 'trainerInternalEdit.php', false);
            //    popup(message);
            //Clear form fill last used employeeNo
            $("#formJPM").trigger('reset');
            $('#employeeNo').val(employeeNo);
          }
          $('#loading').hide();
        },
        error: function() {
          $('#loading').hide();
          tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด', 'Cannot call API JPM Employee<br><small>กรุณาลองรีโหลดหน้าแล้วทำการรายใหม่อีกครั้ง</small>', 'error', 'small', 'trainerInternalEdit.php', false);
          //  popup("Cannot call API");

        }
      });
    }
    $(document).ready(function() {
      //Get JPM
      $('#workplace').select2({
        placeholder: 'เลือกจังหวัดที่ทำงาน',
        tags: true
      });
      $("#getJPM").click(function() {
        getJPM();
      });
      $('#employeeNo').on('keypress', function(e) {
        if (e.which === 13) {
          getJPM();
        }
      });
      //Save
      //$("#btnSave").click(function(){
      $("#formJPM").submit(function(e) {
        event.preventDefault();
        saveEmployee();
      });
      $('#popupModal').on('hidden.bs.modal', function() {
        if ($('#modalContent').html() == "Completed save.") {
          location.href = "trainerInternal.php";
        }
      });
    });

    $(function() {
      $("input[required]").each(function() {
        if ($(this).val().length > 0) {
          $(this).css('border-color', '#ccc');
        } else {
          $(this).css('border-color', '#f28d68');
        }

        $(this).on('change', function() {
          if ($(this).val().length > 0) {
            $(this).css('border-color', '#ccc');
          } else {
            $(this).css('border-color', '#f28d68');
          }
        });
      }) //end each
    }); //end function
  </script>
</body>

</html>