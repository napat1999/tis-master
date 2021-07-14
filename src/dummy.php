<?php
include_once("lib/myOAuth.php");
//include_once("lib/chkLogin.php"); //If need login enable here
//restrict("isadmin","index"); //If need permission enable here
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Form</title>
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
  </head>
  <body>
    <div id="wrap">
			<?php $menu="form" ?>
			<?php $submenu="" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>
                <h5>แบบฟอร์มต่างๆ</h5>
              </header>
              <div class="body">
								<div id='script-warning'></div>
                <div id='loading'>
                  <div class="loading-backdrop">
                  </div>
                  <div class="loading-img">
                      <img src="assets/img/Loading-tis.gif" width="400px"/>
                  </div>
                </div>
                <div class="row">
                    <div class="lg-12">
                        1234
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
