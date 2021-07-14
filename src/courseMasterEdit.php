<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraininghq","courseMaster"); //If need permission enable here
include_once("lib/myLib.php");

//Initial form value
$courseID = isset($_GET["courseID"])?$_GET["courseID"]:'';
if($courseID<>""){
	//Edit mode
	$sql="select * from coursemaster where courseid=".$courseID;
	$result=json_decode(pgQuery($sql),true);
	$isFound=0;
	if($result['code']=="200"){
		for($i=0;$i<count($result)-1;$i++){
			$isFound=1;
			$codeID=$result[$i]['coursecodeid'];
			$courseLevel=$result[$i]['courselevel'];
			$courseNumber=$result[$i]['coursenumber'];
			$courseSequence=$result[$i]['coursesequence'];
			$nameOfficial=$result[$i]['nameofficial'];
			$nameMarketing=$result[$i]['namemarketing'];
			//$schedule=$result[$i]['schedule'];
			$courseHour = $result[$i]["coursehour"];
			$objective = $result[$i]["objective"];
			$content = html_entity_decode($result[$i]["content"]);
			$requirement = $result[$i]["requirement"];
			$courseRemark = $result[$i]["courseremark"];
			$approxstudent = $result[$i]["approxstudent"];
			// $approxhead = number_format($result[$i]["approxhead"]);
			// $approxtotal = number_format($result[$i]["approxtotal"]);
			$trainerid = $result[$i]["trainerid"];
			$trainerArray=explode(",",$trainerid);
			$tagid = $result[$i]["taglist"];
			$tagArray=explode(",",$tagid);
			$lastupdate = $result[$i]["lastupdate"];

		}
	}
	if($isFound==0){ //Wrong access
		$url="error.php?code=courseMaster";
		header("Location: ".$url);
	}
	$sql="select * from paramcode where codeid=".$codeID;
	$result=json_decode(pgQuery($sql),true);
	$isFound=0;
	if($result['code']=="200"){
		for($i=0;$i<count($result)-1;$i++){
			$isFound=1;
			$codeName=$result[$i]['codename'];
			$codeDescription=$result[$i]['codedescription'];
		}
	}
	if($isFound==0){ //Wrong access
		$url="error.php?code=courseMaster";
		header("Location: ".$url);
	}
} else{
	//Insert mode
	$codeID = isset($_POST["codeID"])?$_POST["codeID"]:'';
	$courseContinue = isset($_POST["courseContinue"])?$_POST["courseContinue"]:'';
	if($courseContinue<>""){
		list($courseLevel,$courseNumber,$courseSequence)=explode("-",$courseContinue);
	}
	$sql="select * from paramcode where codeid=".$codeID;
	$result=json_decode(pgQuery($sql),true);
	$isFound=0;
	if($result['code']=="200"){
		for($i=0;$i<count($result)-1;$i++){
			$isFound=1;
			$codeName=$result[$i]['codename'];
			$codeDescription=$result[$i]['codedescription'];
		}
	}

	if($isFound==0){ //Wrong access
		$url="error.php?code=courseMaster";
		header("Location: ".$url);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>TIS : Course Master</title>
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
				0% {
				-webkit-transform: rotate(0deg);}
			100%
			{
				-webkit-transform: rotate(360deg);}
			}

			@keyframes spin {
				0% {
				transform: rotate(0deg);}
			100%
			{
				transform: rotate(360deg);}
			}
			.form-horizontal .control-label{
				/* text-align:right; */
				text-align: left;
			}
			.date-picker-wrapper {
				z-index: 1100 !important;
			}
			.nopaddingRight {
			   padding-right: 0px !important;
			   margin-right: 0px !important;
			}
			.nopadding {
			   padding-left: 0px !important;
			   padding-right: 0px !important;
			}
			input:required {
				border-color: #f28d68;
			}
			.slrequire span.select2-selection {
				border-color: #f28d68  !important;
			}
			.control-label{
				text-align: right !important;
			}
		</style>
		<link href="lib/summernote-0.8.11/summernote.css" rel="stylesheet">
		<link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="lib/jquery-date-range-picker-0.20.0/css/daterangepicker.min.css" rel="stylesheet" />
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
									<li >
										<a href="courseMaster.php">
											<i class="fas fa-fw fa-clipboard-list"></i> หลักสูตรต้นแบบ
										</a>
									</li>
									<li class="active">

										<i class="fab fa-audible"></i> หลักสูตรอบรม

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
								<div class="row">
									<form id="formCourseBasic" name="formCourseBasic" class="form-horizontal">
									<input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<i class="fab fa-accusoft"></i> ข้อมูลเบื้องต้น
											</div>
											<div class="panel-body">
												<input type="hidden" id="codeID" name="codeID" value="<?php echo $codeID;?>">
												<input type="hidden" id="courseNumber" name="courseNumber" value="<?php echo $courseNumber;?>">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group row">

															<label class="control-label col-sm-4">ID ชื่อหลักสูตร</label>
															<div class="col-sm-8">
															<div	class="form-control" style="border:0px;">
																<?php
																if($courseID<>""){
																	//Old course lock ID change
																	?>
																	<span title='<?php echo $codeDescription?>'>
																		<?php echo $codeName.str_pad($courseLevel,2,'0',STR_PAD_LEFT);?>-<?php echo str_pad($courseNumber,3,'0',STR_PAD_LEFT);?>-<?php echo $courseSequence;?>
																	</span>
																	<?php
																} else{
																	if($courseSequence==""){
																		//No Sequence
																		?>
																		<div class="form-group input-group" style="">
																			<span class="input-group-addon" title='<?php echo $codeDescription?>'>
																				<?php echo $codeName;?>
																			</span>
																			<select class="form-control" style="min-width:60px;max-width:200px;border-radius: 0px 0px 0px 0px;" name="courseLevel" id="courseLevel">
																				<option value='1'>01</option>
																				<option value='2'>02</option>
																				<option value='3'>03</option>
																			</select>

																			<span class="input-group-addon" style="width:auto;" title='กำหนดค่าโดยอัตโนมัติเมื่อบันทึก'>
																				-Number-1	<small class="text-muted" ><i class="fas fa-info-circle"></i> กำหนดค่าเมื่อบันทึก</small>
																			</span>
																		</div>


																		<?php
																	} else{
																		//Have Sequence
																		?>
																		<input type="hidden" id="courseLevel" name="courseLevel" value="<?php echo $courseLevel;?>">
																		<span class="control-label" title='<?php echo $codeDescription?>'>
																			<?php echo $codeName.str_pad($courseLevel,2,'0',STR_PAD_LEFT);?>-<?php echo str_pad($courseNumber,3,'0',STR_PAD_LEFT);?>-Sequence <span class="text-muted"><i class="fas fa-info-circle"></i> กำหนดค่าเมื่อบันทึก</span>
																		</span>
																		<?php
																	}
																}
																?>
																</div>
															</div>
														</div>
														<div class="form-group row">
															<label class="control-label col-sm-4">ชื่อหลักสูตร<br><small class="text-info">(เป็นทางการ)</small></label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="ชื่อหลักสูตร (ทางการ)"
																id="nameOfficial" name="nameOfficial" value="<?php echo $nameOfficial;?>" required>
															</div>
														</div>
														<div class="form-group row">
															<label class="control-label col-sm-4">ชื่อหลักสูตร<br><small class="text-info">(ประชาสัมพันธ์)</small></label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="ชื่อหลักสูตร (ประชาสัมพันธ์)"
																id="nameMarketing" name="nameMarketing" value="<?php echo $nameMarketing;?>">
															</div>
														</div>
														<!-- <div class="form-group row">
															<label class="control-label col-sm-4">กำหนดการโดยประมาณ</label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="กำหนดการโดยประมาณ"
																id="schedule" name="schedule" value="">
															</div>
														</div> -->
														<div class="form-group row">
															<label class="control-label col-sm-4">วัตถุประสงค์</label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="วัตถุประสงค์"
																id="objective" name="objective" value="<?php echo $objective;?>">
															</div>
														</div>

														<div class="form-group row">
															<label class="control-label col-sm-4">หมายเหตุหลักสูตร</label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="หมายเหตุหลักสูตร"
																id="courseRemark" name="courseRemark" value="<?php echo $courseRemark;?>">
															</div>
														</div>


													</div>
													<!--panel Right-->
													<div class="col-md-6">

														<div class="form-group row">
															<label class="control-label col-sm-4">คุณสมบัติผู้เข้าอบรม</label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="คุณสมบัติผู้เข้าอบรม"
																id="requirement" name="requirement" value="<?php echo $requirement;?>">
															</div>
														</div>

														<div class="form-group row">
															<label class="control-label col-sm-4">จำนวนผู้เข้าอบรม<br><small class="text-info">(ต่อรุ่น)</small></label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="ผู้เข้าอบรมต่อรุ่น"
																id="approxstudent" name="approxstudent" value="<?php echo $approxstudent;?>">
															</div>
														</div>
														<div class="form-group row">
															<label class="control-label col-sm-4">ชั่วโมงฝึกอบรมทีแนะนำ</label>
															<div class="col-sm-8">
																<input type="number" class="form-control" placeholder="ชั่วโมงฝึกอบรมทีแนะนำ" min="4"
																id="courseHour" name="courseHour" value="<?php echo $courseHour;?>">
															</div>
														</div>
														<!-- <div class="form-group row">
															<label class="control-label col-sm-4">ค่าใช้จ่าย<br><small class="text-info">(ต่อคน)</small></label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="ค่าใช้จ่าย/คน"
																id="approxhead" name="approxhead" value="">
															</div>
														</div>
														<div class="form-group row">
															<label class="control-label col-sm-4">ค่าใช้จ่ายประเมิน</label>
															<div class="col-sm-8">
																<input type="text" class="form-control" placeholder="ค่าใช้จ่ายประเมิน"
																id="approxtotal" name="approxtotal" value="">
															</div>
														</div> -->


														<div class="form-group row">
															<label class="control-label col-sm-4">วิทยากร</label>
															<div class="col-sm-8">
																<select id="trainerid" name="trainerid[]" class="form-control" multiple="multiple">
																	<?php
																	$sql="select trainerid,th_initial || ' ' || thai_name as trainerName,coalesce(department,'วิทยากรภายนอก') as department";
																	$sql.=" from trainer order by department";
																	$result=json_decode(pgQuery($sql),true);
																	$activeRO="";
																	for($i=0;$i<count($result)-1;$i++){
																		$currentRO=$result[$i]['department'];
																		if($currentRO<>$activeRO){ //Start group
																			if($activeRO<>""){ //Close if not begining
																				echo "</optgroup>\n";
																			}
																			echo "<optgroup label='$currentRO'>\n";
																			$activeRO=$currentRO;
																		}
																		if(in_array($result[$i]['trainerid'],$trainerArray)){
																			echo "<option selected";
																		} else{
																			echo "<option";
																		}
																		echo " value='".$result[$i]['trainerid']."'>";
																		echo $result[$i]['trainername']."</option>\n";
																	}
																	echo "</optgroup>\n";
																	?>

																</select>

															</div>

														</div>



														<div class="form-group row">
															<label class="control-label col-sm-4">Tag</label>
															<div class="col-sm-8">
																<select id="tagid" name="tagid[]" class="form-control" multiple="multiple">
																	<?php
																	$sql="select tagid,tagname,tagdescription";
																	$sql.=" from paramtag order by tagname";
																	$result=json_decode(pgQuery($sql),true);
																	for($i=0;$i<count($result)-1;$i++){
																		if(in_array($result[$i]['tagid'],$tagArray)){
																			echo "<option selected";
																		} else{
																			echo "<option";
																		}
																		echo " value='".$result[$i]['tagid']."' title='".$result[$i]['tagdescription']."'>";
																		echo $result[$i]['tagname']."</option>\n";
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
												</div>

											</div>


										</div>
										<!--End panel Right-->

									</div> <!--End panel body-->

								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-body" style="margin-bottom: 0px;">
												<div class="form-group row" style="margin-bottom: 0px;">
													<label class="control-label col-sm-1">เนื้อหา</label>
													<div class="col-sm-10" style="margin-bottom: 0px;">
														<textarea id="course_content" name="course_content"><?php echo $content;?></textarea>
													</div>
												</div>
												<div class="form-group row" <?php if($courseID==""){ echo " style='display:none'";}?>>
													<label class="control-label col-sm-4">ปรับปรุงข้อมูลล่าสุด</label>
													<div class="col-sm-8 ">
														<span class="col-sm-8 text-primary "><?php echo date('d/m/Y H:i:s', strtotime($lastupdate));?></span>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>


<div class="row">

									<div class="form-group col-md-2">
										<a href="courseMaster.php"  class="btn btn-default btn-sm">
											<i class="fas fa-arrow-circle-left"></i> กลับ
										</a>
									</div>
									<div class="form-group col-md-8" align="center">

										<button type="submit"  class="btn btn-success btn-sm" id="btnSave">
											<i class="fas fa-save"></i> บันทึกข้อมูล
										</button>
										<button id="btnCancel" class="btn btn-danger btn-sm">
											<i class="fas fa-times-circle"></i> ยกเลิก
										</button>
									</div>


</div>
								</form>
								<!--Start Right MD-->
								<div class="col-md-4">
									<!--Reserve-->
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

		<?php include_once("notification.php");?>
		<script src="assets/lib/jquery.min.js"></script>
		<script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/lib/screenfull/screenfull.js"></script>
		<script src="assets/js/main.min.js"></script>
		<script src="lib/summernote-0.8.11/summernote.js"></script>
		<script src="lib/summernote-0.8.11/lang/summernote-th-TH.js"></script>
		<script src="lib/select2-4.0.5/js/select2.min.js"></script>
		<script src="lib/Moment 2.24.0/moment.min.js"></script>
		<script src="lib/js/inputFilter.js"></script>
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
					window.location.href = 'courseMaster.php';
					}

				}
			});
		});

			function popup(msg) {
				$('#modalContent').html(msg);
				//$('#modalContent').text(msg);
				$('#popupModal').modal('show');
			}
			$(document).ready(function() {
					$('#course_content').summernote({
							lang: 'th-TH',
							height:150,
							minHeight:80,
							followingToolbar: false,
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
					$('#siteid').select2({
							placeholder: 'เลือกสถานที่จัดอบรม'
						});
					$('#trainerid').select2({
							placeholder: 'เลือกวิทยากรฝึกอบรม'
						});
					$('#tagid').select2({
							placeholder: 'เลือก tag สำหรับค้นหา'
						});
					//Save
					//$("#btnSave").click(function(){
					$("#formCourseBasic").submit(function(e) {
							event.preventDefault();
							$.ajax({
									type: "POST",
									url: "db/saveCourseMaster.php",
									data: $("#formCourseBasic").serialize(),
									beforeSend: function()
									{
										$('#loading').show();
									},
									success: function(result){
										console.log(result);
										try {
											var obj = JSON.parse(result);

											<?php
												if($courseID<>""){
													echo "var IsRedirect=false;";

												}else{

													echo "var IsRedirect=true;";
												}

											 ?>

											if(obj.code=="200") {
											//	popup("Completed save.");
											tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','small','courseMaster.php',IsRedirect);
												$('#loading').hide();
											} else {
												tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>'+obj.message+'<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','users.php',false);
											//	popup(obj.message);
												//$('#debug').html(obj.message);
												$('#loading').hide();
											}
										} catch (err) {
										//	alert(result);
										tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Error : API return unknow json<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','',false);
									//		popup("Save Error : API return unknow json");
											$('#loading').hide();
										}
										//location.reload();
									},
									error: function()
									{
										tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Error : Cannot call api<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','',false);
									//	popup("Save Error : Cannot call api");
										$('#loading').hide();
									}
								});
						});
					$('#popupModal').on('hidden.bs.modal', function () {
							if ($('#modalContent').html()=="Completed save.") {
								<?php
								//Redirect only when new insert
								if($courseID=="") {
									echo "location.href='courseMaster.php';";
								}
								?>
							}
						});
					<?php
					if ($enableRequest==1) {
						?>
						$("#btnRequestApprove").click(function(){
								//Disable resend
								$('#btnRequestApprove').prop('disabled', true);
								//Disable change if request Approve
								$('#btnSave').prop('disabled', true);
								$.ajax({
										type: "POST",
										url: "api/sendMail.php",
										data: $("#formRequestApprove").serialize(),
										beforeSend: function()
										{
											$('#loading').show();
										},
										success: function(result){
											try {
												var obj = JSON.parse(result);
												if(obj.code=="200") {
													//popup("Request sended.");
													updateStatus(1);
													//$('#loading').hide();
												} else {
													popup(obj.message);
													//$('#debug').html(obj.message);
													$('#loading').hide();
												}
											} catch (err) {
													tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Request approve Error : API return unknow json','error','small','',false);
												//popup("Request approve Error : API return unknow json");
												$('#loading').hide();
											}
											//location.reload();
										},
										error: function()
										{
											tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Cannot call save api','error','small','',false);
										//	popup("Cannot call save api");
											$('#loading').hide();
										}
									});
							});
						<?php
					} // end enableRequest
					?>
					<?php
					if ($enableApprove==1) {
						?>
						$("#btnApprove").click(function(){
								//Using approvehtml
								$("#formApprove").find('input[name="bodyhtml"]').val($("#formApprove").find('input[name="approvehtml"]').val());
								//Disable resend
								$('#btnApprove').prop('disabled', true);
								$('#btnReject').prop('disabled', true);
								$.ajax({
										type: "POST",
										url: "api/sendMail.php",
										data: $("#formApprove").serialize(),
										beforeSend: function()
										{
											$('#loading').show();
										},
										success: function(result){
											try {
												var obj = JSON.parse(result);
												if(obj.code=="200") {
													//popup("Request sended.");
													updateStatus(2);
													//$('#loading').hide();
												} else {
													popup(obj.message);
													//$('#debug').html(obj.message);
													$('#loading').hide();
												}
											} catch (err) {
												tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Approve Error : API return unknow json','error','small','',false);
											//	popup("Approve Error : API return unknow json");
												$('#loading').hide();
											}
											//location.reload();
										},
										error: function()
										{
												tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Cannot call save api','error','small','',false);
										//	popup("Cannot call save api");
											$('#loading').hide();
										}
									});
							});
						$("#btnReject").click(function(){
								//Using approvehtml
								$("#formApprove").find('input[name="bodyhtml"]').val($("#formApprove").find('input[name="rejecthtml"]').val());
								//Disable resend
								$('#btnApprove').prop('disabled', true);
								$('#btnReject').prop('disabled', true);
								$.ajax({
										type: "POST",
										url: "api/sendMail.php",
										data: $("#formApprove").serialize(),
										beforeSend: function()
										{
											$('#loading').show();
										},
										success: function(result){
											try {
												var obj = JSON.parse(result);
												if(obj.code=="200") {
													//popup("Request sended.");
													updateStatus(3);
													//$('#loading').hide();
												} else {
													popup(obj.message);
													//$('#debug').html(obj.message);
													$('#loading').hide();
												}
											} catch (err) {
												tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Reject Error : API return unknow json','error','small','',false);
										//		popup("Reject Error : API return unknow json");
												$('#loading').hide();
											}
											//location.reload();
										},
										error: function()
										{
											tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','Cannot call save api','error','small','',false);
										//	popup("Cannot call save api");
											$('#loading').hide();
										}
									});
							});
						<?php
					} // end enableRequest
					?>
					$("#approxstudent").inputFilter(function(value) {
							return /^\d*$/.test(value);});
					$("#approxhead").inputFilter(function(value) {
							return /^-?\d*[,]?\d*$/.test(value);});
					$("#approxtotal").inputFilter(function(value) {
							return /^-?\d*[,]?\d*$/.test(value);});

				}); //end document.ready

			$(function() {
					$("input[required]").each(function() {
							if ($(this).val().length > 0 ) {
								$(this).css('border-color', '#ccc');
							} else {
								$(this).css('border-color', '#f28d68');
							}

							$(this).on('change',function(){
									if ($(this).val().length > 0 ) {
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
