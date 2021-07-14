<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraining","trainingsite"); //If need permission enable here

//Initial form value
$siteid = isset($_GET["siteid"])?$_GET["siteid"]:'';
if($siteid<>""){
	$sql="select * from trainingsite where siteid=".$siteid;
	$result=json_decode(pgQuery($sql),true);
	$isFound=0;
	for($i=0;$i<count($result)-1;$i++){
		$isFound=1;
		$sitename=$result[$i]['sitename'];
		$siteroom=$result[$i]['siteroom'];
		$sitefloor=$result[$i]['sitefloor'];
		$siteprovince=$result[$i]['siteprovince'];
		$sitero=$result[$i]['sitero'];
		$contactname = $result[$i]["contactname"];
		$contactposition = $result[$i]["contactposition"];
		$contacttelephone = $result[$i]["contacttelephone"];
		$contactemail = $result[$i]["contactemail"];
		$siteurl = $result[$i]["siteurl"];
		$siteremark = $result[$i]["siteremark"];
		$sitelat=$result[$i]["sitelat"];
		$sitelong=$result[$i]["sitelong"];
		$lastupdate = $result[$i]["lastupdate"];
	}
	if($isFound==0){ //Wrong access_token
		$url="error.php?code=trainingSite";
		header("Location: ".$url);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>TIS : สถานที่จัดอบรม</title>
		<?php include_once("basicHeader.php");?>
		<style>
			@media (min-width: 768px) {
				.modal-xl {
					width: 90%;
					max-width: 1200px;
				}
			}

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
			.select2-container--bootstrap .select2-results__group {
				color: inherit;
				font-size: inherit;
				font-style: italic;
				padding: 6px 4px;
			}

			.controls {
				margin-top: 10px;
				border: 1px solid transparent;
				border-radius: 2px 0 0 2px;
				box-sizing: border-box;
				-moz-box-sizing: border-box;
				height: 32px;
				outline: none;
				box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			}
			.modal-footer button {
			        float:right;
			        margin-left: 10px;
			      }
			/*  .pac-card {
			margin-top: 10px;
			border: 1px solid transparent;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			height: 32px;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			}*/

			/*	#pac-container {
			padding-bottom: 12px;
			margin-right: 12px;
			z-index: 1040 !important;
			}*/

			/*	.pac-controls {
			display: inline-block;
			padding: 5px 11px;
			}*/

			/*		.pac-controls label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
			}*/
			#pac-input {
				background-color: #fff;
				font-family: "Kanit",Roboto;
				font-size: 15px;
				font-weight: 300;
				margin-left: 12px;
				padding: 0 11px 0 13px;
				text-overflow: ellipsis;
				/*	width: 400px;*/
			}

			.btn{
				font-family: "Kanit",Roboto;

			}
			.gm-style {
				font: 400 11px "Kanit",Roboto, Arial, sans-serif;
				text-decoration: none;
			}

			#pac-input:focus {
				border-color: #4d90fe;
			}

			.ui-autocomplete {
				z-index: 1051 !important;
			}
			.modal{
				z-index: 1000;
			}
			.modal-backdrop{
				z-index: 10;
			}
			​
			#slidecontainer {
				width: 100%;
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
	</head>
	<body>
		<div id="wrap">
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
																	<li >
																			<a href="trainingSite.php">
																			 <i class="fas fa-fw fa-hotel"></i> สถานที่จัดอบรม
																			</a>
																	</li>
																 <li class="active">

																					<i class="fas fa-map-pin"></i> ข้อมูลสถานที่

																	</li>
																<!--	<li >
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
								<div class="row" style="margin-left:2px;">
									<form id="formSite" name="formSite" class="form-horizontal">
									<input type="hidden" id="siteid" name="siteid" value="<?php echo $siteid;?>">
									<div class="col-md-8">
										<div class="panel panel-info">
											<div class="panel-heading">
												<i class="fas fa-map-pin"></i> ข้อมูลสถานที่
											</div>
											<div class="panel-body">
												<div class="form-group">
													<label class="control-label col-sm-3"   for="sitename">สถานที่จัดอบรม</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="สถานที่จัดอบรม" required
														id="sitename" name="sitename" value="<?php echo $sitename;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="siteroom">ห้อง</label>
													<div class="col-sm-8 row" style="padding-right: 0px;">
														<div class="col-sm-8" >
															<input type="text" class="form-control" placeholder="ชื่อห้อง"
															id="siteroom" name="siteroom" value="<?php echo $siteroom;?>">
														</div>
														<div class="col-sm-1">
															<label class="control-label" for="sitefloor">ชั้น</label>
														</div>
														<div class="col-sm-3" style="padding-right: 0px;">
															<input type="text" class="form-control" placeholder="ชั้น"
															id="sitefloor" name="sitefloor" value="<?php echo $sitefloor;?>">
														</div>
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="siteprovince">จังหวัดที่ตั้ง</label>
													<div class="col-sm-8 slrequire">
														<select id="siteprovince" name="siteprovince" class="form-control" required>
															<option></option>
															<?php
															$sql='select provincename,ro from paramprovince order by ro,provincename';
															$result=json_decode(pgQuery($sql),true);
															$activeRO="";
															for($i=0;$i<count($result)-1;$i++){
																$currentRO=$result[$i]['ro'];
																if($currentRO<>$activeRO){ //Start group
																	if($activeRO<>""){ //Close if not begining
																		echo "</optgroup>\n";
																	}
																	echo "<optgroup label='RO $currentRO'>\n";
																	$activeRO=$currentRO;
																}
																if($activeRO==$sitero){
																	if($result[$i]['provincename']==$siteprovince){
																		echo "<option selected>";
																	} else{
																		echo "<option>";
																	}
																} else{
																	echo "<option>";
																}
																echo $result[$i]['provincename']."</option>\n";
															}
															echo "</optgroup>\n";
															?>
														</select>
														<input type="hidden" id="sitero" name="sitero" value="<?php echo $sitero;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="contactname">ผู้ประสานงาน</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="ผู้ประสานงาน"
														id="contactname" name="contactname" value="<?php echo $contactname;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="contactposition">ตำแหน่ง</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="ตำแหน่ง"
														id="contactposition" name="contactposition" value="<?php echo $contactposition;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="contacttelephone">เบอร์โทร ติดต่อ</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="เบอร์โทรติดต่อ"
														id="contacttelephone" name="contacttelephone" value="<?php echo $contacttelephone;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="contactemail">e-mail ติดต่อ</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="e-mail ติดต่อ"
														id="contactemail" name="contactemail" value="<?php echo $contactemail;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="siteurl">website</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="website"
														id="siteurl" name="siteurl" value="<?php echo $siteurl;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3" for="siteremark">หมายเหตุ สถานที่</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" placeholder="หมายเหตุ สถานที่"
														id="siteremark" name="siteremark" value="<?php echo $siteremark;?>">
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-sm-3">Location</label>
													<div class="col-sm-8">
														<div class="row">
															<div class="col-sm-5 nopadding">
																<input type="text" class="form-control" placeholder="ละติจูด"
																id="sitelat" name="sitelat" value="<?php echo $sitelat;?>">
															</div>
															<div class="col-sm-5 nopadding">
																<input type="text" class="form-control" placeholder="ลองจิจูด"
																id="sitelong" name="sitelong" value="<?php echo $sitelong;?>">
															</div>
															<div class="col-sm-1 nopadding">
																<span class="col-sm-8" data-toggle="modal" style="cursor: pointer;" data-backdrop="static" data-keyboard="false" data-target="#mapModal" data-lat='13.9055264' data-lng='100.52104259999999'>
																	<img src="assets/img/ggmap.png" style="height:30px">
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group row" <?php if($siteid==""){ echo " style='display:none'";}?>>
													<label class="control-label col-sm-4">ปรับปรุงข้อมูลล่าสุด</label>
													<div class="col-sm-8">
														<span class="col-sm-8" style="padding-top: 7px;"><?php echo date('d/m/Y H:i:s', strtotime($lastupdate));?></span>
													</div>
												</div>
											</div> <!--End panel body-->
										</div> <!--End panel Left-->
									</div> <!--End Left MD-->
									<br/>

									<div class="form-group col-md-8" align="center">
										<div class="form-group col-md-2">
											<a href="trainingSite.php"  class="btn btn-default btn-sm" id="btnSave">
												<i class="fas fa-arrow-circle-left"></i> กลับ
											</a>
											</div>
										<button type="submit" class="btn btn-success btn-sm" id="btnSave">
											<i class="fas fa-save"></i> บันทึกข้อมูล
										</button>
										<button type="reset" id="btnCancel"  class="btn btn-danger btn-sm">
											<i class="fas fa-times-circle"></i> ยกเลิก
										</button>
									</div>
								</div> <!--End form-->
								<div id="maptest"></div>
								<!--Reserve for other info-->
								<div class="col-md-12">
								</div>
								<!--End Reserve-->
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
						<div id="modalContent">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>
		<div id="mapModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-xl" role="document">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header " style="padding:5px;" >
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>




						<input id="pac-input" style="width:80%;" class="form-control" type="text" placeholder="ค้นหาจากที่อยู่....">



					</div>
					<div class="modal-body" style="padding:5px;">


						<div style="width: 100%; min-height: 450px;" id="map_canvas"></div>

					</div>
					<div class="modal-footer" style="margin-top:0px;padding:10px;">
						<div style="float:left;color:#737373;font-style:italic"><strong>พิกัด:</strong> - <span id="ShowLatLng" style="color:#151B8D"></span></div>
						<div style="float:right">
							<div style="float:left;color:#737373;padding-right:20px;">
								* double click เพื่อเลือก Location ที่ต้องการหรือเปลี่ยนแปลง Location</div>
							<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">ปิด</button>
							<button type="button" class="btn btn-primary btn-sm" id="btnLocationSelect" ><i class="fas fa-crosshairs"></i> ใช้พิกัดนี้</button>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php include_once("notification.php");?>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing&language=th&libraries=places&key=AIzaSyCKibJ57w93K1ch9UupT3ZeWsJSqPy4NKE"></script>
		<script src="assets/lib/jquery.min.js"></script>
		<script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/lib/screenfull/screenfull.js"></script>
		<script src="assets/js/main.min.js"></script>
		<script src="lib/select2-4.0.5/js/select2.min.js"></script>
		<script src="lib/bootbox-5.1.3/bootbox.js"></script>
		<script src="assets/js/tisApp.js"></script>
		<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC67hii0vkllbTtEZMdlQ0lSfqh3rsdDEo"></script>-->

		<script type="text/javascript">

			var map;
			var markers = [];
			var markerLocation=[];
			var selectLocation;
			var bounds;
			var infoWindow1 = new google.maps.InfoWindow(), j;


			function popup(msg) {
				$('#modalContent').html(msg);
				//$('#modalContent').text(msg);
				$('#popupModal').modal('show');
			}
			function saveSite() {
				$.ajax({
						type: "POST",
						url: "db/saveTrainingSite.php",
						data: $("#formSite").serialize(),
						beforeSend: function()
						{
							$('#loading').show();
						},
						success: function(result){
					//		alert(result);
							try {

								var obj = JSON.parse(result);
								$('#loading').hide();
								if(obj.code=="200") {

									<?php
										if($siteid<>""){
											echo "var IsRedirect=false;";

										}else{

											echo "var IsRedirect=true;";
										}

									 ?>

									tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','small','trainingSite.php',IsRedirect);

								//	location.href="trainingSite.php";
								//	popup("Completed save.");
								} else {
								//	tisAlertMessage('ผลการดำเนินการ','<i class="fas fa-clipboard-check fa-2x"></i> การบันทึกข้อมูลเสร็จสิ้น','completed','large','trainingSite.php',false);

									tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>'+obj.message,'error','small','trainingSite.php',false);
								//		location.href="trainingSite.php";
								//popup(obj.message);
									//$('#debug').html(obj.message);

								}
							} catch (err) {
								  tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>API return unknow json<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','trainingSite.php',false);

							//	popup("API return unknow json");
								$('#loading').hide();
							//	location.href="trainingSite.php";
							}
							//location.reload();
						},
						error: function()
						{
							tisAlertMessage('แจ้งเตือน พบข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ <br>Cannot call save api<br>กรุณาติดต่อผู้ดูแลระบบ','error','small','',false);

						//	popup("Cannot call save api");
							$('#loading').hide();
						//	location.href="trainingSite.php";
						}
					});
			}
			$(document).ready(function() {
					$('#siteprovince').select2({
							placeholder: 'เลือกจังหวัดที่ตั้ง'
						});
					$('#siteprovince').change(function() {
							var opt = $(this).find(':selected');
							var sel = opt.text();
							var og = opt.closest('optgroup').attr('label');
							var rotext=og.split(" ");
							$('#sitero').val(rotext[1]);
						});
					$("#formSite").submit(function(e) {
							event.preventDefault();
							saveSite();
						});
					$('#popupModal').on('hidden.bs.modal', function () {
							if ($('#modalContent').html()=="Completed save.") {
								location.href="trainingSite.php";
							}


						});


						$( "#btnCancel" ).click(function( event ) {
							event.preventDefault();
							bootbox.confirm({
								closeButton: false,
								title:"กรุณายืนยัน ยกเลิกการแก้ไขข้อมูล ?",
								backdrop: true,
								size: 'small',
								animate: true,
								centerVertical:true,
								className:"confirmDelete bootbox-confirm",
								message: "คุณต้องการยกเลิกการแก้ไขข้อมูล </br>และกลับสู่เมนูก่อนหน้า",
								buttons: {
									confirm: {
											label: '<i class="fa fa-check "></i> ใช่',
											className:'btn btn-success btn-sm'
									},
										cancel: {
												label: '<i class="fa fa-times"></i> ไม่ใช่',
												className:'btn btn-danger btn-sm'
										}

								},
								callback: function (result) {
									if(result){
									window.location.href = 'trainingSite.php';
									}

								}
							});
						});
					//Map modal
					// var map = null;
					// var myMarker;
					// var myLatlng;





					function initializeGMap(lat, lng) {
						selectLocation=null;
						OnOffBtnSelectLocation();
						markerLocation=[];
						clearMarkers(markerLocation);

						//console.log(markerLocation.length);

						var mapElement1 = document.getElementById('map_canvas');
						mapElement1.innerHTML='Loading...';
						var myLatlng = new google.maps.LatLng(lat, lng);

						setTimeout(function() {
								var bounds = new google.maps.LatLngBounds();
								var myOptions = {
									center: myLatlng,
									zoom: 15,
									minZoom: 10,
									mapTypeIds: [
										google.maps.MapTypeId.TERRAIN,
										google.maps.MapTypeId.ROADMAP
									],
									gestureHandling: 'greedy',
									scaleControl:true,
									disableDefaultUI:true,
									streetViewControl: false,
									mapTypeControl: true,
									zoomControl: true
								};

								map = new google.maps.Map(mapElement1, myOptions);
								//	document.getElementById('pac-input').value="";
								var Default_position = new google.maps.LatLng(lat, lng);
								//		genSelectMarker(Default_position);

								var inputText = (document.getElementById('pac-input'));
								inputText.value=$('#sitename').val()+' '+$('#siteprovince').val();

								//		map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputText);

								var searchBox = new google.maps.places.SearchBox((inputText));
								searchBox.bindTo("bounds", map);
								google.maps.event.addListener(searchBox, 'places_changed', function () {
										var places = searchBox.getPlaces();

										if (places.length == 0) {
											return;
										}
										for (i = 0, marker; marker = markers[i]; i++) {
											marker.setMap(null);
										}



										// For each place, get the icon, place name, and location.

										bounds = new google.maps.LatLngBounds();
										var location ;
										for (var i = 0, place; place = places[i]; i++) {

											//  genSelectMarker(place.geometry.location);
											var image = {
												url: place.icon,
												size: new google.maps.Size(71, 71),
												origin: new google.maps.Point(0, 0),
												anchor: new google.maps.Point(17, 34),
												scaledSize: new google.maps.Size(25, 25)
											};

											// Create a marker for each place.
											var marker = new google.maps.Marker({
													map: map,
													icon: image,
													title: place.name,
													position: place.geometry.location
												});
												console.log(place.geometry.location);
											location=place.geometry.location;
											markers.push(marker);


											//Determine the location where the user has clicked.
											bounds.extend(place.geometry.location);

										}
										//  console.log(location);
										genSelectMarker(location);
										map.fitBounds(bounds);
									});


								var addMark=new google.maps.event.addListener(map, 'dblclick', function (e) {
										var location = e.latLng;
										//			selectLocation=location;
										genSelectMarker(location);

									});

								if(	$('#sitelat').val()!="" && 	$('#sitelong').val()!=""){
									Default_position = new google.maps.LatLng($('#sitelat').val().trim(), $('#sitelong').val().trim());
									//	console.log(Default_position);
									genSelectMarker(Default_position);
									map.setCenter(Default_position);
								}else{

									if($('#sitename').val()!=""){
										var request = {
											query: $('#sitename').val()+' '+$('#siteprovince').val(),
											fields: ['name', 'geometry'],
										};
										var service = new google.maps.places.PlacesService(map);

										service.findPlaceFromQuery(request, function(results, status) {
												var location ;
												if (status === google.maps.places.PlacesServiceStatus.OK) {
													for (var i = 0; i < results.length; i++) {
														//  createMarker(results[i]);
														location=results[i].geometry.location;
													}
													genSelectMarker(location);
													map.setCenter(location);
												}else{
													genSelectMarker(Default_position);
													map.setCenter(Default_position);
												}
											});
									}else{
										genSelectMarker(Default_position);
										map.setCenter(Default_position);
									}

								}




							}, 1300);




						/*myMarker = new google.maps.Marker({
						position: myLatlng,
						draggable: true,
						title: "สถานที่จัดอบรม"
						});

						myMarker.setMap(map);*/




					}

					//  clear Marker
					function clearMarkers(markersRemove) {
						//console.log(markersRemove.length);
						for (var i = 0; i < markersRemove.length; i++) {
							if (markersRemove[i]) {

								markersRemove[i].setMap(null);

							}
						}

						markersRemove = [];
						//console.log(markersRemove.length);
					}



					$('#btnLocationSelect').click(function() {
							//console.log('test click');

							if(selectLocation!=null){

								$('#sitelat').val(selectLocation.lat());
								$('#sitelong').val(selectLocation.lng());
							}else{

							}
							$('#mapModal').modal('hide')


						});
					function OnOffBtnSelectLocation(){

						if(selectLocation==null){
							document.getElementById("btnLocationSelect").disabled = true;
							$('#ShowLatLng').html('ยังไม่เลือก');
						}else{

							document.getElementById("btnLocationSelect").disabled = false;
						}



					}



					function genSelectMarker(target_latlng){
						//console.log(markerLocation.length);
						//console.log(target_latlng);
						if(markerLocation.length==0){


							var imageMap = '/assets/img/Map-Marker-Bubble-Billboard.png';
							//Determine the location where the user has clicked.




							//Create a marker and placed it on the map.
							var markersClick = new google.maps.Marker({
									position: target_latlng,
									map: map,
									title: "คลิกแล้วลาก เพื่อเลื่อน",
									draggable: true,
									animation: google.maps.Animation.DROP,
									icon: imageMap
								});


							markerLocation.push(markersClick);

							selectLocation=target_latlng;

							$('#ShowLatLng').html(markersClick.getPosition().lat()+', '+markersClick.getPosition().lng());


							/*		var request = {
							placeId: 'ChIJN1t_tDeuEmsRUsoyG83frY4',
							fields: ['name', 'formatted_address', 'place_id', 'geometry']
							};*/



							//service.getDetails(request, function(place, status) {
							//    if (status === google.maps.places.PlacesServiceStatus.OK) {
							/*  var marker = new google.maps.Marker({
							map: map,
							position: place.geometry.location
							});*/
							/*  google.maps.event.addListener(markersClick, 'click', function() {
							infoWindow1.setContent('<div><strong>' + place.name + '</strong><br>' +
							'Place ID: ' + place.place_id + '<br>' +
							place.formatted_address + '</div>');
							infoWindow1.open(map, this);
							});*/
							//      }
							//    });
							/*
							google.maps.event.addListener(markersClick, 'click', (function (marker1, j) {
							return function () {
							infoWindow1.setContent("");
							infoWindow1.open(map, marker1);
							}
							})(markersClick, j));
							*/
							//	console.log(markersClick);

							google.maps.event.addListener(markersClick, 'dragend', (function (marker2) {
										return function () {


											$('#ShowLatLng').html(markersClick.getPosition().lat()+', '+markersClick.getPosition().lng());
											//selectLocation=new google.maps.LatLng(markersClick.getPosition().lat(), markersClick.getPosition().lng());
											selectLocation=markersClick.getPosition();
										}
									})(markersClick));

							google.maps.event.addListener(markersClick, 'drag', (function (marker2) {
										return function () {

											selectLocation=markersClick.getPosition();
											$('#ShowLatLng').html(markersClick.getPosition().lat()+', '+markersClick.getPosition().lng());
										}
									})(markersClick));


						}else{
							var markersClick=markerLocation[0];
							markersClick.setPosition(target_latlng);
							selectLocation=markersClick.getPosition();
							$('#ShowLatLng').html(markersClick.getPosition().lat()+', '+markersClick.getPosition().lng());

						}
						OnOffBtnSelectLocation();

					}

					// Re-init map before show modal
					$('#mapModal').on('show.bs.modal', function(event) {
							$(".pac-container").css("z-index", $("#myModal").css("z-index"));
							var button = $(event.relatedTarget);
							document.getElementById('pac-input').value='';
							initializeGMap(button.data('lat'), button.data('lng'));
							$("#map_canvas").css("width", "100%");
						});

					// Trigger map resize event after modal shown
					$('#mapModal').on('shown.bs.modal', function() {
							$(".pac-container").css("z-index", $("#myModal").css("z-index"));
							google.maps.event.trigger(map, "resize");
							//  map.setCenter(myLatlng);
						});

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

						$("select[required]").each(function() {
							if ($(this).prop('selectedIndex') > 0 ) {
								$('.slrequire span.select2-selection').attr('style', 'border-color: #ccc !important');
							} else {
								$('.slrequire span.select2-selection').attr('style', 'border-color: #f28d68 !important');
							}

							$(this).on('change',function(){
									if ($(this).prop('selectedIndex') > 0 ) {
										$('.slrequire span.select2-selection').attr('style', 'border-color: #ccc !important');
									} else {
										$('.slrequire span.select2-selection').attr('style', 'border-color: #f28d68 !important');
									}
							});
						}) //end each
						}); //end function

				});



		</script>
	</body>
</html>
