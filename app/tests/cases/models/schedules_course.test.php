<?php
/* SchedulesCourse Test cases generated on: 2010-10-25 06:10:18 : 1288002438*/
App::import('Model', 'SchedulesCourse');

class SchedulesCourseTestCase extends CakeTestCase {
	var $fixtures = array('app.schedules_course', 'app.schedule', 'app.course', 'app.day', 'app.courses_day');

	function startTest() {
		$this->SchedulesCourse =& ClassRegistry::init('SchedulesCourse');
	}

	function endTest() {
		unset($this->SchedulesCourse);
		ClassRegistry::flush();
	}

}
?>