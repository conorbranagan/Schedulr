<?php
/* Course Test cases generated on: 2010-10-25 06:10:16 : 1288002436*/
App::import('Model', 'Course');

class CourseTestCase extends CakeTestCase {
	var $fixtures = array('app.course', 'app.day', 'app.courses_day', 'app.schedule', 'app.schedules_course');

	function startTest() {
		$this->Course =& ClassRegistry::init('Course');
	}

	function endTest() {
		unset($this->Course);
		ClassRegistry::flush();
	}

}
?>