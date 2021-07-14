<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("istraining","trainingsite"); //If need permission enable here
include_once("lib/myIMGLib.php");
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

    /*  #TrainerTable {
        border-collapse: collapse;
        border: 1px solid black;
    }*/
    .toolbar {
      float: left;
      margin-left: 10px;
      margin-bottom: 5px;
    }
  </style>
  <!--
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables.min.css">
  <link rel="stylesheet" type="text/css" href="lib/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css">
  -->

  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.css" />
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-responsive/dataTables.responsive.css" />
  <link rel="stylesheet" href="lib/fonts/material-design/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" type="text/css" href="lib/gallery/hes-gallery-master/hes-gallery.min.css">
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
                <li class="active">
                  <a href="javascript:void(0);">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i> วิทยากร
                  </a>
                </li>
                <li class="active">

                  <i class="fas fa-fw fa-address-card"></i> วิทยากรภายใน

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
                <!--TB Panel-->
                <div class="col-md-12">
                  <table id="TrainerTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                      <tr>
                        <td style="padding:5px;">&nbsp;</td>
                        <td style="padding:5px;">รหัสพนักงาน</td>
                        <td style="padding:5px;">ชื่อ-นามสกุล</td>
                        <td style="padding:5px;">ตำแหน่ง</td>
                        <td style="padding:5px;">สังกัด</td>
                        <td style="padding:5px;">สถานที่ทำงาน</td>
                        <td style="padding:5px;">ค่าแรงวิทยากร</td>
                        <td style="padding:5px;">ประวัติการศึกษา</td>
                        <td style="padding:5px;">ประสบการณ์</td>
                        <td style="padding:5px;">หลักสูตรที่เชี่ยวชาญ</td>
                        <td style="padding:5px;">หลักสูตรที่อบรมให้พนักงาน</td>
                        <td style="padding:5px;">จำนวนรุ่น</td>
                        <td style="padding:5px;">ปีที่อบรม</td>
                        <td style="padding:5px;">ติดต่อ</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "select * from trainer where employeeNo is not NULL order by thai_name";
                      $result = json_decode(pgQuery($sql), true);
                      if ($result['code'] == "200") {
                        for ($i = 0; $i < count($result) - 1; $i++) {
                          echo "<tr>\n";
                          $urlImage = intranetIMGNoCheck($result[$i]['employeeNo']);
                          echo "<td style='width:100px'>";
                          echo '<div class="hes-gallery" >';
                          echo "<img class='media-object img-thumbnail user-img'  style='height:90px;width:90px;' ";
                          echo "data-subtext='" . strstr($result[$i]['thai_name'], ' ', true) . "' ";
                          echo "data-alt='" . $result[$i]['thai_name'] . "' src='" . $urlImage . "'>";
                          echo "</div>";
                          echo "</td>";
                          echo "<td>";
                          echo $result[$i]['employeeNo'];
                          //if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
                          echo "<br/>";
                          echo "<a href='trainerInternalEdit.php?trainerID=" . $result[$i]['trainerID'] . "'><i class='fas fa-user-edit'></i> แก้ไข</a>";
                          echo "<br/>";

                          echo "<a style='color:red;' href='javascript:trainerDelete(" . $result[$i]['trainerID'] . ",\"" . $result[$i]['employeeNo'] . " " . $result[$i]['th_initial'] . " " . $result[$i]['thai_name'] . "\")'><i class='fas fa-user-minus'></i> ลบ</a>";
                          echo "</td>";
                          //}
                          echo "<td><a href='trainerInternalView.php?trainerID=" . $result[$i]['trainerID'] . "'>" . $result[$i]['th_initial'] . " " . $result[$i]['thai_name'] . "</a></td>";
                          echo "<td>" . $result[$i]['position'] . "</td>";
                          echo "<td>";
                          if ($result[$i]['section'] <> "") {
                            echo $result[$i]['section'] . "<br/>";
                          }
                          if ($result[$i]['division'] <> "") {
                            echo $result[$i]['division'] . "<br/>";
                          }
                          //echo $result[$i]['department'] . "</td>";
                          echo "<td>" . $result[$i]['workplace'] . "</td>";
                          echo "<td>" . $result[$i]['expends'] . "</td>";
                          echo "<td>" . $result[$i]['studyinfo'] . "</td>";
                          echo "<td>" . $result[$i]['workinfo'] . "</td>";
                          echo "<td>" . $result[$i]['spe_courses'] . "</td>";
                          echo "<td>" . $result[$i]['course_em'] . "</td>";
                          echo "<td>" . $result[$i]['gen'] . "</td>";
                          echo "<td>" . $result[$i]['year_train'] . "</td>";
                          echo "<td>" . $result[$i]['email'] . "<br>โทร : " . $result[$i]['telephone'] . "</td>";

                          echo "</tr>\n";
                        }

                        echo "<br/>";
                        echo "<a href='trainerInternalAdd.php' >เพิ่มวิทยากรภายใน</a>";
                        echo "<br/>";
                      } else {
                        echo "<tr><td colspan='6'>\n";
                        echo "Error : " . $result[code] . "<!--" . $result[message] . "--!>";
                        echo "</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <!--End TB Panel-->
              </div>
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

  <?php include_once("notification.php"); ?>
  <script src="assets/lib/jquery.min.js"></script>
  <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/DataTables/jquery.dataTables.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-responsive/dataTables.responsive.js"></script>
  <script src="assets/lib/screenfull/screenfull.js"></script>
  <script src="lib/bootbox-5.1.3/bootbox.js"></script>
  <script src="assets/js/tisApp.js"></script>
  <script src="assets/js/main.min.js"></script>
  <script src="lib/gallery/hes-gallery-master/hes-gallery.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      var table = $('#TrainerTable').DataTable({
        "pageLength": 10,
        responsive: true,
        "columnDefs": [{
          "targets": 0,
          "orderable": false,
          "searchable": false
        }],
        "dom": '<"toolbar">frtip'
      });
      <?php
      if ($_SESSION["isadmin"] == "1" or $_SESSION["istraininghq"] == "1" or $_SESSION["istrainingro"] == "1") {
      ?>
        $("div.toolbar").html('<a class="btn btn-app" href="trainerInternalEdit.php"><i class="fas fa-user-plus"></i> เพิ่มวิทยากร</a>');
      <?php
      }
      ?>
    });

    function trainerDelete(id, textContant) {
      bootbox.confirm({
        closeButton: false,
        title: "กรุณายืนยัน การลบข้อมูลวิทยากรภายใน ?",
        message: textContant,
        size: 'small',
        animate: true,
        centerVertical: true,
        className: "confirmDelete bootbox-confirm",
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
        callback: function(result) {
          if (result) {
            $.ajax({
              type: "POST",
              url: "db/deleteTrainerInternal.php",
              data: "trainerID=" + id,
              beforeSend: function() {
                $('#loading').show();
              },
              success: function(result) {
                var obj = JSON.parse(result);
                if (obj.code == "200") {
                  //alert('OK');
                  location.reload();
                } else {
                  alert(obj.message);
                  //$('#debug').html(obj.message);
                  $('#loading').hide();
                }
              },
              error: function() {
                alert("Cannot call delete api");
                $('#loading').hide();
              }
            });
          }
        }
      });



    }
  </script>
</body>

</html>