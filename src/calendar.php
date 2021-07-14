<?php
include_once("lib/myOAuth.php");
include_once("lib/chkLogin.php"); //If need login enable here
//restrict("isadmin","index"); //If need permission enable here
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>TIS : Calendar</title>
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
	<link rel="stylesheet" href="lib/fullcalendar-4.0.1/core/main.css">
	<link rel="stylesheet" href="lib/fullcalendar-4.0.1/daygrid/main.css">
	<link rel="stylesheet" href="lib/fullcalendar-4.0.1/timegrid/main.css">
	<link rel="stylesheet" href="lib/fullcalendar-4.0.1/list/main.css">
  </head>
  <body>
    <div id="wrap">
			<?php $menu="calendar"?>
      <?php $submenu=""?>
			<?php include_once("top.php");?>
      <?php include_once("left.php");?>
      <div id="content">
        <div class="outer">
          <div class="inner">
            <div class="box">
              <header>
                <h5>กำหนดการอบรม</h5>
              </header>
              <div class="body">
								<div id='script-warning'></div>
								<div id='loading'></div>
	              <div class="row">
	                  <div id="calendar" class="col-lg-12"></div>
	              </div>
								<div class="row" style="margin-top:10px;margin-left:5px;">
	                <div class="col-sm-1" style="background-color:#00AAFF">&nbsp;</div>
									<div class="col-sm-11">เตรียมหลักสูตร/ รออนุมัติ</div>
									<div class="col-sm-1" style="background-color:#0000FF">&nbsp;</div>
									<div class="col-sm-11">อนุมัติ</div>
									<div class="col-sm-1" style="background-color:#FF5555">&nbsp;</div>
									<div class="col-sm-11">วันสำคัญของไทย</div>
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
		<script src="lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
		<!-- <script src='lib/Moment 2.24.0/moment.min.js'></script> -->
	  <script src="lib/fullcalendar-4.0.1/core/main.js"></script>
		<script src="lib/fullcalendar-4.0.1/core/locales-all.js"></script>
		<script src="lib/fullcalendar-4.0.1/interaction/main.js"></script>
		<script src="lib/fullcalendar-4.0.1/daygrid/main.js"></script>
		<script src="lib/fullcalendar-4.0.1/timegrid/main.js"></script>
		<script src="lib/fullcalendar-4.0.1/list/main.js"></script>
		<script src="lib/fullcalendar-4.0.1/google-calendar/main.js"></script>
	  <script src="assets/lib/screenfull/screenfull.js"></script>
	  <script src="assets/js/main.min.js"></script>
		<script>
		function addHoliday(year) {
			console.log('add holiday '+year);
			var apiSource = {
		    holiday: { //get Holiday
					id:'holiday',
					url: 'api/getHoliday.php?year='+year,
					color: '#FF5555',
					textColor: 'black',
          success: function(result) {
            //console.log('result');
					},
					failure: function() {
						$('#script-warning').text('Error get API:Holiday');
						$('#script-warning').show();
					}
				}
			};
			return apiSource.holiday;
		}

		document.addEventListener('DOMContentLoaded', function() {
    var initView=1;
		var activeMonth=-1;
		var activeYear=-1;
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			plugins: [ 'interaction', 'dayGrid', 'list' ],
			height: 450,
			header: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,listMonth'
			},
			locale: 'th',
			showNonCurrentDates:false,
			eventLimit: true, // allow "more" link when too many events
			viewSkeletonRender: function(info) {
				//console.log('skeleton');
        //console.log(info.view.type);
				//Init begin
				var currentStart=info.view.currentStart;
				activeMonth=currentStart.getMonth()+1; //Start 0
				activeYear=currentStart.getFullYear();
				//Initial Holiday
        if(initView==1) {
          calendar.addEventSource(addHoliday(activeYear));
          initView=0;
        }

			},
			datesRender: function(info) {
				//console.log('datesRender');
				var currentStart=info.view.currentStart;
				var currentMonth=currentStart.getMonth()+1; //Start 0
				var currentYear=currentStart.getFullYear();
				if(currentYear!=activeYear) { //Detect year change
					//console.log('year change');
					//Remove old
					calendar.getEventSourceById('holiday').remove();
					//Add new
					calendar.addEventSource(addHoliday(currentYear));
					activeYear=currentYear;
				}
			},
			eventRender: function(info) {
				//alert(info.el);
				//console.log('eventRender');
				//console.log(info.event.title);
				var element=info.el;
				element.title=info.event.extendedProps.description;
				element.setAttribute("data-toggle", "tooltip");
				//console.log(element.innerHTML);
				//var currentStart=info.view.currentStart;
		  },
			eventSources: [
				{ //get Course Prepare
				  url: 'api/getCourseDay.php',
          method: 'POST',
          extraParams: {
            statusID: '0'
          },
				  // type: 'POST',
				  // data: {
					// statusID: '0',
				  // },
				  color: '#00AAFF',
				  textColor: 'white',
          html: true,
				  failure: function() {
				    $('#script-warning').text('Error get API: Course prepare');
					  $('#script-warning').show();
				  }
				},
        { //get Course Open
				  url: 'api/getCourseDay.php',
          method: 'POST',
          extraParams: {
            statusID: '40'
          },
				  color: '#0000FF',
				  textColor: 'white',
          html: true,
				  failure: function() {
				    $('#script-warning').text('Error get API: Course open');
					  $('#script-warning').show();
				  }
				},
			]
		});

		calendar.render();
		});

		//Reserve old style
     //$(document).ready(function () {
		 //});
		</script>
  </body>
</html>
