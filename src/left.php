    <div id="left">
    <?php
    //include_once("lib/myLib.php");
    include_once("lib/myIMGLib.php");
    $userlabel="";
    if ($_SESSION["isadmin"]=="1") {
      $user="Admin";
    } else {
      if ($_SESSION["istraininghq"]=="1" or $_SESSION["istrainingro"]=="1") {
        $user="Training";
      } else {
        if ($_SESSION["iscoordinator"]=="1") {
          $user="Coordinator";
        }
      }
    }
    $userlabel="<span class='label user-label' style='background-color:#4dc4ca;color:#1a1a1a'>".$user."</span>";

		if ($_SESSION["employee_id"]!="") {
		?>
        <div class="media user-media ">
          <span class="user-link">
            <img class="media-object img-thumbnail user-img" alt="User Picture" style="width:90px;" src="<?php echo intranetIMGCheck($_SESSION['employee_id'])?>">
            <?php echo $userlabel;?>
          </span>
          <div class="media-body">
		        <br/>
            <ul class="list-unstyled user-info">
              <li><?=$_SESSION["employee_id"]?></li>
              <li><?=isset($_SESSION["thai_name"])?></li>
              <li><?=$userlabel?></li>
            </ul>
          </div>
        </div>
		<?php
		}
		?>
      <!-- #menu -->
      <ul id="menu" class="collapse">
		  <li class='<?php if($menu=="calendar") { echo "active";}?>'>
			<a href="calendar.php">
			  <i class="fas fa-fw fa-calendar-alt"></i> กำหนดการอบรม</a>
		  </li>
		  <li class="nav-header">Personal</li>
		  <li class="nav-divider"></li>
		  <li class='<?php if($menu=="personalCourse") { echo "active";}?>'>
          <a href="personalCourse.php">
            <i class="fas fa-fw fa-history"></i> การฝึกอบรม
          </a>
      </li>
		  <li class="nav-header">Training Officer</li>
      <li class="nav-divider"></li>
      <li class='<?php if($menu=="course") { echo "active";}?>'>
          <a href="javascript:;">
            <i class="fas fa-fw fa-book"></i>
            <span class="link-title"> หลักสูตร</span>
            <span class="fas fa-angle-left"></span>
          </a>
          <ul>
            <?php
            if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1") {
            ?>
            <li class='<?php if($submenu=="courseMaster.php") { echo "active";}?>'>
              <a href="courseMaster.php">
                <i class="fa fa-fw fa-clipboard-list"></i> หลักสูตรต้นแบบ
              </a>
            </li>
            <?php
            }
            ?>
            <li class='<?php if($submenu=="courseGeneral.php") { echo "active";}?>'>
              <a href="courseGeneral.php">
                <i class="fa fa-fw fa-tasks"></i> หลักสูตรทั่วไป
              </a>
            </li>
          </ul>
      </li>
		  <li class='<?php if($menu=="trainer") { echo "active";}?>'>
          <a href="javascript:;">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span class="link-title"> วิทยากร</span>
            <span class="fas fa-angle-left"></span>
          </a>
          <ul>
            <li class='<?php if($submenu=="trainerInternal.php") { echo "active";}?>'>
              <a href="trainerInternal.php">
                <i class="fas fa-fw fa-address-card"></i> วิทยากรภายใน
              </a>
            </li>
            <li class='<?php if($submenu=="trainerExternal.php") { echo "active";}?>'>
            <a href="trainerExternal.php">
                <i class="fas fa-fw fa-address-book"></i> วิทยากรภายนอก
              </a>
            </li>
          </ul>
      </li>
      <li class='<?php if($menu=="trainingSite") { echo "active";}?>'>
        <a href="trainingSite.php">
			  <i class="fas fa-fw fa-hotel"></i> สถานที่จัดอบรม</a>
		  </li>
      <?php
      if ($_SESSION["isadmin"]=="1" or $_SESSION["istraininghq"]=="1") {
      ?>
      <li class="nav-header">Admin</li>
      <li class="nav-divider"></li>
      <li class='<?php if($menu=="setting") { echo "active";}?>'>
          <a href="javascript:;">
            <i class="fas fa-fw fa-cog"></i>
            <span class="link-title"> ตั้งค่า</span>
            <span class="fas fa-angle-left"></span>
          </a>
          <ul>
            <li class='<?php if($submenu=="courseCode.php") { echo "active";}?>'>
              <a href="courseCode.php">
                <i class="fas fa-fw fa-layer-group"></i> Code หลักสูตร
              </a>
            </li>
            <li class='<?php if($submenu=="courseTag.php") { echo "active";}?>'>
              <a href="courseTag.php">
                <i class="fas fa-fw fa-search"></i> Tag ค้นหาหลักสูตร
              </a>
            </li>
            <li class='<?php if($submenu=="users.php") { echo "active";}?>'>
              <a href="users.php">
                <i class="fas fa-fw fa-user-shield"></i> ผู้ใช้ระบบ
              </a>
            </li>
          </ul>
      </li>
      <?php
      }
      if ($_SESSION["isadmin"]=="1") {
      ?>
      <li class='<?php if($menu=="test") { echo "active";}?>'>
          <a href="javascript:;">
            <i class="fas fa-fw fa-vial"></i>
            <span class="link-title"> ทดสอบ</span>
            <span class="fas fa-angle-left"></span>
          </a>
          <ul>
            <li class='<?php if($submenu=="testConnection.php") { echo "active";}?>'>
              <a href="testConnection.php">
                <i class="fas fa-fw fa-link"></i> ทดสอบการเชื่อมต่อ
              </a>
            </li>
            <li class='<?php if($submenu=="about.php") { echo "active";}?>'>
              <a href="about.php">
                <i class="fas fa-fw fa-copyright"></i> เกี่ยวกับ
              </a>
            </li>
            <li class='<?php if($submenu=="readSession.php") { echo "active";}?>'>
              <a href="readSession.php">
                <i class="fas fa-fw fa-user-secret"></i> อ่านค่า Session
              </a>
            </li>
            <li class='<?php if($submenu=="writeEmpty.php") { echo "active";}?>'>
              <a href="writeEmpty.php">
                <i class="fas fa-fw fa-eraser"></i> เขียนค่าว่าง
              </a>
            </li>
            <li class='<?php if($submenu=="writeRO1637.php") { echo "active";}?>'>
              <a href="writeRO1637.php">
                <i class="fas fa-fw fa-user-shield"></i> เขียนค่า RO1637
              </a>
            </li>
            <li>
              <a href="apitest/getOuListTest.php" target="_blank">
                <i class="fas fa-fw fa-code-branch"></i> ทดสอบ api OU
              </a>
            </li>
            <li>
              <a href="apitest/getEmployeeExpTest.php" target="_blank">
                <i class="fas fa-fw fa-code-branch"></i> ทดสอบ api EXP
              </a>
            </li>
            <li>
              <a href="s3DocumentSample.php" target="_blank">
                <i class="fab fa-fw fa-aws"></i> S3 Test
              </a>
            </li>
          </ul>
      </li>
      <?php
      }
      ?>

      </ul><!-- /#menu -->

    </div><!-- /#left -->
