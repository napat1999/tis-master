<?php
include_once("lib/myOAuth.php");
//include_once("lib/chkLogin.php"); //If need login enable here
//restrict("isadmin","index"); //If need permission enable here
function isOnline($host,$port) {
  $ip = gethostbyname($host);
  $waitTimeoutInSeconds = 2;
  if($fp = @fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){
     return "<div class='alert alert-success'>".$ip." Online</div>";
  } else {
     return "<div class='alert alert-danger'>".$ip." Offline</div>";
  }
  fclose($fp);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : test Connection</title>
	<?php include_once("basicHeader.php");?>
	<style>
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
			<?php $menu="test" ?>
			<?php $submenu="testConnection.php" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>
                <h5>Online Status</h5>
              </header>
              <div class="body">
                <div class="row">
                    <div class="lg-12">
											<div class="md-8" style="margin-left:10px;">
												<table class="table table-striped table-bordered" style="width:500px;">
												<thead>
													<tr>
														<th>Server</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Database</td>
														<td>
															<?php
                              $host="10.2.0.39";
                              $port="5432";
                              echo isOnline($host,$port);
                              ?>
														</td>
													</tr>
                          <tr>
														<td>app.jasmine.com</td>
														<td>
                              HTTP
															<?php
                              $host="app.jasmine.com";
                              $port="80";
                              echo isOnline($host,$port);
                              ?>
                              HTTPS
                              <?php
                              $host="app.jasmine.com";
                              $port="443";
                              echo isOnline($host,$port);
                              ?>
														</td>
													</tr>
                          <tr>
														<td>api.jasmine.com</td>
														<td>
                              HTTP
															<?php
                              $host="api.jasmine.com";
                              $port="80";
                              echo isOnline($host,$port);
                              ?>
                              HTTPS
                              <?php
                              $host="api.jasmine.com";
                              $port="443";
                              echo isOnline($host,$port);
                              ?>
														</td>
													</tr>
                          <tr>
														<td>intranet.jasmine.com<br/>
                              <img src="https://intranet.jasmine.com/hr/office/Data/RO1637.jpg">
                            </td>
														<td>
                              HTTP
															<?php
                              $host="intranet.jasmine.com";
                              $port="80";
                              echo isOnline($host,$port);
                              ?>
                              HTTPS
                              <?php
                              $host="intranet.jasmine.com";
                              $port="443";
                              echo isOnline($host,$port);
                              ?>
														</td>
													</tr>
                          <tr>
														<td>smtp.jasmine.com</td>
														<td>
															<?php
                              $host="10.2.0.2";
                              $port="25";
                              echo isOnline($host,$port);
                              ?>
														</td>
													</tr>
                          <tr>
														<td>Redis</td>
														<td>
															<?php
                              $host="tis-redis";
                              $port="6379";
                              echo isOnline($host,$port);
                              ?>
														</td>
													</tr>
												</tbody>
												</table>
                    	</div>
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
