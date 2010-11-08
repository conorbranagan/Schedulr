<style>
.class.monday {
	left:111px;
}

.class.tuesday {
	left:213px;
}

.class.wednesday {
	left:315px;
}

.class.thursday {
	left:417px;
}

.class.friday {
	left:519px;
}

.class.saturday {
	left:621px;
}

.class.sunday {
	left:723px;
}
</style>
<h1 id = "schedule_title"><?php echo $title ?> - <?php echo $total_credits ?> Credits @ Schedulr</h1>


<ul id = "social">
	<li><a name="fb_share">Share On Facebook</a> 
		<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
		        type="text/javascript">
		</script>
	</li>
	<li><a href = "/fork/<?php echo $code ?>/">Fork This Schedulr</a></li>
	<li><a href = "/">Create Your Own Schedulr</a></li>
</ul>
<div class = "clear"></div>

<div id = "link">
	<p>To access your Schedulr later, bookmark this page or copy the following link: <input type = "text" value = "<?php echo $current_url ?>"></p>
</div>	


<div id = "schedule" class = "saved">
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
	<?php foreach($class_divs as $div) { echo $div . "\n"; } ?>
</div>