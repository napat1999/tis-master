<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here

//Initial form value
$courseID = isset($_GET["courseID"])?$_GET["courseID"]:'';

if($courseID<>"") {
  $sql="select * from course where courseid=".$courseID;
  $result=json_decode(pgQuery($sql),true);
  $isFound=0;
  for($i=0;$i<count($result)-1;$i++) {
    $isFound=1;
    $approxstudent = $result[$i]["approxstudent"];
  }

  if($isFound==0) { //Wrong access_token
    $url="error.php?code=courseAllocate";
    header("Location: ".$url);
  }

  //Have course info next check right to allocate
  $sql="select * from courseAllocate ";
  $sql.="where employeeNo='".$_SESSION["employee_id"]."' and courseId=".$courseID;
  $resultRight=json_decode(pgQuery($sql),true);
  $hasQuota=0;
  for($i=0;$i<count($resultRight)-1;$i++) {
    $hasQuota=1;
    $allocateQuota=$resultRight[0]["allocatequota"];
    $allocateAssign=$resultRight[0]["allocateassign"];
    $allocateLeft=$resultRight[0]["allocateleft"];
    $allocateUsed=$resultRight[0]["allocateused"];
  }
  if($hasQuota==0) { //No Quota to do
    if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1" or $_SESSION["istraining"]=="1") {
      //If admin&training , initial quota
      $allocateQuota=$approxstudent;
      $allocateAssign=0;
      $allocateLeft=$approxstudent;
      $allocateUsed=0;
    } else {
      $url="forbidden.php?permission=allocatable&code=courseAllocate";
      header("Location: ".$url);
    }
  }
} else {
  //Unsupport no courseID
  $url="error.php?code=courseAllocate";
  header("Location: ".$url);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Course Allocate</title>
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
    .nopaddingRight {
		   padding-right: 0px !important;
		   margin-right: 0px !important;
		}
		.nopadding {
		   padding-left: 0px !important;
		   padding-right: 0px !important;
		}
		.paddingButton{
		   padding-left: 3px !important;
		}
	</style>
  <link rel="stylesheet" href="lib/select2-4.0.5/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="lib/gallery/hes-gallery-master/hes-gallery.min.css">
  <link rel="stylesheet" type="text/css" href="lib/tabulator-4.2.3/css/tabulator.css">
  <link rel="stylesheet" type="text/css" href="lib/tabulator-4.2.3/css/tabulator_tis.css">
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
                <h5>จัดสรรผู้เข้ารับการอบรม</h5>
              </header>
              <div class="body">
      				<div id='script-warning'></div>
		          <div id='loading'></div>
                <form id="formCourseAllocate" name="formCourseAllocate" class="form-horizontal">
                  <div class="row">
                    <div class="col-sm-12">
                      <?php
                      $sql="select * from courseAllocate";
                      $sql.=" where courseid=".$courseID;
                      $sql.=" order by allocateid";

                      $result=json_decode(pgQuery($sql),true);
                      $Assigned=0;
                      $Assigner=0;
                      $allocateAll=array();
                      $self=$_SESSION["employee_id"];
                      //$self="RO2768";
                      $isUpper=true; //for ignore upper level

                      if($result['code']=="200") {
                        for($i=0;$i<count($result)-1;$i++) {
                          if($self==$result[$i]['employeeno']) {
                            //Start to get value next row
                            $isUpper=false;
                            continue;
                          }
                          if($isUpper) {
                            continue;
                          }
                          $allocateAll[$i]['allocateid']=$result[$i]['allocateid'];
                          $allocateAll[$i]['employeeno']=$result[$i]['employeeno'];
                          $allocateAll[$i]['thai_name']=$result[$i]['thai_name'];
                          $allocateAll[$i]['position']=$result[$i]['position'];
                          $allocateAll[$i]['allocatequota']=$result[$i]['allocatequota'];
                          $allocateAll[$i]['allocateassign']=$result[$i]['allocateassign'];
                          $allocateAll[$i]['allocateleft']=$result[$i]['allocateleft'];
                          $allocateAll[$i]['allocateused']=$result[$i]['allocateused'];
                          $allocateAll[$i]['assignby']=$result[$i]['assignby'];
                          $allocateAll[$i]['level']=-1;
                        }
                      }
                      //print_r($allocateAll);
                      echo "Before fill<BR>";
                      foreach ($allocateAll as $key=>$value) {
                        echo $key.":";
                        echo $value['allocateid'].",";
                        echo $value['employeeno'].",";
                        echo $value['assignby'].",";
                        echo $value['level'].",";
                        echo "<br/>";
                      }
                      echo "<HR>";


                      $findAssigner=$self;
                      $level=0;
                      $maxlevel=0;
                      $allocateLevel=array();
                      FillAllocateLevel($findAssigner,$level);

                      function FillAllocateLevel($findAssigner,$level) {
                        global $allocateAll;
                        global $allocateLevel;
                        global $maxlevel;
                        $level++;
                        if (!array_key_exists($level,$allocateLevel)) {
                          $allocateLevel[$level]=array();
                        }
                        $maxlevel=$level;
                        foreach ($allocateAll as $key=>$value) {
                          if($value['assignby']==$findAssigner) {
                            $allocateAll[$key]['level']=$level;
                            $value['level']=$level;
                            array_push($allocateLevel[$level],$value);
                            FillAllocateLevel($value['employeeno'],$level);
                          }
                        }
                      }

                      echo "After fill<BR>";
                      foreach ($allocateAll as $key=>$value) {
                        echo $key.":";
                        echo $value['allocateid'].",";
                        echo $value['employeeno'].",";
                        echo $value['assignby'].",";
                        echo $value['level'].",";
                        echo "<br/>";
                      }
                      echo "<HR>";

                      $tabledata="[";
                      for($i=1;$i<=$maxlevel;$i++) {
                        for($j=0;$j<count($allocateLevel[$i]);$j++) {
                          if($i>1) {
                            $row="_children:[{";
                          } else {
                            $row="{";
                          }
                          $row.="allocateid:\"".$allocateLevel[$i][$j]['allocateid']."\",";
                          $row.="employeeno:\"".$allocateLevel[$i][$j]['employeeno']."\",";
                          $row.="thai_name:\"".$allocateLevel[$i][$j]['thai_name']."\",";
                          $row.="position:\"".$allocateLevel[$i][$j]['position']."\",";
                          $row.="allocatequota:\"".$allocateLevel[$i][$j]['allocatequota']."\",";
                          $row.="allocateassign:\"".$allocateLevel[$i][$j]['allocateassign']."\",";
                          $row.="allocateleft:\"".$allocateLevel[$i][$j]['allocateleft']."\",";
                          $row.="allocateused:\"".$allocateLevel[$i][$j]['allocateused']."\",";
                          $row.="level:\"".$allocateLevel[$i][$j]['level']."\",";
                          if($i>1) {
                            $row.="]},\n";
                          } else {
                            $row.="},\n";
                          }
                          echo $row."<br/><br/>";
                          if($i==1) {
                            $tabledata.=$row;
                          } else {
                            array_push($allocateLevel,$row);
                          }
                        }
                      }
                      $tabledata.="]";
                      echo "<HR>";
                      echo $tabledata;
                      //print_r($allocateLevel);
                      ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <div id="divTable"></div>
                    </div>
                    <div class="col-md-4">
                      จอง
                    </div>
                  </div> <!--End setting row-->
                </form>
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
    <div id="confirmModal" class="modal fade" role="dialog" tabindex="-1">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            กรุณายืนยันการเลือกผู้มีอำนาจจัดสรร
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <br/>
          </div>
          <div class="modal-body">
            <form id="formSelected" name="formSelected" class="form-horizontal">
              <input type="hidden" id="courseID" name="courseID" value="<?php echo $courseID;?>">
              <div class="row">
              <table id="tbPrepared" class="table table-striped table-sm table-bordered">
                <thead>
                  <tr>
                    <th colspan="3" class="success" style="text-align:center">
                      Available
                      สิทธิ์ <span id='allocateQuotaDisplay' name='allocateQuotaDisplay'><?php echo $allocateQuota;?></span>
                      เลือกแล้ว <span id='allocateUsedDisplay' name='allocateUsedDisplay'><?php echo $allocateUsed;?></span>
                      จัดสรรแล้ว <span id='allocateTotalDisplay' name='allocateTotalDisplay'></span>
                      รอจัดสรร <span id='allocateLeftDisplay' name='allocateLeftDisplay'></span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <table id="tbRejected" class="table table-striped table-sm table-bordered">
                <thead>
                  <tr>
                    <th colspan="3" class="danger" style="text-align:center">
                      Reject
                    </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              </div>
              <div class="row">
                <div class="col-sm-8">
                  <span id="confirmSummary"></span>
                </div>
                <div class="col-sm-4 text-right">
                  <button type="button" class="btn btn-success" data-dismiss="modal" id="btSave">
                    <i class="fa fa-check"></i> ยืนยัน</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times"></i> ยกเลิก</button>
                </div>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
    <!--end Modal-->

    <?php include_once("notification.php");?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script src="lib/js/inputFilter.js"></script>
    <script src="lib/select2-4.0.5/js/select2.min.js"></script>
    <script src="lib/select2-4.0.5/js/i18n/th.js"></script>
    <script src="lib/bootbox-5.1.3/bootbox.js"></script>
    <script src="assets/js/formatSelect2Emp.js"></script>
    <script src="lib/gallery/hes-gallery-master/hes-gallery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="lib/tabulator-4.2.3/js/tabulator.min.js"></script>
    <script type="text/javascript">
      //Gen Table Data
      var placeholder = "<span>Loading....</span>";
      var tabledata=[
        <?php
        $sql="select * from courseAllocate";
        $sql.=" where courseid=".$courseID;
        $sql.=" order by allocateid";

        $result=json_decode(pgQuery($sql),true);
        $Assigned=0;
        $Assigner=0;
        if($result['code']=="200") {
          for($i=0;$i<count($result)-1;$i++) {
            echo "{";
            echo "allocateid:\"".$result[$i]['allocateid']."\",";
            echo "employeeno:\"".$result[$i]['employeeno']."\",";
            echo "thai_name:\"".$result[$i]['thai_name']."\",";
            echo "position:\"".$result[$i]['position']."\",";
            echo "allocatequota:\"".$result[$i]['allocatequota']."\",";
            echo "allocateassign:\"".$result[$i]['allocateassign']."\",";
            echo "allocateleft:\"".$result[$i]['allocateleft']."\",";
            echo "allocateused:\"".$result[$i]['allocateused']."\",";
            echo "level:\"0\",";
            echo "_children:";
            echo "[";
            echo '{employeeno:"RO0321",thai_name:"พีระ วงศราวิทย์",position:"ผู้จัดการ",level:"1"}';
            echo "]";
            echo "},";
            //Except self
            if($result[$i]['employeeno']!=$_SESSION["employee_id"]) {
              $Assigned=$Assigned+$result[$i]['allocateleft'];
              $Assigner++;
            }
          }
        }
        ?>
      ];
      var tabledatax=<?php echo $tabledata?>;
      var table = new Tabulator('#divTable',{
        placeholder:placeholder,
        data:tabledata,
        dataTree:true,
        columns:[
         {title:"",width:60,field:"employeeno",formatter:function(cell, formatterParams, onRendered){
              var employeeno=cell.getValue();
              var row = cell.getRow();
              var data = row.getData();
              var imgsrc="https://intranet.jasmine.com/hr/office/Data/"+data.employeeno+".jpg";
              var ahref="<div class='hes-gallery' data-wrap='true'>";
              var imgTag=`<img class='media-object img-thumbnail user-img'
                data-subtext='`+data.thai_name+`'
                data-alt='`+data.thai_name+`'
                src='`+imgsrc+`'
                style='min-height:60px;height:60px;text-align:center;'>`;
              return ahref+imgTag+'</a></div>';
            }
            ,headerSort:false,align:"center",
         },
         {title:"รหัสพนักงาน", field:"employeeno",width:100},
         {title:"ชื่อ-นามสกุล/ตำแหน่ง",field:"thai_name",width:150,formatter:function(cell, formatterParams, onRendered){
             var row = cell.getRow();
             var data = row.getData();
             return data.thai_name+'<br/>'+data.position;
           },headerSort:false
         },
         {title:"สิทธิ์", field:"allocatequota",width:60},
         {title:"จัดสรร", field:"allocateassign",width:70},
         {title:"รอจัด", field:"allocateleft",width:70},
         {title:"เลือกผู้เรียน", field:"allocateused",width:100},
         {title:"",field:"allocateid",width:50,formatter:function(cell, formatterParams, onRendered){
             var row = cell.getRow();
             var data = row.getData();
             if(data.level==0) {
               var deleteLink="<a href='javascript:assignDelete("+data.allocateid+")'><i class='fas fa-fw fa-user-minus'></i> ลบ</a>";
               return deleteLink;
             }
           },headerSort:false
         },
        ],
        rowFormatter:function(row){
          switch (row.getData().level) {
            case '1':
              row.getElement().style.backgroundColor = "#A6A6DF";
              break;
            default:
          }
        },
        dataTreeRowExpanded:function(row, level){
         row.getElement().style.backgroundColor = "#FF0000";
        },
        dataTreeRowCollapsed:function(row, level){
          row.getElement().style.backgroundColor = "#FFFFFF";
        },
      }); //end tabulator
    </script>
  </body>
</html>
