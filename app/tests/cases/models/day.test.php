<?php
/* Day Test cases generated on: 2010-10-25 06:10:17 : 1288002437*/
App::import('Model', 'Day');

class DayTestCase extends CakeTestCase {
	var $fixtures = array('app.day', 'app.course', 'app.courses_day', 'app.schedule', 'app.schedules_course');

	function startTest() {
		$this->Day =& ClassRegistry::init('Day');
	}

	function endTest() {
		unset($this->Day);
		ClassRegistry::flush();
	}

}
?>