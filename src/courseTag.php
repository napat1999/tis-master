<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraininghq","courseTag"); //If need permission enable here
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Tag ค้นหา</title>
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
        margin-bottom: 10px;
		}
	/*	#loading {
		  display: none;
		  position: absolute;
		  left: 50%;
		  top: 45%;
		  z-index: 999;
		  width: 150px;
		  height: 150px;
		  margin: -75px 0 0 -75px;
		  border: 16px solid #f3f3f3;
		  border-radius: 50%;
		  border-top: 16px solid #3498db;
		  width: 120px;
		  height: 120px;
		  -webkit-animation: spin 2s linear infinite;
		  animation: spin 2s linear infinite;
	    }*/
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
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.css"/>
  <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-responsive/dataTables.responsive.css"/>
  <link rel="stylesheet" href="lib/fonts/material-design/css/material-design-iconic-font.min.css">
  </head>
  <body>
    <div id="wrap">
			<?php $menu="setting" ?>
			<?php $submenu="courseTag.php" ?>
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
                       <i class="fas fa-cog"></i> ตั้งค่า
                      </a>
                  </li>
                                  <li class="active">

                                       <i class="fas fa-search"></i> Tag ค้นหาหลักสูตร

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
                    <div class="col-sm-6">
											<div class="row" style="margin-left:5px">
												<table id="courseTag" class="table table-hover table-striped table-bordered nowrap" align="left">
													<thead>
													<tr>
															<td style="width:120px;padding:5px;">&nbsp;</td>
															<td style="width:100px;padding:5px;">Tag</td>
															<td style="padding:5px;">Description</td>
													</tr>
													</thead>
												</table>
											</div>
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
			<div class="modal-dialog" style="margin: 100px auto">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header MyModalHeader">
            <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
            <h5 class="modal-title" id="dataModalTitle">Modal title</h5>
					</div>
					<div class="modal-body">
						<div class="row">
							<div id='modal-warning'>xxxxx</div>
							<form id="formCourseTag" class="form-horizontal" name="formCourseTag">
								<input type="hidden" id="tagID" name="tagID">
								<div class="form-group">
										<label class="control-label col-sm-3" for="tagName">Tag</label>
										<div class="col-sm-8">
												<input type="text" class="form-control" placeholder="ระบุ Tag สำหรับการค้นหา"
												id="tagName" name="tagName" maxlength="20" required>
										</div>
								</div>
								<div class="form-group" >
										<label class="control-label col-sm-3" for="tagDescription">Description</label>
										<div class="col-sm-8">
												<input type="text" class="form-control" placeholder="ระบุคำอธิบาย"
												id="tagDescription" name="tagDescription" required>
										</div>
								</div>
								<div class="form-group" align="center">
									<button type="submit" class="btn btn-success btn-sm" id="btnSave" <?php echo $btnSaveStatus;?>>
										<i class="fas fa-save"></i> บันทึกข้อมูล
									</button>
									<button  class="btn btn-danger btn-sm" data-dismiss="modal">
										<i class="fas fa-times-circle"></i> ยกเลิก
									</button>
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
  <script src="lib/bootbox-5.1.3/bootbox.js"></script>
  <script src="assets/js/tisApp.js"></script>

		<script type="text/javascript">
        function popup(msg) {
          $('#modalContent').html(msg);
          //$('#modalContent').text(msg);
          $('#popupModal').modal('show');
        }

				function loadDatatable() {
					var table = $('#courseTag').on( 'error.dt',function ( e, settings, techNote, message ) {
							$('#script-warning').text('พบข้อผิดพลาดในการดึงข้อมูล');
						  $('#script-warning').show();
    				}).DataTable({
	          "pageLength": 10,
						ajax: 'api/getCourseTag.php',
            'processing': true,
						columns: [
				        { data: 'tagid', render: function (data, type, row) {
										//var editLink="<a href='courseEdit.php?courseID="+data+"'><i class='fas fa-fw fa-edit'></i> แก้ไข</a>";
										var editLink="<a href='#' data-toggle='modal' data-target='#dataModal' data-id='"+data+"' data-title='<i class=\"fas fa-edit\"></i> แก้ไข Tag หลักสูตร' title='แก้ไข Tag หลักสูตร'><i class='fas fa-fw fa-edit'></i> แก้ไข</a>";
										var deleteLink="<a style='color:red;' href='javascript:tagDelete(\""+data+"\",\""+row.tagname+" || "+row.tagdescription+"\")'><i class='fas fa-fw fa-trash-alt'></i> ลบ</a>";
										return editLink+' / '+deleteLink;
			            }
								},
								{ data: 'tagname' },
				        { data: 'tagdescription'}
				    ],
						"order": [],
	          "columnDefs": [
							{
	            "targets": 0,
	            "orderable": false,
	            "searchable": false,
							}
						],
	          "dom": '<"toolbar">frtip'
	        });
				}
				function tagDelete(id,textContant) {
          bootbox.confirm({
            closeButton: false,
            title:"กรุณายืนยัน การลบข้อมูล Tag หลักสูตร ?",
              message: textContant,
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
                if(result){
                  $.ajax({
                    type: "POST",
                    url: "db/deleteCourseTag.php",
                    data: "tagID="+id,
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
        $(document).ready(function() {
					$.fn.dataTable.ext.errMode = 'none';
					loadDatatable();
	        $("div.toolbar").html('<a href="#" class="btn btn-app" data-toggle="modal" data-target="#dataModal" data-title="<i class=\'fas fa-plus-circle\'></i> เพิ่ม Tag หลักสูตร" title="เพิ่ม Tag ค้นหา"><i class="fas fa-plus-circle"></i> เพิ่ม Tag ค้นหา</a>');

					$('#dataModal').on('hidden.bs.modal', function(e) {
						$('#loading').hide();
					});
					$('#dataModal').on('show.bs.modal', function(e) {
					    //get data-id attribute of the clicked element
					    var tagID = $(e.relatedTarget).data('id');
               $('#dataModalTitle').html($(e.relatedTarget).data('title'));
							$('#tagID').val(tagID);
							$('#btnSave').prop('disabled', true);
              $('#modal-warning').hide();
							if(tagID!=undefined) {
								$.ajax({
									type: "POST",
									url: "api/getCourseTag.php",
									data: "tagID="+tagID,
									beforeSend: function()
									{
										$('#loading').show();
									},
									success: function(result){
										var obj = JSON.parse(result, function (key, value) {
			                switch(key) {
			                  case 'tagname': $('#tagName').val(value); break;
												case 'tagdescription': $('#tagDescription').val(value); break;
			                  default:
			                }
			              });
										$('#btnSave').prop('disabled', false);
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
							} else {
								$('#tagName').val('');
								$('#tagDescription').val('');
								$('#btnSave').prop('disabled', false);
							}
					    //populate the textbox
					    //$(e.currentTarget).find('input[name="bookId"]').val(bookId);
					});

					$("#formCourseTag").submit(function(e) {
						event.preventDefault();
						//return;
						$.ajax({
								type: "POST",
								url: "db/saveCourseTag.php",
								data: $("#formCourseTag").serialize(),
								beforeSend: function()
								{
									$('#loading').show();
								},
								success: function(result){
									try {
										var obj = JSON.parse(result);
										if(obj.code=="200") {
											$('#loading').hide();
                        var xx=3;

										//	$('#dataModal').modal('toggle');

                    	if(	$('#tagID').val()!=""){
                        $('#modal-warning').html('<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น'+' (รีโหลดใน '+xx+')');
                            $('#modal-warning').show();
                            var x = setInterval(function() {
                              xx-=1;
                                $('#modal-warning').html('<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น'+' (รีโหลดใน '+xx+')');
                                if(xx==0){
                                    location.reload();
                                }

                            }, 1000);
                      	}else{
                          $('#modal-warning').html('<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น');
                            $('#modal-warning').show();
                            setTimeout(function(){ location.reload();}, 1000);

                        }
											//location.reload();
										} else {
                      $('#modal-warning').text(obj.message);
                      $('#modal-warning').show();
											$('#loading').hide();
										}
									} catch (err) {
										//alert(result);
                    $('#modal-warning').text('Save Error : API return unknow json');
                    $('#modal-warning').show();
										$('#loading').hide();
									}
									//location.reload();
								},
								error: function()
								{
                  $('#modal-warning').text('Cannot call API');
                  $('#modal-warning').show();
									$('#loading').hide();
								}
						});
					});

        });
    </script>
  </body>
</html>
