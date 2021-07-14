<?php
include_once("lib/myOAuth.php");
//include_once("lib/chkLogin.php"); //If need login enable here
//restrict("isadmin","index"); //If need permission enable here
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Credits</title>
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
			<?php $submenu="about.php" ?>
	  	<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>
                <h5>Credits<i class="far fa-fw fa-registered"></i></h5>
              </header>
              <div class="body">
                <div class="row">
                    <div class="lg-12">
											<div class="md-8" style="margin-left:10px;">
												<table class="table table-striped table-bordered" style="width:600px;">
												<thead>
													<tr>
														<th>Type</th>
														<th>Brand</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Theme</td>
														<td>
															<a href="https://github.com/puikinsh/Bootstrap-Admin-Template" target="_blank">
																Metis Admin 2.1.4
															</a>
														</td>
													</tr>
													<tr>
														<td>CSS</td>
														<td>
															<a href="http://bootstrapdocs.com/v3.1.1/docs/getting-started/" target="_blank">
																Bootstrap 3.1.1
															</a>
														</td>
													</tr>
													<tr>
														<td>Font</td>
														<td>
															<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">
																Font Aewsome 5.6.3
															</a><br/>
															<a href="https://fonts.google.com/specimen/Kanit?selection.family=Kanit:300,400,500" target="_blank">
																Google font Kanit
															</a>
														</td>
													</tr>
													<tr>
														<td>Calendar</td>
														<td>
															<a href="https://fullcalendar.io/" target="_blank">
																Full Calendar 4.0.1
															</a>
														</td>
													</tr>
													<tr>
														<td>Table</td>
														<td>
															<a href="https://datatables.net/" target="_blank">
																Datatables 1.10.18
															</a><br/>
															<a href="http://tabulator.info/" target="_blank">
																Tabulator 4.2.3
															</a>
														</td>
													</tr>
													<tr>
														<td>Html Editor</td>
														<td>
															<a href="https://summernote.org/" target="_blank">
																Summer Note 0.8.11
															</a>
														</td>
													</tr>
													<tr>
														<td>Date Picker</td>
														<td>
															<a href="https://longbill.github.io/jquery-date-range-picker/" target="_blank">
																jQuery Date Range Picker 0.20
															</a><br/>
															<a href="https://github.com/uxsolutions/bootstrap-datepicker" target="_blank">
																uxsolutions bootstrap-datepicker 1.8.0
															</a>
														</td>
													</tr>
                          <tr>
														<td>Time Picker</td>
														<td>
                              <a href="http://jonthornton.github.io/jquery-timepicker/" target="_blank">
																Jonthornton jquery.timepicker 1.11.14
															</a>
														</td>
													</tr>
                          <tr>
														<td>Date pair</td>
														<td>
                              <a href="https://jonthornton.github.io/Datepair.js/" target="_blank">
																Jonthornton datepair 0.4.16
															</a><br/>
														</td>
													</tr>
													<tr>
														<td>File Upload</td>
														<td>
															<a href="http://plugins.krajee.com/file-input" target="_blank">
																Bootstrap File Input 4.5.2
															</a>
														</td>
													</tr>
													<tr>
														<td>Mail</td>
														<td>
															<a href="https://github.com/PHPMailer/PHPMailer" target="_blank">
																PHP Mailer 6.0.6
															</a>
														</td>
													</tr>
													<tr>
														<td>Select</td>
														<td>
															<a href="https://select2.org/" target="_blank">
																Select2 4.0.5
															</a>
														</td>
													</tr>
													<tr>
														<td>Time length</td>
														<td>
															<a href="https://github.com/grimmlink/TimingField" target="_blank">
																Timing Field
															</a>
														</td>
													</tr>
													<tr>
														<td>Lightbox</td>
														<td>
															<a href="https://github.com/demtario/hes-gallery" target="_blank">
                                HesGallery
															</a>
														</td>
													</tr>
													<tr>
														<td>PDF</td>
														<td>
															<a href="https://tcpdf.org/" target="_blank">
																TCPDF 6.2.26
															</a>
														</td>
													</tr>
                          <tr>
														<td>Input filter</td>
														<td>
															<a href="https://stackoverflow.com/questions/995183/how-to-allow-only-numeric-0-9-in-html-inputbox-using-jquery" target="_blank">
																Stack Overflow
															</a><br/>
                              <a href="https://jsfiddle.net/emkey08/tvx5e7q3" target="_blank">
																JSFiddle
															</a><br/>
														</td>
													</tr>
                          <tr>
														<td>Bootstrap modals</td>
														<td>
															<a href="https://github.com/makeusabrew/bootbox" target="_blank">
																Bootbox 5.1.3
															</a>
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
