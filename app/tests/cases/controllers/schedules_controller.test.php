<?php
/* Schedules Test cases generated on: 2010-10-25 06:10:43 : 1288002103*/
App::import('Controller', 'Schedules');

class TestSchedulesController extends SchedulesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SchedulesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.schedule', 'app.course', 'app.day', 'app.courses_day', 'app.schedules_course');

	function startTest() {
		$this->Schedules =& new TestSchedulesController();
		$this->Schedules->constructClasses();
	}

	function endTest() {
		unset($this->Schedules);
		ClassRegistry::flush();
	}

}
?>