<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("istraining","trainingsite"); //If need permission enable here
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : สถานที่จัดอบรม</title>
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

        /* สีพื้นหลังของ row ที่ถูกเลือกของ DataTable  */
        table.dataTable tbody tr.selected {
          background-color: #FFFDE7;
        }
        .toolbar {
          float: left;
          margin-left:10px;
          margin-bottom:5px;
        }

    </style>
	<!--	<link rel="stylesheet" type="text/css" href="lib/DataTables/datatables.min.css">
	<link rel="stylesheet" type="text/css" href="lib/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">-->
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.css"/>
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-responsive/dataTables.responsive.css"/>
  <link rel="stylesheet" href="lib/fonts/material-design/css/material-design-iconic-font.min.css">

  </head>
  <body>
    <div id="wrap" >
			<?php $menu="trainingSite" ?>
			<?php $submenu="" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>



      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>

                <ol class="breadcrumb">
                                  <li class="active">
                                      <a href="javascript:void(0);">
                                       <i class="fas fa-fw fa-hotel"></i> สถานที่จัดอบรม
                                      </a>
                                  </li>
                                <!--  <li>
                                      <a href="javascript:void(0);">
                                          <i class="fas fa-fw fa-hotel"></i> Library
                                      </a>
                                  </li>
                                  <li >
                                      <i class="fas fa-fw fa-hotel"></i> Data
                                  </li>-->
                              </ol>

              </header>
              <div class="body">
							<div id='script-warning'>
							</div>
              <div id='loading'>
              <div class="loading-backdrop">
              </div>
              <div class="loading-img">
                  <img src="assets/img/Loading-tis.gif" width="400px"/>
              </div>
            </div>
              	<div class="row" style="margin-left:2px;margin-right: -5px;">
                
									<table id="courseTable" class="table table-striped table-hover table-bordered nowrap width-full responsive" style="width:100%">
										<thead>
										<tr>
												<?php
												if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
													//Edit/ Delete only training officer
												?>
												<td style="padding:5px;">&nbsp;</td>
												<?php
												}
												?>
												<td style="padding:5px;">ชื่อสถานที่</td>
												<td style="padding:5px;">RO จังหวัด</td>
												<td style="padding:5px;">ผู้ประสานงาน</td>
												<td style="padding:5px;">เลขหมายติดต่อ</td>
												<td style="padding:5px;">หมายเหตุ</td>
										</tr>
										</thead>
										<tbody>
											<?php
					              $sql="select * from trainingsite";
					              $result=json_decode(pgQuery($sql),true);
												if($result['code']=="200") {
						              for($i=0;$i<count($result)-1;$i++) {
                              $siteroom="";
                              $sitefloor="";
                              if ($result[$i]['siteroom']<>"") {
                                $siteroom= " ห้อง ".$result[$i]['siteroom']." ";
                              }
                              if ($result[$i]['sitefloor']<>"") {
                                $sitefloor= " ชั้น ".$result[$i]['sitefloor'];

                              }

						                  echo "<tr>\n";
															if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
							                  echo "<td style='width:60px;'>";
							                  echo "<a href='trainingSiteEdit.php?siteid=".$result[$i]['siteid']."'><i class='fas fa-fw fa-edit'></i> แก้ไข</a>";
							                  echo "<br/>";
							                  echo "<a title='ลบข้อมูลสถานที่จัดอบรม ".$result[$i]['sitename'].$siteroom."' style='color:red;' href='javascript:trainingsiteDelete(".$result[$i]['siteid'].",\" ".$result[$i]['sitename'].$siteroom. $sitefloor."\")'><i class='fas fa-fw fa-trash-alt'></i> ลบ</a>";
							                  echo "</td>";
															}
															echo "<td>";
															echo $result[$i]['sitename']."<br>";

															if ($result[$i]['siteroom']<>"") {
																echo "ห้อง".$result[$i]['siteroom']." ";
															}
															if ($result[$i]['sitefloor']<>"") {
																echo " ชั้น ".$result[$i]['sitefloor'];

															}
															echo "</td>";
						                  echo "<td> RO".$result[$i]['sitero']." จ.".$result[$i]['siteprovince']."</td>";
															echo "<td>".$result[$i]['contactname']."</td>";
															echo "<td>".$result[$i]['contacttelephone']."</td>";
						                  echo "<td>".$result[$i]['siteremark']."</td>";
						                  echo "</tr>\n";
						              }
												} else {
													 echo "<tr><td colspan='6'>\n";
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

          <!-- end .inner -->
        </div>

        <!-- end .outer -->
      </div>

      <!-- end #content -->
    </div><!-- /#wrap -->
    <div id="footer">
    <?php include_once("footer.php");?>
    </div>

    <?php include_once("notification.php");?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-responsive/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-tabletools/dataTables.tableTools.js"></script>

<script src="lib/bootbox-5.1.3/bootbox.js"></script>
<script src="assets/js/tisApp.js"></script>
	<!--	<script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
  <script type="text/javascript" charset="utf8" src="lib/Responsive-2.2.2/js/dataTables.responsive.min.js"></script>
  -->  <script type="text/javascript">
        $(document).ready(function() {
					var table = $('#courseTable').DataTable({
	          "pageLength": 10,
	          responsive: true,
	          order: [[1, 'asc']],
	          "columnDefs": [ {
	            "targets": 0,
	            "orderable": false,
	            "searchable": false
	          }],
	          "dom": '<"toolbar">frtip'
	        });
          <?php
						if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
					?>
	        $("div.toolbar").html('<a class="btn btn-app" href="trainingSiteEdit.php"><i class="fas fa-plus-circle"></i> เพิ่มสถานที่</a>');
					<?php
						}
					?>
        });
				function trainingsiteDelete(id,name) {


        /*  var locale = {
              OK: 'I Suppose',
              CONFIRM: 'Go Ahead',
              CANCEL: 'Maybe Not'
          };*/
        //  bootbox.addLocale('custom', locale);
          bootbox.confirm({
            closeButton: false,
            title:"กรุณายืนยันการลบข้อมูลสถานที่จัดอบรม ?",
              message: name,
              size: 'small',
              animate: true,
              centerVertical:true,
              className:"confirmDelete bootbox-confirm",
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
              //    console.log('This was logged in the callback: ' + result);

                  if(result) {
                   $.ajax({
                     type: "POST",
                     url: "db/deleteSite.php",
                     data: "siteID="+id,
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
          });



	      }
    </script>
  </body>
</html>
