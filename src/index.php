<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("isadmin","index"); //If need permission enable here
include_once("lib/myFeedLib.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Training Information System</title>
	<?php include_once("basicHeader.php");?>
  </head>
  <body>
    <div id="wrap">
	  <?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="col-lg-12">
              <h2>TIS : Training Information System
              <img src="assets/img/TISLogo.jpg" style="width:50px;">
			        </h2>
              <h4>Change log (10 รายการล่าสุด)</h4>
              <p>
								<?php
								$url="https://gitlab.jasmine.com/3bbhrd/tis/commits/master?feed_token=77h1nqjAtRv9SFHG9XaQ&format=atom";
								getFeed($url,10);
								?>
			  			</p>
							<!-- <h4>Old Work Flow</h4>
							<p>
								<img src="assets/img/TISFlow.png" style="width:80%;">
						  </p> -->
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
<!--Original Demo: https://colorlib.com/polygon/metis/-->
