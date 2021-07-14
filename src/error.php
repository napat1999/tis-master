<?php
include_once("lib/myOAuth.php");
$code = isset($_GET["code"])?$_GET["code"]:'';
switch ($code) {
	case 'course':
		$label="หลักสูตร";
		$returnurl="course.php";
		break;
	case 'courseMaster':
		$label="หลักสูตรต้นแบบ";
		$returnurl="courseMaster.php";
		break;
	case 'trainerinternal':
		$label="ข้อมูลวิทยากร";
		$returnurl="trainerInternal.php";
		break;
	case 'trainerexternal':
		$label="ข้อมูลวิทยากร";
		$returnurl="trainerExternal.php";
		break;
	case 'courseAllocate':
		$label="หลักสูตร";
		$returnurl="courseGeneral.php";
		break;
	case 'courseInvite':
		$label="หลักสูตร";
		$returnurl="courseGeneral.php";
		break;
	case 'trainingSite':
		$label="สถานที่จัดอบรม";
		$returnurl="trainingSite.php";
		break;
	case 'userInfo':
		$label="ข้อมูลผู้ใช้งานระบบ";
		$returnurl="users.php";
		break;
	case 'courseStudent':
		$label="หลักสูตร";
		$returnurl="courseGeneral.php";
		break;
	case 'personalAccepted':
		$label="หลักสูตร";
		$returnurl="personalCourse.php";
		break;
	default:
		break;
}
$desc = "มีการเข้าถึง".$label."ผิดพลาด ".$label."อาจถูกลบไปแล้ว<br/>";
$desc.= "<a href='".$returnurl."'>กลับสู่หน้า".$label."</a>";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Error</title>
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
	</style>
  </head>
  <body>
    <div id="wrap">
			<?php $menu="" ?>
			<?php $submenu="" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box danger">
              <header>
                <h5>กรุณาลองใหม่ หรือติดต่อ admin</h5>
              </header>
              <div class="body">
							<div id='script-warning'>
							</div>
							<div id='loading'></div>
              <div class="md-6">
                  <div class="alert alert-danger" align="center">
										<?php echo $desc;?>
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
  </body>
</html>
