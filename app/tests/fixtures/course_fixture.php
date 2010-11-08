<?php
/* Course Fixture generated on: 2010-10-25 06:10:16 : 1288002436 */
class CourseFixture extends CakeTestFixture {
	var $name = 'Course';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'credits' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'start_time' => array('type' => 'time', 'null' => false, 'default' => NULL),
		'end_time' => array('type' => 'time', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'credits' => 1,
			'start_time' => '06:27:16',
			'end_time' => '06:27:16'
		),
	);
}
?>