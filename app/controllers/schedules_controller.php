<?php
class SchedulesController extends AppController {
	var $helpers = array('Form');
	var $components = array('RequestHandler');
	var $name = 'Schedules';


	function add($uid = null) {
		// Generate the options for credits
		$credit_options = array();
		for($i = 0; $i<= 30; $i++) {
			$credit_options [] = $i;
		}
		$this->set('credit_options', $credit_options);
		
		$this->set('title_for_layout', 'Create A New Schedulr');
		if($uid != null) {
			// We are "forking" a schedule - we must load the data from the database first
			$my_schedule = $this->Schedule->find('first', array('conditions' => array('Schedule.code' => $uid)));
			if($my_schedule == null) {
				// redirect to main add page - just to keep things clean.
			}
			$this->set('is_fork', true); 
			$this->set('courses', $my_schedule['Course']);
		} else {
			$this->set('is_fork', false);
		}	
	}
	
	function save() {
		if($this->RequestHandler->isPost()) {
			
			// Generate "code"/key for this schedule - check for duplicates
			do {
				$code = $this->_encode_base62(rand(1, 1000000));
				$results = $this->Schedule->find('count', array('conditions' => array('Schedule.code' => $code)));
			} while($results != 0);
			
			$all_courses = array();
			$title = $this->params['form']['title'];
			$total_credits = $this->params['form']['total_credits'];
			foreach($this->params['form']['classes'] as $class) {
				$course = json_decode($class);
				if($course) {
					$course_model = array();
					$course_model['name'] = $course->myCourseName;
					$course_model['credits'] = $course->myCourseCredits;
					$course_model['start_time'] = $this->_convertTime($course->myStartTime);
					$course_model['end_time'] = $this->_convertTime($course->myEndTime);
					$course_model['day_string'] = $course->myDayString;
					$course_model['color'] = $course->myColor;
					$all_courses [] = $course_model;
				}
			}
			$schedule_model = array('Schedule' => array('code' => $code, 'title' => $title, 'credits' => $total_credits), 
										'Course' => $all_courses);
			$schedule = $this->Schedule->saveAll($schedule_model, array('validate' => true, 'atomic' => true));
			
			$this->set('response', $code);
		} else {
			$this->set('response', "You did something wrong.");
		}
	}
	
	function saved($uid = null) {
		$options = array(
			'name' => 'Not found',
			'code' => 404,
			'message' => "/",
			'base' => $this->base
		);
		if($uid == null) {
			$this->cakeError('error', $options);
		}
		$this_schedule = $this->Schedule->find('first', array('conditions' => array('Schedule.code' => $uid)));
		if(!$this_schedule) {
			$options['message'] = "/" . $uid;
			$this->cakeError('error', $options);
		}
		
		$class_divs = array();
		foreach($this_schedule['Course'] as $course) {
			for($i = 0; $i < strlen($course['day_string']); $i++) {
				$class_divs [] = $this->_generate_class_div($course, substr($course['day_string'], $i, 1), 157);
			}
		}
		$this->set('title_for_layout', $this_schedule['Schedule']['title']);		
		$this->set('current_url',$this->_current_URL());
		$this->set('class_divs', $class_divs);
		$this->set('title', $this_schedule['Schedule']['title']);
		$this->set('code', $uid);
		$this->set('total_credits', $this_schedule['Schedule']['credits']);
	}
	
	
	/*
		Helper Methods
	*/
	
	function _generate_class_div($course, $day, $top_delta) {
		$TOP_DELTA = $top_delta;
		$MINUTE_HEIGHT = (45 / 60);
		$TIME_DELTA = 7 * 60;

		// Calculate the start time in minutes
		$start_hour = date('g', strtotime($course['start_time']));
		$start_minute = date('i', strtotime($course['start_time']));
		$start_meridian = date('a', strtotime($course['start_time']));
		
		if($start_meridian == 'am' && $start_hour == 12) {
			$start_hour = 0;
		} else if($start_meridian == 'pm' && $start_hour == 12) {
			$start_hour = 12;
		} else if($start_meridian == 'pm') {
			$start_hour += 12;
		}		
		$start_minutes = $start_hour * 60 + $start_minute - $TIME_DELTA;

		// Do the same for the end time
		$end_hour = date('g', strtotime($course['end_time']));
		$end_minute = date('i', strtotime($course['end_time']));
		$end_meridian = date('a', strtotime($course['end_time']));
		
		if($end_meridian == 'am' && $end_hour == 12) {
			$end_hour = 0;
		} else if($end_meridian == 'pm' && $end_hour == 12) {
			$end_hour = 12;
		} else if($end_meridian == 'pm') {
			$end_hour += 12;
		}		
		$end_minutes = $end_hour * 60 + $end_minute - $TIME_DELTA;
		
		// Calculate height
		$height = ($end_minutes - $start_minutes) * $MINUTE_HEIGHT + 1;
		// Calculate top
		$top = $TOP_DELTA + ($start_minutes * $MINUTE_HEIGHT) + ($start_hour - 8);
		$color = $course['color'];
		$div = '<div class = "class ' . $this->_day_string($day) . '" style = "top: ' . $top . 'px; height: ' . $height . 'px; background-color:' . $color . '">';
		$div .= '<p>' . $course['name'] . '<br />' . $course['credits'] . ' Credits<br />';
		$div .= $this->_pretty_time($course['start_time']) . ' - ' . $this->_pretty_time($course['end_time']) . '</p>';
		$div .= '</div>';
		
		return $div;
	}
	
	
	function _pretty_time($time) {
		return date("g:ia", strtotime($time));
	}
	
	function _day_string($day) {
		switch($day) {
			case 'M': return 'monday';
			case 'T': return 'tuesday';
			case 'W': return 'wednesday';
			case 'R': return 'thursday';
			case 'F': return 'friday';
			case 'S': return 'saturday';
			case 'U': return 'sunday';
		}
		return 'monday';
	}
	
	function _encode_base62($val, $base=62, $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    	// can't handle numbers larger than 2^31-1 = 2147483647
    	$str = '';
    	do {
        	$i = $val % $base;
        	$str = $chars[$i] . $str;
       		$val = ($val - $i) / $base;
    	} while($val > 0);
    	return $str;
	}
	
	function _convertTime($time) {
		return date("H:i:s", strtotime($time));
	}
	
	function _current_URL() {
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
		
}
	
?>