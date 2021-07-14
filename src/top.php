      <div id="top">

        <!-- .navbar -->
        <nav class="navbar navbar-inverse navbar-static-top">

          <!-- Brand and toggle get grouped for better mobile display -->
          <header class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a href="index.php" class="navbar-brand">
              <img src="assets/img/TISLogo.jpg" alt="" style="width:50px;">
            </a>
          </header>
          <div class="topnav">
            <div class="btn-toolbar">
              <div class="btn-group">
                <a data-placement="bottom" data-original-title="Fullscreen" data-toggle="tooltip" class="btn btn-default btn-sm" id="toggleFullScreen">
                  <i class="glyphicon glyphicon-fullscreen"></i>
                </a>
              </div>
              <div class="btn-group">
                <a data-placement="bottom" data-original-title="Show / Hide Sidebar" data-toggle="tooltip" class="btn btn-success btn-sm" id="changeSidebarPos">
                  <i class="fas fa-expand"></i>
                </a>
              </div>
              <div class="btn-group">
                <a data-toggle="modal" data-original-title="Alert" data-placement="bottom" class="btn btn-default btn-sm" href="#notificationModal">
                  <i class="fas fa-bell"></i>
                  <span class="label label-warning">4</span>
                </a>
              </div>
              <div class="btn-group">
				<?php
				if ($_SESSION["employee_id"]=="") {
				?>
					<a href="login.php" data-toggle="tooltip" data-original-title="Login" data-placement="bottom" class="btn btn-metis-1 btn-sm">
					  <i class="fas fa-sign-in-alt"></i>
					</a>
				<?php
				} else {
				?>
					<a href="logout.php" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom" class="btn btn-metis-1 btn-sm">
					  <i class="fas fa-power-off"></i>
					</a>
				<?php
				}
				?>
              </div>
            </div>
          </div><!-- /.topnav -->
          <div class="collapse navbar-collapse navbar-ex1-collapse">

            <!-- .nav -->
            <ul class="nav navbar-nav">
              <li class='<?php if($menu=="calendar") { echo "active";}?>'>
                <a href="calendar.php">กำหนดการอบรม</a>
              </li>
              <li class='<?php if($menu=="history") { echo "active";}?>'>
                <a href="personalCourse.php">การฝึกอบรม</a>
              </li>
              <li class='<?php if($menu=="form") { echo "active";}?>'>
                <a href="form.php">แบบฟอร์ม</a>
              </li>
              <!-- <li class='dropdown '>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                รายงาน
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li> <a href="report1.html">Report 1</a>  </li>
                  <li> <a href="report2.html">Report 2</a>  </li>
                  <li> <a href="report3.html">Report 3</a>  </li>
                  <li> <a href="report4.html">Report 4</a>  </li>
                </ul>
              </li> -->
            </ul><!-- /.nav -->
          </div>
        </nav><!-- /.navbar -->

      </div><!-- /#top -->
