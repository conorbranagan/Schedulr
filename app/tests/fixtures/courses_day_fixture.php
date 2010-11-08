<?php
/* CoursesDay Fixture generated on: 2010-10-25 06:10:16 : 1288002436 */
class CoursesDayFixture extends CakeTestFixture {
	var $name = 'CoursesDay';

	var $fields = array(
		'course_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'day_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('course_id', 'day_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'course_id' => 1,
			'day_id' => 1
		),
	);
}
?>