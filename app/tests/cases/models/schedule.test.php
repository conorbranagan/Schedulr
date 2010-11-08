<?php
/* Schedule Test cases generated on: 2010-10-25 06:10:17 : 1288002437*/
App::import('Model', 'Schedule');

class ScheduleTestCase extends CakeTestCase {
	var $fixtures = array('app.schedule', 'app.course', 'app.day', 'app.courses_day', 'app.schedules_course');

	function startTest() {
		$this->Schedule =& ClassRegistry::init('Schedule');
	}

	function endTest() {
		unset($this->Schedule);
		ClassRegistry::flush();
	}

}
?>