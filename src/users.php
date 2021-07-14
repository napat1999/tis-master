<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
restrict("isadmin","users"); //If need permission enable here
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Users</title>
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
		#loading {
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
    .tabulator-col-title {
      text-align: center;
    }
    .tabulator-col {
      display: table-cell;
      vertical-align: bottom;
    }
	</style>
	<link rel="stylesheet" type="text/css" href="lib/tabulator-4.2.3/css/tabulator.css">
  <link rel="stylesheet" type="text/css" href="lib/tabulator-4.2.3/css/tabulator_tis.css">
  <link rel="stylesheet" type="text/css" href="lib/uikit-3.0.3/css/uikit.min.css">
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
                  <li class="active">

                      <i class="fas fa-users-cog"></i> ตั้งค่าผู้ใช้ระบบ

                      </li>
                  </ol>
              </header>
							<div class="body">
								<div id='script-warning'></div>
								<div id='loading'></div>
                <div class="row">
                    <div class="col-sm-3" style="margin-bottom:5px;">
                      <a class="btn btn-app" href="usersInfo.php" title="เพิ่ม ผู้ใช้งานระบบ">
                        <i class="fas fa-user-plus"></i> เพิ่ม ผู้ใช้งานระบบ
                      </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                      <div id="divTable"></div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    Issue : <br/>
                    Permission Select option ตอนเลือกยังไม่สวย<br/>
                    กำหนดหน้าละ 10 คน ยังไม่ได้ทดสอบ<br/>
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
		<!-- End Modal -->

    <?php include_once("notification.php");?>
		<script src="assets/lib/jquery.min.js"></script>
    <script src="lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/tabulator-4.2.3/js/tabulator.min.js"></script>
    <script src="lib/uikit-3.0.3/js/uikit.min.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
		<script type="text/javascript">
      function userDelete(id) {
        if(confirm("Want to delete?")) {
          $.ajax({
            type: "POST",
            url: "db/deleteUser.php",
            data: "userID="+id,
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

      function popup(msg) {

        bootbox.alert({
    message: msg,
    size: 'small'
});
      //  $('#modalContent').html(msg);
        //$('#modalContent').text(msg);
      //  $('#popupModal').modal('show');
      }
      //Gen Table Data
      var placeholder = "<span>Loading....</span>";
      var tabledata=[
      <?php
        $sql="select * from tisusers order by userro,employeeno";
        $result=json_decode(pgQuery($sql),true);
        $usercount=0;
        if($result['code']=="200") {
          for($i=0;$i<count($result)-1;$i++) {
            $usercount++;
            echo "{";
            echo "userid:\"".$result[$i]['userid']."\",";
            echo "employeeno:\"".$result[$i]['employeeno']."\",";
            echo "thai_name:\"".$result[$i]['th_initial']." ".$result[$i]['thai_name']."\",";
            echo "position:\"".$result[$i]['position']."\",";
            echo "workplace:\"".$result[$i]['workplace']."\",";
            echo "userro:\"".$result[$i]['userro']."\",";
            echo "telephone:\"".$result[$i]['telephone']."\",";
            echo "email:\"".$result[$i]['email']."\",";
            echo "userremark:\"".$result[$i]['userremark']."\",";
            if($result[$i]['isadmin']=="") {
              echo "isadmin:\"0\",";
            } else {
              echo "isadmin:\"".$result[$i]['isadmin']."\",";
            }
            if($result[$i]['istraininghq']=="") {
              echo "istraininghq:\"0\",";
            } else {
              echo "istraininghq:\"".$result[$i]['istraininghq']."\",";
            }
            if($result[$i]['istrainingro']=="") {
              echo "istrainingro:\"0\",";
            } else {
              echo "istrainingro:\"".$result[$i]['istrainingro']."\",";
            }
            if($result[$i]['iscoordinator']=="") {
              echo "iscoordinator:\"0\",";
            } else {
              echo "iscoordinator:\"".$result[$i]['iscoordinator']."\",";
            }
            echo "},";
          }
        } else {
           //echo "Error : ".$result[code]."<!--".$result[message]."--!>";
        }
      ?>
      ];
      <?php
      if($result['code']=="200") {
        if($usercount==0) {
          echo "placeholder = '<span>No data...</span>';";
        }
      } else {
        echo "placeholder = '<span>".str_replace("\n",'',nl2br($result[message]))."</span>';";
      }
      ?>

      $(document).ready(function() {
        var table = new Tabulator('#divTable',{
          placeholder:placeholder,
          data:tabledata,
          layout:"fitColumns",
    /*     responsiveLayout:"collapse",*/
  /*  headerFilterPlaceholder:"",*/
          pagination:"local",
          paginationSize:10,
          tooltips:true,
          columns:[/*{formatter:"responsiveCollapse", width:30, minWidth:30, align:"center", resizable:true, headerSort:false},*/
            {title:"",width:60,field:"employeeno",formatter:function(cell, formatterParams, onRendered){
                  var employeeno=cell.getValue();
                  var imgsrc="https://intranet.jasmine.com/hr/office/Data/"+employeeno+".jpg";
                  var ahref="<div uk-lightbox><a href='"+imgsrc+"'>";
                  var imgTag="<img class='media-object img-thumbnail img-responsive user-img' alt='User Picture' style='min-width:50px;width:50px;' src='"+imgsrc+"'>";
                  return ahref+imgTag+'</a></div>';
              }
              ,headerSort:false,align:"center",
            },
            {title:"รหัสพนักงาน",field:"employeeno",width:100,formatter:function(cell, formatterParams, onRendered){
                var row = cell.getRow();
                var data = row.getData();
                var editLink="<a href='usersInfo.php?userID="+data.userid+"' title='แก้ไข Contact'><i class='fas fa-fw fa-user-edit'></i> แก้ไข</a>";
                var deleteLink="<a href='javascript:userDelete("+data.userid+")'><i class='fas fa-fw fa-user-minus'></i> ลบ</a>";
                //return data.employeeno+"<br/>"+editLink+'<br/>'+deleteLink;
                return data.employeeno+'<br/>'+editLink+'<br/>'+deleteLink;
              },headerFilter:"input",headerFilterPlaceholder:"รหัสพนักงาน"
            },
            {title:"ชื่อ-นามสกุล",width:150,field:"thai_name",headerFilter:"input",headerFilterPlaceholder:"ชื่อ-นามสกุล"},
            // {title:"ตำแหน่ง",width:100,field:"position"},
            // {title:"RO",field:"userro",width:100,formatter:function(cell, formatterParams, onRendered){
            //     var data = cell.getData();
            //     //console.log(data);
            //     return 'RO'+data.userro+'<br/>จ.'+data.workplace;
            //   }
            // },
            // {title:"Contact",width:150,field:"telephone",formatter:function(cell, formatterParams, onRendered){
            //     var data = cell.getData();
            //     return data.telephone+"<br/>"+data.email;
            //   }
            // },
            {//create column group
              title:"Workplace",
              columns:[
                {title:"RO",width:60,field:"userro",align:"center"
                  ,headerFilter:"select",headerFilterPlaceholder:"RO"
                  ,headerFilterParams:{values:true}
                  //,headerFilterParams:{values:{"":"","7":"7", "8":"8"}}
                  ,
                },
                {title:"จังหวัด",width:100,field:"workplace",headerFilter:"input",headerFilterPlaceholder:"จังหวัด"},
              ],
            },
            {//create column group
              title:"Contact",
              columns:[
                {title:"telephone",width:100,field:"telephone",headerFilter:"input",headerFilterPlaceholder:"telephone"},
                {title:"email",width:100,field:"email",align:"center",formatter:function(cell, formatterParams, onRendered){
                    var data = cell.getData();
                    var mailLink="<a href='mailto:"+data.email+"' title='"+data.email+"'><i class='fas fa-fw fa-envelope'></i></a>";
                    return mailLink;
                  },headerFilter:"input",headerFilterPlaceholder:"email"
                },
              ],
            },
            {//create column group
              title:"Permission",
              columns:[
                {title:"Ad", field:"isadmin",width:70,formatter:"tickCross",align:"center"
                  ,headerTooltip:"Admin (ตั้งค่าต่างๆ)",editor:"select"
                  ,editorParams:{values:{"1":"Allow", "0":"Disallow"}}
                  ,headerFilter:"tickCross",headerFilterParams:{"tristate":true}
                  ,tooltip:function(cell){
                    if(cell.getValue()==1) {
                      return "Allow";
                    } else {
                      return "Disallow";
                    }
                  }
                  ,cellEdited:function(cell){
                    var field = cell.getField();
                    var data = cell.getData();
                    updatePermission(data.userid,field,data.isadmin)
                  }/*,formatterParams:{
                    allowEmpty:true,
                    allowTruthy:false,
                    tickElement:"<i class='far fa-check-square fa-1x'></i>",
                    crossElement:"<i class='far fa-times-circle fa-1x'></i>",
                  }*/
                },
                {title:"HQ", field:"istraininghq",width:70,formatter:"tickCross",align:"center"
                  ,headerTooltip:"Training HQ (จัดการ,อนุมัติหลักสูตร)",editor:"select"
                  ,editorParams:{values:{"1":"Allow", "0":"Disallow"}}
                  ,headerFilter:"tickCross",headerFilterParams:{"tristate":true}
                  ,tooltip:function(cell){
                    if(cell.getValue()==1) {
                      return "Allow";
                    } else {
                      return "Disallow";
                    }
                  }
                  ,cellEdited:function(cell){
                    var field = cell.getField();
                    var data = cell.getData();
                    updatePermission(data.userid,field,data.istraininghq)
                  },
                },
                {title:"RO", field:"istrainingro",width:70,formatter:"tickCross",align:"center"
                  ,headerTooltip:"Training RO (ขอเปิดหลักสูตร)",editor:"select"
                  ,editorParams:{values:{"1":"Allow", "0":"Disallow"}}
                  ,headerFilter:"tickCross",headerFilterParams:{"tristate":true}
                  ,tooltip:function(cell){
                    if(cell.getValue()==1) {
                      return "Allow";
                    } else {
                      return "Disallow";
                    }
                  }
                  ,cellEdited:function(cell){
                    var field = cell.getField();
                    var data = cell.getData();
                    updatePermission(data.userid,field,data.istrainingro)
                  }
                },
                {title:"Co", field:"iscoordinator",width:70,formatter:"tickCross",align:"center"
                  ,headerTooltip:"Coordinator (ผู้ประสานงาน)",editor:"select"
                  ,editorParams:{values:{"1":"Allow", "0":"Disallow"}}
                  ,headerFilter:"tickCross",headerFilterParams:{"tristate":true}
                  ,tooltip:function(cell){
                    if(cell.getValue()==1) {
                      return "Allow";
                    } else {
                      return "Disallow";
                    }
                  }
                  ,cellEdited:function(cell){
                    var field = cell.getField();
                    var data = cell.getData();
                    updatePermission(data.userid,field,data.iscoordinator)
                  },
                },
              ],
            },
            {title:"หมายเหตุ", field:"userremark",width:100}
          ],
          tooltipGenerationMode:"hover",
        }); //end tabulator

        function updatePermission(userID,col,status) {
          $.ajax({
            type: "POST",
            url: "db/updatePermission.php",
            data: { userID: userID, col: col,status: status },
            beforeSend: function()
            {
              $('#loading').show();
            },
            success: function(result){
              try {
                var obj = JSON.parse(result);
                if(obj.code=="200") {
                  popup("Permission updated.");

                  $('#loading').hide();
                } else {
                  popup(obj.message);
                  //$('#debug').html(obj.message);
                  $('#loading').hide();
                }
              } catch (err) {
                popup("Update status Error : API return unknow json");
                alert(result);
                $('#loading').hide();
              }
              //location.reload();
            },
            error: function()
            {
              popup("Cannot call save api");
              $('#loading').hide();
            }
          });
        }
      }); //end document.ready
    </script>
  </body>
</html>
