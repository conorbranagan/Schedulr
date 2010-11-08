<?php
/* CoursesDay Test cases generated on: 2010-10-25 06:10:16 : 1288002436*/
App::import('Model', 'CoursesDay');

class CoursesDayTestCase extends CakeTestCase {
	var $fixtures = array('app.courses_day', 'app.course', 'app.day', 'app.schedule', 'app.schedules_course');

	function startTest() {
		$this->CoursesDay =& ClassRegistry::init('CoursesDay');
	}

	function endTest() {
		unset($this->CoursesDay);
		ClassRegistry::flush();
	}

}
?>