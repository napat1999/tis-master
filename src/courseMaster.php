<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraininghq","courseMaster"); //If need permission enable here
include_once("lib/myLib.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Form</title>
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
		.select2-selection__rendered {
		  margin-left: 5px;
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

.toolbar {
  float: left;
  margin-left:10px;
  margin-bottom:5px;
}
    </style>
    <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.css"/>
    <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-responsive/dataTables.responsive.css"/>
    <link rel="stylesheet" href="lib/fonts/material-design/css/material-design-iconic-font.min.css">
		<link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
  </head>
  <body>
    <div id="wrap">
			<?php $menu="course" ?>
			<?php $submenu="courseMaster.php" ?>
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
                  <li class="active">

                      <i class="fas fa-fw fa-clipboard-list"></i> หลักสูตรต้นแบบ

                      </li>
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
              	<div class="row" >
                    <div class="col-sm-12">
									<table id="courseTable" class="table table-striped table-bordered nowrap" style="width:100%">
										<thead>
										<tr>
												<?php
												if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
													//Edit/ Delete only training officer
												?>
												<td style="padding:5px;" align="center"><i class="fas fa-tools"></i></td>
												<?php
												}
												?>
												<td style="padding:5px;" align="center">ID ชื่อหลักสูตร</td>
												<td style="padding:5px;" align="center">ชื่อหลักสูตร</td>
												<td style="padding:5px;" align="center">วิทยากร</td>
												<td style="padding:5px;" align="center">กำหนดการ</td>
												<!-- <td style="padding:5px;">ชม.</td> -->
												<td style="padding:5px;" align="center">คุณสมบัติผู้สมัคร</td>
												<td style="padding:5px;" align="center">หมายเหตุ</td>
												<td>Tag</td>
										</tr>
										</thead>
										<tbody>
										<?php
											//Keep trainer in array
											$sql="select * from trainer";
											$result=json_decode(pgQuery($sql),true);
											if($result['code']=="200") {
												for($i=0;$i<count($result)-1;$i++) {
													$trainer[$result[$i]['trainerid']]=$result[$i]['thai_name'];
												}
											}
											//Keep tag in array
											$sql="select * from paramtag";
											$result=json_decode(pgQuery($sql),true);
											if($result['code']=="200") {
												for($i=0;$i<count($result)-1;$i++) {
													$tag[$result[$i]['tagid']]=$result[$i]['tagname'];
												}
											}
											//Keep code name in array
											$sql="select * from paramcode";
										  $result=json_decode(pgQuery($sql),true);
										  if($result['code']=="200") {
										    for($i=0;$i<count($result)-1;$i++) {
										      $codeName[$result[$i]['codeid']]=$result[$i]['codename'];
										    }
										  }
				              $sql="select * from coursemaster order by coursecodeid,courselevel,coursenumber,coursesequence";
				              $result=json_decode(pgQuery($sql),true);
											if($result['code']=="200") {
												for($i=0;$i<count($result)-1;$i++) {
					                  echo "<tr>\n";
														if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
						                  echo "<td style='min-width:60px;'>";
						                  echo "<a href='courseMasterEdit.php?courseID=".$result[$i]['courseid']."'><i class='fas fa-fw fa-edit'></i> แก้ไข</a>";
						                  echo "<br/>";
						                  echo "<a style='color:red;' title='ลบข้อมูลหลักสูตรต้นแบบ ".$result[$i]['nameofficial']." ".$result[$i]['namemarketing']."' href='javascript:courseDelete(\"".$result[$i]['courseid']."\",\"".$result[$i]['nameofficial']."<br>".$result[$i]['namemarketing']."\")'><i class='fas fa-fw fa-trash-alt'></i> ลบ</a>";
						                  echo "</td>";
														}
														echo "<td>".$codeName[$result[$i]['coursecodeid']];
														echo str_pad($result[$i]['courselevel'],2,'0',STR_PAD_LEFT)."-";
														echo str_pad($result[$i]['coursenumber'],3,'0',STR_PAD_LEFT)."-";
											      echo str_pad($result[$i]['coursesequence'],1,'0',STR_PAD_LEFT);
														echo "</td>";
					                  echo "<td>".$result[$i]['nameofficial']."<br/>".$result[$i]['namemarketing']."</td>";
														echo "<td>";
														$trainerArray=explode(",",$result[$i]['trainerid']);
														$trainerName="";
														foreach ($trainerArray as $trainerid) {
															$trainerName.=$trainer[$trainerid]."<br/>";
														}
														$trainerName=rtrim($trainerName,"<br/>");
														echo $trainerName;
														echo "</td>";
														echo "<td>".$result[$i]['schedule']."</td>";
														// echo "<td>".minToText($result[$i]['minutetrain'])."</td>";
					                  echo "<td>".$result[$i]['requirement']."</td>";
														echo "<td>".$result[$i]['courseremark']."</td>";
														echo "<td>";
														$tagArray=explode(",",$result[$i]['taglist']);
														$tagName="";
														foreach ($tagArray as $tagid) {
															$tagName.=$tag[$tagid].",";
														}
														$tagName=rtrim($tagName,",");
														echo $tagName;
														echo "</td>";
					                  echo "</tr>\n";
					              }
											} else {
												 echo "<tr><td colspan='9'>\n";
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
		<div id="dataModal" class="modal fade" role="dialog" data-backdrop="static">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header MyModalHeader">
              <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
						<h5 class="modal-title" id="dataModalTitle">ตั้งค่าเริ่มต้น</h5>
					</div>
					<div class="modal-body" >
						<div class="row">
              <div class="col-sm-12">
							<div id='modal-warning'>xxxxx</div>
							<form id="formCourseMasterNew" class="form-horizontal" name="formCourseMasterNew" method="POST" action="courseMasterEdit.php">
								<div class="form-group">
										<label class="control-label col-sm-3" for="codeID">Code หลักสูตร</label>
										<div class="col-sm-8">
											<select class="form-control" id="codeID" name="codeID">
											</select>
										</div>
								</div>
								<div class="form-group">
										<label class="control-label col-sm-3" for="courseContinue">ต่อเนื่องจาก</label>
										<div class="col-sm-8">
											<select class="form-control" id="courseContinue" name="courseContinue" style="width:100%">
											</select>
										</div>
								</div>
								<div class="form-group" align="center">
										<div class="col-sm-12" style="margin:10px;">
										ไม่พบหมวดหมู่ที่ต้องการ <a href="courseCode.php">ตั้งค่า Code หลักสูตร</a>
										</div>
								</div>
								<div class="form-group" align="center">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-success btn-sm" id="btnSave" <?php echo $btnSaveStatus;?>>
											ดำเนินการเพิ่มหลักสูตรต้นแบบ <i class="fas fa-sign-out-alt"></i>
										</button>
                    <button type="reset" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
									</div>
								</div>
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
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-responsive/dataTables.responsive.js"></script>
<script type="text/javascript" charset="utf8" src="lib/DataTables/datatables-tabletools/dataTables.tableTools.js"></script>
		<script src="lib/select2-4.0.5/js/select2.min.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="assets/js/tisApp.js"></script>

    <script type="text/javascript">
				function courseDelete(id,name) {


          bootbox.confirm({
            closeButton: false,
            title:"กรุณายืนยันการลบข้อมูลหลักสูตรต้นแบบ ?",
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

                  if(result) {
                    $.ajax({
        							type: "POST",
        							url: "db/deleteCourseMaster.php",
        							data: "courseID="+id,
        							beforeSend: function()
        							{
        								$('#loading').show();
        							},
        							success: function(myresult){
        								var obj = JSON.parse(myresult);
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
        $(document).ready(function() {
					var table = $('#courseTable').DataTable({
	          "pageLength": 10,
	          responsive: true,
						"order": [],
	          "columnDefs": [
							{
	            "targets": 0,
	            "orderable": false,
	            "searchable": false
							},
							{
							"targets": 7,
							"visible": false
							}
						],
	          "dom": '<"toolbar">frtip'
	        });
					<?php
						if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
					?>
					$("div.toolbar").html('<a class="btn btn-app" href="#" data-toggle="modal" data-target="#dataModal" title="เพิ่มหลักสูตรต้นแบบ"><i class="fas fa-plus-circle"></i> เพิ่มหลักสูตรต้นแบบ</a>');
					<?php
						}
					?>

					$('#dataModal').on('hidden.bs.modal', function(e) {
						$('#loading').hide();
					});
					$('#dataModal').on('show.bs.modal', function(e) {
							$('#codeID').prop('disabled', true);
							$('#courseContinue').prop('disabled', true);
							$('#btnSave').prop('disabled', true);
              $('#modal-warning').hide();
							$.ajax({
								type: "POST",
								url: "api/getCourseCode.php",
								beforeSend: function()
								{
									$('#loading').show();
								},
								success: function(result){
									$.each(JSON.parse(result), function (key, data) {
										 $('#codeID').empty();
										 var haveValue=0;
										 $.each(data, function (datekey, datavalue) {
											haveValue=1;
											var optionTxt='<option value='+datavalue.codeid+' title='+datavalue.codedescription+'>'+datavalue.codename+': '+datavalue.codedescription+'</option>';
											$('#codeID').append(optionTxt);
										});
										if (haveValue==1) {
											//Enable select option if have value
											$('#codeID').prop('disabled', false);
											$('#btnSave').prop('disabled', false);
											enableSequence();
										}

									});
								  $('#modal-warning').hide();
								  $('#loading').hide();
								},
								error: function()
								{
									$('#modal-warning').text('Cannot call API');
								  $('#modal-warning').show();
									$('#loading').hide();
								}
							});
					});

					$('#codeID').on('change', function() {
					  enableSequence();
					});

					function enableSequence() {
						$('#courseContinue').prop('disabled', true);
						var codeID=$('#codeID').val();
						$.ajax({
							type: "POST",
							url: "api/getCourseSequence.php",
							data: "codeID="+codeID,
							beforeSend: function()
							{
								$('#loading').show();
							},
							success: function(result){
								$.each(JSON.parse(result), function (key, data) {
									 var codeNameFull=$('#codeID option:selected').text();
									 var codeName=codeNameFull.substring(0,3);
									 $('#courseContinue').empty();
									 $('#courseContinue').append('<option></option>');
									 $('#courseContinue').append('<option value=0>หลักสูตรไม่ต่อเนื่อง</option>');
									 var haveValue=0;
									 $.each(data, function (datekey, datavalue) {
										haveValue=1;
										var course=datavalue.courselevel+'-'+datavalue.coursenumber+'-'+datavalue.coursesequence;
										//var course='x';
										var optionTxt='<option style="margin-left:5px;" value='+course+'>'+codeName+course+' : '+datavalue.nameofficial+'</option>';
										$('#courseContinue').append(optionTxt);
									});
									if (haveValue==1) {
										$('#courseContinue').prop('disabled', false);
										$('#courseContinue').select2({
					            placeholder: 'หลักสูตรไม่ต่อเนื่อง หรือ เลือกหลักสูตรต่อเนื่อง'
					          });
									}
								});
								$('#loading').hide();
							},
							error: function()
							{
								$('#modal-warning').text('Cannot call API Sequence');
								$('#modal-warning').show();
								$('#loading').hide();
							}
						});
					}

					$("#formCourseMasterNew").submit(function(e) {
						//event.preventDefault();
						//return;
					});

        });
    </script>
  </body>
</html>
