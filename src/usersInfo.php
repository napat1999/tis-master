<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("isadmin","users"); //If need permission enable here

//Initial form value
$userID = isset($_GET["userID"])?$_GET["userID"]:'';
if($userID<>"") {
  $sql="select * from tisusers where userid=".$userID;
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $isFound=1;
    $employeeNo=$result[$i]['employeeno'];
    $th_initial=$result[$i]['th_initial'];
    $thai_name = $result[$i]["thai_name"];
    $position = $result[$i]["position"];
    $department = $result[$i]["department"];
    $company = $result[$i]["company"];
    $section = $result[$i]["section"];
    $division = $result[$i]["division"];
    $workplace = $result[$i]["workplace"];
    $userro = $result[$i]["userro"];
    $telephone = $result[$i]["telephone"];
    $email = $result[$i]["email"];
    $userremark = $result[$i]["userremark"];
    $isadmin = $result[$i]["isadmin"];
    $istraininghq = $result[$i]["istraininghq"];
    $istrainingro = $result[$i]["istrainingro"];
    $iscoordinator = $result[$i]["iscoordinator"];
    $lastupdate = $result[$i]["lastupdate"];
  }
  if($isFound==0) { //Wrong access_token
    $url="error.php?code=userInfo";
    header("Location: ".$url);
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : ข้อมูลผู้ใช้งานระบบ</title>
	<?php include_once("basicHeader.php");?>
	<style>

		/* Safari */
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}
    .form-horizontal .control-label{
      /* text-align:right; */
      text-align:left;
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
.control-label{
    text-align: right !important;
}
.modal-footer button {
        float:right;
        margin-left: 10px;
      }
	</style>
  <link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="lib/TisCheckbox/CustomCheckBox.css">

  </head>
  <body>
    <div id="wrap">
      <?php $menu="setting" ?>
      <?php $submenu="users.php" ?>
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
                       <i class="fas fa-cog"></i> ตั้งค่า
                      </a>
                  </li>
                  <li >
                      <a href="users.php">
                       <i class="fas fa-users-cog"></i> ตั้งค่าผู้ใช้ระบบ
                      </a>
                  </li>
                  <li class="active">

                      <i class="fas fa-user-plus"></i> ข้อมูลผู้ใช้งานระบบ

                      </li>
                  </ol>
              </header>
              <div class="body">
	            <div id='loading'>
                <div class="loading-backdrop">
                </div>
                <div class="loading-img">
                    <img src="assets/img/Loading-tis.gif" width="400px"/>
                </div>
              </div>
              <div class="row">
                <!--LEFT Panel-->
                <form id="formJPM" name="formJPM" class="form-horizontal">
                  <input type="hidden" id="userID" name="userID" value="<?php echo $userID;?>">
                  <div class="col-md-8">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <i class="fas fa-id-card-alt"></i>
                      Basic Information (JPM)
                    </div>
                    <div class="panel-body">
                      <div class="form-group row">
                          <label class="control-label text-right col-sm-3">รหัสพนักงาน</label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <input type="text" style="text-transform: uppercase" class="form-control" placeholder="รหัสพนักงาน"
                                id="employeeNo" name="employeeNo" value="<?php echo $employeeNo;?>"
                                <?php if($trainerID<>"") { echo "readonly style='background-color : #d1d1d1;'";}?> required>
                              <span class="input-group-btn">
                              <button type="button" class="btn btn-primary" id="getJPM" style="border-radius: 0px 4px 4px 0px !important;">
                              <img src="assets/img/jas_logo.png" height="20px">
                               อ่านข้อมูลอัตโนมัติ
                              </button>
                            </span>
                            </div>
                          </div>
                        </div>
                      <div class="form-group row">
                        <label class="control-label col-sm-3">ชื่อ-นามสกุล</label>
                        <div class="col-sm-2">
                          <select id="th_initial" name="th_initial" class="form-control" required>
                            <option value="นาย" <?php if($th_initial=="นาย") { echo "selected";}?>>นาย</option>
                            <option value="นาง" <?php if($th_initial=="นาง") { echo "selected";}?>>นาง</option>
                            <option value="น.ส." <?php if($th_initial=="น.ส.") { echo "selected";}?>>น.ส.</option>
                          </select>
                        </div>
                        <div class="col-sm-6" style="padding-left: 0px;">
                          <input type="text" class="form-control" placeholder="ชื่อ-นามสกุล" id="thai_name" name="thai_name" value="<?php echo $thai_name;?>" required>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="control-label col-sm-3">ตำแหน่ง</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="ตำแหน่ง" id="position" name="position" value="<?php echo $position;?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3">department</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="department" id="department" name="department" value="<?php echo $department;?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-sm-3">section</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="section" id="section" name="section" value="<?php echo $section;?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-sm-3">division</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="division" id="division" name="division" value="<?php echo $division;?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3">company</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="company" id="company" name="company" value="<?php echo $company;?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-sm-3">สถานที่ทำงาน</label>
                        <div class="col-sm-8 row" style="padding-right: 0px;">
                          <div class="col-sm-8" >
                            <select id="workplace" name="workplace" class="form-control " style="width: 100%">
                              <option></option>
                              <?php
                              $sql='select distinct provincename from paramprovince order by provincename';
                              $result=json_decode(pgQuery($sql),true);
                              for($i=0;$i<count($result)-1;$i++) {
                                if($result[$i]['provincename']==$workplace) {
                                  echo "<option selected>";
                                } else {
                                  echo "<option>";
                                }
                                //echo "value='".$result[$i]['provincename']."'>";
                                echo $result[$i]['provincename']."</option>\n";
                              }
                              echo "</optgroup>\n";
                              ?>
                            </select>
                          </div>
                          <div class="col-sm-1" style="padding-right: 0px;padding-left:0px;">
                            <label class="control-label" for="userro" >RO</label>
                          </div>
                          <div class="col-sm-3" style="padding-right: 0px;padding-left:0px;">
                            <select id="userro" name="userro" class="form-control" placeholder="กรุณาเลือก RO" style="width: 100%" required>
                              <option></option>
                              <?php
                              for($i=1;$i<=10;$i++) {
                                if($i==$userro) {
                                  echo "<option selected";
                                } else {
                                  echo "<option";
                                }
                                echo " value='".$i."'>".$i."</option>";
                              }
                              if($userro=="HQ") {
                                echo "<option selected value='HQ'>HQ</option>";
                              } else {
                                echo "<option value='HQ'>HQ</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="control-label col-sm-3">Telephone</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="telephone" id="telephone" name="telephone" value="<?php echo $telephone;?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-3">email</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="email" id="email" name="email" value="<?php echo $email;?>">
                        </div>
                      </div>
                      <div class="form-group row" <?php if($trainerID=="") { echo " style='display:none'";}?>>
                        <label class="control-label col-sm-3">ปรับปรุงข้อมูลล่าสุด</label>
                        <div class="col-sm-8">
                          <span class="col-sm-8"><?php echo date('d/m/Y H:i:s', strtotime($lastupdate));?></span>
                        </div>
                      </div>
                    </div> <!--End panel body-->
                  </div> <!--End panel Left-->
                  </div> <!--End Left MD-->
                  <div class="col-md-4"> <!--Begin Right MD-->
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Extra Information
                      </div>
                      <div class="panel-body">
                        <div class="form-group row">
                          <label class="control-label col-sm-3">หมายเหตุ</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="หมายเหตุ" id="userremark" name="userremark" value="<?php echo $userremark;?>">
                          </div>
                        </div>
                      </div> <!--End panel body-->
                    </div> <!--End panel Extra1-->
                    <div class="panel panel-default">
                    <div class="panel-heading">
                      <i class="fas fa-user-shield"></i>
                      Permission
                    </div>
                    <div class="panel-body">
                      <div class="form-check checkbox checkbox-primary">
                        <input class="form-check-input" type="checkbox" value="1" id="isAdmin" name="isAdmin" <?php if($isadmin==1) { echo 'checked';}?>>
                        <label class="form-check-label" for="isAdmin">
                          Admin (ตั้งค่าต่างๆ)
                        </label>
                      </div>
                      <div class="form-check checkbox checkbox-primary">
                        <input class="form-check-input" type="checkbox" value="1" id="isTrainingHQ" name="isTrainingHQ" <?php if($istraininghq==1) { echo 'checked';}?>>
                        <label class="form-check-label" for="isTrainingHQ">
                          Training HQ (จัดการ,อนุมัติหลักสูตร)
                        </label>
                      </div>
                      <div class="form-check checkbox checkbox-primary">
                        <input class="form-check-input" type="checkbox" value="1" id="isTrainingRO" name="isTrainingRO" <?php if($istrainingro==1) { echo 'checked';}?>>
                        <label class="form-check-label" for="isTrainingRO">
                          Training RO (ขอเปิดหลักสูตร)
                        </label>
                      </div>
                      <div class="form-check checkbox checkbox-primary">
                        <input class="form-check-input" type="checkbox" value="1" id="isCoordinator" name="isCoordinator" <?php if($iscoordinator==1) { echo 'checked';}?>>
                        <label class="form-check-label" for="isCoordinator">
                          Coordinator (ผู้ประสานงาน)
                        </label>
                      </div>
                    </div> <!--End panel body-->
                    <div class="panel-footer">
                      <div class="row">
                        <div class="form-group" align="center">
                          <button type="submit" class="btn btn-success btn-sm" id="btnSave">
                          <i class="fas fa-save"></i> บันทึกข้อมูล
                          </button>
                          <button id="btnCancel"  class="btn btn-danger btn-sm">
                          <i class="fas fa-times-circle"></i> ยกเลิก
                          </button>
                        </div>
                      </div>
                    </div>
                    </div> <!--End panel Right-->
                    <div class="form-group col-md-2">
                      <a href="users.php"  class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-circle-left"></i> กลับ
                      </a>
                      </div>

                  </div> <!--End Right MD-->
                  <!-- Back Button -->

                </form>
              </div><!--End Row-->
              <div class="row">
                <!--Reserve-->
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

    <?php include_once("notification.php");?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script src="lib/select2-4.0.5/js/select2.min.js"></script>
<script src="lib/bootbox-5.1.3/bootbox.js"></script>
<script src="assets/js/tisApp.js"></script>
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
          window.location.href = 'users.php';
          }

        }
      });
    });
function CancelOnclick(){
//  e.preventDefault();

}


      function popup(msg) {
        $('#modalContent').html(msg);
        $('#popupModal').modal('show');
      }
      function saveEmployee() {
        var employeeNo = $('#employeeNo').val();
        if(employeeNo=="") {
            return;
        }
        $.ajax({
            type: "POST",
            url: "db/saveUsers.php",
            data: $("#formJPM").serialize(),
            beforeSend: function()
            {
              $('#loading').show();
            },
            success: function(result){
              var obj = JSON.parse(result);
                $('#loading').hide();
              if(obj.code=="200") {
                <?php
                  if($userID<>""){
                    echo "var IsRedirect=false;";

                  }else{

                    echo "var IsRedirect=true;";
                  }

                 ?>
                	tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','small','users.php',IsRedirect);
              //  popup("Completed save.");
              } else {
                tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>'+obj.message+'<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','users.php',false);
              //  popup(obj.message);

              }
              //location.reload();
            },
            error: function()
            {
              tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Cannot call save api<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','',false);
              $('#loading').hide();
            }
          });
      }
      function getJPM() {
          var employeeNo = $('#employeeNo').val();
          if(employeeNo=="") {
              return;
          }
          $('#employeeNo').val(employeeNo.toUpperCase());
          employeeNo=employeeNo.toUpperCase();
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
              var code='';
              var message='';
              var obj = JSON.parse(result, function (key, value) {
                switch(key) {
                  case 'code': code=value; break;
                  case 'message': message=value; break;
                  case 'th_initial': $('#th_initial').val(value); break;
                  case 'thai_name': $('#thai_name').val(value); break;
                  case 'title': $('#position').val(value); break;
                  case 'department':
                    if(value!=null) {
                      $('#department').val(value);
                      var roBegin=value.indexOf("\(RO");
                      if(roBegin>0) {
                        var roEnd=value.indexOf("\)");
                        var roIndex=value.substring(roBegin+3,roEnd);
                        $('#userro').val(roIndex);
                      }
                    }

                    break;
                  case 'company': $('#company').val(value); break;
                  case 'section': $('#section').val(value); break;
                  case 'division': $('#division').val(value); break;
                  case 'workplace': $('#workplace').val(value); $('#workplace').select2({tags:true});break;
                  case 'work_telephone': $('#telephone').val(value); break;
                  case 'email': $('#email').val(value); break;
                  default:
                }
              });
              if(code=="200") {
                //OK
                //$('#thai_name').val(th_initial + ' ' + thai_name);
              } else {
                popup(message);
                //Clear form fill last used employeeNo
                $("#formJPM").trigger('reset');
                $('#employeeNo').val(employeeNo);
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
      $(document).ready(function(){
        //Get JPM
        $('#workplace').select2({
          placeholder: 'เลือกจังหวัดที่ทำงาน',
          tags: true
        });
        $("#getJPM").click(function(){
          getJPM();
        });
        $('#employeeNo').on('keypress', function (e) {
          if(e.which === 13){
            getJPM();
          }
        });
        //Save
        $("#formJPM").submit(function(e) {
          event.preventDefault();
          saveEmployee();
        });
        $('#popupModal').on('hidden.bs.modal', function () {
            if ($('#modalContent').html()=="Completed save.") {
              location.href="users.php";
            }
        });
      });
    </script>
  </body>
</html>
