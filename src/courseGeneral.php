<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("istraining","courseGeneral"); //If need permission enable here
include_once("lib/myLib.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : หลักสูตรทั่วไป</title>
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

        table.dataTable tbody tr.selected {
          background-color: #FFFDE7;
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
                <ol class="breadcrumb">
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fa fa-fw fa-book"></i> หลักสูตร
                    </a>
                  </li>
                  <li class="active">

                      <i class="fa fa-fw fa-tasks"></i>  หลักสูตรทั่วไป

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
												<td style="padding:5px;" align="center">สถานที่จัดอบรม</td>
												<td style="padding:5px;" align="center">วิทยากร</td>
												<td style="padding:5px;" align="center">กำหนดการ</td>
												<td style="padding:5px;" align="center">ชม.อบรม</td>
												<td style="padding:5px;" align="center">ผู้เรียน</td>
												<td style="padding:5px;" align="center">หมายเหตุ</td>
												<td style="padding:5px;" align="center">Status</td>
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
											$sql.=" from course left join trainingsite on course.siteid=trainingsite.siteid";
                      $sql.=" left join coursemaster cm on course.coursemasterid=cm.courseid";
											$sql.=" where course.status>=0 order by course.courseid";
				              $result=json_decode(pgQuery($sql),true);
											if($result['code']=="200") {
												for($i=0;$i<count($result)-1;$i++) {
					                  echo "<tr>\n";
														if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
						                  echo "<td style='min-width:60px;'>";
						                  echo "<a href='courseGeneralEdit.php?courseID=".$result[$i]['courseid']."'><i class='fas fa-fw fa-edit'></i> แก้ไข</a>";
						                  echo "<br/>";
						                  echo "<a style='color:red;' href='javascript:courseDelete(".$result[$i]['courseid'].")'><i class='fas fa-fw fa-trash-alt'></i> ลบ</a>";
						                  echo "</td>";
														}
                            if ($result[$i]['coursemasterid']=="0") {
                              echo "<td style='color:red;'>ยังไม่กำหนด</td>";
                            } else {
                              echo "<td>".$codeName[$result[$i]['coursecodeid']];
  														echo str_pad($result[$i]['courselevel'],2,'0',STR_PAD_LEFT)."-";
  														echo str_pad($result[$i]['coursenumber'],3,'0',STR_PAD_LEFT)."-";
  											      echo str_pad($result[$i]['coursesequence'],1,'0',STR_PAD_LEFT);
  														echo "</td>";
                            }

					                  echo "<td style='max-width:145px;'>".$result[$i]['nameofficial']."<br/>".$result[$i]['namemarketing']."</td>";
					                  echo "<td >";
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
														echo $trainerName;
														echo "</td>";
														echo "<td>".$result[$i]['schedule']."</td>";
														echo "<td>".minToText($result[$i]['minutetrain'])."</td>";
					                  echo "<td>";

                            if($result[$i]['status']=="40") {
                              $sql="select count(*) cnt from coursestudent where courseid=".$result[$i]['courseid'];
                              $sql.=" and status in ('0','1','2')";
        										  $resultInside=json_decode(pgQuery($sql),true);
                              $student=0;
                              if($resultInside['code']=="200") {
                                $student=$resultInside[0]['cnt'];
        										  }
                              if($student=="0") {
                                echo $student."/".$result[$i]['approxstudent'];
                              } else {
                                echo "<a href='courseStudent.php?courseID=".$result[$i]['courseid']."'>";
                                echo $student."/".$result[$i]['approxstudent'];
                                echo "</a>";
                              }

                            } else {
                              echo $result[$i]['approxstudent'];
                            }
                            echo "</td>";
														echo "<td>".$result[$i]['courseremark']."</td>";
                            echo "<td>";
                            echo str_replace(")",")<br/>",statusText($result[$i]['status']));
                            $sqlRight="select * from courseAllocate ";
                            $sqlRight.="where employeeNo='".$_SESSION["employee_id"]."' and courseId=".$result[$i]['courseid'];
                            $resultRight=json_decode(pgQuery($sqlRight),true);
                            $hasRight=0;
                            for($j=0;$j<count($resultRight)-1;$j++) {
                              $hasRight=1;
                            }
                            if($hasRight!=0) { //Have Right
                              echo "<br/><a class='btn btn-primary btn-xs' data-original-title='จัดสรรผู้เข้ารับการอบรม' data-toggle='tooltip' data-placement='top' href='courseAllocate.php?courseID=".$result[$i]['courseid']."'><i class='fas fa-person-booth'></i> </a>";
                              echo " <a  class='btn btn-warning btn-xs' data-original-title='เลือกผู้เข้ารับการอบรม' data-toggle='tooltip' data-placement='top' href='courseInvite.php?courseID=".$result[$i]['courseid']."'><i class='fas fa-user-plus'></i> </a>";
                            }
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
            "order": [],
	          "columnDefs": [
							{
	            "targets": 0,
	            "orderable": false,
	            "searchable": false
							},
							{
							"targets": 10,
							"visible": false
							}
						],
	          "dom": '<"toolbar">frtip'
	        });
					<?php
						if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
					?>
	        $("div.toolbar").html('<a class="btn btn-app" href="courseGeneralEdit.php"><i class="fas fa-plus-circle"></i> เพิ่มหลักสูตรทั่วไป</a>');
					<?php
						}
					?>
        });
    </script>
  </body>
</html>
