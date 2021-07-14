<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("istraining","courseGeneral"); //If need permission enable here
include_once("lib/myLib.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : ข้อมูลการฝึกอบรม</title>
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
    </style>
    <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-fixedheader/dataTables.fixedHeader.css"/>
    <link rel="stylesheet" type="text/css" href="lib/DataTables/datatables-responsive/dataTables.responsive.css"/>
    <link rel="stylesheet" href="lib/fonts/material-design/css/material-design-iconic-font.min.css">

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

                                  <li class="active">
                                      <a href="javascript:void(0);">
                                       <i class="fas fa-fw fa-history"></i> ข้อมูลการฝึกอบรม
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
									<table id="courseTable" class="table table-striped table-bordered nowrap width-full responsive" style="width:100%">
										<thead>
										<tr>
                        <td style="padding:5px;">ID ชื่อหลักสูตร</td>
                        <td style="padding:5px;">ชื่อหลักสูตร</td>
												<td style="padding:5px;">สถานที่จัดอบรม</td>
												<td style="padding:5px;">วิทยากร</td>
												<td style="pad6ing:5px;">หมายเหตุ</td>
												<td style="padding:5px;min-width:110px;">สถานะหลักสูตร</td>
												<td style="padding:5px;min-width:120px;">สถานะส่วนบุคคล</td>
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

                      $sql="select trainingsite.siteprovince,trainingsite.sitero,trainingsite.sitename";
                      $sql.=",course.*,cm.coursecodeid,cm.courselevel,cm.coursenumber,cm.coursesequence";
                      $sql.=",std.status studentstatus";
											$sql.=" from coursestudent std,course";
											$sql.=" left join trainingsite on course.siteid=trainingsite.siteid";
                      $sql.=" left join coursemaster cm on course.coursemasterid=cm.courseid";
											$sql.=" where course.status>=0 and std.courseid=course.courseid";
											$sql.=" and std.employeeno='".$_SESSION["employee_id"]."'";
											$sql.=" order by course.courseid";
				              $result=json_decode(pgQuery($sql),true);
											if($result['code']=="200") {
												for($i=0;$i<count($result)-1;$i++) {
					                  echo "<tr>\n";
                            if ($result[$i]['coursemasterid']=="0") {
                              echo "<td style='color:red;'>ยังไม่กำหนด</td>";
                            } else {
                              echo "<td>".$codeName[$result[$i]['coursecodeid']];
  														echo str_pad($result[$i]['courselevel'],2,'0',STR_PAD_LEFT)."-";
  														echo str_pad($result[$i]['coursenumber'],3,'0',STR_PAD_LEFT)."-";
  											      echo str_pad($result[$i]['coursesequence'],1,'0',STR_PAD_LEFT);
  														echo "</td>";
                            }

					                  echo "<td>".$result[$i]['nameofficial']."<br/>".$result[$i]['namemarketing']."</td>";
					                  echo "<td>";
														echo $result[$i]['sitename']."<br/>";
														echo "จ.".$result[$i]['siteprovince']." RO".$result[$i]['sitero'];
														echo "</td>";
														echo "<td>";
														$trainerArray=explode(",",$result[$i]['trainerid']);
														$trainerName="";
														foreach ($trainerArray as $trainerid) {
															$trainerName.=$trainer[$trainerid]."<br/>";
														}
														$trainerName=rtrim($trainerName,"<br/>");
                            $status_style="";
                            if($result[$i]['studentstatus']=="1"){
                                $status_style="style='color:#5cb85c;'";
                            }else if($result[$i]['studentstatus']=="2"){

                                $status_style="style='color:#d9534f;'";
                            }else{
                                $status_style="";
                            }



														echo $trainerName;
														echo "</td>";
														echo "<td>".$result[$i]['courseremark']."</td>";
														echo "<td>".statusText($result[$i]['status'])."</td>";
					                  echo "<td style='width:50px;'>";
					                  echo "<a ".$status_style." href='personalAccepted.php?courseID=".$result[$i]['courseid']."'>";
                            $studentStatus=$result[$i]['studentstatus'];
                            switch ($studentStatus) {
                              case '0':
                                echo "<i class='fas fa-fw fa-hourglass-start'></i>".statusStudentText($studentStatus);
                                break;
                              case '1':
                                echo "<i class='fas fa-fw fa-edit'></i>".statusStudentText($studentStatus);
                                break;
                              case '2':
                                echo "<i class='fas fa-fw fa-question'></i>".statusStudentText($studentStatus);
                                break;
                              case '3':
                                echo "<i class='fas fa-fw fa-user-slash'></i>".statusStudentText($studentStatus);
                                break;
                              case '4':
                                echo "<i class='fas fa-fw fa-user-slash'></i>".statusStudentText($studentStatus);
                                break;
                              default:
                                // code...
                                break;
                            }
                            echo "</a>";
					                  echo "</td>";
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
    <script type="text/javascript">
				function courseDelete(id) {
					if(confirm("Want to delete?")) {
						$.ajax({
							type: "POST",
							url: "db/deleteCourseGeneral.php",
							data: "courseID="+id,
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
        $(document).ready(function() {
					var table = $('#courseTable').DataTable({
	          "pageLength": 10,
	          responsive: true,
            //"scrollX": true,
            "order": [[1, 'asc']],
	          "columnDefs": [
							{
							"targets": 7,
							"visible": false
							}
						],
	          "dom": 'frtip'
	        });
        });
    </script>
  </body>
</html>
