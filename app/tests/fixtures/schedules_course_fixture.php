<?php
/* SchedulesCourse Fixture generated on: 2010-10-25 06:10:17 : 1288002437 */
class SchedulesCourseFixture extends CakeTestFixture {
	var $name = 'SchedulesCourse';

	var $fields = array(
		'schedule_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('schedule_id', 'course_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'schedule_id' => 1,
			'course_id' => 1
		),
	);
}
?>