<?php
include_once("lib/myOAuth.php");
//include_once("lib/chkLogin.php"); //If need login enable here
//restrict("istraining","trainingsite"); //If need permission enable here
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
				<div id='script-warning'>
				</div>
				<div id='loading'></div>
                <div class="row">
                    <h3>รายชื่อผู้รับการฝึกอบรม (แบบ ฝยฝป.3)</h3>
                    <ul>
												<li><a href="#" target="_blank" id="form1-1-blank">หลักสูตร 1 วัน-0 คน</a></li>
												<li><a href="#" target="_blank" id="form1-1-15">หลักสูตร 1 วัน-15 คน</a></li>
												<li><a href="#" target="_blank" id="form1-1-20">หลักสูตร 1 วัน-20 คน</a></li>
												<li><a href="#" target="_blank" id="form1-1-23">หลักสูตร 1 วัน-23 คน</a></li>
												<li><a href="#" target="_blank" id="form1-1-30">หลักสูตร 1 วัน-30 คน</a></li>
                    </ul>
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

		<form method="post" target="_blank" id="frmGenPDF" style="display:none" action="genPDF.php" >
			<input type="hidden" name="url" />
			<input type="hidden" name="fname" />
		</form>

		<form method="post" target="_blank" id="frmOpenPDF" style="display:none">
			<input type="hidden" name="courseID" />
		</form>

    <?php include_once("notification.php");?>
    <script src="assets/lib/jquery.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="assets/lib/screenfull/screenfull.js"></script>
    <script src="assets/js/main.min.js"></script>

		<script type="text/javascript">
		$(document).ready(function() {
			$("#form1-1-blank").click(function(){
				var url='formName1PDF.php';
				$('input[name=courseID]').val('');
				$('#frmOpenPDF').attr('action',url).submit();
			});
			$("#form1-1-15").click(function(){
				var url='formName1PDF.php';
				$('input[name=courseID]').val(1);
				$('#frmOpenPDF').attr('action',url).submit();
			});
			$("#form1-1-20").click(function(){
				var url='formName1PDF.php';
				$('input[name=courseID]').val(2);
				$('#frmOpenPDF').attr('action',url).submit();
			});
			$("#form1-1-23").click(function(){
				var url='formName1PDF.php';
				$('input[name=courseID]').val(3);
				$('#frmOpenPDF').attr('action',url).submit();
			});
			$("#form1-1-30").click(function(){
				var url='formName1PDF.php';
				$('input[name=courseID]').val(4);
				$('#frmOpenPDF').attr('action',url).submit();
			});
			/*
			$("#formReserve").click(function(){
				var url='http://<?php echo $_SERVER['SERVER_NAME'];?>/formName1.php?courseID=4';
				$('input[name=url]').val(url);
				$('input[name=fname]').val('formName1.pdf');
				$("#frmGenPDF").submit();
			});
			*/
		});
	</script>
  </body>
</html>
