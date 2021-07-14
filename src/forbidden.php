<?php
include_once("lib/myOAuth.php");
$permission = isset($_GET["permission"])?$_GET["permission"]:'';
$code = isset($_GET["code"])?$_GET["code"]:'';
$permissionText="";
switch ($permission) {
	case 'isadmin': $permissionText="Admin"; break;
	case 'istraining': $permissionText="Training"; break;
	case 'istraininghq': $permissionText="Training HQ"; break;
	case 'istrainingro': $permissionText="Training RO"; break;
	case 'iscoordinator': $permissionText="Coordinator"; break;
	case 'allocatable': $permissionText="จัดสรร"; break;
	case 'invitable': $permissionText="การเลือก"; break;
	default:
		break;
}
switch ($code) {
	case 'users':
		$label="ข้อมูลผู้ใช้ระบบ";
		break;
	case 'trainingsite':
		$label="ข้อมูลสถานที่อบรม";
		break;
	case 'trainerInternal':
		$label="ข้อมูลวิทยากรภายใน";
		break;
	case 'trainerExternal':
		$label="ข้อมูลวิทยากรภายนอก";
		break;
	case 'courseCode':
		$label="ข้อมูล Code หมวดหมู่หลักสูตร";
		break;
	case 'courseTag':
		$label="ข้อมูล Tag ค้นหาหลักสูตร";
		break;
	case 'courseMaster':
		$label="ข้อมูลหลักสูตรต้นแบบ";
		break;
	case 'courseGeneral':
		$label="ข้อมูลหลักสูตรทั่วไป";
		break;
	case 'courseAllocate':
		$label="การจัดสรรผู้เข้ารับการอบรม";
		break;
	case 'courseInvite':
		$label="การเลือกผู้เข้ารับการอบรม";
		break;
	default:
		break;
}
$desc = "ต้องการสิทธิ์ ".$permissionText." ในการเข้าถึง ".$label."<br/>";
$desc.= "กรุณาติดต่อผู้ดูแลระบบ";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Forbidden</title>
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
