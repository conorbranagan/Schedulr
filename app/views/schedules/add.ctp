<?php echo $this->Html->script('add_class')?>

<div id = "loader"></div>
<div id = "all_content">
	<div id = "welcomeWindow" style = "display:none">
		<h3>Welcome To Schedulr!</h3>
		<p>Schedulr is the easiest way to make your school schedule.</p>
		<p>Schedulr lets you add as many classes as you want then simplifies the process of finding conflicts by allowing you to click the class name
			to add or remove it from the current schedule.</p>
		<p>Our speedy conflict detector will automatically show if non-enabled classes in your list conflict with something in the schedule!</p>
		<p>To get started, click "Add A Class To Your Schedulr"</p>
	</div>
	<div id = "setTitleWindow" style = "display:none">
		<h3>Give Your Schedulr A Title</h3>
		<p><input id = "scheduleTitle" type = "text" maxlength = "30" /></p>
		<p><input type = "button" id = "saveScheduleFinal" value = "Save"></p>
	</div>
	<div id = "addClassWindow" style = "display:none">
		<h3>Add A Class To Your Schedulr</h3>
		<div id = "addClassLeft">
			<p>
				<label for = "CourseName">Short Course Name</label>
				<input type = "text" name = "CourseName" id = "CourseName" />
				<span class = "error_msg"></span>
			</p>
			<p><?php echo $this->Form->input('Course.start_time', array('label' => 'Start Time',
																		'div' => false
																		)) ?>
				<span class = "error_msg"></span>													
			</p>
		</div>
		<div id = "addClassRight">
			<p><?php echo $this->Form->input('Course.credits', array('label' => 'Credits',
			 															'options' => $credit_options, 
																		'selected' => 3,
																		'div' => false
																	)) ?></p>
			<p><?php echo $this->Form->input('Course.end_time', array('label' => 'End Time',
																		'div' => false
																	)) ?>
				<span class = "error_msg"></span>													
			</p>
		</div>

		<div class = "clear"></div>
		<ul class = "day_buttons">
			<li id = "monday"><p>Monday</p></li>
			<li id = "tuesday"><p>Tuesday</p></li>
			<li id = "wednesday"><p>Wednesday</p></li>
			<li id = "thursday"><p>Thursday</p></li>
			<li id = "friday"><p>Friday</p></li>
			<li id = "saturday"><p>Saturday</p></li>
			<li id = "sunday"><p>Sunday</p></li>
		</ul>
		<div class = "clear"></div>

		<p><input type = "button" id = "addClass" value = "Add Class" /></p>
	</div>

	<h1 id = "title">Schedulr</h1>

	<p id = "cred">
		<a href = "#setTitleWindow" rel = "facebox" id = "saveSchedule">Save Schedule</a>
	    <span id = "credit_info">Currently taking <span id = "total_credits">0</span> Credits </span> 
	</p>

	<div id = "class_listing">
		<h1>Class List</h1>
		<p><a href = "#addClassWindow" rel = "facebox">Add A Class To You Schedule</a></p>	
		<p id = "enable_info"><strong>Click a class in this list to enable/disable it.</strong></p>
	</div>
<div id = "schedule">
	<div id = "row_lines">
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
		<div class = "row_line_1"></div>
	</div>

	<div id = "day_list">
		<div class = "day first">&nbsp;</div>
		<div class = "day">Monday</div>
		<div class = "day">Tuesday</div>
		<div class = "day">Wednesday</div>
		<div class = "day">Thursday</div>
		<div class = "day">Friday</div>
		<div class = "day">Saturday</div>
		<div class = "day">Sunday</div>
	</div>
	<div class = "clear"></div>
	<div id = "time_list">
		<div class = "time_box first_time"><p>7:00am</p></div>
		<div class = "time_box"><p>8:00am</p></div>
		<div class = "time_box"><p>9:00am</p></div>
		<div class = "time_box"><p>10:00am</p></div>
		<div class = "time_box"><p>11:00am</p></div>
		<div class = "time_box"><p>12:00pm</p></div>
		<div class = "time_box"><p>1:00pm</p></div>
		<div class = "time_box"><p>2:00pm</p></div>
		<div class = "time_box"><p>3:00pm</p></div>
		<div class = "time_box"><p>4:00pm</p></div>
		<div class = "time_box"><p>5:00pm</p></div>
		<div class = "time_box"><p>6:00pm</p></div>
		<div class = "time_box"><p>7:00pm</p></div>
		<div class = "time_box"><p>8:00pm</p></div>
		<div class = "time_box"><p>9:00pm</p></div>		
		<div class = "time_box"><p>10:00pm</p></div>
		<div class = "time_box last_time"><p>11:00pm</p></div>
	</div>
</div>	
<div id = "classes">
</div>
</div>


<script>
var MINUTE_HEIGHT = (45 / 60);
var TIME_DELTA = 7 * 60;
var TOP_DELTA = 126;

var course_id = 1, div, totalCredits = 0;
var disp_classes, all_classes;

$(document).ready(function() {
	/*
		Initialization
	*/
	disp_classes = Array();
	all_classes = Array();
	$('a[rel*=facebox]').facebox();	
	$('a[rel*=facebox]').click(function() {
		resetCourseAddForm();
	});
	jQuery.facebox({ div: '#welcomeWindow' });
	
<?php
		if($is_fork) {
			$TIME_DELTA = 7 * 60;
			$TOP_DELTA = 126;
			$MINUTE_HEIGHT = (45 / 60);
			// We want to initialize all the courses just as if they were added
			// through the main javascript calls (I think...)
			$color_index = 0;
			$course_id = 1;
			foreach($courses as $course) {
				$course = $course['Course'];
				// Find the number of minutes in the start/end time
				$start_hour = date('g', strtotime($course['start_time']));
				$start_minute = date('i', strtotime($course['start_time']));
				$start_meridian = date('a', strtotime($course['start_time']));
				
				if($start_meridian == 'am' && $start_hour == 12) {
					$start_hour = 0;
				} else if($start_meridian == 'pm' && $start_hour == 12) {
					$start_hour = 12;
				} else if($start_meridian == 'pm') {
					$start_hour += 12;
				}		
				$start_minutes = $start_hour * 60 + $start_minute - $TIME_DELTA;
		
				// Do the same for the end time
				$end_hour = date('g', strtotime($course['end_time']));
				$end_minute = date('i', strtotime($course['end_time']));
				$end_meridian = date('a', strtotime($course['end_time']));
				
				if($end_meridian == 'am' && $end_hour == 12) {
					$end_hour = 0;
				} else if($end_meridian == 'pm' && $end_hour == 12) {
					$end_hour = 12;
				} else if($end_meridian == 'pm') {
					$end_hour += 12;
				}		
				$end_minutes = $end_hour * 60 + $end_minute - $TIME_DELTA;	
				$height = ($end_minutes - $start_minutes) * $MINUTE_HEIGHT + 1;
				// Calculate top
				$top = $TOP_DELTA + ($start_minutes * $MINUTE_HEIGHT) + ($start_hour - 8);
				$course_name = $course['name'];
				$course_credits = $course['credits'];
				$start_time = date("g:ia", strtotime($course['start_time']));
				$end_time = date("g:ia", strtotime($course['end_time']));		
				$day_string = $course['day_string'];		
			?>
				c1 = new Course(<?php echo $height ?>, <?php echo $top ?>, '<?php echo $day_string ?>', '<?php echo $course_name ?>',
									<?php echo $course_credits ?>, '<?php echo $start_time ?>', '<?php echo $end_time ?>', color_array[<?php echo $color_index ?>]);
					
			<?php
				for($i = 0; $i < strlen($course['day_string']); $i++) {
					switch(substr($course['day_string'], $i, 1)) {
						case 'M': $day = 'monday'; break;
						case 'T': $day = 'tuesday'; break;
						case 'W': $day = 'wednesday'; break;
						case 'R': $day = 'thursday'; break;
						case 'F': $day = 'friday'; break;
						case 'S': $day = 'saturday'; break;
						case 'U': $day = 'sunday'; break;
						default: $day = 'monday';
					}
				 ?>
				c1.addDay('<?php echo $day ?>');
				<?php
				}
				?>
				c1.mySelector = $('.course_' + <?php echo $course_id ?>);
				c1.enable();
				all_classes.push(c1);		
		<?php 
		$course_id++;
		$color_index++;
		?>
		course_id = <?php echo $course_id ?>;
		<?php } ?>
			course_id = <?php echo $course_id ?>;
			color_index = <?php echo $color_index ?>;
	<?php }
	?>	
});

</script>



